<?php
/**
 * @var $this \yii\web\View
 * @var $models Param[]
 * @var $list array
 * @var $cate_id integer
 */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\ArrayHelper;
use \app\models\Param;
use app\helpers\StringHelper;
use app\helpers\Helper;

$this->title = ArrayHelper::getValue($list['cate'] , $cate_id);
$this->params['title'] = $this->title;
?>
<ul class="nav nav-tabs" role="tablist">
    <?php foreach ($list['cate'] as $k => $item):?>
    <li class="<?= $cate_id == $k ? 'active' : false;?>">
        <a href="<?= Url::to(['param/set' , 'cate_id' => $k]) ?>">
            <p><?= $item;?></p>
        </a>
    </li>
    <?php endforeach;?>
</ul>
<style>
    .hint-block{
        color: #ff1c1c;
    }
</style>
<?php $form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data',
    ]
]) ?>

    <table class="table table-bordered table-editor">
        <?php foreach ($models as $model): ?>
            <?php if ($model->type == 'password'): ?>
                <?= $form->field($model, "[{$model->code}]value")->label($model->name)->passwordInput() ?>
            <?php elseif ($model->type == 'secretKeyText'): ?>
                <?php $btn = Html::tag('span' , Html::tag('span' , Yii::t('app.admin', '生成新的') , ['class' => 'btn btn-edit' , 'onclick' => "createKey({$model->extra} , {$model->id})"]) , ['class' => 'input-group-btn']);;?>
                <?= $form->field($model, "[{$model->code}]value")->label($model->name)->textInput(['id' => $model->id])->hint($model->hint . $btn)  ?>
            <?php elseif ($model->type == 'radio'): ?>
                <?= $form->field($model, "[{$model->code}]value")->label($model->name)->radioList(StringHelper::parseAttr($model->extra))->hint($model->hint)  ?>
            <?php elseif ($model->type == 'file'): ?>
                <?= $form->field($model, "[{$model->code}]value")->label($model->name)->fileInput(['class' => false])->hint($model->value ? Html::img(Helper::getFullUrl($model->value), ['height' => '200px']) . Html::a(Yii::t('app.admin', '删除'), ['/admin/default/cover-delete', 'id' => $model->id, 'table' => 'param', 'field' => 'value'], ['class' => 'item-delete btn btn-delete']) : '')?>
            <?php elseif ($model->type == 'textarea'): ?>
                <?= $form->field($model, "[{$model->code}]data")->label($model->name)->textarea()->hint($model->hint) ?>
            <?php elseif ($model->type == 'kinder'): ?>
                <?= $form->field($model, "[{$model->code}]data")->label($model->name)->fullEditor()->hint($model->hint) ?>
            <?php else: ?>
                <?= $form->field($model, "[{$model->code}]value")->label($model->name)->textInput()->hint($model->hint) ?>
            <?php endif; ?>

        <?php endforeach; ?>
        <tr class="button-row">
            <th width="120"></th>
            <td>
                <?= Html::submitButton(Yii::t('app.admin', '立即提交'), ['class' => 'btn btn-submit']) ?>
            </td>
        </tr>
    </table>
<?php ActiveForm::end() ?>
<script>
    function createKey(num, id) {
        let letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let token = '';
        for (let i = 0; i < num; i++) {
            let j = parseInt(Math.random() * 61 + 1);
            token += letters[j];
        }
        $("#" + id).val(token);
    }
</script>
