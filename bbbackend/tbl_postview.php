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

$tbl_post_view = NULL; // Initialize page object first

class ctbl_post_view extends ctbl_post {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{9095E487-4467-4C46-97C7-01D1A378652D}";

	// Table name
	var $TableName = 'tbl_post';

	// Page object name
	var $PageObjName = 'tbl_post_view';

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

		// Table object (tbl_post)
		if (!isset($GLOBALS["tbl_post"])) {
			$GLOBALS["tbl_post"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tbl_post"];
		}
		$KeyUrl = "";
		if (@$_GET["post_id"] <> "") {
			$this->RecKey["post_id"] = $_GET["post_id"];
			$KeyUrl .= "&post_id=" . urlencode($this->RecKey["post_id"]);
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
			define("EW_TABLE_NAME", 'tbl_post', TRUE);

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
			if (@$_GET["post_id"] <> "") {
				$this->post_id->setQueryStringValue($_GET["post_id"]);
				$this->RecKey["post_id"] = $this->post_id->QueryStringValue;
			} else {
				$sReturnUrl = "tbl_postlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "tbl_postlist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "tbl_postlist.php"; // Not page request, return to list
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();

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
if (!isset($tbl_post_view)) $tbl_post_view = new ctbl_post_view();

// Page init
$tbl_post_view->Page_Init();

// Page main
$tbl_post_view->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tbl_post_view = new ew_Page("tbl_post_view");
tbl_post_view.PageID = "view"; // Page ID
var EW_PAGE_ID = tbl_post_view.PageID; // For backward compatibility

// Form object
var ftbl_postview = new ew_Form("ftbl_postview");

// Form_CustomValidate event
ftbl_postview.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbl_postview.ValidateRequired = true;
<?php } else { ?>
ftbl_postview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("View") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $tbl_post->TableCaption() ?>&nbsp;&nbsp;</span><?php $tbl_post_view->ExportOptions->Render("body"); ?>
</p>
<p class="phpmaker">
<a href="<?php echo $tbl_post_view->ListUrl ?>" id="a_BackToList" class="ewLink"><?php echo $Language->Phrase("BackToList") ?></a>&nbsp;
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($tbl_post_view->AddUrl <> "") { ?>
<a href="<?php echo $tbl_post_view->AddUrl ?>" id="a_AddLink" class="ewLink"><?php echo $Language->Phrase("ViewPageAddLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($tbl_post_view->EditUrl <> "") { ?>
<a href="<?php echo $tbl_post_view->EditUrl ?>" id="a_EditLink" class="ewLink"><?php echo $Language->Phrase("ViewPageEditLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($tbl_post_view->CopyUrl <> "") { ?>
<a href="<?php echo $tbl_post_view->CopyUrl ?>" id="a_CopyLink" class="ewLink"><?php echo $Language->Phrase("ViewPageCopyLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($tbl_post_view->DeleteUrl <> "") { ?>
<a href="<?php echo $tbl_post_view->DeleteUrl ?>" id="a_DeleteLink" class="ewLink"><?php echo $Language->Phrase("ViewPageDeleteLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
</p>
<?php $tbl_post_view->ShowPageHeader(); ?>
<?php
$tbl_post_view->ShowMessage();
?>
<form name="ftbl_postview" id="ftbl_postview" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="tbl_post">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_tbl_postview" class="ewTable">
<?php if ($tbl_post->post_id->Visible) { // post_id ?>
	<tr id="r_post_id"<?php echo $tbl_post->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_post_post_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_post->post_id->CellAttributes() ?>><span id="el_tbl_post_post_id">
<span<?php echo $tbl_post->post_id->ViewAttributes() ?>>
<?php echo $tbl_post->post_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_post->post_author->Visible) { // post_author ?>
	<tr id="r_post_author"<?php echo $tbl_post->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_post_post_author"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_author->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_post->post_author->CellAttributes() ?>><span id="el_tbl_post_post_author">
<span<?php echo $tbl_post->post_author->ViewAttributes() ?>>
<?php echo $tbl_post->post_author->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_post->post_date->Visible) { // post_date ?>
	<tr id="r_post_date"<?php echo $tbl_post->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_post_post_date"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_date->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_post->post_date->CellAttributes() ?>><span id="el_tbl_post_post_date">
<span<?php echo $tbl_post->post_date->ViewAttributes() ?>>
<?php echo $tbl_post->post_date->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_post->post_content->Visible) { // post_content ?>
	<tr id="r_post_content"<?php echo $tbl_post->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_post_post_content"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_content->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_post->post_content->CellAttributes() ?>><span id="el_tbl_post_post_content">
<span<?php echo $tbl_post->post_content->ViewAttributes() ?>>
<?php echo $tbl_post->post_content->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_post->post_title->Visible) { // post_title ?>
	<tr id="r_post_title"<?php echo $tbl_post->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_post_post_title"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_title->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_post->post_title->CellAttributes() ?>><span id="el_tbl_post_post_title">
<span<?php echo $tbl_post->post_title->ViewAttributes() ?>>
<?php echo $tbl_post->post_title->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_post->post_active->Visible) { // post_active ?>
	<tr id="r_post_active"<?php echo $tbl_post->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_post_post_active"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_active->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_post->post_active->CellAttributes() ?>><span id="el_tbl_post_post_active">
<span<?php echo $tbl_post->post_active->ViewAttributes() ?>>
<?php echo $tbl_post->post_active->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_post->post_rate->Visible) { // post_rate ?>
	<tr id="r_post_rate"<?php echo $tbl_post->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_post_post_rate"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_rate->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_post->post_rate->CellAttributes() ?>><span id="el_tbl_post_post_rate">
<span<?php echo $tbl_post->post_rate->ViewAttributes() ?>>
<?php echo $tbl_post->post_rate->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_post->post_type->Visible) { // post_type ?>
	<tr id="r_post_type"<?php echo $tbl_post->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_post_post_type"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_type->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_post->post_type->CellAttributes() ?>><span id="el_tbl_post_post_type">
<span<?php echo $tbl_post->post_type->ViewAttributes() ?>>
<?php echo $tbl_post->post_type->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_post->post_class->Visible) { // post_class ?>
	<tr id="r_post_class"<?php echo $tbl_post->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_post_post_class"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_class->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_post->post_class->CellAttributes() ?>><span id="el_tbl_post_post_class">
<span<?php echo $tbl_post->post_class->ViewAttributes() ?>>
<?php echo $tbl_post->post_class->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tbl_post->post_group->Visible) { // post_group ?>
	<tr id="r_post_group"<?php echo $tbl_post->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tbl_post_post_group"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_group->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tbl_post->post_group->CellAttributes() ?>><span id="el_tbl_post_post_group">
<span<?php echo $tbl_post->post_group->ViewAttributes() ?>>
<?php echo $tbl_post->post_group->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
</form>
<br>
<script type="text/javascript">
ftbl_postview.Init();
</script>
<?php
$tbl_post_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbl_post_view->Page_Terminate();
?>
