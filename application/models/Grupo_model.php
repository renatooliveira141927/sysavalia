<?php
/**
 * Created by PhpStorm.
 * User: paulo.roberto
 * Date: 05/05/2015
 * Time: 10:05
 *
 * @schema = avalia
 * @table = tb_grupo
 * @key = ci_grupo
 *
 */
class Grupo_model extends MY_Model {
	private $limitPagina = 10;
	public function __construct(){
		parent::__construct();
		$this->table_name = "avalia.tb_grupo";
		$this->key_field = "ci_grupo";
	}
	
	
	public function get_grupo_by_filter($objTerm, $page = 1){
		$objTerm = (Object) $objTerm;
		$db = $this->db;
		$db->start_cache();
		$db->select('ci_grupo,nm_grupo,cd_modulo, fl_nivel_acesso')
		   ->from('avalia.tb_grupo g')
		   ->where("cd_modulo", $this->config->item('module_id'))
		   ->where("g.fl_nivel_acesso !=", 6);
		
		(isset($objTerm->search1) && trim($objTerm->search1) != "" ? $db->like("upper(g.nm_grupo)", strtoupper($objTerm->search1), "both") : null);
		(isset($objTerm->search2) && trim($objTerm->search2) != "0" ? $db->where("g.fl_nivel_acesso", $objTerm->search2) : null);
		$db->stop_cache();
	
		$query = $db->get();
		$data['num_rows'] = $query->num_rows();
	
		$db->order_by("nm_grupo","ASC")
		   ->limit(10,(($page - 1) * $this->limitPagina));
		
		$t = $db->get();
		$data['result']   = $t->result();
		//trace($db->last_query());
		return (Object) $data;
	}
	
	public function get_grupo($objTerm, $id = null){
		$objTerm = (Object) $objTerm;
		$db = $this->db;
		$db->start_cache();
		$db->select('ci_grupo,nm_grupo,cd_modulo, fl_nivel_acesso')
		   ->from('avalia.tb_grupo g')
		   ->where("cd_modulo", $this->config->item('module_id'));
		($id != null ? $db->where("ci_grupo != ",$id ) : null );
		(isset($objTerm->nm_grupo) && trim($objTerm->nm_grupo) != "" ? $db->where("upper(g.nm_grupo)", strtoupper($objTerm->nm_grupo)) : null);
		$db->stop_cache();
	
		$data['num_rows'] = $db->get()->num_rows();
		$data['result']   = $db->get()->result();
		//trace($db->last_query());
		return (Object) $data;
	}
	
    //Retorna um objeto grupo.
    public function get_grupo_by_user($user){
        //Seleciona uma lista de todos os grupos do usuário deste módulo, ordenados de forma decrescente pelo nível de acesso
        $db = $this->db;
        $db->select('fl_nivel_acesso, nm_grupo');
        $db->from('avalia.tb_usuariogrupos tug');
        $db->join('avalia.tb_grupo tg', 'tg.ci_grupo = tug.cd_grupo', 'inner');
        $db->where('cd_usuario',(int)$user);
        $db->where('cd_modulo',$this->config->item('module_id'));
        $db->order_by('fl_nivel_acesso','desc');
        $queryGrupos = $db->get();
        //trace($db->last_query()); exit;
        return $queryGrupos->result();
    }

    //Retorna grupo do modulo disponivel para o usuario
    public function get_grupo_disponivel($user){
        $db = $this->db;
        $userS = $this->session->userdata("user");
        $and = "";
        if(!isMaster()){ $and = " AND fl_nivel_acesso <= ".$userS->max_nivel_acesso;}
        
        $sql = 'SELECT ci_grupo, nm_grupo, fl_nivel_acesso
                FROM avalia.tb_grupo
                WHERE cd_modulo = '.$this->config->item('module_id').'
                '.$and.'
                AND ci_grupo NOT IN(
                    SELECT cd_grupo
                    FROM avalia.tb_usuariogrupos
                    WHERE cd_usuario = '.$user.')
                ORDER BY fl_nivel_acesso DESC';
        $queryGrupos = $db->query($sql);
        return $queryGrupos->result();
    }

    //Retorna grupo do modulo avaliaizado pelo o usuario
    public function get_grupo_avaliaizado($user){
        $db = $this->db;
        $sql = 'SELECT tg.ci_grupo, tg.nm_grupo
                  FROM avalia.tb_usuariogrupos tug
                  JOIN avalia.tb_grupo tg ON(tug.cd_grupo = tg.ci_grupo)
                  WHERE tg.cd_modulo = '.$this->config->item('module_id').'
                  AND tug.cd_usuario = '.$user;
        $queryGrupos = $db->query($sql);
        return $queryGrupos->result();
    }

    public function get_grupo_disponiveis_new(){
        $db = $this->db;
        $and = "";
        if(!isMaster()){ $and = " AND fl_nivel_acesso <= ".$this->session->userdata("user")->max_nivel_acesso;}
        
        $queryGrupos = $db->query('select ci_grupo, nm_grupo from avalia.tb_grupo where cd_modulo = '.$this->config->item('module_id').' '.$and.' order by fl_nivel_acesso desc');
        return $queryGrupos->result();
    }

    public function nextval(){
        $db = $this->db;
        $query = $db->query("SELECT nextval('avalia.tb_grupo_ci_grupo_seq') as num");
        $rowID = $query->row();
        return $rowID->num;
    }

    public function save_gruop_users($grupos,$ci_usuario){
        $db = $this->db;
        $retorno = TRUE;

        $valid = $db->query('delete from avalia.tb_usuariogrupos
                    where ci_usuariogrupos in(
                    select ci_usuariogrupos from avalia.tb_usuariogrupos tug
                    inner join avalia.tb_grupo tg on(tug.cd_grupo = tg.ci_grupo)
                    where tg.cd_modulo = ' . $this->config->item('module_id') . ' and tug.cd_usuario = ' . $ci_usuario . ')');

        if($valid){
            $sqlGrupos = '';
            for ($i=0;$i<count($grupos);$i++){
                $sqlGrupos .= 'insert into avalia.tb_usuariogrupos (cd_usuario, cd_grupo) values ('.$ci_usuario.', '.$grupos[$i].'); ';
            }

            if($sqlGrupos != '') {
                if(!$db->query($sqlGrupos)) $retorno = FALSE;
            }
        }else{
            $retorno = FALSE;
        }

        return $retorno;
    }

    public function update($id){
        $user = $this->session->get_userdata('user');
        $retorno = Array();
        $ci_grupo = $id;
        $nm_grupo = addslashes($this->input->post('nm_grupo'));
        $fl_nivel_acesso = $this->input->post('fl_nivel_acesso');
        $transacao = $this->input->post('transacao');
        $ci_usuario = $user['user']->ci_usuario;

        $sql = "";
        if($transacao){
            for($i=0;$i<count($transacao);$i++){
                $ci_transacao = $transacao[$i];
                $fl_inserir = (@$_POST['insert_'.$ci_transacao] ? "S" : "N");
                $fl_alterar = (@$_POST['update_'.$ci_transacao] ? "S" : "N");
                $fl_deletar = (@$_POST['delete_'.$ci_transacao] ? "S" : "N");
                $sql .= "INSERT INTO avalia.tb_grupotransacoes(cd_grupo, cd_transacao, fl_inserir, fl_alterar, fl_deletar, cd_usuario_insert)
				VALUES ({$ci_grupo}, {$ci_transacao}, '{$fl_inserir}', '{$fl_alterar}', '{$fl_deletar}', {$ci_usuario}); ";
            }
        }

        $this->db->trans_start();
        $this->db->query("UPDATE avalia.tb_grupo SET nm_grupo='{$nm_grupo}', fl_nivel_acesso={$fl_nivel_acesso} WHERE ci_grupo = {$ci_grupo};");
        $this->db->query("DELETE FROM avalia.tb_grupotransacoes WHERE cd_grupo = {$ci_grupo};");
        if($sql != "") $this->db->query($sql);
        $this->db->trans_complete();

        $retorno['messeger'] = "Salvo com sucesso!";
        $retorno['valid'] = TRUE;

        if ($this->db->trans_status() === FALSE)
        {
            $retorno['messeger'] = "Ocorreu um erro ao salvar";
            $retorno['valid'] = FALSE;
        }

        return $retorno;
    }

    public function insert(){
        $user = $this->session->get_userdata('user');
        $retorno = Array();

        $ci_grupo = $this->nextval();
        $nm_grupo = addslashes($this->input->post('nm_grupo'));
        $fl_nivel_acesso = $this->input->post('fl_nivel_acesso');
        $transacao = $this->input->post('transacao');
        $ci_usuario = $user['user']->ci_usuario;

        $sql = "";
        if($transacao){
            for($i=0;$i<count($transacao);$i++){
                $ci_transacao = $transacao[$i];
                $fl_inserir = (@$_POST['insert_'.$ci_transacao] ? "S" : "N");
                $fl_alterar = (@$_POST['update_'.$ci_transacao] ? "S" : "N");
                $fl_deletar = (@$_POST['delete_'.$ci_transacao] ? "S" : "N");
                $sql .= "INSERT INTO avalia.tb_grupotransacoes(cd_grupo, cd_transacao, fl_inserir, fl_alterar, fl_deletar, cd_usuario_insert)
				VALUES ({$ci_grupo}, {$ci_transacao}, '{$fl_inserir}', '{$fl_alterar}', '{$fl_deletar}', {$ci_usuario}); ";
            }
        }

        $this->db->trans_start();
        $this->db->query("INSERT INTO avalia.tb_grupo(ci_grupo, nm_grupo, cd_modulo, fl_nivel_acesso) VALUES ({$ci_grupo}, '{$nm_grupo}', ".$this->config->item('module_id').", {$fl_nivel_acesso}); ");
        if($sql != "") $this->db->query($sql);
        $this->db->trans_complete();

        $retorno['messeger'] = "Salvo com sucesso!";
        $retorno['valid'] = TRUE;

        if ($this->db->trans_status() === FALSE)
        {
            $retorno['messeger'] = "Ocorreu um erro ao salvar";
            $retorno['valid'] = FALSE;
        }

        return $retorno;
    }

    public function remove(){
        $checked = $this->input->post('checkdel');
        $retorno = Array();

        $this->db->trans_start();

        //Deleta as transações relacionadas ao grupo.
        $this->db->where_in('cd_grupo',$checked);
        $this->db->delete('avalia.tb_grupotransacoes');

        //Deleta o(s) grupo(s)
        $this->db->where("cd_modulo", $this->config->item('module_id'));
        $this->db->where_in('ci_grupo',$checked);
        $this->db->delete('avalia.tb_grupo');

        $this->db->trans_complete();

        $retorno['messeger'] = "Excluido com sucesso!";
        $retorno['valid'] = TRUE;

        if ($this->db->trans_status() === FALSE)
        {
            $retorno['messeger'] = "Ocorreu um erro ao exluir";
            $retorno['valid'] = FALSE;
        }

        return $retorno;
    }
}
