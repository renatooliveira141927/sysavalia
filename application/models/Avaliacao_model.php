<?php
/**
 * Created by PhpStorm.
 * User: Renato
 * Date: 22/06/2018
 * Time: 22:46
 */

class Avaliacao_model extends MY_Model{

    private $limitPagina = 10;
    public function __construct(){
        parent::__construct();
        $this->table_name = "avalia.tb_parametros";
        $this->key_field = "ci_parametros";
    }

}