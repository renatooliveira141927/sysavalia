<?php
/**
 * Created by PhpStorm.
 * User: Paulo Roberto
 * Date: 12/11/2017
 * Time: 15:48
 */
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('templates/bread_template', $breadcrumbs);

//trace($form_curso); exit;
//trace($form); //exit;
?>
<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5><?=$form_curso->curso?> <small>Eixo: <?=$form_curso->ds_eixo?></small></h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-md-3 has-success">
                <label class="control-label">Carga Horária (Presencial):</label>
                <pre class="m-t-sm chpresencial"><?=$form_curso->chpresencial?></pre>
            </div>
            <div class="col-md-2">
                <div class="m-r-md inline">
                    <!--<input type="text" value="" class="dial m-r-sm" data-fgColor="#1AB394" data-width="85" data-height="85" data-readOnly=true id="chpresencial_graf"/>-->
                </div>
            </div>
            <div class="col-md-3 has-warning">
                <label class="control-label">Carga Horária (À distancia):</label>
                <pre class="m-t-sm chdistancia"><?=$form_curso->chdistancia?></pre>
            </div>
            <div class="col-md-2">
                <div class="m-r-md inline">
                    <!--<input type="text" value="" class="dial m-r-sm" data-fgColor="#f8ac59" data-width="85" data-height="85" data-readOnly=true id="chdistancia_graf"/>-->
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox float-e-margins box_modulo">
    <div class="ibox-title">
        <h5>Módulos/Disciplina <small>Disciplinas adicionadas</small></h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content">
        <div class="sk-spinner sk-spinner-wave">
            <div class="sk-rect1"></div>
            <div class="sk-rect2"></div>
            <div class="sk-rect3"></div>
            <div class="sk-rect4"></div>
            <div class="sk-rect5"></div>
        </div>
        <div class="row" style="padding-bottom: 10px">
            <div class="text-right">
                <a class="btn btn-primary" id="btn_modal_modulo"><i class="fa fa-plus"></i> Adicionar Módulo</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table_modulo table-hover" id="table_modulo">
                <thead>
                <tr>
                    <th>Módulo </th>
                    <th class='text-center'>CH Presencial</th>
                    <th class='text-center'>CH à Distancia</th>
                    <th class='text-center'>Disciplinas</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                    <?php
                        $tr = "";
                        if(!empty($form)) {
                            foreach ($form as $values) {
                                $tr .= "<tr id=\"tr_$values->ci_modulo\">";
                                $tr .= "<td>$values->ds_modulo</td>";
                                $tr .= "<td class='text-center'>$values->nr_chpresencial</td>";
                                $tr .= "<td class='text-center'>$values->nr_chadistancia</td>";
                                $tr .= "<td class='text-center'><button type=\"button\" class=\"btn btn-outline btn-primary\">$values->qdt_disciplina</button></td>";
                                $tr .= '<td width="30" align="center">
                                                <button type="button" class="btn btn-outline btn-primary btn_disciplina" title="Adicionar Disciplinas" data-toggle="tooltip" 
                                                    id="btn_disciplina_'.$values->ci_modulo.'" data-id="'.$values->ci_modulo.'">
                                                    <span class="fa fa-plus"></span> Adicionar Disciplinas
                                                </button>
                                            </td>';
                                $tr .= '<td width="30" align="center">
                                                <button type="button" class="btn btn-outline btn-danger btn_remove_disc" title="Remover Disciplinas" data-toggle="tooltip" 
                                                    id="btn_remove_disc_'.$values->ci_modulo.'" data-id="'.$values->ci_modulo.'">
                                                    <span class="fa fa-trash"></span>
                                                </button>
                                            </td>';
                                $tr .= "</tr>";
                            }
                        }else{
                            $tr.= "<tr class=\"table-danger tr_messeger\"><td colspan='5' class='text-center'><label><h4>Nenhum registro encontrado</h4></label></td></tr>";
                        }
                        echo $tr;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <input type="hidden" id="url" value="<?=$url?>"/>
    <input type="hidden" id="curso" value="<?=$form_curso->id?>"/>
</div>

<div class="modal" tabindex="-1" role="dialog" id="modal-form">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Adicionar Módulos</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label">Modulo <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" id="modulo_tx" />
                    </div>
                    <div class="col-md-12">
                        <label class="control-label">Descrição</label>
                        <textarea class="form-control" id="modulo_ds" style="resize: none" rows="4"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_modal_add_mod"><i class="fa fa-plus-square-o"></i> Adicionar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="modal_mod_close"><i class="fa fa-remove"></i> Fechar</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-form-disc" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Adicionar Disciplina</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btn_close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-disciplina">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label">Disciplina <span class="text-danger">*</span></label>
                            <div class="input-group m-b">
                                <input type="text" class="form-control typeahead_1" placeholder="Digite a disciplina" name="disciplina"/>
                                <span class="input-group-addon bg-info" id="loading_button">
                                    <i class="fa fa-search"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-2 has-success">
                            <label class="control-label">C.H. Presencial</label>
                            <input type="text" class="form-control" id="ch_presencial" name="chpresencial">
                        </div>
                        <div class="col-md-2 has-warning">
                            <label class="control-label">C.H. à Distancia</label>
                            <input type="text" class="form-control" id="ch_adistancia" name="chadistancia">
                        </div>
                        <input type="hidden" id="id_disciplina" name="id_disciplina"/>
                        <input type="hidden" id="id_modulo" name="id_modulo"/>
                        <div class="col-md-2" style="padding-top: 13px;">
                            <button id="btInsertEdit" class="btn btn-success btn-save" type="submit">
                                <span class="fa fa-plus"></span> Adicionar
                            </button>
                        </div>
                    </div>
                </form>
                <div class="hr-line-dashed"></div>
                <div class="table-responsive">
                    <table class="table table-striped table_modulo table-hover" id="table_disciplina">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Disciplinas</th>
                            <th class='text-center'>CH Presencial</th>
                            <th class='text-center'>CH à Distancia</th>
                            <th width="25" align="center"></th>
                            <th width="25" align="center"></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="col-md-12 text-right">
                        <h6 class="reg_disciplina_mod"></h6>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="modal_disc_close"><i class="fa fa-remove"></i> Fechar</button>
            </div>
        </div>
    </div>
</div>
