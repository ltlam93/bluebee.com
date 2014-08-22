<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "tbl_subjectinfo.php" ?>
<?php include_once "tbl_subject_typegridcls.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$tbl_subject_view = NULL; // Initialize page object first

class ctbl_subject_view extends ctbl_subject {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{9095E487-4467-4C46-97C7-01D1A378652D}";

	// Table name
	var $TableName = 'tbl_subject';

	// Page object name
	var $PageObjName = 'tbl_subject_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// Table object (tbl_subject)
		if (!isset($GLOBALS["tbl_subject"])) {
			$GLOBALS["tbl_subject"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tbl_subject"];
		}
		$KeyUrl = "";
		if (@$_GET["subject_id"] <> "") {
			$this->RecKey["subject_id"] = $_GET["subject_id"];
			$KeyUrl .= "&subject_id=" . urlencode($this->RecKey["subject_id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tbl_subject', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";
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
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"];
		$this->subject_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
	var $ExportOptions; // Export options
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["subject_id"] <> "") {
				$this->subject_id->setQueryStringValue($_GET["subject_id"]);
				$this->RecKey["subject_id"] = $this->subject_id->QueryStringValue;
			} else {
				$sReturnUrl = "tbl_subjectlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "tbl_subjectlist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "tbl_subjectlist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
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
		$this->subject_id->setDbValue($rs->fields('subject_id'));
		$this->subject_name->setDbValue($rs->fields('subject_name'));
		$this->subject_code->setDbValue($rs->fields('subject_code'));
		$this->subject_active->setDbValue($rs->fields('subject_active'));
		$this->subject_university->setDbValue($rs->fields('subject_university'));
		$this->subject_type->setDbValue($rs->fields('subject_type'));
		$this->subject_year->setDbValue($rs->fields('subject_year'));
		$this->subject_credits->setDbValue($rs->fields('subject_credits'));
		$this->subject_credit_hour->setDbValue($rs->fields('subject_credit_hour'));
		$this->subject_requirement->setDbValue($rs->fields('subject_requirement'));
		$this->subject_target->setDbValue($rs->fields('subject_target'));
		$this->subject_info->setDbValue($rs->fields('subject_info'));
		$this->subject_test->setDbValue($rs->fields('subject_test'));
		$this->subject_faculty->setDbValue($rs->fields('subject_faculty'));
		$this->subject_dept->setDbValue($rs->fields('subject_dept'));
		$this->subject_content->setDbValue($rs->fields('subject_content'));
		$this->subject_general_faculty_id->setDbValue($rs->fields('subject_general_faculty_id'));
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// subject_id
		// subject_name
		// subject_code
		// subject_active
		// subject_university
		// subject_type
		// subject_year
		// subject_credits
		// subject_credit_hour
		// subject_requirement
		// subject_target
		// subject_info
		// subject_test
		// subject_faculty
		// subject_dept
		// subject_content
		// subject_general_faculty_id

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// subject_id
			$this->subject_id->ViewValue = $this->subject_id->CurrentValue;
			$this->subject_id->ViewCustomAttributes = "";

			// subject_name
			$this->subject_name->ViewValue = $this->subject_name->CurrentValue;
			$this->subject_name->ViewCustomAttributes = "";

			// subject_code
			$this->subject_code->ViewValue = $this->subject_code->CurrentValue;
			$this->subject_code->ViewCustomAttributes = "";

			// subject_active
			$this->subject_active->ViewValue = $this->subject_active->CurrentValue;
			$this->subject_active->ViewCustomAttributes = "";

			// subject_university
			$this->subject_university->ViewValue = $this->subject_university->CurrentValue;
			$this->subject_university->ViewCustomAttributes = "";

			// subject_type
			if (strval($this->subject_type->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->subject_type->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id`, `subject_type_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tbl_subject_type`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->subject_type->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->subject_type->ViewValue = $this->subject_type->CurrentValue;
				}
			} else {
				$this->subject_type->ViewValue = NULL;
			}
			$this->subject_type->ViewCustomAttributes = "";

			// subject_year
			$this->subject_year->ViewValue = $this->subject_year->CurrentValue;
			$this->subject_year->ViewCustomAttributes = "";

			// subject_credits
			$this->subject_credits->ViewValue = $this->subject_credits->CurrentValue;
			$this->subject_credits->ViewCustomAttributes = "";

			// subject_credit_hour
			$this->subject_credit_hour->ViewValue = $this->subject_credit_hour->CurrentValue;
			$this->subject_credit_hour->ViewCustomAttributes = "";

			// subject_requirement
			$this->subject_requirement->ViewValue = $this->subject_requirement->CurrentValue;
			$this->subject_requirement->ViewCustomAttributes = "";

			// subject_target
			$this->subject_target->ViewValue = $this->subject_target->CurrentValue;
			$this->subject_target->ViewCustomAttributes = "";

			// subject_info
			$this->subject_info->ViewValue = $this->subject_info->CurrentValue;
			$this->subject_info->ViewCustomAttributes = "";

			// subject_test
			$this->subject_test->ViewValue = $this->subject_test->CurrentValue;
			$this->subject_test->ViewCustomAttributes = "";

			// subject_faculty
			if (strval($this->subject_faculty->CurrentValue) <> "") {
				$sFilterWrk = "`faculty_id`" . ew_SearchString("=", $this->subject_faculty->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `faculty_id`, `faculty_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tbl_faculty`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->subject_faculty->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->subject_faculty->ViewValue = $this->subject_faculty->CurrentValue;
				}
			} else {
				$this->subject_faculty->ViewValue = NULL;
			}
			$this->subject_faculty->ViewCustomAttributes = "";

			// subject_dept
			if (strval($this->subject_dept->CurrentValue) <> "") {
				$sFilterWrk = "`dept_id`" . ew_SearchString("=", $this->subject_dept->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `dept_id`, `dept_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tbl_dept`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->subject_dept->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->subject_dept->ViewValue = $this->subject_dept->CurrentValue;
				}
			} else {
				$this->subject_dept->ViewValue = NULL;
			}
			$this->subject_dept->ViewCustomAttributes = "";

			// subject_content
			$this->subject_content->ViewValue = $this->subject_content->CurrentValue;
			$this->subject_content->ViewCustomAttributes = "";

			// subject_general_faculty_id
			if (strval($this->subject_general_faculty_id->CurrentValue) <> "") {
				$sFilterWrk = "`faculty_id`" . ew_SearchString("=", $this->subject_general_faculty_id->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `faculty_id`, `faculty_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tbl_faculty`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->subject_general_faculty_id->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->subject_general_faculty_id->ViewValue = $this->subject_general_faculty_id->CurrentValue;
				}
			} else {
				$this->subject_general_faculty_id->ViewValue = NULL;
			}
			$this->subject_general_faculty_id->ViewCustomAttributes = "";

			// subject_id
			$this->subject_id->LinkCustomAttributes = "";
			$this->subject_id->HrefValue = "";
			$this->subject_id->TooltipValue = "";

			// subject_name
			$this->subject_name->LinkCustomAttributes = "";
			$this->subject_name->HrefValue = "";
			$this->subject_name->TooltipValue = "";

			// subject_code
			$this->subject_code->LinkCustomAttributes = "";
			$this->subject_code->HrefValue = "";
			$this->subject_code->TooltipValue = "";

			// subject_active
			$this->subject_active->LinkCustomAttributes = "";
			$this->subject_active->HrefValue = "";
			$this->subject_active->TooltipValue = "";

			// subject_university
			$this->subject_university->LinkCustomAttributes = "";
			$this->subject_university->HrefValue = "";
			$this->subject_university->TooltipValue = "";

			// subject_type
			$this->subject_type->LinkCustomAttributes = "";
			$this->subject_type->HrefValue = "";
			$this->subject_type->TooltipValue = "";

			// subject_year
			$this->subject_year->LinkCustomAttributes = "";
			$this->subject_year->HrefValue = "";
			$this->subject_year->TooltipValue = "";

			// subject_credits
			$this->subject_credits->LinkCustomAttributes = "";
			$this->subject_credits->HrefValue = "";
			$this->subject_credits->TooltipValue = "";

			// subject_credit_hour
			$this->subject_credit_hour->LinkCustomAttributes = "";
			$this->subject_credit_hour->HrefValue = "";
			$this->subject_credit_hour->TooltipValue = "";

			// subject_requirement
			$this->subject_requirement->LinkCustomAttributes = "";
			$this->subject_requirement->HrefValue = "";
			$this->subject_requirement->TooltipValue = "";

			// subject_target
			$this->subject_target->LinkCustomAttributes = "";
			$this->subject_target->HrefValue = "";
			$this->subject_target->TooltipValue = "";

			// subject_info
			$this->subject_info->LinkCustomAttributes = "";
			$this->subject_info->HrefValue = "";
			$this->subject_info->TooltipValue = "";

			// subject_test
			$this->subject_test->LinkCustomAttributes = "";
			$this->subject_test->HrefValue = "";
			$this->subject_test->TooltipValue = "";

			// subject_faculty
			$this->subject_faculty->LinkCustomAttributes = "";
			$this->subject_faculty->HrefValue = "";
			$this->subject_faculty->TooltipValue = "";

			// subject_dept
			$this->subject_dept->LinkCustomAttributes = "";
			$this->subject_dept->HrefValue = "";
			$this->subject_dept->TooltipValue = "";

			// subject_content
			$this->subject_content->LinkCustomAttributes = "";
			$this->subject_content->HrefValue = "";
			$this->subject_content->TooltipValue = "";

			// subject_general_faculty_id
			$this->subject_general_faculty_id->LinkCustomAttributes = "";
			$this->subject_general_faculty_id->HrefValue = "";
			$this->subject_general_faculty_id->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($tbl_subject_view)) $tbl_subject_view = new ctbl_subject_view();

// Page init
$tbl_subject_view->Page_Init();

// Page main
$tbl_subject_view->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tbl_subject_view = new ew_Page("tbl_subject_view");
tbl_subject_view.PageID = "view"; // Page ID
var EW_PAGE_ID = tbl_subject_view.PageID; // For backward compatibility

// Form object
var ftbl_subjectview = new ew_Form("ftbl_subjectview");

// Form_CustomValidate event
ftbl_subjectview.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbl_subjectview.ValidateRequired = true;
<?php } else { ?>
ftbl_subjectview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftbl_subjectview.Lists["x_subject_type"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_subject_type_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ftbl_subjectview.Lists["x_subject_faculty"] = {"LinkField":"x_faculty_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_faculty_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ftbl_subjectview.Lists["x_subject_dept"] = {"LinkField":"x_dept_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_dept_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ftbl_subjectview.Lists["x_subject_general_faculty_id"] = {"LinkField":"x_faculty_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_faculty_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("View") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $tbl_subject->TableCaption() ?>&nbsp;&nbsp;</span><?php $tbl_subject_view->ExportOptions->Render("body"); ?>
</p>
<p class="phpmaker">
<a href="<?php echo $tbl_subject_view->ListUrl ?>" id="a_BackToList" class="ewLink"><?php echo $Language->Phrase("BackToList") ?></a>&nbsp;
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($tbl_subject_view->AddUrl <> "") { ?>
<a href="<?php echo $tbl_subject_view->AddUrl ?>" id="a_AddLink" class="ewLink"><?php echo $Language->Phrase("ViewPageAddLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($tbl_subject_view->EditUrl <> "") { ?>
<a href="<?php echo $tbl_subject_view->EditUrl ?>" id="a_EditLink" class="ewLink"><?php echo $Language->Phrase("ViewPageEditLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($tbl_subject_view->CopyUrl <> "") { ?>
<a href="<?php echo $tbl_subject_view->CopyUrl ?>" id="a_CopyLink" class="ewLink"><?php echo $Language->Phrase("ViewPageCopyLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($tbl_subject_view->DeleteUrl <> "") { ?>
<a href="<?php echo $tbl_subject_view->DeleteUrl ?>" id="a_DeleteLink" class="ewLink"><?php echo $Language->Phrase("ViewPageDeleteLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<a href="tbl_subject_typelist.php?<?php echo EW_TABLE_SHOW_MASTER ?>=tbl_subject&subject_type=<?php echo urlencode(strval($tbl_subject->subject_type->CurrentValue)) ?>"><?php echo $Language->Phrase("ViewPageDetailLink") ?><?php echo $Language->TablePhrase("tbl_subject_type", "TblCaption") ?>
</a>
&nbsp;
<?php } ?>
</p>
<?php $tbl_subject_view->ShowPageHeader(); ?>
<?php
$tbl_subject_view->ShowMessage();
?>
<form name="ftbl_subjectview" id="ftbl_subjectview" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="tbl_subject">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_tbl_subjectview" class="ewTable">
<?php if ($tbl_subject->subject_id->Visible) { // subject_id ?>
	<tr id="r_subject_id"<?php echo $tbl_subject->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_subject_subject_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_subject->subject_id->CellAttributes() ?>><span id="el_tbl_subject_subject_id">
<span<?php echo $tbl_subject->subject_id->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_subject->subject_name->Visible) { // subject_name ?>
	<tr id="r_subject_name"<?php echo $tbl_subject->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_subject_subject_name"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_name->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_subject->subject_name->CellAttributes() ?>><span id="el_tbl_subject_subject_name">
<span<?php echo $tbl_subject->subject_name->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_name->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_subject->subject_code->Visible) { // subject_code ?>
	<tr id="r_subject_code"<?php echo $tbl_subject->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_subject_subject_code"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_code->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_subject->subject_code->CellAttributes() ?>><span id="el_tbl_subject_subject_code">
<span<?php echo $tbl_subject->subject_code->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_code->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_subject->subject_active->Visible) { // subject_active ?>
	<tr id="r_subject_active"<?php echo $tbl_subject->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_subject_subject_active"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_active->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_subject->subject_active->CellAttributes() ?>><span id="el_tbl_subject_subject_active">
<span<?php echo $tbl_subject->subject_active->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_active->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_subject->subject_university->Visible) { // subject_university ?>
	<tr id="r_subject_university"<?php echo $tbl_subject->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_subject_subject_university"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_university->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_subject->subject_university->CellAttributes() ?>><span id="el_tbl_subject_subject_university">
<span<?php echo $tbl_subject->subject_university->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_university->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_subject->subject_type->Visible) { // subject_type ?>
	<tr id="r_subject_type"<?php echo $tbl_subject->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_subject_subject_type"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_type->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_subject->subject_type->CellAttributes() ?>><span id="el_tbl_subject_subject_type">
<span<?php echo $tbl_subject->subject_type->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_type->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_subject->subject_year->Visible) { // subject_year ?>
	<tr id="r_subject_year"<?php echo $tbl_subject->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_subject_subject_year"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_year->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_subject->subject_year->CellAttributes() ?>><span id="el_tbl_subject_subject_year">
<span<?php echo $tbl_subject->subject_year->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_year->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_subject->subject_credits->Visible) { // subject_credits ?>
	<tr id="r_subject_credits"<?php echo $tbl_subject->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_subject_subject_credits"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_credits->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_subject->subject_credits->CellAttributes() ?>><span id="el_tbl_subject_subject_credits">
<span<?php echo $tbl_subject->subject_credits->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_credits->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_subject->subject_credit_hour->Visible) { // subject_credit_hour ?>
	<tr id="r_subject_credit_hour"<?php echo $tbl_subject->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_subject_subject_credit_hour"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_credit_hour->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_subject->subject_credit_hour->CellAttributes() ?>><span id="el_tbl_subject_subject_credit_hour">
<span<?php echo $tbl_subject->subject_credit_hour->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_credit_hour->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_subject->subject_requirement->Visible) { // subject_requirement ?>
	<tr id="r_subject_requirement"<?php echo $tbl_subject->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_subject_subject_requirement"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_requirement->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_subject->subject_requirement->CellAttributes() ?>><span id="el_tbl_subject_subject_requirement">
<span<?php echo $tbl_subject->subject_requirement->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_requirement->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_subject->subject_target->Visible) { // subject_target ?>
	<tr id="r_subject_target"<?php echo $tbl_subject->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_subject_subject_target"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_target->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_subject->subject_target->CellAttributes() ?>><span id="el_tbl_subject_subject_target">
<span<?php echo $tbl_subject->subject_target->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_target->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_subject->subject_info->Visible) { // subject_info ?>
	<tr id="r_subject_info"<?php echo $tbl_subject->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_subject_subject_info"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_info->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_subject->subject_info->CellAttributes() ?>><span id="el_tbl_subject_subject_info">
<span<?php echo $tbl_subject->subject_info->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_info->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_subject->subject_test->Visible) { // subject_test ?>
	<tr id="r_subject_test"<?php echo $tbl_subject->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_subject_subject_test"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_test->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_subject->subject_test->CellAttributes() ?>><span id="el_tbl_subject_subject_test">
<span<?php echo $tbl_subject->subject_test->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_test->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_subject->subject_faculty->Visible) { // subject_faculty ?>
	<tr id="r_subject_faculty"<?php echo $tbl_subject->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_subject_subject_faculty"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_faculty->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_subject->subject_faculty->CellAttributes() ?>><span id="el_tbl_subject_subject_faculty">
<span<?php echo $tbl_subject->subject_faculty->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_faculty->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_subject->subject_dept->Visible) { // subject_dept ?>
	<tr id="r_subject_dept"<?php echo $tbl_subject->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_subject_subject_dept"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_dept->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_subject->subject_dept->CellAttributes() ?>><span id="el_tbl_subject_subject_dept">
<span<?php echo $tbl_subject->subject_dept->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_dept->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_subject->subject_content->Visible) { // subject_content ?>
	<tr id="r_subject_content"<?php echo $tbl_subject->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_subject_subject_content"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_content->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_subject->subject_content->CellAttributes() ?>><span id="el_tbl_subject_subject_content">
<span<?php echo $tbl_subject->subject_content->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_content->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_subject->subject_general_faculty_id->Visible) { // subject_general_faculty_id ?>
	<tr id="r_subject_general_faculty_id"<?php echo $tbl_subject->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_subject_subject_general_faculty_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_subject->subject_general_faculty_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_subject->subject_general_faculty_id->CellAttributes() ?>><span id="el_tbl_subject_subject_general_faculty_id">
<span<?php echo $tbl_subject->subject_general_faculty_id->ViewAttributes() ?>>
<?php echo $tbl_subject->subject_general_faculty_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
</form>
<br>
<script type="text/javascript">
ftbl_subjectview.Init();
</script>
<?php
$tbl_subject_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbl_subject_view->Page_Terminate();
?>
