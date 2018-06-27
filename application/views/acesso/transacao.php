<?php 
	$ci_transacao = null;
	$nm_transacao = null;
	$nm_label = null;
	if(isset($transacao)){
		$ci_transacao = $transacao->ci_transacao;
		$nm_transacao = $transacao->nm_transacao;
		$nm_label = $transacao->nm_label;
	}
$this->load->view('templates/bread_template', $breadcrumbs);
?>
<div class="panel panel-default">
	<div class="panel-body">
		<div class="alert-container"><?php alert();?></div>
		<form action="<?=base_url('transacao/commit/'.$ci_transacao)?>" id="formInsertEdit" method="post" class="form" onsubmit="return test();">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label  class="control-label">Transação: *</label>
							<input type="text" name="nmTransacao" id="nmTransacao" value="<?php echo $nm_transacao;?>" class="form-control text-uppercase" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label  class="control-label">Label: *</label>
							<input type="text" name="nmlabel" id="nmlabel"  value="<?php echo $nm_label; ?>" class="form-control" />
	
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<div class="btn-group" role="group" aria-label="...">
								<button type="submit" id="btSearch" class="btn btn-success"><span class="fa fa-save"></span> <?=(isset($ci_transacao)? "Atualizar" : "Salvar")?></button>
							</div>
						</div>
					</div>
				</div>
		</form>
	</div>
</div>