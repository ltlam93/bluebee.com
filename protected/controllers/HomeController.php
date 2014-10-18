<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class HomeController extends Controller {

    public function actionIndex() {
        $this->pageTitle = "Everything you need to become UETer ! | Bluebee - UET  ";
        Yii::app()->clientScript->registerMetaTag("Everything you need to become UETer ! | Bluebee - UET ", null, null, array('property' => 'og:title'));
        $this->actionHome();
    }

    public function actionHome() {
        $this->render('Home');
    }

}
