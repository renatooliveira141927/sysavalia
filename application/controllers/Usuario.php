<?php
/**
 * Created by PhpStorm.
 * User: paulo.roberto
 * Date: 06/05/2015
 * Time: 09:04
 */

class Usuario extends MY_Controller{
    private $transacao = "usuarios";

    public function __construct(){
        parent::__construct();
        if (!$this->is_logged()) {
            $this->lang->load('project');
            send_alert($this->lang->line('restrict_access'), 5000, 'warning');
            redirect(base_url());
        }        
        addJS(array("jquery.maskedinput.min", 'views/usuario'));
        $this->load->model("usuario_model");
        $this->load->model("grupo_model");
    }

    public function index(){
        if(isRead($this->transacao)) {
            redirect(base_url('usuario/page/1'));
        }else{
            send_alert($this->lang->line('restrict_access'), null, 'warning');
            redirect(base_url());
        }
    }

    public function page($page = 1){
        if(isRead($this->transacao)) {
            $this->load->library('pagination');
			$page = isset($_GET['page']) ? $_GET['page'] : 1;

            $limit = $this->pagination->per_page;
            $offset = ($page-1)*$limit;
            $data['count_results'] = $this->usuario_model->count_results();
            $data['usuarios'] = $this->usuario_model->search($limit, $offset);
            $data["transacao"] = $this->transacao;
            $data["search"] = $this->input->get();

            $data['breadcrumbs'] = $this->view_breadcrumbs(
                $page,"Usuários",
                array('Usuário','Pesquisa','Pagina '.$page),
                'usuario/novo', 'users'
            );

            $config['base_url'] = base_url('usuario/page/');
            $config['total_rows'] = $data['count_results'];
            $this->pagination->initialize($config);
            $this->load_view('acesso/usuarios-view', $data);
			
        }else{
            send_alert($this->lang->line('restrict_access'), null, 'warning');
            redirect(base_url());
        }
    }

    public function load_busca($find,$search=""){
        $registros = $this->usuario_model->countPagination;

        if($registros > 1) $descrRows = $registros." registros";
        elseif($registros == 0) $descrRows = 'Nenhum registro encontrado';
        else $descrRows = $registros." registro encontrado";

        $data["transacao"] = $this->transacao;
        $data["find"] = $find;
        $data["descrRows"] = $descrRows;
        $data["search"] = $search;

        $this->load->library('pagination');
        $config['base_url'] = base_url('usuario/buscar/page/');
        $config['total_rows'] = $registros;
        $this->pagination->initialize($config);
        $this->load_view('acesso/usuarios-view', $data);
    }

    public function editar(){
        if($this->input->post()){
            if($this->validar()){
                $this->alterar();
            }else{
                send_alert(validation_errors(), null, 'danger');
            }
        }
        $this->load->helper(array('form', 'url'));
        if(isUpdate($this->transacao)) {
            $id = $this->uri->segment(3);
            $edit = $this->usuario_model->find_by_id($id);
            $grupoDisponivel = $this->grupo_model->get_grupo_disponivel($id);
            $grupoUtilizado = $this->grupo_model->get_grupo_utilizado($id);
            $data = Array(
                "rowEdit" => $edit,
                "queryGruposDisponiveis" => $grupoDisponivel,
                "queryGruposUtilizados" => $grupoUtilizado,
                "url" => base_url('usuario/alterar/' . $edit->ci_usuario),
                "urlPartial" => '../partials/unidade_trabalho',
                "breadcrumbs" => $this->view_breadcrumbs(
                    null,"Usuários",
                    array('Usuário','Editar'),
                    'usuario', 'users',
                    true
                )
            );
            
            $this->load_view('acesso/usuarios', $data);
        }else{
            send_alert($this->lang->line('restrict_access'), null, 'warning');
            redirect(base_url('usuario/page'));
        }
    }

    public function novo(){

        if($this->input->post()){
            if($this->validar()){
                $this->salvar();
            }else{
                send_alert(validation_errors());
            }
        }
        $this->load->helper(array('form', 'url'));
        if(isCreate($this->transacao)) {
            $grupoDisponivel = $this->grupo_model->get_grupo_disponiveis_new();
            $data = Array(
                "queryGruposDisponiveis" => $grupoDisponivel,
                "url" => base_url('usuario/salvar'),
                "urlPartial" => 'partials/unidade_trabalho'
            );

            $data['breadcrumbs'] = $this->view_breadcrumbs(
                null,"Usuários",
                array('Usuário','Novo'),
                'usuario', 'users',
                true
            );
            $this->load_view('acesso/usuarios', $data);
        }else{
            send_alert($this->lang->line('restrict_access'), null, 'warning');
            redirect(base_url('usuario/page/1'));
        }
    }

    public function partials(){
        $action = $this->uri->segment(3);

        //Ajax para buscar as unidades de trabalho
        if($action == 'unidade_trabalho'){
            $term = $this->input->get('term', TRUE);
            $result = "";
            $result = $this->usuario_model->get_unidade_trabalho($term);
            echo $result;
        }
    }

    public function validar(){
        $this->load->library('form_validation');

        $this->form_validation->set_rules('cd_unidade', 'Unidade de Trabalho', 'required');
        $this->form_validation->set_rules('login', 'Login', 'required|min_length[3]');
        $this->form_validation->set_rules('nome', 'Nome completo', 'required|min_length[3]');
        $this->form_validation->set_rules('cpf', 'CPF', 'required');
        $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
        $this->form_validation->set_rules('nascimento', 'Data de nascimento', 'required');
        $this->form_validation->set_rules('sexo', 'Sexo', 'required');

        return $this->form_validation->run();
    }

    public function salvar(){
        if(isCreate($this->transacao)) {
            $valid = $this->usuario_model->insert();
            if ($valid["valid"]) {
                $cd_grupo_select = $this->input->post('grupo_select');
                $valid_gruop = $this->grupo_model->save_gruop_users($cd_grupo_select, $valid["ci_usuario"]);
                
                $nm_login = addslashes(strtoupper($this->input->post('login')));
                $ds_email = addslashes($this->input->post('email'));
                $user = (Object) array("ci_usuario"=>$valid["ci_usuario"], "nm_login"=>$nm_login);
                
                $validEmail = $this->send_password($ds_email, $user, "Primeiro acesso");
                
                if ($validEmail->fl_envio) {
                    send_alert($validEmail->mensagem, 9000, 'success');
                }else{
                	send_alert($validEmail->mensagem);
                }
                redirect(base_url('usuario/page/1'));
            } else {
                send_alert($valid["messeger"]);
               // $this->load_view("acesso/usuarios");
            }
        }else{
            send_alert($this->lang->line('restrict_access'), null, 'warning');
            redirect(base_url('usuario/page/1'));
        }
    }

    public function alterar(){
        if(isUpdate($this->transacao)) {
            $id = $this->uri->segment(3);
            $rsUpdate = $this->usuario_model->update($id);
            if ($rsUpdate->valid) {
                $cd_grupo_select = $this->input->post('grupo_select');
                $valid_gruop = $this->grupo_model->save_gruop_users($cd_grupo_select, $id);
                if ($valid_gruop) {
                    send_alert("Salvo com sucesso!",  90000, 'success');
                    redirect(base_url('usuario/page/1'));

                }
            } else {
                send_alert($rsUpdate->messeger);
               // $this->load_view("acesso/usuarios");
            }
        }else{
            send_alert($this->lang->line('restrict_access'), 'warning');
            redirect(base_url('usuario'));
        }
    }
}