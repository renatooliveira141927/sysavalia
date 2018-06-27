<?php
/**
 * Created by PhpStorm.
 * User: Paulo Roberto
 * Date: 28/10/2017
 * Time: 16:17
 * @property Usuario_model $usuario_model
 */

class Registro extends MY_Controller
{
    function index(){
        addJS(array('views/registro', 'jquery.maskedinput.min'));
        $this->load_view('registro', null, true);
        $this->load->model('usuario_model');

        if($this->input->post()){
            if($this->validar()){
                $this->salvar();
            }else{
                send_alert(validation_errors());
                redirect(base_url('registro'));
            }
        }
    }

    function salvar(){
        $result = $this->usuario_model->registro();
        if ($result['valid']) {
            send_alert($result['messeger'], null, 'success');
            redirect(base_url('login'));
        } else {
            send_alert($result['messeger']);
            redirect(base_url('registro'));
        }
    }


    public function validar(){

        $this->form_validation->set_rules('nome', 'Nome', 'required|min_length[3]');
        $this->form_validation->set_rules('apelido', 'Apelidos', 'required|min_length[3]');
        $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
        $this->form_validation->set_rules('telefone', 'Telefone', 'required');
        $this->form_validation->set_rules('password', 'Senha', 'required');
        $this->form_validation->set_rules('confirm_password', 'Confima a senha', 'required|matches[password]');
        $this->form_validation->set_rules('concordo', 'Concordo com o termo.', 'required');

        return $this->form_validation->run();
    }
}