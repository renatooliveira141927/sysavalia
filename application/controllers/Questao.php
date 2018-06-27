<?php
/**
 * Created by PhpStorm.
 * User: Renato
 * Date: 24/06/2018
 * Time: 10:27
 */

class Questao extends MY_Controller
{
    private $transacao = "manter_questao";

    public function __construct()
    {
        parent::__construct();
        if (!$this->is_logged()) {
            $this->lang->load('project');
            send_alert($this->lang->line('restrict_access'), 5000, 'warning');
            redirect(base_url());
        }
        addCSS(array("plugins/iCheck/custom",'editor'));
        addJS(array("jquery.mask.min",'editor', 'views/questao', "plugins/iCheck/icheck.min"));
        $this->load->model("questao_model");
        $this->load->model("disciplina_model");
        $this->load->model("ofertaEnsino_model");

    }

    public function index(){
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
                $page, "Questão",
                array('Questão', 'Pesquisa', 'Pagina ' . $page),
                'questao/novo', 'list-alt', false, isCreate($this->transacao)
            );
            $this->load_view('avalia/questao_view', $data);
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
                if($this->salvar($post)){
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
                null,"Questão",
                array('Questão','Novo'),
                'questao', 'list-alt',
                true
            );

            $this->load_view('avalia/questao.php', $data);
        }else{
            send_alert($this->lang->line('restrict_access'), null, 'warning');
            redirect(base_url('avaliacao'));
        }
    }

    public function salvar($post){
        trace($post);
        die;
    }

}