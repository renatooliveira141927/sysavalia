<?php
/**
 * Created by PhpStorm
 * Time: 11:38
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <span>
                            <!--img alt="image" class="img-circle" src="<?=get_image_asset('profile_small.jpg"')?> /-->
                            <i class="fa fa-user-circle-o fa-5x"></i>
                        </span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?=@$this->session->userdata('user')->nm_usuario;?></strong>
                            </span> <span class="text-muted text-xs block"><?=@$this->session->userdata('grupos');?> <b class="caret"></b></span> </span>
                        </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a href="#"><i class="fa fa-plus-circle"></i> Adicionar foto</a></li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:logout('<?=base_url("home/logout")?>');">
                                    <i class="fa fa-sign-out"></i> Sair
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="logo-element">
                        SISAva+
                    </div>
                </li>
                <li>
                    <a href="<?=base_url()?>"><i class="fa fa-home"></i> <span class="nav-label">Início</span></a>
                </li>
                <li>
                    <a href="<?=base_url('parametros')?>"><i class="fa fa-edit"></i> <span class="nav-label">Parâmetros</span></a>
                </li>
                <li class="dropdown-submenu submenu hidden-xs background bg3">
                    <a tabindex="-1" href="#" class="submenu"><i class="fa fa-pencil-square-o"></i><span>Avaliações</span> </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?=base_url('avaliacao')?>"
                               data-toggle="tooltip_exp"
                               title="Realize aqui o cadastro das avaliações diagnósticas" ><span>Avaliação</span> </a></li>
                        <li><a href="<?=base_url('questao')?>"
                               data-toggle="tooltip_exp"
                               title="Consulte e cadastre questões para as avaliações" ><span>Questões</span> </a></li>
                    </ul>
                </li>
                <li>
                    <a href="<?=base_url('gabarito')?>"><i class="fa fa-edit"></i> <span class="nav-label">Gabarito</span></a>
                </li>
                <li>
                    <a href="<?=base_url()?>"><i class="fa fa-edit"></i> <span class="nav-label">Relat&oacute;rio</span></a>
                </li>
                <li>
                    <a href="<?=base_url("login")?>"><i class="fa fa-edit"></i> <span class="nav-label">Entrar</span></a>
                </li>
            </ul>

        </div>
</nav>