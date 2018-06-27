<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recuperar extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("usuario_model");
	}

	public function index()
	{
		$email = $this->input->post('email');
		preg_match_all("/[0-9]+/", $this->input->post('cpf'), $out);
		$_POST['cpf'] = implode('',$out[0]);		
		$user = $this->usuario_model->check_user();		
		if($this->envia_email($email, $user)){
			$this->load_view('recuperar-senha',null,true);
		}
	}
}
