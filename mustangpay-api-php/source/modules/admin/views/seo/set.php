<?php
/**
 * @var $this \yii\web\View
 * @var $models \app\models\Seo[]
 *
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app.admin', 'SEO设置');
$this->params['title'] = $this->title;
?>

<div class="box-help">
    <p><?= Yii::t('app.admin', '插入的变量必需使用英文大括号“{}”包裹；当应用范围不支持该变量时，将显示变量名建议使用手写代替关键词。以下是常用SEO变量规则：') ?></p>
    <div class="list">
        <p><b><?= Yii::t('app.admin', '站点名称') ?>：</b>{siteName}</p>
        <p><b><?= Yii::t('app.admin', '默认') ?> SEO Title：</b>{siteTitle}</p>
        <p><b><?= Yii::t('app.admin', '默认') ?> SEO Keywords：</b>{siteKeywords}</p>
        <p><b><?= Yii::t('app.admin', '默认') ?> SEO Description：</b>{siteDescription}</p>
    </div>
    <p class="note"><?= Yii::t('app.admin', '注：当前设置的SEO规则会替换基础SEO配置规范，如需沿用则可留空。') ?></p>
</div>
<?php $form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data',
    ],
    'validateOnSubmit' => true,
]) ?>
<table class="table table-bordered table-editor" rules="all">
    <?php foreach($models as $model): ?>
        <tr class="field-seo-keywords">
            <th width="200">
                <label class="control-label" for="seo-keywords"><?= $model['name'] ?><br></label>
            </th>
            <td class="form">
                <table class="table table-bordered table-sub">
                    <tr>
                        <td width="136">title:</td>
                        <td><?= Html::textInput("Seo[{$model['action']}][title]", $model['title'],['id'=>'seo-action','class'=>'form-control']); ?></td>
                    </tr>
                    <tr>
                        <td>keywords:</td>
                        <td><?= Html::textInput("Seo[{$model['action']}][keywords]", $model['keywords'],['id'=>'seo-keywords','class'=>'form-control']); ?></td>
                    </tr>
                    <tr>
                        <td>description:</td>
                        <td><?= Html::textInput("Seo[{$model['action']}][description]", $model['description'],['id'=>'seo-description','class'=>'form-control']); ?></td>
                    </tr>
                </table>
                <div class="help-block"></div>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr class="button-row">
        <th></th>
        <td>
            <button type="submit" class="btn btn-submit"><?= Yii::t('app.admin', '立即提交') ?></button>
        </td>
    </tr>
</table>
<?php ActiveForm::end() ?>

