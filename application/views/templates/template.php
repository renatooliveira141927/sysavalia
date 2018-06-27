<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?=base_url()?>/assets/img/favicon.ico" type="image/gif">
    <title><?=$this->config->item('project_title')?></title>
    <?=link_tag('assets/css/bootstrap.min.css'); ?>
    <?=link_tag('assets/font-awesome/css/font-awesome.css'); ?>
    <?=link_tag('assets/css/animate.css'); ?>
    <?=link_tag('assets/css/style.css'); ?>
    <?=link_tag('assets/css/toastr.css'); ?>
    <?=link_tag('assets/css/datepicker3.css'); ?>
    <?=link_tag('assets/css/bootstrap-select.css'); ?>
    <?=link_tag('assets/css/plugins/chosen/bootstrap-chosen.css'); ?>
    <?=link_tag('assets/css/plugins/switchery/switchery.css'); ?>
    <?=link_tag('assets/css/plugins/iCheck/custom.css'); ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"/>
</head>

<body class="">

    <!-- Mainly scripts -->
    <script src="<?=base_url()?>assets/js/jquery.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
    <script src="<?=base_url()?>assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="<?=base_url()?>assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?=base_url()?>assets/js/bootstrap3-typeahead.min.js"></script>
    <script src="<?=base_url()?>assets/js/moment.min.js"></script>
    <script src="<?=base_url()?>assets/js/bootstrap-datetimepicker.min.js"></script>
    <script src="<?=base_url()?>assets/js/bootstrap-datepicker.js"></script>
    <script src="<?=base_url()?>assets/js/datatables.min.js"></script>
    <script src="<?=base_url('assets/js/plugins/chosen/chosen.jquery.js')?>"></script>
    <script src="<?=base_url('assets/js/plugins/switchery/switchery.js')?>"></script>
    <script src="<?=base_url('assets/js/bootstrap-select.min.js')?>"></script>
    <script src="<?=base_url('assets/js/plugins/iCheck/icheck.min.js')?>"></script>

    <!-- Custom and plugin javascript -->
    <script src="<?=base_url()?>assets/js/inspinia.js"></script>
    <script src="<?=base_url()?>assets/js/plugins/pace/pace.min.js"></script>
    <script src="<?=base_url()?>assets/js/base.min.js"></script>
    <script src="<?=base_url()?>assets/js/toastr.min.js"></script>

    <div id="wrapper">

        <?php include('menu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    <!--form role="search" class="navbar-form-custom" action="search_results.html">
                        <div class="form-group">
                            <input type="text" placeholder="Buscar" class="form-control"
                                   name="top-search" id="top-search">
                        </div>
                    </form-->
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <span class="m-r-sm text-muted welcome-message">
                            Bem-vindos ao sistema avaliação SISAVALIAÇÃO.
                        </span>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                            <i class="fa fa-envelope"></i>
                            <span class="label label-warning">0</span>
                        </a>
                        <ul class="dropdown-menu dropdown-messages">
                            <li>
                                <div class="dropdown-messages-box">
                                    <a href="#" class="pull-left">
                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                    </a>
                                    <div class="media-body">
                                        <small class="pull-left">Nenhum mensagem recebida.</small>
                                    </div>
                                </div>
                            </li>
                            <li class="divider"></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                            <i class="fa fa-bell"></i>
                            <span class="label label-primary">0</span>
                        </a>
                        <ul class="dropdown-menu dropdown-alerts">
                            <li>
                                <a href="#">
                                    <div>
                                        <i class="fa fa-envelope fa-fw"></i> Você não possui messagem
                                        <span class="pull-right text-muted small"></span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                            <i class="fa fa-paste" title="Adicionar lembrete." id="btn_note" data-toggle="tooltip" data-placement="bottom"></i>
                            <span class="label label-primary">1</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:logout('<?=base_url("home/logout")?>');">
                            <i class="fa fa-sign-out"></i> Sair
                        </a>
                    </li>
                </ul>

            </nav>
        </div>
            

            <div class="wrapper wrapper-content">
                <?php $this->load->view($view); ?>
            </div>
            <div class="footer">
                <div class="pull-right">

                </div>
                <div>
                    <strong>Copyright</strong> SISAVALIAÇÃO &copy; 2018
                </div>
            </div>

        </div>
        </div>

    <?php writeCSS();?>
    <?php writeJS(); ?>

</body>

</html>
