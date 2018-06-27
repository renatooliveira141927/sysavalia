<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function index()
	{
		addJS(array('views/home', 'jquery.maskedinput.min'));
		//if($this->is_logged())
		    $this->load_view('home');
		//else redirect(base_url('login'));

	}
}
