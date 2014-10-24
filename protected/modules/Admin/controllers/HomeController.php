<?php

class HomeController extends AdminTableController {

    var $pages = array(
        "user" => array(
            "title" => "User manager"
        ),
        "document" => array(
            "title" => "Document manager"
        ),
        "faculty" => array(
            "title" => "Faculty manager"
        ),
        "dept" => array(
            "title" => "Department manager"
        ),
        "subject" => array(
            "title" => "Subject manager"
        ),
        "subjecttype" => array(
            "title" => "Subject Type manager"
        ),
        "subjectgrouptype" => array(
            "title" => "Subject Group Type manager"
        ),
        "subjectdoc" => array(
            "title" => "Subject - Doc manager"
        ),
        "subjectteacher" => array(
            "title" => "Subject - Teacher manager"
        ),
        "teacherfacultypos" => array(
            "title" => "Teacher Position in Faculty"
        ),
        "lesson" => array(
            "title" => "Lesson manager"
        ),
        "lessondoc" => array(
            "title" => "Lesson - Doc manager"
        ),
        "lessonvideo" => array(
            "title" => "Lesson - Video manager"
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

    public function actionFaculty() {
        $this->setCurrentPage("faculty");
        $this->handleTable("faculty");
    }

    public function actionDept() {
        $this->setCurrentPage("dept");
        $this->handleTable("dept");
    }

    public function actionSubjectType() {
        $this->setCurrentPage("subjecttype");
        $this->handleTable("subjecttype");
    }

    public function actionSubject() {
        $this->setCurrentPage("subject");
        $this->handleTable("subject");
    }

    public function actionSubjectGroupType() {
        $this->setCurrentPage("subjectgrouptype");
        $this->handleTable("subjectgrouptype");
    }

    public function actionSubjectDoc() {
        $this->setCurrentPage("subjectdoc");
        $this->handleTable("subjectdoc");
    }

    public function actionSubjectTeacher() {
        $this->setCurrentPage("subjectteacher");
        $this->handleTable("subjectteacher");
    }

    public function actionTeacherFacultyPos() {
        $this->setCurrentPage("teacherfacultypos");
        $this->handleTable("teacherfacultypos");
    }

    public function actionLesson() {
        $this->setCurrentPage("lesson");
        $this->handleTable("lesson");
    }
    public function actionLessonDoc() {
        $this->setCurrentPage("lessondoc");
        $this->handleTable("lessondoc");
    }
     public function actionLessonVideo() {
        $this->setCurrentPage("lessonvideo");
        $this->handleTable("lessonvideo");
    }

}
