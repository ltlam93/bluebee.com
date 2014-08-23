<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "tbl_newsinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$tbl_news_edit = NULL; // Initialize page object first

class ctbl_news_edit extends ctbl_news {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{9095E487-4467-4C46-97C7-01D1A378652D}";

	// Table name
	var $TableName = 'tbl_news';

	// Page object name
	var $PageObjName = 'tbl_news_edit';

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

		// Table object (tbl_news)
		if (!isset($GLOBALS["tbl_news"])) {
			$GLOBALS["tbl_news"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tbl_news"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tbl_news', TRUE);

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
		$this->news_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		if (@$_GET["news_id"] <> "")
			$this->news_id->setQueryStringValue($_GET["news_id"]);

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->news_id->CurrentValue == "")
			$this->Page_Terminate("tbl_newslist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("tbl_newslist.php"); // No matching record, return to list
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
		if (!$this->news_id->FldIsDetailKey)
			$this->news_id->setFormValue($objForm->GetValue("x_news_id"));
		if (!$this->news_university->FldIsDetailKey) {
			$this->news_university->setFormValue($objForm->GetValue("x_news_university"));
		}
		if (!$this->news_title->FldIsDetailKey) {
			$this->news_title->setFormValue($objForm->GetValue("x_news_title"));
		}
		if (!$this->news_content->FldIsDetailKey) {
			$this->news_content->setFormValue($objForm->GetValue("x_news_content"));
		}
		if (!$this->news_active->FldIsDetailKey) {
			$this->news_active->setFormValue($objForm->GetValue("x_news_active"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->news_id->CurrentValue = $this->news_id->FormValue;
		$this->news_university->CurrentValue = $this->news_university->FormValue;
		$this->news_title->CurrentValue = $this->news_title->FormValue;
		$this->news_content->CurrentValue = $this->news_content->FormValue;
		$this->news_active->CurrentValue = $this->news_active->FormValue;
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
		$this->news_id->setDbValue($rs->fields('news_id'));
		$this->news_university->setDbValue($rs->fields('news_university'));
		$this->news_title->setDbValue($rs->fields('news_title'));
		$this->news_content->setDbValue($rs->fields('news_content'));
		$this->news_active->setDbValue($rs->fields('news_active'));
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// news_id
		// news_university
		// news_title
		// news_content
		// news_active

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// news_id
			$this->news_id->ViewValue = $this->news_id->CurrentValue;
			$this->news_id->ViewCustomAttributes = "";

			// news_university
			$this->news_university->ViewValue = $this->news_university->CurrentValue;
			$this->news_university->ViewCustomAttributes = "";

			// news_title
			$this->news_title->ViewValue = $this->news_title->CurrentValue;
			$this->news_title->ViewCustomAttributes = "";

			// news_content
			$this->news_content->ViewValue = $this->news_content->CurrentValue;
			$this->news_content->ViewCustomAttributes = "";

			// news_active
			$this->news_active->ViewValue = $this->news_active->CurrentValue;
			$this->news_active->ViewCustomAttributes = "";

			// news_id
			$this->news_id->LinkCustomAttributes = "";
			$this->news_id->HrefValue = "";
			$this->news_id->TooltipValue = "";

			// news_university
			$this->news_university->LinkCustomAttributes = "";
			$this->news_university->HrefValue = "";
			$this->news_university->TooltipValue = "";

			// news_title
			$this->news_title->LinkCustomAttributes = "";
			$this->news_title->HrefValue = "";
			$this->news_title->TooltipValue = "";

			// news_content
			$this->news_content->LinkCustomAttributes = "";
			$this->news_content->HrefValue = "";
			$this->news_content->TooltipValue = "";

			// news_active
			$this->news_active->LinkCustomAttributes = "";
			$this->news_active->HrefValue = "";
			$this->news_active->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// news_id
			$this->news_id->EditCustomAttributes = "";
			$this->news_id->EditValue = $this->news_id->CurrentValue;
			$this->news_id->ViewCustomAttributes = "";

			// news_university
			$this->news_university->EditCustomAttributes = "";
			$this->news_university->EditValue = ew_HtmlEncode($this->news_university->CurrentValue);

			// news_title
			$this->news_title->EditCustomAttributes = "";
			$this->news_title->EditValue = ew_HtmlEncode($this->news_title->CurrentValue);

			// news_content
			$this->news_content->EditCustomAttributes = "";
			$this->news_content->EditValue = ew_HtmlEncode($this->news_content->CurrentValue);

			// news_active
			$this->news_active->EditCustomAttributes = "";
			$this->news_active->EditValue = ew_HtmlEncode($this->news_active->CurrentValue);

			// Edit refer script
			// news_id

			$this->news_id->HrefValue = "";

			// news_university
			$this->news_university->HrefValue = "";

			// news_title
			$this->news_title->HrefValue = "";

			// news_content
			$this->news_content->HrefValue = "";

			// news_active
			$this->news_active->HrefValue = "";
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
		if (!ew_CheckInteger($this->news_university->FormValue)) {
			ew_AddMessage($gsFormError, $this->news_university->FldErrMsg());
		}
		if (!ew_CheckInteger($this->news_title->FormValue)) {
			ew_AddMessage($gsFormError, $this->news_title->FldErrMsg());
		}
		if (!ew_CheckInteger($this->news_active->FormValue)) {
			ew_AddMessage($gsFormError, $this->news_active->FldErrMsg());
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

			// news_university
			$this->news_university->SetDbValueDef($rsnew, $this->news_university->CurrentValue, NULL, $this->news_university->ReadOnly);

			// news_title
			$this->news_title->SetDbValueDef($rsnew, $this->news_title->CurrentValue, NULL, $this->news_title->ReadOnly);

			// news_content
			$this->news_content->SetDbValueDef($rsnew, $this->news_content->CurrentValue, NULL, $this->news_content->ReadOnly);

			// news_active
			$this->news_active->SetDbValueDef($rsnew, $this->news_active->CurrentValue, NULL, $this->news_active->ReadOnly);

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
if (!isset($tbl_news_edit)) $tbl_news_edit = new ctbl_news_edit();

// Page init
$tbl_news_edit->Page_Init();

// Page main
$tbl_news_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tbl_news_edit = new ew_Page("tbl_news_edit");
tbl_news_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = tbl_news_edit.PageID; // For backward compatibility

// Form object
var ftbl_newsedit = new ew_Form("ftbl_newsedit");

// Validate form
ftbl_newsedit.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_news_university"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($tbl_news->news_university->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_news_title"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($tbl_news->news_title->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_news_active"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($tbl_news->news_active->FldErrMsg()) ?>");

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
ftbl_newsedit.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbl_newsedit.ValidateRequired = true;
<?php } else { ?>
ftbl_newsedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $tbl_news->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $tbl_news->getReturnUrl() ?>" id="a_GoBack" class="ewLink"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $tbl_news_edit->ShowPageHeader(); ?>
<?php
$tbl_news_edit->ShowMessage();
?>
<form name="ftbl_newsedit" id="ftbl_newsedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<br>
<input type="hidden" name="t" value="tbl_news">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_tbl_newsedit" class="ewTable">
<?php if ($tbl_news->news_id->Visible) { // news_id ?>
	<tr id="r_news_id"<?php echo $tbl_news->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_news_news_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_news->news_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_news->news_id->CellAttributes() ?>><span id="el_tbl_news_news_id">
<span<?php echo $tbl_news->news_id->ViewAttributes() ?>>
<?php echo $tbl_news->news_id->EditValue ?></span>
<input type="hidden" name="x_news_id" id="x_news_id" value="<?php echo ew_HtmlEncode($tbl_news->news_id->CurrentValue) ?>">
</span><?php echo $tbl_news->news_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbl_news->news_university->Visible) { // news_university ?>
	<tr id="r_news_university"<?php echo $tbl_news->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_news_news_university"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_news->news_university->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_news->news_university->CellAttributes() ?>><span id="el_tbl_news_news_university">
<input type="text" name="x_news_university" id="x_news_university" size="30" value="<?php echo $tbl_news->news_university->EditValue ?>"<?php echo $tbl_news->news_university->EditAttributes() ?>>
</span><?php echo $tbl_news->news_university->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbl_news->news_title->Visible) { // news_title ?>
	<tr id="r_news_title"<?php echo $tbl_news->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_news_news_title"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_news->news_title->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_news->news_title->CellAttributes() ?>><span id="el_tbl_news_news_title">
<input type="text" name="x_news_title" id="x_news_title" size="30" value="<?php echo $tbl_news->news_title->EditValue ?>"<?php echo $tbl_news->news_title->EditAttributes() ?>>
</span><?php echo $tbl_news->news_title->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbl_news->news_content->Visible) { // news_content ?>
	<tr id="r_news_content"<?php echo $tbl_news->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_news_news_content"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_news->news_content->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_news->news_content->CellAttributes() ?>><span id="el_tbl_news_news_content">
<textarea name="x_news_content" id="x_news_content" cols="35" rows="4"<?php echo $tbl_news->news_content->EditAttributes() ?>><?php echo $tbl_news->news_content->EditValue ?></textarea>
</span><?php echo $tbl_news->news_content->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbl_news->news_active->Visible) { // news_active ?>
	<tr id="r_news_active"<?php echo $tbl_news->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_news_news_active"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_news->news_active->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_news->news_active->CellAttributes() ?>><span id="el_tbl_news_news_active">
<input type="text" name="x_news_active" id="x_news_active" size="30" value="<?php echo $tbl_news->news_active->EditValue ?>"<?php echo $tbl_news->news_active->EditAttributes() ?>>
</span><?php echo $tbl_news->news_active->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<br>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>">
</form>
<script type="text/javascript">
ftbl_newsedit.Init();
</script>
<?php
$tbl_news_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbl_news_edit->Page_Terminate();
?>
