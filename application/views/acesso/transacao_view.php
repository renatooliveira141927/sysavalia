<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('templates/bread_template', $breadcrumbs);
?>
<div class="panel panel-default">
	<div class="panel-body">
		<div class="alert-container"><?php alert();?></div>
		<form action="<?=base_url('transacao')?>" method="post" class="form">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label  class="control-label">Transação:</label>
							<input type="text" name="search1" id="search1" value="<?php echo @$_POST['search1']; ?>" class="form-control" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label  class="control-label">Label:</label>
							<input type="text" name="search2" id="search2"  value="<?php echo @$_POST['search2']; ?>" class="form-control" />
	
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
		
		<form action="<?=base_url('transacao/remove')?>" method="post" id="formSearch">
            <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
				<thead>
					<tr class="ui-widget-header">
						<?php if(isDelete($transacao)) { ?><th width="25" class="check"><input type="checkbox" id="btCheckAll"/></th><?php } ?>
						<th>ID</th>
						<th>Transação</th>
						<th>LABEL</th>
						<?php if(isUpdate($transacao)) { ?><th></th><?php } ?>
					</tr>
				</thead>
				<tbody>
				<?php
                    foreach($find as $row){
						$tr = '<tr>';
						if(isDelete($transacao) && !$row->fl_using) {
							$tr .= '<td class="check"><input type="checkbox" class="btCheck" name="checkdel[]" value="' . $row->ci_transacao . '"/></td>';
						}else{
							$tr .= '<td ><input type="checkbox" class="bt" name="" disabled/></td>';
						}
						$tr .= '<td>'.$row->ci_transacao.'</td>
								<td>'.$row->nm_transacao.'</td>
								<td>'.$row->nm_label.'</td>';
						if(isUpdate($transacao)) {
							$tr .= '<td width="30" align="center">
										<button onclick="'.setLink(base_url('transacao/editar/'.$row->ci_transacao)).'" type="button" class="btn btn-default btn-xs" title="Editar" data-toggle="tooltip">
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
        <p></p>
        <button id="btDel" type="button" class="btn btn-xs btn-success pull-left" style="margin-top: -7px" title="Excluir selecionados" data-toggle="modal" data-target="#modalExcluir">
            <span class="fa fa-trash"></span> Excluir
        </button>
	</div>
</div>