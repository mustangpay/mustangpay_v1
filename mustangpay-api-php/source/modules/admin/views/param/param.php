<?php
/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $searchModel \app\modules\admin\models\searchs\ParamSearch
 */
use yii\grid\GridView;
use yii\helpers\Html;
use app\models\Param;
use app\models\ParamCategory;
use yii\helpers\ArrayHelper;

$this->title = '设置管理';
$this->params['title'] = $this->title;
$actionId = 'param';
?>
<div class="box-tool mar-btm clearfix">
    <div class="box-button pull-right">
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> 添加', [$actionId . '-create'], ['class' => 'btn btn-add']) ?>
    </div>
</div>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'class' => 'yii\grid\SerialColumn',
        ],
        [
            'attribute' => 'order_by',
            'filter' => false,
        ],
        [
            'label' => '类别',
            'attribute' => 'category.title',
            'filter' => Html::activeDropDownList($searchModel, 'cate_id', ParamCategory::map(), [
                    'prompt' => '全部',
                    'class' => 'form-control'
                ]
            ),
        ],
        'code',
        'name',
        [
            'attribute' => 'typeName',
            'filter' => Html::activeDropDownList($searchModel, 'type', Param::getInputList() , [
                    'prompt' => '全部',
                    'class' => 'form-control'
                ]
            ),
        ],
        [
            'attribute' => 'status',
            'filter' => Html::activeDropDownList($searchModel, 'status', Yii::$app->params['list']['qy_ty'], [
                    'prompt' => '全部',
                    'class' => 'form-control'
                ]
            ),
            'value' => function($model){
                return Yii::$app->params['list']['qy_ty'][$model->status];
            },
        ],
        [
            'attribute' => 'updated_at',
            'filter' => false,
            'format' =>  ['date' , 'php:Y-m-d H:i:s']
        ],
        [
            'class' => 'app\components\ActionColumn',
            'item' => $actionId,
        ]

    ],
]);?>

