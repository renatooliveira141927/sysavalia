<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('templates/bread_template', $breadcrumbs);
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="alert-container"><?php alert();?></div>
        <form id="formSearch" action="" method="get" class="form" role="form">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Usuário:</label>
                        <input type="text" name="nome" id="nome" value="<?= @$_GET['nome']?>" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label class="control-label">E-mail:</label>
                        <input type="text" id="email" name="email" value="<?= @$_GET['email']?>" class="form-control" maxlength="100" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Login:</label>
                        <input type="text" id="login" name="login" value="<?= @$_GET['login']?>"  maxlength="50" class="form-control"/>
                    </div>

                    <div class="form-group">
                        <label class="control-label">CPF:</label>
                        <input type="text" id="cpf" name="cpf" value="<?= @$_GET['cpf']?>"   maxlength="14" class="form-control"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="btn-group" role="group">
                            <button type="submit" id="btSearch" class="btn btn-success"><span class="fa fa-search"></span> Consultar</button>
                            <button type="reset" class="btn btn-default"><span class="fa fa-eraser"></span> Limpar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover table-condensed">
                <thead>
                <tr class="ui-widget-header">
                    <th>Cód.</th>
                    <th>Usuário</th>
                    <th>Login</th>
                    <th>CPF</th>
                    <th>Email</th>
                    <th>Nascimento</th>
                    <th>Sexo</th>
                    <?php if(isUpdate($transacao)) { ?><th></th><?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach($usuarios as $row){
                        $row->fl_sexo == 0 ? $sexo = 'M': $sexo = 'F';
                        $arrayDatas = explode(" ", $row->dt_nascimento); ?>
                    <tr>
		            	<td><?=$row->ci_usuario?></td>
		                <td><?=$row->nm_usuario?></td>
		                <td><?=$row->nm_login?></td>
		                <td><?=mask($row->nm_cpf, '###.###.###-##')?></td>
		                <td><?=$row->ds_email?></td>
		                <td><?=$arrayDatas[0]?></td>
		                <td><?=$sexo?></td>
                        <?php if(isUpdate($transacao)) {?>
                        <td width="30" align="center">
                            <a href="<?= base_url('usuario/editar/' . $row->ci_usuario) ?>" class="btn btn-default btn-xs" title="Editar" data-toggle="tooltip">
                                        <span class="fa fa-pencil"></span>
                            </a>
                        </td>
                        <?php }?>
		        	    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php echo $this->pagination->create_links();?>
        <span id="paginacao-info"><?= $count_results; ?> registros</span>
    </div>
</div>