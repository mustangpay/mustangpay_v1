<?php
/**
 * @var $this \yii\web\View
 * @var $model \app\models\User
 */
use yii\widgets\ActiveForm;

$this->title = ($model->isNewRecord ? '添加' : '编辑') . '用户';
$this->params['title'] = $this->title;
?>
<?php $form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data',
    ],
    'validateOnSubmit' => true,
]) ?>
<table class="table table-bordered table-editor" rules="all">
    <?= $form->field($model, 'username') ?>
    <?= $form->field($model, 'status')->dropDownList(Yii::$app->params['list']['qy_ty'],['style' => 'width: 80px;']) ?>
    <tr class="button-row">
        <th width="120"></th>
        <td><button type="submit" class="btn btn-submit"><?= Yii::t('app.admin', '立即提交') ?></button></td>
    </tr>
</table>
<?php ActiveForm::end() ?>

