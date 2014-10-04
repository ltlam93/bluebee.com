<td data-field="<?php echo $column ?>">
	<?php 
	switch ($field["type"]) {
		case '_dropdown':
			?>
				<span class="label label-info" style="font-size:100%">{{TableConfig.fields.<?php echo $column ?>.list[row.<?php echo $column ?>] && TableConfig.fields.<?php echo $column ?>.list[row.<?php echo $column ?>] || row.<?php echo $column ?>}}</span>
			<?php
			break;
		case '_checkbox':
			?>
				<div class="text-center">
					<button type="button" class="btn btn-sm {{parseInt(row.<?php echo $column ?>) ? 'btn-info' : 'btn-danger'}}"><i class="{{parseInt(row.<?php echo $column ?>) ? 'icon-ok' : 'icon-remove'}}"></i></button>
				</div>
			<?php
			break;
		case '_image':
			?>
				<img ng-attr-src="{{row.<?php echo $column ?>}}" style="max-width:100%" />
			<?php
			break;
		case "_timestamp":
			?>
				{{displayTimestamp(row.<?php echo $column ?>)}}
			<?php
			break;
		default:
			?>
				{{row.<?php echo $column ?>}}
			<?php
			break;
	}?>
</td>