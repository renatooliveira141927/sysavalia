<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

	public function index()
	{
        addJS(array('views/home', 'jquery.maskedinput.min'));
        $post = $this->input->post();
        if(!empty($post) && !$this->do_login()) {
            send_alert("Login ou senha inválidos", 8000);
            redirect(base_url('login'));
        }
        if($this->is_logged()) redirect(base_url('home'));
        else $this->load_view('login', null, true);
	}
}
