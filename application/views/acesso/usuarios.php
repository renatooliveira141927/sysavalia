<?php
/**
 * Created by PhpStorm.
 * User: paulo.roberto
 * Date: 11/05/2015
 * Time: 10:32
 */
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('templates/bread_template', $breadcrumbs);
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="alert-container"><?php alert();?></div>
        <form action="" method="post" class="form" id="formInsertEdit" role="form" onsubmit="return test();">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group" id="unidade-trab">
                        <label class="control-label">Unidade de Trabalho: <span class="text-danger">*</span> </label>
                        <div class="input-group">
                            <input type="text" id="unidade_trabalho" name="unidade_trabalho" autocomplete="off" class="form-control typeahead" maxlength="90"
                                   value="<?=isset($rowEdit) ? @$rowEdit->nm_unidade_trabalho : set_value('unidade_trabalho_hidden');?>"  <?=isset($rowEdit) ? "disabled" :"";?> />
                            <span style="right:40px; top: 10px;" class="fa fa-check form-control-feedback hidden" aria-hidden="true"></span>
                            <input type="hidden" id="cd_unidade" name="cd_unidade" value="<?=isset($rowEdit) ? @$rowEdit->nm_unidade_trabalho_id : set_value('cd_unidade');?>"/>
                            <input type="hidden" id="unidade_trabalho_hidden" name="unidade_trabalho_hidden"
                                   value="<?=isset($rowEdit) ? @$rowEdit->nm_unidade_trabalho : set_value('unidade_trabalho_hidden');?>"/>
                                <span class="input-group-btn">
                                    <button id="edit-unidade" class="btn btn-default"  <?=isset($rowEdit) ? "" :"disabled";?>type="button"><i class="fa fa-pencil-square-o"></i></button>
                                </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Login:<span class="text-danger">*</span></label>
                        <input type="text" id="login" name="login" value="<?=isset($rowEdit) ? $rowEdit->nm_login : set_value('nm_login'); ?>" class="form-control" size="20" maxlength="50"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Nome Completo:<span class="text-danger">*</span></label>
                        <input type="text" id="nome" name="nome" value="<?=isset($rowEdit) ? @$rowEdit->nm_usuario : set_value('nm_usuario'); ?>" class="form-control" size="50" maxlength="90"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>CPF:<span class="text-danger">*</span></label>
                        <input value="<?=isset($rowEdit) ? (@$rowEdit -> nm_cpf ? mask($rowEdit -> nm_cpf, '###.###.###-##') : '') : set_value('cpf'); ?>"
                               type="text" id="cpf" name="cpf" maxlength="14" class="form-control" size="20"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>E-mail:<span class="text-danger">*</span></label>
                        <input type="text" id="email" name="email" value="<?=strtolower(isset($rowEdit) ? @$rowEdit->ds_email : set_value('email')); ?>" class="form-control" size="50" maxlength="100"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Data de Nascimento:<span class="text-danger">*</span></label>
                        <div class="form-group" id="data_nascimento">
                            <div class="input-group date">
                                <input type="text" class="form-control" maxlength="10"
                                       value="<?=isset($rowEdit) ? @$rowEdit->dt_nascimento : set_value('nascimento'); ?>">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label style="margin-bottom: 10px;">Sexo:<span class="text-danger">*</span></label><br />
                        <label><input type="radio" name="sexo" value="0" <?=((isset($rowEdit) ? @$rowEdit->fl_sexo : set_value('sexo')) == 0 ? 'checked="checked"' : ''); ?>/>Masculino</label>&nbsp;
                        <label><input type="radio" name="sexo" value="1" <?=((isset($rowEdit) ? @$rowEdit->fl_sexo : set_value('sexo')) == 1 ? 'checked="checked"' : ''); ?>/>Feminino</label>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-md-12 text-center"><h3>Grupos</h3></div>
            </div>
            <div class="row">
                <div class="selectPicker">
                    <div class="col-sm-5" >
                        <label>Disponíveis</label>
                        <select size="8" class="form-control">
                            <?php
                            if(@$queryGruposDisponiveis){
                                foreach($queryGruposDisponiveis as $row){
                                    echo '<option value="'.$row->ci_grupo.'">'.$row->nm_grupo.'</option>';
                                }
                            } ?>
                        </select>
                    </div>
                    <div class="col-sm-2 text-center" style="padding-top: 20px">
                        <button type="button" id="add-one" class="btn btn-default btn-xs btn_grupo"><i class="fa fa-chevron-right"></i></button><br/>
                        <button type="button" id="remove-one" class="btn btn-default btn-xs btn_grupo"><i class="fa fa-chevron-left"></i></button><br/>
                        <button type="button" id="add-all" class="btn btn-default btn-xs btn_grupo"><i class="fa fa-forward"></i></button><br/>
                        <button type="button" id="remove-all" class="btn btn-default btn-xs btn_grupo"><i class="fa fa-backward"></i></button><br/>
                    </div>
                    <div class="col-sm-5">
                        <label>Vinculados</label>
                        <select name="grupo_select[]" size="8" class="form-control" id="cd_grupo_select" multiple>
                            <?php
                            if(@$queryGruposUtilizados){
                                foreach($queryGruposUtilizados as $row){
                                    echo '<option value="'.$row->ci_grupo.'">'.$row->nm_grupo.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <button id="btInsertEdit" class="btn btn-success btn-adjust" type="submit"><span class="fa fa-save"></span> Salvar</button></td>
                </div>
            </div>
            <div class="form-group">

            </div>
        </form>
        <input type="hidden" id="url" value="<?=$urlPartial?>">
    </div>
</div>