<?php
/**
 * Created by PhpStorm.
 * User: Renato
 * Date: 22/06/2018
 * Time: 22:39
 */

class Avaliacao extends MY_Controller
{
    private $transacao = "manter_avaliacao";

    public function __construct()
    {
        parent::__construct();
        if (!$this->is_logged()) {
            $this->lang->load('project');
            send_alert($this->lang->line('restrict_access'), 5000, 'warning');
            redirect(base_url());
        }
        addCSS(array("plugins/iCheck/custom"));
        addJS(array("jquery.mask.min", 'views/avaliacao', "plugins/iCheck/icheck.min"));
        $this->load->model("avaliacao_model");
        $this->load->model("disciplina_model");
        $this->load->model("ofertaEnsino_model");
    }

    public function index()
    {
        if (isRead($this->transacao)) {
            $this->buscar();
        } else {
            send_alert($this->lang->line('restrict_access'));
            redirect(base_url());
        }
    }

    public function buscar()
    {
        $page = isset($_GET['page']) ? $this->input->get('page', TRUE) : 1;
        if (isRead($this->transacao)) {

            $data['breadcrumbs'] = $this->view_breadcrumbs(
                $page, "Avaliação",
                array('Avaliação', 'Pesquisa', 'Pagina ' . $page),
                'avaliacao/novo', 'list-alt', false, isCreate($this->transacao)
            );
            $this->load_view('avalia/avaliacao_view.php', $data);
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
            $data['disciplinas'] = $this->disciplina_model->get_disciplinas();
            $data['ofertaensino'] = $this->ofertaEnsino_model->get_ofertaensino();

            //Migalha de pão
            $data['breadcrumbs'] = $this->view_breadcrumbs(
                null,"Avaliação",
                array('Avaliação','Novo'),
                'avaliacao', 'list-alt',
                true
            );

            $this->load_view('avalia/avaliacao.php', $data);
        }else{
            send_alert($this->lang->line('restrict_access'), null, 'warning');
            redirect(base_url('avaliacao'));
        }
    }

    public function save($post){
        if(isCreate($this->transacao)) {
            trace($post);
            die;
            $result = (Object)$this->avaliacao_model->save($post);
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

        $this->form_validation->set_rules('oferta', 'Oferta', 'required');
        $this->form_validation->set_rules('disciplina', 'Disciplina', 'required|integer');
        $this->form_validation->set_rules('dt_inicio', 'Inicio', 'required|min_length[3]');
        $this->form_validation->set_rules('dt_fim', 'FIM', 'required|min_length[3]');

        return $this->form_validation->run();
    }
}