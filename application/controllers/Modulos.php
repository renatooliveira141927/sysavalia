<?php
/**
 * Created by PhpStorm.
 * User: Paulo Roberto
 * Date: 11/11/2017
 * Time: 11:26
 * @property Modulos_model $modulos_model
 * @property Cursos_model $cursos_model
 */

class Modulos extends MY_Controller
{
    private $transacao = "manter_modulos";

    public function __construct(){
        parent::__construct();
        if (!$this->is_logged()) {
            $this->lang->load('project');
            send_alert($this->lang->line('restrict_access'), 5000, 'warning');
            redirect(base_url());
        }

        addJS(
            array("jquery.mask.min", "views/modulos",
                    "plugins/jsKnob/jquery.knob",
                    "bootstrap-typeahead.min",
                    "plugins/bootstrap-tagsinput/bootstrap-tagsinput",
                    "plugins/sweetalert/sweetalert.min",
                    "plugins/pace/pace.min",
                    "plugins/validate/jquery.validate.min"

            )
        );
        addCSS(
            array("plugins/iCheck/custom",
                    "plugins/bootstrap-tagsinput/bootstrap-tagsinput",
                    "plugins/sweetalert/sweetalert",
                    "plugins/toastr/toastr.min",
                    "animate"
                )
        );

        $this->load->model("modulos_model");
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
            $rs = $this->modulos_model->get_cursos_by_filter($_GET,$page);

            $descrRows = "";
            if ($rs->num_rows > 1) $descrRows = $rs->num_rows . " registros";
            elseif ($rs->num_rows == 0) $descrRows = 'Nenhum registro encontrado';
            else $descrRows = $rs->num_rows . " registro encontrado";

            $data = array();

            //Populando combox eixos
            $data['eixos'] = $this->cursos_model->get_eixos();

            $data['transacao'] = $this->transacao;
            $data['find'] = $rs->result;
            $data['descrRows'] = $descrRows;

            $data['breadcrumbs'] = $this->view_breadcrumbs(
                $page, "Modulos",
                array('Modulos', 'Pesquisa', 'Pagina ' . $page),
                'modulos/novo', 'list-alt', false, false
            );

            $this->load->library('pagination');
            $config['base_url'] = base_url('modulos/buscar/');
            $config['total_rows'] = $rs->num_rows;
            $this->pagination->initialize($config);

            $this->load_view('academico/modulos_view.php', $data);
        }else{
            send_alert($this->lang->line('restrict_access'));
            redirect(base_url());
        }
    }

    public function editar($id)
    {
        if (isUpdate($this->transacao)) {
            $data['btn'] = 'ALTERAR';
            $data['icon_btn'] = 'fa-save';
            $data['url'] = base_url("modulos/partials");

            //Migalha de pão
            $data['breadcrumbs'] = $this->view_breadcrumbs(
                null, "Modulos",
                array('Modulos', 'Editar'),
                'modulos', 'list-alt',
                true
            );

            $data['form_curso'] = $this->cursos_model->get_id($id);

            $rs = $this->modulos_model->get_modulos($id);
            $data['form'] = $rs->result;

            $this->load_view('academico/modulos.php', $data);
        }else {
            send_alert($this->lang->line('restrict_access'), null, 'warning');
            redirect(base_url('cursos'));
        }
    }

    public function partials($function = null){
         if ($function == "addmodulo") {
            $term = (Object) $this->input->post();
            $result = $this->modulos_model->save_modulo($term);

            echo json_encode($result);
        }

        if($function == "remove_modulo"){
            $term = (Object) $this->input->post();
            $result = $this->modulos_model->remove_modulo($term);
            echo json_encode($result);
        }

        if($function == "load_disciplina"){
            $term = (Object) $this->input->post();
            $result = $this->modulos_model->get_disciplinas_by_modulo($term);
            echo json_encode($result);
        }

        if($function == "get_disciplina"){
            $term = (Object) $this->input->get();
            $result = $this->modulos_model->get_disciplinas($term);
            echo json_encode($result['result']);
        }

        if($function == "salvar_modulo_disciplina"){
            $term = (Object) $this->input->post();
            $result = $this->modulos_model->salvar_modulo_disciplina($term);
            echo json_encode($result);
        }

    }
}