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

$notifications_add = NULL; // Initialize page object first

class cnotifications_add extends cnotifications {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{9095E487-4467-4C46-97C7-01D1A378652D}";

	// Table name
	var $TableName = 'notifications';

	// Page object name
	var $PageObjName = 'notifications_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
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
					$this->Page_Terminate("notificationslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "notificationsview.php")
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
		$this->user_id->CurrentValue = NULL;
		$this->user_id->OldValue = $this->user_id->CurrentValue;
		$this->action->CurrentValue = NULL;
		$this->action->OldValue = $this->action->CurrentValue;
		$this->object_type->CurrentValue = NULL;
		$this->object_type->OldValue = $this->object_type->CurrentValue;
		$this->object_id->CurrentValue = NULL;
		$this->object_id->OldValue = $this->object_id->CurrentValue;
		$this->possessive->CurrentValue = NULL;
		$this->possessive->OldValue = $this->possessive->CurrentValue;
		$this->from_user_id->CurrentValue = NULL;
		$this->from_user_id->OldValue = $this->from_user_id->CurrentValue;
		$this->clicked->CurrentValue = NULL;
		$this->clicked->OldValue = $this->clicked->CurrentValue;
		$this->relevant_id->CurrentValue = NULL;
		$this->relevant_id->OldValue = $this->relevant_id->CurrentValue;
		$this->relevant_object->CurrentValue = NULL;
		$this->relevant_object->OldValue = $this->relevant_object->CurrentValue;
		$this->app->CurrentValue = NULL;
		$this->app->OldValue = $this->app->CurrentValue;
		$this->is_active->CurrentValue = NULL;
		$this->is_active->OldValue = $this->is_active->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->user_id->FldIsDetailKey) {
			$this->user_id->setFormValue($objForm->GetValue("x_user_id"));
		}
		if (!$this->action->FldIsDetailKey) {
			$this->action->setFormValue($objForm->GetValue("x_action"));
		}
		if (!$this->object_type->FldIsDetailKey) {
			$this->object_type->setFormValue($objForm->GetValue("x_object_type"));
		}
		if (!$this->object_id->FldIsDetailKey) {
			$this->object_id->setFormValue($objForm->GetValue("x_object_id"));
		}
		if (!$this->possessive->FldIsDetailKey) {
			$this->possessive->setFormValue($objForm->GetValue("x_possessive"));
		}
		if (!$this->from_user_id->FldIsDetailKey) {
			$this->from_user_id->setFormValue($objForm->GetValue("x_from_user_id"));
		}
		if (!$this->clicked->FldIsDetailKey) {
			$this->clicked->setFormValue($objForm->GetValue("x_clicked"));
		}
		if (!$this->relevant_id->FldIsDetailKey) {
			$this->relevant_id->setFormValue($objForm->GetValue("x_relevant_id"));
		}
		if (!$this->relevant_object->FldIsDetailKey) {
			$this->relevant_object->setFormValue($objForm->GetValue("x_relevant_object"));
		}
		if (!$this->app->FldIsDetailKey) {
			$this->app->setFormValue($objForm->GetValue("x_app"));
		}
		if (!$this->is_active->FldIsDetailKey) {
			$this->is_active->setFormValue($objForm->GetValue("x_is_active"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->user_id->CurrentValue = $this->user_id->FormValue;
		$this->action->CurrentValue = $this->action->FormValue;
		$this->object_type->CurrentValue = $this->object_type->FormValue;
		$this->object_id->CurrentValue = $this->object_id->FormValue;
		$this->possessive->CurrentValue = $this->possessive->FormValue;
		$this->from_user_id->CurrentValue = $this->from_user_id->FormValue;
		$this->clicked->CurrentValue = $this->clicked->FormValue;
		$this->relevant_id->CurrentValue = $this->relevant_id->FormValue;
		$this->relevant_object->CurrentValue = $this->relevant_object->FormValue;
		$this->app->CurrentValue = $this->app->FormValue;
		$this->is_active->CurrentValue = $this->is_active->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// user_id
			$this->user_id->EditCustomAttributes = "";
			$this->user_id->EditValue = ew_HtmlEncode($this->user_id->CurrentValue);

			// action
			$this->action->EditCustomAttributes = "";
			$this->action->EditValue = ew_HtmlEncode($this->action->CurrentValue);

			// object_type
			$this->object_type->EditCustomAttributes = "";
			$this->object_type->EditValue = ew_HtmlEncode($this->object_type->CurrentValue);

			// object_id
			$this->object_id->EditCustomAttributes = "";
			$this->object_id->EditValue = ew_HtmlEncode($this->object_id->CurrentValue);

			// possessive
			$this->possessive->EditCustomAttributes = "";
			$this->possessive->EditValue = ew_HtmlEncode($this->possessive->CurrentValue);

			// from_user_id
			$this->from_user_id->EditCustomAttributes = "";
			$this->from_user_id->EditValue = ew_HtmlEncode($this->from_user_id->CurrentValue);

			// clicked
			$this->clicked->EditCustomAttributes = "";
			$this->clicked->EditValue = ew_HtmlEncode($this->clicked->CurrentValue);

			// relevant_id
			$this->relevant_id->EditCustomAttributes = "";
			$this->relevant_id->EditValue = ew_HtmlEncode($this->relevant_id->CurrentValue);

			// relevant_object
			$this->relevant_object->EditCustomAttributes = "";
			$this->relevant_object->EditValue = ew_HtmlEncode($this->relevant_object->CurrentValue);

			// app
			$this->app->EditCustomAttributes = "";
			$this->app->EditValue = ew_HtmlEncode($this->app->CurrentValue);

			// is_active
			$this->is_active->EditCustomAttributes = "";
			$this->is_active->EditValue = ew_HtmlEncode($this->is_active->CurrentValue);

			// Edit refer script
			// user_id

			$this->user_id->HrefValue = "";

			// action
			$this->action->HrefValue = "";

			// object_type
			$this->object_type->HrefValue = "";

			// object_id
			$this->object_id->HrefValue = "";

			// possessive
			$this->possessive->HrefValue = "";

			// from_user_id
			$this->from_user_id->HrefValue = "";

			// clicked
			$this->clicked->HrefValue = "";

			// relevant_id
			$this->relevant_id->HrefValue = "";

			// relevant_object
			$this->relevant_object->HrefValue = "";

			// app
			$this->app->HrefValue = "";

			// is_active
			$this->is_active->HrefValue = "";
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
		if (!ew_CheckInteger($this->user_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->user_id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->object_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->object_id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->possessive->FormValue)) {
			ew_AddMessage($gsFormError, $this->possessive->FldErrMsg());
		}
		if (!ew_CheckInteger($this->from_user_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->from_user_id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->clicked->FormValue)) {
			ew_AddMessage($gsFormError, $this->clicked->FldErrMsg());
		}
		if (!ew_CheckInteger($this->relevant_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->relevant_id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->relevant_object->FormValue)) {
			ew_AddMessage($gsFormError, $this->relevant_object->FldErrMsg());
		}
		if (!ew_CheckInteger($this->is_active->FormValue)) {
			ew_AddMessage($gsFormError, $this->is_active->FldErrMsg());
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

		// user_id
		$this->user_id->SetDbValueDef($rsnew, $this->user_id->CurrentValue, NULL, FALSE);

		// action
		$this->action->SetDbValueDef($rsnew, $this->action->CurrentValue, NULL, FALSE);

		// object_type
		$this->object_type->SetDbValueDef($rsnew, $this->object_type->CurrentValue, NULL, FALSE);

		// object_id
		$this->object_id->SetDbValueDef($rsnew, $this->object_id->CurrentValue, NULL, FALSE);

		// possessive
		$this->possessive->SetDbValueDef($rsnew, $this->possessive->CurrentValue, NULL, FALSE);

		// from_user_id
		$this->from_user_id->SetDbValueDef($rsnew, $this->from_user_id->CurrentValue, NULL, FALSE);

		// clicked
		$this->clicked->SetDbValueDef($rsnew, $this->clicked->CurrentValue, NULL, FALSE);

		// relevant_id
		$this->relevant_id->SetDbValueDef($rsnew, $this->relevant_id->CurrentValue, NULL, FALSE);

		// relevant_object
		$this->relevant_object->SetDbValueDef($rsnew, $this->relevant_object->CurrentValue, NULL, FALSE);

		// app
		$this->app->SetDbValueDef($rsnew, $this->app->CurrentValue, NULL, FALSE);

		// is_active
		$this->is_active->SetDbValueDef($rsnew, $this->is_active->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
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
			$this->id->setDbValue($conn->Insert_ID());
			$rsnew['id'] = $this->id->DbValue;
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
if (!isset($notifications_add)) $notifications_add = new cnotifications_add();

// Page init
$notifications_add->Page_Init();

// Page main
$notifications_add->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var notifications_add = new ew_Page("notifications_add");
notifications_add.PageID = "add"; // Page ID
var EW_PAGE_ID = notifications_add.PageID; // For backward compatibility

// Form object
var fnotificationsadd = new ew_Form("fnotificationsadd");

// Validate form
fnotificationsadd.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_user_id"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($notifications->user_id->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_object_id"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($notifications->object_id->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_possessive"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($notifications->possessive->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_from_user_id"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($notifications->from_user_id->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_clicked"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($notifications->clicked->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_relevant_id"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($notifications->relevant_id->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_relevant_object"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($notifications->relevant_object->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_is_active"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($notifications->is_active->FldErrMsg()) ?>");

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
fnotificationsadd.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fnotificationsadd.ValidateRequired = true;
<?php } else { ?>
fnotificationsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Add") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $notifications->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $notifications->getReturnUrl() ?>" id="a_GoBack" class="ewLink"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $notifications_add->ShowPageHeader(); ?>
<?php
$notifications_add->ShowMessage();
?>
<form name="fnotificationsadd" id="fnotificationsadd" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<br>
<input type="hidden" name="t" value="notifications">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_notificationsadd" class="ewTable">
<?php if ($notifications->user_id->Visible) { // user_id ?>
	<tr id="r_user_id"<?php echo $notifications->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_notifications_user_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->user_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $notifications->user_id->CellAttributes() ?>><span id="el_notifications_user_id">
<input type="text" name="x_user_id" id="x_user_id" size="30" value="<?php echo $notifications->user_id->EditValue ?>"<?php echo $notifications->user_id->EditAttributes() ?>>
</span><?php echo $notifications->user_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($notifications->action->Visible) { // action ?>
	<tr id="r_action"<?php echo $notifications->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_notifications_action"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->action->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $notifications->action->CellAttributes() ?>><span id="el_notifications_action">
<input type="text" name="x_action" id="x_action" size="30" maxlength="45" value="<?php echo $notifications->action->EditValue ?>"<?php echo $notifications->action->EditAttributes() ?>>
</span><?php echo $notifications->action->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($notifications->object_type->Visible) { // object_type ?>
	<tr id="r_object_type"<?php echo $notifications->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_notifications_object_type"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->object_type->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $notifications->object_type->CellAttributes() ?>><span id="el_notifications_object_type">
<input type="text" name="x_object_type" id="x_object_type" size="30" maxlength="45" value="<?php echo $notifications->object_type->EditValue ?>"<?php echo $notifications->object_type->EditAttributes() ?>>
</span><?php echo $notifications->object_type->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($notifications->object_id->Visible) { // object_id ?>
	<tr id="r_object_id"<?php echo $notifications->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_notifications_object_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->object_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $notifications->object_id->CellAttributes() ?>><span id="el_notifications_object_id">
<input type="text" name="x_object_id" id="x_object_id" size="30" value="<?php echo $notifications->object_id->EditValue ?>"<?php echo $notifications->object_id->EditAttributes() ?>>
</span><?php echo $notifications->object_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($notifications->possessive->Visible) { // possessive ?>
	<tr id="r_possessive"<?php echo $notifications->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_notifications_possessive"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->possessive->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $notifications->possessive->CellAttributes() ?>><span id="el_notifications_possessive">
<input type="text" name="x_possessive" id="x_possessive" size="30" value="<?php echo $notifications->possessive->EditValue ?>"<?php echo $notifications->possessive->EditAttributes() ?>>
</span><?php echo $notifications->possessive->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($notifications->from_user_id->Visible) { // from_user_id ?>
	<tr id="r_from_user_id"<?php echo $notifications->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_notifications_from_user_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->from_user_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $notifications->from_user_id->CellAttributes() ?>><span id="el_notifications_from_user_id">
<input type="text" name="x_from_user_id" id="x_from_user_id" size="30" value="<?php echo $notifications->from_user_id->EditValue ?>"<?php echo $notifications->from_user_id->EditAttributes() ?>>
</span><?php echo $notifications->from_user_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($notifications->clicked->Visible) { // clicked ?>
	<tr id="r_clicked"<?php echo $notifications->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_notifications_clicked"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->clicked->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $notifications->clicked->CellAttributes() ?>><span id="el_notifications_clicked">
<input type="text" name="x_clicked" id="x_clicked" size="30" value="<?php echo $notifications->clicked->EditValue ?>"<?php echo $notifications->clicked->EditAttributes() ?>>
</span><?php echo $notifications->clicked->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($notifications->relevant_id->Visible) { // relevant_id ?>
	<tr id="r_relevant_id"<?php echo $notifications->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_notifications_relevant_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->relevant_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $notifications->relevant_id->CellAttributes() ?>><span id="el_notifications_relevant_id">
<input type="text" name="x_relevant_id" id="x_relevant_id" size="30" value="<?php echo $notifications->relevant_id->EditValue ?>"<?php echo $notifications->relevant_id->EditAttributes() ?>>
</span><?php echo $notifications->relevant_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($notifications->relevant_object->Visible) { // relevant_object ?>
	<tr id="r_relevant_object"<?php echo $notifications->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_notifications_relevant_object"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->relevant_object->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $notifications->relevant_object->CellAttributes() ?>><span id="el_notifications_relevant_object">
<input type="text" name="x_relevant_object" id="x_relevant_object" size="30" value="<?php echo $notifications->relevant_object->EditValue ?>"<?php echo $notifications->relevant_object->EditAttributes() ?>>
</span><?php echo $notifications->relevant_object->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($notifications->app->Visible) { // app ?>
	<tr id="r_app"<?php echo $notifications->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_notifications_app"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->app->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $notifications->app->CellAttributes() ?>><span id="el_notifications_app">
<input type="text" name="x_app" id="x_app" size="30" maxlength="45" value="<?php echo $notifications->app->EditValue ?>"<?php echo $notifications->app->EditAttributes() ?>>
</span><?php echo $notifications->app->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($notifications->is_active->Visible) { // is_active ?>
	<tr id="r_is_active"<?php echo $notifications->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_notifications_is_active"><table class="ewTableHeaderBtn"><tr><td><?php echo $notifications->is_active->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $notifications->is_active->CellAttributes() ?>><span id="el_notifications_is_active">
<input type="text" name="x_is_active" id="x_is_active" size="30" value="<?php echo $notifications->is_active->EditValue ?>"<?php echo $notifications->is_active->EditAttributes() ?>>
</span><?php echo $notifications->is_active->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<br>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("AddBtn")) ?>">
</form>
<script type="text/javascript">
fnotificationsadd.Init();
</script>
<?php
$notifications_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$notifications_add->Page_Terminate();
?>
