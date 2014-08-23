<?php

// Global variable for table object
$tbl_post = NULL;

//
// Table class for tbl_post
//
class ctbl_post extends cTable {
	var $post_id;
	var $post_author;
	var $post_date;
	var $post_content;
	var $post_title;
	var $post_active;
	var $post_rate;
	var $post_type;
	var $post_class;
	var $post_group;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'tbl_post';
		$this->TableName = 'tbl_post';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// post_id
		$this->post_id = new cField('tbl_post', 'tbl_post', 'x_post_id', 'post_id', '`post_id`', '`post_id`', 3, -1, FALSE, '`post_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->post_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['post_id'] = &$this->post_id;

		// post_author
		$this->post_author = new cField('tbl_post', 'tbl_post', 'x_post_author', 'post_author', '`post_author`', '`post_author`', 3, -1, FALSE, '`post_author`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->post_author->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['post_author'] = &$this->post_author;

		// post_date
		$this->post_date = new cField('tbl_post', 'tbl_post', 'x_post_date', 'post_date', '`post_date`', 'DATE_FORMAT(`post_date`, \'%Y/%m/%d %H:%i:%s\')', 135, 5, FALSE, '`post_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->post_date->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['post_date'] = &$this->post_date;

		// post_content
		$this->post_content = new cField('tbl_post', 'tbl_post', 'x_post_content', 'post_content', '`post_content`', '`post_content`', 201, -1, FALSE, '`post_content`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['post_content'] = &$this->post_content;

		// post_title
		$this->post_title = new cField('tbl_post', 'tbl_post', 'x_post_title', 'post_title', '`post_title`', '`post_title`', 200, -1, FALSE, '`post_title`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['post_title'] = &$this->post_title;

		// post_active
		$this->post_active = new cField('tbl_post', 'tbl_post', 'x_post_active', 'post_active', '`post_active`', '`post_active`', 3, -1, FALSE, '`post_active`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->post_active->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['post_active'] = &$this->post_active;

		// post_rate
		$this->post_rate = new cField('tbl_post', 'tbl_post', 'x_post_rate', 'post_rate', '`post_rate`', '`post_rate`', 3, -1, FALSE, '`post_rate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->post_rate->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['post_rate'] = &$this->post_rate;

		// post_type
		$this->post_type = new cField('tbl_post', 'tbl_post', 'x_post_type', 'post_type', '`post_type`', '`post_type`', 200, -1, FALSE, '`post_type`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['post_type'] = &$this->post_type;

		// post_class
		$this->post_class = new cField('tbl_post', 'tbl_post', 'x_post_class', 'post_class', '`post_class`', '`post_class`', 3, -1, FALSE, '`post_class`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->post_class->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['post_class'] = &$this->post_class;

		// post_group
		$this->post_group = new cField('tbl_post', 'tbl_post', 'x_post_group', 'post_group', '`post_group`', '`post_group`', 3, -1, FALSE, '`post_group`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->post_group->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['post_group'] = &$this->post_group;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`tbl_post`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlWhere() { // Where
		$sWhere = "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "";
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		return TRUE;
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(),
			$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->SqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . substr($sSql, 13);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$origFilter = $this->CurrentFilter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`tbl_post`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			$sql .= ew_QuotedName('post_id') . '=' . ew_QuotedValue($rs['post_id'], $this->post_id->FldDataType) . ' AND ';
		}
		if (substr($sql, -5) == " AND ") $sql = substr($sql, 0, -5);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " AND " . $filter;
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`post_id` = @post_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->post_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@post_id@", ew_AdjustSql($this->post_id->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "tbl_postlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "tbl_postlist.php";
	}

	// View URL
	function GetViewUrl() {
		return $this->KeyUrl("tbl_postview.php", $this->UrlParm());
	}

	// Add URL
	function GetAddUrl() {
		return "tbl_postadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("tbl_postedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("tbl_postadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("tbl_postdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->post_id->CurrentValue)) {
			$sUrl .= "post_id=" . urlencode($this->post_id->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["post_id"]; // post_id

			//return $arKeys; // do not return yet, so the values will also be checked by the following code
		}

		// check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->post_id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
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

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
	}

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;

		// Write header
		$Doc->ExportTableHeader();
		if ($Doc->Horizontal) { // Horizontal format, write header
			$Doc->BeginExportRow();
			if ($ExportPageType == "view") {
				if ($this->post_id->Exportable) $Doc->ExportCaption($this->post_id);
				if ($this->post_author->Exportable) $Doc->ExportCaption($this->post_author);
				if ($this->post_date->Exportable) $Doc->ExportCaption($this->post_date);
				if ($this->post_content->Exportable) $Doc->ExportCaption($this->post_content);
				if ($this->post_title->Exportable) $Doc->ExportCaption($this->post_title);
				if ($this->post_active->Exportable) $Doc->ExportCaption($this->post_active);
				if ($this->post_rate->Exportable) $Doc->ExportCaption($this->post_rate);
				if ($this->post_type->Exportable) $Doc->ExportCaption($this->post_type);
				if ($this->post_class->Exportable) $Doc->ExportCaption($this->post_class);
				if ($this->post_group->Exportable) $Doc->ExportCaption($this->post_group);
			} else {
				if ($this->post_id->Exportable) $Doc->ExportCaption($this->post_id);
				if ($this->post_author->Exportable) $Doc->ExportCaption($this->post_author);
				if ($this->post_date->Exportable) $Doc->ExportCaption($this->post_date);
				if ($this->post_title->Exportable) $Doc->ExportCaption($this->post_title);
				if ($this->post_active->Exportable) $Doc->ExportCaption($this->post_active);
				if ($this->post_rate->Exportable) $Doc->ExportCaption($this->post_rate);
				if ($this->post_type->Exportable) $Doc->ExportCaption($this->post_type);
				if ($this->post_class->Exportable) $Doc->ExportCaption($this->post_class);
				if ($this->post_group->Exportable) $Doc->ExportCaption($this->post_group);
			}
			$Doc->EndExportRow();
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
				if ($ExportPageType == "view") {
					if ($this->post_id->Exportable) $Doc->ExportField($this->post_id);
					if ($this->post_author->Exportable) $Doc->ExportField($this->post_author);
					if ($this->post_date->Exportable) $Doc->ExportField($this->post_date);
					if ($this->post_content->Exportable) $Doc->ExportField($this->post_content);
					if ($this->post_title->Exportable) $Doc->ExportField($this->post_title);
					if ($this->post_active->Exportable) $Doc->ExportField($this->post_active);
					if ($this->post_rate->Exportable) $Doc->ExportField($this->post_rate);
					if ($this->post_type->Exportable) $Doc->ExportField($this->post_type);
					if ($this->post_class->Exportable) $Doc->ExportField($this->post_class);
					if ($this->post_group->Exportable) $Doc->ExportField($this->post_group);
				} else {
					if ($this->post_id->Exportable) $Doc->ExportField($this->post_id);
					if ($this->post_author->Exportable) $Doc->ExportField($this->post_author);
					if ($this->post_date->Exportable) $Doc->ExportField($this->post_date);
					if ($this->post_title->Exportable) $Doc->ExportField($this->post_title);
					if ($this->post_active->Exportable) $Doc->ExportField($this->post_active);
					if ($this->post_rate->Exportable) $Doc->ExportField($this->post_rate);
					if ($this->post_type->Exportable) $Doc->ExportField($this->post_type);
					if ($this->post_class->Exportable) $Doc->ExportField($this->post_class);
					if ($this->post_group->Exportable) $Doc->ExportField($this->post_group);
				}
				$Doc->EndExportRow();
			}
			$Recordset->MoveNext();
		}
		$Doc->ExportTableFooter();
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
