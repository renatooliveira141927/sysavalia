<?php
/**
 * Created by PhpStorm.
 * User: Renato Oliveira
 * Date: 28/10/2017
 * Time: 16:17
 * @property Parametros_model $parametros_model
 */

class Parametros extends MY_Controller
{
	private $transacao = "manter_parametros";

	function index(){
        addJS(array('views/parametros', 'jquery.maskedinput.min'));        
        $this->load->model('parametros_model');

        if($this->input->post()){
            if($this->validar()){
                $this->salvar();
            }else{
                send_alert(validation_errors());
                redirect(base_url('parametros'));
            }
        }else{
        	$this->buscar();
        }
    }

    function buscar(){
    	$page = isset($_GET['page']) ? $this->input->get('page', TRUE) : 1;

    	$data['breadcrumbs'] = $this->view_breadcrumbs(
                $page, "Parâmetros",
                array('Parametros', 'Pesquisa', 'Pagina ' . $page),
                'parametros/novo', 'list-alt', false, isCreate($this->transacao)
            );
    	 $this->load_view('parametros_view', $data);
    }


}