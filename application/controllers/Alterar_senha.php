<?php
/*
 * @property Alterar_senha_model alterar_senha_model
 */
	class Alterar_senha extends MY_Controller{

		public function __construct(){
			parent::__construct();
            if (!$this->is_logged()) {
                $this->lang->load('project');
                send_alert($this->lang->line('restrict_access'), 5000, 'warning');
                redirect(base_url());
            }
            $this->load->model("alterar_senha_model");
			addJS(array('pStrength.jquery','views/alterarSenha'));
		}

		public function index(){
			addJS(array('pStrength.jquery','views/alterarSenha'));
			$data = Array('user'=>$this->session->userdata('user'));
			$this->load_view('acesso/alterar_senha', $data);
		}

		public function alterar(){	
			$data = Array('user'=>$this->session->userdata('user'));

			$ci_usuario = $this->input->post('ci_usuario');
			$senhaAtual = $this->input->post('nm_senha_atual');
			$newSenha = $this->input->post('nm_nova_senha');

			switch ($this->alterar_senha_model->alterar_senha($ci_usuario, $senhaAtual, $newSenha )){
				case 1:
					send_alert("Senha alterada com sucesso!", null, "success");
					break;
				case 3:
                    send_alert("Senha atual incorreta!");
					break;
				default:
                    send_alert("Ocorreu um erro ao tentar alterar a senha!");
					break;
			}
			redirect(base_url("alterar_senha"));
		}
	}
 ?>