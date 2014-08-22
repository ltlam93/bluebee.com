<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "tbl_deptinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$tbl_dept_view = NULL; // Initialize page object first

class ctbl_dept_view extends ctbl_dept {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{9095E487-4467-4C46-97C7-01D1A378652D}";

	// Table name
	var $TableName = 'tbl_dept';

	// Page object name
	var $PageObjName = 'tbl_dept_view';

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

		// Table object (tbl_dept)
		if (!isset($GLOBALS["tbl_dept"])) {
			$GLOBALS["tbl_dept"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tbl_dept"];
		}
		$KeyUrl = "";
		if (@$_GET["dept_id"] <> "") {
			$this->RecKey["dept_id"] = $_GET["dept_id"];
			$KeyUrl .= "&dept_id=" . urlencode($this->RecKey["dept_id"]);
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
			define("EW_TABLE_NAME", 'tbl_dept', TRUE);

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
		$this->dept_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			if (@$_GET["dept_id"] <> "") {
				$this->dept_id->setQueryStringValue($_GET["dept_id"]);
				$this->RecKey["dept_id"] = $this->dept_id->QueryStringValue;
			} else {
				$sReturnUrl = "tbl_deptlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "tbl_deptlist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "tbl_deptlist.php"; // Not page request, return to list
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
		$this->dept_id->setDbValue($rs->fields('dept_id'));
		$this->dept_name->setDbValue($rs->fields('dept_name'));
		$this->dept_active->setDbValue($rs->fields('dept_active'));
		$this->dept_faculty->setDbValue($rs->fields('dept_faculty'));
		$this->dept_target->setDbValue($rs->fields('dept_target'));
		$this->dept_knowleadge->setDbValue($rs->fields('dept_knowleadge'));
		$this->dept_behavior->setDbValue($rs->fields('dept_behavior'));
		$this->dept_out_standard->setDbValue($rs->fields('dept_out_standard'));
		$this->dept_contact->setDbValue($rs->fields('dept_contact'));
		$this->dept_in_standart->setDbValue($rs->fields('dept_in_standart'));
		$this->dept_language->setDbValue($rs->fields('dept_language'));
		$this->dept_credits->setDbValue($rs->fields('dept_credits'));
		$this->dept_code->setDbValue($rs->fields('dept_code'));
		$this->dept_link_download->setDbValue($rs->fields('dept_link_download'));
		$this->dept_skill->setDbValue($rs->fields('dept_skill'));
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
		// dept_id
		// dept_name
		// dept_active
		// dept_faculty
		// dept_target
		// dept_knowleadge
		// dept_behavior
		// dept_out_standard
		// dept_contact
		// dept_in_standart
		// dept_language
		// dept_credits
		// dept_code
		// dept_link_download
		// dept_skill

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// dept_id
			$this->dept_id->ViewValue = $this->dept_id->CurrentValue;
			$this->dept_id->ViewCustomAttributes = "";

			// dept_name
			$this->dept_name->ViewValue = $this->dept_name->CurrentValue;
			$this->dept_name->ViewCustomAttributes = "";

			// dept_active
			$this->dept_active->ViewValue = $this->dept_active->CurrentValue;
			$this->dept_active->ViewCustomAttributes = "";

			// dept_faculty
			if (strval($this->dept_faculty->CurrentValue) <> "") {
				$sFilterWrk = "`faculty_id`" . ew_SearchString("=", $this->dept_faculty->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `faculty_id`, `faculty_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `tbl_faculty`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->dept_faculty->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->dept_faculty->ViewValue = $this->dept_faculty->CurrentValue;
				}
			} else {
				$this->dept_faculty->ViewValue = NULL;
			}
			$this->dept_faculty->ViewCustomAttributes = "";

			// dept_target
			$this->dept_target->ViewValue = $this->dept_target->CurrentValue;
			$this->dept_target->ViewCustomAttributes = "";

			// dept_knowleadge
			$this->dept_knowleadge->ViewValue = $this->dept_knowleadge->CurrentValue;
			$this->dept_knowleadge->ViewCustomAttributes = "";

			// dept_behavior
			$this->dept_behavior->ViewValue = $this->dept_behavior->CurrentValue;
			$this->dept_behavior->ViewCustomAttributes = "";

			// dept_out_standard
			$this->dept_out_standard->ViewValue = $this->dept_out_standard->CurrentValue;
			$this->dept_out_standard->ViewCustomAttributes = "";

			// dept_contact
			$this->dept_contact->ViewValue = $this->dept_contact->CurrentValue;
			$this->dept_contact->ViewCustomAttributes = "";

			// dept_in_standart
			$this->dept_in_standart->ViewValue = $this->dept_in_standart->CurrentValue;
			$this->dept_in_standart->ViewCustomAttributes = "";

			// dept_language
			$this->dept_language->ViewValue = $this->dept_language->CurrentValue;
			$this->dept_language->ViewCustomAttributes = "";

			// dept_credits
			$this->dept_credits->ViewValue = $this->dept_credits->CurrentValue;
			$this->dept_credits->ViewCustomAttributes = "";

			// dept_code
			$this->dept_code->ViewValue = $this->dept_code->CurrentValue;
			$this->dept_code->ViewCustomAttributes = "";

			// dept_link_download
			$this->dept_link_download->ViewValue = $this->dept_link_download->CurrentValue;
			$this->dept_link_download->ViewCustomAttributes = "";

			// dept_skill
			$this->dept_skill->ViewValue = $this->dept_skill->CurrentValue;
			$this->dept_skill->ViewCustomAttributes = "";

			// dept_id
			$this->dept_id->LinkCustomAttributes = "";
			$this->dept_id->HrefValue = "";
			$this->dept_id->TooltipValue = "";

			// dept_name
			$this->dept_name->LinkCustomAttributes = "";
			$this->dept_name->HrefValue = "";
			$this->dept_name->TooltipValue = "";

			// dept_active
			$this->dept_active->LinkCustomAttributes = "";
			$this->dept_active->HrefValue = "";
			$this->dept_active->TooltipValue = "";

			// dept_faculty
			$this->dept_faculty->LinkCustomAttributes = "";
			$this->dept_faculty->HrefValue = "";
			$this->dept_faculty->TooltipValue = "";

			// dept_target
			$this->dept_target->LinkCustomAttributes = "";
			$this->dept_target->HrefValue = "";
			$this->dept_target->TooltipValue = "";

			// dept_knowleadge
			$this->dept_knowleadge->LinkCustomAttributes = "";
			$this->dept_knowleadge->HrefValue = "";
			$this->dept_knowleadge->TooltipValue = "";

			// dept_behavior
			$this->dept_behavior->LinkCustomAttributes = "";
			$this->dept_behavior->HrefValue = "";
			$this->dept_behavior->TooltipValue = "";

			// dept_out_standard
			$this->dept_out_standard->LinkCustomAttributes = "";
			$this->dept_out_standard->HrefValue = "";
			$this->dept_out_standard->TooltipValue = "";

			// dept_contact
			$this->dept_contact->LinkCustomAttributes = "";
			$this->dept_contact->HrefValue = "";
			$this->dept_contact->TooltipValue = "";

			// dept_in_standart
			$this->dept_in_standart->LinkCustomAttributes = "";
			$this->dept_in_standart->HrefValue = "";
			$this->dept_in_standart->TooltipValue = "";

			// dept_language
			$this->dept_language->LinkCustomAttributes = "";
			$this->dept_language->HrefValue = "";
			$this->dept_language->TooltipValue = "";

			// dept_credits
			$this->dept_credits->LinkCustomAttributes = "";
			$this->dept_credits->HrefValue = "";
			$this->dept_credits->TooltipValue = "";

			// dept_code
			$this->dept_code->LinkCustomAttributes = "";
			$this->dept_code->HrefValue = "";
			$this->dept_code->TooltipValue = "";

			// dept_link_download
			$this->dept_link_download->LinkCustomAttributes = "";
			$this->dept_link_download->HrefValue = "";
			$this->dept_link_download->TooltipValue = "";

			// dept_skill
			$this->dept_skill->LinkCustomAttributes = "";
			$this->dept_skill->HrefValue = "";
			$this->dept_skill->TooltipValue = "";
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
if (!isset($tbl_dept_view)) $tbl_dept_view = new ctbl_dept_view();

// Page init
$tbl_dept_view->Page_Init();

// Page main
$tbl_dept_view->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tbl_dept_view = new ew_Page("tbl_dept_view");
tbl_dept_view.PageID = "view"; // Page ID
var EW_PAGE_ID = tbl_dept_view.PageID; // For backward compatibility

// Form object
var ftbl_deptview = new ew_Form("ftbl_deptview");

// Form_CustomValidate event
ftbl_deptview.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbl_deptview.ValidateRequired = true;
<?php } else { ?>
ftbl_deptview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftbl_deptview.Lists["x_dept_faculty"] = {"LinkField":"x_faculty_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_faculty_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("View") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $tbl_dept->TableCaption() ?>&nbsp;&nbsp;</span><?php $tbl_dept_view->ExportOptions->Render("body"); ?>
</p>
<p class="phpmaker">
<a href="<?php echo $tbl_dept_view->ListUrl ?>" id="a_BackToList" class="ewLink"><?php echo $Language->Phrase("BackToList") ?></a>&nbsp;
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($tbl_dept_view->AddUrl <> "") { ?>
<a href="<?php echo $tbl_dept_view->AddUrl ?>" id="a_AddLink" class="ewLink"><?php echo $Language->Phrase("ViewPageAddLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($tbl_dept_view->EditUrl <> "") { ?>
<a href="<?php echo $tbl_dept_view->EditUrl ?>" id="a_EditLink" class="ewLink"><?php echo $Language->Phrase("ViewPageEditLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($tbl_dept_view->CopyUrl <> "") { ?>
<a href="<?php echo $tbl_dept_view->CopyUrl ?>" id="a_CopyLink" class="ewLink"><?php echo $Language->Phrase("ViewPageCopyLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($tbl_dept_view->DeleteUrl <> "") { ?>
<a href="<?php echo $tbl_dept_view->DeleteUrl ?>" id="a_DeleteLink" class="ewLink"><?php echo $Language->Phrase("ViewPageDeleteLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
</p>
<?php $tbl_dept_view->ShowPageHeader(); ?>
<?php
$tbl_dept_view->ShowMessage();
?>
<form name="ftbl_deptview" id="ftbl_deptview" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="tbl_dept">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_tbl_deptview" class="ewTable">
<?php if ($tbl_dept->dept_id->Visible) { // dept_id ?>
	<tr id="r_dept_id"<?php echo $tbl_dept->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_dept_dept_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_dept->dept_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_dept->dept_id->CellAttributes() ?>><span id="el_tbl_dept_dept_id">
<span<?php echo $tbl_dept->dept_id->ViewAttributes() ?>>
<?php echo $tbl_dept->dept_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_dept->dept_name->Visible) { // dept_name ?>
	<tr id="r_dept_name"<?php echo $tbl_dept->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_dept_dept_name"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_dept->dept_name->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_dept->dept_name->CellAttributes() ?>><span id="el_tbl_dept_dept_name">
<span<?php echo $tbl_dept->dept_name->ViewAttributes() ?>>
<?php echo $tbl_dept->dept_name->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_dept->dept_active->Visible) { // dept_active ?>
	<tr id="r_dept_active"<?php echo $tbl_dept->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_dept_dept_active"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_dept->dept_active->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_dept->dept_active->CellAttributes() ?>><span id="el_tbl_dept_dept_active">
<span<?php echo $tbl_dept->dept_active->ViewAttributes() ?>>
<?php echo $tbl_dept->dept_active->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_dept->dept_faculty->Visible) { // dept_faculty ?>
	<tr id="r_dept_faculty"<?php echo $tbl_dept->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_dept_dept_faculty"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_dept->dept_faculty->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_dept->dept_faculty->CellAttributes() ?>><span id="el_tbl_dept_dept_faculty">
<span<?php echo $tbl_dept->dept_faculty->ViewAttributes() ?>>
<?php echo $tbl_dept->dept_faculty->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_dept->dept_target->Visible) { // dept_target ?>
	<tr id="r_dept_target"<?php echo $tbl_dept->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_dept_dept_target"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_dept->dept_target->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_dept->dept_target->CellAttributes() ?>><span id="el_tbl_dept_dept_target">
<span<?php echo $tbl_dept->dept_target->ViewAttributes() ?>>
<?php echo $tbl_dept->dept_target->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_dept->dept_knowleadge->Visible) { // dept_knowleadge ?>
	<tr id="r_dept_knowleadge"<?php echo $tbl_dept->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_dept_dept_knowleadge"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_dept->dept_knowleadge->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_dept->dept_knowleadge->CellAttributes() ?>><span id="el_tbl_dept_dept_knowleadge">
<span<?php echo $tbl_dept->dept_knowleadge->ViewAttributes() ?>>
<?php echo $tbl_dept->dept_knowleadge->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_dept->dept_behavior->Visible) { // dept_behavior ?>
	<tr id="r_dept_behavior"<?php echo $tbl_dept->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_dept_dept_behavior"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_dept->dept_behavior->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_dept->dept_behavior->CellAttributes() ?>><span id="el_tbl_dept_dept_behavior">
<span<?php echo $tbl_dept->dept_behavior->ViewAttributes() ?>>
<?php echo $tbl_dept->dept_behavior->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_dept->dept_out_standard->Visible) { // dept_out_standard ?>
	<tr id="r_dept_out_standard"<?php echo $tbl_dept->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_dept_dept_out_standard"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_dept->dept_out_standard->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_dept->dept_out_standard->CellAttributes() ?>><span id="el_tbl_dept_dept_out_standard">
<span<?php echo $tbl_dept->dept_out_standard->ViewAttributes() ?>>
<?php echo $tbl_dept->dept_out_standard->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_dept->dept_contact->Visible) { // dept_contact ?>
	<tr id="r_dept_contact"<?php echo $tbl_dept->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_dept_dept_contact"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_dept->dept_contact->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_dept->dept_contact->CellAttributes() ?>><span id="el_tbl_dept_dept_contact">
<span<?php echo $tbl_dept->dept_contact->ViewAttributes() ?>>
<?php echo $tbl_dept->dept_contact->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_dept->dept_in_standart->Visible) { // dept_in_standart ?>
	<tr id="r_dept_in_standart"<?php echo $tbl_dept->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_dept_dept_in_standart"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_dept->dept_in_standart->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_dept->dept_in_standart->CellAttributes() ?>><span id="el_tbl_dept_dept_in_standart">
<span<?php echo $tbl_dept->dept_in_standart->ViewAttributes() ?>>
<?php echo $tbl_dept->dept_in_standart->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_dept->dept_language->Visible) { // dept_language ?>
	<tr id="r_dept_language"<?php echo $tbl_dept->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_dept_dept_language"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_dept->dept_language->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_dept->dept_language->CellAttributes() ?>><span id="el_tbl_dept_dept_language">
<span<?php echo $tbl_dept->dept_language->ViewAttributes() ?>>
<?php echo $tbl_dept->dept_language->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_dept->dept_credits->Visible) { // dept_credits ?>
	<tr id="r_dept_credits"<?php echo $tbl_dept->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_dept_dept_credits"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_dept->dept_credits->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_dept->dept_credits->CellAttributes() ?>><span id="el_tbl_dept_dept_credits">
<span<?php echo $tbl_dept->dept_credits->ViewAttributes() ?>>
<?php echo $tbl_dept->dept_credits->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_dept->dept_code->Visible) { // dept_code ?>
	<tr id="r_dept_code"<?php echo $tbl_dept->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_dept_dept_code"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_dept->dept_code->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_dept->dept_code->CellAttributes() ?>><span id="el_tbl_dept_dept_code">
<span<?php echo $tbl_dept->dept_code->ViewAttributes() ?>>
<?php echo $tbl_dept->dept_code->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_dept->dept_link_download->Visible) { // dept_link_download ?>
	<tr id="r_dept_link_download"<?php echo $tbl_dept->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_dept_dept_link_download"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_dept->dept_link_download->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_dept->dept_link_download->CellAttributes() ?>><span id="el_tbl_dept_dept_link_download">
<span<?php echo $tbl_dept->dept_link_download->ViewAttributes() ?>>
<?php echo $tbl_dept->dept_link_download->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_dept->dept_skill->Visible) { // dept_skill ?>
	<tr id="r_dept_skill"<?php echo $tbl_dept->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_dept_dept_skill"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_dept->dept_skill->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_dept->dept_skill->CellAttributes() ?>><span id="el_tbl_dept_dept_skill">
<span<?php echo $tbl_dept->dept_skill->ViewAttributes() ?>>
<?php echo $tbl_dept->dept_skill->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
</form>
<br>
<script type="text/javascript">
ftbl_deptview.Init();
</script>
<?php
$tbl_dept_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbl_dept_view->Page_Terminate();
?>
