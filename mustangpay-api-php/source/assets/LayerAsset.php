<?php

namespace app\assets;

use yii\web\AssetBundle;

class LayerAsset extends AssetBundle
{
    public $basePath = '@webroot/static/plugins';
    public $baseUrl = '@web/static/plugins';

    public $js = [
        'layer/layer.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
    public function registerAssetFiles($view)
    {
        $view->registerJs(<<<JS
$.fn.linkConfirm = function (msg) {
    $(this).on("click", function (e) {
        e.preventDefault();
        var url = $(this).attr("href");
        var dlg = layer.confirm(msg, function () {
            layer.close(dlg);
            window.location.href = url;
        });
    });
};
$(".item-delete").linkConfirm("确定要删除该项？");
window.alert = layer.msg;
JS
   , $view::POS_END);
        parent::registerAssetFiles($view);
    }


}
