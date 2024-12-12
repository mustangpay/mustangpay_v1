<?php
/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = Yii::t('app.admin', '数据库备份');
$this->params['title'] = $this->title;
$actionId = 'backup';
?>
<style>
    .layui-layer-content {
        padding: 0 25px;
    }
    .layui-layer-content h3 {
        text-align: center;
        margin: 10px;
    }
</style>
<div class="box-tool mar-btm clearfix">
    <ul class="nav nav-tabs">
        <li class="active"><a href="<?= Url::to(['tables'])?>"><?= Yii::t('app.admin', '数据备份')?></a></li>
        <li><a href="<?= Url::to(['backup'])?>"><?= Yii::t('app.admin', '数据还原')?></a></li>
    </ul>
    <br>
    <div class="inline">
        <?= Html::a(Yii::t('app.admin', '立即备份'), 'javascript:;', ['class' => 'btn btn-warning tables','data-type' => 1]) ?>
        <?= Html::a(Yii::t('app.admin', '修复表'), 'javascript:;', ['class' => 'btn btn-primary tables','data-type' => 2]) ?>
        <?= Html::a(Yii::t('app.admin', '优化表'), 'javascript:;', ['class' => 'btn btn-info tables','data-type' => 3]) ?>
        <?= Html::a(Yii::t('app.admin', 'Markdown数据字典'), 'javascript:;', ['class' => 'btn btn-success dictionary']) ?>
    </div>
</div>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'id' => 'grid',
    'columns' => [
        [
            'class' => \yii\grid\CheckboxColumn::class,
            'checkboxOptions' => function($model , $key , $index , $column){
                return ['value' => $model['name'] , 'class' => 'checkbox'];
            }
        ],
        [
            'header' => Yii::t('app.admin', '表备注'),
            'attribute' => 'comment',
        ],
        [
            'header' => Yii::t('app.admin', '表名'),
            'attribute' => 'name',
        ],
        [
            'header' => Yii::t('app.admin', '类型'),
            'attribute' => 'engine',
        ],
        [
            'header' => Yii::t('app.admin', '记录总数'),
            'attribute' => 'rows',
        ],
        [
            'header' => Yii::t('app.admin', '数据大小'),
            'format' => ['shortSize', 2],
            'attribute' => 'data_length',
        ],

        [
            'header' => Yii::t('app.admin', '编码'),
            'attribute' => 'collation',
        ],
        [
            'class' => 'app\components\ActionColumn',
            'template' => '{repair} {optimize}',
            'item' => $actionId,
            'buttons' => [
                'repair' => function ($url, $model, $key) {
                    return Html::a(Yii::t('app.admin', '修复表'), 'javascript:;', ['class' => 'btn btn-primary repair' , 'data-table' => $model['name']]);
                },
                'optimize' => function ($url, $model, $key) {
                    return Html::a(Yii::t('app.admin', '优化表'), 'javascript:;', ['class' => 'btn btn-info optimize' , 'data-table' => $model['name']]);
                },
            ],
        ]
    ]
]);?>

<?php
$export = Url::to(['backup-create']);
$optimize = Url::to(['optimize']);
$repair = Url::to(['repair']);
$dictionary = Url::to(['dictionary']);

$this->registerJs(<<<JS
var tableNames = [];
$('.tables').click(function(){
    var dataType = $(this).attr('data-type');
    tableNames = $("#grid").yiiGridView("getSelectedRows");
    console.log(tableNames);
    if(dataType == 1){
        layer.msg('备份中,请不要关闭本页面');
        Export();
    } else if(dataType == 2){
        layer.msg('修复中,请不要关闭本页面');
        Repair();
    } else if(dataType == 3){
        layer.msg('优化中,请不要关闭本页面');
        Optimize();
    }
})
$('.repair').click(function(){
    tableNames = $(this).attr('data-table');
    Repair();
})
$('.optimize').click(function(){
    tableNames = $(this).attr('data-table');
    Optimize();
})
//数据字典
$('.dictionary').click(function(){
    tableNames = $("#grid").yiiGridView("getSelectedRows");
    $.ajax({
        type : "POST",
        url : "{$dictionary}",
        data:{"tables" : tableNames},
        dataType : "json",
        success:function(res){
            //自定页
            layer.open({
                type: 1,
                title: '数据字典',
                skin: 'layui-layer-demo', //样式类名
                closeBtn: false, //不显示关闭按钮
                shift: 2,
                area: ['800px', '80%'],
                shadeClose: true, //开启遮罩关闭
                content: res.data.str
            });
            $('table').addClass('table');
        }
    })
})
//备份表
function Export(){
    $.ajax({
        type : "POST",
        url : "{$export}",
        data:{"tables" : tableNames},
        dataType : "json",
        success:function(res){
            layer.alert(res.message);
        }
    })
}
//修复表
function Repair(){
    $.ajax({
        type : "POST",
        url : "{$repair}",
        data:{"tables" : tableNames},
        dataType : "json",
        success:function(res){
            layer.alert(res.message);
        }
    })
}
//优化表
function Optimize(){
    $.ajax({
        type : "POST",
        url : "{$optimize}",
        data:{"tables" : tableNames},
        dataType : "json",
        success:function(res){
            layer.alert(res.message);
        }
    })
}

JS
);

?>

