<?php
/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
use yii\helpers\Html;
use yii\grid\GridView;
$this->title = Yii::t('app.admin' ,'分类管理');
$this->params['title'] = $this->title;

$actionId = 'category';
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
        [
            'class' => 'app\components\ListColumn',
            'attribute' => 'status',
            'list' => Yii::$app->params['list']['qy_ty'],
            'optionsList' => Yii::$app->params['colorList']['s_f'],
        ],
        [
            'attribute' => 'created_at',
            'format' => ['date' , 'php:Y-m-d H:i:s']
        ],
        [
            'class' => 'app\components\ActionColumn',
            'item' => $actionId,
        ]

    ],
]);?>

