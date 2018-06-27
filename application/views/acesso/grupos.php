<?php
/**
 * Created by PhpStorm.
 * User: paulo.roberto
 * Date: 14/05/2015
 * Time: 15:41
 */

defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('templates/bread_template', $breadcrumbs);
?>
<!-- FORMULÁRIO DE CADASTRO -->
<div class="panel panel-default">
    <div class="panel-body">
        <div class="alert-container"><?php alert();?></div>
        <form action="<?=base_url('grupos/commit/'.@$rowEdit->ci_grupo)?>" method="post" class="form" id="formInsertEdit" onsubmit="return test();">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Grupo: <span class="text-danger">*</span></label>
                        <input type="text" id="nm_grupo" name="nm_grupo" size="65" value="<?php echo trim(@$rowEdit->nm_grupo); ?>" maxlength="50" style="text-transform: uppercase;" class="form-control" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Nível de Acesso: <span class="text-danger">*</span></label>
                        <select id="fl_nivel_acesso" name="fl_nivel_acesso" class="form-control selectpicker" title="Selecione" data-live-search="true">
                            <option value="0">...</option>
                            <?php
                            foreach($niveis as $key=>$value){
                                if(@$rowEdit->fl_nivel_acesso == $key)
                                    echo "<option value=\"$key\" selected=\"selected\">$key - $value</option>";
                                else
                                    echo "<option value=\"$key\">$key - $value</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <fieldset class="ui-corner-all">
                <legend>Transações</legend>
                <div class="col-md-6">
                    <ul class="list-group" >
                        <li class="list-group-item active">	Possíveis: </li>
                        <div id="box_possiveis" style="overflow-y:scroll; height: 410px; ">
	                        <?php
	                        foreach ($queryPossiveis as $row){
	                            echo '
	                                <li role="presentation" data-idtrans="'.$row->ci_transacao.'" class="list-group-item item_grupo">
	                                    <a href="javascript:void(0)" class="remove-trans hidden"><span class="fa fa-arrow-circle-left fa-lg"></span></a> '.$row->nm_transacao.'
	                                    <a href="javascript:void(0)" class="add-trans pull-right"><span class="fa fa-arrow-circle-right fa-lg"></span></a>
	                                    <span class="pull-right hidden check">
	                                        <input type="hidden" name="transacao[]" value="'.$row->ci_transacao.'" disabled="disabled"/>
	                                        <input type="checkbox" title="Adicionar" data-toggle="tooltip" disabled="disabled" />
	                                        <input type="checkbox" title="Editar" data-toggle="tooltip" disabled="disabled" />
	                                        <input type="checkbox" title="Excluir" data-toggle="tooltip" disabled="disabled" />
	                                    </span>
	                                </li>';
	                        }
	                        ?>
	                     </div>
                    </ul>
                    <ul class="list-group" id="box_footer_possiveis">
                        <li class="list-group-item active">	Transações Possíveis:
                            <span class="pull-right numTotal"></span>
                            <!--span class="pull-right">
                                <span class="fa fa-chevron-left"></span>
                                <label class="paginacao_trans">1 de 2 páginas</label>
                                <span class="fa fa-chevron-right"></span>
                            </span-->
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-group" >
                        <li class="list-group-item active">	Pertencentes ao grupo:
                                <span class="pull-right" style="margin-right: 20px;">
                                    <span class="fa fa-plus"></span>
                                    <span class="fa fa-edit"></span>
                                    <span class="fa fa-trash"></span>
                                </span>
                        </li>
                        <div id="box_selecionadas" style="overflow-y:scroll; height: 410px; ">
	                        <?php
	                        if(@$querySelecionadas){
	                            foreach ($querySelecionadas as $row){
	                                echo '<li role="presentation" data-idtrans="'.$row->ci_transacao.'" class="list-group-item item_grupo">
	                                            <a href="javascript:void(0)" class="remove-trans"><span class="fa fa-arrow-circle-left fa-lg"></span></a> '.strtoupper($row->nm_transacao).'
	                                            <a href="javascript:void(0)" class="add-trans pull-right hidden"><span class="fa fa-arrow-circle-right fa-lg"></span></a>
	                                            <span class="pull-right check">
	                                                <input type="hidden" name="transacao[]" value="'.$row->ci_transacao.'" />
	                                                <input type="checkbox" title="" data-toggle="tooltip" data-original-title="Adicionar" name="insert_'.$row->ci_transacao.'" value="1" '.($row->fl_inserir == 'S' ? 'checked="checked"' : '').'/>
	                                                <input type="checkbox" title="" data-toggle="tooltip" data-original-title="Editar" name="update_'.$row->ci_transacao.'" value="1" '.($row->fl_alterar == 'S' ? 'checked="checked"' : '').'/>
	                                                <input type="checkbox" title="" data-toggle="tooltip" data-original-title="Excluir" name="delete_'.$row->ci_transacao.'" value="1" '.($row->fl_deletar == 'S' ? 'checked="checked"' : '').'/>
	                                            </span>
	                                        </li>';
	                            }
	                        }
	                        ?>
	                    </div>
                    </ul>
                    <ul class="list-group" id="box_footer_selecionadas">
                        <li class="list-group-item active">	Transações Selecionadas:
                            <span class="pull-right numTotal"></span>
                            <!--span class="pull-right">
                                <i class="fa fa-chevron-left"></i>
                                <label class="paginacao_trans"></label>
                                <i class="fa fa-chevron-right"></i>
                            </span -->
                        </li>
                    </ul>
                </div>
            </fieldset>
            <br clear="all">
            <div id="boxRowsHidden" style="display:none;"></div>
            <div align="center"><button class="btn btn-success" id="btInsertEdit" type="submit"><span class="fa fa-save"></span> Salvar</button></div>
        </form>
    </div>
</div>