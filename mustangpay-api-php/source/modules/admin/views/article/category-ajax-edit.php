<?php
/**
 * @var $this \yii\web\View
 * @var $model \app\models\Article
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$prefix = $model->isNewRecord ? Yii::t('app.admin', '添加') : Yii::t('app.admin', '编辑');
$this->title = Yii::t('app.admin', '{prefix}分类', ['prefix' => $prefix]);
$this->params['title'] = $this->title;
?>
<?php $form = ActiveForm::begin([
    'id' => $model->formName(),
    'options' => [
        'enctype' => 'multipart/form-data',
    ],
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['category-ajax-update', 'id' => $model['id']]),
]) ?>

<div class="modal-header">
    <h4 class="modal-title"><?= $this->title;?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
    <table class="table table-bordered table-editor" rules="all">
        <?= $form->field($model, 'order_by')->textInput()->hint(Yii::t('app.admin', '数字越大越靠前')) ?>
        <?= $form->field($model, 'title')->textInput() ?>
        <?= $form->field($model, 'status')->dropDownList(Yii::$app->params['list']['qy_ty'], ['style' => 'width: 80px;']) ?>
        <tr class="button-row">
            <th width="120"></th>
            <td><button type="submit" class="btn btn-submit"><?= Yii::t('app.admin', '立即提交') ?></button></td>
        </tr>
    </table>
</div>
<?php ActiveForm::end() ?>


