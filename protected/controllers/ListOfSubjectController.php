<?php

Yii::import('application.controllers.BaseController');

class ListOfSubjectController extends BaseController {

    public function actionIndex() {
        $this->pageTitle = "Bluebee - UET | Chương trình đào tạo";
        Yii::app()->clientScript->registerMetaTag("Bluebee - UET | Chương trình đào tạo", null, null, array('property' => 'og:title'));
        $this->actionListOfSubject();
    }

    public function listCategoryFather() {
        $category_father = Faculty::model()->findAll();
        return $category_father;
    }

    public function listSubjectType() {
        $subject_type = SubjectType::model()->findAll();
        return $subject_type;
    }

    public function actionListOfSubject() {
        $category_father = $this->listCategoryFather();
        $subject_type = $this->listSubjectType();
        $this->render('listOfSubject', array('category_father' => $category_father, 'subject_type' => $subject_type));
    }

    public function actionInfo() {
        $category_father = $this->listCategoryFather();
        $subject_type = $this->listSubjectType();

        $this->render('info', array('category_father' => $category_father, 'subject_type' => $subject_type));
    }

    public function actionSubject() {
        if (isset($_GET["subject_id"])) {
            $subject_id = StringHelper::filterString($_GET["subject_id"]);
            $subjectCriteria = new CDbCriteria();
            $subjectCriteria->select = "*";
            $subjectCriteria->condition = "subject_id = :subject_id";
            $subjectCriteria->params = array(":subject_id" => $subject_id);
            $subject = Subject::model()->findAll($subjectCriteria);

            $teachers = Teacher::model()->with(array("subject_teacher" => array(
                            "select" => false,
                            "condition" => "subject_id = :subject_id",
                            "params" => array(":subject_id" => $subject_id)
                )))->findAll();

            $doc = Doc::model()->with(array("docs" => array(
                            "select" => false,
                            "condition" => "subject_id = :subject_id and active = 1",
                            "params" => array(":subject_id" => $subject_id)
                )))->findAll(array("limit" => "3", "order" => "RAND()"));

            $reference = Doc::model()->with(array("docs" => array(
                            "select" => false,
                            "condition" => "subject_id = :subject_id and active = 0",
                            "params" => array(":subject_id" => $subject_id)
                )))->findAll(array("limit" => "3", "order" => "RAND()"));

            $lesson = Lesson::model()->findAll(array("select" => "*", "condition" => "lesson_subject = :lesson_subject",
                "params" => array(":lesson_subject" => $subject_id),
                "order" => "lesson_weeks ASC"));

            $doc_related = Doc::model()->with(array("docs" => array(
                            "condition" => "subject_id = :subject_id",
                            "params" => array(":subject_id" => $subject_id)
                )))->findAll();
        }
        foreach ($subject as $subject_detail):
            $title = "Bluebee - UET | " . $subject_detail->subject_name;
            $des = $subject_detail->subject_target;
        endforeach;
        $this->pageTitle = $title;
        Yii::app()->clientScript->registerMetaTag($title, null, null, array('property' => 'og:title'));
        Yii::app()->clientScript->registerMetaTag(Yii::app()->createAbsoluteUrl('listOfSubject/subject?subject_id=') . $_GET["subject_id"], null, null, array('property' => 'og:url'));
        Yii::app()->clientScript->registerMetaTag($des, null, null, array('property' => 'og:description'));
        $category_father = Faculty::model()->findAll();
        $subject_type = SubjectType::model()->findAll();
        $this->render('subject', array('subject' => $subject, 'category_father' => $category_father,
            'subject_type' => $subject_type, 'teacher' => $teachers,
            'doc' => $doc, 'reference' => $reference, 'lesson' => $lesson, 'doc_related' => $doc_related));
    }

    public function actionCourseOfStudy() {
        $category_father = $this->listCategoryFather();
        $subject_type = $this->listSubjectType();
        $this->render('courseOfStudy', array('category_father' => $category_father, 'subject_type' => $subject_type));
    }

    public function actionDeptInfoView() {
        $this->retVal = new stdClass();
        $html = $this->renderPartial('courseOfStudyhtml', FALSE);

        echo $html;
        Yii::app()->end();
    }

    public function actionFacultyInfoView() {
        $this->retVal = new stdClass();
        $html = $this->renderPartial('departmenthtml', FALSE);
        echo $html;
        Yii::app()->end();
    }

    public function actionDeptInfo() {
        $this->retVal = new stdClass();
        $request = Yii::app()->request;
        if ($request->isPostRequest && isset($_POST)) {
            try {
                $listSubjectData = array(
                    'dept_id' => $_POST['dept_id'],
                    'faculty_id' => $_POST['faculty_id'],
                );
                $dept_data = Dept::model()->findAllByAttributes(array('dept_id' => $listSubjectData['dept_id'],
                    'dept_faculty' => $listSubjectData['faculty_id']));
                $this->retVal->dept_data = $dept_data;
                $this->retVal->message = 1;
            } catch (exception $e) {
                $this->retVal->message = $e->getMessage();
            }
            echo CJSON::encode($this->retVal);
            Yii::app()->end();
        }
    }

    public function actionListOfSubjectInfoView() {
        $this->retVal = new stdClass();
        $html = $this->renderPartial('listOfSubjecthtml', FALSE);

        echo $html;
        Yii::app()->end();
    }

    public function actionListOfSubjectInfo() {
        $this->retVal = new stdClass();
        $request = Yii::app()->request;
        if ($request->isPostRequest && isset($_POST)) {
            try {
                $listSubjectData = array(
                    'subject_dept' => $_POST['subject_dept'],
                    'subject_faculty' => $_POST['subject_faculty'],
                    'subject_type' => $_POST['subject_type'],
                    'dept_id' => $_POST['subject_dept'],
                    'faculty_id' => $_POST['subject_faculty'],
                );
                $subject_data = Subject::model()->findAll(array(
                    'select' => '*',
                    'condition' => 'subject_faculty = ' . $listSubjectData['subject_faculty'] . ' AND subject_type = ' . $listSubjectData['subject_type'] . ' AND (subject_general_faculty_id = ' . $listSubjectData['faculty_id'] . ' OR subject_dept = ' . $listSubjectData['subject_dept'] . ')'));
                $subject_type_group = SubjectGroupType::model()->findAllByAttributes(array('subject_group' => $listSubjectData['subject_type'],
                    'subject_dept' => $listSubjectData['subject_dept'], 'subject_faculty' => $listSubjectData['subject_faculty']));
//                var_dump($subject_data);
//                                exit();
                $subject_type_name = SubjectType::model()->findAllByAttributes(array('id' => $listSubjectData['subject_type']));
                $this->retVal->subject_data = $subject_data;
                $this->retVal->subject_group_type = $subject_type_group;
                $dept_data = Dept::model()->findAllByAttributes(array('dept_id' => $listSubjectData['dept_id'],
                    'dept_faculty' => $listSubjectData['faculty_id']));

                $this->retVal->subject_type = $subject_type_name;
                $this->retVal->message = 1;
            } catch (exception $e) {
                $this->retVal->message = $e->getMessage();
            }
            echo CJSON::encode($this->retVal);
            Yii::app()->end();
        }
    }

    public function actionFacultyInfo() {
        $category_father = $this->listCategoryFather();
        $subject_type = $this->listSubjectType();
        $this->retVal = new stdClass();
        $request = Yii::app()->request;
        if ($request->isPostRequest && isset($_POST)) {
            try {
                $listSubjectData = array(
                    'faculty_id' => $_POST['faculty_id'],
                );
                $faculty_data = Faculty::model()->findAllByAttributes(array(
                    'faculty_id' => $listSubjectData['faculty_id']));
                $sql = "SELECT * FROM tbl_teacher_faculty_position INNER JOIN tbl_teacher ON tbl_teacher_faculty_position.teacher_id = tbl_teacher.teacher_id WHERE tbl_teacher_faculty_position.teacher_id = '" . $listSubjectData['faculty_id'] . "'";
                $teacher_faculty_position = Yii::app()->db->createCommand($sql)->queryAll();

                $this->retVal->faculty_data = $faculty_data;
                $this->retVal->teacher_faculty_position = $teacher_faculty_position;
                $this->retVal->message = 1;
            } catch (exception $e) {
                $this->retVal->message = $e->getMessage();
            }
            echo CJSON::encode($this->retVal);
            Yii::app()->end();
        }
    }

}
