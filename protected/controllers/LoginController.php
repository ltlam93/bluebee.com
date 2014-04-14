<?php

Yii::import('application.controllers.BaseController');
Yii::import('application.components.facebook');
Yii::import('application.components.base_facebook');

$facebook_path = Yii::getPathOfAlias('webroot') . '/protected/extensions/facebook.php';
include_once($facebook_path);

//YiiBase::import($facebook_path);

class LoginController extends BaseController {

    public function actionIndex() {
        $this->actionLogin();
    }

    public function actionLogin() {
        $this->retVal = new stdClass();
        $request = Yii::app()->request;
        if ($request->isPostRequest && isset($_POST)) {
            try {
                $loginFormData = array(
                    'user_name' => @$_POST['username'],
                    'user_password' => @$_POST['Password'],
                );
                if (!empty($loginFormData['user_name'])) {
                    if (!empty($loginFormData['user_password'])) {

                        $user = User::model()->findByAttributes(array('user_name' => $loginFormData['user_name']));
                        if ($user) {
                            //user existed, check password
                            if (strcmp($user->user_password, $loginFormData['user_password'] == 0)) {
                                $this->retVal->message = "Dang nhap thanh cong";
                                //     Yii::app()->request->redirect('discussion');
                            } else {
                                //user not existed
                                $this->retVal->message = "Sai ten nguoi dung hoac mat khau";
                            }
                        } else {
                            $this->retVal->message = "Ten nguoi dung chua duoc danh ky";
                        }
                    } else {
                        $this->retVal->message = "Password khong duoc de trong";
                    }
                } else {
                    $this->retVal->message = "User name khong duoc de trong";
                }
            } catch (exception $e) {
                $this->retVal->message = $e->getMessage();
            }
            echo CJSON::encode($this->retVal);
            Yii::app()->end();
        }

        $this->render('Login');
    }

    public function actionSignup() {
        $this->retVal = new stdClass();
        $request = Yii::app()->request;
        if ($request->isPostRequest && isset($_POST)) {
            try {
                $loginFormData = array(
                    'user_name' => $_POST['contact_name'],
                    'user_password' => $_POST['Password'],
                    'user_email' => $_POST['contact_email'],
                );
                if (!empty($loginFormData['user_name'])) {
                    if (!empty($loginFormData['user_email'])) {
                        if (!empty($loginFormData['user_password'])) {
                            if (Validator::validateUsername($loginFormData['user_name'])) {
                                if (Validator::validateEmail($loginFormData['user_email'])) {
                                    if (Validator::validatePassword($loginFormData['user_password'])) {


                                        $user = User::model()->findByAttributes(array('user_name' => $loginFormData['user_name']));
                                        if ($user) {
                                            //user existed, check password
                                            $this->retVal->message = "Tên người dùng đã được đăng ký";
                                        } else {
                                            $user = User::model()->findByAttributes(array('user_email' => $loginFormData['user_email']));
                                            if ($user) {
                                                $this->retVal->message = "Email đã được đăng ký";
                                            } else {
                                                $model = new User;
                                                if ($model) {
                                                    $model->user_name = $loginFormData['user_name'];
                                                    $model->user_password = $loginFormData['user_password'];
                                                    $model->user_email = $loginFormData['user_email'];
                                                    $model->user_status = 1;
                                                    $model->save(FALSE);
                                                    if ($model->save(FALSE)) {
                                                        $this->retVal->message = "Đăng ký thành công, hãy đăng nhập bằng tài khoản của bạn";
                                                    } else {
                                                        $this->retVal->message = "Không thể lưu user do lỗi server";
                                                    }
                                                } else {
                                                    $this->retVal->message = "Không thể lưu user do lỗi server ";
                                                }
                                            }
                                        }
                                    } else {
                                        $this->retVal->message = "Password phải nhiều hơn 5 kí tự";
                                    }
                                } else {
                                    $this->retVal->message = "Sai định dạng email";
                                }
                            } else {
                                $this->retVal->message = "username không được có khoảng trắng và phải nhiều hơn 5 kí tự";
                            }
                        } else {
                            $this->retVal->message = "Password không được để trống";
                        }
                    } else {
                        $this->retVal->message = "Email không được để trống";
                    }
                } else {
                    $this->retVal->message = "Username khong duoc de trong";
                }
            } catch (exception $e) {
                $this->retVal->message = $e->getMessage();
            }
            echo CJSON::encode($this->retVal);
            Yii::app()->end();
        }

        $this->render('login/signup');
    }

    function getFb() {
        $app_id = "1428478800723370";
        $app_secret = "64b21e0ab23ec7db82979f9607065704";
        $site_url = "bluebee-uet.com";

        $facebook = new Facebook(array(
            'appId' => $app_id,
            'secret' => $app_secret,
            "cookie" => true
        ));
        return $facebook;
    }

    public function actionFb_login() {
        $facebook = $this->getFb();
        $loginUrl = $facebook->getLoginUrl(array(
            'scope' => '',
            'redirect_uri' => "http://bluebee-uet.com/discussion",
        ));
        $this->redirect($loginUrl);
    }

    public function actionFb_login_result() {
        $facebook = $this->getFb();
        $access_token = $facebook->getAccessToken();
        $user = $facebook->api("me", "get", array(
            "access_token" => $access_token
        ));
    }

    public function actionloginFacebook() {
        $app_id = "1428478800723370";
        $app_secret = "64b21e0ab23ec7db82979f9607065704";
        $site_url = "bluebee-uet.com";

        $facebook = new Facebook(array(
            'appId' => $app_id,
            'secret' => $app_secret,
        ));

        $user = $facebook->getUser();

        if ($user) {
            try {
                $user_profile = $facebook->api('/me');
            } catch (FacebookApiException $e) {
                error_log($e);
                $user = NULL;
            }
        }

        if ($user) {
            $logoutUrl = $facebook->getLogoutUrl();
        } else {
            $loginUrl = $facebook->getLoginUrl(array(
                'scope' => 'read_stream, publish_stream, user_birthday, user_location, user_work_history, user_hometown, user_photos',
                'redirect_uri' => $site_url,
            ));
            $this->redirect($loginUrl);
        }

        if ($user) {
            $queries = array(
                array('method' => 'GET', 'relative_url' => '/' . $user),
                array('method' => 'GET', 'relative_url' => '/' . $user . '/home?limit=50'),
                array('method' => 'GET', 'relative_url' => '/' . $user . '/friends'),
                array('method' => 'GET', 'relative_url' => '/' . $user . '/photos?limit=6'),
            );

            try {
                $batchResponse = $facebook->api('?batch=' . json_encode($queries), 'POST');
            } catch (Exception $o) {
                error_log($o);
            }

            $user_info = json_decode($batchResponse[0]['body'], TRUE);
            $feed = json_decode($batchResponse[1]['body'], TRUE);
            $friends_list = json_decode($batchResponse[2]['body'], TRUE);
            $photos = json_decode($batchResponse[3]['body'], TRUE);
        }
        $this->render('fb');
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
