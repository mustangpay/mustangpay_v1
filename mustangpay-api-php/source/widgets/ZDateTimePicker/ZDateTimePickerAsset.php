<?php

namespace app\widgets\ZDateTimePicker;

use yii\web\AssetBundle;

class ZDateTimePickerAsset extends AssetBundle
{
    public $css = [
    	'css/jquery.datetimepicker.css',
    ];
    public $js = [                    
        'jquery.datetimepicker.full.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
    public function init()
    {
        $this->sourcePath = __DIR__ . '/dist';
        parent::init();
    }
}