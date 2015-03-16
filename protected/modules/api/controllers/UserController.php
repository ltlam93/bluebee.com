<?php

class UserController extends Controller {

    public $retVal;

    public function actionIndex() {
        $this->render('index');
    }

    public function actionSaveUser() {
        $this->retVal = new stdClass();
        $request = Yii::app()->request;
        if ($request->isPostRequest && isset($_POST['user_facebook_id'])) {
            try {
                $user_id_fb = StringHelper::filterString($request->getPost('user_facebook_id'));

                $check = User::model()->findByAttributes(array('user_id_fb' => $user_id_fb));
                $user_dob = $request->getPost('user_dob');

                if ($check) {
                    $check->user_avatar = "http://graph.facebook.com/" . $user_id_fb . "/picture?type=large";
                    $this->retVal->user_data = $check;
                    $this->retVal->message = 'true';
                } else {
                    $user = new User;
                    $user->user_avatar = "http://graph.facebook.com/" . $user_id_fb . "/picture?type=large";
                    $user->user_dob = $user_dob;

                    if ($user->save(FALSE)) {
                        $this->retVal->user_data = $user;
                        $this->retVal->message = 'true';
                    } else {
                        $this->retVal->message = 'false';
                    }
                }
            } catch (exception $e) {
                $this->retVal->message = $e->getMessage();
            }
            echo CJSON::encode($this->retVal);
            Yii::app()->end();
        }
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
