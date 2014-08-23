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

$tbl_post_delete = NULL; // Initialize page object first

class ctbl_post_delete extends ctbl_post {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{9095E487-4467-4C46-97C7-01D1A378652D}";

	// Table name
	var $TableName = 'tbl_post';

	// Page object name
	var $PageObjName = 'tbl_post_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
			$this->Page_Terminate("tbl_postlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in tbl_post class, tbl_postinfo.php

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
				$sThisKey .= $row['post_id'];
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
if (!isset($tbl_post_delete)) $tbl_post_delete = new ctbl_post_delete();

// Page init
$tbl_post_delete->Page_Init();

// Page main
$tbl_post_delete->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tbl_post_delete = new ew_Page("tbl_post_delete");
tbl_post_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = tbl_post_delete.PageID; // For backward compatibility

// Form object
var ftbl_postdelete = new ew_Form("ftbl_postdelete");

// Form_CustomValidate event
ftbl_postdelete.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbl_postdelete.ValidateRequired = true;
<?php } else { ?>
ftbl_postdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($tbl_post_delete->Recordset = $tbl_post_delete->LoadRecordset())
	$tbl_post_deleteTotalRecs = $tbl_post_delete->Recordset->RecordCount(); // Get record count
if ($tbl_post_deleteTotalRecs <= 0) { // No record found, exit
	if ($tbl_post_delete->Recordset)
		$tbl_post_delete->Recordset->Close();
	$tbl_post_delete->Page_Terminate("tbl_postlist.php"); // Return to list
}
?>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Delete") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $tbl_post->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $tbl_post->getReturnUrl() ?>" id="a_GoBack" class="ewLink"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $tbl_post_delete->ShowPageHeader(); ?>
<?php
$tbl_post_delete->ShowMessage();
?>
<form name="ftbl_postdelete" id="ftbl_postdelete" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<br>
<input type="hidden" name="t" value="tbl_post">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($tbl_post_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_tbl_postdelete" class="ewTable ewTableSeparate">
<?php echo $tbl_post->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_tbl_post_post_id" class="tbl_post_post_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_id->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_tbl_post_post_author" class="tbl_post_post_author"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_author->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_tbl_post_post_date" class="tbl_post_post_date"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_date->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_tbl_post_post_title" class="tbl_post_post_title"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_title->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_tbl_post_post_active" class="tbl_post_post_active"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_active->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_tbl_post_post_rate" class="tbl_post_post_rate"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_rate->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_tbl_post_post_type" class="tbl_post_post_type"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_type->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_tbl_post_post_class" class="tbl_post_post_class"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_class->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_tbl_post_post_group" class="tbl_post_post_group"><table class="ewTableHeaderBtn"><tr><td><?php echo $tbl_post->post_group->FldCaption() ?></td></tr></table></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$tbl_post_delete->RecCnt = 0;
$i = 0;
while (!$tbl_post_delete->Recordset->EOF) {
	$tbl_post_delete->RecCnt++;
	$tbl_post_delete->RowCnt++;

	// Set row properties
	$tbl_post->ResetAttrs();
	$tbl_post->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$tbl_post_delete->LoadRowValues($tbl_post_delete->Recordset);

	// Render row
	$tbl_post_delete->RenderRow();
?>
	<tr<?php echo $tbl_post->RowAttributes() ?>>
		<td<?php echo $tbl_post->post_id->CellAttributes() ?>><span id="el<?php echo $tbl_post_delete->RowCnt ?>_tbl_post_post_id" class="tbl_post_post_id">
<span<?php echo $tbl_post->post_id->ViewAttributes() ?>>
<?php echo $tbl_post->post_id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $tbl_post->post_author->CellAttributes() ?>><span id="el<?php echo $tbl_post_delete->RowCnt ?>_tbl_post_post_author" class="tbl_post_post_author">
<span<?php echo $tbl_post->post_author->ViewAttributes() ?>>
<?php echo $tbl_post->post_author->ListViewValue() ?></span>
</span></td>
		<td<?php echo $tbl_post->post_date->CellAttributes() ?>><span id="el<?php echo $tbl_post_delete->RowCnt ?>_tbl_post_post_date" class="tbl_post_post_date">
<span<?php echo $tbl_post->post_date->ViewAttributes() ?>>
<?php echo $tbl_post->post_date->ListViewValue() ?></span>
</span></td>
		<td<?php echo $tbl_post->post_title->CellAttributes() ?>><span id="el<?php echo $tbl_post_delete->RowCnt ?>_tbl_post_post_title" class="tbl_post_post_title">
<span<?php echo $tbl_post->post_title->ViewAttributes() ?>>
<?php echo $tbl_post->post_title->ListViewValue() ?></span>
</span></td>
		<td<?php echo $tbl_post->post_active->CellAttributes() ?>><span id="el<?php echo $tbl_post_delete->RowCnt ?>_tbl_post_post_active" class="tbl_post_post_active">
<span<?php echo $tbl_post->post_active->ViewAttributes() ?>>
<?php echo $tbl_post->post_active->ListViewValue() ?></span>
</span></td>
		<td<?php echo $tbl_post->post_rate->CellAttributes() ?>><span id="el<?php echo $tbl_post_delete->RowCnt ?>_tbl_post_post_rate" class="tbl_post_post_rate">
<span<?php echo $tbl_post->post_rate->ViewAttributes() ?>>
<?php echo $tbl_post->post_rate->ListViewValue() ?></span>
</span></td>
		<td<?php echo $tbl_post->post_type->CellAttributes() ?>><span id="el<?php echo $tbl_post_delete->RowCnt ?>_tbl_post_post_type" class="tbl_post_post_type">
<span<?php echo $tbl_post->post_type->ViewAttributes() ?>>
<?php echo $tbl_post->post_type->ListViewValue() ?></span>
</span></td>
		<td<?php echo $tbl_post->post_class->CellAttributes() ?>><span id="el<?php echo $tbl_post_delete->RowCnt ?>_tbl_post_post_class" class="tbl_post_post_class">
<span<?php echo $tbl_post->post_class->ViewAttributes() ?>>
<?php echo $tbl_post->post_class->ListViewValue() ?></span>
</span></td>
		<td<?php echo $tbl_post->post_group->CellAttributes() ?>><span id="el<?php echo $tbl_post_delete->RowCnt ?>_tbl_post_post_group" class="tbl_post_post_group">
<span<?php echo $tbl_post->post_group->ViewAttributes() ?>>
<?php echo $tbl_post->post_group->ListViewValue() ?></span>
</span></td>
	</tr>
<?php
	$tbl_post_delete->Recordset->MoveNext();
}
$tbl_post_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<br>
<input type="submit" name="Action" value="<?php echo ew_BtnCaption($Language->Phrase("DeleteBtn")) ?>">
</form>
<script type="text/javascript">
ftbl_postdelete.Init();
</script>
<?php
$tbl_post_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbl_post_delete->Page_Terminate();
?>
