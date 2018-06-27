<?php
class  Transacao extends MY_Controller {
	private $limitPagina = 10;
	private $transacao = "manter_transacao";

	public function __construct(){
		parent::__construct();
        if (!$this->is_logged()) {
            $this->lang->load('project');
            send_alert($this->lang->line('restrict_access'), 5000, 'warning');
            redirect(base_url());
        }
		addJS('views/transacao');
		$this->load->model('transacao_model');
        $this->lang->load('project', 'portugues');
	}

	public function index(){
		if(isRead($this->transacao)) {
			$this->buscar();
		}else{
			send_alert($this->lang->line('restrict_access'));
			redirect(base_url());
		}
	}

	public function novo()
	{
		if ($this->input->post()) {
			if ($this->validar()) {
				$this->commit($this->input->post('ciTransacao'));
			} else {
				send_alert(validation_errors());
			}
		}
		$this->load->helper(array('form', 'url'));
		if(isCreate($this->transacao)) {
            $data['breadcrumbs'] = $this->view_breadcrumbs(
                null,"Transações",
                array('Transação','Novo'),
                'transacao', 'exchange',
                true
            );
			$this->load_view('acesso/transacao', $data);

		}else{
			send_alert($this->lang->line('restrict_access'));
			redirect(base_url('transacao'));
		}
	}

	public function editar($ci_transacao)
	{
		if ($this->input->post()) {
			if ($this->validar()) {
				$this->commit($ci_transacao);
			} else {
				send_alert(validation_errors());
			}
		}
		$this->load->helper(array('form', 'url'));
		if(isUpdate($this->transacao)) {
			$rs = $this->transacao_model->get($ci_transacao);
			$data = array('transacao' => $rs);

            $data['breadcrumbs'] = $this->view_breadcrumbs(
                null,"Transações",
                array('Transação','Editar'),
                'transacao', 'exchange',
                true
            );

			$this->load_view('acesso/transacao', $data);
		}else{
			send_alert($this->lang->line('restrict_access'));
			redirect(base_url('transacao'));
		}
	}


	public function buscar($page = 1){
		if(isRead($this->transacao)) {
			$rs = $this->transacao_model->get_transacoes_by_filter($_POST, $page);
		    $descrRows = "";
			if ($rs->num_rows > 1) $descrRows = $rs->num_rows . " registros";
			elseif ($rs->num_rows == 0) $descrRows = 'Nenhum registro encontrado';
			else $descrRows = $rs->num_rows . " registro encontrado";

			$data = array("transacao" => $this->transacao, 'find' => $rs->result, 'descrRows' => $descrRows);

            $data['breadcrumbs'] = $this->view_breadcrumbs(
                $page,"Transações",
                array('Transações','Pesquisa','Pagina '.$page),
                'transacao/novo', 'exchange'
            );

			$this->load->library('pagination');
			$config['base_url'] = base_url('transacao/buscar/');
			$config['total_rows'] = $rs->num_rows;
			$this->pagination->initialize($config);

			$this->load_view('acesso/transacao_view', $data);
		}else{
			send_alert($this->lang->line('restrict_access'));
			redirect(base_url());
		}
	}

	public function validar(){
		$this->load->library('form_validation');

		$this->form_validation->set_rules('nmTransacao', 'Transacao', 'required|min_length[3]');
		$this->form_validation->set_rules('nmlabel', 'Label', 'required|min_length[3]');

		return $this->form_validation->run();
	}

	//======USADO PARA EDITAR OU INSERIR
	public function commit($id = null){
		if($id == null){
			if(isCreate($this->transacao)) {
				$term['search1'] = trim($this->input->post('nmTransacao'));
				$term['search2'] = trim($this->input->post('nmlabel'));
				$rs = $this->transacao_model->get_transacao_by_filter($_POST);
				if ($rs->num_rows == 0) {
					$sql = "select nextval('util.tb_transacao_ci_transacao_seq') as num";
					$query = $this->db->query($sql);
					$rsId = $query->result();

					$sqlG = " SELECT ci_grupo, nm_grupo FROM util.tb_grupo WHERE cd_modulo = " . $this->config->item('module_id') . " AND fl_nivel_acesso = 6 ";

					$query = $this->db->query($sql);
					$rsG = $query->result();
					$transacao = (Object)array(
						'ci_transacao' => $rsId[0]->num,
						'nm_transacao' => $term['search1'],
						'nm_label' => $term['search2']
					);

					$moduloTransacao = (Object)array('cd_modulo' => $this->config->item('module_id'), 'cd_transacao' => $rsId[0]->num);
					$this->db->trans_start();

					$this->db->insert('util.tb_transacao', $transacao);
					$this->db->insert('util.tb_modulotransacao', $moduloTransacao);

					$this->db->trans_complete();

					if ($this->db->trans_status() != false) {
						send_alert("Operação realizada com sucesso!", null, "success");
						redirect(base_url('transacao'));
					} else {
						send_alert("Ocorreu um erro ao tentar salvar!");
						redirect(base_url('transacao/novo/'));
					}
				} else {
					send_alert("Trasação já existente!");
					redirect(base_url('transacao/novo/'));
				}
			}else{
				send_alert($this->lang->line('restrict_access'), null, 'warning');
				redirect(base_url('transacao'));
			}
		}else{
			if(isUpdate($this->transacao)) {
				
				$data = (Object)array(
					'nm_transacao' => trim($this->input->post('nmTransacao')),
					'nm_label' => trim($this->input->post('nmlabel'))
				);
				
				$this->db->where("ci_transacao", $id);

				if ($this->db->update('util.tb_transacao', $data)) {
					send_alert("Operação realizada com sucesso!!", null, "success");
					redirect(base_url('transacao'));
				} else {
					send_alert("Ocorreu um erro ao tentar salvar!");
					redirect(base_url('transacao/editar/' . $id));
				}
			}else{
				send_alert($this->lang->line('restrict_access'), null, 'warning');
				redirect(base_url('transacao'));
			}
		}
	}

	public function remove(){
		if(isDelete($this->transacao)) {
			$result = $this->transacao_model->remove();
			if ($result['valid'])
				send_alert($result['messeger'], null, 'success');
			else
				send_alert($result['messeger'], null, 'danger');

			redirect(base_url('transacao'));
		}else{
			send_alert($this->lang->line('restrict_access'), null, 'warning');
			redirect(base_url('transacao'));
		}
	}
}