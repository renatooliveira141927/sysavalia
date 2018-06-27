<?php
/**
 * Created by PhpStorm.
 * User: Renato
 * Date: 23/06/2018
 * Time: 19:27
 * @property Descritor_model $descritor_model
 */

class Descritor extends MY_Controller
{

    public function __construct(){
        parent::__construct();
        if (!$this->is_logged()) {
            $this->lang->load('project');
            send_alert($this->lang->line('restrict_access'), 5000, 'warning');
            redirect(base_url());
        }
        addCSS(array("plugins/iCheck/custom"));
        addJS(array("jquery.mask.min", 'views/curso', "plugins/iCheck/icheck.min"));
        $this->load->model("descritor_model");
    }

    function busca_descritores(){

        $param['disciplina']=$this->input->post('disciplina');

        $desc = $this->descritor_model->get_descritores($param);

        echo '<option value="0">Selecione</option>';

        foreach($desc as $rem) {

            echo '<option value="' . $rem->ci_descritor. '">' . $rem->nm_descritor. '</option>';
        }
    }
}