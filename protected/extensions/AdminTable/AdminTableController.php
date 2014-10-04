<?php

abstract class AdminTableController extends CoreController
{
	var $viewTable = "application.extensions.AdminTable.views.AdminTable";
	var $viewList = "application.extensions.AdminTable.views.AdminList";
	var $title = "Admin Panel";
	var $table = null;
	var $pages = array();
	var $currentPage = "";

	var $tableDefault = array(
		"actions" => array(
			"_view" => "*",
			"_edit" => false,
			"_delete" => true,
			"_new" => false,
			"search" => true,
			"search_advanced" => false,
			"_link" => false,
			"_customButtons" => array()
		),
		"default" => array(
			"orderBy" => "id",
			"orderType" => "asc",
			"page" => 0,
			"per_page" => 10,
			"search" => "",
			"search_advanced" => ""
		),
		"type" => "table",
		"dragToReOrder" => false,
		"hasHtmlEditor" => false,
		"condition" => false,
		"join" => false,
		"limit_values" => array(10,20,30,40),
		"model" => null,
		"primary" => "id",
		"formUpload" => false,
		"itemLabel" => "Item",
		"additionalFiles" => array(
			"beginDocument" => null,
			"beforeTable" => null,
			"afterTable" => null,
			"endDocument" => null,
			"rightOfTitle" => null
		),
		"confirmDeleteMessage" => "Are you sure you want to delete this item? - It cannot be undone",
		"deleteCompletedly" => true
	);

	var $tableFieldDefault = array(
		"type" => "_text",
		"orderable" => true,
		"searchAdvancedType" => "_default"
	);

	protected abstract function getFileLocation();

	protected function handleTable($tableName)
	{
		$table = $this->getTable($tableName);
		$action = $this->input_get("action");
		switch($action){
			case "data":
				$this->tableData($table);
				break;
			case "update":
				$this->tableUpdate($table);
				break;
			case "delete":
				$this->tableDelete($table);
				break;
			case "insert":
				$this->tableInsert($table);
				break;
			default:
				$this->data["table"] = $table;
				$this->renderViewTable();
				break;
		}
	}	

	protected function getTable($name)
	{
		return include_once(dirname($this->getFileLocation()) . "/pages/" . $name . ".php");
	}

	public function setCurrentPage($page){
		$this->currentPage = $page;
	}

//	protected function beforeAction($action)
//	{
//		if(Yii::app()->user->isGuest)
//		{
//			$this->redirect("site/login");
//			return false;
//		}
//		return parent::beforeAction($action);
//	}

	protected function handleUpdateField($object,$column,$table){
		$val = $this->input_post($column);
		if($val===false && $table["fields"][$column]["type"]=="_checkbox")
		{
			$val = 0;
		}
		$object->setAttribute($column,$val);
		return $val;
	}

	protected function tableData($table){
		$criteria = $this->sqlFromRequest($table);
		$rows = $table["model"]::model()->findAll($criteria);
		$count = $table["model"]::model()->count($criteria);
		$data = array();
		foreach($rows as $i => $row)
		{
			$data[$i] = $row->attributes;
		}
		$this->returnSuccess(array(
			"data" => $data,
			"count" => $count
		));
	}

	protected function tableInsert($modelClass,$table,$extend=array()){
		$obj = new $modelClass();
		$fields = $table["actions"]["_new"]["attr"];
		foreach($fields as $field){
			$value = $this->handleUpdateField($obj,$field,$table);
			if(!$value)
				$this->returnError();
			//$obj->$field = $value;
		}
		foreach($extend as $key => $value){
			$obj->$key = $value;
		}
		//print_r($obj);die();
		if(!$obj->save(true))
			$this->returnError();
		$this->returnSuccess();
	}

	protected function tableUpdate($table)
	{
		$editable = $table["actions"]["_edit"];
		$modelClass = $table["model"];
		if(!is_array($editable) || !isset($editable[0]))
		{
			$this->returnError();
			return;
		}

		if($this->input_get("multiple")){
			$objects = $modelClass::model()->updateAll($this->input_post["attrs"],$table["primary"]." in (". implode(",", $this->input_post($table["primary"])) .") ");
			$this->returnSuccess();
			return;
		}

		$object = $modelClass::model()->find($table["primary"]." = ".$this->input_post($table["primary"]));
		if(!$object)
		{
			$this->returnError($errorMessage);
			return;
		}
		foreach($editable as $column)
		{
			$this->handleUpdateField($object,$column,$table);	
		}
		if(!$object->save(true,$editable))
		{
			$this->returnError("Error occurs",array(
				"errors" => $object->getErrors()
			));
		}
		else
		{
			$this->returnSuccess();
		}
	}

	protected function tableDelete($table)
	{
		$modelClass = $table["model"];
		if($this->input_get("multiple")){
			$objects = $modelClass::model()->deleteAll($table["primary"]." in (". implode(",", $this->input_post($table["primary"])) .") ");
			$this->returnSuccess();
			return;
		}

		$object = $modelClass::model()->find($table["primary"]." = ".$this->input_post($table["primary"]));
		if(!$object)
		{
			$this->returnError();
			return;
		}
		$result = $object->delete();
		if(!$result)
		{
			$this->returnError($options["errorMessage"],array(
				"errors" => $object->getErrors()
			));
		}
		else
		{
			$this->returnSuccess();
		}
	}

	protected function tableOrder($table)
	{
		$orders = $this->input_post("orders");
		if(!$orders)
			return;
		$whenSql = "";
		foreach($orders as $order)
		{
			$whenSql .= "WHEN ".$order["id"]." THEN '".$order["index"]."'";
		}
		$sql = "UPDATE ".$table." SET order_index = CASE id ".$whenSql." END ";
		Yii::app()->db->createCommand($sql)->execute();
	}

	protected function _render(){
		$table = $this->data["table"];
		
		$table = array_replace_recursive($this->tableDefault,$table);
		$table["url"] = $this->md("home/" . $table["tableAlias"],false);
		if($table["actions"]["_view"]=="*")
		{
			$table["actions"]["_view"] = array();
			foreach($table["fields"] as $column => $field)
			{
				$table["actions"]["_view"][] = $column;
			}
		}
		

		foreach($table["fields"] as $name => $field)
		{
			$table["fields"][$name] = array_merge($this->tableFieldDefault,$field);
			if(!isset($field["label"]))
				$table["fields"][$name]["label"] = ucwords($name);
		}

		$this->add_asset_extension("angular",array(
			"angular.min.js"
		),false);

		$this->add_asset_extension("bootstrap-datepicker",array(
			"bootstrap-datepicker.js",
			"\$__\$.js"
		),"datepicker.css");

		if($table["dragToReOrder"]){

			$this->add_asset_extension("jquery-ui","jquery.ui.js",array(
			    "jquery-ui.css",
			    "jquery.ui.theme.css"
			));
			
			$this->add_asset_extension("angular",array(
				"//cdnjs.cloudflare.com/ajax/libs/angular-ui/0.4.0/angular-ui.min.js" => 1
			),false);
			$this->add_asset_extension("tinymce",array(
				"tinymce.min.js"
			),false);
		}

		$this->add_asset_extension("font-awesome-3.2.1",false,"font-awesome.css");

		$cs = Yii::app()->getClientScript();
		$cs->registerScript("admin-table-script",'
				var TableConfig = '.json_encode($table).';
		',CClientScript::POS_HEAD);

		$this->data["table"] = $table;
		
	}

	protected function renderAdditionalFiles($position)
	{
		//print_r($this->data["table"]);die();
		$files = $this->data["table"]["additionalFiles"][$position];
		if($files==null)
			return;
		if(is_array($files))
		{
			foreach($files as $file)
			{
				$this->renderPartial($file,array(
					"table" => $this->data["table"]
				));
			}
		}
		else
		{
			$this->renderPartial($files,array(
				"table" => $this->data["table"]
			));
		}
	}

	protected function renderViewTable()
	{
		$this->_render();
		$this->renderView($this->viewTable);
	}

	protected function renderPartialTable(){
		$this->_render();
		$this->renderPartial($this->viewTable,$this->data);
	}

	protected function renderViewList(){
		$this->_render();
		$this->renderView($this->viewList);
	}

	protected function renderPartialList(){
		$this->_render();
		$this->renderPartial($this->viewList,$this->data);
	}

	protected function isPost($postVar=null)
	{
		$return = Yii::app()->getRequest()->getIsPostRequest() && isset($_POST["do"]);
		if($postVar!=null)
		{
			if(is_array($postVar))
			{
				foreach($postVar as $var)
				{
					$return = $return && isset($_POST[$var]);		
				}
			}
			else
			{
				$return = $return && isset($_POST[$postVar]);
			}
		}
		return $return;
	}

	protected function sqlFromRequest($table)
	{
		// order, page, per_page, search, search_advanced
		$order = $this->input_get_post("order","id desc");
		$page = intval($this->input_get_post("page",0));
		$per_page = intval($this->input_get_post("per_page",10));
		$search = trim($this->input_get_post("search",""));
		$search_advanced = $this->input_get_post("search_advanced",0);
		//
		$criteria=new CDbCriteria();
		$criteria->together = true;
		if(!$search_advanced && $search!="" && $table["actions"]["_search"])
		{
			$searchAttr = $table["actions"]["_search"];
			foreach($searchAttr as $i => $attr)
			{
				$criteria->compare($attr,$search,true,"OR",true);
			}
		}
		if($search_advanced)
		{
			foreach($search_advanced as $attr => $val)
			{
				$field = $table["fields"][$attr];
				if(!isset($field["searchAdvancedType"]))
					$field["searchAdvancedType"] = "_default";
				switch($field["searchAdvancedType"]){
					case "_timestamp_range":
						// $val is an array
						if($val["from"])
							$criteria->compare($attr,">=" . strtotime($val["from"]),false,"AND",true);
						if($val["to"])
							$criteria->compare($attr,"<=" . strtotime($val["to"]),false,"AND",true);
						break;
					case "_number_range":
						if($val["from"])
							$criteria->compare($attr,">=" . $val["from"],false,"AND",true);
						if($val["to"])
							$criteria->compare($attr,"<=" . $val["to"],false,"AND",true);
						break;
					case "_datetime_range":
						// $val is an array
						if($val["from"])
							$criteria->compare($attr,">=" . $val["from"],false,"AND",true);
						if($val["to"])
							$criteria->compare($attr,"<=" . $val["to"],false,"AND",true);
						break;
					default:
						$criteria->compare($attr,$val,true,"AND",true);
						break;
				}
			}
		}
		$criteria->order = $order;
		$criteria->limit = $per_page;
		$criteria->offset = ($page-1)*$per_page;

		//print_r($criteria);

		return $criteria;
	}
}