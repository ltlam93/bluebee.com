<?php

class HomeController extends AdminTableController {

    var $pages = array(
        "user" => array(
            "title" => "User manager"
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

}
