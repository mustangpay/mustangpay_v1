<?php
/**
 * @var $this \yii\web\View
 * @var $model \app\modules\admin\models\ChangePwdForm
 */

use yii\widgets\ActiveForm;

$this->title = Yii::t('app.admin', '修改密码');
$this->params['title'] = $this->title;
?>
<?php $form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data',
    ],
    'validateOnSubmit' => true,
]) ?>

<table class="table table-bordered table-editor" rules="all">
    <?= $form->field($model, 'oldPassword')->passwordInput() ?>
    <?= $form->field($model, 'password')->passwordInput() ?>
    <?= $form->field($model, 'passwordRepeat')->passwordInput() ?>
    <tr class="button-row">
        <th></th>
        <td><button type="submit" class="btn btn-submit"><?= Yii::t('app.admin', '立即提交') ?></button></td>
    </tr>
</table>
<?php ActiveForm::end() ?>
