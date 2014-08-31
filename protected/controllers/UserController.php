<?php

Yii::import('application.controllers.BaseController');

class UserController extends BaseController {

    public function actionIndex() {
        $this->actionUser();
    }

//    public function actionUser() {
//        if (isset($_GET["token"])) {
//
//            $user_activity = $this->userActivity();
//            $spCriteria = new CDbCriteria();
//            $spCriteria->select = "*";
//            $spCriteria->condition = "user_token = '" . $_GET["token"] . "'";
//
//            $user_current_token = User::model()->findByAttributes(array('user_token' => $_GET["token"]));
//
//            if ($user_current_token) {
//
//
//                $this->render('user', array('user_detail_info' => User::model()->findAll($spCriteria),
//                    'user_activity' => $user_activity));
//            }
//        }
//    }

    public function actionUser() {
        if (isset($_GET["token"])) {
            $token = StringHelper::filterString($_GET["token"]);

            $user_current_token = User::model()->find(array('select' => '*', 'condition' => 'user_token = :user_token', 'params' => array(':user_token' => $token)));
            $user_activity = $this->userActivity();
            $spCriteria = new CDbCriteria();
            $spCriteria->select = "*";
            $spCriteria->condition = "user_id = '" . $user_current_token->user_id . "'";
            $user_doc_info = Doc::model()->findAllByAttributes(array('doc_author' => $user_current_token->user_id));
            $user_current_id = User::model()->findByAttributes(array('user_id' => $user_current_token->user_id));
            $this->pageTitle = "Bluebee - UET | " . $user_current_token->user_real_name;
            Yii::app()->clientScript->registerMetaTag("Bluebee - UET | " . $user_current_token->user_real_name, null, null, array('property' => 'og:title'));
            Yii::app()->clientScript->registerMetaTag($user_current_token->user_avatar, null, null, array('property' => 'og:image'));

            if ($user_current_id) {
                $this->render('user', array('user_detail_info' => User::model()->findAll($spCriteria),
                    'user_doc_info' => $user_doc_info, 'user_activity' => $user_activity));
            }
        }
        if (isset($_GET["id"])) {
            $id = StringHelper::filterString($_GET["id"]);
            $user_activity = $this->userActivity();
            $spCriteria = new CDbCriteria();
            $spCriteria->select = "*";
            $spCriteria->condition = "user_id = :id";
            $spCriteria->params = array(':id' => $id);

            $spjCriteria = new CDbCriteria();
            $spjCriteria->select = "*";
            $spjCriteria->condition = "doc_author = :doc_author";
            $spjCriteria->params = array(':doc_author' => $id);
            $user_doc_info = Doc::model()->findAll($spjCriteria);
            $user_detail_info = User::model()->findAll($spCriteria);
            foreach ($user_detail_info as $user):
                $this->pageTitle = "Bluebee - UET | " . $user['user_real_name'];
                Yii::app()->clientScript->registerMetaTag("Bluebee - UET | " . $user['user_real_name'], null, null, array('property' => 'og:title'));
                Yii::app()->clientScript->registerMetaTag($user['user_avatar'], null, null, array('property' => 'og:image'));
            endforeach;
            $this->render('user', array('user_detail_info' => User::model()->findAll($spCriteria),
                'user_doc_info' => $user_doc_info, 'user_activity' => $user_activity));
        }
    }

//    public function actionUser_Visitor() {
//        if (isset($_GET["token"])) {
//            $user_current_token = User::model()->findByAttributes(array('user_token' => $_GET["token"]));
//            $spCriteria = new CDbCriteria();
//            $spCriteria->select = "*";
//            $spCriteria->condition = "user_id = '" . $user_current_token->user_id . "'";
//            if ($user_current_token) {
//                $sql = "SELECT * FROM tbl_class_user INNER JOIN tbl_class ON tbl_class_user.class_id = tbl_class.class_id WHERE user_id = '" . $user_current_token->user_id . "'";
//                $user_class_info = Yii::app()->db->createCommand($sql)->queryAll();
//                $this->render('user', array('user_detail_info' => User::model()->findAll($spCriteria),
//                    'user_class_info' => $user_class_info));
//            } else {
//                
//            }
//        }
//    }

    public function userActivity() {
        $user_activity = Post::model()->findAllByAttributes(array('post_author' => Yii::app()->session["user_id"]));
        return $user_activity;
    }

    public function actionChangeCover() {
        $this->retVal = new stdClass;
        $relativePath = '/images/user_cover/' . Yii::app()->session["user_id"] . '/';
        $dir = "images/user_cover/" . Yii::app()->session["user_id"];
        @mkdir(Yii::getPathOfAlias('webroot') . '/' . $dir, 0777, true);
        $image = "";
        if (isset($_FILES["file_upload_cover"]["name"])) {
            if ((($_FILES["file_upload_cover"]["type"] == "image/jpeg") || ($_FILES["file_upload_cover"]["type"] == "image/jpg") || ($_FILES["file_upload_cover"]["type"] == "image/pjpeg") || ($_FILES["file_upload_cover"]["type"] == "image/x-png") || ($_FILES["file_upload_cover"]["type"] == "image/png"))
            ) {
                if ($_FILES["file_upload_cover"]["error"] > 0) {
                    $arr->message = "Return Code: " . $_FILES["file_upload_cover"]["error"];
                }
                $tempFile = $_FILES["file_upload_cover"]["tmp_name"];          //3             
                $targetPath = Yii::getPathOfAlias('webroot') . '/' . $dir . "/";  //4
                $targetFile = $targetPath . $_FILES["file_upload_cover"]["name"];  //5
                move_uploaded_file($tempFile, $targetFile); //6
                $image = $relativePath . $_FILES["file_upload_cover"]["name"];
            }
        }
        $image_resize = $relativePath . 'coverresize' . $_FILES["file_upload_cover"]["name"];

        imageresize::resize_image(Yii::getPathOfAlias('webroot') . $image, null, 1000, 315, false, Yii::getPathOfAlias('webroot') . $image_resize, false, false, 100);
        $this->retVal->message = Yii::app()->createUrl($image_resize);
        $user_cover = User::model()->findByAttributes(array('user_id' => Yii::app()->session["user_id"]));
        $user_cover->user_cover = $image_resize;
        $user_cover->save(FALSE);
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
