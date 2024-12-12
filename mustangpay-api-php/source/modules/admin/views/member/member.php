<?php
/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $model \app\modules\admin\models\searchs\UserSearch
 */
use yii\grid\GridView;
use \app\models\User;
use \yii\helpers\ArrayHelper;
$this->title = Yii::t('app.admin' ,'用户管理');
$this->params['title'] = $this->title;

$actionId = 'member';
?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $model,
    'columns' => [
        'id',
        'nickname',
        'mobile',
        [
            'class' => 'app\components\ListColumn',
            'attribute' => 'group',
            'list' => User::listGroupNames(),
        ],
        [
            'class' => 'app\components\ListColumn',
            'attribute' => 'status',
            'list' => User::listStatusNames(),
        ],
        [
            'attribute' => 'created_at',
            'filter' => false,
            'format' => ['date' , 'php:Y-m-d H:i:s']
        ],
        [
            'class' => 'app\components\ActionColumn',
            'item' => $actionId,
        ]

    ],
]);

?>

