<?php
/**
 * @var $this \yii\web\View
 * @var $model \app\models\Param
 */
use yii\widgets\ActiveForm;
use app\models\Param;
use app\models\ParamCategory;
$this->title = ($model->isNewRecord ? '添加' : '编辑') . '设置';
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
    <?= $form->field($model, 'code')->textInput() ?>
    <?= $form->field($model, 'cate_id')->dropDownList(ParamCategory::map()) ?>
    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'hint')->textInput() ?>
    <?= $form->field($model, 'value')->textInput() ?>
    <?= $form->field($model, 'type')->dropDownList(Param::getInputList()) ?>
    <?= $form->field($model, 'extra')->textarea()->hint('如果是枚举型 需要配置该项') ?>
    <?= $form->field($model, 'status')->dropDownList(Yii::$app->params['list']['qy_ty'] , ['style' => 'width: 80px;'])?>

    <tr class="button-row">
        <th width="120"></th>
        <td><button type="submit" class="btn btn-submit"><?= Yii::t('app.admin', '立即提交') ?></button></td>
    </tr>
</table>
<?php ActiveForm::end() ?>

