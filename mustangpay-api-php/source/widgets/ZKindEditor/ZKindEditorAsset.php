<?php
namespace app\widgets\ZKindEditor;

use yii\web\AssetBundle;

class ZKindEditorAsset extends AssetBundle
{

    public $js = [
        //YII_DEBUG ? 'kindeditor.js' : 'kindeditor-min.js',
        'kindeditor-min.js',
    ];
    public $css = [
    	'themes/default/default.css',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/dist';
        parent::init();
    }
}