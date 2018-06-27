<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$GLOBALS['scripts'] = [];
$GLOBALS['styles'] = [];
if ( ! function_exists('addJS')) {
    function addJS($param)
    {
		if(is_array($param)){
			foreach ($param as $file){
				if (!in_array($file,  $GLOBALS['scripts'])) {
					array_push($GLOBALS['scripts'], $file);
				}
			}
		}else{
			if (!in_array($param,  $GLOBALS['scripts'])) {
				array_push($GLOBALS['scripts'], $param);
			}
		}
    }
}


if ( ! function_exists('addCSS')) {
	function addCSS($param)
	{
		if(is_array($param)){
			foreach ($param as $file){
				if (!in_array($file,  $GLOBALS['styles'])) {
					array_push($GLOBALS['styles'], $file);
				}
			}
		}else{
			if (!in_array($param,  $GLOBALS['styles'])) {
				array_push($GLOBALS['styles'], $param);
			}
		}
	}
}

if ( ! function_exists('writeJS')) {
    function writeJS()
    {
        foreach ($GLOBALS['scripts'] as $js) {
            echo '<script src="'.base_url("assets/js/$js.js").'" type="text/javascript" charset="utf-8"></script>';
        }
    }
}

if ( ! function_exists('writeCSS')) {
	function writeCSS()
	{
		foreach ($GLOBALS['styles'] as $css) {			
			echo '<link rel="stylesheet" type="text/css" href="' . base_url("/assets/css/" . $css . ".css") . '" charset="utf-8">';
        }
    }
}

if ( ! function_exists('scritpJS')) {
	function reportJS()
	{
		if(defined('URL_REPORT')){
			echo '<script type="text/javascript">
						var janela = window.open("'.URL_REPORT.'", "_blank", "dependent=yes, menubar=no, toolbar=no, location=no, status=no, scrollbars=yes, resizable=yes, height=screen.height, width=screen.width");
						janela.focus();
					</script>';
		}
	}
}

if ( ! function_exists('alert')) {
	function alert()
	{
		$show_alert = get_instance()->session->flashdata('show_alert');
		//trace($show_alert); exit;
		get_instance()->session->unset_userdata('show_alert');
		if(isset($show_alert)) {
			echo '<div class="alert alert-' . $show_alert->type . ' alert-dismissible" role="alert" data-timer="'.$show_alert->timer.'">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <strong><i class="fa fa-'.$show_alert->icon.'"></i></strong> '.$show_alert->message.'
				</div>';
		}
	}
}

if ( ! function_exists('send_alert')) {
	function send_alert( $message , $timer = null , $type='danger' , $icon='exclamation-triangle'){
		$data = new stdClass();
		$data->type = $type ;
		$data->message = $message ;
		$data->icon = $icon;
		$data->timer = $timer ;
		get_instance()->session->set_flashdata('show_alert' , $data);
	}
}

if ( ! function_exists('get_image')) {
    /**
     * Retorna o caminho completo da imagem
     * @param $file_name
     * @return string caminho completo
     */
    function get_image_asset($file_name){
        //return 'http://assets.seduc.ce.gov.br/ci3/img/'.$file_name;
        return base_url('assets/img/'.$file_name);
    }
}

