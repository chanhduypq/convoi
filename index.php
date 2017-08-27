<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following line when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);

require_once($yii);
try{
    Yii::createWebApplication($config)->run();
}
catch (Exception $exception){
    echo '<meta charset="utf-8" />website này đang được phát triển. Vui lòng quay lại sau.';
    exit;
}
 
