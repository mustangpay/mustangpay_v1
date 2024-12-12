<?php
/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
use yii\grid\GridView;
use yii\helpers\Html;
$this->title = '设置分类';
$this->params['title'] = $this->title;
$actionId = 'param-category';
?>
<div class="box-tool mar-btm clearfix">
    <div class="box-button pull-right">
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> 添加', [$actionId . '-create'], ['class' => 'btn btn-add']) ?>
    </div>
</div>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'class' => 'yii\grid\SerialColumn',
        ],
        'order_by',
        'title',
        [
            'class' => 'app\components\ListColumn',
            'attribute' => 'status',
            'list' => Yii::$app->params['list']['qy_ty'],
        ],
        [
            'attribute' => 'updated_at',
            'format' => ['date' , 'php:Y-m-d H:i:s']
        ],
        [
            'class' => 'app\components\ActionColumn',
            'item' => $actionId,
        ]

    ],
]);?>

