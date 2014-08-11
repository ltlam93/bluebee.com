<?php

class BbbackendModule extends CWebModule {

    public function init() {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
        $this->setImport(array(
            'bbbackend.models.*',
            'bbbackend.components.*',
        ));
        $this->layoutPath = Yii::getPathOfAlias('bbbackend.views.layouts');
        $this->layout = 'main';
    }

    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here
             $controller->layout = 'main';
            return true;
        } else
            return false;
    }

}
