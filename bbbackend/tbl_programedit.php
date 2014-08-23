<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "tbl_programinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$tbl_program_edit = NULL; // Initialize page object first

class ctbl_program_edit extends ctbl_program {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{9095E487-4467-4C46-97C7-01D1A378652D}";

	// Table name
	var $TableName = 'tbl_program';

	// Page object name
	var $PageObjName = 'tbl_program_edit';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			$html .= "<p class=\"ewMessage\">" . $sMessage . "</p>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewWarningIcon\"></td><td class=\"ewWarningMessage\">" . $sWarningMessage . "</td></tr></table>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewSuccessIcon\"></td><td class=\"ewSuccessMessage\">" . $sSuccessMessage . "</td></tr></table>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewErrorIcon\"></td><td class=\"ewErrorMessage\">" . $sErrorMessage . "</td></tr></table>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p class=\"phpmaker\">" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Fotoer exists, display
			echo "<p class=\"phpmaker\">" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language, $UserAgent;

		// User agent
		$UserAgent = ew_UserAgent();
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (tbl_program)
		if (!isset($GLOBALS["tbl_program"])) {
			$GLOBALS["tbl_program"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tbl_program"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tbl_program', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"];
		$this->program_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["program_id"] <> "")
			$this->program_id->setQueryStringValue($_GET["program_id"]);

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->program_id->CurrentValue == "")
			$this->Page_Terminate("tbl_programlist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("tbl_programlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
		$index = $objForm->Index; // Save form index
		$objForm->Index = -1;
		$confirmPage = (strval($objForm->GetValue("a_confirm")) <> "");
		$objForm->Index = $index; // Restore form index
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->program_id->FldIsDetailKey)
			$this->program_id->setFormValue($objForm->GetValue("x_program_id"));
		if (!$this->program_name->FldIsDetailKey) {
			$this->program_name->setFormValue($objForm->GetValue("x_program_name"));
		}
		if (!$this->program_credits->FldIsDetailKey) {
			$this->program_credits->setFormValue($objForm->GetValue("x_program_credits"));
		}
		if (!$this->program_year->FldIsDetailKey) {
			$this->program_year->setFormValue($objForm->GetValue("x_program_year"));
		}
		if (!$this->program_active->FldIsDetailKey) {
			$this->program_active->setFormValue($objForm->GetValue("x_program_active"));
		}
		if (!$this->program_code->FldIsDetailKey) {
			$this->program_code->setFormValue($objForm->GetValue("x_program_code"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->program_id->CurrentValue = $this->program_id->FormValue;
		$this->program_name->CurrentValue = $this->program_name->FormValue;
		$this->program_credits->CurrentValue = $this->program_credits->FormValue;
		$this->program_year->CurrentValue = $this->program_year->FormValue;
		$this->program_active->CurrentValue = $this->program_active->FormValue;
		$this->program_code->CurrentValue = $this->program_code->FormValue;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->program_id->setDbValue($rs->fields('program_id'));
		$this->program_name->setDbValue($rs->fields('program_name'));
		$this->program_credits->setDbValue($rs->fields('program_credits'));
		$this->program_year->setDbValue($rs->fields('program_year'));
		$this->program_active->setDbValue($rs->fields('program_active'));
		$this->program_code->setDbValue($rs->fields('program_code'));
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// program_id
		// program_name
		// program_credits
		// program_year
		// program_active
		// program_code

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// program_id
			$this->program_id->ViewValue = $this->program_id->CurrentValue;
			$this->program_id->ViewCustomAttributes = "";

			// program_name
			$this->program_name->ViewValue = $this->program_name->CurrentValue;
			$this->program_name->ViewCustomAttributes = "";

			// program_credits
			$this->program_credits->ViewValue = $this->program_credits->CurrentValue;
			$this->program_credits->ViewCustomAttributes = "";

			// program_year
			$this->program_year->ViewValue = $this->program_year->CurrentValue;
			$this->program_year->ViewCustomAttributes = "";

			// program_active
			$this->program_active->ViewValue = $this->program_active->CurrentValue;
			$this->program_active->ViewCustomAttributes = "";

			// program_code
			$this->program_code->ViewValue = $this->program_code->CurrentValue;
			$this->program_code->ViewCustomAttributes = "";

			// program_id
			$this->program_id->LinkCustomAttributes = "";
			$this->program_id->HrefValue = "";
			$this->program_id->TooltipValue = "";

			// program_name
			$this->program_name->LinkCustomAttributes = "";
			$this->program_name->HrefValue = "";
			$this->program_name->TooltipValue = "";

			// program_credits
			$this->program_credits->LinkCustomAttributes = "";
			$this->program_credits->HrefValue = "";
			$this->program_credits->TooltipValue = "";

			// program_year
			$this->program_year->LinkCustomAttributes = "";
			$this->program_year->HrefValue = "";
			$this->program_year->TooltipValue = "";

			// program_active
			$this->program_active->LinkCustomAttributes = "";
			$this->program_active->HrefValue = "";
			$this->program_active->TooltipValue = "";

			// program_code
			$this->program_code->LinkCustomAttributes = "";
			$this->program_code->HrefValue = "";
			$this->program_code->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// program_id
			$this->program_id->EditCustomAttributes = "";
			$this->program_id->EditValue = $this->program_id->CurrentValue;
			$this->program_id->ViewCustomAttributes = "";

			// program_name
			$this->program_name->EditCustomAttributes = "";
			$this->program_name->EditValue = ew_HtmlEncode($this->program_name->CurrentValue);

			// program_credits
			$this->program_credits->EditCustomAttributes = "";
			$this->program_credits->EditValue = ew_HtmlEncode($this->program_credits->CurrentValue);

			// program_year
			$this->program_year->EditCustomAttributes = "";
			$this->program_year->EditValue = ew_HtmlEncode($this->program_year->CurrentValue);

			// program_active
			$this->program_active->EditCustomAttributes = "";
			$this->program_active->EditValue = ew_HtmlEncode($this->program_active->CurrentValue);

			// program_code
			$this->program_code->EditCustomAttributes = "";
			$this->program_code->EditValue = ew_HtmlEncode($this->program_code->CurrentValue);

			// Edit refer script
			// program_id

			$this->program_id->HrefValue = "";

			// program_name
			$this->program_name->HrefValue = "";

			// program_credits
			$this->program_credits->HrefValue = "";

			// program_year
			$this->program_year->HrefValue = "";

			// program_active
			$this->program_active->HrefValue = "";

			// program_code
			$this->program_code->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ew_CheckInteger($this->program_credits->FormValue)) {
			ew_AddMessage($gsFormError, $this->program_credits->FldErrMsg());
		}
		if (!ew_CheckInteger($this->program_year->FormValue)) {
			ew_AddMessage($gsFormError, $this->program_year->FldErrMsg());
		}
		if (!ew_CheckInteger($this->program_active->FormValue)) {
			ew_AddMessage($gsFormError, $this->program_active->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$rsnew = array();

			// program_name
			$this->program_name->SetDbValueDef($rsnew, $this->program_name->CurrentValue, NULL, $this->program_name->ReadOnly);

			// program_credits
			$this->program_credits->SetDbValueDef($rsnew, $this->program_credits->CurrentValue, NULL, $this->program_credits->ReadOnly);

			// program_year
			$this->program_year->SetDbValueDef($rsnew, $this->program_year->CurrentValue, NULL, $this->program_year->ReadOnly);

			// program_active
			$this->program_active->SetDbValueDef($rsnew, $this->program_active->CurrentValue, NULL, $this->program_active->ReadOnly);

			// program_code
			$this->program_code->SetDbValueDef($rsnew, $this->program_code->CurrentValue, NULL, $this->program_code->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($tbl_program_edit)) $tbl_program_edit = new ctbl_program_edit();

// Page init
$tbl_program_edit->Page_Init();

// Page main
$tbl_program_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tbl_program_edit = new ew_Page("tbl_program_edit");
tbl_program_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = tbl_program_edit.PageID; // For backward compatibility

// Form object
var ftbl_programedit = new ew_Form("ftbl_programedit");

// Validate form
ftbl_programedit.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();	
	if (fobj.a_confirm && fobj.a_confirm.value == "F")
		return true;
	var elm, aelm;
	var rowcnt = 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // rowcnt == 0 => Inline-Add
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = "";
		elm = fobj.elements["x" + infix + "_program_credits"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($tbl_program->program_credits->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_program_year"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($tbl_program->program_year->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_program_active"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($tbl_program->program_active->FldErrMsg()) ?>");

		// Set up row object
		ew_ElementsToRow(fobj, infix);

		// Fire Form_CustomValidate event
		if (!this.Form_CustomValidate(fobj))
			return false;
	}

	// Process detail page
	if (fobj.detailpage && fobj.detailpage.value && ewForms[fobj.detailpage.value])
		return ewForms[fobj.detailpage.value].Validate(fobj);
	return true;
}

// Form_CustomValidate event
ftbl_programedit.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbl_programedit.ValidateRequired = true;
<?php } else { ?>
ftbl_programedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $tbl_program->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $tbl_program->getReturnUrl() ?>" id="a_GoBack" class="ewLink"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $tbl_program_edit->ShowPageHeader(); ?>
<?php
$tbl_program_edit->ShowMessage();
?>
<form name="ftbl_programedit" id="ftbl_programedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<br>
<input type="hidden" name="t" value="tbl_program">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_tbl_programedit" class="ewTable">
<?php if ($tbl_program->program_id->Visible) { // program_id ?>
	<tr id="r_program_id"<?php echo $tbl_program->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_program_program_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_program->program_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_program->program_id->CellAttributes() ?>><span id="el_tbl_program_program_id">
<span<?php echo $tbl_program->program_id->ViewAttributes() ?>>
<?php echo $tbl_program->program_id->EditValue ?></span>
<input type="hidden" name="x_program_id" id="x_program_id" value="<?php echo ew_HtmlEncode($tbl_program->program_id->CurrentValue) ?>">
</span><?php echo $tbl_program->program_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbl_program->program_name->Visible) { // program_name ?>
	<tr id="r_program_name"<?php echo $tbl_program->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_program_program_name"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_program->program_name->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_program->program_name->CellAttributes() ?>><span id="el_tbl_program_program_name">
<input type="text" name="x_program_name" id="x_program_name" size="30" maxlength="200" value="<?php echo $tbl_program->program_name->EditValue ?>"<?php echo $tbl_program->program_name->EditAttributes() ?>>
</span><?php echo $tbl_program->program_name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbl_program->program_credits->Visible) { // program_credits ?>
	<tr id="r_program_credits"<?php echo $tbl_program->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_program_program_credits"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_program->program_credits->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_program->program_credits->CellAttributes() ?>><span id="el_tbl_program_program_credits">
<input type="text" name="x_program_credits" id="x_program_credits" size="30" value="<?php echo $tbl_program->program_credits->EditValue ?>"<?php echo $tbl_program->program_credits->EditAttributes() ?>>
</span><?php echo $tbl_program->program_credits->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbl_program->program_year->Visible) { // program_year ?>
	<tr id="r_program_year"<?php echo $tbl_program->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_program_program_year"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_program->program_year->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_program->program_year->CellAttributes() ?>><span id="el_tbl_program_program_year">
<input type="text" name="x_program_year" id="x_program_year" size="30" value="<?php echo $tbl_program->program_year->EditValue ?>"<?php echo $tbl_program->program_year->EditAttributes() ?>>
</span><?php echo $tbl_program->program_year->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbl_program->program_active->Visible) { // program_active ?>
	<tr id="r_program_active"<?php echo $tbl_program->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_program_program_active"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_program->program_active->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_program->program_active->CellAttributes() ?>><span id="el_tbl_program_program_active">
<input type="text" name="x_program_active" id="x_program_active" size="30" value="<?php echo $tbl_program->program_active->EditValue ?>"<?php echo $tbl_program->program_active->EditAttributes() ?>>
</span><?php echo $tbl_program->program_active->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbl_program->program_code->Visible) { // program_code ?>
	<tr id="r_program_code"<?php echo $tbl_program->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_program_program_code"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_program->program_code->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_program->program_code->CellAttributes() ?>><span id="el_tbl_program_program_code">
<input type="text" name="x_program_code" id="x_program_code" size="30" maxlength="200" value="<?php echo $tbl_program->program_code->EditValue ?>"<?php echo $tbl_program->program_code->EditAttributes() ?>>
</span><?php echo $tbl_program->program_code->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<br>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>">
</form>
<script type="text/javascript">
ftbl_programedit.Init();
</script>
<?php
$tbl_program_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbl_program_edit->Page_Terminate();
?>
