<div ng-attr-id="modal-edit-{{row.<?php echo $table["primary"] ?>}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
				<h3 id="myModalLabel">Change <?php echo $table["itemLabel"] ?>'s information</h3>
			</div>
			<div class="modal-body">
				<div class="panel panel-default">
					<div class="panel-body">
						<!-- Message box (make sure the id match the form) -->
						<div id="administrator_edit_form_message_error" class="alert alert-error" style="display: none;">
							<button type="button" class="close" data-dismiss="alert">×</button>
							<span id="administrator_edit_form_message_error_placeholder"></span>
						</div>
						<div class="alert alert-success" id="administrator_edit_form_message_success" style="display: none;">
							<button type="button" class="close" data-dismiss="alert">×</button>
						</div>
						<!-- Form -->
						<form ng-attr-id="form-edit-{{row.<?php echo $table["primary"] ?>}}" action="<?php echo $table["url"] ?>?action=update" method="POST" ng-success="form-edit" <?php if(isset($table["formUpload"]) && $table["formUpload"]): ?>enctype="multipart/form-data" data-type="iframe" <?php endif; ?> class="form-horizontal form-bordered form-validate form-edit">
							<input type="hidden" name="<?php echo $table["primary"] ?>" ng-attr-value="{{row.<?php echo $table["primary"] ?>}}" />
							<?php foreach($table["actions"]["_edit"] as $column): $field = $table["fields"][$column]; ?>
								<div class="form-group">
									<label for="<?php echo $column ?>" class="control-label col-lg-3"><?php echo $field["label"] ?></label>
									<div class="col-lg-9">
										<?php switch ($field["type"]) {
											case '_checkbox':
												# code...
												?>
													<input type="checkbox" class="form-control input-sm" name="<?php echo $column ?>" value="1" ng-checked="parseInt(row.<?php echo $column ?>)" />
												<?php
												break;
											case '_html':
												# code...
												?>
													<textarea class="form-control input-sm mce-editor" ng-tinymce name="<?php echo $column ?>">{{row.<?php echo $column ?>}}</textarea>
												<?php
												break;
											case '_dropdown':
												?>
													<select name="<?php echo $column ?>" class="form-control input-sm input-select">
														<?php foreach($field["list"] as $value => $label): ?>
															<option value="<?php echo $value ?>" ng-selected="row.<?php echo $column ?>=='<?php echo $value ?>'"><?php echo $label ?></option>
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
													<textarea class="form-control input-sm" name="<?php echo $column ?>" class="input-sm form-control" rows="3">{{row.<?php echo $column ?>}}</textarea>
												<?php
												break;
											default:
												?>
													<input type="text" class="form-control input-sm" name="<?php echo $column ?>" value="{{row.<?php echo $column ?>}}" class="input-sm form-control" />
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
				<button type="button" data-dismiss="modal" ng-attr-data-click="__ajax('#form-edit-{{row.<?php echo $table["primary"] ?>}}')" class="btn btn-primary btn-sm">Save changes</button>
			</div>
		</div>
	</div>
</div>