<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "tbl_lessoninfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$tbl_lesson_add = NULL; // Initialize page object first

class ctbl_lesson_add extends ctbl_lesson {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{9095E487-4467-4C46-97C7-01D1A378652D}";

	// Table name
	var $TableName = 'tbl_lesson';

	// Page object name
	var $PageObjName = 'tbl_lesson_add';

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

		// Table object (tbl_lesson)
		if (!isset($GLOBALS["tbl_lesson"])) {
			$GLOBALS["tbl_lesson"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tbl_lesson"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tbl_lesson', TRUE);

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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["lesson_id"] != "") {
				$this->lesson_id->setQueryStringValue($_GET["lesson_id"]);
				$this->setKey("lesson_id", $this->lesson_id->CurrentValue); // Set up key
			} else {
				$this->setKey("lesson_id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("tbl_lessonlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "tbl_lessonview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
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

	// Load default values
	function LoadDefaultValues() {
		$this->lesson_active->CurrentValue = NULL;
		$this->lesson_active->OldValue = $this->lesson_active->CurrentValue;
		$this->lesson_weeks->CurrentValue = NULL;
		$this->lesson_weeks->OldValue = $this->lesson_weeks->CurrentValue;
		$this->lesson_subject->CurrentValue = NULL;
		$this->lesson_subject->OldValue = $this->lesson_subject->CurrentValue;
		$this->lesson_name->CurrentValue = NULL;
		$this->lesson_name->OldValue = $this->lesson_name->CurrentValue;
		$this->lesson_info->CurrentValue = NULL;
		$this->lesson_info->OldValue = $this->lesson_info->CurrentValue;
		$this->lesson_doc->CurrentValue = NULL;
		$this->lesson_doc->OldValue = $this->lesson_doc->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->lesson_active->FldIsDetailKey) {
			$this->lesson_active->setFormValue($objForm->GetValue("x_lesson_active"));
		}
		if (!$this->lesson_weeks->FldIsDetailKey) {
			$this->lesson_weeks->setFormValue($objForm->GetValue("x_lesson_weeks"));
		}
		if (!$this->lesson_subject->FldIsDetailKey) {
			$this->lesson_subject->setFormValue($objForm->GetValue("x_lesson_subject"));
		}
		if (!$this->lesson_name->FldIsDetailKey) {
			$this->lesson_name->setFormValue($objForm->GetValue("x_lesson_name"));
		}
		if (!$this->lesson_info->FldIsDetailKey) {
			$this->lesson_info->setFormValue($objForm->GetValue("x_lesson_info"));
		}
		if (!$this->lesson_doc->FldIsDetailKey) {
			$this->lesson_doc->setFormValue($objForm->GetValue("x_lesson_doc"));
		}
		if (!$this->lesson_id->FldIsDetailKey)
			$this->lesson_id->setFormValue($objForm->GetValue("x_lesson_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->lesson_id->CurrentValue = $this->lesson_id->FormValue;
		$this->lesson_active->CurrentValue = $this->lesson_active->FormValue;
		$this->lesson_weeks->CurrentValue = $this->lesson_weeks->FormValue;
		$this->lesson_subject->CurrentValue = $this->lesson_subject->FormValue;
		$this->lesson_name->CurrentValue = $this->lesson_name->FormValue;
		$this->lesson_info->CurrentValue = $this->lesson_info->FormValue;
		$this->lesson_doc->CurrentValue = $this->lesson_doc->FormValue;
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
		$this->lesson_id->setDbValue($rs->fields('lesson_id'));
		$this->lesson_active->setDbValue($rs->fields('lesson_active'));
		$this->lesson_weeks->setDbValue($rs->fields('lesson_weeks'));
		$this->lesson_subject->setDbValue($rs->fields('lesson_subject'));
		$this->lesson_name->setDbValue($rs->fields('lesson_name'));
		$this->lesson_info->setDbValue($rs->fields('lesson_info'));
		$this->lesson_doc->setDbValue($rs->fields('lesson_doc'));
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("lesson_id")) <> "")
			$this->lesson_id->CurrentValue = $this->getKey("lesson_id"); // lesson_id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// lesson_id
		// lesson_active
		// lesson_weeks
		// lesson_subject
		// lesson_name
		// lesson_info
		// lesson_doc

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// lesson_id
			$this->lesson_id->ViewValue = $this->lesson_id->CurrentValue;
			$this->lesson_id->ViewCustomAttributes = "";

			// lesson_active
			$this->lesson_active->ViewValue = $this->lesson_active->CurrentValue;
			$this->lesson_active->ViewCustomAttributes = "";

			// lesson_weeks
			$this->lesson_weeks->ViewValue = $this->lesson_weeks->CurrentValue;
			$this->lesson_weeks->ViewCustomAttributes = "";

			// lesson_subject
			if (strval($this->lesson_subject->CurrentValue) <> "") {
				$sFilterWrk = "`subject_id`" . ew_SearchString("=", $this->lesson_subject->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `subject_id`, `subject_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tbl_subject`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->lesson_subject->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->lesson_subject->ViewValue = $this->lesson_subject->CurrentValue;
				}
			} else {
				$this->lesson_subject->ViewValue = NULL;
			}
			$this->lesson_subject->ViewCustomAttributes = "";

			// lesson_name
			$this->lesson_name->ViewValue = $this->lesson_name->CurrentValue;
			$this->lesson_name->ViewCustomAttributes = "";

			// lesson_info
			$this->lesson_info->ViewValue = $this->lesson_info->CurrentValue;
			$this->lesson_info->ViewCustomAttributes = "";

			// lesson_doc
			$this->lesson_doc->ViewValue = $this->lesson_doc->CurrentValue;
			$this->lesson_doc->ViewCustomAttributes = "";

			// lesson_active
			$this->lesson_active->LinkCustomAttributes = "";
			$this->lesson_active->HrefValue = "";
			$this->lesson_active->TooltipValue = "";

			// lesson_weeks
			$this->lesson_weeks->LinkCustomAttributes = "";
			$this->lesson_weeks->HrefValue = "";
			$this->lesson_weeks->TooltipValue = "";

			// lesson_subject
			$this->lesson_subject->LinkCustomAttributes = "";
			$this->lesson_subject->HrefValue = "";
			$this->lesson_subject->TooltipValue = "";

			// lesson_name
			$this->lesson_name->LinkCustomAttributes = "";
			$this->lesson_name->HrefValue = "";
			$this->lesson_name->TooltipValue = "";

			// lesson_info
			$this->lesson_info->LinkCustomAttributes = "";
			$this->lesson_info->HrefValue = "";
			$this->lesson_info->TooltipValue = "";

			// lesson_doc
			$this->lesson_doc->LinkCustomAttributes = "";
			$this->lesson_doc->HrefValue = "";
			$this->lesson_doc->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// lesson_active
			$this->lesson_active->EditCustomAttributes = "";
			$this->lesson_active->EditValue = ew_HtmlEncode($this->lesson_active->CurrentValue);

			// lesson_weeks
			$this->lesson_weeks->EditCustomAttributes = "";
			$this->lesson_weeks->EditValue = ew_HtmlEncode($this->lesson_weeks->CurrentValue);

			// lesson_subject
			$this->lesson_subject->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `subject_id`, `subject_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `tbl_subject`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->lesson_subject->EditValue = $arwrk;

			// lesson_name
			$this->lesson_name->EditCustomAttributes = "";
			$this->lesson_name->EditValue = ew_HtmlEncode($this->lesson_name->CurrentValue);

			// lesson_info
			$this->lesson_info->EditCustomAttributes = "";
			$this->lesson_info->EditValue = ew_HtmlEncode($this->lesson_info->CurrentValue);

			// lesson_doc
			$this->lesson_doc->EditCustomAttributes = "";
			$this->lesson_doc->EditValue = ew_HtmlEncode($this->lesson_doc->CurrentValue);

			// Edit refer script
			// lesson_active

			$this->lesson_active->HrefValue = "";

			// lesson_weeks
			$this->lesson_weeks->HrefValue = "";

			// lesson_subject
			$this->lesson_subject->HrefValue = "";

			// lesson_name
			$this->lesson_name->HrefValue = "";

			// lesson_info
			$this->lesson_info->HrefValue = "";

			// lesson_doc
			$this->lesson_doc->HrefValue = "";
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
		if (!ew_CheckInteger($this->lesson_active->FormValue)) {
			ew_AddMessage($gsFormError, $this->lesson_active->FldErrMsg());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;
		$rsnew = array();

		// lesson_active
		$this->lesson_active->SetDbValueDef($rsnew, $this->lesson_active->CurrentValue, NULL, FALSE);

		// lesson_weeks
		$this->lesson_weeks->SetDbValueDef($rsnew, $this->lesson_weeks->CurrentValue, NULL, FALSE);

		// lesson_subject
		$this->lesson_subject->SetDbValueDef($rsnew, $this->lesson_subject->CurrentValue, NULL, FALSE);

		// lesson_name
		$this->lesson_name->SetDbValueDef($rsnew, $this->lesson_name->CurrentValue, NULL, FALSE);

		// lesson_info
		$this->lesson_info->SetDbValueDef($rsnew, $this->lesson_info->CurrentValue, NULL, FALSE);

		// lesson_doc
		$this->lesson_doc->SetDbValueDef($rsnew, $this->lesson_doc->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->lesson_id->CurrentValue == "" && $this->lesson_id->getSessionValue() == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
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
if (!isset($tbl_lesson_add)) $tbl_lesson_add = new ctbl_lesson_add();

// Page init
$tbl_lesson_add->Page_Init();

// Page main
$tbl_lesson_add->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tbl_lesson_add = new ew_Page("tbl_lesson_add");
tbl_lesson_add.PageID = "add"; // Page ID
var EW_PAGE_ID = tbl_lesson_add.PageID; // For backward compatibility

// Form object
var ftbl_lessonadd = new ew_Form("ftbl_lessonadd");

// Validate form
ftbl_lessonadd.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_lesson_active"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($tbl_lesson->lesson_active->FldErrMsg()) ?>");

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
ftbl_lessonadd.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbl_lessonadd.ValidateRequired = true;
<?php } else { ?>
ftbl_lessonadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftbl_lessonadd.Lists["x_lesson_subject"] = {"LinkField":"x_subject_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_subject_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Add") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $tbl_lesson->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $tbl_lesson->getReturnUrl() ?>" id="a_GoBack" class="ewLink"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $tbl_lesson_add->ShowPageHeader(); ?>
<?php
$tbl_lesson_add->ShowMessage();
?>
<form name="ftbl_lessonadd" id="ftbl_lessonadd" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<br>
<input type="hidden" name="t" value="tbl_lesson">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_tbl_lessonadd" class="ewTable">
<?php if ($tbl_lesson->lesson_active->Visible) { // lesson_active ?>
	<tr id="r_lesson_active"<?php echo $tbl_lesson->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_lesson_lesson_active"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_lesson->lesson_active->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_lesson->lesson_active->CellAttributes() ?>><span id="el_tbl_lesson_lesson_active">
<input type="text" name="x_lesson_active" id="x_lesson_active" size="30" value="<?php echo $tbl_lesson->lesson_active->EditValue ?>"<?php echo $tbl_lesson->lesson_active->EditAttributes() ?>>
</span><?php echo $tbl_lesson->lesson_active->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbl_lesson->lesson_weeks->Visible) { // lesson_weeks ?>
	<tr id="r_lesson_weeks"<?php echo $tbl_lesson->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_lesson_lesson_weeks"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_lesson->lesson_weeks->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_lesson->lesson_weeks->CellAttributes() ?>><span id="el_tbl_lesson_lesson_weeks">
<input type="text" name="x_lesson_weeks" id="x_lesson_weeks" size="30" maxlength="100" value="<?php echo $tbl_lesson->lesson_weeks->EditValue ?>"<?php echo $tbl_lesson->lesson_weeks->EditAttributes() ?>>
</span><?php echo $tbl_lesson->lesson_weeks->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbl_lesson->lesson_subject->Visible) { // lesson_subject ?>
	<tr id="r_lesson_subject"<?php echo $tbl_lesson->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_lesson_lesson_subject"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_lesson->lesson_subject->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_lesson->lesson_subject->CellAttributes() ?>><span id="el_tbl_lesson_lesson_subject">
<select id="x_lesson_subject" name="x_lesson_subject"<?php echo $tbl_lesson->lesson_subject->EditAttributes() ?>>
<?php
if (is_array($tbl_lesson->lesson_subject->EditValue)) {
	$arwrk = $tbl_lesson->lesson_subject->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tbl_lesson->lesson_subject->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
ftbl_lessonadd.Lists["x_lesson_subject"].Options = <?php echo (is_array($tbl_lesson->lesson_subject->EditValue)) ? ew_ArrayToJson($tbl_lesson->lesson_subject->EditValue, 1) : "[]" ?>;
</script>
</span><?php echo $tbl_lesson->lesson_subject->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbl_lesson->lesson_name->Visible) { // lesson_name ?>
	<tr id="r_lesson_name"<?php echo $tbl_lesson->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_lesson_lesson_name"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_lesson->lesson_name->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_lesson->lesson_name->CellAttributes() ?>><span id="el_tbl_lesson_lesson_name">
<textarea name="x_lesson_name" id="x_lesson_name" cols="35" rows="4"<?php echo $tbl_lesson->lesson_name->EditAttributes() ?>><?php echo $tbl_lesson->lesson_name->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("ftbl_lessonadd", "x_lesson_name", 35, 4, <?php echo ($tbl_lesson->lesson_name->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span><?php echo $tbl_lesson->lesson_name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbl_lesson->lesson_info->Visible) { // lesson_info ?>
	<tr id="r_lesson_info"<?php echo $tbl_lesson->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_lesson_lesson_info"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_lesson->lesson_info->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_lesson->lesson_info->CellAttributes() ?>><span id="el_tbl_lesson_lesson_info">
<textarea name="x_lesson_info" id="x_lesson_info" cols="35" rows="4"<?php echo $tbl_lesson->lesson_info->EditAttributes() ?>><?php echo $tbl_lesson->lesson_info->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("ftbl_lessonadd", "x_lesson_info", 35, 4, <?php echo ($tbl_lesson->lesson_info->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span><?php echo $tbl_lesson->lesson_info->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbl_lesson->lesson_doc->Visible) { // lesson_doc ?>
	<tr id="r_lesson_doc"<?php echo $tbl_lesson->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_lesson_lesson_doc"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_lesson->lesson_doc->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_lesson->lesson_doc->CellAttributes() ?>><span id="el_tbl_lesson_lesson_doc">
<textarea name="x_lesson_doc" id="x_lesson_doc" cols="undefined" rows="undefined"<?php echo $tbl_lesson->lesson_doc->EditAttributes() ?>><?php echo $tbl_lesson->lesson_doc->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("ftbl_lessonadd", "x_lesson_doc", 0, 0, <?php echo ($tbl_lesson->lesson_doc->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span><?php echo $tbl_lesson->lesson_doc->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<br>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("AddBtn")) ?>">
</form>
<script type="text/javascript">
ftbl_lessonadd.Init();
</script>
<?php
$tbl_lesson_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbl_lesson_add->Page_Terminate();
?>
