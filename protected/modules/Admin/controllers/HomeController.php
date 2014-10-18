<?php

class HomeController extends AdminTableController {

    var $pages = array(
        "user" => array(
            "title" => "User manager"
        ),
        "document" => array(
            "title" => "Document manager"
        ),
    );

//    public function beforeAction($action) {
//        if (Yii::app()->user->isGuest && $action=="login") {
//            $this->redirect(Yii::app()->createUrl('/admin/home/login'));
//            return false;
//        }
//        return parent::beforeAction($action);
//    }

    public function actionIndex() {
        $this->render('index');
    }

    protected function getTheme() {
        return "madmin";
    }

    protected function getBrandName() {
        return "Bluebee Admin";
    }

    protected function getLoginUrl() {
        return $this->md("home/login");
    }

    protected function getLogoutUrl() {
        return $this->md("home/logout");
    }

    protected function hasLoggedIn() {
//        $user = Yii::app()->user;
//        if ($user->isGuest)
//            return false;
        return true;
    }

    protected function getActionLogin() {
        return "login";
    }

    protected function getControllerLogin() {
        return "home";
    }

    public function actionLogin() {
        $this->render('login/index');
    }

    protected function getFileLocation() {
        return __FILE__;
    }

    public function actionUser() {
        $this->setCurrentPage("user");
        $this->handleTable("user");
    }
    
    public function actionDocument() {
        $this->setCurrentPage("document");
        $this->handleTable("document");
    }

}
