<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class HomeController extends Controller {

    public function actionIndex() {
        if (Yii::app()->session['token'] == "") {
            $redirect = $this->redirect('welcomePage');
        }
        $this->actionHome();
    }

    public function actionHome() {
        $this->render('home');
    }

}
