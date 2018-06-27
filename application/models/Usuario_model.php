tb_usuariogrupos<?php
/**
 * Created by PhpStorm.
 * User: Paulo Roberto Mesquita da Silva
 * Date: 01/11/2017
 * Time: 04:17
 * @property Generic_crud   generic_crud
 * @property Grupo_model grupo_model
 */

class Usuario_model extends CI_Model {
    public $countPagination;
    private $limitPagina = 10;

    //Retorna uma lista (Array).
    public function get_user_by_login($login){
        $db = $this->db;
        //Realiza a verificação no banco e valida a autenticação
        $db->select('tu.ci_usuario, tu.nm_usuario, tu.nm_login, tu.nm_senha, fl_atualizousenha, 
                                tut.ci_instituicao, tut.nm_instituicao as nm_unidade_trabalho');
        $db->from('avalia.tb_usuario tu');
        $db->join('avalia.tb_instituicao tut', 'tu.cd_instituicao = tut.ci_instituicao', 'inner');
        $db->where('upper(tu.nm_login)',$login);
        $db->or_where('tu.ds_email',strtolower($login));
        $query = $db->get();
        //echo $db->last_query(); exit;
        return $query->row();
    }

	/**
	 *  Alterar senha do usuario
	 *  @param int $ci_usuario usuario que a senha vai ser alterarda
	 *  @param string $senhaAtual Senha atual
	 *  @param string $newSenha nova senha
	 *  
	 *  @return int se retorna 0 = success, 1 = senha atual incorreta, 2 = erro ao alterar senha 
	 */
	public function alterar_senha($ci_usuario,$senhaAtual, $newSenha){
		$db = $this->db;
		$db->select("ci_usuario ,nm_senha");
		$db->from(" avalia.tb_usuario");
		$db->where("ci_usuario = ".$ci_usuario);
		$result = $db->get()->row();
		
		if(md5($senhaAtual) == $result->nm_senha || md5(strtoupper($senhaAtual)) == $result->nm_senha){
			$set = array('nm_senha'=>md5($newSenha), 'fl_atualizousenha'=>'S');
			return $db->update('avalia.tb_usuario',$set, "ci_usuario = ".$ci_usuario);
		}else{
			return 3;
		}
	}

    //Update Senha - Recupera��o
    public function update_senha($senha,$user){
        $db = $this->db;
        $data = Array('nm_senha' => md5($senha), 'fl_atualizousenha' => 'N');
        $db->where('ci_usuario', $user);
        return $db->update('avalia.tb_usuario', $data);
    }

    //Inserir ultimo acesso do usuario
    public function insert_last_acess($user){
        //Inserindo o acesso atual
        $db = $this->db;
        $data = array(
            'cd_usuario' => $user,
            'cd_modulo' => $this->config->item('module_id'),
            'dt_inicio' => 'now()',
            'nr_ip' => $_SERVER["REMOTE_ADDR"]
        );

        $db->insert('avalia.tb_acesso_modulo', $data);
    }

    public function get_last_acess_by_user($user){
        //Adquirindo o último acesso do usuário.
        $db = $this->db;
        $sql = "select to_char(dt_inicio, 'DD/MM/YYYY HH24:MI:SS') as dt_inicio
					from avalia.tb_acesso_modulo
					where ci_acesso_modulo = (select max(ci_acesso_modulo)
											  from avalia.tb_acesso_modulo
											  where cd_usuario = {$user}
											   and cd_modulo = ".$this->config->item('module_id').");";

        return $db->query($sql)->row_array();
    }


    function check_user(){
        $this->db->select('*');
        $this->db->from('avalia.tb_usuario');
        $this->db->where('upper(nm_login)', strtoupper($this->input->post('login')));
        $this->db->where('upper(ds_email)', strtoupper($this->input->post('email')));
        $this->db->where('nm_cpf', $this->input->post('cpf'));
        $this->db->where('fl_ativo', 't');
        $query = $this->db->get();
        return $query->row();
    }

    public function record_count($query){
        $this->countPagination = $query->num_rows();
    }

    public function nextval(){
        $db = $this->db;
        $query = $db->query("select nextval('avalia.tb_usuario_ci_usuario_seq') as num");
        $rowID = $query->row();
        return $rowID->num;
    }

    public function get_unidade_trabalho($term){
        $db = $this->db;
        $query = $db->query("SELECT ci_unidade_trabalho, nr_codigo_unid_trab, nm_unidade_trabalho FROM avalia.tb_unidade_trabalho
		WHERE nm_unidade_trabalho ilike '%{$term}%' OR nr_codigo_unid_trab ilike '%{$term}%'
		GROUP BY 1,2,3
		ORDER BY 2 ASC
		LIMIT 10");

        $str = '[';
        foreach($query->result() as $row){
            $str .= '{"id":"'.$row->ci_unidade_trabalho.'","inep":"'.$row->nr_codigo_unid_trab.'","label":"'.$row->nm_unidade_trabalho.'"},';
        }
        return substr($str, 0, -1).']';
    }

    public function find(){
        $db = $this->db;
        $segment = $this->uri->segment(4);
        $p = (isset($segment) ? $segment : 1);
        $sql = "select 	ci_usuario,
                nm_usuario,
                nm_login,
                nm_cpf,
                ds_email,
                to_char(dt_nascimento, 'dd/mm/yyyy') as dt_nascimento,
                fl_sexo
            from avalia.tb_usuario
            where 1=1
            order by 2
            limit {$this->limitPagina} offset ".(($p - 1) * $this->limitPagina);
        $query = $db->query($sql);
        $this->record_count($query);
        return $query->result();
    }

    public function search($limit, $offset){
        $segment = $this->uri->segment(4);
        $p = (isset($segment) ? $segment : 1);
        $this->db->select("ci_usuario, nm_usuario, nm_login, nm_cpf, ds_email, to_char(dt_nascimento, 'dd/mm/yyyy') as dt_nascimento, fl_sexo");
        $this->db->from("avalia.tb_usuario");
        if($this->input->get('nome')) $this->db->like("upper(nm_usuario)", strtoupper($this->input->get('nome')));
        if($this->input->get('login')) $this->db->where("upper(nm_login)", strtoupper($this->input->get('login')));
        if($this->input->get('cpf')) $this->db->where("nm_cpf", preg_replace("/[^0-9]/", "", $this->input->get('cpf')));
        if($this->input->get('email')) $this->db->where("ds_email", $this->input->get('email'));
        if($limit) $this->db->limit($limit);
        if($offset) $this->db->offset($offset);
        $this->db->order_by('nm_usuario', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function count_results(){
        $this->db->select("ci_usuario, nm_usuario, nm_login, nm_cpf, ds_email, to_char(dt_nascimento, 'dd/mm/yyyy') as dt_nascimento, fl_sexo");
        $this->db->from("avalia.tb_usuario");
        if($this->input->get('nome')) $this->db->like("upper(nm_usuario)", strtoupper($this->input->get('nome')));
        if($this->input->get('login')) $this->db->where("upper(nm_login)", strtoupper($this->input->get('login')));
        if($this->input->get('cpf')) $this->db->where("nm_cpf", preg_replace("/[^0-9]/", "", $this->input->get('cpf')));
        if($this->input->get('email')) $this->db->where("ds_email", $this->input->get('email'));
        $num_results  = $this->db->count_all_results();
        return $num_results;
    }

    public function find_by_id($id){
        $db = $this->db;
        $db->select("tu.*, to_char(dt_nascimento, 'dd/mm/yyyy') as dt_nascimento, tut.nm_unidade_trabalho, tu.cd_unidade_trabalho as nm_unidade_trabalho_id");
        $db->from("avalia.tb_usuario tu");
        $db->join("avalia.tb_unidade_trabalho tut", "tu.cd_unidade_trabalho=tut.ci_unidade_trabalho", "inner");
        $db->where("tu.ci_usuario",(int)$id);
        $query = $db->get();
        return $query->row();
    }

    public function update($id){
    	$db = $this->db;
    	$nm_login = $this->input->post('login');
    	$ds_email = $this->input->post('email');
    	$nm_cpf = addslashes($this->input->post('cpf'));
    	$nm_cpf = preg_replace("/[^0-9]/", "", $nm_cpf);
    	$user = $this->find_by_id($id);
    	
    	//Validando para que não haja cpfs, emails ou logins duplicados
    	$queryTestLogin = $db->query("select ci_usuario from avalia.tb_usuario where nm_login = '$nm_login' and ci_usuario != ".$id);
    	$queryTestEmail = $db->query("select ci_usuario from avalia.tb_usuario where ds_email = '$ds_email' and ci_usuario != ".$id);
    	$queryTestCPF = $db->query("select ci_usuario from avalia.tb_usuario where nm_cpf = '$nm_cpf' and ci_usuario != ".$id);
    	
    	if($queryTestLogin->num_rows() > 0) {
    		return (Object) Array("valid"=>FALSE, "messeger"=>'Já existe um usuário com este login: ' . $nm_login . ' !');
    	}elseif($queryTestEmail->num_rows() > 0 && strcasecmp($user->ds_email,$ds_email) != 0) {
    		return (Object) Array("valid"=>FALSE, "messeger"=>'Já existe um usuário com este Email: ' . $ds_email . ' !');
    	}elseif($queryTestCPF->num_rows() > 0 && strcasecmp($user->nm_cpf,$nm_cpf) != 0) {
    		return (Object) Array("valid"=>FALSE, "messeger"=>'Já existe um usuário com este CPF: ' . mask($nm_cpf, '###.###.###-##') . ' !');
    	}else {
    		
    		$dt_nascimento = $this->input->post('nascimento');
    		$parts = explode('/', $dt_nascimento);
    		$dt_nascimento = $parts[2].$parts[1].$parts[0];
    		
    		$data = array(
    				'nm_usuario' => $this->input->post('nome'),
    				'nm_login' => $nm_login,
    				'ds_email' => $ds_email,
    				'nm_cpf' => $nm_cpf,
    				'dt_nascimento' => $dt_nascimento,
    				'fl_sexo' => $this->input->post('sexo'),
    				'cd_unidade_trabalho' => (int)$this->input->post('cd_unidade')
    		);
    		$this->db->where('ci_usuario', (int)$id);
    		if( $this->db->update('avalia.tb_usuario', $data)){
    			 return (Object) Array("valid"=>TRUE);
    		}    		
    	}
    }

    public function update_all(){}

    public function insert(){
        $db = $this->db;
        $ci_usuario = $this->nextval();
        $nm_usuario = addslashes(strtoupper($this->input->post('nome')));
        $nm_login = addslashes(strtoupper($this->input->post('login')));
        $ds_email = addslashes($this->input->post('email'));
        $nm_cpf = addslashes($this->input->post('cpf'));
        $nm_cpf = preg_replace("/[^0-9]/", "", $nm_cpf);
        $dt_nascimento = $this->input->post('nascimento');
        $parts = explode('/', $dt_nascimento);
        $dt_nascimento = $parts[2].$parts[1].$parts[0];

        //Gerando senha aleat�ria
        $senha = senhaAleatoria();

        //Validando para que n�o haja cpfs, emails ou logins duplicados
        $queryTestLogin = $db->query("select ci_usuario from avalia.tb_usuario where nm_login = '$nm_login' and ci_usuario != ".$ci_usuario);
        $queryTestEmail = $db->query("select ci_usuario from avalia.tb_usuario where ds_email = '$ds_email' and ci_usuario != ".$ci_usuario);
        $queryTestCPF = $db->query("select ci_usuario from avalia.tb_usuario where nm_cpf = '$nm_cpf' and ci_usuario != ".$ci_usuario);

        $data = array(
            'ci_usuario' => $ci_usuario,
            'nm_usuario' => $nm_usuario,
            'nm_login' => $nm_login,
            'ds_email' => $this->input->post('email'),
            'nm_senha' => md5($senha),
            'nm_cpf' => $nm_cpf,
            'dt_nascimento' => $dt_nascimento,
            'fl_sexo' => $this->input->post('sexo'),
            'cd_unidade_trabalho' => (int)$this->input->post('cd_unidade')
        );

        if($queryTestLogin->num_rows() > 0) {
            $return = Array("valid"=>FALSE, "messeger"=>'Já existe um usuário com este login: ' . $nm_login . ' !');
            return $return;
        }elseif($queryTestEmail->num_rows() > 0) {
            $return = Array("valid"=>FALSE, "messeger"=>'Já existe um usuário com este Email: ' . $ds_email . ' !');
            return $return;
        }elseif($queryTestCPF->num_rows() > 0) {
            $return = Array("valid"=>FALSE, "messeger"=>'Já existe um usuário com este CPF:' . mask($nm_cpf, '###.###.###-##') . ' !');
            return $return;
        }else {
            if($this->db->insert('avalia.tb_usuario', $data)) {
                $return = Array("valid"=>TRUE, "messeger"=>"Salvo com sucesso!", "ci_usuario"=>$ci_usuario);
                return $return;
            }else{
                $return = Array("valid"=>FALSE, "messeger"=>"Ocorreu um erro ao salvar!");
                return $return;
            }
        }
    }

    public function insert_all(){}
    public function remove(){}
    public function remove_by_id($id){}

    public function save_modulo_grupo($id=null){

        $db = $this->db;
        $retorno = TRUE;

        $search = 'MEDIOTEC';
        $sql = "SELECT ci_grupo FROM avalia.tb_grupo g WHERE g.nm_grupo ILIKE '%{$search}%';";
        $query = $this->db->query($sql);
        $grupo= (Object) $query->row();
        $arr_grupo = array($grupo->ci_grupo);

        $valid = $db->query('delete from avalia.tb_usuariogrupos
                    where ci_usuariogrupos in(
                    select ci_usuariogrupos from avalia.tb_usuariogrupos tug
                    inner join avalia.tb_grupo tg on(tug.cd_grupo = tg.ci_grupo)
                    where tg.cd_modulo = ' . $this->config->item('module_id') . ' and tug.cd_usuario = ' . $id . ')');

        if($valid){
            $sqlGrupos = '';
            for ($i=0;$i<count($arr_grupo);$i++){
                $sqlGrupos .= 'insert into avalia.tb_usuariogrupos (cd_usuario, cd_grupo) values ('.$id.', '.$arr_grupo[$i].'); ';
            }

            if($sqlGrupos != '') {
                if(!$db->query($sqlGrupos)) $retorno = FALSE;
            }
        }else{
            $retorno = FALSE;
        }

        return $retorno;
    }

    public function registro(){
        $db = $this->db;

        $email = strtoupper($this->input->post('email'));
        $apelido = explode('@',$email);

        $ci_usuario = $this->nextval();
        $nm_usuario = addslashes(strtoupper($this->input->post('nome')));
        $nm_login = addslashes($apelido[0]);
        $nm_apelido = addslashes(strtoupper($this->input->post('apelido')));
        $ds_email = $this->input->post('email');
        $nr_telefone = addslashes($this->input->post('telefone'));
        $nm_senha = md5($this->input->post('password'));

        //Validando para que não haja cpfs, emails ou logins duplicados
        $queryTestEmail = $db->query("select ci_usuario from avalia.tb_usuario where ds_email = '$ds_email' and ci_usuario != ".$ci_usuario);

        $data = array(
            'ci_usuario' => $ci_usuario,
            'nm_usuario' => $nm_usuario,
            'nm_login' => $nm_login,
            'ds_email' => strtolower($email),
            'nm_apelido' => $nm_apelido,
            'nm_senha' => $nm_senha,
            'fl_atualizousenha' => 'N',
            'dt_criacao' => 'now()',
            'nr_telefone' => $nr_telefone,
            'cd_unidade_trabalho' => '15890'
        );

        if($queryTestEmail->num_rows() > 0) {
            $return = Array("valid"=>FALSE, "messeger"=>'Já existe um usuário com este Email: ' . $ds_email . ' !');
            return $return;
        }else {
            if($this->db->insert('avalia.tb_usuario', $data)) {
                $result = $this->save_modulo_grupo($ci_usuario);
                if($result){
                    $return = Array("valid"=>TRUE, "messeger"=>"Salvo com sucesso!", "ci_usuario"=>$ci_usuario);
                    return $return;
                }else{
                    $this->remover_usuario($ci_usuario);
                    $return = Array("valid"=>FALSE, "messeger"=>"Grupo Usuario: Ocorreu um erro ao salvar!");
                    return $return;
                }
            }else{
                $return = Array("valid"=>FALSE, "messeger"=>"Ocorreu um erro ao salvar!");
                return $return;
            }
        }
    }

    public function remover_usuario($id)
    {
        $this->db->trans_start();

        $this->db->where('ci_usuario', $id);
        $this->db->delete('avalia.tb_usuario');

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
        {
            return false;
        }

        return true;
    }
} 