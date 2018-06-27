<?php
/**
 * Created by PhpStorm.
 * User: Paulo Roberto
 * Date: 11/11/2017
 * Time: 16:17
 */
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('templates/bread_template', $breadcrumbs);
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="alert-container"><?php alert();?></div>
        <form action="<?=base_url('modulos')?>" method="get" class="form">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label  class="control-label">Eixos:</label>
                        <select class="chosen-select form-control"  tabindex="2"
                                name="search1" id="search1">
                            <?php
                            $option = "<option value=\"0\">Selecione um eixo</option>";
                            foreach ($eixos as $values){
                                $selected = "";
                                if(isset($_GET['search1']) && $_GET['search1'] == $values->ci_eixo){
                                    $selected = 'selected=true';
                                }
                                $option .= "<option value=\"$values->ci_eixo\" $selected>$values->ds_eixo</option>";
                            }
                            echo $option;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label  class="control-label">Cursos:</label>
                        <input type="text" name="search2" id="search2"  value="<?php echo @$_GET['search2']; ?>" class="form-control" />

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="btn-group" role="group" aria-label="...">
                            <button type="submit" id="btSearch" class="btn btn-primary"><span class="fa fa-search"></span> Consultar</button>
                            <button type="button" class="btn btn-danger btLimpar"><span class="fa fa-eraser"></span> Limpar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <form action="<?=base_url('modulos/remove')?>" method="post" id="formSearch">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <?php if(isDelete($transacao)) { ?><th width="25" class="check"><input type="checkbox" id="btCheckAll" class="i-checks"/></th><?php } ?>
                        <th>Cursos</th>
                        <th>Eixos</th>
                        <th>Módulos</th>
                        <th>CH Presencial</th>
                        <th>CH à Distancia</th>
                        <?php if(isUpdate($transacao)) { ?><th></th><?php } ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($find as $row){
                        $tr = '<tr>';
                        if(isDelete($transacao)) {
                            $tr .= '<td class="check"><input type="checkbox" class="btCheck i-checks" name="checkdel[]" value="' . $row->ci_curso . '"/></td>';
                        }else{
                            $tr .= '<td ><input type="checkbox" class="bt" name="" disabled/></td>';
                        }
                        $tr .= '<td>'.$row->nm_curso.'</td>
                                        <td>'.$row->ds_eixo.'</td>
                                        <td>'.$row->qdt_modulos.'</td>
                                        <td>'.$row->nr_carga_horaria_presencial.'</td>
                                        <td>'.$row->nr_carga_horaria_distancia.'</td>';
                        if(isUpdate($transacao)) {
                            $tr .= '<td width="30" align="center">
                                                <button onclick="'.setLink(base_url('modulos/editar/'.$row->ci_curso)).'" type="button" class="btn btn-primary btn-rounded btn-xs" title="Editar" data-toggle="tooltip">
                                                    <span class="fa fa-pencil"></span>
                                                </button>
                                            </td>';
                        }
                        $tr.='</tr>';
                        echo $tr;
                    }
                    ?>
                    <tbody>

                    </tbody>
                </table>
                <?php echo $this->pagination->create_links();?>
                <span id="paginacao-info"><?php echo $descrRows; ?></span>
            </form>
        </div>
        <button id="btDel" type="button" class="btn btn-sm btn-primary pull-left" style="margin-top: -10px" title="Excluir selecionados" data-toggle="modal" data-target="#modalExcluir">
            <span class="fa fa-trash"></span> Excluir
        </button>
    </div>
</div>
