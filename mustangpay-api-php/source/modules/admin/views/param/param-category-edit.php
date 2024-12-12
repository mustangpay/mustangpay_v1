<?php
/**
 * @var $this \yii\web\View
 * @var $model \app\models\ParamCategory
 */
use yii\widgets\ActiveForm;
$this->title = ($model->isNewRecord ? '添加' : '编辑') . '设置分类';
$this->params['title'] = $this->title;
?>

<?php $form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data',
    ],
    'validateOnSubmit' => true,
]) ?>
<table class="table table-bordered table-editor" rules="all">
    <?= $form->field($model, 'order_by')->textInput()->hint('数字越大越靠前') ?>
    <?= $form->field($model, 'title')->textInput() ?>
    <?= $form->field($model, 'status')->dropDownList(Yii::$app->params['list']['qy_ty'] , ['style' => 'width: 80px;'])?>

    <tr class="button-row">
        <th width="120"></th>
        <td><button type="submit" class="btn btn-submit"><?= Yii::t('app.admin', '立即提交') ?></button></td>
    </tr>
</table>
<?php ActiveForm::end() ?>

