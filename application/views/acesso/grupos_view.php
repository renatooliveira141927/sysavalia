<?php
/**
 * Created by PhpStorm.
 * User: paulo.roberto
 * Date: 14/05/2015
 * Time: 11:38
 */
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('templates/bread_template', $breadcrumbs);
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="alert-container"><?php alert();?></div>
        <form action="<?=base_url('grupos')?>" method="post" class="form">
            <div class="row">
                <div class="col-md-7">
                    <div class="form-group">
                        <label  class="control-label">Grupo:</label>
                        <input type="text" name="search1" id="search1" size="40" value="<?php echo @$_POST['search1']; ?>" class="form-control  col-xs-3" />
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label  class="control-label">Nível de Acesso:</label>
                        <select id="serarch2" name="search2" class="form-control selectpicker" title="Selecione" data-live-search="true">
                            <option value="0">...</option>
                            <?php
                            foreach($niveis as $key=>$value){
                                if(@$_POST['search2'] == $key)
                                    echo "<option value=\"$key\" selected=\"selected\">$key - $value</option>";
                                else
                                    echo "<option value=\"$key\">$key - $value</option>";
                            }
                            ?>
                        </select>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="btn-group" role="group" aria-label="...">
                            <button type="submit" id="btSearch" class="btn btn-success"><span class="fa fa-search"></span> Consultar</button>
                            <button type="button" class="btn btn-default btLimpar"><span class="fa fa-eraser"></span> Limpar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- LISTAGEM DOS REGISTROS -->
        <form action="<?php echo base_url('grupos/remove');?>" method="post" id="formSearch">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover form-horizontal">
                    <thead>
                    <tr class="ui-widget-header">
                        <?php if(isDelete($transacao)) { ?><th width="25" class="check"><input type="checkbox" id="btCheckAll"/></th><?php } ?>
                        <th>Grupo</th>
                        <th>Nível de Acesso</th>
                        <?php if(isUpdate($transacao)) { ?><th></th><?php } ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($find as $row){
                        $tr = '<tr>';
                        if(isDelete($transacao)) {
                            $tr .= '<td class="check"><input type="checkbox" class="btCheck" name="checkdel[]" value="' . $row->ci_grupo . '"/></td>';
                        }
                        $tr .= '<td>'.$row->nm_grupo.'</td>
                               <td>'.$row->fl_nivel_acesso.' - '.@$niveis[$row->fl_nivel_acesso].'</td>';
                        if(isUpdate($transacao)) {
                            $tr .= '<td width="30" align="center">
                                        <button type="button" onclick="'.setLink(base_url('grupos/editar/'.$row->ci_grupo)).'" class="btn btn-default btn-xs" title="Editar" data-toggle="tooltip">
                                            <span class="fa fa-pencil"></span>
                                        </button>
                                    </td>';
                        }
                        $tr.='</tr>';
                        echo $tr;
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <?php echo $this->pagination->create_links();?>
            <span id="paginacao-info"><?php echo $descrRows; ?></span>
        </form>
        <br clear="all">
        <div id="boxRowsHidden" style="display:none;"></div>
        <button id="btDel" class="btn btn-xs btn-success pull-left" style="margin-top: -7px" title="Excluir selecionados" data-toggle="modal" data-target="#modalExcluir"><span class="fa fa-trash"></span> Excluir	</button>
    </div>
</div>