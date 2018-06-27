<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="loginColumns animated fadeInDown">
    <div class="row">

        <div class="col-md-6">
            <h2 class="font-bold">Bem vindo ao SISAvaliação</h2>
            <p></p>
        </div>
        <div class="col-md-6">
            <div class="ibox-content">
                <div class="alert-container"><?php alert(); ?></div>
                <form role="form" class="m-t" method="post" id="formLogin">
                    <div class="form-group">
                        <input type="text" name="login" class="form-control form-border" placeholder="Usuário ou Email" required="">
                    </div>
                    <div class="form-group">
                        <input type="password" name= senha class="form-control form-border" placeholder="Senha" required="">
                    </div>
                    <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
                    <button type="button" class="btn btn-success block full-width m-b" id="btnVoltar"
                        onclick="<?=setLink(base_url('home'));?>">Voltar</button>
                    <a href="javascript:void(0);" onclick="modalPass();" data-toggle="modal" data-target="#myModal">
                        <i class="fa fa-info-circle"></i> Esqueceu a senha?
                    </a>
                </form>
                <p class="m-t">
                    <small></small>
                </p>
            </div>
        </div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-md-6">
            <strong>Copyright</strong> SISAvaliação &copy; 2018
        </div>
        <div class="col-md-6 text-right">
           <small>Contato: email@email.com.br</small>
        </div>
    </div>
</div>


<form class="form hidden" method="post" action="<?= base_url('recuperar') ?>" id="formRecupSenha">
    <div class="form-group">
        <label class="control-label">E-mail<span class="text-danger">*</span></label>
        <input name="email" id="email" placeholder="Informe seu e-mail" type="text" size="35"
               maxlength="200" class="form-control form-border"/>
    </div>
    <div class="form-group">
        <label class="control-label">CPF<span class="text-danger">*</span></label>
        <input name="cpf" id="cpf" placeholder="Informe seu CPF" type="text" size="20"
               maxlength="14" class="form-control form-border"/>
    </div>
</form>
