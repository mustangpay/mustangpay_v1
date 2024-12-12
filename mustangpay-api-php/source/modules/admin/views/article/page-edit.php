<?php
/**
 * @var $this \yii\web\View
 * @var $model \app\models\Page
 */

use yii\widgets\ActiveForm;
$prefix = $model->isNewRecord ? Yii::t('app.admin', '添加') : Yii::t('app.admin', '编辑');
$this->title = Yii::t('app.admin', '{prefix}页面', ['prefix' => $prefix]);
$this->params['title'] = $this->title;
?>
<?php $form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data',
    ],
    'validateOnSubmit' => true,
]) ?>

    <table class="table table-bordered table-editor" rules="all">
        <?= $form->field($model, 'order_by') ?>
        <?= $form->field($model, 'title')->textInput(['disabled' => $model->isNewRecord ? false : true]) ?>
        <?= $form->field($model, 'code')->textInput(['disabled' => $model->isNewRecord ? false : true]) ?>
        <?= $form->field($model, 'content')->fullEditor() ?>
        <?= $form->field($model, 'status')->dropDownList(Yii::$app->params['list']['qy_ty']) ?>
        <tr class="button-row">
            <th></th>
            <td><button type="submit" class="btn btn-submit"><?= Yii::t('app.admin', '立即提交') ?></button></td>
        </tr>
    </table>
<?php ActiveForm::end() ?>
