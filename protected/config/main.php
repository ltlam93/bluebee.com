<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'My Web Application',
    // preloading 'log' component
    'preload' => array('log'),
    'defaultController' => 'ListOfSubject',
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.components.Helper.Date.*',
        'application.components.Helper.String.*',
        'application.components.Helper.Validator.*',
        'application.components.Helper.Email.*',
        'application.components.Helper.Permission.*',
        'application.components.Helper.File.*',
        'application.components.Helper.Array.*',
        'ext.giix-components.*', // giix components
        'ext.Paginator.*', // giix components
        'ext.YiiMailer.YiiMailer',
        'ext.xupload.XUpload',
        'ext.xupload.eguiders',
        "ext.Core.controllers.*",
        "ext.Core.models.*",
        "ext.Core.app.*",
        "ext.AdminTable.*"
        
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool

        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => '1',
        // If removed, Gii defaults to localhost only. Edit carefully to taste.
        //'ipFilters'=>array('127.0.0.1','::1'),
        ),
        'admin' => array ("defaultController" => "home"),
        
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        'request' => array(
            'enableCookieValidation' => true,
        // 'enableCsrfValidation' => true,
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                '<module:\w+>/<controller:\w+>/<id:\d+>' => '<module>/<controller>/view',
                '<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<module>/<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                #'<controller:\w+>/<action:\w+>/<id:\d+>/<sex:\d+>'=>'<controller>/<action>',
                #'<controller:\w+>/<action:\w+>/<id:\d+>/<sex:\d+>/<userid:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        'db' => array(
            'connectionString' => 'sqlite:' . dirname(__FILE__) . '/../data/testdrive.db',
        ),
        // uncomment the following to use a MySQL database
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=bluebee',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ),
//            'db'=>array(
//			'connectionString' => 'mysql:host=localhost;dbname=bluebee_uet',
//			'emulatePrepare' => true,
//			'username' => 'bluebee_acc',
//			'password' => 'minhhieu',
//			'charset' => 'utf8',
//		),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
             */
            ),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => include (dirname(__FILE__).'/params.php'),
    //theme
    'theme' => 'classic',
);
