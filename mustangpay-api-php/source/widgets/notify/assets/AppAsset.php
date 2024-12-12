<?php
namespace app\widgets\notify\assets;

use yii\web\AssetBundle;
use yii\bootstrap\BootstrapAsset;

class AppAsset extends AssetBundle
{
    public $js = [
        'bootstrap-notify.min.js',
        'lightyear.js',
    ];
    public $depends = [
        BootstrapAsset::class,
    ];

    public function init()
    {
        $this->sourcePath = dirname(__DIR__) . '/resources';
        parent::init();
    }
}