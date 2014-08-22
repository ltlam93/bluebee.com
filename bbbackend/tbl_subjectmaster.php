<?php

// subject_id
// subject_name
// subject_code
// subject_active
// subject_university
// subject_type
// subject_year
// subject_credits
// subject_credit_hour
// subject_faculty
// subject_dept
// subject_general_faculty_id

?>
<?php if ($tbl_subject->Visible) { ?>
<table cellspacing="0" id="t_tbl_subject" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_tbl_subjectmaster" class="ewTable ewTableSeparate">
	<tbody>
<?php if ($tbl_subject->subject_id->Visible) { // subject_id ?>
		<tr id="r_subject_id">
			<td class="ewTableHeader"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_id->FldCaption() ?></td></tr></table></td>
			<td<?php echo $tbl_subject->subject_id->CellAttributes() ?>><span id="el_tbl_subject_subject_id">
<span<?php echo $tbl_subject->subject_id->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_id->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($tbl_subject->subject_name->Visible) { // subject_name ?>
		<tr id="r_subject_name">
			<td class="ewTableHeader"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_name->FldCaption() ?></td></tr></table></td>
			<td<?php echo $tbl_subject->subject_name->CellAttributes() ?>><span id="el_tbl_subject_subject_name">
<span<?php echo $tbl_subject->subject_name->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_name->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($tbl_subject->subject_code->Visible) { // subject_code ?>
		<tr id="r_subject_code">
			<td class="ewTableHeader"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_code->FldCaption() ?></td></tr></table></td>
			<td<?php echo $tbl_subject->subject_code->CellAttributes() ?>><span id="el_tbl_subject_subject_code">
<span<?php echo $tbl_subject->subject_code->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_code->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($tbl_subject->subject_active->Visible) { // subject_active ?>
		<tr id="r_subject_active">
			<td class="ewTableHeader"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_active->FldCaption() ?></td></tr></table></td>
			<td<?php echo $tbl_subject->subject_active->CellAttributes() ?>><span id="el_tbl_subject_subject_active">
<span<?php echo $tbl_subject->subject_active->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_active->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($tbl_subject->subject_university->Visible) { // subject_university ?>
		<tr id="r_subject_university">
			<td class="ewTableHeader"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_university->FldCaption() ?></td></tr></table></td>
			<td<?php echo $tbl_subject->subject_university->CellAttributes() ?>><span id="el_tbl_subject_subject_university">
<span<?php echo $tbl_subject->subject_university->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_university->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($tbl_subject->subject_type->Visible) { // subject_type ?>
		<tr id="r_subject_type">
			<td class="ewTableHeader"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_type->FldCaption() ?></td></tr></table></td>
			<td<?php echo $tbl_subject->subject_type->CellAttributes() ?>><span id="el_tbl_subject_subject_type">
<span<?php echo $tbl_subject->subject_type->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_type->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($tbl_subject->subject_year->Visible) { // subject_year ?>
		<tr id="r_subject_year">
			<td class="ewTableHeader"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_year->FldCaption() ?></td></tr></table></td>
			<td<?php echo $tbl_subject->subject_year->CellAttributes() ?>><span id="el_tbl_subject_subject_year">
<span<?php echo $tbl_subject->subject_year->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_year->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($tbl_subject->subject_credits->Visible) { // subject_credits ?>
		<tr id="r_subject_credits">
			<td class="ewTableHeader"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_credits->FldCaption() ?></td></tr></table></td>
			<td<?php echo $tbl_subject->subject_credits->CellAttributes() ?>><span id="el_tbl_subject_subject_credits">
<span<?php echo $tbl_subject->subject_credits->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_credits->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($tbl_subject->subject_credit_hour->Visible) { // subject_credit_hour ?>
		<tr id="r_subject_credit_hour">
			<td class="ewTableHeader"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_credit_hour->FldCaption() ?></td></tr></table></td>
			<td<?php echo $tbl_subject->subject_credit_hour->CellAttributes() ?>><span id="el_tbl_subject_subject_credit_hour">
<span<?php echo $tbl_subject->subject_credit_hour->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_credit_hour->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($tbl_subject->subject_faculty->Visible) { // subject_faculty ?>
		<tr id="r_subject_faculty">
			<td class="ewTableHeader"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_faculty->FldCaption() ?></td></tr></table></td>
			<td<?php echo $tbl_subject->subject_faculty->CellAttributes() ?>><span id="el_tbl_subject_subject_faculty">
<span<?php echo $tbl_subject->subject_faculty->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_faculty->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($tbl_subject->subject_dept->Visible) { // subject_dept ?>
		<tr id="r_subject_dept">
			<td class="ewTableHeader"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_dept->FldCaption() ?></td></tr></table></td>
			<td<?php echo $tbl_subject->subject_dept->CellAttributes() ?>><span id="el_tbl_subject_subject_dept">
<span<?php echo $tbl_subject->subject_dept->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_dept->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($tbl_subject->subject_general_faculty_id->Visible) { // subject_general_faculty_id ?>
		<tr id="r_subject_general_faculty_id">
			<td class="ewTableHeader"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_general_faculty_id->FldCaption() ?></td></tr></table></td>
			<td<?php echo $tbl_subject->subject_general_faculty_id->CellAttributes() ?>><span id="el_tbl_subject_subject_general_faculty_id">
<span<?php echo $tbl_subject->subject_general_faculty_id->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_general_faculty_id->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
	</tbody>
</table>
</div>
</td></tr></table>
<br>
<?php } ?>
