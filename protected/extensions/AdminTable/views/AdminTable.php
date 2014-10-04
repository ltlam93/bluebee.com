<?php $this->renderAdditionalFiles("beginDocument"); ?>

<?php $this->add_asset_extension("admin-table",true,false); ?>
<script>
	var $table = null;
	$(function(){
		window.$table = $("#theTable");
		$("#theTable table").addClass("dataTable");
	});
</script>

<style>
	.bootstrap-select:not([class*="span"]):not([class*="col-"]):not([class*="form-control"]){
		width:75px;
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
		background-image:url(<?php echo Yii::app()->theme->baseUrl."/assets/img/loading3.gif" ?>);
	}
	#theTable table thead tr.thetitle th.orderable
	{
		cursor:pointer;
	}
	#theTable table
	{
		table-layout:fixed;
		width:100%;
	}
	#theTable table td, #theTable table th
	{
		color:#000;
	}
	.text_filter
	{
		max-width:90%;
	}
	.table-draggable > tr
	{
		cursor:move;
	}
	.pagination
	{
		margin-top:0px;
	}
	body.modal-open, .modal-open .navbar-fixed-top, .modal-open .navbar-fixed-bottom
	{
		margin-right:0px;
	}
	#theTable .modal .modal-header
	{
		margin:0px !important;
		padding-bottom:0px;
		padding-top:0px;
		border:none;
	}
	#theTable .modal .modal-body
	{
		margin:0px !important;
		padding-top:0px;
		padding-bottom:0px;
	}
	#theTable .modal .modal-footer
	{
		margin:0px !important;
		padding-top:0px;
		border:none;
	}
	#theTable .modal .modal-header h3
	{
		font-size:22px;
		font-weight:normal !important; 
		text-align:center;
	}
	#theTable .modal .modal-header button
	{
		font-size:18px;
	}
	#theTable .modal label
	{
		font-weight: bold;
	}
	#theTable th
	{
		font-weight: bold;
	}
</style>
<div class="row">
	<div class="col-lg-6">
		<h4>
			<?php echo $table["title"] ?>
		</h4>
	</div>
	<div class="col-lg-6">
		<?php $this->renderAdditionalFiles("rightOfTitle"); ?>		
	</div>
</div>
<?php $this->renderAdditionalFiles("beforeTable"); ?>
<div id="theTable" ng-app="AjaxTable" ng-controller="AjaxTableController" ng-rendered>
	<div class="loading-container" ng-if="loading">
		<div class="loading">
		</div>
	</div>
	<div class="panel panel-default" style="margin-top:5px">
		<div class="panel-body">
			<div class="row" style="margin-left:0px; margin-right:0px;">
				<div class="col-lg-6" style="padding-left:0px;">
					<label style="font-weight:normal">
						<select class="select-dropdown" ng-model="per_page" ng-change="refresh()" style="margin-right:5px;">
							<?php foreach($table["limit_values"] as $value): ?>
								<option value="<?php echo $value ?>"><?php echo $value ?></option>
							<?php endforeach; ?>
						</select>
						entries per page
					</label>
					<div>
						<?php if(isset($table["actions"]["_checkbox"]) && ($checkbox = $table["actions"]["_checkbox"])): ?>
							<!-- Single button -->
							<div class="btn-group text-left">
								<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
								Action <span class="caret"></span>
								</button>
								<ul class="dropdown-menu" role="menu" style="left:auto; right:0px;">
									<?php foreach($checkbox as $action): ?>
										<li><a href="javascript:;" ng-click="doAction('<?php echo $this->createUrl($action["url"]) ?>','<?php echo isset($action["confirm"]) ? $action["confirm"] : "" ?>')"><?php echo $action["label"] ?></a></li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="col-lg-6 text-right" style="padding-right:0px;">
					<?php if($table["actions"]["_search"]): ?>
					<label>
						<span>Search: </span>
						<input type="text" id="search" class="form-control input-sm" placeholder="Search here..." ng-model="search" ng-enter="refresh()" style="display:inline-block; width:auto; margin-left:5px;" />
					</label>
					<?php endif; ?>
				</div>
			</div>
			<table class="table table-hover table-nomargin table-bordered" style="margin-top:15px;">
				<thead>
					<tr class="thetitle" role="row">
						<?php if(isset($table["actions"]["_checkbox"]) && $table["actions"]["_checkbox"]): ?>
							<th class="text-center" style="width:50px;"><input type="checkbox" ng-model="__checkedAll" ng-change="checkAll()" /></th>
						<?php endif; ?>
						<?php foreach($table["columns"] as $column): $field = $table["fields"][$column]; ?>
							<th data-field="<?php echo $column ?>" <?php if($field["orderable"]): ?> ng-click="changeOrder('<?php echo $column ?>')" ng-attr-class="orderable {{orderBy!='<?php echo $column ?>' && 'sorting' || orderType=='desc' && 'sorting_desc' || 'sorting_asc' }}"<?php endif; ?> <?php if($field["type"]=="_checkbox"): ?>style="width:100px;"<?php endif; ?>>
								<?php echo $table["fields"][$column]["label"] ?>
								<?php if($field["orderable"]): ?>
									<i ng-attr-class="pull-right orderable {{orderBy!='<?php echo $column ?>' && 'icon-sort' || orderType=='desc' && 'icon-sort-up' || 'icon-sort-down' }}"></i>
								<?php endif; ?>
							</th>
						<?php endforeach; ?>
						<th>
							Actions
						</th>
					</tr>
					<tr class="thefilter" role="row">
						<?php if(isset($table["actions"]["_checkbox"]) && $table["actions"]["_checkbox"]): ?>
							<th></th>
						<?php endif; ?>
						<?php 
							$temp_columns = array();
							foreach($table["actions"]["_search_advanced"] as $item){
								$temp_columns[$item] = $item;
							}
						?>
						<?php foreach($table["columns"] as $column): $field = $table["fields"][$column]; unset($temp_columns[$column]); ?>
							<th data-field="<?php echo $column ?>">
								<span class="filter_column filter_text">
									<?php if(in_array($column, $table["actions"]["_search_advanced"])): ?>
										<?php $this->renderPartial("ext.AdminTable.views.AdminAdvancedInput",array(
											"field" => $field,
											"column" => $column
										)) ?>
									<?php endif; ?>
								</span>
							</th>
						<?php endforeach; ?>
						<th>
							<?php if(count($temp_columns)): ?>
								<!-- Single button -->
								<div class="btn-group">
									<a href="#modal-filter" data-toggle="modal" class="btn btn-flat btn-rounded flat-white btn-sm">More</a>
									<!-- Modal Filter -->
									<div id="modal-filter" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
													<h3 id="myModalLabel">Filter</h3>
												</div>
												<div class="modal-body">
													<div class="panel panel-default">
														<div class="panel-body form-horizontal">
															<?php foreach($temp_columns as $column): $field = $table["fields"][$column]; ?>
																<div class="form-group">
																	<label class="col-lg-3 control-label"><?php echo $field["label"] ?></label>
																	<div class="col-lg-9">
																		<?php $this->renderPartial("ext.AdminTable.views.AdminAdvancedInput",array(
																			"field" => $field,
																			"column" => $column
																		)) ?>
																	</div>
																</div>
															<?php endforeach; ?>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" ng-click="refresh()" class="btn btn-sm btn-primary" data-dismiss="modal" aria-hidden="true">Search</button>
													<button type="button" class="btn btn-sm" data-dismiss="modal" aria-hidden="true">Close</button>
												</div>
											</div>
										</div>
									</div>
									<!-- /Modal Filter -->
								</div>
							<?php endif; ?>
						</th>
					</tr>
				</thead>
				<tbody id="tableDraggable" <?php if($table["dragToReOrder"]): ?>ui-sortable="sortableOptions" ng-model="rows" class="table-draggable"<?php endif; ?>>
					<tr ng-if="!rows.length && refreshCount > 0">
						<td colspan="<?php echo count($table["columns"])+1 ?>">
							<!-- NO ITEMS -->
							No <?php echo $table["itemLabel"] ?> found
						</td>
					</tr>
					<tr ng-if="rows.length" ng-repeat="row in rows" data-id="{{row.id}}" ng-rendered>
						<?php if(isset($table["actions"]["_checkbox"]) && $table["actions"]["_checkbox"]): ?>
							<td class="text-center" style="width:50px;"><input type="checkbox" ng-model="row.__checked" /></td>
						<?php endif; ?>
						<?php foreach($table["columns"] as $column): $field = $table["fields"][$column]; ?>
							<!-- DISPLAY FIELD -->
							<?php $this->renderPartial("ext.AdminTable.views.AdminFieldDisplay",array(
								"field" => $field,
								"column" => $column
							)) ?>
						<?php endforeach; ?>
						<td>
							<?php if($table["actions"]["_view"]): ?>
								<a href="#modal-view-{{row.<?php echo $table["primary"] ?>}}" class="btn" data-toggle="modal" rel="tooltip" title="View ">
									<i class="icon-eye-open"></i>
								</a>
								<!-- Modal View -->
								<?php $this->renderPartial("ext.AdminTable.views.AdminFormView",array(
									"table" => $table
								)) ?>
								<!-- /Modal View -->
							<?php endif; ?>
							<?php if($table["actions"]["_edit"]): ?>
								<a href="#modal-edit-{{row.<?php echo $table["primary"] ?>}}" class="btn" data-toggle="modal" rel="tooltip" title="Edit ">
									<i class="icon-edit"></i>
								</a>
								<!-- Modal Update -->
								<?php $this->renderPartial("ext.AdminTable.views.AdminFormUpdate",array(
									"table" => $table
								)); ?>
								<!-- /Modal Update -->
							<?php endif; ?>
							<?php if($table["actions"]["_delete"]): ?>
								<?php $this->renderPartial("ext.AdminTable.views.AdminFormDelete",array(
									"table" => $table
								)); ?>
							<?php endif; ?>
							<?php if(isset($table["actions"]["_preview"]) && ($previewColumn = $table["actions"]["_preview"])): ?>
								<a href="#modal-preview-{{row.<?php echo $table["primary"] ?>}}" class="btn" data-toggle="modal" rel="tooltip" title="Preview">
									<i class="icon-zoom-in"></i>
								</a>
								<!-- Modal Preview -->
								<?php $this->renderPartial("ext.AdminTable.views.AdminFormPreview",array(
									"table" => $table
								)); ?>
								<!-- /Modal Preview -->
							<?php endif; ?>
							<?php if($table["actions"]["_link"]): ?>
								<a ng-attr-href="{{_link_template(row,'<?php echo $table["actions"]["_link"] ?>')}}" class="btn">
									<i class="icon-eye-open"></i>
								</a>
							<?php endif; ?>
							<?php foreach($table["actions"]["_customButtons"] as $button): ?>
								<?php $this->renderPartial($button["content"],array(
									"table" => $table
								)) ?>
							<?php endforeach; ?>
						</td>
					</tr>
				</tbody>
			</table>
			<?php $this->renderPartial("ext.AdminTable.views.AdminPagination",array(
				"table" => $table
			)); ?>
		</div>
	</div>
	<?php if($newAction = $table["actions"]["_new"]): ?>
		<div>
			<div class="text-right">
				<?php if($newAction["type"]=="link"): ?>
					<a href="<?php echo $newAction["href"] ?>" class="btn btn-flat btn-rounded flat-danger btn-sm" style="margin-top:20px;"><i class="fa fa-plus"></i> New <?php echo $table["itemLabel"] ?></a>
				<?php elseif($newAction["type"]=="popup"): ?>
					<a href="#modal-create" data-toggle="modal" class="btn btn-flat btn-rounded flat-danger btn-sm" style="margin-top:20px;"><i class="fa fa-plus"></i> New <?php echo $table["itemLabel"] ?></a>
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
<?php $this->renderAdditionalFiles("afterTable"); ?>
<?php $this->renderAdditionalFiles("endDocument"); ?>
<?php



