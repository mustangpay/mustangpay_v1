<?php
/**
 * @var $this \yii\web\View
 * @var $model \app\models\Article
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$prefix = $model->isNewRecord ? Yii::t('app.admin', '添加') : Yii::t('app.admin', '编辑');
$this->title = Yii::t('app.admin', '{prefix}分类', ['prefix' => '查看']);
$this->params['title'] = $this->title;
?>
<?php $form = ActiveForm::begin() ?>
<table class="table table-bordered table-editor" rules="all">
    <?= $form->field($model, 'order_by')->text() ?>
    <?= $form->field($model, 'title')->text() ?>
    <?= $form->field($model, 'status')->dropDownList(Yii::$app->params['list']['qy_ty'], ['style' => 'width: 80px;']) ?>
</table>
<?php ActiveForm::end() ?>


