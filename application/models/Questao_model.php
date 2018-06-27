<?php
/**
 * Created by PhpStorm.
 * User: Renato
 * Date: 24/06/2018
 * Time: 11:13
 */

class Questao_model extends MY_Model
{
    private $limitPagina = 10;
    public function __construct(){
        parent::__construct();
        $this->table_name = "avalia.tb_gabarito_item_descricao";
        $this->key_field = "ci_gabarito_item_descricao";
    }

    public function get_questoes($param)
    {
        $db = $this->db;
        $db->select('*');
        $db->from('avalia.tb_gabarito_item_descricao id');
        if ($param != null) {
            $db->where('id.cd_gabarito_item', $param['item']);
        }
        //$db->order_by('ordenador','ASC');
        $query = $db->get();

        return (Object) $query->result();
    }
}