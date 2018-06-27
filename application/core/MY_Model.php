<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author: Luiz Alberto Mesquita
 * Date: 18/05/2015
 * @property CI_DB_query_builder $db
 * @property CI_Session $session
 */
class MY_Model extends CI_Model
{
    /**
     * As propriedades abaixo precisam ser setadas no construtor das classes filhas
     * @var table_name nome da tabela com esquema se necessário
     * @var key_field nome do campo chave da tabela
     */
    protected $table_name, $key_field;

    /**
     * @return string
     */
    protected function getClassName(){
        return get_class($this);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function get_all($limit = NULL, $offset = NULL)
    {
        $query = $this->db->get($this->table_name, $limit, $offset);
        return $query->result($this->getClassName());
    }

    /**
     * @param int $id
     * @return object
     */
    public function get($id){
        $query = $this->db->get_where($this->table_name, array($this->key_field => $id));
        return $query->row(0,$this->getClassName());
    }

    /**
     * @return boolean
     */
    public function insert(){
        return $this->db->insert($this->table_name, $this);
    }

    /**
     * @param int $id
     * @return boolean
     */
    public function update($id){
        return $this->db->update($this->table_name, $this, array($this->key_field => $id));
    }

    /**
     * @param int $id
     * @return boolean
     */
    public function delete($id){
        $query = $this->db->delete($this->table_name, array($this->key_field => $id));
        return $query->result();
    }

    /**
     * @return int
     */
    public function count_all(){
        return $this->db->count_all($this->table_name);
    }
    
    
    public function anoletivo(){
    	$session = $this->session;
    	if($session->userdata("isSeduc")){
    		$sql = " SELECT nr_anoletivo as ano FROM academico.fc_carrega_ano_letivo(0) group by nr_anoletivo order by 1 desc limit 2;";
    	}elseif($session->userdata("isCrede")){
    		$cd_crede = $session->userdata("user")->ci_unidade_trabalho;
    		$sql = " SELECT tp.nr_anoreferencia as ano FROM academico.tb_parametros tp, util.tb_unidade_trabalho tut ".
    				" WHERE tp.cd_unidade_trabalho = tut.ci_unidade_trabalho ".
    				" AND tp.dt_inicioanoletivo <= 'now'::TEXT::DATE and tp.dt_fimanoletivo >= 'now'::TEXT::DATE ".
    				" AND (tp.cd_unidade_trabalho = ". $cd_crede . ")".
    				" GROUP BY  tp.nr_anoreferencia ".
    				" UNION ALL ".
    				" SELECT tp.nr_anoreferencia as ano FROM academico.tb_parametros tp, util.tb_unidade_trabalho tut ".
    				" WHERE tp.cd_unidade_trabalho = tut.cd_unidade_trabalho_pai ".
    				" AND tp.dt_inicioanoletivo <= 'now'::TEXT::DATE and tp.dt_fimanoletivo >= 'now'::TEXT::DATE ".
    				" AND (tut.ci_unidade_trabalho = " . $cd_crede . ")".
    				" GROUP BY  tp.nr_anoreferencia ".
    				" ORDER BY 1 desc ;";
    	}elseif($session->userdata("isEscola")){
    		$sql = " SELECT tp.nr_anoreferencia as ano FROM academico.tb_parametros tp WHERE tp.dt_inicioanoletivo <= 'now'::TEXT::DATE AND tp.dt_fimanoletivo >= 'now'::TEXT::DATE".
    				" AND cd_unidade_trabalho = {$session->userdata("user")->cd_unidade_trabalho_pai}";
    	}
    
    	$rs = $this->db->query($sql)->row();
    	return $rs;
    }
    
    	
    public function getAllCrede(){
    	$sql = ' SELECT ci_unidade_trabalho,
						nm_unidade_trabalho,
						nm_sigla
					 FROM util.tb_unidade_trabalho
					 WHERE cd_tpunidade_trabalho = 2
					 AND cd_unidade_trabalho_pai = 15890
					 AND ci_unidade_trabalho between 1 and 30
					 ORDER BY 1';
    	$rs = $this->db->query($sql)->result();
    	return $rs;
    }
    
    public function getMunicipioCrede($Idcrede){
    	$Idcrede = isset($Idcrede) || $Idcrede !="" ? $Idcrede : 0;
    	$sql = ' select ci_localidade, sg_estado, ds_localidade, cd_inep from util.tb_localidades l
				 inner join util.tb_municipio_crede mc on mc.cd_localidade = l.ci_localidade
				 where cd_unidade_trabalho = '.$Idcrede;
    	$rs = $this->db->query($sql)->result();
    	return $rs;
    }

}