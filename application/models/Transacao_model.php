<?php
/**
 * Created by PhpStorm.
 * User: Jeferson inacio Macedo
 * Date: 05/05/2015
 * Time: 11:09
 *
 * @schema = avalia
 * @table = tb_transacao
 * @key = ci_transacao
 */

class Transacao_model extends MY_Model {
	private $limitPagina = 10;
	public function __construct(){
		parent::__construct();
		$this->table_name = "avalia.tb_transacao";
		$this->key_field = "ci_transacao";
	}
	
	public function get_transacoes_by_filter($objTerm, $page = 1){
		$objTerm = (Object) $objTerm;
		$db = $this->db;
		$db->start_cache();
		$db->select(' t.*, (case when gt.cd_transacao is not null then 1 else 0 end) as fl_using')
		   ->from('avalia.tb_transacao t')
		   ->join("avalia.tb_modulotransacao mt", "t.ci_transacao = mt.cd_transacao")
		   ->join("avalia.tb_grupotransacoes gt", "gt.cd_transacao = t.ci_transacao", "left")
		   ->join("avalia.tb_modulo m"," m.ci_modulo = mt.cd_modulo")
		   ->where("ci_modulo", $this->config->item('module_id'))
		   ->group_by("1,2,3,gt.cd_transacao");		
		$search1 = (isset($objTerm->search1) && trim($objTerm->search1) != "" ? $db->like("upper(t.nm_transacao)", strtoupper($objTerm->search1), "both") : null);
		$search2 = (isset($objTerm->search2) && trim($objTerm->search2) != "" ? $db->like("upper(t.nm_label)", strtoupper($objTerm->search2), "both") : null);
		$db->stop_cache();
				
		$query = $db->get();
		$data['num_rows'] = $query->num_rows();
		
		$db->order_by("nm_transacao","ASC")
		   ->limit(10,(($page - 1) * $this->limitPagina));
		$t = $db->get();
		$data['result']   = $t->result();
		//trace($db->last_query());
		
		return (Object) $data;
	}
	
	public function get_transacao_by_filter($objterm){
		 $db = $this->db;
		 $objterm = (Object) $objterm;
		 trace($objterm);
		 $db->start_cache();
		 $db->from('avalia.tb_transacao t');
		 (isset($objterm->nmTransacao) && trim($objterm->nmTransacao) != "" ? $db->where("t.nm_transacao", $objterm->nmTransacao) : null);
		 (isset($objterm->nmlabel) && trim($objterm->nmlabel) != "" ? $db->where("t.nm_label", $objterm->nmlabel) : null);
		 $db->stop_cache();
		 
		 return (Object) array("num_rows" => $db->get()->num_rows(), "result"=> $db->get()->row());
	}
	
    //retorna um array para o fluxo de privilégios e sessão.
	
    public function get_transacao_by_user($user){
        $db = $this->db;
        $db->select('nm_label, fl_inserir, fl_alterar, fl_deletar');
        $db->from('avalia.vw_usuariotransacoes');
        $db->where('ci_usuario',(int)$user);
        $db->where('ci_modulo',$this->config->item('module_id'));
        $queryTransacoes = $db->get();
        return $queryTransacoes->result_array();
    }

	public function remove(){
		$checked = $this->input->post('checkdel');
		$retorno = Array();

		$this->db->trans_start();

		//Deleta as transações relacionadas ao grupo.
		$this->db->where_in('cd_transacao',$checked);
		$this->db->delete('avalia.tb_grupotransacoes');

		//Deleta as transações relacionadas ao modulo.
		$this->db->where("cd_modulo", $this->config->item('module_id'));
		$this->db->where_in('cd_transacao',$checked);
		$this->db->delete('avalia.tb_modulotransacao');

		//Deleta a(s) trasacao(ões)
		$this->db->where_in('ci_transacao',$checked);
		$this->db->delete('avalia.tb_transacao');

		$this->db->trans_complete();

		$retorno['messeger'] = "Excluido com sucesso!";
		$retorno['valid'] = TRUE;

		if ($this->db->trans_status() === FALSE){
			$retorno['messeger'] = "Ocorreu um erro ao exluir";
			$retorno['valid'] = FALSE;
		}
		return $retorno;
	}
	
	function get_transacoes_grupo($ci_grupo){
		$db = $this->db;
		$data = array();
		$db->start_cache();
		 $db->from("avalia.tb_transacao t");
		$db->stop_cache();
		//Transações que pertence ao grupo
		$db->join("avalia.tb_grupotransacoes gt", "t.ci_transacao = gt.cd_transacao")
		   ->where("cd_grupo",$ci_grupo);
		
		$data["transacoes_pertencentes"] = $db->get()->result();
		
		//Transações possiveis / disponives
		$db->join("avalia.tb_modulotransacao mt", "mt.cd_transacao = t.ci_transacao")
		   ->where("cd_modulo",$this->config->item('module_id'))
		   ->where("ci_transacao not in(select cd_transacao from avalia.tb_grupotransacoes where cd_grupo = $ci_grupo)",NULL, FALSE);
		$data["transacoes_possiveis"] = $db->get()->result();
		
		return (Object) $data;
	}
	
	function get_transacoes(){
		$db = $this->db;
		$db->from("avalia.tb_transacao t")
		   ->join("avalia.tb_modulotransacao mt", "mt.cd_transacao = t.ci_transacao")
		   ->where("cd_modulo",$this->config->item('module_id'));
		return $db->get()->result();
	}
}