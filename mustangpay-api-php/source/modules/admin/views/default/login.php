<?php
/**
 * @var $this \yii\web\View
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->context->layout = 'base';
$this->title = Yii::t('app.admin','登录');
?>
<div class="login-wrapper">
    <div class="login-box">
        <?php $form = ActiveForm::begin([
            'id' => 'formLogin',
            'fieldConfig' => [
                'template' => '{input}{error}',
                'options' => ['class' => null],
                'inputOptions' => ['class' => null],
            ],
        ]) ?>
            <h3>后台管理系统</h3>
            <div class="ipt-txt">
                <?= $form->field($model, 'username')->textInput(['placeholder' => Yii::t('app.admin','请输入账号')]) ?>
            </div>
            <div class="ipt-txt">
                <?= $form->field($model, 'password')->passwordInput(['placeholder' => Yii::t('app.admin','请输入密码')]) ?>
            </div>
            <div class="ipt-txt verify">
                <?= $form->field($model, 'verifyCode')
                    ->widget(\yii\captcha\Captcha::className(), [
                        'captchaAction' => ['/admin/default/captcha'],
                        'template' => "{input}\n {image}",
                        'options' => [
                            'placeholder' => Yii::t('app.admin','请输入验证码'),
                        ],
                        'imageOptions' => [
                            'class' => 'code',
                            'style' => 'vertical-align:middle; cursor: pointer;',
                            'title' => Yii::t('app.admin','登陆验证码，点击刷新'),
                        ],
                    ]);
                ?>
            </div>
            <?= Html::submitButton('立即登录', ['class' => 'btn-submit', 'id' => 'btnSubmit']) ?>
        <?php ActiveForm::end() ?>
        <p class="foot">Powered by x-sure © 2021 <?= Yii::t('app.admin','版权所有')?></p>
    </div>
    <!--/login-box-->
</div>


<?php
$this->registerCssFile('@web/static/admin/css/login.css');
$this->registerCss(<<<CSS
.help-block{
    color: #ff1c1c;
}   
CSS
);
?>
