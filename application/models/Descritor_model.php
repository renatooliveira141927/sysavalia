<?php
/**
 * Created by PhpStorm.
 * User: Renato
 * Date: 23/06/2018
 * Time: 18:40
 */

class Descritor_model extends MY_Model
{
    private $limitPagina = 10;
    public function __construct(){
        parent::__construct();
        $this->table_name = "avalia.tb_descritor";
        $this->key_field = "ci_descritor";
    }

    public function get_descritores($param)
    {
        $db = $this->db;
        $db->select('*');
        $db->from('avalia.tb_descritor tc');
        if ($param != null) {
            $db->where('tc.cd_disciplina', $param['disciplina']);
        }
          $db->order_by('ordenador','ASC');
        $query = $db->get();

        return (Object) $query->result();
    }

}