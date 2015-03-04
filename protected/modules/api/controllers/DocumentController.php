<?php

Yii::import('webroot.protected.modules.api.controllers.BaseController');

class DocumentController extends BaseController {

    public function actionIndex() {
        $this->render('index');
    }

    public function actionLatestDocument() {
        $this->retVal = new stdClass();
        $request = Yii::app()->request;
        if ($request->isPostRequest && isset($_POST)) {
            try {
                $limit = $request->getPost('limit');
                $sql = "SELECT * FROM tbl_doc JOIN tbl_subject_doc "
                        . "ON tbl_doc.doc_id = tbl_subject_doc.doc_id JOIN "
                        . "tbl_subject ON tbl_subject_doc.subject_id = tbl_subject.subject_id "
                        . "ORDER BY tbl_doc.doc_id DESC LIMIT " . $limit;
                $result = Yii::app()->db->createCommand($sql)->queryAll();
                $this->retVal->doc_data = $result;
            } catch (exception $e) {
                $this->retVal->message = $e->getMessage();
            }
            echo CJSON::encode($this->retVal);
            Yii::app()->end();
        }
    }

    public function actionGetFaculty() {
        $this->retVal = new stdClass();
        try {
            $sql = "SELECT * FROM tbl_faculty";
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            $this->retVal->faculty_data = $result;
        } catch (exception $e) {
            $this->retVal->message = $e->getMessage();
        }
        echo CJSON::encode($this->retVal);
        Yii::app()->end();
    }

    public function actionGetDepartment() {
        $this->retVal = new stdClass();
        $request = Yii::app()->request;
        if ($request->isPostRequest && isset($_POST)) {
            try {
                $faculty_id = $request->getPost('faculty_id');
                $sql = "SELECT * FROM tbl_dept WHERE dept_faculty = '" . $faculty_id . "'";
                $result = Yii::app()->db->createCommand($sql)->queryAll();
                $this->retVal->dept_data = $result;
            } catch (exception $e) {
                $this->retVal->message = $e->getMessage();
            }
            echo CJSON::encode($this->retVal);
            Yii::app()->end();
        }
    }

    public function actionGetSubject() {
        $this->retVal = new stdClass();
        //      $request = Yii::app()->request;

        try {
            //      $faculty_id = $request->getPost('faculty_id');
            //    $dept_id = $request->getPost('dept_id');
//                $sql = "SELECT * FROM tbl_subject JOIN tbl_faculty "
//                        . "ON tbl_subject.subject_faculty = tbl_faculty.faculty_id JOIN tbl_dept "
//                        . "ON tbl_subject.subject_dept = tbl_dept.dept_id";
            $sql = "SELECT subject_id, subject_name FROM tbl_subject";
            $result = Yii::app()->db->createCommand($sql)->queryAll();
            $this->retVal->subject_data = $result;
        } catch (exception $e) {
            $this->retVal->message = $e->getMessage();
        }
        echo CJSON::encode($this->retVal);
        Yii::app()->end();
    }

    public function actionListDocumentDept() {
        $this->retVal = new stdClass();
        $request = Yii::app()->request;
        if ($request->isPostRequest && isset($_POST)) {
            try {
                $listSubjectData = array(
                    'subject_dept' => StringHelper::filterString($_POST['subject_dept']),
                    'subject_faculty' => StringHelper::filterString($_POST['subject_faculty']),
                );
//                $subject_data = Subject::model()->findAll(array(
//                    'select' => '*',
//                    'condition' => 'subject_faculty = ' . $listSubjectData['subject_faculty'] . ' AND (subject_general_faculty_id = ' . $listSubjectData['subject_faculty'] . ' OR subject_dept = ' . $listSubjectData['subject_dept'] . ')'
//                    , 'order' => 'subject_name ASC'));
                $doc_data = Doc::model()->findAll(array(
                    'select' => '*',
                    'condition' => 'subject_faculty = ' . $listSubjectData['subject_faculty'] . ' AND (subject_general_faculty_id = ' . $listSubjectData['subject_faculty'] . ' OR subject_dept = ' . $listSubjectData['subject_dept'] . ')'
                    , 'order' => 'doc_id DESC',
                ));
                //        $this->retVal->subject_data = $subject_data;
                $this->retVal->doc_data = $doc_data;
                $this->retVal->message = 1;
            } catch (exception $e) {
                $this->retVal->message = $e->getMessage();
            }
            echo CJSON::encode($this->retVal);
            Yii::app()->end();
        }
    }

    public function actionListDocumentFaculty() {
        $this->retVal = new stdClass();
        $request = Yii::app()->request;
        if ($request->isPostRequest && isset($_POST)) {
            try {
                $listSubjectData = array(
                    'subject_faculty' => StringHelper::filterString($_POST['subject_faculty']),
                );
//                $subject_data = Subject::model()->findAll(array(
//                    'select' => '*',
//                    'condition' => 'subject_faculty = ' . $listSubjectData['subject_faculty']
//                    , 'order' => 'subject_name ASC'));
                $doc_data = Doc::model()->findAll(array(
                    'select' => '*',
                    'condition' => 'subject_faculty = ' . $listSubjectData['subject_faculty']
                    , 'order' => 'doc_id DESC',
                ));
                //  $this->retVal->subject_data = $subject_data;
                $this->retVal->doc_data = $doc_data;
                $this->retVal->message = 1;
            } catch (exception $e) {
                $this->retVal->message = $e->getMessage();
            }
            echo CJSON::encode($this->retVal);
            Yii::app()->end();
        }
    }

    public function actionListDocumentSubject() {
        $this->retVal = new stdClass();
        $request = Yii::app()->request;
        if ($request->isPostRequest) {
            try {
                $subject_id = $request->getPost('subject_id');
                $sql = "SELECT * FROM tbl_doc JOIN tbl_subject_doc ON tbl_doc.doc_id = tbl_subject_doc.doc_id WHERE tbl_subject_doc.subject_id = '" . $subject_id . "'";
                //  $this->retVal->subject_data = $subject_data;
                $doc_data = Yii::app()->db->createCommand($sql)->queryAll();
                $this->retVal->doc_data = $doc_data;
            } catch (exception $e) {
                $this->retVal->message = $e->getMessage();
            }
            echo CJSON::encode($this->retVal);
            Yii::app()->end();
        }
    }

   

    //   public function ac
    // Uncomment the following methods and override them if needed
    /*
      public function filters()
      {
      // return the filter configuration for this controller, e.g.:
      return array(
      'inlineFilterName',
      array(
      'class'=>'path.to.FilterClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }

      public function actions()
      {
      // return external action classes, e.g.:
      return array(
      'action1'=>'path.to.ActionClass',
      'action2'=>array(
      'class'=>'path.to.AnotherActionClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }
     */
}
