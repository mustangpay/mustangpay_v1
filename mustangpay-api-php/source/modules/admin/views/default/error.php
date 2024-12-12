<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h2><?= Html::encode($this->title) ?></h2>

    <div class="alert alert-danger h4">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        <?= Yii::t('app.admin', 'Web服务器处理您的请求时发生上述错误。') ?>
    </p>
    <p>
        <?= Yii::t('app.admin', '如果您认为这是服务器错误，请联系我们。谢谢！') ?>
    </p>

</div>
