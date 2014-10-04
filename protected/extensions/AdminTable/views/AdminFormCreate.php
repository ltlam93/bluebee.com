<div id="modal-create" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
				<h3 id="myModalLabel">Create</h3>
			</div>
			<div class="modal-body">
				<div class="panel panel-default">
					<div class="panel-body">
						<!-- Message box (make sure the id match the form) -->
						<div id="administrator_edit_form_message_error" class="alert alert-error" style="display: none;">
							<button type="button" class="close" data-dismiss="alert">×</button>
							<span id="administrator_edit_form_message_error_placeholder"></span>
						</div>
						<!-- Form -->
						<form id="form-create" action="<?php echo $table["url"] ?>?action=insert" method="POST" class="form-horizontal form-bordered form-validate form-edit" ng-success="form-create" <?php if(isset($table["formUpload"]) && $table["formUpload"]): ?>enctype="multipart/form-data" data-type="iframe" <?php endif; ?>>
							<?php foreach($table["actions"]["_new"]["attr"] as $column): $field = $table["fields"][$column]; ?>
								<div class="form-group">
									<label for="<?php echo $column ?>" class="control-label col-lg-3"><?php echo $field["label"] ?></label>
									<div class="col-lg-9">
										<?php switch ($field["type"]) {
											case '_checkbox':
												# code...
												?>
													<input type="checkbox" class="form-control input-sm" name="<?php echo $column ?>" value="1" />
												<?php
												break;
											case '_html':
												# code...
												?>
													<textarea class="form-control input-sm mce-editor" ng-tinymce name="<?php echo $column ?>"></textarea>
												<?php
												break;
											case '_dropdown':
												?>
													<select name="<?php echo $column ?>" class="form-control input-sm input-select">
														<?php foreach($field["list"] as $value => $label): ?>
															<option value="<?php echo $value ?>"><?php echo $label ?></option>
														<?php endforeach; ?>
													</select>
												<?php
												break;
											case "_image":
												?>
													<input type="file" class="form-control input-sm" name="<?php echo $column ?>" accept="image/*" class="input-sm form-control" />
												<?php
												break;
											case "_textarea":
												?>
													<textarea class="form-control input-sm" name="<?php echo $column ?>" rows="3"></textarea>
												<?php
												break;
											default:
												?>
													<input type="text" class="form-control input-sm" name="<?php echo $column ?>" />
												<?php
												break;
										} ?>
									</div>
								</div>
							<?php endforeach; ?>
						</form>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-sm" data-dismiss="modal" aria-hidden="true">Close</button>
				<button type="button" data-dismiss="modal" data-click="__ajax('#form-create')" class="btn btn-primary btn-sm">Save changes</button>
			</div>
		</div>
	</div>
</div>