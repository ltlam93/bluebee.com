<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "tbl_postinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$tbl_post_edit = NULL; // Initialize page object first

class ctbl_post_edit extends ctbl_post {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{9095E487-4467-4C46-97C7-01D1A378652D}";

	// Table name
	var $TableName = 'tbl_post';

	// Page object name
	var $PageObjName = 'tbl_post_edit';

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

		// Table object (tbl_post)
		if (!isset($GLOBALS["tbl_post"])) {
			$GLOBALS["tbl_post"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tbl_post"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tbl_post', TRUE);

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
		$this->post_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		if (@$_GET["post_id"] <> "")
			$this->post_id->setQueryStringValue($_GET["post_id"]);

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->post_id->CurrentValue == "")
			$this->Page_Terminate("tbl_postlist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("tbl_postlist.php"); // No matching record, return to list
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
		if (!$this->post_id->FldIsDetailKey)
			$this->post_id->setFormValue($objForm->GetValue("x_post_id"));
		if (!$this->post_author->FldIsDetailKey) {
			$this->post_author->setFormValue($objForm->GetValue("x_post_author"));
		}
		if (!$this->post_date->FldIsDetailKey) {
			$this->post_date->setFormValue($objForm->GetValue("x_post_date"));
			$this->post_date->CurrentValue = ew_UnFormatDateTime($this->post_date->CurrentValue, 5);
		}
		if (!$this->post_content->FldIsDetailKey) {
			$this->post_content->setFormValue($objForm->GetValue("x_post_content"));
		}
		if (!$this->post_title->FldIsDetailKey) {
			$this->post_title->setFormValue($objForm->GetValue("x_post_title"));
		}
		if (!$this->post_active->FldIsDetailKey) {
			$this->post_active->setFormValue($objForm->GetValue("x_post_active"));
		}
		if (!$this->post_rate->FldIsDetailKey) {
			$this->post_rate->setFormValue($objForm->GetValue("x_post_rate"));
		}
		if (!$this->post_type->FldIsDetailKey) {
			$this->post_type->setFormValue($objForm->GetValue("x_post_type"));
		}
		if (!$this->post_class->FldIsDetailKey) {
			$this->post_class->setFormValue($objForm->GetValue("x_post_class"));
		}
		if (!$this->post_group->FldIsDetailKey) {
			$this->post_group->setFormValue($objForm->GetValue("x_post_group"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->post_id->CurrentValue = $this->post_id->FormValue;
		$this->post_author->CurrentValue = $this->post_author->FormValue;
		$this->post_date->CurrentValue = $this->post_date->FormValue;
		$this->post_date->CurrentValue = ew_UnFormatDateTime($this->post_date->CurrentValue, 5);
		$this->post_content->CurrentValue = $this->post_content->FormValue;
		$this->post_title->CurrentValue = $this->post_title->FormValue;
		$this->post_active->CurrentValue = $this->post_active->FormValue;
		$this->post_rate->CurrentValue = $this->post_rate->FormValue;
		$this->post_type->CurrentValue = $this->post_type->FormValue;
		$this->post_class->CurrentValue = $this->post_class->FormValue;
		$this->post_group->CurrentValue = $this->post_group->FormValue;
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
		$this->post_id->setDbValue($rs->fields('post_id'));
		$this->post_author->setDbValue($rs->fields('post_author'));
		$this->post_date->setDbValue($rs->fields('post_date'));
		$this->post_content->setDbValue($rs->fields('post_content'));
		$this->post_title->setDbValue($rs->fields('post_title'));
		$this->post_active->setDbValue($rs->fields('post_active'));
		$this->post_rate->setDbValue($rs->fields('post_rate'));
		$this->post_type->setDbValue($rs->fields('post_type'));
		$this->post_class->setDbValue($rs->fields('post_class'));
		$this->post_group->setDbValue($rs->fields('post_group'));
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// post_id
		// post_author
		// post_date
		// post_content
		// post_title
		// post_active
		// post_rate
		// post_type
		// post_class
		// post_group

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// post_id
			$this->post_id->ViewValue = $this->post_id->CurrentValue;
			$this->post_id->ViewCustomAttributes = "";

			// post_author
			$this->post_author->ViewValue = $this->post_author->CurrentValue;
			$this->post_author->ViewCustomAttributes = "";

			// post_date
			$this->post_date->ViewValue = $this->post_date->CurrentValue;
			$this->post_date->ViewValue = ew_FormatDateTime($this->post_date->ViewValue, 5);
			$this->post_date->ViewCustomAttributes = "";

			// post_content
			$this->post_content->ViewValue = $this->post_content->CurrentValue;
			$this->post_content->ViewCustomAttributes = "";

			// post_title
			$this->post_title->ViewValue = $this->post_title->CurrentValue;
			$this->post_title->ViewCustomAttributes = "";

			// post_active
			$this->post_active->ViewValue = $this->post_active->CurrentValue;
			$this->post_active->ViewCustomAttributes = "";

			// post_rate
			$this->post_rate->ViewValue = $this->post_rate->CurrentValue;
			$this->post_rate->ViewCustomAttributes = "";

			// post_type
			$this->post_type->ViewValue = $this->post_type->CurrentValue;
			$this->post_type->ViewCustomAttributes = "";

			// post_class
			$this->post_class->ViewValue = $this->post_class->CurrentValue;
			$this->post_class->ViewCustomAttributes = "";

			// post_group
			$this->post_group->ViewValue = $this->post_group->CurrentValue;
			$this->post_group->ViewCustomAttributes = "";

			// post_id
			$this->post_id->LinkCustomAttributes = "";
			$this->post_id->HrefValue = "";
			$this->post_id->TooltipValue = "";

			// post_author
			$this->post_author->LinkCustomAttributes = "";
			$this->post_author->HrefValue = "";
			$this->post_author->TooltipValue = "";

			// post_date
			$this->post_date->LinkCustomAttributes = "";
			$this->post_date->HrefValue = "";
			$this->post_date->TooltipValue = "";

			// post_content
			$this->post_content->LinkCustomAttributes = "";
			$this->post_content->HrefValue = "";
			$this->post_content->TooltipValue = "";

			// post_title
			$this->post_title->LinkCustomAttributes = "";
			$this->post_title->HrefValue = "";
			$this->post_title->TooltipValue = "";

			// post_active
			$this->post_active->LinkCustomAttributes = "";
			$this->post_active->HrefValue = "";
			$this->post_active->TooltipValue = "";

			// post_rate
			$this->post_rate->LinkCustomAttributes = "";
			$this->post_rate->HrefValue = "";
			$this->post_rate->TooltipValue = "";

			// post_type
			$this->post_type->LinkCustomAttributes = "";
			$this->post_type->HrefValue = "";
			$this->post_type->TooltipValue = "";

			// post_class
			$this->post_class->LinkCustomAttributes = "";
			$this->post_class->HrefValue = "";
			$this->post_class->TooltipValue = "";

			// post_group
			$this->post_group->LinkCustomAttributes = "";
			$this->post_group->HrefValue = "";
			$this->post_group->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// post_id
			$this->post_id->EditCustomAttributes = "";
			$this->post_id->EditValue = $this->post_id->CurrentValue;
			$this->post_id->ViewCustomAttributes = "";

			// post_author
			$this->post_author->EditCustomAttributes = "";
			$this->post_author->EditValue = ew_HtmlEncode($this->post_author->CurrentValue);

			// post_date
			$this->post_date->EditCustomAttributes = "";
			$this->post_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->post_date->CurrentValue, 5));

			// post_content
			$this->post_content->EditCustomAttributes = "";
			$this->post_content->EditValue = ew_HtmlEncode($this->post_content->CurrentValue);

			// post_title
			$this->post_title->EditCustomAttributes = "";
			$this->post_title->EditValue = ew_HtmlEncode($this->post_title->CurrentValue);

			// post_active
			$this->post_active->EditCustomAttributes = "";
			$this->post_active->EditValue = ew_HtmlEncode($this->post_active->CurrentValue);

			// post_rate
			$this->post_rate->EditCustomAttributes = "";
			$this->post_rate->EditValue = ew_HtmlEncode($this->post_rate->CurrentValue);

			// post_type
			$this->post_type->EditCustomAttributes = "";
			$this->post_type->EditValue = ew_HtmlEncode($this->post_type->CurrentValue);

			// post_class
			$this->post_class->EditCustomAttributes = "";
			$this->post_class->EditValue = ew_HtmlEncode($this->post_class->CurrentValue);

			// post_group
			$this->post_group->EditCustomAttributes = "";
			$this->post_group->EditValue = ew_HtmlEncode($this->post_group->CurrentValue);

			// Edit refer script
			// post_id

			$this->post_id->HrefValue = "";

			// post_author
			$this->post_author->HrefValue = "";

			// post_date
			$this->post_date->HrefValue = "";

			// post_content
			$this->post_content->HrefValue = "";

			// post_title
			$this->post_title->HrefValue = "";

			// post_active
			$this->post_active->HrefValue = "";

			// post_rate
			$this->post_rate->HrefValue = "";

			// post_type
			$this->post_type->HrefValue = "";

			// post_class
			$this->post_class->HrefValue = "";

			// post_group
			$this->post_group->HrefValue = "";
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
		if (!ew_CheckInteger($this->post_author->FormValue)) {
			ew_AddMessage($gsFormError, $this->post_author->FldErrMsg());
		}
		if (!ew_CheckDate($this->post_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->post_date->FldErrMsg());
		}
		if (!ew_CheckInteger($this->post_active->FormValue)) {
			ew_AddMessage($gsFormError, $this->post_active->FldErrMsg());
		}
		if (!ew_CheckInteger($this->post_rate->FormValue)) {
			ew_AddMessage($gsFormError, $this->post_rate->FldErrMsg());
		}
		if (!ew_CheckInteger($this->post_class->FormValue)) {
			ew_AddMessage($gsFormError, $this->post_class->FldErrMsg());
		}
		if (!ew_CheckInteger($this->post_group->FormValue)) {
			ew_AddMessage($gsFormError, $this->post_group->FldErrMsg());
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

			// post_author
			$this->post_author->SetDbValueDef($rsnew, $this->post_author->CurrentValue, NULL, $this->post_author->ReadOnly);

			// post_date
			$this->post_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->post_date->CurrentValue, 5), NULL, $this->post_date->ReadOnly);

			// post_content
			$this->post_content->SetDbValueDef($rsnew, $this->post_content->CurrentValue, NULL, $this->post_content->ReadOnly);

			// post_title
			$this->post_title->SetDbValueDef($rsnew, $this->post_title->CurrentValue, NULL, $this->post_title->ReadOnly);

			// post_active
			$this->post_active->SetDbValueDef($rsnew, $this->post_active->CurrentValue, NULL, $this->post_active->ReadOnly);

			// post_rate
			$this->post_rate->SetDbValueDef($rsnew, $this->post_rate->CurrentValue, NULL, $this->post_rate->ReadOnly);

			// post_type
			$this->post_type->SetDbValueDef($rsnew, $this->post_type->CurrentValue, NULL, $this->post_type->ReadOnly);

			// post_class
			$this->post_class->SetDbValueDef($rsnew, $this->post_class->CurrentValue, NULL, $this->post_class->ReadOnly);

			// post_group
			$this->post_group->SetDbValueDef($rsnew, $this->post_group->CurrentValue, NULL, $this->post_group->ReadOnly);

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
if (!isset($tbl_post_edit)) $tbl_post_edit = new ctbl_post_edit();

// Page init
$tbl_post_edit->Page_Init();

// Page main
$tbl_post_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tbl_post_edit = new ew_Page("tbl_post_edit");
tbl_post_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = tbl_post_edit.PageID; // For backward compatibility

// Form object
var ftbl_postedit = new ew_Form("ftbl_postedit");

// Validate form
ftbl_postedit.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_post_author"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($tbl_post->post_author->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_post_date"];
		if (elm && !ew_CheckDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($tbl_post->post_date->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_post_active"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($tbl_post->post_active->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_post_rate"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($tbl_post->post_rate->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_post_class"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($tbl_post->post_class->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_post_group"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($tbl_post->post_group->FldErrMsg()) ?>");

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
ftbl_postedit.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbl_postedit.ValidateRequired = true;
<?php } else { ?>
ftbl_postedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $tbl_post->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $tbl_post->getReturnUrl() ?>" id="a_GoBack" class="ewLink"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $tbl_post_edit->ShowPageHeader(); ?>
<?php
$tbl_post_edit->ShowMessage();
?>
<form name="ftbl_postedit" id="ftbl_postedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<br>
<input type="hidden" name="t" value="tbl_post">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_tbl_postedit" class="ewTable">
<?php if ($tbl_post->post_id->Visible) { // post_id ?>
	<tr id="r_post_id"<?php echo $tbl_post->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_post_post_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_post->post_id->CellAttributes() ?>><span id="el_tbl_post_post_id">
<span<?php echo $tbl_post->post_id->ViewAttributes() ?>>
<?php echo $tbl_post->post_id->EditValue ?></span>
<input type="hidden" name="x_post_id" id="x_post_id" value="<?php echo ew_HtmlEncode($tbl_post->post_id->CurrentValue) ?>">
</span><?php echo $tbl_post->post_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbl_post->post_author->Visible) { // post_author ?>
	<tr id="r_post_author"<?php echo $tbl_post->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_post_post_author"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_author->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_post->post_author->CellAttributes() ?>><span id="el_tbl_post_post_author">
<input type="text" name="x_post_author" id="x_post_author" size="30" value="<?php echo $tbl_post->post_author->EditValue ?>"<?php echo $tbl_post->post_author->EditAttributes() ?>>
</span><?php echo $tbl_post->post_author->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbl_post->post_date->Visible) { // post_date ?>
	<tr id="r_post_date"<?php echo $tbl_post->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_post_post_date"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_date->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_post->post_date->CellAttributes() ?>><span id="el_tbl_post_post_date">
<input type="text" name="x_post_date" id="x_post_date" value="<?php echo $tbl_post->post_date->EditValue ?>"<?php echo $tbl_post->post_date->EditAttributes() ?>>
</span><?php echo $tbl_post->post_date->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbl_post->post_content->Visible) { // post_content ?>
	<tr id="r_post_content"<?php echo $tbl_post->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_post_post_content"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_content->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_post->post_content->CellAttributes() ?>><span id="el_tbl_post_post_content">
<textarea name="x_post_content" id="x_post_content" cols="35" rows="4"<?php echo $tbl_post->post_content->EditAttributes() ?>><?php echo $tbl_post->post_content->EditValue ?></textarea>
</span><?php echo $tbl_post->post_content->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbl_post->post_title->Visible) { // post_title ?>
	<tr id="r_post_title"<?php echo $tbl_post->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_post_post_title"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_title->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_post->post_title->CellAttributes() ?>><span id="el_tbl_post_post_title">
<input type="text" name="x_post_title" id="x_post_title" size="30" maxlength="200" value="<?php echo $tbl_post->post_title->EditValue ?>"<?php echo $tbl_post->post_title->EditAttributes() ?>>
</span><?php echo $tbl_post->post_title->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbl_post->post_active->Visible) { // post_active ?>
	<tr id="r_post_active"<?php echo $tbl_post->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_post_post_active"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_active->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_post->post_active->CellAttributes() ?>><span id="el_tbl_post_post_active">
<input type="text" name="x_post_active" id="x_post_active" size="30" value="<?php echo $tbl_post->post_active->EditValue ?>"<?php echo $tbl_post->post_active->EditAttributes() ?>>
</span><?php echo $tbl_post->post_active->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbl_post->post_rate->Visible) { // post_rate ?>
	<tr id="r_post_rate"<?php echo $tbl_post->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_post_post_rate"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_rate->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_post->post_rate->CellAttributes() ?>><span id="el_tbl_post_post_rate">
<input type="text" name="x_post_rate" id="x_post_rate" size="30" value="<?php echo $tbl_post->post_rate->EditValue ?>"<?php echo $tbl_post->post_rate->EditAttributes() ?>>
</span><?php echo $tbl_post->post_rate->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbl_post->post_type->Visible) { // post_type ?>
	<tr id="r_post_type"<?php echo $tbl_post->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_post_post_type"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_type->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_post->post_type->CellAttributes() ?>><span id="el_tbl_post_post_type">
<input type="text" name="x_post_type" id="x_post_type" size="30" maxlength="45" value="<?php echo $tbl_post->post_type->EditValue ?>"<?php echo $tbl_post->post_type->EditAttributes() ?>>
</span><?php echo $tbl_post->post_type->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbl_post->post_class->Visible) { // post_class ?>
	<tr id="r_post_class"<?php echo $tbl_post->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_post_post_class"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_class->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_post->post_class->CellAttributes() ?>><span id="el_tbl_post_post_class">
<input type="text" name="x_post_class" id="x_post_class" size="30" value="<?php echo $tbl_post->post_class->EditValue ?>"<?php echo $tbl_post->post_class->EditAttributes() ?>>
</span><?php echo $tbl_post->post_class->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbl_post->post_group->Visible) { // post_group ?>
	<tr id="r_post_group"<?php echo $tbl_post->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_post_post_group"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_group->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_post->post_group->CellAttributes() ?>><span id="el_tbl_post_post_group">
<input type="text" name="x_post_group" id="x_post_group" size="30" value="<?php echo $tbl_post->post_group->EditValue ?>"<?php echo $tbl_post->post_group->EditAttributes() ?>>
</span><?php echo $tbl_post->post_group->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<br>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>">
</form>
<script type="text/javascript">
ftbl_postedit.Init();
</script>
<?php
$tbl_post_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbl_post_edit->Page_Terminate();
?>
