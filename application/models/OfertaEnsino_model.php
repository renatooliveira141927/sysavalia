<?php
/**
 * Created by PhpStorm.
 * User: Renato
 * Date: 23/06/2018
 * Time: 09:58
 */

class OfertaEnsino_model extends MY_Model
{

    private $limitPagina = 10;
    public function __construct(){
        parent::__construct();
        $this->table_name = "avalia.tb_ofertaensino";
        $this->key_field = "ci_ofertaensino";
    }

    public function get_ofertaensino(){
        $db = $this->db;
        $db->select('*');
        $db->from('avalia.tb_ofertaensino');

        $query = $db->get();

        return (Object) $query->result();
    }
}