<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "tbl_teacherinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$tbl_teacher_view = NULL; // Initialize page object first

class ctbl_teacher_view extends ctbl_teacher {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{9095E487-4467-4C46-97C7-01D1A378652D}";

	// Table name
	var $TableName = 'tbl_teacher';

	// Page object name
	var $PageObjName = 'tbl_teacher_view';

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

		// Table object (tbl_teacher)
		if (!isset($GLOBALS["tbl_teacher"])) {
			$GLOBALS["tbl_teacher"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tbl_teacher"];
		}
		$KeyUrl = "";
		if (@$_GET["teacher_id"] <> "") {
			$this->RecKey["teacher_id"] = $_GET["teacher_id"];
			$KeyUrl .= "&teacher_id=" . urlencode($this->RecKey["teacher_id"]);
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
			define("EW_TABLE_NAME", 'tbl_teacher', TRUE);

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
		$this->teacher_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			if (@$_GET["teacher_id"] <> "") {
				$this->teacher_id->setQueryStringValue($_GET["teacher_id"]);
				$this->RecKey["teacher_id"] = $this->teacher_id->QueryStringValue;
			} else {
				$sReturnUrl = "tbl_teacherlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "tbl_teacherlist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "tbl_teacherlist.php"; // Not page request, return to list
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
		$this->teacher_id->setDbValue($rs->fields('teacher_id'));
		$this->teacher_name->setDbValue($rs->fields('teacher_name'));
		$this->teacher_personal_page->setDbValue($rs->fields('teacher_personal_page'));
		$this->teacher_avatar->Upload->DbValue = $rs->fields('teacher_avatar');
		$this->teacher_description->setDbValue($rs->fields('teacher_description'));
		$this->teacher_work_place->setDbValue($rs->fields('teacher_work_place'));
		$this->teacher_active->setDbValue($rs->fields('teacher_active'));
		$this->teacher_acadamic_title->setDbValue($rs->fields('teacher_acadamic_title'));
		$this->teacher_birthday->setDbValue($rs->fields('teacher_birthday'));
		$this->teacher_sex->setDbValue($rs->fields('teacher_sex'));
		$this->teacher_faculty->setDbValue($rs->fields('teacher_faculty'));
		$this->teacher_dept->setDbValue($rs->fields('teacher_dept'));
		$this->teacher_rate->setDbValue($rs->fields('teacher_rate'));
		$this->teacher_personality->setDbValue($rs->fields('teacher_personality'));
		$this->advices->setDbValue($rs->fields('advices'));
		$this->teacher_research->setDbValue($rs->fields('teacher_research'));
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

		// Convert decimal values if posted back
		if ($this->teacher_rate->FormValue == $this->teacher_rate->CurrentValue && is_numeric(ew_StrToFloat($this->teacher_rate->CurrentValue)))
			$this->teacher_rate->CurrentValue = ew_StrToFloat($this->teacher_rate->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// teacher_id
		// teacher_name
		// teacher_personal_page
		// teacher_avatar
		// teacher_description
		// teacher_work_place
		// teacher_active
		// teacher_acadamic_title
		// teacher_birthday
		// teacher_sex
		// teacher_faculty
		// teacher_dept
		// teacher_rate
		// teacher_personality
		// advices
		// teacher_research

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// teacher_id
			$this->teacher_id->ViewValue = $this->teacher_id->CurrentValue;
			$this->teacher_id->ViewCustomAttributes = "";

			// teacher_name
			$this->teacher_name->ViewValue = $this->teacher_name->CurrentValue;
			$this->teacher_name->ViewCustomAttributes = "";

			// teacher_personal_page
			$this->teacher_personal_page->ViewValue = $this->teacher_personal_page->CurrentValue;
			$this->teacher_personal_page->ViewCustomAttributes = "";

			// teacher_avatar
			$this->teacher_avatar->UploadPath = 'themes\classic\assets\img\Teacher_img';
			if (!ew_Empty($this->teacher_avatar->Upload->DbValue)) {
				$this->teacher_avatar->ImageAlt = $this->teacher_avatar->FldAlt();
				$this->teacher_avatar->ViewValue = ew_UploadPathEx(FALSE, $this->teacher_avatar->UploadPath) . $this->teacher_avatar->Upload->DbValue;
			} else {
				$this->teacher_avatar->ViewValue = "";
			}
			$this->teacher_avatar->ViewCustomAttributes = "";

			// teacher_description
			$this->teacher_description->ViewValue = $this->teacher_description->CurrentValue;
			$this->teacher_description->ViewCustomAttributes = "";

			// teacher_work_place
			$this->teacher_work_place->ViewValue = $this->teacher_work_place->CurrentValue;
			$this->teacher_work_place->ViewCustomAttributes = "";

			// teacher_active
			$this->teacher_active->ViewValue = $this->teacher_active->CurrentValue;
			$this->teacher_active->ViewCustomAttributes = "";

			// teacher_acadamic_title
			$this->teacher_acadamic_title->ViewValue = $this->teacher_acadamic_title->CurrentValue;
			$this->teacher_acadamic_title->ViewCustomAttributes = "";

			// teacher_birthday
			$this->teacher_birthday->ViewValue = $this->teacher_birthday->CurrentValue;
			$this->teacher_birthday->ViewCustomAttributes = "";

			// teacher_sex
			$this->teacher_sex->ViewValue = $this->teacher_sex->CurrentValue;
			$this->teacher_sex->ViewCustomAttributes = "";

			// teacher_faculty
			if (strval($this->teacher_faculty->CurrentValue) <> "") {
				$sFilterWrk = "`faculty_id`" . ew_SearchString("=", $this->teacher_faculty->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `faculty_id`, `faculty_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tbl_faculty`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->teacher_faculty->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->teacher_faculty->ViewValue = $this->teacher_faculty->CurrentValue;
				}
			} else {
				$this->teacher_faculty->ViewValue = NULL;
			}
			$this->teacher_faculty->ViewCustomAttributes = "";

			// teacher_dept
			if (strval($this->teacher_dept->CurrentValue) <> "") {
				$sFilterWrk = "`dept_id`" . ew_SearchString("=", $this->teacher_dept->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `dept_id`, `dept_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tbl_dept`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->teacher_dept->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->teacher_dept->ViewValue = $this->teacher_dept->CurrentValue;
				}
			} else {
				$this->teacher_dept->ViewValue = NULL;
			}
			$this->teacher_dept->ViewCustomAttributes = "";

			// teacher_rate
			$this->teacher_rate->ViewValue = $this->teacher_rate->CurrentValue;
			$this->teacher_rate->ViewCustomAttributes = "";

			// teacher_personality
			$this->teacher_personality->ViewValue = $this->teacher_personality->CurrentValue;
			$this->teacher_personality->ViewCustomAttributes = "";

			// advices
			$this->advices->ViewValue = $this->advices->CurrentValue;
			$this->advices->ViewCustomAttributes = "";

			// teacher_research
			$this->teacher_research->ViewValue = $this->teacher_research->CurrentValue;
			$this->teacher_research->ViewCustomAttributes = "";

			// teacher_id
			$this->teacher_id->LinkCustomAttributes = "";
			$this->teacher_id->HrefValue = "";
			$this->teacher_id->TooltipValue = "";

			// teacher_name
			$this->teacher_name->LinkCustomAttributes = "";
			$this->teacher_name->HrefValue = "";
			$this->teacher_name->TooltipValue = "";

			// teacher_personal_page
			$this->teacher_personal_page->LinkCustomAttributes = "";
			$this->teacher_personal_page->HrefValue = "";
			$this->teacher_personal_page->TooltipValue = "";

			// teacher_avatar
			$this->teacher_avatar->LinkCustomAttributes = "";
			$this->teacher_avatar->HrefValue = "";
			$this->teacher_avatar->TooltipValue = "";

			// teacher_description
			$this->teacher_description->LinkCustomAttributes = "";
			$this->teacher_description->HrefValue = "";
			$this->teacher_description->TooltipValue = "";

			// teacher_work_place
			$this->teacher_work_place->LinkCustomAttributes = "";
			$this->teacher_work_place->HrefValue = "";
			$this->teacher_work_place->TooltipValue = "";

			// teacher_active
			$this->teacher_active->LinkCustomAttributes = "";
			$this->teacher_active->HrefValue = "";
			$this->teacher_active->TooltipValue = "";

			// teacher_acadamic_title
			$this->teacher_acadamic_title->LinkCustomAttributes = "";
			$this->teacher_acadamic_title->HrefValue = "";
			$this->teacher_acadamic_title->TooltipValue = "";

			// teacher_birthday
			$this->teacher_birthday->LinkCustomAttributes = "";
			$this->teacher_birthday->HrefValue = "";
			$this->teacher_birthday->TooltipValue = "";

			// teacher_sex
			$this->teacher_sex->LinkCustomAttributes = "";
			$this->teacher_sex->HrefValue = "";
			$this->teacher_sex->TooltipValue = "";

			// teacher_faculty
			$this->teacher_faculty->LinkCustomAttributes = "";
			$this->teacher_faculty->HrefValue = "";
			$this->teacher_faculty->TooltipValue = "";

			// teacher_dept
			$this->teacher_dept->LinkCustomAttributes = "";
			$this->teacher_dept->HrefValue = "";
			$this->teacher_dept->TooltipValue = "";

			// teacher_rate
			$this->teacher_rate->LinkCustomAttributes = "";
			$this->teacher_rate->HrefValue = "";
			$this->teacher_rate->TooltipValue = "";

			// teacher_personality
			$this->teacher_personality->LinkCustomAttributes = "";
			$this->teacher_personality->HrefValue = "";
			$this->teacher_personality->TooltipValue = "";

			// advices
			$this->advices->LinkCustomAttributes = "";
			$this->advices->HrefValue = "";
			$this->advices->TooltipValue = "";

			// teacher_research
			$this->teacher_research->LinkCustomAttributes = "";
			$this->teacher_research->HrefValue = "";
			$this->teacher_research->TooltipValue = "";
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
if (!isset($tbl_teacher_view)) $tbl_teacher_view = new ctbl_teacher_view();

// Page init
$tbl_teacher_view->Page_Init();

// Page main
$tbl_teacher_view->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tbl_teacher_view = new ew_Page("tbl_teacher_view");
tbl_teacher_view.PageID = "view"; // Page ID
var EW_PAGE_ID = tbl_teacher_view.PageID; // For backward compatibility

// Form object
var ftbl_teacherview = new ew_Form("ftbl_teacherview");

// Form_CustomValidate event
ftbl_teacherview.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbl_teacherview.ValidateRequired = true;
<?php } else { ?>
ftbl_teacherview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftbl_teacherview.Lists["x_teacher_faculty"] = {"LinkField":"x_faculty_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_faculty_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ftbl_teacherview.Lists["x_teacher_dept"] = {"LinkField":"x_dept_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_dept_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("View") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $tbl_teacher->TableCaption() ?>&nbsp;&nbsp;</span><?php $tbl_teacher_view->ExportOptions->Render("body"); ?>
</p>
<p class="phpmaker">
<a href="<?php echo $tbl_teacher_view->ListUrl ?>" id="a_BackToList" class="ewLink"><?php echo $Language->Phrase("BackToList") ?></a>&nbsp;
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($tbl_teacher_view->AddUrl <> "") { ?>
<a href="<?php echo $tbl_teacher_view->AddUrl ?>" id="a_AddLink" class="ewLink"><?php echo $Language->Phrase("ViewPageAddLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($tbl_teacher_view->EditUrl <> "") { ?>
<a href="<?php echo $tbl_teacher_view->EditUrl ?>" id="a_EditLink" class="ewLink"><?php echo $Language->Phrase("ViewPageEditLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($tbl_teacher_view->CopyUrl <> "") { ?>
<a href="<?php echo $tbl_teacher_view->CopyUrl ?>" id="a_CopyLink" class="ewLink"><?php echo $Language->Phrase("ViewPageCopyLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($tbl_teacher_view->DeleteUrl <> "") { ?>
<a href="<?php echo $tbl_teacher_view->DeleteUrl ?>" id="a_DeleteLink" class="ewLink"><?php echo $Language->Phrase("ViewPageDeleteLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
</p>
<?php $tbl_teacher_view->ShowPageHeader(); ?>
<?php
$tbl_teacher_view->ShowMessage();
?>
<form name="ftbl_teacherview" id="ftbl_teacherview" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="tbl_teacher">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_tbl_teacherview" class="ewTable">
<?php if ($tbl_teacher->teacher_id->Visible) { // teacher_id ?>
	<tr id="r_teacher_id"<?php echo $tbl_teacher->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_teacher_teacher_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_teacher->teacher_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_teacher->teacher_id->CellAttributes() ?>><span id="el_tbl_teacher_teacher_id">
<span<?php echo $tbl_teacher->teacher_id->ViewAttributes() ?>>
<?php echo $tbl_teacher->teacher_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_teacher->teacher_name->Visible) { // teacher_name ?>
	<tr id="r_teacher_name"<?php echo $tbl_teacher->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_teacher_teacher_name"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_teacher->teacher_name->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_teacher->teacher_name->CellAttributes() ?>><span id="el_tbl_teacher_teacher_name">
<span<?php echo $tbl_teacher->teacher_name->ViewAttributes() ?>>
<?php echo $tbl_teacher->teacher_name->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_teacher->teacher_personal_page->Visible) { // teacher_personal_page ?>
	<tr id="r_teacher_personal_page"<?php echo $tbl_teacher->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_teacher_teacher_personal_page"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_teacher->teacher_personal_page->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_teacher->teacher_personal_page->CellAttributes() ?>><span id="el_tbl_teacher_teacher_personal_page">
<span<?php echo $tbl_teacher->teacher_personal_page->ViewAttributes() ?>>
<?php echo $tbl_teacher->teacher_personal_page->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_teacher->teacher_avatar->Visible) { // teacher_avatar ?>
	<tr id="r_teacher_avatar"<?php echo $tbl_teacher->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_teacher_teacher_avatar"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_teacher->teacher_avatar->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_teacher->teacher_avatar->CellAttributes() ?>><span id="el_tbl_teacher_teacher_avatar">
<span>
<?php if ($tbl_teacher->teacher_avatar->LinkAttributes() <> "") { ?>
<?php if (!empty($tbl_teacher->teacher_avatar->Upload->DbValue)) { ?>
<img src="<?php echo $tbl_teacher->teacher_avatar->ViewValue ?>" alt="" style="border: 0;"<?php echo $tbl_teacher->teacher_avatar->ViewAttributes() ?>>
<?php } elseif (!in_array($tbl_teacher->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($tbl_teacher->teacher_avatar->Upload->DbValue)) { ?>
<img src="<?php echo $tbl_teacher->teacher_avatar->ViewValue ?>" alt="" style="border: 0;"<?php echo $tbl_teacher->teacher_avatar->ViewAttributes() ?>>
<?php } elseif (!in_array($tbl_teacher->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_teacher->teacher_description->Visible) { // teacher_description ?>
	<tr id="r_teacher_description"<?php echo $tbl_teacher->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_teacher_teacher_description"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_teacher->teacher_description->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_teacher->teacher_description->CellAttributes() ?>><span id="el_tbl_teacher_teacher_description">
<span<?php echo $tbl_teacher->teacher_description->ViewAttributes() ?>>
<?php echo $tbl_teacher->teacher_description->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_teacher->teacher_work_place->Visible) { // teacher_work_place ?>
	<tr id="r_teacher_work_place"<?php echo $tbl_teacher->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_teacher_teacher_work_place"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_teacher->teacher_work_place->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_teacher->teacher_work_place->CellAttributes() ?>><span id="el_tbl_teacher_teacher_work_place">
<span<?php echo $tbl_teacher->teacher_work_place->ViewAttributes() ?>>
<?php echo $tbl_teacher->teacher_work_place->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_teacher->teacher_active->Visible) { // teacher_active ?>
	<tr id="r_teacher_active"<?php echo $tbl_teacher->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_teacher_teacher_active"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_teacher->teacher_active->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_teacher->teacher_active->CellAttributes() ?>><span id="el_tbl_teacher_teacher_active">
<span<?php echo $tbl_teacher->teacher_active->ViewAttributes() ?>>
<?php echo $tbl_teacher->teacher_active->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_teacher->teacher_acadamic_title->Visible) { // teacher_acadamic_title ?>
	<tr id="r_teacher_acadamic_title"<?php echo $tbl_teacher->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_teacher_teacher_acadamic_title"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_teacher->teacher_acadamic_title->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_teacher->teacher_acadamic_title->CellAttributes() ?>><span id="el_tbl_teacher_teacher_acadamic_title">
<span<?php echo $tbl_teacher->teacher_acadamic_title->ViewAttributes() ?>>
<?php echo $tbl_teacher->teacher_acadamic_title->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_teacher->teacher_birthday->Visible) { // teacher_birthday ?>
	<tr id="r_teacher_birthday"<?php echo $tbl_teacher->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_teacher_teacher_birthday"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_teacher->teacher_birthday->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_teacher->teacher_birthday->CellAttributes() ?>><span id="el_tbl_teacher_teacher_birthday">
<span<?php echo $tbl_teacher->teacher_birthday->ViewAttributes() ?>>
<?php echo $tbl_teacher->teacher_birthday->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_teacher->teacher_sex->Visible) { // teacher_sex ?>
	<tr id="r_teacher_sex"<?php echo $tbl_teacher->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_teacher_teacher_sex"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_teacher->teacher_sex->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_teacher->teacher_sex->CellAttributes() ?>><span id="el_tbl_teacher_teacher_sex">
<span<?php echo $tbl_teacher->teacher_sex->ViewAttributes() ?>>
<?php echo $tbl_teacher->teacher_sex->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_teacher->teacher_faculty->Visible) { // teacher_faculty ?>
	<tr id="r_teacher_faculty"<?php echo $tbl_teacher->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_teacher_teacher_faculty"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_teacher->teacher_faculty->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_teacher->teacher_faculty->CellAttributes() ?>><span id="el_tbl_teacher_teacher_faculty">
<span<?php echo $tbl_teacher->teacher_faculty->ViewAttributes() ?>>
<?php echo $tbl_teacher->teacher_faculty->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_teacher->teacher_dept->Visible) { // teacher_dept ?>
	<tr id="r_teacher_dept"<?php echo $tbl_teacher->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_teacher_teacher_dept"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_teacher->teacher_dept->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_teacher->teacher_dept->CellAttributes() ?>><span id="el_tbl_teacher_teacher_dept">
<span<?php echo $tbl_teacher->teacher_dept->ViewAttributes() ?>>
<?php echo $tbl_teacher->teacher_dept->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_teacher->teacher_rate->Visible) { // teacher_rate ?>
	<tr id="r_teacher_rate"<?php echo $tbl_teacher->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_teacher_teacher_rate"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_teacher->teacher_rate->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_teacher->teacher_rate->CellAttributes() ?>><span id="el_tbl_teacher_teacher_rate">
<span<?php echo $tbl_teacher->teacher_rate->ViewAttributes() ?>>
<?php echo $tbl_teacher->teacher_rate->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_teacher->teacher_personality->Visible) { // teacher_personality ?>
	<tr id="r_teacher_personality"<?php echo $tbl_teacher->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_teacher_teacher_personality"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_teacher->teacher_personality->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_teacher->teacher_personality->CellAttributes() ?>><span id="el_tbl_teacher_teacher_personality">
<span<?php echo $tbl_teacher->teacher_personality->ViewAttributes() ?>>
<?php echo $tbl_teacher->teacher_personality->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_teacher->advices->Visible) { // advices ?>
	<tr id="r_advices"<?php echo $tbl_teacher->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_teacher_advices"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_teacher->advices->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_teacher->advices->CellAttributes() ?>><span id="el_tbl_teacher_advices">
<span<?php echo $tbl_teacher->advices->ViewAttributes() ?>>
<?php echo $tbl_teacher->advices->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_teacher->teacher_research->Visible) { // teacher_research ?>
	<tr id="r_teacher_research"<?php echo $tbl_teacher->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_teacher_teacher_research"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_teacher->teacher_research->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_teacher->teacher_research->CellAttributes() ?>><span id="el_tbl_teacher_teacher_research">
<span<?php echo $tbl_teacher->teacher_research->ViewAttributes() ?>>
<?php echo $tbl_teacher->teacher_research->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
</form>
<br>
<script type="text/javascript">
ftbl_teacherview.Init();
</script>
<?php
$tbl_teacher_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbl_teacher_view->Page_Terminate();
?>
