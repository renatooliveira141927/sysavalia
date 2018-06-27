<?php
/**
 * Created by PhpStorm.
 * User: Renato
 * Date: 23/06/2018
 * Time: 09:09
 */

class Disciplina_model extends MY_Model{

    private $limitPagina = 10;
    public function __construct(){
        parent::__construct();
        $this->table_name = "avalia.tb_disciplina";
        $this->key_field = "ci_disciplina";
    }

    public function get_disciplinas(){
        $db = $this->db;
        $db->select('*');
        $db->from('avalia.tb_disciplina');

        $query = $db->get();

        return (Object) $query->result();
    }

}