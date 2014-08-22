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

$notifications_delete = NULL; // Initialize page object first

class cnotifications_delete extends cnotifications {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{9095E487-4467-4C46-97C7-01D1A378652D}";

	// Table name
	var $TableName = 'notifications';

	// Page object name
	var $PageObjName = 'notifications_delete';

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

		// Table object (notifications)
		if (!isset($GLOBALS["notifications"])) {
			$GLOBALS["notifications"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["notifications"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'notifications', TRUE);

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
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("notificationslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in notifications class, notificationsinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Call Recordset Selecting event
		$this->Recordset_Selecting($this->CurrentFilter);

		// Load List page SQL
		$sSql = $this->SelectSQL();
		if ($offset > -1 && $rowcnt > -1)
			$sSql .= " LIMIT $rowcnt OFFSET $offset";

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;
		} else {
			$this->LoadRowValues($rs); // Load row values
		}
		$conn->BeginTrans();

		// Clone old rows
		$rsold = ($rs) ? $rs->GetRows() : array();
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id'];
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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
if (!isset($notifications_delete)) $notifications_delete = new cnotifications_delete();

// Page init
$notifications_delete->Page_Init();

// Page main
$notifications_delete->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var notifications_delete = new ew_Page("notifications_delete");
notifications_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = notifications_delete.PageID; // For backward compatibility

// Form object
var fnotificationsdelete = new ew_Form("fnotificationsdelete");

// Form_CustomValidate event
fnotificationsdelete.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fnotificationsdelete.ValidateRequired = true;
<?php } else { ?>
fnotificationsdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($notifications_delete->Recordset = $notifications_delete->LoadRecordset())
	$notifications_deleteTotalRecs = $notifications_delete->Recordset->RecordCount(); // Get record count
if ($notifications_deleteTotalRecs <= 0) { // No record found, exit
	if ($notifications_delete->Recordset)
		$notifications_delete->Recordset->Close();
	$notifications_delete->Page_Terminate("notificationslist.php"); // Return to list
}
?>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Delete") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $notifications->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $notifications->getReturnUrl() ?>" id="a_GoBack" class="ewLink"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $notifications_delete->ShowPageHeader(); ?>
<?php
$notifications_delete->ShowMessage();
?>
<form name="fnotificationsdelete" id="fnotificationsdelete" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<br>
<input type="hidden" name="t" value="notifications">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($notifications_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_notificationsdelete" class="ewTable ewTableSeparate">
<?php echo $notifications->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_notifications_id" class="notifications_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->id->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_notifications_user_id" class="notifications_user_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->user_id->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_notifications_action" class="notifications_action"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->action->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_notifications_object_type" class="notifications_object_type"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->object_type->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_notifications_object_id" class="notifications_object_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->object_id->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_notifications_possessive" class="notifications_possessive"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->possessive->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_notifications_from_user_id" class="notifications_from_user_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->from_user_id->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_notifications_clicked" class="notifications_clicked"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->clicked->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_notifications_relevant_id" class="notifications_relevant_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->relevant_id->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_notifications_relevant_object" class="notifications_relevant_object"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->relevant_object->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_notifications_app" class="notifications_app"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->app->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_notifications_is_active" class="notifications_is_active"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->is_active->FldCaption() ?></td></tr></table></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$notifications_delete->RecCnt = 0;
$i = 0;
while (!$notifications_delete->Recordset->EOF) {
	$notifications_delete->RecCnt++;
	$notifications_delete->RowCnt++;

	// Set row properties
	$notifications->ResetAttrs();
	$notifications->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$notifications_delete->LoadRowValues($notifications_delete->Recordset);

	// Render row
	$notifications_delete->RenderRow();
?>
	<tr<?php echo $notifications->RowAttributes() ?>>
		<td<?php echo $notifications->id->CellAttributes() ?>><span id="el<?php echo $notifications_delete->RowCnt ?>_notifications_id" class="notifications_id">
<span<?php echo $notifications->id->ViewAttributes() ?>>
<?php echo $notifications->id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $notifications->user_id->CellAttributes() ?>><span id="el<?php echo $notifications_delete->RowCnt ?>_notifications_user_id" class="notifications_user_id">
<span<?php echo $notifications->user_id->ViewAttributes() ?>>
<?php echo $notifications->user_id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $notifications->action->CellAttributes() ?>><span id="el<?php echo $notifications_delete->RowCnt ?>_notifications_action" class="notifications_action">
<span<?php echo $notifications->action->ViewAttributes() ?>>
<?php echo $notifications->action->ListViewValue() ?></span>
</span></td>
		<td<?php echo $notifications->object_type->CellAttributes() ?>><span id="el<?php echo $notifications_delete->RowCnt ?>_notifications_object_type" class="notifications_object_type">
<span<?php echo $notifications->object_type->ViewAttributes() ?>>
<?php echo $notifications->object_type->ListViewValue() ?></span>
</span></td>
		<td<?php echo $notifications->object_id->CellAttributes() ?>><span id="el<?php echo $notifications_delete->RowCnt ?>_notifications_object_id" class="notifications_object_id">
<span<?php echo $notifications->object_id->ViewAttributes() ?>>
<?php echo $notifications->object_id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $notifications->possessive->CellAttributes() ?>><span id="el<?php echo $notifications_delete->RowCnt ?>_notifications_possessive" class="notifications_possessive">
<span<?php echo $notifications->possessive->ViewAttributes() ?>>
<?php echo $notifications->possessive->ListViewValue() ?></span>
</span></td>
		<td<?php echo $notifications->from_user_id->CellAttributes() ?>><span id="el<?php echo $notifications_delete->RowCnt ?>_notifications_from_user_id" class="notifications_from_user_id">
<span<?php echo $notifications->from_user_id->ViewAttributes() ?>>
<?php echo $notifications->from_user_id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $notifications->clicked->CellAttributes() ?>><span id="el<?php echo $notifications_delete->RowCnt ?>_notifications_clicked" class="notifications_clicked">
<span<?php echo $notifications->clicked->ViewAttributes() ?>>
<?php echo $notifications->clicked->ListViewValue() ?></span>
</span></td>
		<td<?php echo $notifications->relevant_id->CellAttributes() ?>><span id="el<?php echo $notifications_delete->RowCnt ?>_notifications_relevant_id" class="notifications_relevant_id">
<span<?php echo $notifications->relevant_id->ViewAttributes() ?>>
<?php echo $notifications->relevant_id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $notifications->relevant_object->CellAttributes() ?>><span id="el<?php echo $notifications_delete->RowCnt ?>_notifications_relevant_object" class="notifications_relevant_object">
<span<?php echo $notifications->relevant_object->ViewAttributes() ?>>
<?php echo $notifications->relevant_object->ListViewValue() ?></span>
</span></td>
		<td<?php echo $notifications->app->CellAttributes() ?>><span id="el<?php echo $notifications_delete->RowCnt ?>_notifications_app" class="notifications_app">
<span<?php echo $notifications->app->ViewAttributes() ?>>
<?php echo $notifications->app->ListViewValue() ?></span>
</span></td>
		<td<?php echo $notifications->is_active->CellAttributes() ?>><span id="el<?php echo $notifications_delete->RowCnt ?>_notifications_is_active" class="notifications_is_active">
<span<?php echo $notifications->is_active->ViewAttributes() ?>>
<?php echo $notifications->is_active->ListViewValue() ?></span>
</span></td>
	</tr>
<?php
	$notifications_delete->Recordset->MoveNext();
}
$notifications_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<br>
<input type="submit" name="Action" value="<?php echo ew_BtnCaption($Language->Phrase("DeleteBtn")) ?>">
</form>
<script type="text/javascript">
fnotificationsdelete.Init();
</script>
<?php
$notifications_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$notifications_delete->Page_Terminate();
?>
