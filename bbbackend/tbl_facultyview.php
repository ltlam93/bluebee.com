<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "tbl_facultyinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$tbl_faculty_view = NULL; // Initialize page object first

class ctbl_faculty_view extends ctbl_faculty {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{9095E487-4467-4C46-97C7-01D1A378652D}";

	// Table name
	var $TableName = 'tbl_faculty';

	// Page object name
	var $PageObjName = 'tbl_faculty_view';

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

		// Table object (tbl_faculty)
		if (!isset($GLOBALS["tbl_faculty"])) {
			$GLOBALS["tbl_faculty"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tbl_faculty"];
		}
		$KeyUrl = "";
		if (@$_GET["faculty_id"] <> "") {
			$this->RecKey["faculty_id"] = $_GET["faculty_id"];
			$KeyUrl .= "&faculty_id=" . urlencode($this->RecKey["faculty_id"]);
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
			define("EW_TABLE_NAME", 'tbl_faculty', TRUE);

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
		$this->faculty_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			if (@$_GET["faculty_id"] <> "") {
				$this->faculty_id->setQueryStringValue($_GET["faculty_id"]);
				$this->RecKey["faculty_id"] = $this->faculty_id->QueryStringValue;
			} else {
				$sReturnUrl = "tbl_facultylist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "tbl_facultylist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "tbl_facultylist.php"; // Not page request, return to list
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
		$this->faculty_id->setDbValue($rs->fields('faculty_id'));
		$this->faculty_university->setDbValue($rs->fields('faculty_university'));
		$this->faculty_name->setDbValue($rs->fields('faculty_name'));
		$this->faculty_code->setDbValue($rs->fields('faculty_code'));
		$this->faculty_active->setDbValue($rs->fields('faculty_active'));
		$this->faculty_research->setDbValue($rs->fields('faculty_research'));
		$this->faculty_lab->setDbValue($rs->fields('faculty_lab'));
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
		// faculty_id
		// faculty_university
		// faculty_name
		// faculty_code
		// faculty_active
		// faculty_research
		// faculty_lab

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// faculty_id
			$this->faculty_id->ViewValue = $this->faculty_id->CurrentValue;
			$this->faculty_id->ViewCustomAttributes = "";

			// faculty_university
			$this->faculty_university->ViewValue = $this->faculty_university->CurrentValue;
			$this->faculty_university->ViewCustomAttributes = "";

			// faculty_name
			$this->faculty_name->ViewValue = $this->faculty_name->CurrentValue;
			$this->faculty_name->ViewCustomAttributes = "";

			// faculty_code
			$this->faculty_code->ViewValue = $this->faculty_code->CurrentValue;
			$this->faculty_code->ViewCustomAttributes = "";

			// faculty_active
			$this->faculty_active->ViewValue = $this->faculty_active->CurrentValue;
			$this->faculty_active->ViewCustomAttributes = "";

			// faculty_research
			$this->faculty_research->ViewValue = $this->faculty_research->CurrentValue;
			$this->faculty_research->ViewCustomAttributes = "";

			// faculty_lab
			$this->faculty_lab->ViewValue = $this->faculty_lab->CurrentValue;
			$this->faculty_lab->ViewCustomAttributes = "";

			// faculty_id
			$this->faculty_id->LinkCustomAttributes = "";
			$this->faculty_id->HrefValue = "";
			$this->faculty_id->TooltipValue = "";

			// faculty_university
			$this->faculty_university->LinkCustomAttributes = "";
			$this->faculty_university->HrefValue = "";
			$this->faculty_university->TooltipValue = "";

			// faculty_name
			$this->faculty_name->LinkCustomAttributes = "";
			$this->faculty_name->HrefValue = "";
			$this->faculty_name->TooltipValue = "";

			// faculty_code
			$this->faculty_code->LinkCustomAttributes = "";
			$this->faculty_code->HrefValue = "";
			$this->faculty_code->TooltipValue = "";

			// faculty_active
			$this->faculty_active->LinkCustomAttributes = "";
			$this->faculty_active->HrefValue = "";
			$this->faculty_active->TooltipValue = "";

			// faculty_research
			$this->faculty_research->LinkCustomAttributes = "";
			$this->faculty_research->HrefValue = "";
			$this->faculty_research->TooltipValue = "";

			// faculty_lab
			$this->faculty_lab->LinkCustomAttributes = "";
			$this->faculty_lab->HrefValue = "";
			$this->faculty_lab->TooltipValue = "";
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
if (!isset($tbl_faculty_view)) $tbl_faculty_view = new ctbl_faculty_view();

// Page init
$tbl_faculty_view->Page_Init();

// Page main
$tbl_faculty_view->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tbl_faculty_view = new ew_Page("tbl_faculty_view");
tbl_faculty_view.PageID = "view"; // Page ID
var EW_PAGE_ID = tbl_faculty_view.PageID; // For backward compatibility

// Form object
var ftbl_facultyview = new ew_Form("ftbl_facultyview");

// Form_CustomValidate event
ftbl_facultyview.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbl_facultyview.ValidateRequired = true;
<?php } else { ?>
ftbl_facultyview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("View") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $tbl_faculty->TableCaption() ?>&nbsp;&nbsp;</span><?php $tbl_faculty_view->ExportOptions->Render("body"); ?>
</p>
<p class="phpmaker">
<a href="<?php echo $tbl_faculty_view->ListUrl ?>" id="a_BackToList" class="ewLink"><?php echo $Language->Phrase("BackToList") ?></a>&nbsp;
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($tbl_faculty_view->AddUrl <> "") { ?>
<a href="<?php echo $tbl_faculty_view->AddUrl ?>" id="a_AddLink" class="ewLink"><?php echo $Language->Phrase("ViewPageAddLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($tbl_faculty_view->EditUrl <> "") { ?>
<a href="<?php echo $tbl_faculty_view->EditUrl ?>" id="a_EditLink" class="ewLink"><?php echo $Language->Phrase("ViewPageEditLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($tbl_faculty_view->CopyUrl <> "") { ?>
<a href="<?php echo $tbl_faculty_view->CopyUrl ?>" id="a_CopyLink" class="ewLink"><?php echo $Language->Phrase("ViewPageCopyLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($tbl_faculty_view->DeleteUrl <> "") { ?>
<a href="<?php echo $tbl_faculty_view->DeleteUrl ?>" id="a_DeleteLink" class="ewLink"><?php echo $Language->Phrase("ViewPageDeleteLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
</p>
<?php $tbl_faculty_view->ShowPageHeader(); ?>
<?php
$tbl_faculty_view->ShowMessage();
?>
<form name="ftbl_facultyview" id="ftbl_facultyview" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="tbl_faculty">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_tbl_facultyview" class="ewTable">
<?php if ($tbl_faculty->faculty_id->Visible) { // faculty_id ?>
	<tr id="r_faculty_id"<?php echo $tbl_faculty->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_faculty_faculty_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_faculty->faculty_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_faculty->faculty_id->CellAttributes() ?>><span id="el_tbl_faculty_faculty_id">
<span<?php echo $tbl_faculty->faculty_id->ViewAttributes() ?>>
<?php echo $tbl_faculty->faculty_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_faculty->faculty_university->Visible) { // faculty_university ?>
	<tr id="r_faculty_university"<?php echo $tbl_faculty->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_faculty_faculty_university"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_faculty->faculty_university->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_faculty->faculty_university->CellAttributes() ?>><span id="el_tbl_faculty_faculty_university">
<span<?php echo $tbl_faculty->faculty_university->ViewAttributes() ?>>
<?php echo $tbl_faculty->faculty_university->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_faculty->faculty_name->Visible) { // faculty_name ?>
	<tr id="r_faculty_name"<?php echo $tbl_faculty->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_faculty_faculty_name"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_faculty->faculty_name->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_faculty->faculty_name->CellAttributes() ?>><span id="el_tbl_faculty_faculty_name">
<span<?php echo $tbl_faculty->faculty_name->ViewAttributes() ?>>
<?php echo $tbl_faculty->faculty_name->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_faculty->faculty_code->Visible) { // faculty_code ?>
	<tr id="r_faculty_code"<?php echo $tbl_faculty->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_faculty_faculty_code"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_faculty->faculty_code->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_faculty->faculty_code->CellAttributes() ?>><span id="el_tbl_faculty_faculty_code">
<span<?php echo $tbl_faculty->faculty_code->ViewAttributes() ?>>
<?php echo $tbl_faculty->faculty_code->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_faculty->faculty_active->Visible) { // faculty_active ?>
	<tr id="r_faculty_active"<?php echo $tbl_faculty->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_faculty_faculty_active"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_faculty->faculty_active->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_faculty->faculty_active->CellAttributes() ?>><span id="el_tbl_faculty_faculty_active">
<span<?php echo $tbl_faculty->faculty_active->ViewAttributes() ?>>
<?php echo $tbl_faculty->faculty_active->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_faculty->faculty_research->Visible) { // faculty_research ?>
	<tr id="r_faculty_research"<?php echo $tbl_faculty->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_faculty_faculty_research"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_faculty->faculty_research->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_faculty->faculty_research->CellAttributes() ?>><span id="el_tbl_faculty_faculty_research">
<span<?php echo $tbl_faculty->faculty_research->ViewAttributes() ?>>
<?php echo $tbl_faculty->faculty_research->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_faculty->faculty_lab->Visible) { // faculty_lab ?>
	<tr id="r_faculty_lab"<?php echo $tbl_faculty->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_faculty_faculty_lab"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_faculty->faculty_lab->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_faculty->faculty_lab->CellAttributes() ?>><span id="el_tbl_faculty_faculty_lab">
<span<?php echo $tbl_faculty->faculty_lab->ViewAttributes() ?>>
<?php echo $tbl_faculty->faculty_lab->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
</form>
<br>
<script type="text/javascript">
ftbl_facultyview.Init();
</script>
<?php
$tbl_faculty_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbl_faculty_view->Page_Terminate();
?>
