<?php
/**
 * @var $this \yii\web\View
 * @var $model \app\models\Article
 */
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
$prefix = $model->isNewRecord ? Yii::t('app.admin', '添加') : Yii::t('app.admin', '编辑');
$this->title = Yii::t('app.admin', '{prefix}文章', ['prefix' => $prefix]);
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
    <?= $form->field($model, 'tags')->tagsInput() ?>
    <?= $form->field($model, 'coverFile')->fileInput() ?>
    <?= $form->field($model, 'coversFile[]')->fileInput(['multiple' => 'multiple']) ?>
    <?= $form->field($model, 'summary')->textarea(['rows' => 5]) ?>
    <?= $form->field($model, 'content')->fullEditor() ?>
    <?= $form->field($model, 'status')->dropDownList(Yii::$app->params['list']['qy_ty'], ['style' => 'width: 80px;']) ?>
    <tr class="button-row">
        <th width="120"></th>
        <td><button type="submit" class="btn btn-submit"><?= Yii::t('app.admin', '立即提交') ?></button></td>
    </tr>
</table>
<?php ActiveForm::end() ?>

