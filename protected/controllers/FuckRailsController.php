<?php

class FuckRailsController extends Controller {
    public function actionIndex() {
        $this->actionFuckRails();
    }

    public function actionFuckRails() {
        $this->render('FuckRails');
    }
}

