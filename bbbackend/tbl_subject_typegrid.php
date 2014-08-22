<?php

// Create page object
if (!isset($tbl_subject_type_grid)) $tbl_subject_type_grid = new ctbl_subject_type_grid();

// Page init
$tbl_subject_type_grid->Page_Init();

// Page main
$tbl_subject_type_grid->Page_Main();
?>
<?php if ($tbl_subject_type->Export == "") { ?>
<script type="text/javascript">

// Page object
var tbl_subject_type_grid = new ew_Page("tbl_subject_type_grid");
tbl_subject_type_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = tbl_subject_type_grid.PageID; // For backward compatibility

// Form object
var ftbl_subject_typegrid = new ew_Form("ftbl_subject_typegrid");

// Validate form
ftbl_subject_typegrid.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();	
	if (fobj.a_confirm && fobj.a_confirm.value == "F")
		return true;
	var elm, aelm;
	var rowcnt = (fobj.key_count) ? Number(fobj.key_count.value) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // rowcnt == 0 => Inline-Add
	var addcnt = 0;
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = (fobj.key_count) ? String(i) : "";
		var checkrow = (fobj.a_list && fobj.a_list.value == "gridinsert") ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
		elm = fobj.elements["x" + infix + "_is_active"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($tbl_subject_type->is_active->FldErrMsg()) ?>");

		// Set up row object
		ew_ElementsToRow(fobj, infix);

		// Fire Form_CustomValidate event
		if (!this.Form_CustomValidate(fobj))
			return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ftbl_subject_typegrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "subject_type_name", false)) return false;
	if (ew_ValueChanged(fobj, infix, "is_active", false)) return false;
	return true;
}

// Form_CustomValidate event
ftbl_subject_typegrid.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbl_subject_typegrid.ValidateRequired = true;
<?php } else { ?>
ftbl_subject_typegrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($tbl_subject_type->CurrentAction == "gridadd") {
	if ($tbl_subject_type->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$tbl_subject_type_grid->TotalRecs = $tbl_subject_type->SelectRecordCount();
			$tbl_subject_type_grid->Recordset = $tbl_subject_type_grid->LoadRecordset($tbl_subject_type_grid->StartRec-1, $tbl_subject_type_grid->DisplayRecs);
		} else {
			if ($tbl_subject_type_grid->Recordset = $tbl_subject_type_grid->LoadRecordset())
				$tbl_subject_type_grid->TotalRecs = $tbl_subject_type_grid->Recordset->RecordCount();
		}
		$tbl_subject_type_grid->StartRec = 1;
		$tbl_subject_type_grid->DisplayRecs = $tbl_subject_type_grid->TotalRecs;
	} else {
		$tbl_subject_type->CurrentFilter = "0=1";
		$tbl_subject_type_grid->StartRec = 1;
		$tbl_subject_type_grid->DisplayRecs = $tbl_subject_type->GridAddRowCount;
	}
	$tbl_subject_type_grid->TotalRecs = $tbl_subject_type_grid->DisplayRecs;
	$tbl_subject_type_grid->StopRec = $tbl_subject_type_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$tbl_subject_type_grid->TotalRecs = $tbl_subject_type->SelectRecordCount();
	} else {
		if ($tbl_subject_type_grid->Recordset = $tbl_subject_type_grid->LoadRecordset())
			$tbl_subject_type_grid->TotalRecs = $tbl_subject_type_grid->Recordset->RecordCount();
	}
	$tbl_subject_type_grid->StartRec = 1;
	$tbl_subject_type_grid->DisplayRecs = $tbl_subject_type_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$tbl_subject_type_grid->Recordset = $tbl_subject_type_grid->LoadRecordset($tbl_subject_type_grid->StartRec-1, $tbl_subject_type_grid->DisplayRecs);
}
?>
<p style="white-space: nowrap;"><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php if ($tbl_subject_type->CurrentMode == "add" || $tbl_subject_type->CurrentMode == "copy") { ?><?php echo $Language->Phrase("Add") ?><?php } elseif ($tbl_subject_type->CurrentMode == "edit") { ?><?php echo $Language->Phrase("Edit") ?><?php } ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $tbl_subject_type->TableCaption() ?></span></p>
</p>
<?php $tbl_subject_type_grid->ShowPageHeader(); ?>
<?php
$tbl_subject_type_grid->ShowMessage();
?>
<br>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="ftbl_subject_typegrid" class="ewForm">
<div id="gmp_tbl_subject_type" class="ewGridMiddlePanel">
<table id="tbl_tbl_subject_typegrid" class="ewTable ewTableSeparate">
<?php echo $tbl_subject_type->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$tbl_subject_type_grid->RenderListOptions();

// Render list options (header, left)
$tbl_subject_type_grid->ListOptions->Render("header", "left");
?>
<?php if ($tbl_subject_type->id->Visible) { // id ?>
	<?php if ($tbl_subject_type->SortUrl($tbl_subject_type->id) == "") { ?>
		<td><span id="elh_tbl_subject_type_id" class="tbl_subject_type_id"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $tbl_subject_type->id->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div><span id="elh_tbl_subject_type_id" class="tbl_subject_type_id">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $tbl_subject_type->id->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($tbl_subject_type->id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($tbl_subject_type->id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($tbl_subject_type->subject_type_name->Visible) { // subject_type_name ?>
	<?php if ($tbl_subject_type->SortUrl($tbl_subject_type->subject_type_name) == "") { ?>
		<td><span id="elh_tbl_subject_type_subject_type_name" class="tbl_subject_type_subject_type_name"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $tbl_subject_type->subject_type_name->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div><span id="elh_tbl_subject_type_subject_type_name" class="tbl_subject_type_subject_type_name">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $tbl_subject_type->subject_type_name->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($tbl_subject_type->subject_type_name->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($tbl_subject_type->subject_type_name->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($tbl_subject_type->is_active->Visible) { // is_active ?>
	<?php if ($tbl_subject_type->SortUrl($tbl_subject_type->is_active) == "") { ?>
		<td><span id="elh_tbl_subject_type_is_active" class="tbl_subject_type_is_active"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $tbl_subject_type->is_active->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div><span id="elh_tbl_subject_type_is_active" class="tbl_subject_type_is_active">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $tbl_subject_type->is_active->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($tbl_subject_type->is_active->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($tbl_subject_type->is_active->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$tbl_subject_type_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$tbl_subject_type_grid->StartRec = 1;
$tbl_subject_type_grid->StopRec = $tbl_subject_type_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue("key_count") && ($tbl_subject_type->CurrentAction == "gridadd" || $tbl_subject_type->CurrentAction == "gridedit" || $tbl_subject_type->CurrentAction == "F")) {
		$tbl_subject_type_grid->KeyCount = $objForm->GetValue("key_count");
		$tbl_subject_type_grid->StopRec = $tbl_subject_type_grid->KeyCount;
	}
}
$tbl_subject_type_grid->RecCnt = $tbl_subject_type_grid->StartRec - 1;
if ($tbl_subject_type_grid->Recordset && !$tbl_subject_type_grid->Recordset->EOF) {
	$tbl_subject_type_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $tbl_subject_type_grid->StartRec > 1)
		$tbl_subject_type_grid->Recordset->Move($tbl_subject_type_grid->StartRec - 1);
} elseif (!$tbl_subject_type->AllowAddDeleteRow && $tbl_subject_type_grid->StopRec == 0) {
	$tbl_subject_type_grid->StopRec = $tbl_subject_type->GridAddRowCount;
}

// Initialize aggregate
$tbl_subject_type->RowType = EW_ROWTYPE_AGGREGATEINIT;
$tbl_subject_type->ResetAttrs();
$tbl_subject_type_grid->RenderRow();
if ($tbl_subject_type->CurrentAction == "gridadd")
	$tbl_subject_type_grid->RowIndex = 0;
if ($tbl_subject_type->CurrentAction == "gridedit")
	$tbl_subject_type_grid->RowIndex = 0;
while ($tbl_subject_type_grid->RecCnt < $tbl_subject_type_grid->StopRec) {
	$tbl_subject_type_grid->RecCnt++;
	if (intval($tbl_subject_type_grid->RecCnt) >= intval($tbl_subject_type_grid->StartRec)) {
		$tbl_subject_type_grid->RowCnt++;
		if ($tbl_subject_type->CurrentAction == "gridadd" || $tbl_subject_type->CurrentAction == "gridedit" || $tbl_subject_type->CurrentAction == "F") {
			$tbl_subject_type_grid->RowIndex++;
			$objForm->Index = $tbl_subject_type_grid->RowIndex;
			if ($objForm->HasValue("k_action"))
				$tbl_subject_type_grid->RowAction = strval($objForm->GetValue("k_action"));
			elseif ($tbl_subject_type->CurrentAction == "gridadd")
				$tbl_subject_type_grid->RowAction = "insert";
			else
				$tbl_subject_type_grid->RowAction = "";
		}

		// Set up key count
		$tbl_subject_type_grid->KeyCount = $tbl_subject_type_grid->RowIndex;

		// Init row class and style
		$tbl_subject_type->ResetAttrs();
		$tbl_subject_type->CssClass = "";
		if ($tbl_subject_type->CurrentAction == "gridadd") {
			if ($tbl_subject_type->CurrentMode == "copy") {
				$tbl_subject_type_grid->LoadRowValues($tbl_subject_type_grid->Recordset); // Load row values
				$tbl_subject_type_grid->SetRecordKey($tbl_subject_type_grid->RowOldKey, $tbl_subject_type_grid->Recordset); // Set old record key
			} else {
				$tbl_subject_type_grid->LoadDefaultValues(); // Load default values
				$tbl_subject_type_grid->RowOldKey = ""; // Clear old key value
			}
		} elseif ($tbl_subject_type->CurrentAction == "gridedit") {
			$tbl_subject_type_grid->LoadRowValues($tbl_subject_type_grid->Recordset); // Load row values
		}
		$tbl_subject_type->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($tbl_subject_type->CurrentAction == "gridadd") // Grid add
			$tbl_subject_type->RowType = EW_ROWTYPE_ADD; // Render add
		if ($tbl_subject_type->CurrentAction == "gridadd" && $tbl_subject_type->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$tbl_subject_type_grid->RestoreCurrentRowFormValues($tbl_subject_type_grid->RowIndex); // Restore form values
		if ($tbl_subject_type->CurrentAction == "gridedit") { // Grid edit
			if ($tbl_subject_type->EventCancelled) {
				$tbl_subject_type_grid->RestoreCurrentRowFormValues($tbl_subject_type_grid->RowIndex); // Restore form values
			}
			if ($tbl_subject_type_grid->RowAction == "insert")
				$tbl_subject_type->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$tbl_subject_type->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($tbl_subject_type->CurrentAction == "gridedit" && ($tbl_subject_type->RowType == EW_ROWTYPE_EDIT || $tbl_subject_type->RowType == EW_ROWTYPE_ADD) && $tbl_subject_type->EventCancelled) // Update failed
			$tbl_subject_type_grid->RestoreCurrentRowFormValues($tbl_subject_type_grid->RowIndex); // Restore form values
		if ($tbl_subject_type->RowType == EW_ROWTYPE_EDIT) // Edit row
			$tbl_subject_type_grid->EditRowCnt++;
		if ($tbl_subject_type->CurrentAction == "F") // Confirm row
			$tbl_subject_type_grid->RestoreCurrentRowFormValues($tbl_subject_type_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$tbl_subject_type->RowAttrs = array_merge($tbl_subject_type->RowAttrs, array('data-rowindex'=>$tbl_subject_type_grid->RowCnt, 'id'=>'r' . $tbl_subject_type_grid->RowCnt . '_tbl_subject_type', 'data-rowtype'=>$tbl_subject_type->RowType));

		// Render row
		$tbl_subject_type_grid->RenderRow();

		// Render list options
		$tbl_subject_type_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($tbl_subject_type_grid->RowAction <> "delete" && $tbl_subject_type_grid->RowAction <> "insertdelete" && !($tbl_subject_type_grid->RowAction == "insert" && $tbl_subject_type->CurrentAction == "F" && $tbl_subject_type_grid->EmptyRow())) {
?>
	<tr<?php echo $tbl_subject_type->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tbl_subject_type_grid->ListOptions->Render("body", "left", $tbl_subject_type_grid->RowCnt);
?>
	<?php if ($tbl_subject_type->id->Visible) { // id ?>
		<td<?php echo $tbl_subject_type->id->CellAttributes() ?>><span id="el<?php echo $tbl_subject_type_grid->RowCnt ?>_tbl_subject_type_id" class="tbl_subject_type_id">
<?php if ($tbl_subject_type->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" name="o<?php echo $tbl_subject_type_grid->RowIndex ?>_id" id="o<?php echo $tbl_subject_type_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($tbl_subject_type->id->OldValue) ?>">
<?php } ?>
<?php if ($tbl_subject_type->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span<?php echo $tbl_subject_type->id->ViewAttributes() ?>>
<?php echo $tbl_subject_type->id->EditValue ?></span>
<input type="hidden" name="x<?php echo $tbl_subject_type_grid->RowIndex ?>_id" id="x<?php echo $tbl_subject_type_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($tbl_subject_type->id->CurrentValue) ?>">
<?php } ?>
<?php if ($tbl_subject_type->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tbl_subject_type->id->ViewAttributes() ?>>
<?php echo $tbl_subject_type->id->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $tbl_subject_type_grid->RowIndex ?>_id" id="x<?php echo $tbl_subject_type_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($tbl_subject_type->id->FormValue) ?>">
<input type="hidden" name="o<?php echo $tbl_subject_type_grid->RowIndex ?>_id" id="o<?php echo $tbl_subject_type_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($tbl_subject_type->id->OldValue) ?>">
<?php } ?>
</span></td>
	<?php } ?>
<a id="<?php echo $tbl_subject_type_grid->PageObjName . "_row_" . $tbl_subject_type_grid->RowCnt ?>"></a>
	<?php if ($tbl_subject_type->subject_type_name->Visible) { // subject_type_name ?>
		<td<?php echo $tbl_subject_type->subject_type_name->CellAttributes() ?>><span id="el<?php echo $tbl_subject_type_grid->RowCnt ?>_tbl_subject_type_subject_type_name" class="tbl_subject_type_subject_type_name">
<?php if ($tbl_subject_type->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $tbl_subject_type_grid->RowIndex ?>_subject_type_name" id="x<?php echo $tbl_subject_type_grid->RowIndex ?>_subject_type_name" size="30" maxlength="255" value="<?php echo $tbl_subject_type->subject_type_name->EditValue ?>"<?php echo $tbl_subject_type->subject_type_name->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $tbl_subject_type_grid->RowIndex ?>_subject_type_name" id="o<?php echo $tbl_subject_type_grid->RowIndex ?>_subject_type_name" value="<?php echo ew_HtmlEncode($tbl_subject_type->subject_type_name->OldValue) ?>">
<?php } ?>
<?php if ($tbl_subject_type->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $tbl_subject_type_grid->RowIndex ?>_subject_type_name" id="x<?php echo $tbl_subject_type_grid->RowIndex ?>_subject_type_name" size="30" maxlength="255" value="<?php echo $tbl_subject_type->subject_type_name->EditValue ?>"<?php echo $tbl_subject_type->subject_type_name->EditAttributes() ?>>
<?php } ?>
<?php if ($tbl_subject_type->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tbl_subject_type->subject_type_name->ViewAttributes() ?>>
<?php echo $tbl_subject_type->subject_type_name->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $tbl_subject_type_grid->RowIndex ?>_subject_type_name" id="x<?php echo $tbl_subject_type_grid->RowIndex ?>_subject_type_name" value="<?php echo ew_HtmlEncode($tbl_subject_type->subject_type_name->FormValue) ?>">
<input type="hidden" name="o<?php echo $tbl_subject_type_grid->RowIndex ?>_subject_type_name" id="o<?php echo $tbl_subject_type_grid->RowIndex ?>_subject_type_name" value="<?php echo ew_HtmlEncode($tbl_subject_type->subject_type_name->OldValue) ?>">
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($tbl_subject_type->is_active->Visible) { // is_active ?>
		<td<?php echo $tbl_subject_type->is_active->CellAttributes() ?>><span id="el<?php echo $tbl_subject_type_grid->RowCnt ?>_tbl_subject_type_is_active" class="tbl_subject_type_is_active">
<?php if ($tbl_subject_type->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $tbl_subject_type_grid->RowIndex ?>_is_active" id="x<?php echo $tbl_subject_type_grid->RowIndex ?>_is_active" size="30" value="<?php echo $tbl_subject_type->is_active->EditValue ?>"<?php echo $tbl_subject_type->is_active->EditAttributes() ?>>
<input type="hidden" name="o<?php echo $tbl_subject_type_grid->RowIndex ?>_is_active" id="o<?php echo $tbl_subject_type_grid->RowIndex ?>_is_active" value="<?php echo ew_HtmlEncode($tbl_subject_type->is_active->OldValue) ?>">
<?php } ?>
<?php if ($tbl_subject_type->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $tbl_subject_type_grid->RowIndex ?>_is_active" id="x<?php echo $tbl_subject_type_grid->RowIndex ?>_is_active" size="30" value="<?php echo $tbl_subject_type->is_active->EditValue ?>"<?php echo $tbl_subject_type->is_active->EditAttributes() ?>>
<?php } ?>
<?php if ($tbl_subject_type->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tbl_subject_type->is_active->ViewAttributes() ?>>
<?php echo $tbl_subject_type->is_active->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $tbl_subject_type_grid->RowIndex ?>_is_active" id="x<?php echo $tbl_subject_type_grid->RowIndex ?>_is_active" value="<?php echo ew_HtmlEncode($tbl_subject_type->is_active->FormValue) ?>">
<input type="hidden" name="o<?php echo $tbl_subject_type_grid->RowIndex ?>_is_active" id="o<?php echo $tbl_subject_type_grid->RowIndex ?>_is_active" value="<?php echo ew_HtmlEncode($tbl_subject_type->is_active->OldValue) ?>">
<?php } ?>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$tbl_subject_type_grid->ListOptions->Render("body", "right", $tbl_subject_type_grid->RowCnt);
?>
	</tr>
<?php if ($tbl_subject_type->RowType == EW_ROWTYPE_ADD || $tbl_subject_type->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ftbl_subject_typegrid.UpdateOpts(<?php echo $tbl_subject_type_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($tbl_subject_type->CurrentAction <> "gridadd" || $tbl_subject_type->CurrentMode == "copy")
		if (!$tbl_subject_type_grid->Recordset->EOF) $tbl_subject_type_grid->Recordset->MoveNext();
}
?>
<?php
	if ($tbl_subject_type->CurrentMode == "add" || $tbl_subject_type->CurrentMode == "copy" || $tbl_subject_type->CurrentMode == "edit") {
		$tbl_subject_type_grid->RowIndex = '$rowindex$';
		$tbl_subject_type_grid->LoadDefaultValues();

		// Set row properties
		$tbl_subject_type->ResetAttrs();
		$tbl_subject_type->RowAttrs = array_merge($tbl_subject_type->RowAttrs, array('data-rowindex'=>$tbl_subject_type_grid->RowIndex, 'id'=>'r0_tbl_subject_type', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($tbl_subject_type->RowAttrs["class"], "ewTemplate");
		$tbl_subject_type->RowType = EW_ROWTYPE_ADD;

		// Render row
		$tbl_subject_type_grid->RenderRow();

		// Render list options
		$tbl_subject_type_grid->RenderListOptions();
		$tbl_subject_type_grid->StartRowCnt = 0;
?>
	<tr<?php echo $tbl_subject_type->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tbl_subject_type_grid->ListOptions->Render("body", "left", $tbl_subject_type_grid->RowIndex);
?>
	<?php if ($tbl_subject_type->id->Visible) { // id ?>
		<td><span id="el$rowindex$_tbl_subject_type_id" class="tbl_subject_type_id">
<?php if ($tbl_subject_type->CurrentAction <> "F") { ?>
<?php } else { ?>
<span<?php echo $tbl_subject_type->id->ViewAttributes() ?>>
<?php echo $tbl_subject_type->id->ViewValue ?></span>
<input type="hidden" name="x<?php echo $tbl_subject_type_grid->RowIndex ?>_id" id="x<?php echo $tbl_subject_type_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($tbl_subject_type->id->FormValue) ?>">
<?php } ?>
<input type="hidden" name="o<?php echo $tbl_subject_type_grid->RowIndex ?>_id" id="o<?php echo $tbl_subject_type_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($tbl_subject_type->id->OldValue) ?>">
</span></td>
	<?php } ?>
	<?php if ($tbl_subject_type->subject_type_name->Visible) { // subject_type_name ?>
		<td><span id="el$rowindex$_tbl_subject_type_subject_type_name" class="tbl_subject_type_subject_type_name">
<?php if ($tbl_subject_type->CurrentAction <> "F") { ?>
<input type="text" name="x<?php echo $tbl_subject_type_grid->RowIndex ?>_subject_type_name" id="x<?php echo $tbl_subject_type_grid->RowIndex ?>_subject_type_name" size="30" maxlength="255" value="<?php echo $tbl_subject_type->subject_type_name->EditValue ?>"<?php echo $tbl_subject_type->subject_type_name->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $tbl_subject_type->subject_type_name->ViewAttributes() ?>>
<?php echo $tbl_subject_type->subject_type_name->ViewValue ?></span>
<input type="hidden" name="x<?php echo $tbl_subject_type_grid->RowIndex ?>_subject_type_name" id="x<?php echo $tbl_subject_type_grid->RowIndex ?>_subject_type_name" value="<?php echo ew_HtmlEncode($tbl_subject_type->subject_type_name->FormValue) ?>">
<?php } ?>
<input type="hidden" name="o<?php echo $tbl_subject_type_grid->RowIndex ?>_subject_type_name" id="o<?php echo $tbl_subject_type_grid->RowIndex ?>_subject_type_name" value="<?php echo ew_HtmlEncode($tbl_subject_type->subject_type_name->OldValue) ?>">
</span></td>
	<?php } ?>
	<?php if ($tbl_subject_type->is_active->Visible) { // is_active ?>
		<td><span id="el$rowindex$_tbl_subject_type_is_active" class="tbl_subject_type_is_active">
<?php if ($tbl_subject_type->CurrentAction <> "F") { ?>
<input type="text" name="x<?php echo $tbl_subject_type_grid->RowIndex ?>_is_active" id="x<?php echo $tbl_subject_type_grid->RowIndex ?>_is_active" size="30" value="<?php echo $tbl_subject_type->is_active->EditValue ?>"<?php echo $tbl_subject_type->is_active->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $tbl_subject_type->is_active->ViewAttributes() ?>>
<?php echo $tbl_subject_type->is_active->ViewValue ?></span>
<input type="hidden" name="x<?php echo $tbl_subject_type_grid->RowIndex ?>_is_active" id="x<?php echo $tbl_subject_type_grid->RowIndex ?>_is_active" value="<?php echo ew_HtmlEncode($tbl_subject_type->is_active->FormValue) ?>">
<?php } ?>
<input type="hidden" name="o<?php echo $tbl_subject_type_grid->RowIndex ?>_is_active" id="o<?php echo $tbl_subject_type_grid->RowIndex ?>_is_active" value="<?php echo ew_HtmlEncode($tbl_subject_type->is_active->OldValue) ?>">
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$tbl_subject_type_grid->ListOptions->Render("body", "right", $tbl_subject_type_grid->RowCnt);
?>
<script type="text/javascript">
ftbl_subject_typegrid.UpdateOpts(<?php echo $tbl_subject_type_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($tbl_subject_type->CurrentMode == "add" || $tbl_subject_type->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="key_count" id="key_count" value="<?php echo $tbl_subject_type_grid->KeyCount ?>">
<?php echo $tbl_subject_type_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($tbl_subject_type->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="key_count" id="key_count" value="<?php echo $tbl_subject_type_grid->KeyCount ?>">
<?php echo $tbl_subject_type_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($tbl_subject_type->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" id="detailpage" value="ftbl_subject_typegrid">
</div>
<?php

// Close recordset
if ($tbl_subject_type_grid->Recordset)
	$tbl_subject_type_grid->Recordset->Close();
?>
<?php if (($tbl_subject_type->CurrentMode == "add" || $tbl_subject_type->CurrentMode == "copy" || $tbl_subject_type->CurrentMode == "edit") && $tbl_subject_type->CurrentAction != "F") { // add/copy/edit mode ?>
<div class="ewGridLowerPanel">
<?php if ($tbl_subject_type->AllowAddDeleteRow) { ?>
<?php if ($Security->IsLoggedIn()) { ?>
<span class="phpmaker">
<a href="javascript:void(0);" onclick="ew_AddGridRow(this);"><?php echo $Language->Phrase("AddBlankRow") ?></a>&nbsp;&nbsp;
</span>
<?php } ?>
<?php } ?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($tbl_subject_type->Export == "") { ?>
<script type="text/javascript">
ftbl_subject_typegrid.Init();
</script>
<?php } ?>
<?php
$tbl_subject_type_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$tbl_subject_type_grid->Page_Terminate();
$Page = &$MasterPage;
?>
