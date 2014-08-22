<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "notificationsinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$notifications_view = NULL; // Initialize page object first

class cnotifications_view extends cnotifications {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{9095E487-4467-4C46-97C7-01D1A378652D}";

	// Table name
	var $TableName = 'notifications';

	// Page object name
	var $PageObjName = 'notifications_view';

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

		// Table object (notifications)
		if (!isset($GLOBALS["notifications"])) {
			$GLOBALS["notifications"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["notifications"];
		}
		$KeyUrl = "";
		if (@$_GET["id"] <> "") {
			$this->RecKey["id"] = $_GET["id"];
			$KeyUrl .= "&id=" . urlencode($this->RecKey["id"]);
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
			define("EW_TABLE_NAME", 'notifications', TRUE);

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
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			if (@$_GET["id"] <> "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->RecKey["id"] = $this->id->QueryStringValue;
			} else {
				$sReturnUrl = "notificationslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "notificationslist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "notificationslist.php"; // Not page request, return to list
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
		$this->id->setDbValue($rs->fields('id'));
		$this->user_id->setDbValue($rs->fields('user_id'));
		$this->action->setDbValue($rs->fields('action'));
		$this->object_type->setDbValue($rs->fields('object_type'));
		$this->object_id->setDbValue($rs->fields('object_id'));
		$this->possessive->setDbValue($rs->fields('possessive'));
		$this->from_user_id->setDbValue($rs->fields('from_user_id'));
		$this->clicked->setDbValue($rs->fields('clicked'));
		$this->relevant_id->setDbValue($rs->fields('relevant_id'));
		$this->relevant_object->setDbValue($rs->fields('relevant_object'));
		$this->app->setDbValue($rs->fields('app'));
		$this->is_active->setDbValue($rs->fields('is_active'));
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
		// id
		// user_id
		// action
		// object_type
		// object_id
		// possessive
		// from_user_id
		// clicked
		// relevant_id
		// relevant_object
		// app
		// is_active

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// user_id
			$this->user_id->ViewValue = $this->user_id->CurrentValue;
			$this->user_id->ViewCustomAttributes = "";

			// action
			$this->action->ViewValue = $this->action->CurrentValue;
			$this->action->ViewCustomAttributes = "";

			// object_type
			$this->object_type->ViewValue = $this->object_type->CurrentValue;
			$this->object_type->ViewCustomAttributes = "";

			// object_id
			$this->object_id->ViewValue = $this->object_id->CurrentValue;
			$this->object_id->ViewCustomAttributes = "";

			// possessive
			$this->possessive->ViewValue = $this->possessive->CurrentValue;
			$this->possessive->ViewCustomAttributes = "";

			// from_user_id
			$this->from_user_id->ViewValue = $this->from_user_id->CurrentValue;
			$this->from_user_id->ViewCustomAttributes = "";

			// clicked
			$this->clicked->ViewValue = $this->clicked->CurrentValue;
			$this->clicked->ViewCustomAttributes = "";

			// relevant_id
			$this->relevant_id->ViewValue = $this->relevant_id->CurrentValue;
			$this->relevant_id->ViewCustomAttributes = "";

			// relevant_object
			$this->relevant_object->ViewValue = $this->relevant_object->CurrentValue;
			$this->relevant_object->ViewCustomAttributes = "";

			// app
			$this->app->ViewValue = $this->app->CurrentValue;
			$this->app->ViewCustomAttributes = "";

			// is_active
			$this->is_active->ViewValue = $this->is_active->CurrentValue;
			$this->is_active->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// user_id
			$this->user_id->LinkCustomAttributes = "";
			$this->user_id->HrefValue = "";
			$this->user_id->TooltipValue = "";

			// action
			$this->action->LinkCustomAttributes = "";
			$this->action->HrefValue = "";
			$this->action->TooltipValue = "";

			// object_type
			$this->object_type->LinkCustomAttributes = "";
			$this->object_type->HrefValue = "";
			$this->object_type->TooltipValue = "";

			// object_id
			$this->object_id->LinkCustomAttributes = "";
			$this->object_id->HrefValue = "";
			$this->object_id->TooltipValue = "";

			// possessive
			$this->possessive->LinkCustomAttributes = "";
			$this->possessive->HrefValue = "";
			$this->possessive->TooltipValue = "";

			// from_user_id
			$this->from_user_id->LinkCustomAttributes = "";
			$this->from_user_id->HrefValue = "";
			$this->from_user_id->TooltipValue = "";

			// clicked
			$this->clicked->LinkCustomAttributes = "";
			$this->clicked->HrefValue = "";
			$this->clicked->TooltipValue = "";

			// relevant_id
			$this->relevant_id->LinkCustomAttributes = "";
			$this->relevant_id->HrefValue = "";
			$this->relevant_id->TooltipValue = "";

			// relevant_object
			$this->relevant_object->LinkCustomAttributes = "";
			$this->relevant_object->HrefValue = "";
			$this->relevant_object->TooltipValue = "";

			// app
			$this->app->LinkCustomAttributes = "";
			$this->app->HrefValue = "";
			$this->app->TooltipValue = "";

			// is_active
			$this->is_active->LinkCustomAttributes = "";
			$this->is_active->HrefValue = "";
			$this->is_active->TooltipValue = "";
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
if (!isset($notifications_view)) $notifications_view = new cnotifications_view();

// Page init
$notifications_view->Page_Init();

// Page main
$notifications_view->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var notifications_view = new ew_Page("notifications_view");
notifications_view.PageID = "view"; // Page ID
var EW_PAGE_ID = notifications_view.PageID; // For backward compatibility

// Form object
var fnotificationsview = new ew_Form("fnotificationsview");

// Form_CustomValidate event
fnotificationsview.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fnotificationsview.ValidateRequired = true;
<?php } else { ?>
fnotificationsview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("View") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $notifications->TableCaption() ?>&nbsp;&nbsp;</span><?php $notifications_view->ExportOptions->Render("body"); ?>
</p>
<p class="phpmaker">
<a href="<?php echo $notifications_view->ListUrl ?>" id="a_BackToList" class="ewLink"><?php echo $Language->Phrase("BackToList") ?></a>&nbsp;
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($notifications_view->AddUrl <> "") { ?>
<a href="<?php echo $notifications_view->AddUrl ?>" id="a_AddLink" class="ewLink"><?php echo $Language->Phrase("ViewPageAddLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($notifications_view->EditUrl <> "") { ?>
<a href="<?php echo $notifications_view->EditUrl ?>" id="a_EditLink" class="ewLink"><?php echo $Language->Phrase("ViewPageEditLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($notifications_view->CopyUrl <> "") { ?>
<a href="<?php echo $notifications_view->CopyUrl ?>" id="a_CopyLink" class="ewLink"><?php echo $Language->Phrase("ViewPageCopyLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($notifications_view->DeleteUrl <> "") { ?>
<a href="<?php echo $notifications_view->DeleteUrl ?>" id="a_DeleteLink" class="ewLink"><?php echo $Language->Phrase("ViewPageDeleteLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
</p>
<?php $notifications_view->ShowPageHeader(); ?>
<?php
$notifications_view->ShowMessage();
?>
<form name="fnotificationsview" id="fnotificationsview" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="notifications">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_notificationsview" class="ewTable">
<?php if ($notifications->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $notifications->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_notifications_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $notifications->id->CellAttributes() ?>><span id="el_notifications_id">
<span<?php echo $notifications->id->ViewAttributes() ?>>
<?php echo $notifications->id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($notifications->user_id->Visible) { // user_id ?>
	<tr id="r_user_id"<?php echo $notifications->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_notifications_user_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->user_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $notifications->user_id->CellAttributes() ?>><span id="el_notifications_user_id">
<span<?php echo $notifications->user_id->ViewAttributes() ?>>
<?php echo $notifications->user_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($notifications->action->Visible) { // action ?>
	<tr id="r_action"<?php echo $notifications->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_notifications_action"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->action->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $notifications->action->CellAttributes() ?>><span id="el_notifications_action">
<span<?php echo $notifications->action->ViewAttributes() ?>>
<?php echo $notifications->action->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($notifications->object_type->Visible) { // object_type ?>
	<tr id="r_object_type"<?php echo $notifications->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_notifications_object_type"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->object_type->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $notifications->object_type->CellAttributes() ?>><span id="el_notifications_object_type">
<span<?php echo $notifications->object_type->ViewAttributes() ?>>
<?php echo $notifications->object_type->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($notifications->object_id->Visible) { // object_id ?>
	<tr id="r_object_id"<?php echo $notifications->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_notifications_object_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->object_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $notifications->object_id->CellAttributes() ?>><span id="el_notifications_object_id">
<span<?php echo $notifications->object_id->ViewAttributes() ?>>
<?php echo $notifications->object_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($notifications->possessive->Visible) { // possessive ?>
	<tr id="r_possessive"<?php echo $notifications->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_notifications_possessive"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->possessive->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $notifications->possessive->CellAttributes() ?>><span id="el_notifications_possessive">
<span<?php echo $notifications->possessive->ViewAttributes() ?>>
<?php echo $notifications->possessive->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($notifications->from_user_id->Visible) { // from_user_id ?>
	<tr id="r_from_user_id"<?php echo $notifications->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_notifications_from_user_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->from_user_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $notifications->from_user_id->CellAttributes() ?>><span id="el_notifications_from_user_id">
<span<?php echo $notifications->from_user_id->ViewAttributes() ?>>
<?php echo $notifications->from_user_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($notifications->clicked->Visible) { // clicked ?>
	<tr id="r_clicked"<?php echo $notifications->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_notifications_clicked"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->clicked->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $notifications->clicked->CellAttributes() ?>><span id="el_notifications_clicked">
<span<?php echo $notifications->clicked->ViewAttributes() ?>>
<?php echo $notifications->clicked->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($notifications->relevant_id->Visible) { // relevant_id ?>
	<tr id="r_relevant_id"<?php echo $notifications->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_notifications_relevant_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->relevant_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $notifications->relevant_id->CellAttributes() ?>><span id="el_notifications_relevant_id">
<span<?php echo $notifications->relevant_id->ViewAttributes() ?>>
<?php echo $notifications->relevant_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($notifications->relevant_object->Visible) { // relevant_object ?>
	<tr id="r_relevant_object"<?php echo $notifications->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_notifications_relevant_object"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->relevant_object->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $notifications->relevant_object->CellAttributes() ?>><span id="el_notifications_relevant_object">
<span<?php echo $notifications->relevant_object->ViewAttributes() ?>>
<?php echo $notifications->relevant_object->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($notifications->app->Visible) { // app ?>
	<tr id="r_app"<?php echo $notifications->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_notifications_app"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->app->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $notifications->app->CellAttributes() ?>><span id="el_notifications_app">
<span<?php echo $notifications->app->ViewAttributes() ?>>
<?php echo $notifications->app->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($notifications->is_active->Visible) { // is_active ?>
	<tr id="r_is_active"<?php echo $notifications->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_notifications_is_active"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->is_active->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $notifications->is_active->CellAttributes() ?>><span id="el_notifications_is_active">
<span<?php echo $notifications->is_active->ViewAttributes() ?>>
<?php echo $notifications->is_active->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
</form>
<br>
<script type="text/javascript">
fnotificationsview.Init();
</script>
<?php
$notifications_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$notifications_view->Page_Terminate();
?>
