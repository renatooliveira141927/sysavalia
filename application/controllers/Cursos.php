<?php
/**
 * Created by PhpStorm.
 * User: Paulo Roberto
 * Date: 03/11/2017
 * Time: 14:55
 * @property Cursos_model $cursos_model
 */

class Cursos extends MY_Controller
{
    private $transacao = "manter_curso";

    public function __construct(){
        parent::__construct();
        if (!$this->is_logged()) {
            $this->lang->load('project');
            send_alert($this->lang->line('restrict_access'), 5000, 'warning');
            redirect(base_url());
        }
        addCSS(array("plugins/iCheck/custom"));
        addJS(array("jquery.mask.min", 'views/curso', "plugins/iCheck/icheck.min"));
        $this->load->model("cursos_model");
    }

    public function index()
    {
        if(isRead($this->transacao)) {
            $this->buscar();
        }else{
            send_alert($this->lang->line('restrict_access'));
            redirect(base_url());
        }
    }

    public function buscar()
    {
        $page = isset($_GET['page']) ? $this->input->get('page', TRUE) : 1;
        if(isRead($this->transacao)) {

            $rs = $this->cursos_model->get_cursos_by_filter($_GET, $page);

            $descrRows = "";
            if ($rs->num_rows > 1) $descrRows = $rs->num_rows . " registros";
            elseif ($rs->num_rows == 0) $descrRows = 'Nenhum registro encontrado';
            else $descrRows = $rs->num_rows . " registro encontrado";

            $data = array();
            $data['transacao'] = $this->transacao;
            $data['find'] = $rs->result;
            $data['descrRows'] = $descrRows;

            //Populando combox eixos
            $data['eixos'] = $this->cursos_model->get_eixos();

            $data['breadcrumbs'] = $this->view_breadcrumbs(
                $page, "Cursos",
                array('Curso', 'Pesquisa', 'Pagina ' . $page),
                'cursos/novo', 'list-alt', false, isCreate($this->transacao)
            );

            $this->load->library('pagination');
            $config['base_url'] = base_url('cursos/buscar/');
            $config['total_rows'] = $rs->num_rows;
            $this->pagination->initialize($config);

            $this->load_view('academico/curso_view.php', $data);
        }else{
            send_alert($this->lang->line('restrict_access'));
            redirect(base_url());
        }
    }

    public function novo(){
        if(isCreate($this->transacao)) {
            $data['painel_modulo'] = false;
            $data['btn'] = 'SALVAR';
            $data['icon_btn'] = 'fa-save';
            $post = $this->input->post();

            if($post){
                if($this->validar()){
                    $this->save($post);
                }else{
                    send_alert(validation_errors(), null, 'danger');
                    $data['form'] = (Object) $post;
                }
            }

            //Populando combox eixos
            $data['eixos'] = $this->cursos_model->get_eixos();

            //Migalha de pão
            $data['breadcrumbs'] = $this->view_breadcrumbs(
                null,"Cursos",
                array('Cursos','Novo'),
                'cursos', 'list-alt',
                true
            );

            $this->load_view('academico/cursos.php', $data);
        }else{
            send_alert($this->lang->line('restrict_access'), null, 'warning');
            redirect(base_url('cursos'));
        }
    }

    public function editar($id){
        if(isUpdate($this->transacao)) {
            $data['painel_modulo'] = true;
            $post = $this->input->post();
            if($post){
                if($this->validar()){
                    $this->alterar($id,$post);
                }else{
                    send_alert(validation_errors(), null, 'danger');
                }
            }

            $data['btn'] = 'ALTERAR';
            $data['icon_btn'] = 'fa-save';

            $data['form'] = $this->cursos_model->get_id($id);

            //Populando combox eixos
            $data['eixos'] = $this->cursos_model->get_eixos();

            //Migalha de pão
            $data['breadcrumbs'] = $this->view_breadcrumbs(
                null, "Cursos",
                array('Cursos', 'Editar'),
                'cursos', 'list-alt',
                true
            );

            $this->load_view('academico/cursos.php', $data);
        }else{
            send_alert($this->lang->line('restrict_access'), null, 'warning');
            redirect(base_url('cursos'));
        }

    }

    public function save($post){
        if(isCreate($this->transacao)) {
            $result = (Object)$this->cursos_model->save($post);
            if ($result->valid) {
                send_alert($result->messeger, 9000, 'success');
            } else {
                send_alert($result->messeger);
            }
            redirect(base_url('cursos'));
        }else{
            send_alert($this->lang->line('restrict_access'), null, 'warning');
            redirect(base_url('cursos'));
        }
    }


    public function alterar($id, $post){
        if(isCreate($this->transacao)) {
            $result = (Object)$this->cursos_model->alterar($id, $post);
            if ($result->valid) {
                send_alert($result->messeger, 9000, 'success');
            } else {
                send_alert($result->messeger);
            }
            redirect(base_url('cursos'));
        }else{
            send_alert($this->lang->line('restrict_access'), null, 'warning');
            redirect(base_url('cursos'));
        }
    }

    public function validar(){
        $this->load->library('form_validation');

        $this->form_validation->set_rules('curso', 'Curso', 'required');
        $this->form_validation->set_rules('eixos', 'Eixos', 'required|integer');
        $this->form_validation->set_rules('ementa', 'Ementa', 'required|min_length[3]');
        $this->form_validation->set_rules('objgeral', 'Objetivo Geral', 'required');
        $this->form_validation->set_rules('chpresencial', 'Carga Horária Presencial', 'required|integer');

        return $this->form_validation->run();
    }
}