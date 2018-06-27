<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* INFORME OS DADOS DE AUTENTICAÇÃO */
$config['smtp_user'] 	= 'sigeescola@seduc.ce.gov.br';
$config['smtp_pass'] 	= 'S1g3$$ES';

/* NÃO ALTERAR OS DADOS ABAIXO */
$config['protocol'] 	= 'smtp';
$config['smtp_host'] 	= 'smtp.seduc.ce.gov.br';
$config['smtp_port'] 	= 465;
$config['smtp_timeout'] = 20;
$config['smtp_crypto'] 	= 'ssl'; //lembrar de ativar no php.ini extension=php_openssl.dll
$config['mailtype'] 	= 'html';
$config['charset'] 		= 'utf-8';
