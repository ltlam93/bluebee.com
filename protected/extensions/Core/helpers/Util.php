<?php

if (!function_exists('http_response_code')) {
    function http_response_code($code = NULL) {

        if ($code !== NULL) {

            switch ($code) {
                case 100: $text = 'Continue'; break;
                case 101: $text = 'Switching Protocols'; break;
                case 200: $text = 'OK'; break;
                case 201: $text = 'Created'; break;
                case 202: $text = 'Accepted'; break;
                case 203: $text = 'Non-Authoritative Information'; break;
                case 204: $text = 'No Content'; break;
                case 205: $text = 'Reset Content'; break;
                case 206: $text = 'Partial Content'; break;
                case 300: $text = 'Multiple Choices'; break;
                case 301: $text = 'Moved Permanently'; break;
                case 302: $text = 'Moved Temporarily'; break;
                case 303: $text = 'See Other'; break;
                case 304: $text = 'Not Modified'; break;
                case 305: $text = 'Use Proxy'; break;
                case 400: $text = 'Bad Request'; break;
                case 401: $text = 'Unauthorized'; break;
                case 402: $text = 'Payment Required'; break;
                case 403: $text = 'Forbidden'; break;
                case 404: $text = 'Not Found'; break;
                case 405: $text = 'Method Not Allowed'; break;
                case 406: $text = 'Not Acceptable'; break;
                case 407: $text = 'Proxy Authentication Required'; break;
                case 408: $text = 'Request Time-out'; break;
                case 409: $text = 'Conflict'; break;
                case 410: $text = 'Gone'; break;
                case 411: $text = 'Length Required'; break;
                case 412: $text = 'Precondition Failed'; break;
                case 413: $text = 'Request Entity Too Large'; break;
                case 414: $text = 'Request-URI Too Large'; break;
                case 415: $text = 'Unsupported Media Type'; break;
                case 500: $text = 'Internal Server Error'; break;
                case 501: $text = 'Not Implemented'; break;
                case 502: $text = 'Bad Gateway'; break;
                case 503: $text = 'Service Unavailable'; break;
                case 504: $text = 'Gateway Time-out'; break;
                case 505: $text = 'HTTP Version not supported'; break;
                default:
                    exit('Unknown http status code "' . htmlentities($code) . '"');
                break;
            }

            $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');

            header($protocol . ' ' . $code . ' ' . $text);

            $GLOBALS['http_response_code'] = $code;

        } else {

            $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);

        }

        return $code;

    }
}

function l($category,$message,$param3=null)
{
    if($param3==null)
        return Yii::t($category,$message);
    else if(!is_array($param3))
        return Yii::t($category,$message,$param3);
    $arr = array();
    foreach($param3 as $k => $v){
        $arr["{".$k."}"] = $v;
    }
    return t($category,$message,$arr);
}

function ll($category,$message,$param3=null)
{
    echo l($category,$message,$param3);
}

function t($category,$message,$param3=null)
{
    if($param3!=null)
        return Yii::t($category,$message,$param3);
    else
        return Yii::t($category,$message);
}

class Util
{
    public static function generateRandomString($length = 10,$customAppend="") {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString . $customAppend;
    }

    public static function sendMail($to,$subject,$view,$params)
    {
        if($view!="empty"){
            $template = Template::model()->findByName($view);
            if($template!=null){
                self::sendMailWithContent($to,$subject,$template->applyParams($params));
                return;
            }
        }

        $mail = new YiiMailer($view, $params);
                
        //set properties
        $setting = Yii::app()->params["accounts"]["mail"];
        $mail->setFrom($setting["adminEmail"], $setting["admin"]);
        $mail->setSubject($subject);
        $mail->setTo($to);

        $mail->IsSMTP();
        $mail->Host       = $setting["host"];
        $mail->SMTPDebug  = 0;                     
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = $setting["smtp"]["secure"];
        $mail->Host       = $setting["smtp"]["host"];
        $mail->Port       = $setting["smtp"]["port"];
        $mail->Username   = $setting["smtp"]["username"];
        $mail->Password   = $setting["smtp"]["password"];

        //send
        if ($mail->send()) {
            return;
        } else {
            echo "Error while sending email: ".$mail->getError();
            return false;
        }
    }

    public static function sendMailWithContent($to,$subject,$content){
        self::sendMail($to,$subject,"empty",array(
            "content" => $content
        ));
    }

    public static function param($name,$default=false)
    {   
        if(isset(Yii::app()->params[$name]))
        {
            return Yii::app()->params[$name];
        }
        return $default;
    }
    
    public static function session($name,$default=false)
    {   
        if(isset(Yii::app()->session[$name]))
        {
            return Yii::app()->session[$name];
        }
        return $default;
    }

    public static function setSession($name,$value){
        Yii::app()->session[$name] = $value;
    }

    public static function deleteSession($name){
        if(isset(Yii::app()->session[$name]))
            unset(Yii::app()->session[$name]);
    }

    public static function date($timestamp)
    {
        $dateTime = new DateTime();
        $dateTime->setTimestamp($timestamp);
        return $dateTime;
    }

    public static function getFirstError($model){
        foreach($model->getErrors() as $errorsOfAttr){
            return $errorsOfAttr[0];
        }
    }
}

