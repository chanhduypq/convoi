<?php

return array(
    // This path may be different. You can probably get it from `config/main.php`.
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'Immo-Share',

    'preload'=>array('log'),
    'sourceLanguage' => 'en',
    'language' => 'de',

    'import'=>array(
        'application.components.*',
        'application.models.*',
        'ext.YiiMailer.YiiMailer',
        'application.extensions.*'
    ),
    // We'll log cron messages to the separate files
    'components'=>array(
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'logFile'=>'cron.log',
                    'levels'=>'error, warning, info',
                ),
                array(
                    'class'=>'CFileLogRoute',
                    'logFile'=>'cron_trace.log',
                    'levels'=>'trace',
                ),
            ),
        ),
        'func' => array(
            'class' => 'application.components.Functions',
        ),
        'urlManager'=>array(
            'urlFormat'=>'path',
            'showScriptName' => false,
            'rules'=>require(dirname(__FILE__).'/url.php'),
        ),

        // Your DB connection
        'db' =>  require(dirname(__FILE__).'/db.php'),
    ),
    'params'=>require(dirname(__FILE__).'/params.php'),
);