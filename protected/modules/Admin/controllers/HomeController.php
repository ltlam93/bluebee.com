<?php

class HomeController extends AdminTableController
{
    var $pages = array(
        "user" => array (
            "title" => "User manager"
        ),
    );
	public function actionIndex()
	{
		$this->render('index');
	}
        
        protected function getFileLocation()
        {
            return __FILE__;
        }
        
        public function actionUser() {
            $this->setCurrentPage("user");
            $this->handleTable("user");
        }
        
        
}