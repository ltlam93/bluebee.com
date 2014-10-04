<div id="modal-preview-{{row.<?php echo $table["primary"] ?>}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
				<h3 id="myModalLabel"><?php echo $table["itemLabel"] ?>'s Preview</h3>
			</div>
			<div class="modal-body">
				<div class="panel panel-default">
					<div class="panel-body">
						<div ng-bind-html-unsafe="row.<?php echo $table["actions"]["_preview"] ?>"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-default btn-sm" data-dismiss="modal" aria-hidden="true">Close</button>
			</div>
		</div>
	</div>
</div>