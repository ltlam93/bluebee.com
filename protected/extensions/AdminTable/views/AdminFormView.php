<div id="modal-view-{{row.<?php echo $table["primary"] ?>}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
				<h3 id="myModalLabel"><?php echo $table["itemLabel"] ?>'s Details</h3>
			</div>
			<div class="modal-body">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="form-horizontal form-bordered form-validate">
							<?php foreach($table["actions"]["_view"] as $column): $field = $table["fields"][$column]; ?>
								<div class="form-group">
									<label for="<?php echo $column ?>" class="control-label col-lg-3"><?php echo $field["label"] ?></label>
									<div class="col-lg-9">
										<div class="checkbox">
										<?php switch ($field["type"]) {
											case '_checkbox':
												# code...
												?>
													<button type="button" class="btn btn-info {{parseInt(row.<?php echo $column ?>) ? 'btn-primary' : 'btn-danger'}}"><i class="{{parseInt(row.<?php echo $column ?>) ? 'icon-ok' : 'icon-remove'}}"></i></button>
												<?php
												break;
											case '_image':
												?>
													<img src="{{row.<?php echo $column ?>}}" style="max-width:100%" />
												<?php
												break;
											case '_url':
												?>
													<a href="{{row.<?php echo $column ?>}}" target="_blank">Go to link</a>
												<?php
												break;
											case "_dropdown":
												?>
													<span class="label label-info">{{TableConfig.fields.<?php echo $column ?>.list[row.<?php echo $column ?>]}}</span>
												<?php
												break;
											default:
												?>
													<span>{{row.<?php echo $column ?>}}</span>
												<?php
												break;
										} ?>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-default btn-sm" data-dismiss="modal" aria-hidden="true">Close</button>
			</div>
		</div>
	</div>
</div>