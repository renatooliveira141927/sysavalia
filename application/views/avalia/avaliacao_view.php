<?php
/**
 * Created by PhpStorm.
 * User: Renato
 * Date: 22/06/2018
 * Time: 22:44
 */
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('templates/bread_template', $breadcrumbs);
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="alert-container"><?php alert();?></div>
        <form action="<?=base_url('cursos')?>" method="get" class="form">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label  class="control-label">Eixos:</label>

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
    </div>
</div>
