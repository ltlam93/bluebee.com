<?php 
if(!isset($field["searchAdvancedType"]))
	$field["searchAdvancedType"] = $field["type"];
switch ($field["searchAdvancedType"]) {
	case '_checkbox':
		# code...
		?>
			<select class="query-input text_filter input-sm form-control input-select" name="search_advanced[<?php echo $column ?>]" ng-model="search_advanced_<?php echo $column ?>" ng-change="refresh()">
				<option value="">All</option>
				<option value="1">Yes</option>
				<option value="0">No</option>
			</select>
		<?php
		break;
	case '_dropdown':
		?>
			<select class="query-input text_filter form-control input-sm select-dropdown" name="search_advanced[<?php echo $column ?>]" ng-model="search_advanced_<?php echo $column ?>" ng-change="refresh()">
				<option value="">All</option>
				<?php foreach($field["list"] as $value => $label): ?>
					<option value="<?php echo $value ?>"><?php echo $label ?></option>
				<?php endforeach; ?>
			</select>
		<?php
		break;
	case "_number_range":
		?>
			<input type="hidden" class="query-input" name="search_advanced[<?php echo $column ?>][type]" value="range" />
			<div class="div-table">
				<div class="">
					<input type="text" class="text_filter query-input input-sm form-control" placeholder="<?php echo $field["label"]; ?> from" name="search_advanced[<?php echo $column ?>][from]" ng-enter="refresh()" />
				</div>
				<div class="text-right">
					<input type="text" class="text_filter query-input input-sm form-control" placeholder="<?php echo $field["label"]; ?> to" name="search_advanced[<?php echo $column ?>][to]" ng-enter="refresh()" />
				</div>
			</div>
		<?php
		break;
	case "_datetime":
		?>
			<input type="text" class="text_filter query-input input-sm form-control input-date" placeholder="<?php echo $field["label"]; ?>" name="search_advanced[<?php echo $column ?>]" ng-enter="refresh()" />
		<?php
		break;
	case "_datetime_range":
		?>
			<input type="hidden" class="query-input" name="search_advanced[<?php echo $column ?>][type]" value="range" />
			<input type="hidden" class="query-input" name="search_advanced[<?php echo $column ?>][datetime_type]" value="<?php echo isset($field["datetime_type"]) ? $field["datetime_type"] : "datetime" ?>" />
			<div class="div-table">
				<div class="">
					<input type="text" data-format="yyyy-mm-dd" class="text_filter query-input input-sm form-control input-date" placeholder="<?php echo $field["label"]; ?> from" name="search_advanced[<?php echo $column ?>][from]" ng-enter="refresh()" />
				</div>
				<div class="text-right">
					<input type="text" data-format="yyyy-mm-dd" class="text_filter query-input input-sm form-control input-date" placeholder="<?php echo $field["label"]; ?> to" name="search_advanced[<?php echo $column ?>][to]" ng-enter="refresh()" />
				</div>
			</div>
		<?php
		break;
	default:
		?>
			<input type="text" class="text_filter query-input input-sm form-control" placeholder="<?php echo $field["label"]; ?>" name="search_advanced[<?php echo $column ?>]" ng-enter="refresh()" />
		<?php
		break;
} ?>