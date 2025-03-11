<?php
/**
 * @var $this \yii\web\View
 * @var $data array
 */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$url = is_array($data['url']) ? Url::to($data['url']) : $data['url'];
$iconClassList = [
    'success' => 'fa fa-check fa-lg',
    'danger' => 'fa fa-close fa-lg',
    'info' => 'fa fa-info fa-lg',
];
$this->title = Yii::t('app.view','系统提示');
$supMsg = Yii::t('app.view','点击这里返回上一页');
$homeMsg = Yii::t('app.view','点击这里返回首页');
?>
<style type="text/css">

    .alert-success {
        color: #3c763d;
        background-color: #dff0d8;
        border-color: #d6e9c6;
    }
    .alert-danger {
        color: #a94442;
        background-color: #f2dede;
        border-color: #ebccd1;
    }
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
        text-align: center;
    }
</style>
<div class="row mar-top" >
    <div class="col-md-8 col-md-offset-2">
        <div class="alert alert-<?= $data['type'] ?>">
            <h4 class="mar-btm">
                <i class="<?= ArrayHelper::getValue($iconClassList, $data['type']) ?>"></i>
                <?= $data['message'] ?>
            </h4>
            <?php if ($url): ?>
                <p><?= Html::a(Yii::t('app.view','如果您的浏览器没有自动跳转，请点击此链接'), $url, ['class' => 'alert-link']) ?></p>
                <script type="text/javascript" reload="1">
                    setTimeout("window.location.href ='<?= $url; ?>';", <?= $data['time']; ?>);
                </script>
            <?php else: ?>
                <script type="text/javascript">
                    var browser = {}, ua = navigator.userAgent.toLowerCase();
                    browser.firefox = /firefox/.test(ua);
                    browser.chrome = /chrome/.test(ua);
                    browser.opera = /opera/.test(ua);
                    browser.ie = /msie/.test(ua);
                    //IE 11的userAgent版本为Trident 7.0
                    if(!browser.ie) browser.ie = /trident 7.0/.test(ua);

                    if (history.length > (browser.ie ? 0 : 1)) {
                        document.write('<p><a href="javascript:history.back()" class="alert-link">[ <?= $supMsg;?> ]</a></p>');
                    } else {
                        document.write('<p"><a href="./" class="alert-link">[ <?= $homeMsg;?> ]</a></p>');
                    }
                </script>
            <?php endif; ?>
        </div>
    </div>
</div>