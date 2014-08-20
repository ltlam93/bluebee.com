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

$tbl_dept_list = NULL; // Initialize page object first

class ctbl_dept_list extends ctbl_dept {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{9095E487-4467-4C46-97C7-01D1A378652D}";

	// Table name
	var $TableName = 'tbl_dept';

	// Page object name
	var $PageObjName = 'tbl_dept_list';

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "tbl_deptadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "tbl_deptdelete.php";
		$this->MultiUpdateUrl = "tbl_deptupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tbl_dept', TRUE);

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
			$this->dept_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->dept_id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->dept_name, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->dept_target, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->dept_knowleadge, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->dept_behavior, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->dept_out_standard, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->dept_contact, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->dept_in_standart, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->dept_language, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->dept_code, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->dept_link_download, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->dept_skill, $Keyword);
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
			$this->UpdateSort($this->dept_id); // dept_id
			$this->UpdateSort($this->dept_name); // dept_name
			$this->UpdateSort($this->dept_active); // dept_active
			$this->UpdateSort($this->dept_faculty); // dept_faculty
			$this->UpdateSort($this->dept_credits); // dept_credits
			$this->UpdateSort($this->dept_code); // dept_code
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
				$this->dept_id->setSort("");
				$this->dept_name->setSort("");
				$this->dept_active->setSort("");
				$this->dept_faculty->setSort("");
				$this->dept_credits->setSort("");
				$this->dept_code->setSort("");
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("dept_id")) <> "")
			$this->dept_id->CurrentValue = $this->getKey("dept_id"); // dept_id
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

			// dept_credits
			$this->dept_credits->ViewValue = $this->dept_credits->CurrentValue;
			$this->dept_credits->ViewCustomAttributes = "";

			// dept_code
			$this->dept_code->ViewValue = $this->dept_code->CurrentValue;
			$this->dept_code->ViewCustomAttributes = "";

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

			// dept_credits
			$this->dept_credits->LinkCustomAttributes = "";
			$this->dept_credits->HrefValue = "";
			$this->dept_credits->TooltipValue = "";

			// dept_code
			$this->dept_code->LinkCustomAttributes = "";
			$this->dept_code->HrefValue = "";
			$this->dept_code->TooltipValue = "";
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
if (!isset($tbl_dept_list)) $tbl_dept_list = new ctbl_dept_list();

// Page init
$tbl_dept_list->Page_Init();

// Page main
$tbl_dept_list->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tbl_dept_list = new ew_Page("tbl_dept_list");
tbl_dept_list.PageID = "list"; // Page ID
var EW_PAGE_ID = tbl_dept_list.PageID; // For backward compatibility

// Form object
var ftbl_deptlist = new ew_Form("ftbl_deptlist");

// Form_CustomValidate event
ftbl_deptlist.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbl_deptlist.ValidateRequired = true;
<?php } else { ?>
ftbl_deptlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftbl_deptlist.Lists["x_dept_faculty"] = {"LinkField":"x_faculty_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_faculty_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var ftbl_deptlistsrch = new ew_Form("ftbl_deptlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$tbl_dept_list->TotalRecs = $tbl_dept->SelectRecordCount();
	} else {
		if ($tbl_dept_list->Recordset = $tbl_dept_list->LoadRecordset())
			$tbl_dept_list->TotalRecs = $tbl_dept_list->Recordset->RecordCount();
	}
	$tbl_dept_list->StartRec = 1;
	if ($tbl_dept_list->DisplayRecs <= 0 || ($tbl_dept->Export <> "" && $tbl_dept->ExportAll)) // Display all records
		$tbl_dept_list->DisplayRecs = $tbl_dept_list->TotalRecs;
	if (!($tbl_dept->Export <> "" && $tbl_dept->ExportAll))
		$tbl_dept_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$tbl_dept_list->Recordset = $tbl_dept_list->LoadRecordset($tbl_dept_list->StartRec-1, $tbl_dept_list->DisplayRecs);
?>
<p style="white-space: nowrap;"><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $tbl_dept->TableCaption() ?>&nbsp;&nbsp;</span>
<?php $tbl_dept_list->ExportOptions->Render("body"); ?>
</p>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($tbl_dept->Export == "" && $tbl_dept->CurrentAction == "") { ?>
<form name="ftbl_deptlistsrch" id="ftbl_deptlistsrch" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<a href="javascript:ftbl_deptlistsrch.ToggleSearchPanel();" style="text-decoration: none;"><img id="ftbl_deptlistsrch_SearchImage" src="phpimages/collapse.gif" alt="" width="9" height="9" style="border: 0;"></a><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("Search") ?></span><br>
<div id="ftbl_deptlistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="tbl_dept">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" size="20" value="<?php echo ew_HtmlEncode($tbl_dept_list->BasicSearch->getKeyword()) ?>">
	<input type="submit" name="btnsubmit" id="btnsubmit" value="<?php echo ew_BtnCaption($Language->Phrase("QuickSearchBtn")) ?>">&nbsp;
	<a href="<?php echo $tbl_dept_list->PageUrl() ?>cmd=reset" id="a_ShowAll" class="ewLink"><?php echo $Language->Phrase("ShowAll") ?></a>&nbsp;
</div>
<div id="xsr_2" class="ewRow">
	<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($tbl_dept_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($tbl_dept_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>&nbsp;&nbsp;<label><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($tbl_dept_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
</div>
</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $tbl_dept_list->ShowPageHeader(); ?>
<?php
$tbl_dept_list->ShowMessage();
?>
<br>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="ftbl_deptlist" id="ftbl_deptlist" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="tbl_dept">
<div id="gmp_tbl_dept" class="ewGridMiddlePanel">
<?php if ($tbl_dept_list->TotalRecs > 0) { ?>
<table id="tbl_tbl_deptlist" class="ewTable ewTableSeparate">
<?php echo $tbl_dept->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$tbl_dept_list->RenderListOptions();

// Render list options (header, left)
$tbl_dept_list->ListOptions->Render("header", "left");
?>
<?php if ($tbl_dept->dept_id->Visible) { // dept_id ?>
	<?php if ($tbl_dept->SortUrl($tbl_dept->dept_id) == "") { ?>
		<td><span id="elh_tbl_dept_dept_id" class="tbl_dept_dept_id"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $tbl_dept->dept_id->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $tbl_dept->SortUrl($tbl_dept->dept_id) ?>',1);"><span id="elh_tbl_dept_dept_id" class="tbl_dept_dept_id">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $tbl_dept->dept_id->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($tbl_dept->dept_id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($tbl_dept->dept_id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($tbl_dept->dept_name->Visible) { // dept_name ?>
	<?php if ($tbl_dept->SortUrl($tbl_dept->dept_name) == "") { ?>
		<td><span id="elh_tbl_dept_dept_name" class="tbl_dept_dept_name"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $tbl_dept->dept_name->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $tbl_dept->SortUrl($tbl_dept->dept_name) ?>',1);"><span id="elh_tbl_dept_dept_name" class="tbl_dept_dept_name">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $tbl_dept->dept_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td class="ewTableHeaderSort"><?php if ($tbl_dept->dept_name->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($tbl_dept->dept_name->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($tbl_dept->dept_active->Visible) { // dept_active ?>
	<?php if ($tbl_dept->SortUrl($tbl_dept->dept_active) == "") { ?>
		<td><span id="elh_tbl_dept_dept_active" class="tbl_dept_dept_active"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $tbl_dept->dept_active->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $tbl_dept->SortUrl($tbl_dept->dept_active) ?>',1);"><span id="elh_tbl_dept_dept_active" class="tbl_dept_dept_active">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $tbl_dept->dept_active->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($tbl_dept->dept_active->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($tbl_dept->dept_active->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($tbl_dept->dept_faculty->Visible) { // dept_faculty ?>
	<?php if ($tbl_dept->SortUrl($tbl_dept->dept_faculty) == "") { ?>
		<td><span id="elh_tbl_dept_dept_faculty" class="tbl_dept_dept_faculty"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $tbl_dept->dept_faculty->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $tbl_dept->SortUrl($tbl_dept->dept_faculty) ?>',1);"><span id="elh_tbl_dept_dept_faculty" class="tbl_dept_dept_faculty">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $tbl_dept->dept_faculty->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($tbl_dept->dept_faculty->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($tbl_dept->dept_faculty->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($tbl_dept->dept_credits->Visible) { // dept_credits ?>
	<?php if ($tbl_dept->SortUrl($tbl_dept->dept_credits) == "") { ?>
		<td><span id="elh_tbl_dept_dept_credits" class="tbl_dept_dept_credits"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $tbl_dept->dept_credits->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $tbl_dept->SortUrl($tbl_dept->dept_credits) ?>',1);"><span id="elh_tbl_dept_dept_credits" class="tbl_dept_dept_credits">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $tbl_dept->dept_credits->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($tbl_dept->dept_credits->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($tbl_dept->dept_credits->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($tbl_dept->dept_code->Visible) { // dept_code ?>
	<?php if ($tbl_dept->SortUrl($tbl_dept->dept_code) == "") { ?>
		<td><span id="elh_tbl_dept_dept_code" class="tbl_dept_dept_code"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $tbl_dept->dept_code->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $tbl_dept->SortUrl($tbl_dept->dept_code) ?>',1);"><span id="elh_tbl_dept_dept_code" class="tbl_dept_dept_code">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $tbl_dept->dept_code->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></td><td class="ewTableHeaderSort"><?php if ($tbl_dept->dept_code->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;"><?php } elseif ($tbl_dept->dept_code->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;"><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$tbl_dept_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($tbl_dept->ExportAll && $tbl_dept->Export <> "") {
	$tbl_dept_list->StopRec = $tbl_dept_list->TotalRecs;
} else {

	// Set the last record to display
	if ($tbl_dept_list->TotalRecs > $tbl_dept_list->StartRec + $tbl_dept_list->DisplayRecs - 1)
		$tbl_dept_list->StopRec = $tbl_dept_list->StartRec + $tbl_dept_list->DisplayRecs - 1;
	else
		$tbl_dept_list->StopRec = $tbl_dept_list->TotalRecs;
}
$tbl_dept_list->RecCnt = $tbl_dept_list->StartRec - 1;
if ($tbl_dept_list->Recordset && !$tbl_dept_list->Recordset->EOF) {
	$tbl_dept_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $tbl_dept_list->StartRec > 1)
		$tbl_dept_list->Recordset->Move($tbl_dept_list->StartRec - 1);
} elseif (!$tbl_dept->AllowAddDeleteRow && $tbl_dept_list->StopRec == 0) {
	$tbl_dept_list->StopRec = $tbl_dept->GridAddRowCount;
}

// Initialize aggregate
$tbl_dept->RowType = EW_ROWTYPE_AGGREGATEINIT;
$tbl_dept->ResetAttrs();
$tbl_dept_list->RenderRow();
while ($tbl_dept_list->RecCnt < $tbl_dept_list->StopRec) {
	$tbl_dept_list->RecCnt++;
	if (intval($tbl_dept_list->RecCnt) >= intval($tbl_dept_list->StartRec)) {
		$tbl_dept_list->RowCnt++;

		// Set up key count
		$tbl_dept_list->KeyCount = $tbl_dept_list->RowIndex;

		// Init row class and style
		$tbl_dept->ResetAttrs();
		$tbl_dept->CssClass = "";
		if ($tbl_dept->CurrentAction == "gridadd") {
		} else {
			$tbl_dept_list->LoadRowValues($tbl_dept_list->Recordset); // Load row values
		}
		$tbl_dept->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$tbl_dept->RowAttrs = array_merge($tbl_dept->RowAttrs, array('data-rowindex'=>$tbl_dept_list->RowCnt, 'id'=>'r' . $tbl_dept_list->RowCnt . '_tbl_dept', 'data-rowtype'=>$tbl_dept->RowType));

		// Render row
		$tbl_dept_list->RenderRow();

		// Render list options
		$tbl_dept_list->RenderListOptions();
?>
	<tr<?php echo $tbl_dept->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tbl_dept_list->ListOptions->Render("body", "left", $tbl_dept_list->RowCnt);
?>
	<?php if ($tbl_dept->dept_id->Visible) { // dept_id ?>
		<td<?php echo $tbl_dept->dept_id->CellAttributes() ?>><span id="el<?php echo $tbl_dept_list->RowCnt ?>_tbl_dept_dept_id" class="tbl_dept_dept_id">
<span<?php echo $tbl_dept->dept_id->ViewAttributes() ?>>
<?php echo $tbl_dept->dept_id->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<a id="<?php echo $tbl_dept_list->PageObjName . "_row_" . $tbl_dept_list->RowCnt ?>"></a>
	<?php if ($tbl_dept->dept_name->Visible) { // dept_name ?>
		<td<?php echo $tbl_dept->dept_name->CellAttributes() ?>><span id="el<?php echo $tbl_dept_list->RowCnt ?>_tbl_dept_dept_name" class="tbl_dept_dept_name">
<span<?php echo $tbl_dept->dept_name->ViewAttributes() ?>>
<?php echo $tbl_dept->dept_name->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($tbl_dept->dept_active->Visible) { // dept_active ?>
		<td<?php echo $tbl_dept->dept_active->CellAttributes() ?>><span id="el<?php echo $tbl_dept_list->RowCnt ?>_tbl_dept_dept_active" class="tbl_dept_dept_active">
<span<?php echo $tbl_dept->dept_active->ViewAttributes() ?>>
<?php echo $tbl_dept->dept_active->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($tbl_dept->dept_faculty->Visible) { // dept_faculty ?>
		<td<?php echo $tbl_dept->dept_faculty->CellAttributes() ?>><span id="el<?php echo $tbl_dept_list->RowCnt ?>_tbl_dept_dept_faculty" class="tbl_dept_dept_faculty">
<span<?php echo $tbl_dept->dept_faculty->ViewAttributes() ?>>
<?php echo $tbl_dept->dept_faculty->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($tbl_dept->dept_credits->Visible) { // dept_credits ?>
		<td<?php echo $tbl_dept->dept_credits->CellAttributes() ?>><span id="el<?php echo $tbl_dept_list->RowCnt ?>_tbl_dept_dept_credits" class="tbl_dept_dept_credits">
<span<?php echo $tbl_dept->dept_credits->ViewAttributes() ?>>
<?php echo $tbl_dept->dept_credits->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($tbl_dept->dept_code->Visible) { // dept_code ?>
		<td<?php echo $tbl_dept->dept_code->CellAttributes() ?>><span id="el<?php echo $tbl_dept_list->RowCnt ?>_tbl_dept_dept_code" class="tbl_dept_dept_code">
<span<?php echo $tbl_dept->dept_code->ViewAttributes() ?>>
<?php echo $tbl_dept->dept_code->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$tbl_dept_list->ListOptions->Render("body", "right", $tbl_dept_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($tbl_dept->CurrentAction <> "gridadd")
		$tbl_dept_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($tbl_dept->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($tbl_dept_list->Recordset)
	$tbl_dept_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($tbl_dept->CurrentAction <> "gridadd" && $tbl_dept->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager"><tr><td>
<?php if (!isset($tbl_dept_list->Pager)) $tbl_dept_list->Pager = new cPrevNextPager($tbl_dept_list->StartRec, $tbl_dept_list->DisplayRecs, $tbl_dept_list->TotalRecs) ?>
<?php if ($tbl_dept_list->Pager->RecordCount > 0) { ?>
	<table cellspacing="0" class="ewStdTable"><tbody><tr><td><span class="phpmaker"><?php echo $Language->Phrase("Page") ?>&nbsp;</span></td>
<!--first page button-->
	<?php if ($tbl_dept_list->Pager->FirstButton->Enabled) { ?>
	<td><a href="<?php echo $tbl_dept_list->PageUrl() ?>start=<?php echo $tbl_dept_list->Pager->FirstButton->Start ?>"><img src="phpimages/first.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" style="border: 0;"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/firstdisab.gif" alt="<?php echo $Language->Phrase("PagerFirst") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($tbl_dept_list->Pager->PrevButton->Enabled) { ?>
	<td><a href="<?php echo $tbl_dept_list->PageUrl() ?>start=<?php echo $tbl_dept_list->Pager->PrevButton->Start ?>"><img src="phpimages/prev.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" style="border: 0;"></a></td>
	<?php } else { ?>
	<td><img src="phpimages/prevdisab.gif" alt="<?php echo $Language->Phrase("PagerPrevious") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" id="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $tbl_dept_list->Pager->CurrentPage ?>" size="4"></td>
<!--next page button-->
	<?php if ($tbl_dept_list->Pager->NextButton->Enabled) { ?>
	<td><a href="<?php echo $tbl_dept_list->PageUrl() ?>start=<?php echo $tbl_dept_list->Pager->NextButton->Start ?>"><img src="phpimages/next.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" style="border: 0;"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/nextdisab.gif" alt="<?php echo $Language->Phrase("PagerNext") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
<!--last page button-->
	<?php if ($tbl_dept_list->Pager->LastButton->Enabled) { ?>
	<td><a href="<?php echo $tbl_dept_list->PageUrl() ?>start=<?php echo $tbl_dept_list->Pager->LastButton->Start ?>"><img src="phpimages/last.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" style="border: 0;"></a></td>	
	<?php } else { ?>
	<td><img src="phpimages/lastdisab.gif" alt="<?php echo $Language->Phrase("PagerLast") ?>" width="16" height="16" style="border: 0;"></td>
	<?php } ?>
	<td><span class="phpmaker">&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $tbl_dept_list->Pager->PageCount ?></span></td>
	</tr></tbody></table>
	</td>	
	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td>
	<span class="phpmaker"><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $tbl_dept_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $tbl_dept_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $tbl_dept_list->Pager->RecordCount ?></span>
<?php } else { ?>
	<?php if ($tbl_dept_list->SearchWhere == "0=101") { ?>
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
<?php if ($tbl_dept_list->AddUrl <> "") { ?>
<a class="ewGridLink" href="<?php echo $tbl_dept_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>&nbsp;&nbsp;
<?php } ?>
<?php } ?>
</span>
</div>
</td></tr></table>
<script type="text/javascript">
ftbl_deptlistsrch.Init();
ftbl_deptlist.Init();
</script>
<?php
$tbl_dept_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbl_dept_list->Page_Terminate();
?>
