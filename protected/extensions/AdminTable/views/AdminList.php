<?php 
	$this->add_asset_extension("jquery-mixitup",array(
		"jquery.mixitup.min.js",
		"\$__\$.js"
	),false);
	$this->add_asset_extension("admin-table","admin-table.js",false);
	$this->add_asset_custom(false,"portfolio.css");
?>
<style>
	.mix-grid .mix .mix-details{
		color:inherit;
	}
	.mix-grid .mix a.mix-link{
		display:inline-block;
		position:relative;
		right:auto;
		margin-right:5px;
	}
	.modal .panel-body
	{
		padding:0px !important;
	}
	.orderBy
	{
		min-width: 180px;
	}
	.bootstrap-select:not([class*="span"]):not([class*="col-"]):not([class*="form-control"]){
		width:100px;
	}
	#theTable{
		position:relative;
	}
	#theTable .loading-container
	{
		position:absolute;
		width:100%;
		height:100%;
		background:#fff;
		opacity:0.5;
		z-index:9999;
	}
	#theTable .loading-container .loading
	{
		position:absolute;
		left:50%;
		top:50%;
		width:30px;
		height:30px;
		margin-left:-15px;
		margin-right:-15px;
		background-size:100% 100%;
		background-image:url(/themes/bootstrap/assets/img/loading3.gif);
	}
	#theTable table thead tr.thetitle th.orderable
	{
		cursor:pointer;
	}
</style>
<div  ng-app="AjaxTable" ng-controller="AjaxTableController">
	<div id="theTable">
		<div>
			<label>
				<select class="input-select input-sm" ng-model="per_page" ng-change="refresh()" style="margin-right:5px;">
					<?php foreach($table["limit_values"] as $value): ?>
						<option value="<?php echo $value ?>"><?php echo $value ?></option>
					<?php endforeach; ?>
				</select>
				entries per page
			</label>

			<div class="pull-right">
				<label style="margin-left:10px;">
					<span style="margin-right:15px">Sort by: </span>
					<select class="input-select input-sm orderBy" ng-model="orderBy" ng-change="refresh()" style="margin-right:5px;">
						<?php foreach($table["fields"] as $slug => $field): if(!$field["orderable"])continue; ?>
							<option value="<?php echo $slug ?>"><?php echo $field["label"] ?></option>
						<?php endforeach; ?>
					</select>
				</label>

				<label style="margin-left:10px;">
					<select class="input-select input-sm" ng-model="orderType" ng-change="refresh()" style="margin-right:5px;">
						<option value="asc">ASC</option>
						<option value="desc">DESC</option>
					</select>
				</label>
			</div>
		</div>
		<!-- LIST -->
		<div class="row mix-grid display-mixitup">
			<div class="col-md-3 col-sm-4 mix" ng-repeat="row in rows">
				<div class="mix-inner" ng-rendered>
					<img class="img-responsive" ng-attr-src="{{row.<?php echo $table["displayAsImage"] ?>}}" alt="" style="width:250px; height:190px;">
					<div class="mix-details">
						<h4>{{row.<?php echo $table["displayAsLabel"] ?>}}</h4>
						<div class="mix-action">
							<?php if($table["actions"]["_view"]): ?>
								<a href="#modal-view-{{row.<?php echo $table["primary"] ?>}}" class="mix-link" data-toggle="modal" rel="tooltip" title="View ">
									<i class="icon-eye-open"></i>
								</a>
								<!-- Modal View -->
								<?php $this->renderPartial("ext.AdminTable.views.AdminFormView",array(
									"table" => $table
								)) ?>
								<!-- /Modal View -->
							<?php endif; ?>
							<?php if($table["actions"]["_edit"]): ?>
								<a href="#modal-edit-{{row.<?php echo $table["primary"] ?>}}" class="mix-link" data-toggle="modal" rel="tooltip" title="Edit ">
									<i class="icon-edit"></i>
								</a>
								<!-- Modal Edit -->
								<?php $this->renderPartial("ext.AdminTable.views.AdminFormEdit",array(
									"table" => $table
								)); ?>
								<!-- /Modal Edit -->
							<?php endif; ?>
							<?php if($table["actions"]["_delete"]): ?>
								<?php $this->renderPartial("ext.AdminTable.views.AdminFormDelete",array(
									"table" => $table
								)); ?>
							<?php endif; ?>
							<?php if(isset($table["actions"]["_preview"]) && ($previewColumn = $table["actions"]["_preview"])): ?>
								<a href="#modal-preview-{{row.<?php echo $table["primary"] ?>}}" class="btn" data-toggle="modal" rel="tooltip" title="View ">
									<i class="icon-zoom-in"></i>
								</a>
								<!-- Modal Preview -->
								<?php $this->renderPartial("ext.AdminTable.views.AdminFormPreview",array(
									"table" => $table
								)); ?>
								<!-- /Modal Preview -->
							<?php endif; ?>
							<?php if($table["actions"]["_link"]): ?>
								<a ng-attr-href="{{_link_template(row,'<?php echo $table["actions"]["_link"] ?>')}}" class="mix-link">
									<i class="icon-eye-open"></i>
								</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- PAGINATION -->
		<?php $this->renderPartial("ext.AdminTable.views.AdminPagination",array(
			"table" => $table
		)); ?>
	</div>

	<?php if($newAction = $table["actions"]["_new"]): ?>
		<div>
			<div class="text-right">
				<?php if($newAction["type"]=="link"): ?>
					<a href="<?php echo $newAction["href"] ?>" class="btn btn-primary" style="margin-top:20px;"><i class="fa fa-plus"></i> New <?php echo $table["itemLabel"] ?></a>
				<?php elseif($newAction["type"]=="popup"): ?>
					<a href="#modal-create" data-toggle="modal" class="btn btn-primary" style="margin-top:20px;"><i class="fa fa-plus"></i> New <?php echo $table["itemLabel"] ?></a>
					<!-- Modal Create -->
					<?php $this->renderPartial("ext.AdminTable.views.AdminFormCreate",array(
						"table" => $table
					)); ?>
					<!-- /Modal Create -->
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
</div>

