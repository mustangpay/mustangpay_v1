<?php
/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('app.admin', '数据库备份');
$this->params['title'] = $this->title;
$actionId = 'backup';
?>

<div class="box-tool mar-btm clearfix">
    <div class="box-button pull-right">
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('app.admin', '立即备份'), [$actionId . '-create'], ['class' => 'btn btn-add']) ?>
    </div>
</div>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'id' => 'grid',
    'columns' => [
        [
            'attribute' => 'filename',
            'format' => 'raw',
            'header' => Yii::t('app.admin', '文件名'),
            'value' => function ($model, $key) {
                return '<i class="ri-file-3-fill align-bottom mr-1"></i>' . $key;
            }
        ],
        [
            'attribute' => 'size',
            'format' => ['shortSize', 2],
            'header' => Yii::t('app.admin', '文件大小'),
        ],
        [
            'attribute' => 'fileTime',
            'format' => ['date', 'php:Y-m-d H:i'],
            'header' => Yii::t('app.admin', '建立时间'),
        ],
        [
            'class' => 'app\components\ActionColumn',
            'template' => '{restore} {download} {delete}',
            'item' => $actionId,
            'buttons' => [
                'restore' => function ($url, $model, $key) {
                    return Html::a(Yii::t('app.admin', '还原'), $url , ['class' => 'btn  btn-primary btn_restore']);
                },
                'download' => function ($url, $model, $key) {
                    return Html::a(Yii::t('app.admin', '下载'), $url, ['class' => 'btn btn-edit btn_download']);
                },
            ],
        ]
    ]
]);?>

<?php
$this->registerJs(<<<JS
$(".btn-add").linkConfirm("确定要备份数据库吗？");
$(".btn_download").linkConfirm("确定要下载该项？");
$(".btn_restore").linkConfirm("确定要还原该项？");
JS
 , $this::POS_LOAD)
?>