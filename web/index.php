<?php

// comment out the following two lines when deployed to production
//defined('YII_DEBUG') or define('YII_DEBUG', true);
//defined('YII_ENV') or define('YII_ENV', 'dev');

file_put_contents('tmp.log', $_SERVER['REMOTE_ADDR'] . PHP_EOL, FILE_APPEND);

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
