<?php
/**
 * Created by PhpStorm.
 * User: Renato
 * Date: 25/06/2018
 * Time: 19:43
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
            <div class="container-fluid">
                <div class="row">
                    <label class="control-label">Enunciado: <span class="text-danger">*</span> </label>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-10 nopadding">
                                <textarea id="txtEditor"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </br>
            <div class="row">
                <div class="col-md-2">
                    <label class="control-label">Alternativa: <span class="text-danger">*</span> </label>
                    <input type="text" id="alternativa" size="2"  class="form-control" />
                </div>
                <div class="col-md-4">
                     <label class="control-label">Resposta:  <span class="text-danger">*</span> </label>
                     <input type="text" id="resposta" class="form-control"/>
                </div>
                <div class="col-md-6">
                        <label class="control-label">Análise: <span class="text-danger">*</span> </label>
                        <input type="text" id="analise" class="form-control"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="btn-group">
                        <label class="control-label">&nbsp;</label>
                        <button type="button" id="btAddDadosProfissionais"  onclick="adicionaItensAvaliacao();" class="btn btn-success"><span class="fa fa-plus"></span> Adicionar</button>
                    </div>
                </div>
            </div>
            </br>
            <div class="row">
                <div class="col-md-12" id="divAlternativa" name="divAlternativa">
                    <table id="dadosAlternativa"
                           class="table table-striped table-responsive table-bordered table-hover table-condensed" >
                        <thead>
                        <tr class="ui-widget-header">
                            <th style="text-align: center;vertical-align: middle; width: 10%;">Alternativa</th>
                            <th style="text-align: center;vertical-align: middle; width: 10%;">Resposta</th>
                            <th style="text-align: center; vertical-align: middle;">Analise</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
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