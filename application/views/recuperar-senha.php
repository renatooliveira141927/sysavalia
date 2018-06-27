<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="col-md-6 col-md-offset-3 text-center bem-vindo">
    <h2><i class="fa fa-envelope-o"></i> Verifique seu e-mail</h2>
    <p class="text-center">
        Foi enviada uma mensagem para o seu endereço de e-mail <strong><?=$this->input->post('email')?></strong> com os dados para redefinição da sua senha.
        Por favor, acesse seu e-mail e siga as instruções informadas na mensagem.<br/>
    </p>
    <a class="btn btn-success" href="<?=base_url()?>"><i class="fa fa-mail-reply"></i> Voltar</a>
</div>
