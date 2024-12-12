<?php
/**
 * @link      https://github.com/wbraganca/yii2-tagsinput
 * @copyright Copyright (c) 2015 Wanderson Bragança
 * @license   https://github.com/wbraganca/yii2-tagsinput/blob/master/LICENSE
 */

namespace app\widgets\tagsinput;

/**
 * Asset bundle for tagsinput Widget
 *
 * @author Wanderson Bragança <wanderson.wbc@gmail.com>
 */
class TagsinputAsset extends \yii\web\AssetBundle
{
    public $css = [
        'bootstrap-tagsinput.css',
    ];

    public $js = [
        'bootstrap-tagsinput.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset'
    ];
    public function init()
    {
        $this->sourcePath = __DIR__ . '/dist';
        parent::init();
    }
}
