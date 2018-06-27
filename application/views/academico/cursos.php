<?php
/**
 * Created by PhpStorm.
 * User: paulo.roberto
 * Date: 07/11/2017
 * Time: 14:24
 */
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('templates/bread_template', $breadcrumbs);
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="alert-container"><?php alert();?></div>
        <form action="" method="post" class="form" id="formInsertEdit" role="form">
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label">Curso: <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" name="curso" id="curso" value="<?=@$form->curso;?>"/>
                </div>
                <div class="col-md-6">
                    <label class="control-label">Eixo: <span class="text-danger">*</span> </label>
                    <select class="chosen-select form-control" tabindex="2" name="eixos" id="eixos">
                        <?php
                            $option = "<option value=\"\">Selecione um eixo</option>";
                            foreach ($eixos as $values){
                                $selected = "";
                                if(isset($form->eixos) && $form->eixos == $values->ci_eixo){
                                    $selected = 'selected=true';
                                }
                                $option .= "<option value=\"$values->ci_eixo\" $selected>$values->ds_eixo</option>";
                            }
                            echo $option;
                        ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label">Ementa: <span class="text-danger">*</span> </label>
                    <textarea class="form-control" rows="6" name="ementa" id="ementa"
                              maxlength="1000" placeholder="Máximo 1000 caracteres" style="resize: none"><?=@$form->ementa;?></textarea>
                </div>
                <div class="col-md-6">
                    <label class="control-label">Objetivo Geral: <span class="text-danger">*</span> </label>
                    <textarea class="form-control" rows="6" name="objgeral" id="objgeral" maxlength="1000"
                              placeholder="Máximo 1000 caracteres" style="resize: none"><?=@$form->objgeral;?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label">Objetivo Específico: </label>
                    <textarea class="form-control" rows="6" name="objespec" id="objespec" maxlength="1000"
                              placeholder="Máximo 1000 caracteres" style="resize: none"><?=@$form->objespec;?></textarea>
                </div>
                <div class="col-md-6">
                    <label class="control-label">Avaliação: </label>
                    <textarea class="form-control" rows="6" name="avaliacao" id="avaliacao" maxlength="1000"
                              placeholder="Máximo 1000 caracteres" style="resize: none"><?=@$form->avaliacao;?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label class="control-label">Bibliografia: </label>
                    <textarea class="form-control" rows="6" name="bibliografia" id="bibliografia" maxlength="1000"
                              placeholder="Máximo 1000 caracteres" style="resize: none"><?=@$form->bibliografia;?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <label class="control-label">CH Presencial: <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" name="chpresencial" id="chpresencial" value="<?=@$form->chpresencial;?>"/>
                </div>
                <div class="col-md-2">
                    <label class="control-label">CH à distância: </label>
                    <input type="text" class="form-control" name="chdistancia" id="chdistancia" value="<?=@$form->chdistancia;?>"/>
                </div>
                <div class="col-md-1">
                    <label class="control-label">Ativo: <span class="text-danger">*</span> </label>
                    <?php
                        $ativo = 'f';
                        $checked = "";
                        if($form->ativo == 't'){
                            $ativo = 't';
                            $checked = "checked";
                        }
                    ?>
                    <input type="checkbox" class="js-switch" value="true" name="ativo" id="ativo" <?=$checked?> />
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <button id="btInsertEdit" class="btn btn-success btn-save" type="submit">
                        <span class="fa fa-save"></span> <?=$btn?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
