<?php

class UserController extends Controller {

    public $retVal;

    public function actionIndex() {
        //echo "UserController.actionIndex";
        //return;
        $this->render('index');
    }

    public function actionListUser() {
        $user = User::model()->findAll();
        echo CJSON::encode($user);
    }

    public function actionEditUser() {
        $this->retVal = new stdClass();
        $request = Yii::app()->request;
        if ($request->isPostRequest && isset($_POST)) {
            try {
                $editFormData = array(
                    'user_id' => $_POST['user_id'],
                    'username' => $_POST['username'],
                    'user_real_name' => $_POST['user_real_name'],
                    'user_date_attend' => $_POST['user_date_attend'],
                    'user_active' => $_POST['user_active'],
                );
//                var_dump($editFormData['user_id']);
//                var_dump($editFormData['username']);
//                exit();
                $user_edit = User::model()->findByAttributes(array('user_id' => $editFormData['user_id']));
                $user_edit->username = $editFormData['username'];
                $user_edit->user_real_name = $editFormData['user_real_name'];
                $user_edit->user_date_attend = $editFormData['user_date_attend'];
                $user_edit->user_active = $editFormData['user_active'];

                if ($user_edit->update(FALSE)) {
                    $this->retVal->message = "Lưu thành công";
                } else {
                    $this->retVal->message = "Có lỗi xảy ra";
                }
            } catch (exception $e) {
                $this->retVal->message = $e->getMessage();
            }
            echo CJSON::encode($this->retVal);
            //     Yii::app()->end();
        }
    }

    public function actionDeleteUser() {
        $this->retVal = new stdClass();
        $request = Yii::app()->request;
        if ($request->isPostRequest && isset($_POST)) {
            try {
                $editFormData = array(
                    'user_id' => $_POST['0'],
                );
//                var_dump($editFormData['user_id']);
//                var_dump($editFormData['username']);
//                exit();
                $user_edit = User::model()->findByAttributes(array('user_id' => $editFormData['user_id']));
                if ($user_edit->delete(FALSE)) {
                    $this->retVal->message = "Xóa thành công";
                } else {
                    $this->retVal->message = "Có lỗi xảy ra";
                }
            } catch (exception $e) {
                $this->retVal->message = $e->getMessage();
            }
            echo CJSON::encode($this->retVal);
            //     Yii::app()->end();
        }
    }

}
