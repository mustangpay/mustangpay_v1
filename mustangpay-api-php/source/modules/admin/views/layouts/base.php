<?php
/**
 * @var $this \yii\web\View
 * @var $content string
 */
use yii\helpers\Html;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language?>">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1" />
    <meta name="renderer" content="webkit" />
    <meta name="format-detection" content="telephone=no" />
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
</head>

<body>

<!--[if lt IE 8]>
<style>
/*IE兼容性*/
.upgradeBrowser{ background:#ffffe1;border-bottom:1px solid #f90;}
.upgradeBrowserBox{width:1000px;margin:0 auto;line-height:46px; text-align:center;color:#f60;}
</style>
<div class="upgradeBrowser">
    <div class="upgradeBrowserBox">
        <span>Hi，您的IE浏览器版本过低，请升级您的浏览器至IE9+，以享受更优质的浏览效果！:)</span>
    </div>
</div>
<![endif]-->
<?php $this->beginBody() ?>
    <?= \app\widgets\notify\Notify::widget()?>
    <?= $content ?>
<?php $this->endBody() ?>

</body>
</html>

<?php $this->endPage() ?>
