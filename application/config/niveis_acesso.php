<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$niveis = array(
    1 => 'BÁSICO',
    2 => 'ESCOLA',
    3 => 'MUNICÍPIO',
    4 => 'CREDE',
    5 => 'SEDUC',
    6 => 'MASTER'
);

$config['BASICO']       = 1;
$config['ESCOLA']       = 2;
$config['MUNICIPIO']    = 3;
$config['CREDE']        = 4;
$config['SEDUC']        = 5;
$config['MASTER']       = 6;
$config['NIVEIS'] = $niveis;