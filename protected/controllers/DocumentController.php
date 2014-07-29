<?php

Yii::import('application.controllers.BaseController');

class DocumentController extends BaseController {

//     public function beforeAction() {
//        if (Yii::app()->session['token'] == '')
//            $this->redirect('welcomePage');
//    }

    public function actionIndex() {

        //    $this->redirect('welcomePage');

        $this->actionDocument();
    }

      public function listCategoryFather() {
        $category_father = Faculty::model()->findAll();
        return $category_father;
    }

    public function listSubjectType() {
        $subject_type = SubjectType::model()->findAll();
        return $subject_type;
    }


    public function actionDocument() {
         $category_father = $this->listCategoryFather();
        $subject_type = $this->listSubjectType();
        $Criteria = new CDbCriteria(); //represent for query such as conditions, ordering by, limit/offset.
        
        $Criteria->select = "*";
        $Criteria->order = "doc_id DESC";

        $subject = Subject::model()->findAll();

        $this->render('document', array('document' => Doc::model()->findAll($Criteria), 'subject_list' => $subject, 'category_father' => $category_father, 'subject_type' => $subject_type));
    }


    public function actionViewDocument() {
        $this->render('viewdocument');
    }

    public function saveDoc($doc_name, $doc_description, $doc_url, $doc_author, $subject_id, $doc_scribd_id, $doc_type) {
        $doc_model = new Doc;
        $doc_model->doc_name = $doc_name;
        $doc_model->doc_description = $doc_description;
        $doc_model->doc_url = $doc_url;
        $doc_model->doc_scribd_id = $doc_scribd_id;
        $doc_model->doc_type = $doc_type;
        $doc_model->doc_status = 1;
        $doc_model->doc_author = $doc_author;
        $doc_model->save(FALSE);
        $doc_subject = new SubjectDoc;
        $doc_subject->doc_id = $doc_model->doc_id;
        $doc_subject->doc_type = $doc_model->doc_type;
        $doc_subject->subject_id = $subject_id;
        $doc_subject->active = 1;
        $doc_subject->save(FALSE);
    }

    public function unicode_str_filter($str) {
        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => 'Đ',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );
        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        return $str;
    }

    public function actionUpload() {
        //$ds = DIRECTORY_SEPARATOR;  //1
        $subject_id = strip_tags($_POST['subject_id']);
        $doc_name = strip_tags($_POST['doc_name']);
        $doc_description = strip_tags($_POST['doc_description']);
        $doc_author = Yii::app()->session['user_id'];
        $api_key = "24cxjtv3vw69wu5p7pqd9";
        $secret = "sec-b2rlvg8kxwwpkz9fo3i02mo9vo";
        $this->retVal = new stdClass();
        $scribd = new Scribd($api_key, $secret);
        $storeFolder = Yii::getPathOfAlias('webroot') . '/uploads/';   //2
        $name = $this->unicode_str_filter( $_FILES['file']['name']);
        $tempFile = $_FILES['file']['tmp_name'];          //3
        $targetPath = $storeFolder;  //4
        $targetFile = $targetPath . $name;  //5
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        move_uploaded_file($tempFile, $targetFile); //6

        if ($ext == "gif" || $ext == "jpg" || $ext == "jpeg" || $ext == "pjepg" || $ext == "png" || $ext == "x-png") {
            $this->saveDoc($doc_name, $doc_description, $targetFile, $doc_author, $subject_id, NULL, 1);

            $this->retVal->url = $targetFile;
            $this->retVal->doc_name = $doc_name;
            $this->retVal->user_name = Yii::app()->session['user_name'];
        } else if ($ext == "doc" || $ext == "docx" || $ext == "ppt" || $ext == "pptx" || $ext == "xls" || $ext == "xlsx" || $ext == 'txt' || $ext == 'pdf') {

            $upload_scribd = @$scribd->upload($targetFile);

            $thumbnail_info = array('doc_id' => $upload_scribd["doc_id"],
                'method' => NULL,
                'session_key' => NULL,
                'my_user_id' => NULL,
                'width' => '180',
                'height' => '220');
            $get_thumbnail = @$scribd->postRequest('thumbnail.get', $thumbnail_info);
            // var_dump($get_thumbnail);
            $this->saveDoc($doc_name, $doc_description, @$get_thumbnail["thumbnail_url"], $doc_author, $subject_id, $upload_scribd["doc_id"], 2);
            $this->retVal->docid = @$upload_scribd["doc_id"];
            $this->retVal->thumbnail = @$get_thumbnail["thumbnail_url"];
            $this->retVal->doc_name = $doc_name;
            $this->retVal->doc_path = $targetFile;
            $this->retVal->user_name = Yii::app()->session['user_name'];
        } else {
            $url_file = "";
            $this->saveDoc($doc_name, $doc_description, $targetFile, $doc_author, $subject_id, NULL, 3);
            $this->retVal->url = $targetFile;
            $this->retVal->doc_name = $doc_name;
            $this->retVal->user_name = Yii::app()->session['user_name'];
        }
        echo CJSON::encode($this->retVal);
        Yii::app()->end();
    }

    public function actionFilterDocumentByTime() {

        $request = Yii::app()->request;
        if ($request->isPostRequest && isset($_POST)) {
            try {
                $FilerFormData = array(
                    'filter_time' => $_POST['filter_time'],
                );
                $Criteria = new CDbCriteria(); //represent for query such as conditions, ordering by, limit/offset.
                $Criteria->select = "*";
                $Criteria->order = "doc_id " . $FilerFormData['filter_time'];
                $result = Doc::model()->findAll($Criteria);
                $this->retVal = $result;
            } catch (exception $e) {
                // $this->retVal->message = $e->getMessage();
            }
        }
        echo CJSON::encode($this->retVal);
        Yii::app()->end();
    }

    public function actionFilterDocumentBySubject() {
        $request = Yii::app()->request;
        if ($request->isPostRequest && isset($_POST)) {
            try {
                $FilerFormData = array(
                    'subject_id' => $_POST['subject_id'],
                );
                $Criteria = new CDbCriteria(); //represent for query such as conditions, ordering by, limit/offset.
                $Criteria->select = "*";
                $Criteria->order = "doc_id DESC";
                $Criteria->condition = "subject_id = '" . $FilerFormData['subject_id'] . "'";
                $result = SubjectDoc::model()->findAll($Criteria);
                $this->retVal = $result;
            } catch (exception $e) {
                // $this->retVal->message = $e->getMessage();
            }
        }
        echo CJSON::encode($this->retVal);
        Yii::app()->end();
    }

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
