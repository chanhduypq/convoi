<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'MNS�',
    'theme' => 'admin',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.extensions.*',
        'application.modules.admin.models.*',
        'application.modules.admin.components.*',
    ),
    'sourceLanguage' => 'en',
    'language' => 'vi',
    

    'defaultController' => 'index',
    // application components
    'aliases' => array(
        'helpers' => 'application.widgets',
        'widgets' => 'application.widgets',
    ),
    'components' => array(
        
        'ePdf' => array(
            'class'         => 'ext.yii-pdf.EYiiPdf',
            'params'        => array(
                'mpdf'     => array(
                    'librarySourcePath' => 'application.vendors.mpdf.*',
                    'constants'         => array(
                        '_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
                    ),
                    'class'=>'mpdf', // the literal class filename to be loaded from the vendors folder
                    /*'defaultParams'     => array( // More info: http://mpdf1.com/manual/index.php?tid=184
                        'mode'              => '', //  This parameter specifies the mode of the new document.
                        'format'            => 'A4', // format A4, A5, ...
                        'default_font_size' => 0, // Sets the default document font size in points (pt)
                        'default_font'      => '', // Sets the default font-family for the new document.
                        'mgl'               => 15, // margin_left. Sets the page margins for the new document.
                        'mgr'               => 15, // margin_right
                        'mgt'               => 16, // margin_top
                        'mgb'               => 16, // margin_bottom
                        'mgh'               => 9, // margin_header
                        'mgf'               => 9, // margin_footer
                        'orientation'       => 'P', // landscape or portrait orientation
                    )*/
                ),
                'HTML2PDF' => array(
                    'librarySourcePath' => 'application.vendors.html2pdf.*',
                    'classFile'         => 'html2pdf.class.php', // For adding to Yii::$classMap
                    /*'defaultParams'     => array( // More info: http://wiki.spipu.net/doku.php?id=html2pdf:en:v4:accueil
                        'orientation' => 'P', // landscape or portrait orientation
                        'format'      => 'A4', // format A4, A5, ...
                        'language'    => 'en', // language: fr, en, it ...
                        'unicode'     => true, // TRUE means clustering the input text IS unicode (default = true)
                        'encoding'    => 'UTF-8', // charset encoding; Default is UTF-8
                        'marges'      => array(5, 5, 5, 8), // margins by default, in order (left, top, right, bottom)
                    )*/
                )
            ),
        ),
        
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        'settings' => array(
            'class' => 'XSettings',
        ),
        'Smtpmail' => array(
            'class' => 'application.extensions.smtpmail.PHPMailer',
            'Host' => 'mail.mns.vn',
            'Username' => 'notification@mns.vn',
            'Password' => 'Mns123!@#',
//            'Host' => 'smtp.gmail.com',
//            'Username' => 'chanhduypq@gmail.com',
//            'Password' => 'buddha0808',
            'Mailer' => 'smtp',
//                'Port'=>587,
            'SMTPAuth' => true,
            'Port' => 25,
//            'Port' => 465,
//            'SMTPSecure' => 'ssl',
        ),
        'image'=>array(    
                'class'=>'application.extensions.image.CImageComponent',           
                'driver'=>'GD',
        ),
        'String' => array(
            'class' => 'application.extensions.common.String',
        ),
        'Scan' => array(
            'class' => 'application.extensions.common.Scan',
        ),
        'Advertise' => array(
            'class' => 'application.extensions.common.Advertise',
        ),
        'NumberToWords' => array(
            'class' => 'application.extensions.common.NumberToWords',
        ),
        'nodeSocket' => array(
            'class' => 'application.extensions.yii-node-socket.yii-node-socket.lib.php.NodeSocket',
            'host' => 'localhost',  // default is 127.0.0.1, can be ip or domain name, without http
            'port' => 3001      // default is 3001, should be integer
        ),
        'db' => require(dirname(__FILE__) . '/db.php'),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'index/error',
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => require(dirname(__FILE__) . '/url.php'),
        ),
//        'log' => array(
//            'class' => 'CLogRouter',
//            'routes' => array(
//                array(
//                    'class' => 'CFileLogRoute',
//                    'levels' => 'error, warning, info',
//                ),
//            // uncomment the following to show log messages on web pages
//
//            /* array(
//              'class'=>'CWebLogRoute',
//              'enabled' => YII_DEBUG,
//              ), */
//            ),
//        ),
        'messages' => array(
            'class' => 'CDbMessageSource',
            'cacheID' => 'cache',
            'onMissingTranslation' => array('MissingMessages', 'load'),
        ),
        'func' => array(
            'class' => 'application.components.Functions',
        ),
        'email' => array(
            'class' => 'application.extensions.email.Email',
            'view' => 'email',
            'viewVars' => array(),
            'layout' => 'main',
        ),
        'geoip' => array(
            'class' => 'application.extensions.PcMaxmindGeoIp.PcMaxmindGeoIp',
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => require(dirname(__FILE__) . '/params.php'),
);
