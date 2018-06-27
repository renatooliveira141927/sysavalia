<?php
/**
 * Created by PhpStorm.
 * User: Paulo Roberto
 * Date: 03/11/2017
 * Time: 17:01
 */

class Parametros_model extends MY_Model{

	private $limitPagina = 10;
    public function __construct(){
        parent::__construct();
        $this->table_name = "avalia.tb_parametros";
        $this->key_field = "ci_parametros";
    }
}