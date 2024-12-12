<?php
// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');


require __DIR__ . '/system/vendor/autoload.php';
require __DIR__ . '/system/vendor/yiisoft/yii2/Yii.php';
/**
 * 打印
 *
 * @param $array
 */
function p(...$array)
{
    echo "<pre>";

    if (count($array) == 1) {
        print_r($array[0]);
    } else {
        print_r($array);
    }

    echo '</pre>';
}
$config = require __DIR__ . '/source/config/test.php';
(new yii\web\Application($config))->run();
