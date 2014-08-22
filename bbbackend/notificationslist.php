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

$notifications_list = NULL; // Initialize page object first

class cnotifications_list extends cnotifications {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{9095E487-4467-4C46-97C7-01D1A378652D}";

	// Table name
	var $TableName = 'notifications';

	// Page object name
	var $PageObjName = 'notifications_list';

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "notificationsadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "notificationsdelete.php";
		$this->MultiUpdateUrl = "notificationsupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'notifications', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

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

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Handle reset command
			$this->ResetCmd();

			// Hide all options
			if ($this->Export <> "" ||
				$this->CurrentAction == "gridadd" ||
				$this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ExportOptions->HideAllOptions();
			}

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Restore search parms from Session if not searching / reset
			if ($this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall")
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search") {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue("k_key"));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue("k_key"));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->action, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->object_type, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->app, $Keyword);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $Keyword) {
		if ($Keyword == EW_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NULL";
		} elseif ($Keyword == EW_NOT_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NOT NULL";
		} else {
			$sFldExpression = ($Fld->FldVirtualExpression <> $Fld->FldExpression) ? $Fld->FldVirtualExpression : $Fld->FldBasicSearchExpression;
			$sWrk = $sFldExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING));
		}
		if ($Where <> "") $Where .= " OR ";
		$Where .= $sWrk;
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere() {
		global $Security;
		$sSearchStr = "";
		$sSearchKeyword = $this->BasicSearch->Keyword;
		$sSearchType = $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				while (strpos($sSearch, "  ") !== FALSE)
					$sSearch = str_replace("  ", " ", $sSearch);
				$arKeyword = explode(" ", trim($sSearch));
				foreach ($arKeyword as $sKeyword) {
					if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
					$sSearchStr .= "(" . $this->BasicSearchSQL($sKeyword) . ")";
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($sSearch);
			}
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id); // id
			$this->UpdateSort($this->user_id); // user_id
			$this->UpdateSort($this->action); // action
			$this->UpdateSort($this->object_type); // object_type
			$this->UpdateSort($this->object_id); // object_id
			$this->UpdateSort($this->possessive); // possessive
			$this->UpdateSort($this->from_user_id); // from_user_id
			$this->UpdateSort($this->clicked); // clicked
			$this->UpdateSort($this->relevant_id); // relevant_id
			$this->UpdateSort($this->relevant_object); // relevant_object
			$this->UpdateSort($this->app); // app
			$this->UpdateSort($this->is_active); // is_active
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->SqlOrderBy() <> "") {
				$sOrderBy = $this->SqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// cmd=reset (Reset search parameters)
	// cmd=resetall (Reset search and master/detail parameters)
	// cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->id->setSort("");
				$this->user_id->setSort("");
				$this->action->setSort("");
				$this->object_type->setSort("");
				$this->object_id->setSort("");
				$this->possessive->setSort("");
				$this->from_user_id->setSort("");
				$this->clicked->setSort("");
				$this->relevant_id->setSort("");
				$this->relevant_object->setSort("");
				$this->app->setSort("");
				$this->is_active->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->IsLoggedIn();
		$item->OnLeft = FALSE;

		// Call ListOptions_Load event
		$this->ListOptions_Load();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->IsLoggedIn())
			$oListOpt->Body = "<a class=\"ewRowLink\" href=\"" . $this->ViewUrl . "\">" . $Language->Phrase("ViewLink") . "</a>";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = "<a class=\"ewRowLink\" href=\"" . $this->EditUrl . "\">" . $Language->Phrase("EditLink") . "</a>";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if ($Security->IsLoggedIn()) {
			$oListOpt->Body = "<a class=\"ewRowLink\" href=\"" . $this->CopyUrl . "\">" . $Language->Phrase("CopyLink") . "</a>";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->IsLoggedIn())
			$oListOpt->Body = "<a class=\"ewRowLink\"" . "" . " href=\"" . $this->DeleteUrl . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($notifications_list)) $notifications_list = new cnotifications_list();

// Page init
$notifications_list->Page_Init();

// Page main
$notifications_list->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var notifications_list = new ew_Page("notifications_list");
notifications_list.PageID = "list"; // Page ID
var EW_PAGE_ID = notifications_list.PageID; // For backward compatibility

// Form object
var fnotificationslist = new ew_Form("fnotificationslist");

// Form_CustomValidate event
fnotificationslist.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fnotificationslist.ValidateRequired = true;
<?php } else { ?>
fnotificationslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fnotificationslistsrch = new ew_Form("fnotificationslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$notifications_list->TotalRecs = $notifications->SelectRecordCount();
	} else {
		if ($notifications_list->Recordset = $notifications_list->LoadRecordset())
			$notifications_list->TotalRecs = $notifications_list->Recordset->RecordCount();
	}
	$notifications_list->StartRec = 1;
	if ($notifications_list->DisplayRecs <= 0 || ($notifications->Export <> "" && $notifications->ExportAll)) // Display all records
		$notifications_list->DisplayRecs = $notifications_list->TotalRecs;
	if (!($notifications->Export <> "" && $notifications->ExportAll))
		$notifications_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$notifications_list->Recordset = $notifications_list->LoadRecordset($notifications_list->StartRec-1, $notifications_list->DisplayRecs);
?>
<p style="white-space: nowrap;"><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $notifications->TableCaption() ?>&nbsp;&nbsp;</span>
<?php $notifications_list->ExportOptions->Render("body"); ?>
</p>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($notifications->Export == "" && $notifications->CurrentAction == "") { ?>
<form name="fnotificationslistsrch" id="fnotificationslistsrch" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<a href="javascript:fnotificationslistsrch.ToggleSearchPanel();" style="text-decoration: none;"><img id="fnotificationslistsrch_SearchImage" src="phpimages/collapse.gif" alt="" width="9" height="9" style="border: 0;"></a><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("Search") ?></span><br>
<div id="fnotificationslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="notifications">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" size="20" value="<?php echo ew_HtmlEncode($notifications_list->BasicSearch->getKeyword()) ?>">
	<input type="submit" name="btnsubmit" id="btnsubmit" value="<?php echo ew_BtnCaption($Language->Phrase("QuickSearchBtn")) ?>">&nbsp;
	<a href="<?php echo $notifications_list->PageUrl() ?>cmd=reset" id="a_ShowAll" class="ewLink"><?php echo $Language->Phrase("ShowAll") ?></a>&nbsp;
</div>
<div id="xsr_2" class="ewRow">
	<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($notifications_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($notifications_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($notifications_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
</div>
</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $notifications_list->ShowPageHeader(); ?>
<?php
$notifications_list->ShowMessage();
?>
<br>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fnotificationslist" id="fnotificationslist" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="notifications">
<div id="gmp_notifications" class="ewGridMiddlePanel">
<?php if ($notifications_list->TotalRecs > 0) { ?>
<table id="tbl_notificationslist" class="ewTable ewTableSeparate">
<?php echo $notifications->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$notifications_list->RenderListOptions();

// Render list options (header, left)
$notifications_list->ListOptions->Render("header", "left");
?>
<?php if ($notifications->id->Visible) { // id ?>
	<?php if ($notifications->SortUrl($notifications->id) == "") { ?>
		<td><span id="elh_notifications_id" class="notifications_id"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $notifications->id->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $notifications->SortUrl($notifications->id) ?>',1);"><span id="elh_notifications_id" class="notifications_id">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $notifications->id->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($notifications->id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($notifications->id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($notifications->user_id->Visible) { // user_id ?>
	<?php if ($notifications->SortUrl($notifications->user_id) == "") { ?>
		<td><span id="elh_notifications_user_id" class="notifications_user_id"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $notifications->user_id->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $notifications->SortUrl($notifications->user_id) ?>',1);"><span id="elh_notifications_user_id" class="notifications_user_id">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $notifications->user_id->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($notifications->user_id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($notifications->user_id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($notifications->action->Visible) { // action ?>
	<?php if ($notifications->SortUrl($notifications->action) == "") { ?>
		<td><span id="elh_notifications_action" class="notifications_action"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $notifications->action->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $notifications->SortUrl($notifications->action) ?>',1);"><span id="elh_notifications_action" class="notifications_action">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $notifications->action->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td class="ewTableHeaderSort"><?php if ($notifications->action->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($notifications->action->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($notifications->object_type->Visible) { // object_type ?>
	<?php if ($notifications->SortUrl($notifications->object_type) == "") { ?>
		<td><span id="elh_notifications_object_type" class="notifications_object_type"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $notifications->object_type->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $notifications->SortUrl($notifications->object_type) ?>',1);"><span id="elh_notifications_object_type" class="notifications_object_type">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $notifications->object_type->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td class="ewTableHeaderSort"><?php if ($notifications->object_type->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($notifications->object_type->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($notifications->object_id->Visible) { // object_id ?>
	<?php if ($notifications->SortUrl($notifications->object_id) == "") { ?>
		<td><span id="elh_notifications_object_id" class="notifications_object_id"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $notifications->object_id->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $notifications->SortUrl($notifications->object_id) ?>',1);"><span id="elh_notifications_object_id" class="notifications_object_id">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $notifications->object_id->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($notifications->object_id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($notifications->object_id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($notifications->possessive->Visible) { // possessive ?>
	<?php if ($notifications->SortUrl($notifications->possessive) == "") { ?>
		<td><span id="elh_notifications_possessive" class="notifications_possessive"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $notifications->possessive->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $notifications->SortUrl($notifications->possessive) ?>',1);"><span id="elh_notifications_possessive" class="notifications_possessive">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $notifications->possessive->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($notifications->possessive->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($notifications->possessive->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($notifications->from_user_id->Visible) { // from_user_id ?>
	<?php if ($notifications->SortUrl($notifications->from_user_id) == "") { ?>
		<td><span id="elh_notifications_from_user_id" class="notifications_from_user_id"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $notifications->from_user_id->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $notifications->SortUrl($notifications->from_user_id) ?>',1);"><span id="elh_notifications_from_user_id" class="notifications_from_user_id">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $notifications->from_user_id->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($notifications->from_user_id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($notifications->from_user_id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($notifications->clicked->Visible) { // clicked ?>
	<?php if ($notifications->SortUrl($notifications->clicked) == "") { ?>
		<td><span id="elh_notifications_clicked" class="notifications_clicked"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $notifications->clicked->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $notifications->SortUrl($notifications->clicked) ?>',1);"><span id="elh_notifications_clicked" class="notifications_clicked">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $notifications->clicked->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($notifications->clicked->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($notifications->clicked->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($notifications->relevant_id->Visible) { // relevant_id ?>
	<?php if ($notifications->SortUrl($notifications->relevant_id) == "") { ?>
		<td><span id="elh_notifications_relevant_id" class="notifications_relevant_id"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $notifications->relevant_id->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $notifications->SortUrl($notifications->relevant_id) ?>',1);"><span id="elh_notifications_relevant_id" class="notifications_relevant_id">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $notifications->relevant_id->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($notifications->relevant_id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($notifications->relevant_id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($notifications->relevant_object->Visible) { // relevant_object ?>
	<?php if ($notifications->SortUrl($notifications->relevant_object) == "") { ?>
		<td><span id="elh_notifications_relevant_object" class="notifications_relevant_object"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $notifications->relevant_object->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $notifications->SortUrl($notifications->relevant_object) ?>',1);"><span id="elh_notifications_relevant_object" class="notifications_relevant_object">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $notifications->relevant_object->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($notifications->relevant_object->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($notifications->relevant_object->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($notifications->app->Visible) { // app ?>
	<?php if ($notifications->SortUrl($notifications->app) == "") { ?>
		<td><span id="elh_notifications_app" class="notifications_app"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $notifications->app->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $notifications->SortUrl($notifications->app) ?>',1);"><span id="elh_notifications_app" class="notifications_app">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $notifications->app->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td class="ewTableHeaderSort"><?php if ($notifications->app->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($notifications->app->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($notifications->is_active->Visible) { // is_active ?>
	<?php if ($notifications->SortUrl($notifications->is_active) == "") { ?>
		<td><span id="elh_notifications_is_active" class="notifications_is_active"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $notifications->is_active->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $notifications->SortUrl($notifications->is_active) ?>',1);"><span id="elh_notifications_is_active" class="notifications_is_active">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $notifications->is_active->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($notifications->is_active->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($notifications->is_active->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$notifications_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($notifications->ExportAll && $notifications->Export <> "") {
	$notifications_list->StopRec = $notifications_list->TotalRecs;
} else {

	// Set the last record to display
	if ($notifications_list->TotalRecs > $notifications_list->StartRec + $notifications_list->DisplayRecs - 1)
		$notifications_list->StopRec = $notifications_list->StartRec + $notifications_list->DisplayRecs - 1;
	else
		$notifications_list->StopRec = $notifications_list->TotalRecs;
}
$notifications_list->RecCnt = $notifications_list->StartRec - 1;
if ($notifications_list->Recordset && !$notifications_list->Recordset->EOF) {
	$notifications_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $notifications_list->StartRec > 1)
		$notifications_list->Recordset->Move($notifications_list->StartRec - 1);
} elseif (!$notifications->AllowAddDeleteRow && $notifications_list->StopRec == 0) {
	$notifications_list->StopRec = $notifications->GridAddRowCount;
}

// Initialize aggregate
$notifications->RowType = EW_ROWTYPE_AGGREGATEINIT;
$notifications->ResetAttrs();
$notifications_list->RenderRow();
while ($notifications_list->RecCnt < $notifications_list->StopRec) {
	$notifications_list->RecCnt++;
	if (intval($notifications_list->RecCnt) >= intval($notifications_list->StartRec)) {
		$notifications_list->RowCnt++;

		// Set up key count
		$notifications_list->KeyCount = $notifications_list->RowIndex;

		// Init row class and style
		$notifications->ResetAttrs();
		$notifications->CssClass = "";
		if ($notifications->CurrentAction == "gridadd") {
		} else {
			$notifications_list->LoadRowValues($notifications_list->Recordset); // Load row values
		}
		$notifications->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$notifications->RowAttrs = array_merge($notifications->RowAttrs, array('data-rowindex'=>$notifications_list->RowCnt, 'id'=>'r' . $notifications_list->RowCnt . '_notifications', 'data-rowtype'=>$notifications->RowType));

		// Render row
		$notifications_list->RenderRow();

		// Render list options
		$notifications_list->RenderListOptions();
?>
	<tr<?php echo $notifications->RowAttributes() ?>>
<?php

// Render list options (body, left)
$notifications_list->ListOptions->Render("body", "left", $notifications_list->RowCnt);
?>
	<?php if ($notifications->id->Visible) { // id ?>
		<td<?php echo $notifications->id->CellAttributes() ?>><span id="el<?php echo $notifications_list->RowCnt ?>_notifications_id" class="notifications_id">
<span<?php echo $notifications->id->ViewAttributes() ?>>
<?php echo $notifications->id->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<a id="<?php echo $notifications_list->PageObjName . "_row_" . $notifications_list->RowCnt ?>"></a>
	<?php if ($notifications->user_id->Visible) { // user_id ?>
		<td<?php echo $notifications->user_id->CellAttributes() ?>><span id="el<?php echo $notifications_list->RowCnt ?>_notifications_user_id" class="notifications_user_id">
<span<?php echo $notifications->user_id->ViewAttributes() ?>>
<?php echo $notifications->user_id->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($notifications->action->Visible) { // action ?>
		<td<?php echo $notifications->action->CellAttributes() ?>><span id="el<?php echo $notifications_list->RowCnt ?>_notifications_action" class="notifications_action">
<span<?php echo $notifications->action->ViewAttributes() ?>>
<?php echo $notifications->action->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($notifications->object_type->Visible) { // object_type ?>
		<td<?php echo $notifications->object_type->CellAttributes() ?>><span id="el<?php echo $notifications_list->RowCnt ?>_notifications_object_type" class="notifications_object_type">
<span<?php echo $notifications->object_type->ViewAttributes() ?>>
<?php echo $notifications->object_type->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($notifications->object_id->Visible) { // object_id ?>
		<td<?php echo $notifications->object_id->CellAttributes() ?>><span id="el<?php echo $notifications_list->RowCnt ?>_notifications_object_id" class="notifications_object_id">
<span<?php echo $notifications->object_id->ViewAttributes() ?>>
<?php echo $notifications->object_id->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($notifications->possessive->Visible) { // possessive ?>
		<td<?php echo $notifications->possessive->CellAttributes() ?>><span id="el<?php echo $notifications_list->RowCnt ?>_notifications_possessive" class="notifications_possessive">
<span<?php echo $notifications->possessive->ViewAttributes() ?>>
<?php echo $notifications->possessive->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($notifications->from_user_id->Visible) { // from_user_id ?>
		<td<?php echo $notifications->from_user_id->CellAttributes() ?>><span id="el<?php echo $notifications_list->RowCnt ?>_notifications_from_user_id" class="notifications_from_user_id">
<span<?php echo $notifications->from_user_id->ViewAttributes() ?>>
<?php echo $notifications->from_user_id->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($notifications->clicked->Visible) { // clicked ?>
		<td<?php echo $notifications->clicked->CellAttributes() ?>><span id="el<?php echo $notifications_list->RowCnt ?>_notifications_clicked" class="notifications_clicked">
<span<?php echo $notifications->clicked->ViewAttributes() ?>>
<?php echo $notifications->clicked->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($notifications->relevant_id->Visible) { // relevant_id ?>
		<td<?php echo $notifications->relevant_id->CellAttributes() ?>><span id="el<?php echo $notifications_list->RowCnt ?>_notifications_relevant_id" class="notifications_relevant_id">
<span<?php echo $notifications->relevant_id->ViewAttributes() ?>>
<?php echo $notifications->relevant_id->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($notifications->relevant_object->Visible) { // relevant_object ?>
		<td<?php echo $notifications->relevant_object->CellAttributes() ?>><span id="el<?php echo $notifications_list->RowCnt ?>_notifications_relevant_object" class="notifications_relevant_object">
<span<?php echo $notifications->relevant_object->ViewAttributes() ?>>
<?php echo $notifications->relevant_object->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($notifications->app->Visible) { // app ?>
		<td<?php echo $notifications->app->CellAttributes() ?>><span id="el<?php echo $notifications_list->RowCnt ?>_notifications_app" class="notifications_app">
<span<?php echo $notifications->app->ViewAttributes() ?>>
<?php echo $notifications->app->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($notifications->is_active->Visible) { // is_active ?>
		<td<?php echo $notifications->is_active->CellAttributes() ?>><span id="el<?php echo $notifications_list->RowCnt ?>_notifications_is_active" class="notifications_is_active">
<span<?php echo $notifications->is_active->ViewAttributes() ?>>
<?php echo $notifications->is_active->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$notifications_list->ListOptions->Render("body", "right", $notifications_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($notifications->CurrentAction <> "gridadd")
		$notifications_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($notifications->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($notifications_list->Recordset)
	$notifications_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($notifications->CurrentAction <> "gridadd" && $notifications->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager"><tr><td>
<?php if (!isset($notifications_list->Pager)) $notifications_list->Pager = new cPrevNextPager($notifications_list->StartRec, $notifications_list->DisplayRecs, $notifications_list->TotalRecs) ?>
<?php if ($notifications_list->Pager->RecordCount > 0) { ?>
	<table cellspacing="0" class="ewStdTable"><tbody><tr><td><span class="phpmaker"><?php echo $Language->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($notifications_list->Pager->FirstButton->Enabled) { ?>
	<td><a href="<?php echo $notifications_list->PageUrl() ?>start=<?php echo $notifications_list->Pager->FirstButton->Start ?>"><img src="phpimages/first.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" style="border: 0;"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/firstdisab.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($notifications_list->Pager->PrevButton->Enabled) { ?>
	<td><a href="<?php echo $notifications_list->PageUrl() ?>start=<?php echo $notifications_list->Pager->PrevButton->Start ?>"><img src="phpimages/prev.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" style="border: 0;"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/prevdisab.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" id="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $notifications_list->Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($notifications_list->Pager->NextButton->Enabled) { ?>
	<td><a href="<?php echo $notifications_list->PageUrl() ?>start=<?php echo $notifications_list->Pager->NextButton->Start ?>"><img src="phpimages/next.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" style="border: 0;"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/nextdisab.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
<!--last page button-->
	<?php if ($notifications_list->Pager->LastButton->Enabled) { ?>
	<td><a href="<?php echo $notifications_list->PageUrl() ?>start=<?php echo $notifications_list->Pager->LastButton->Start ?>"><img src="phpimages/last.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" style="border: 0;"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/lastdisab.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
	<td><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $notifications_list->Pager->PageCount ?></span></td>
	</tr></tbody></table>
	</td>	
	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td>
	<span class="phpmaker"><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $notifications_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $notifications_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $notifications_list->Pager->RecordCount ?></span>
<?php } else { ?>
	<?php if ($notifications_list->SearchWhere == "0=101") { ?>
	<span class="phpmaker"><?php echo $Language->Phrase("EnterSearchCriteria") ?></span>
	<?php } else { ?>
	<span class="phpmaker"><?php echo $Language->Phrase("NoRecord") ?></span>
	<?php } ?>
<?php } ?>
	</td>
</tr></table>
</form>
<?php } ?>
<span class="phpmaker">
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($notifications_list->AddUrl <> "") { ?>
<a class="ewGridLink" href="<?php echo $notifications_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>&nbsp;&nbsp;
<?php } ?>
<?php } ?>
</span>
</div>
</td></tr></table>
<script type="text/javascript">
fnotificationslistsrch.Init();
fnotificationslist.Init();
</script>
<?php
$notifications_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$notifications_list->Page_Terminate();
?>
