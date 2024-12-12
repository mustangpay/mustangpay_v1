<?php
/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = Yii::t('app.admin', '单页管理');
$this->params['title'] = $this->title;
$actionId = 'page';
?>
<div class="box-tool mar-btm clearfix">
    <div class="box-button pull-right">
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> 添加', [$actionId . '-create'], ['class' => 'btn btn-add']) ?>
    </div>
</div>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'order_by',
        'title',
        'code',
        [
            'class' => 'app\components\ActionColumn',
            'item' => $actionId,
            'template' => '{update}',
        ]
    ]
]);