<?php
/**
 * Created by PhpStorm.
 * User: Renato
 * Date: 23/06/2018
 * Time: 09:13
 */
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('templates/bread_template', $breadcrumbs);
?>
<div class="panel panel-default" xmlns="http://www.w3.org/1999/html">
    <div class="panel-body">
        <div class="alert-container"><?php alert();?></div>
        <form action="" method="post" class="form" id="formInsertEdit" role="form">
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label">Oferta: <span class="text-danger">*</span> </label>
                    <select class="chosen-select form-control" tabindex="2" name="oferta" id="oferta">
                        <?php
                        $option = "<option value=\"\">Selecione um eixo</option>";
                        foreach ($ofertaensino as $values){
                            $selected = "";
                            if(isset($form->oferta) && $form->oferta == $values->ci_ofertaensino){
                                $selected = 'selected=true';
                            }
                            $option .= "<option value=\"$values->ci_ofertaensino\" $selected>$values->ds_ofertaensino</option>";
                        }
                        echo $option;
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="control-label">Disciplina: <span class="text-danger">*</span> </label>
                    <select class="chosen-select form-control" tabindex="2" name="disciplina" id="disciplina">
                        <?php
                        $option = "<option value=\"\">Selecione um eixo</option>";
                        foreach ($disciplinas as $values){
                            $selected = "";
                            if(isset($form->disciplina) && $form->disciplina == $values->ci_disciplina){
                                $selected = 'selected=true';
                            }
                            $option .= "<option value=\"$values->ci_disciplina\" $selected>$values->ds_disciplina</option>";
                        }
                        echo $option;
                        ?>
                    </select>
                </div>
            </div>
        </br>
            <div class="row">
                <div class="col-md-3">
                    <label class="control-label">Início: <span class="text-danger">*</span> </label>
                    <span class="fa fa-calendar"></span>
                    <input type="text" class="form-control date" name="dt_inicio" id="dt_inicio" value="<?=@$form->chpresencial;?>"/>
                </div>
                <div class="col-md-3">
                    <label class="control-label">Fim: <span class="text-danger">*</span> </label>
                    <span class="fa fa-calendar"></span>
                    <input type="text" class="form-control date" name="dt_fim" id="dt_fim" value="<?=@$form->chdistancia;?>"/>

                </div>
                <div class="col-md-2">
                    <label class="control-label">Número de Questões: </label>
                    <input type="text" class="form-control" name="nr_linhas" id="nr_linhas" value="<?=@$form->nr_linhas;?>"/>
                    <input type="hidden"  name="base_url" id="base_url" value="<?=base_url('descritor/busca_descritores')?>"/>
                </div>                
            </div>
         </br>   
            <div class="row">
                    <div class="col-md-2">
                    <div class="btn-group">
                        <label class="control-label"></label>
                        <button type="button" id="btAddDadosProfissionais"  onclick="adicionaItensAvaliacao();" class="btn btn-success"><span class="fa fa-plus"></span> Adicionar</button>
                    </div>
                </div>
            </div>    
        </br>
            <div class="row" id="divgabarito" name="divgabarito">
                   <table id="dadosgabaritoAdd" 
                        class="table table-striped table-responsive table-bordered table-hover table-condensed" >
                            <thead>
                                <tr class="ui-widget-header">
                                    <th style="text-align: center;vertical-align: middle; width: 10%;">Item</th>
                                    <th style="text-align: center;vertical-align: middle; width: 10%;">Resposta</th>
                                    <th style="text-align: center; vertical-align: middle;">Descritor</th>
                                </tr>
                            </thead> 
                            <tbody>
                            </tbody>    
                    </table>        
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
