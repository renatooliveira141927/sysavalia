<?php
/**
 * ------------- EXTENDE AS FUNCIONALIDADES DOS CONTROLLERS ---------------------
 * Esta classe deve ser estendida por todos os controllers da aplicação
 * Ela fornece recursos extras para utilização pelos controllers
 * -------------------------------------------------------------------
 * Created 18/02/2015, 16:34
 *
 * @author     Paulo Roberto Mesquita da Silva
 * @copyright  Secretaria de Educação Básica do Estado do Ceará
 * @version    1.0
 *
 * @property Autenticacao        $autenticacao
 * @property Usuario_model       $usuario_model
 * @property Grupo_model         $grupo_model
 * @property Transacao_model     $transacao_model
 * @property Conselho_model      $conselho_model
 * @property Presidente_model  $presidente_model
 * @property Alterar_senha_model $alterar_senha_model
 */

class MY_Controller extends CI_Controller {

    var $data = array();
    function __construct() {
        parent::__construct();
        //$this->output->enable_profiler(TRUE);
    }

    /**
     * Carrega uma página (view) dentro do template do site
     * O template deve ser informado no arquivo config/cofig.php
     *
     * @see config/config.php   $config['html_template']
     * @param string $view      Página (view) que deverá ser exibida dentro do template
     * @param array $data       Dados que serão enviados para a view informada
     */
    protected function load_view($view, $data = NULL, $blank = false) {
        $data['view'] = $view;
        $data['params'] = $data;
        if($blank){
            $template = $this->config->item('html_template_blank');
        }else{
            $template = $this->config->item('html_template');
            
        }
        $this->load->view($template, $data);
    }

    /**
     * Carrega uma página (view) dentro do template do site
     * utilizando substituição dos textos por pseudo-variáveis
     * O template deve ser informado no arquivo config/cofig.php
     *
     * @see config/config.php   $config['html_template']
     * @param string $view      Página (view) que deverá ser exibida dentro do template
     */
    protected function load_parsed_view($view) {
        $this->load->library('parser');
        $this->data['view'] = $view;

        $template = $this->config->item('html_template');
        $this->parser->parse($template, (array) $this->data);
    }

    /**
     * Envia os dados que serão acessados pela view.
     * Ex.:
     *      $this->parse_data('chave', 'valor');
     *      $this->load_parsed_view('view');
     *
     * @param string $key       Nome da propridade que será acessada pela view
     * @param string $data      Valor da propriedade
     */
    protected function parse_data($key, $data){
        $this->data[$key] = $data;
    }


    /**
     * Informa se o usuário está logado
     *
     * @return bool     TRUE se logado, FALSE caso contrário
     */
    protected function is_logged() {
        $user_data = $this->session->userdata('user');
        if(empty($user_data)) return false;
        return true;
    }

    /**
     * Envia email de recuperação de Senha
     *
     * @return Object Usuario
     */

    public function envia_email($email, $user)
    {
        $this->load->model('usuario_model');

        if(NULL !== $user){
            //Gerando senha aleatória
            $senha = senhaAleatoria();

            $data['usuario'] = $user->nm_login;
            $data['senha'] = $senha;
            $data['assunto'] = "Recuperação de Senha";
            $mensagem = $this->load->view('templates/email', $data, true);

            // Lembrar de configurar corretamente o arquivo config/email.php e ativar no php.ini a extensão php_openssl.dll
            $this->load->library('email');
            $this->email->from('sigeescola@seduc.ce.gov.br', 'ASTIN - SEDUC/CE');
            $this->email->to($email);
            $this->email->subject('Recuperação de senha');
            $this->email->message($mensagem);
            $enviado = $this->email->send();

            if(!$enviado){
                $data['mensagem'] = 'Não foi possível enviar o email de recuperação. Tente novamente, se o problema persistir entre em contato com o suporte técnico.';
                $this->load_view('recuperar-erro', $data);
                return FALSE;
            }

            //Atualizando Senha no BD
            $result = $this->usuario_model->update_senha($senha,$user->ci_usuario);
            if(!$result)
            {
                $data['mensagem'] = 'Erro ao gerar senha. Tente novamente, se o problema persistir entre em contato com o suporte técnico.';
                $this->load_view('recuperar-erro', $data);
                return FALSE;
            }
            return $user;
        }
        $data['mensagem'] = 'Usuario não foi encontrado. Verifique os dados e tente novamente.';
        $this->load_view('recuperar-erro', $data);
        return FALSE;
    }

	public function send_password($email, $user, $assunto){
		$this->load->model('usuario_model');
		if(NULL !== $user){
			//Gerando senha aleatória
			$senha = senhaAleatoria();
		
			$data['usuario'] = $user->nm_login;
			$data['senha'] = $senha;
			$data['assunto'] = $assunto;
			$mensagem = $this->load->view('templates/email', $data, true);
		
			//Lembrar de configurar corretamente o arquivo config/email.php e ativar no php.ini a extensão php_openssl.dll
			$this->load->library('email');
			$this->email->from('sigeescola@seduc.ce.gov.br', 'ASTIN - SEDUC/CE');
			$this->email->to($email);
			$this->email->subject($assunto);
			$this->email->message($mensagem);
			$enviado = $this->email->send();
		
			if(!$enviado){
				$data['fl_envio'] = false;
				$data['mensagem'] = 'Não foi possível enviar o email. Por favor entrar em contato com administrador.';
				return FALSE;
			}else{
				//Atualizando Senha no BD
				$result = $this->usuario_model->update_senha($senha,$user->ci_usuario);
				if(!$result){
					$data['fl_envio'] = false;
					$data['mensagem'] = 'Erro ao gerar senha. Por favor entrar em contato com administrador.';
				}else{
					$data['fl_envio'] = true;
					$data['mensagem'] = 'A senha foi enviada para o E-mail <strong>'.$email.'</strong>';
				}
			}
		
			return (Object) $data;
		}
		$data['mensagem'] = 'Usuario não foi encontrado. Verifique os dados e tente novamente.';
		$this->load_view('recuperar-erro', $data);
		return FALSE;
	}

    /**
     * Testa o login e cria a sessão do usário no sistema
     * Modificar no "Autenticacao" a regra da consulta que dá a permissão de acesso
     */
    public function do_login(){
        //Carregar Models
        $this->load->model('usuario_model');
        $this->load->model('grupo_model');
        $this->load->model('transacao_model');

        //Parametros enviados pelo login.
        $login = strtoupper(addslashes($this->input->post('login')));
        $pass = addslashes($this->input->post('senha'));


        //Verificar usuario existente (Lista (Array) para verificar se existe pelo menos um usuario para aquele login).
        $usuario = $this->usuario_model->get_user_by_login($login);
        if($usuario != NULL ){
            if($usuario->nm_senha == md5($pass) || $usuario->nm_senha == md5(strtoupper($pass))){

                //Função responsável pela verificação do mandato de cada conselho.
              //  $this->transacao_model->mandato_conselho();

                //Ajustando as variáveis que serão gravadas na sessão ($user, $grupos e $transacoes)
                $atualizousenha =$usuario->fl_atualizousenha;
                unset($usuario->nm_senha); //Excluindo a chave nm_senha para não ser gravada na sessão
                unset($usuario->fl_atualizousenha); //Excluindo a chave fl_atualizousenha para não ser gravada na sessão
                $usuario->ismaster = FALSE;
                $usuario->ultimo_acesso = '---';
                $listaGrupos = '';

                $grupos = $this->grupo_model->get_grupo_by_user($usuario->ci_usuario);

                if($grupos != NULL ){
                    //Caso o usuário seja um membro MASTER, terá acesso a todas as transações do módulo.
                    //Poderá ocorrer casos especiais, onde, terá de ser testado se o usuário tem vínculo
                    //em um grupo específico. Isso acontece, por exemplo, em um "encaminhamento de estagiários",
                    //que somente poderá ser efetuado se o usuário estiver vinculado a um grupo específico, chamado "orientador-aprovado". Considerando assim,
                    //o grupo como uma label de transação. Torna-se necessária então que se mantenha na sessão
                    //todos os grupos do usuário na sessão exceto o MASTER.
                    $max_nivel_acesso = 0;
                    foreach ($grupos as $key=>$row){
                        if($this->config->item('MASTER') == $row->fl_nivel_acesso){
                            $usuario->ismaster = TRUE;
                        }
                        else{
                            $listaGrupos .= $row->nm_grupo.',';
                            if($key == 0 || $row->fl_nivel_acesso > $max_nivel_acesso){
                            	$max_nivel_acesso = $row->fl_nivel_acesso;
                        }
                    }
                    }
                    
                    $listaGrupos = substr($listaGrupos, 0, -1);
                    $usuario->max_nivel_acesso = $row->fl_nivel_acesso;

                    //Seleciona o privilégio detalhado de todas as transações deste usuário, neste módulo.
                    $transacoes = array();
                    $list_transacoes = $this->transacao_model->get_transacao_by_user($usuario->ci_usuario);
                    foreach ($list_transacoes as $row){
                        if(array_key_exists($row['nm_label'], $transacoes)){
                            $transacoes[$row['nm_label']] = atualizaPriv($transacoes[$row['nm_label']], $row['fl_inserir'], "S", $row['fl_alterar'], $row['fl_deletar']);
                        }
                        else{
                            $transacoes[$row['nm_label']] = priv($row['fl_inserir'], "S", $row['fl_alterar'], $row['fl_deletar']);
                        }
                    }

                    $row_ultimo_acesso = $this->usuario_model->get_last_acess_by_user($usuario->ci_usuario);
                    if(@$row_ultimo_acesso['dt_inicio']){
                        $usuario->ultimo_acesso = $row_ultimo_acesso['dt_inicio'];
                    }

                    //Inserindo o acesso atual
                    $this->usuario_model->insert_last_acess($usuario->ci_usuario);

                    //Nivel do Usuário
                    if($usuario->cd_tpunidade_trabalho == 3) $this->session->set_userdata('isSeduc', TRUE);
                    elseif($usuario->cd_tpunidade_trabalho == 2) $this->session->set_userdata('isCrede', TRUE);
                    elseif($usuario->cd_tpunidade_trabalho == 1) $this->session->set_userdata('isEscola', TRUE);
                    elseif($usuario->cd_tpunidade_trabalho == 5) $this->session->set_userdata('isMunicipio', TRUE);

                    //Gravando na sessão
                    $this->session->set_userdata('user', $usuario);
                    $this->session->set_userdata('grupos', $listaGrupos);
                    $this->session->set_userdata('transacoes', $transacoes);

                   //$anoLetivo = $this->transacao_model->anoletivo();
                   //$this->session->set_userdata('anoletivo', $anoLetivo->ano);

                    //Redirencionando para alteração da senha caso seja o primeiro acesso
                    if($atualizousenha == 'N'){
                        //redirect(base_url('alterar_senha'));
                        return TRUE;
                    }
                    else{
                        return TRUE;
                    }
                }else{
                    send_alert("Você não tem permissão para acessar este sistema!");
                    return FALSE;
                }

            }else{
                send_alert("Usuário ou Senha incorretos!");
                return FALSE;
            }
        }else{
            send_alert("Usuário ou Senha incorretos!");
            return FALSE;
        }
    }

    /**
     * Encerra a sessão do usuário
     */
    public function logout(){
        $this->session->sess_destroy();
        redirect(base_url());
    }

    public function view_breadcrumbs($page=null, $title, $list, $link, $icon, $btnBack=false, $btnRender=true){
        //Breadcrumbs
        $breadcrumbs["title_page"] = $title;
        $breadcrumbs['list'] = $list;
        $breadcrumbs['link'] = $link;
        $breadcrumbs['icon'] = $icon;
        $breadcrumbs['btn_back'] = $btnBack;
        $breadcrumbs['btn_render'] = $btnRender;

        return $breadcrumbs;
    }

}