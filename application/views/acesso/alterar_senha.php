<?php
/**
 * Created by PhpStorm.
 * User: paulo.roberto
 * Date: 14/05/2015
 * Time: 11:38
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="row wrapper white-bg page-heading">
            <div class="col-lg-10">
                <h2><i class="fa fa-key"></i> Alterar Senha</h2>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">
                                <i class="fa fa-home" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Alterar Senha</li>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-2">

            </div>
        </div>
    </div>
</div>
<div class="panel panel-default">
		<div class="panel-body">
			  <div class="alert-container"><?php alert();?></div>
			<form method="post" id="formInsertEdit" class="form" action="<?=base_url("alterar_senha/alterar")?>">
				<div class="form-group">
		   			<label  class="control-label">Usuario:</label>
	   				<input  type="text" id="nm_usuario" name="nm_usuario" value="<?php echo @$user->nm_usuario;?>" class="form-control" size="30" disabled="disabled"/>
		   		</div>
		   		<div class="form-group">
		   			<label  class="control-label">Senha Atual:</label>
	   				<input type="password" id="nm_senha_atual" name="nm_senha_atual" value="" class="form-control " size="30"/>
		   		</div>
				<div class="row">
					<div class="col-md-6">						
				   		<div class="form-group">
				   			<label  class="control-label">Nova Senha: <span class="text-danger">*</span></label>
				   			<input type="password" id="nm_nova_senha" name="nm_nova_senha" value="" class="form-control" size="30"/>
				   			
							<div class="progress">
								<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
									<span class="pass-force"></span>
								</div>
							</div>
				   		</div>
			   		</div>
			   		<div class="col-md-6">
				   		<div class="form-group">
				   			<label  class="control-label">Repetir Senha: <span class="text-danger">*</span></label>
				   			<input type="password" id="nm_repetir_senha" name="nm_repetir_senha" value=""  class="form-control " size="30"/>
				   			<span class="text-danger" id="pass-tip">Digite uma senha</span>
				   		</div>
				   	</div>
				   	
		   		</div>
				<button id="btInsertEdit" type="button" class="btn btn-success" onclick="submitForm()"> <span class="fa fa-save"></span> Alterar</button>
   				<input type="hidden" name="ci_usuario" value="<?php echo @$user->ci_usuario;?>"/>
    		</form> 
	   </div>
</div>