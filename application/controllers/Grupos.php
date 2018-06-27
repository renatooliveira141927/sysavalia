<?php
/**
 * Created by PhpStorm.
 * User: paulo.roberto
 * Date: 14/05/2015
 * Time: 09:34
 * @property Grupo_model grupo_model
 * @property Transacao_model transacao_model
 */

class Grupos extends MY_Controller{
    private $limitPagina = 10;
    private $transacao = "manter_grupo_de_usuario";
    private $niveis;

    public function __construct(){
        parent::__construct();
       
        if (!$this->is_logged()) {
            $this->lang->load('project');
            send_alert($this->lang->line('restrict_access'), 5000, 'warning');
            redirect(base_url());
        }
        addJS('views/grupo');
        $this->load->model('transacao_model');
        $this->load->model('grupo_model');
        $this->niveis = $this->config->config['NIVEIS'];
    }


    public function index(){
        if(isRead($this->transacao)) {
            $this->buscar();
        }else{
            send_alert($this->lang->line('restrict_access'), null, 'warning');
            redirect(base_url());
        }
    }

    public function buscar($page = 1){
        if(isRead($this->transacao)) {            
            $rs = $this->grupo_model->get_grupo_by_filter($_POST ,$page);
            $descrRows = "";
            
            if($rs->num_rows > 1) $descrRows = $rs->num_rows." registros";
            elseif($rs->num_rows == 0) $descrRows = 'Nenhum registro encontrado';
            else $descrRows = $rs->num_rows." registro encontrado";


            $data = array("transacao" => $this->transacao,
                            'find' => $rs->result,
                            'descrRows' =>$descrRows,
                            'niveis'=>$this->niveis);

            $data['breadcrumbs'] = $this->view_breadcrumbs(
                $page,"Grupos",
                array('Grupos','Pesquisa','Pagina '.$page),
                'grupos/novo', 'users'
            );

            $this->load->library('pagination');
            $config['base_url'] = base_url('grupos/buscar/');
            $config['total_rows'] = $rs->num_rows;
            $this->pagination->initialize($config);

            $this->load_view('acesso/grupos_view',$data);
        }else{
            send_alert($this->lang->line('restrict_access'), null, 'warning');
            redirect(base_url());
        }
    }

    public function editar($ci_grupo)
    {
        if ($this->input->post()) {
            if ($this->validar()) {
                $this->commit($ci_grupo);
            } else {
                send_alert(validation_errors());
            }
        }
        $this->load->helper(array('form', 'url'));
        if(isUpdate($this->transacao)) {
            $rs = $this->grupo_model->get($ci_grupo);
            $rsTransacoes = $this->transacao_model->get_transacoes_grupo($ci_grupo);
            
            $data = array(
                'rowEdit' => $rs,
                "niveis" => $this->niveis,
                "queryPossiveis" => $rsTransacoes->transacoes_possiveis,
                "querySelecionadas" => $rsTransacoes->transacoes_pertencentes
            );

            $data['breadcrumbs'] = $this->view_breadcrumbs(
                null,"Grupos",
                array('Grupos','Editar'),
                'grupos', 'users',
                true
            );

            $this->load_view('acesso/grupos', $data);
        }else{
            send_alert($this->lang->line('restrict_access'), null, 'warning');
            redirect(base_url('grupos'));
        }
    }

    public function novo()
    {
        if ($this->input->post()) {
            if ($this->validar()) {
                $this->commit();
            } else {
                send_alert(validation_errors());
            }
        }
        $this->load->helper(array('form', 'url'));
        if(isCreate($this->transacao)) {
            $queryPossiveis = $this->transacao_model->get_transacoes();

            $data = array("niveis" => $this->niveis,
                          "queryPossiveis" => $queryPossiveis);

            $data['breadcrumbs'] = $this->view_breadcrumbs(
                null,"Grupos",
                array('Grupos','Novo'),
                'grupos', 'users',
                true
            );

            $this->load_view('acesso/grupos', $data);
        }else{
            send_alert($this->lang->line('restrict_access'), null, 'warning');
            redirect(base_url('grupos'));
        }
    }

    public function validar(){
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nm_grupo', 'Grupo', 'required|alpha_numeric|min_length[3]');
        $this->form_validation->set_rules('fl_nivel_acesso', 'Nível de Acesso', 'required|callback_valida_nivel');

        return $this->form_validation->run();
    }

    public function valida_nivel($str) {
        if ($str == '0') {
            $this->form_validation->set_message('valida_nivel', 'O campo {field} é obrigatório.');
            return false;
        }
        return true;
    }

    public function commit($id = null){
    	$user = (Object) array("nm_grupo"=>addslashes($this->input->post('nm_grupo')));
    	$valid = $this->grupo_model->get_grupo($user, $id);
        if($id == null){
            if(isCreate($this->transacao)) {
            	if($valid->num_rows == 0){
	                $result = $this->grupo_model->insert();
	                if ($result['valid']) {
                        send_alert($result['messeger'], null, 'success');
	                    redirect(base_url('grupos'));
	                } else {
                        send_alert($result['messeger']);
	                    redirect(base_url('grupos/novo'));
	                }
            	}else{
                    send_alert("Grupo já existente!");
            		redirect(base_url('grupos/novo'));
            	}
            }else{
                send_alert($this->lang->line('restrict_access'), null, 'warning');
                redirect(base_url('grupos'));
            }
        }else{
            if(isUpdate($this->transacao)) {
            	if($valid->num_rows == 0){
	                $result = $this->grupo_model->update($id);
	                if ($result['valid']) {
	                    send_alert($result['messeger'], null, 'success', '90000', 'exclamation-triangle');
	                    redirect(base_url('grupos'));
	                } else {
	                    send_alert($result['messeger'], 'danger', '90000', 'exclamation-triangle');
	                    redirect(base_url('grupos/editar/' . $id));
	                }
            	}else{
            		send_alert("Grupo já existente!", 'danger', '90000', 'exclamation-triangle');
            		redirect(base_url('grupos/editar/' . $id));
            	}
            }else{
                send_alert($this->lang->line('restrict_access'), 'warning');
                redirect(base_url('grupos'));
            }
        }
    }

    public function remove(){
        if(isDelete($this->transacao)) {
            $result = $this->grupo_model->remove();
            if ($result['valid'])
                send_alert($result['messeger'], null, 'success');
            else
               send_alert($result['messeger'], null, 'danger');

            redirect(base_url('grupos'));
        }else{
            send_alert($this->lang->line('restrict_access'), null, 'warning');
            redirect(base_url('grupos'));
        }
    }
}