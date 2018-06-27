<?php
/**
 * Created by PhpStorm.
 * User: luiz.alberto
 * Date: 03/02/2015
 * Time: 15:08
 */

defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('trace')) {
    function trace($value) {
        echo '<pre>';
        print_r($value);
        echo '</pre>';
    }
}

if ( ! function_exists('get_image')) {
    /**
     * Retorna o caminho completo da imagem
     * @param $file_name
     * @param $ext
     * @return string caminho completo
     */
    function get_image($file_name, $ext){
        return base_url('assets/img/'.$file_name.'.'.$ext);
    }
}

if ( ! function_exists('active_menu')) {
    /**
     * Retorna active do menu
     * @param $arr
     * @return String class active
     */
    function active_menu($arr){
        $active = "";
        $controller = get_instance()->router->fetch_class();
        if(in_array($controller,$arr)){
            $active = "active";
        }
        return $active;
    }
}

if ( ! function_exists('dateFromDb')) {
    /**
     * Formata a data do banco para exibição ao usuário
     * @param $date
     * @return string formatada
     * @deprecated utilizar date_helper
     */
    function dateFromDb($date)
    {
        return implode("/", array_reverse(explode("-", $date)));
    }
}

if ( ! function_exists('mask')) {
	/*
	 * Mascara CPF para Listar.
	* @param $val, $mask
	* @return string formatada
	* */
	function mask($val, $mask)
	{
		if (strlen($val) == 14) return $val;
		$maskared = '';
		$k = 0;
		for ($i = 0; $i <= strlen($mask) - 1; $i++) {
			if ($mask[$i] == '#') {
				if (isset($val[$k])) $maskared .= $val[$k++];
			} else {
				if (isset($mask[$i])) $maskared .= $mask[$i];
			}
		}
		return $maskared;
	}
}

if ( ! function_exists('senhaAleatoria') ) {
	function senhaAleatoria() {
		$senha = '';
		$lmin = 'abcdefghijklmnopqrstuvwxyz';
		$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$num = '1234567890';
		$caracteres = $lmin . $lmai . $num;
		$len = strlen($caracteres);
		for ($i = 0; $i < 6; $i++) {
			$rand = mt_rand(1, $len);
			$senha .= $caracteres[$rand - 1];
		}
		return $senha;
	}
}

if ( ! function_exists('setLink') ){
	function setLink($url){
		return "location.href = '{$url}'";
	}
}

if ( ! function_exists('currencyToDb')) {
    /**
     * Converte o valor digitado para o formato do banco
     * @param $value
     * @return string formatada
     */
    function currencyToDb($value){
        return str_replace(",", ".", filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_THOUSAND));
    }

}