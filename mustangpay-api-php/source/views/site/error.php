<?php
/**
 * @var $this yii\web\View
 * @var $name string
 * @var $message string
 * @var $exception Exception
 */
use yii\helpers\Html;
$this->title = $name;
?>

<style>
	body{background: #f1f1f1;}
	.site-error{width: 1200px;margin:100px auto; text-align: center; padding: 50px;background: #fff;}
	.site-error h1{font-size: 24px;}
	.site-error .alert{padding: 30px 0; font-size: 14px;}
	.site-error p{line-height: 24px;}
</style>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        The above error occurred while the Web server was processing your request.
    </p>
    <p>
        Please contact us if you think this is a server error. Thank you.
    </p>

</div>
