<?php
/**
 * @var $this \yii\web\View
 */

use yii\widgets\ActiveForm;

$this->title = '站点地图生成';
$this->params['title'] = $this->title;
?>
<?php $form = ActiveForm::begin([
]) ?>
<table class="table table-bordered table-editor" rules="all">

    <tr>
        <th>
            <label class="control-label">文件</label>
        </th>
        <td>
            <input type="hidden" name="SiteMapForm[files]" value="">
            <div id="sitemapform-files">
                <label>xml</label>
                <br>
                <label>txt</label>
            </div>
        </td>
    </tr>
    <tr class="button-row">
        <th width="120"></th>
        <td><button type="submit" class="btn btn-submit"><?= Yii::t('app.admin', '立即提交') ?></button></td>
    </tr>

</table>
<?php ActiveForm::end() ?>

