<?php
/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = Yii::t('app.admin', '数据还原');
$this->params['title'] = $this->title;
$actionId = 'backup';
?>

<div class="box-tool mar-btm clearfix">
    <ul class="nav nav-tabs">
        <li><a href="<?= Url::to(['tables'])?>"><?= Yii::t('app.admin', '数据备份')?></a></li>
        <li class="active"><a href="<?= Url::to(['backup'])?>"><?= Yii::t('app.admin', '数据还原')?></a></li>
    </ul>
</div>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'id'=>'grid',
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
$downStr = Yii::t('app.admin', '确定要下载该项？');
$restoreStr = Yii::t('app.admin', '确定要还原该项？');
$this->registerJs(<<<JS
$(".btn_download").linkConfirm("$downStr");
$(".btn_restore").linkConfirm("$restoreStr");
JS
 , $this::POS_LOAD)
?>