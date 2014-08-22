<?php

Yii::import('application.controllers.BaseController');
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'PHPMailer' . DIRECTORY_SEPARATOR . 'class.phpmailer.php');
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'PHPMailer' . DIRECTORY_SEPARATOR . 'class.pop3.php');
include_once (dirname(__FILE__) . '/../extensions/facebook.php');
Yii::import('ext.ImageResize.imageresize');
Yii::import('application.components.imageresize');

class WelcomePageController extends BaseController {


    public function actionIndex() {
        $this->redirect(Yii::app()->createAbsoluteUrl('listOfSubject'));
    }

//    public function actionWelcomePage() {
//        $this->render('welcomePage');
//    }
//
//    function smtpmailer($to, $from, $from_name, $subject, $body) {
//
//        $mail = new PHPMailer();      // tạo một đối tượng mới từ class PHPMailer
//        $mail->IsSMTP();       // bật chức năng SMTP
//        $mail->CharSet = "UTF-8";
//        $mail->IsHTML(true);
//        //    $mail->SMTPDebug = 1;       // kiểm tra ỗi : 1 là  hiển thị lỗi và thông báo cho ta biết, 2 = chỉ thông báo lỗi
//        $mail->SMTPAuth = true;      // bật chức năng đăng nhập vào SMTP nàyf
//        //$mail->SMTPSecure = 'ssl'; 				// sử dụng giao thức SSL vì gmail bắt buộc dùng cái này
//        $mail->Host = 'localhost';   // smtp của gmail
//        $mail->Port = 25;       // port của smpt gmail
//        $mail->Username = "activate@bluebee-uet.com";
//        $mail->Password = "123456789";
//        $mail->SetFrom($from, $from_name);
//        $mail->Subject = $subject;
//        $mail->Body = $body;
//        $mail->AddAddress($to);
//        if (!$mail->Send()) {
//            $message = 'Gửi mail bị lỗi: ' . $mail->ErrorInfo;
//            return false;
//        } else {
//            $message = 'Thư của bạn đã được gửi đi ';
//            return true;
//        }
//    }

//    public function actionLogin() {
//
//        $this->retVal = new stdClass();
//        $request = Yii::app()->request;
//        if ($request->isPostRequest && isset($_POST)) {
//            try {
//                $loginFormData = array(
//                    'user_name' => @$_POST['username'],
//                    'user_password' => @$_POST['Password'],
//                );
//                if (!empty($loginFormData['user_name'])) {
//                    if (Validator::validateEmail($loginFormData['user_name'])) {
//                        if (!empty($loginFormData['user_password'])) {
//                            $user = User::model()->findByAttributes(array('username' => $loginFormData['user_name']));
//                            if ($user) {
//                                //user existed, check password
//                                if ($user->user_active == 1) {
//                                    if ($user->password == md5($loginFormData['user_password'])) {
//                                        $this->retVal->message = "Đăng nhập thành công";
//                                        Yii::app()->session['user_id'] = $user->user_id;
//                                        Yii::app()->session['user_real_name'] = $user->user_real_name;
//                                        Yii::app()->session['user_email'] = $user->username;
//                                        Yii::app()->session['user_avatar'] = $user->user_avatar;
//
//                                        $this->retVal->success = 1;
//                                        //token
//                                        $token = StringHelper::generateToken(16, 36);
//                                        $user->user_token = $token;
//                                        Yii::app()->session['token'] = $token;
//                                        $user->save(FALSE);
//                                        $this->retVal->token = $token;
//                                        $this->retVal->url = Yii::app()->createUrl("user?token=" . $token);
//                                    } else {
//                                        $this->retVal->message = "Sai tên người dùng hoặc mật khẩu";
//                                        $this->retVal->success = 0;
//                                    }
//                                } else {
//
//                                    $this->retVal->message = "Bạn chưa kích hoạt tài khoản. Hãy kiểm tra email của bạn để kích hoạt nhé";
//                                    $this->retVal->success = 0;
//                                }
//                            } else {
//                                $this->retVal->message = "Tên người dùng chưa được đăng ký";
//                                $this->retVal->success = 0;
//                            }
//                        } else {
//                            $this->retVal->message = "Password";
//                            $this->retVal->success = 0;
//                        }
//                    } else {
//                        $this->retVal->message = "Sai định dạng email";
//                        $this->retVal->success = 0;
//                    }
//                } else {
//                    $this->retVal->message = "Email không được để trống";
//                    $this->retVal->success = 0;
//                }
//            } catch (exception $e) {
//                $this->retVal->message = $e->getMessage();
//            }
//            echo CJSON::encode($this->retVal);
//            //     Yii::app()->end();
//        }
//    }
//
//    public function actionSignUp() {
//        $this->retVal = new stdClass();
//        $request = Yii::app()->request;
//        if ($request->isPostRequest && isset($_POST)) {
//            try {
//                $singupFormData = array(
//                    'user_name' => $_POST['contact_name'],
//                    'user_password' => $_POST['Password'],
//                    'user_email' => $_POST['contact_email'],
//                );
//                if (!empty($singupFormData['user_name'])) {
//                    if (!empty($singupFormData['user_email'])) {
//                        if (!empty($singupFormData['user_password'])) {
//
//                            if (Validator::validateEmail($singupFormData['user_email'])) {
//                                if (Validator::validatePassword($singupFormData['user_password'])) {
//
//                                    $user = User::model()->findByAttributes(array('username' => $singupFormData['user_email']));
//                                    if ($user) {
//                                        $this->retVal->message = "Email đã được đăng ký";
//                                        $this->retVal->success = 0;
//                                    } else {
//                                        $model = new User;
//                                        if ($model) {
//                                            $activator = md5($singupFormData['user_email']);
//                                            $link_activate = Yii::app()->createAbsoluteUrl('welcomePage/activate?token=' . $activator);
//                                            $model->user_real_name = $singupFormData['user_name'];
//                                            $model->password = md5($singupFormData['user_password']);
//                                            $model->username = $singupFormData['user_email'];
//                                            $model->user_activator = $activator;
//                                            $model->user_status = 1;
//                                            $model->user_active = 0;
//                                            $model->user_qoutes = "Học, học nữa, học mãi";
//                                            $model->user_date_attend = date('d/m/Y');
//                                            $model->save(FALSE);
//                                            $res = $this->smtpmailer($singupFormData['user_email'], "activate@bluebee-uet.com", "Email kích hoạt tài khoản BlueBee của bạn", "Kích hoạt tài khoản bluebee của bạn", "Chào bạn " . $singupFormData["user_name"] . "<br/> Đây là đường link kích hoạt tài khoản của bạn: " . $link_activate . "<br/> Chúc bạn học tốt với bluebee");
//                                            if (true) {
//                                                $this->retVal->message = "Đăng kí thành công, hãy kiểm tra tài khoản email của bạn để kích hoạt (chú ý cả thư mục spam)";
//                                                $this->retVal->success = 1;
//                                            }
//                                            // echo $ress;
//                                        } else {
//                                            $this->retVal->message = "Không thể lưu user do lỗi server ";
//                                            $this->retVal->success = 0;
//                                        }
//                                    }
//                                } else {
//                                    $this->retVal->message = "Password phải nhiều hơn 5 kí tự";
//                                    $this->retVal->success = 0;
//                                }
//                            } else {
//                                $this->retVal->message = "Sai định dạng email";
//                                $this->retVal->success = 0;
//                            }
//                        } else {
//                            $this->retVal->message = "Password không được để trống";
//                            $this->retVal->success = 0;
//                        }
//                    } else {
//                        $this->retVal->message = "Email không được để trống";
//                        $this->retVal->success = 0;
//                    }
//                } else {
//                    $this->retVal->message = "Tên hiển thị không được để trống !. Bạn có thể thay đổi tên hiển thị sau khi đăng nhập";
//                    $this->retVal->success = 0;
//                }
//            } catch (exception $e) {
//                $this->retVal->message = $e->getMessage();
//            }
//            echo CJSON::encode($this->retVal);
//            //     Yii::app()->end();
//        }
//
//        //  $this->render('welcomePage/signUp');
//    }

    public function actionLogout() {
        $user_current_token = User::model()->findByAttributes(array('user_id' => Yii::app()->session['user_id']));

        if ($user_current_token) {
            $user_current_token->user_token = "";
            $user_current_token->save(FALSE);
        }

        Yii::app()->session['user_id'] = "";
        Yii::app()->session['user_real_name'] = "";
        Yii::app()->session['user_email'] = "";
        Yii::app()->session['token'] = "";
        Yii::app()->session['user_avatar'] = "";

        $this->redirect(Yii::app()->createUrl('index.php'));
    }
//
//    public function actionActivate() {
//        if (isset($_GET["token"])) {
//            $user_activate = User::model()->findByAttributes(array('user_activator' => $_GET["token"]));
//            if ($user_activate) {
//                $user_activate->user_active = 1;
//                $user_activate->save(FALSE);
//                $this->retVal->message = "Kích hoạt tài khoản thành công, hãy đăng nhập bằng tài khoản của bạn.";
//                $this->retVal->success = 1;
//            }
//        }
//        $this->render('activate');
//    }

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
        // $user = $this->actionFb_login_result();
        //$user_id = User::model()->findByAttributes(array('user_id_fb' => $user["id"]));
        $facebook = $this->getFb();
        $loginUrl = $facebook->getLoginUrl(array(
            'scope' => 'read_stream, publish_stream, user_birthday, user_location, user_work_history, user_hometown, user_photos, email',
        //    'redirect_uri' => "http://bluebee-uet.com/user?token=" . $user_id->user_token,
            "redirect_uri" => Yii::app()->createAbsoluteUrl('welcomePage/fb_login_result')
        ));
        $this->redirect($loginUrl);
    }

    public function actionFb_login_result() {
        $facebook = $this->getFb();
        $access_token = $facebook->getAccessToken();
        $user = $facebook->api("me", "get", array(
            "access_token" => $access_token
        )); //check login tai day
        //print_r($user["id"]);
        //die();
        $user_facebook_exist = User::model()->findByAttributes(array('user_id_fb' => $user["id"]));
        if ($user_facebook_exist) {
            $token = StringHelper::generateToken(16, 36);
            $user_facebook_exist->user_token = $token;
            if (isset($user["name"])) {
                $user_facebook_exist->user_real_name = $user['name'];
            }
            if (isset($user["email"])) {
                $user_facebook_exist->username = $user['email'];
            }

            if (isset($user["quotes"])) {
                $user_facebook_exist->user_qoutes = $user["quotes"];
            }
            $user_facebook_exist->user_dob = $user["birthday"];
            $user_facebook_exist->user_avatar = "http://graph.facebook.com/" . $user["id"] . "/picture?type=large";
            $user_facebook_exist->user_hometown = $user["hometown"]["name"];
            $user_facebook_exist->user_active = 1;
            $user_facebook_exist->save(FALSE);
            Yii::app()->session['user_avatar'] = $user_facebook_exist->user_avatar;
            Yii::app()->session['user_name'] = $user['name'];
            Yii::app()->session['token'] = $token;
            Yii::app()->session['user_id'] = $user_facebook_exist->user_id;
            $this->redirect(Yii::app()->createUrl('user?token=' . $token));
        } else {
            //   echo 'ok';
            //   die();
            $token = StringHelper::generateToken(16, 36);
            $user_facebook = new User;
            $user["password"] = "bluebee_facebook";
            if (isset($user["name"])) {
                $user_facebook->user_real_name = $user['name'];
            }
            if (isset($user["email"])) {
                $user_facebook->username = $user['email'];
            }
            $user_facebook->user_token = $token;
            $user_facebook->user_dob = $user["birthday"];
            $user_facebook->user_hometown = $user["hometown"]["name"];
            $user_facebook->user_avatar = "http://graph.facebook.com/" . $user["id"] . "/picture?type=large";
            Yii::app()->session['user_avatar'] = "http://graph.facebook.com/" . $user["id"] . "/picture?type=large";
            Yii::app()->session['token'] = $token;
            Yii::app()->session['user_name'] = $user['name'];
            $user_facebook->user_id_fb = $user["id"];
            $user_facebook->user_active = 1;
            if (isset($user["quotes"])) {
                $user_facebook->user_qoutes = $user["quotes"];
            }
            $user_facebook->user_date_attend = date('d/m/Y');
            $user_facebook->save(FALSE);
            Yii::app()->session['user_id'] = $user_facebook->user_id;
            //return $user;
            $this->redirect(Yii::app()->createUrl('user?token=' . $token));
        }
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
                'scope' => 'read_stream, publish_stream, user_birthday, user_location, user_work_history, user_hometown, user_photos, email',
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

//    public function actionWelcomePage() {
//        $this->render('index');
//    }
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
