<?php
class CoreController extends Controller{
	var $data = array(
	);
	public $coreAssets;

	public function init()
	{
		parent::init();
		$this->coreAssets = Yii::app()->params["assets"];
		Yii::app()->theme = "admin";
	}

	public function l($url,$echo=false){
		$url = $this->createUrl($url);
		if($echo){
			echo $url;
		}
		return $url;
	}

	public function md($url,$echo=false){
		return $this->l("/" . $this->module->getName() . "/" . $url,$echo);
	}

	public function render($view,$data=array(),$return=false){
		$this->add_asset_core(false,$this->coreAssets["css"]);

		$this->add_asset_core($this->coreAssets["js"]);

		foreach($this->coreAssets["extensions"] as $extension_name => $arr){
			$js_files = isset($arr["js"]) ? $arr["js"] : false;
			$css_files = isset($arr["css"]) ? $arr["css"] : false;
			$this->add_asset_extension($extension_name,$js_files,$css_files);
		}

		$this->add_asset_custom($this->coreAssets["custom"]["js"],$this->coreAssets["custom"]["css"]);

		return parent::render($view,$data,$return);
	}
	protected function renderView($view)
	{
		$this->render($view,$this->data);
	}
	public function get_asset_folder($to_file=""){
		$path =  Yii::app()->theme->baseUrl . "/assets/";
		return $path . $to_file;
	}
	public function add_asset_core($js_files=false, $css_files=false){
		$cs = Yii::app()->getClientScript();

		if($js_files){
			foreach($js_files as $key => $value){
				if($value==1){
					// extenal file
					$cs->registerScriptFile($key);
				} else {
					$cs->registerScriptFile($this->get_asset_folder("core/js/".$value));
				}
			}
		}
		if($css_files){
			foreach($css_files as $key => $value){
				if($value==1){
					// extenal file
					$cs->registerCssFile($key);
				} else {
					$cs->registerCssFile($this->get_asset_folder("core/css/".$value));	
				}
			}
		}
	}
	public function add_asset_custom($js_files=array(),$css_files=array()){
		$cs = Yii::app()->getClientScript();

		if($js_files){
			if(is_array($js_files)){
				// an array of files
				foreach($js_files as $key => $value){
					if($value==1){
						// extenal file
						$cs->registerScriptFile($key,CClientScript::POS_END);
					} else {
						$cs->registerScriptFile($this->get_asset_folder("custom/js/".$value),CClientScript::POS_END);
					}
				}
			} else {
				// filename = filename
				$cs->registerScriptFile($this->get_asset_folder("custom/js/".$js_files),CClientScript::POS_END);
			}
		}
		if($css_files){
			if(is_array($css_files)){
				// an array of files
				foreach($css_files as $key => $value){
					if($value==1){
						// extenal file
						$cs->registerCssFile($key);
					} else {
						$cs->registerCssFile($this->get_asset_folder("custom/css/".$value));
					}
				}
			} else {
				// filename = filename
				$cs->registerCssFile($this->get_asset_folder("custom/css/".$css_files));
			}
		}
	}
	public function add_asset_module($js_files=array(),$css_files=array()){
		$cs = Yii::app()->getClientScript();

		//$path = "/application/modules/".$this->module->getName()."/assets/";
		$path = Yii::app()->assetManager->publish(Yii::getPathOfAlias('application.modules.'."admin".'.assets'),false,-1,true);

		if($js_files){
			if(is_array($js_files)){
				// an array of files
				foreach($js_files as $key => $value){
					if($value==1){
						// extenal file
						$cs->registerScriptFile($key,CClientScript::POS_END);
					} else {
						$cs->registerScriptFile($path . "/js/".$value,CClientScript::POS_END);
					}
				}
			} else {
				// filename = filename
				$cs->registerScriptFile($path . "/js/".$js_files,CClientScript::POS_END);
			}
		}
		if($css_files){
			if(is_array($css_files)){
				// an array of files
				foreach($css_files as $key => $value){
					if($value==1){
						// extenal file
						$cs->registerCssFile($key);
					} else {
						$cs->registerCssFile($path . "/css/".$value);
					}
				}
			} else {
				// filename = filename
				$cs->registerCssFile($path . "/css/".$css_files);
			}
		}
	}
	public function add_asset_extension($extension_name,$js_files=true, $css_files=true){
		$cs = Yii::app()->getClientScript();

		if($js_files){
			if(is_array($js_files)){
				// an array of files
				foreach($js_files as $key => $value){
					if($value==1){
						// extenal file
						$cs->registerScriptFile($key,CClientScript::POS_END);
					} else {
						$cs->registerScriptFile($this->get_asset_folder("extensions/".$extension_name."/js/".$value),CClientScript::POS_END);
					}
				}
			} elseif($js_files===true) {
				// filename = extension_name
				$cs->registerScriptFile($this->get_asset_folder("extensions/".$extension_name."/js/".$extension_name.".js"),CClientScript::POS_END);
			} else {
				// filename = filename
				$cs->registerScriptFile($this->get_asset_folder("extensions/".$extension_name."/js/".$js_files),CClientScript::POS_END);
			}
		}
		if($css_files){
			if(is_array($css_files)){
				// an array of files
				foreach($css_files as $key => $value){
					if($value==1){
						// extenal file
						$cs->registerCssFile($key);
					} else {
						$cs->registerCssFile($this->get_asset_folder("extensions/".$extension_name."/css/".$value));
					}
				}
			} elseif($css_files===true) {
				// filename = extension_name
				$cs->registerCssFile($this->get_asset_folder("extensions/".$extension_name."/css/".$extension_name.".css"));
			} else {
				// filename = filename
				$cs->registerCssFile($this->get_asset_folder("extensions/".$extension_name."/css/".$css_files));
			}
		}
	}
	//
	var $js_code_index = 0;
	public function asset_start_js_code()
	{
		ob_start();
	}
	public function asset_end_js_code()
	{
		$js_code = ob_get_clean();
		$js_code = str_replace("<script>", "", $js_code);
		$js_code = str_replace("</script>", "", $js_code);
		$cs = Yii::app()->getClientScript();
		$cs->registerScript("js_code_".($this->js_code_index++),$js_code,CClientScript::POS_END);
		//$cs->renderBodyEnd($js_code);

	}
	protected function input_get($name,$default=false,$extendDefaultInsteadOfReplace=false)
	{
		return $this->input($name,"get",$default,$extendDefaultInsteadOfReplace);
	}
	protected function input_post($name,$default=false,$extendDefaultInsteadOfReplace=false)
	{
		return $this->input($name,"post",$default,$extendDefaultInsteadOfReplace);
	}
	protected function input_get_post($name,$default=false,$extendDefaultInsteadOfReplace=false)
	{
		return $this->input($name,"get_post",$default,$extendDefaultInsteadOfReplace);
	}
	protected function input($name,$type="get_post",$default=false,$extendDefaultInsteadOfReplace=false)
	{
		switch($type)
		{
			case "get":
				return isset($_GET[$name]) ? $this->input_default($_GET[$name],$default,$extendDefaultInsteadOfReplace) : $default;
				break;
			case "post":
				return isset($_POST[$name]) ? $this->input_default($_POST[$name],$default,$extendDefaultInsteadOfReplace) : $default;
				break;
			case "get_post":
				return isset($_GET[$name]) ? $this->input_default($_GET[$name],$default,$extendDefaultInsteadOfReplace) : (isset($_POST[$name]) ? input_default($_POST[$name],$default,$extendDefaultInsteadOfReplace) : $default);
				break;
		}
	}
	protected function input_default($input, $default=false,$extendDefaultInsteadOfReplace=false)
	{
		if(!$extendDefaultInsteadOfReplace)
			return $input;
		return array_merge_recursive($default,$input);

	}
	protected function show_404($die=true){
		echo "404";
		if($die)
			die();
	}
	protected function returnJSON($array)
	{
		echo CJSON::encode($array);
		Yii::app()->end();
	}
	protected function returnSuccess($data=array(),$message="Ok")
	{
		$this->returnJSON(array(
			"success" => 1,
			"message" => $message,
			"data" => $data
		));
	}
	protected function returnError($message="Error occurs",$data=array())
	{
		$this->returnJSON(array(
			"success" => 0,
			"message" => $message,
			"data" => $data
		));	
	}

	function handleUploadFile($input_file, $id=false, $folder=false, $file_type=null)
	{
		if(isset($_FILES[$input_file]) && $_FILES[$input_file]["name"])
		{
			$ext = pathinfo($_FILES[$input_file]["name"], PATHINFO_EXTENSION);
			if($file_type){
				$_file_type = array();
				if(is_array($file_type)){
					$_file_type = $file_type;
				} else if(is_string($file_type)){
					switch ($file_type) {
						case 'image':
							$_file_type = array(
								"jpg", "jpeg", "png", "bmp", "gif", "webp", "svg"
							);
							break;
					}
				}
				if(!in_array(strtolower($ext), $_file_type))
					return false;
			}
			$name = "";
			if($id)
				$name .= $id . "+";
			$name .= time();
			$name = md5($name);
			$name .= "." . $ext;
			$path = "upload/";
			if($folder)
				$path .= $folder . "/";
			$path .= $name;
			move_uploaded_file($_FILES[$input_file]["tmp_name"], $path);
			return "/" . $path;
		}
		else
		{
			return false;
		}
	}
}