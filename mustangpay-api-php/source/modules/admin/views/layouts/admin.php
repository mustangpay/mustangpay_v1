<?php
/**
 * @var $this \yii\web\View
 * @var $leftMenus array
 * @var $content string
 *
 */

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\Menu;
use \app\modules\admin\assets\AdminAsset;
AdminAsset::register($this);

$this->title = $this->title . Yii::t('app.admin', '{title}系统后台' , ['title' => ' - x-sure - ']);
$leftMenus = ArrayHelper::getValue(Yii::$app->params , 'leftMenus' , []);
?>

<?php $this->beginContent('@app/modules/admin/views/layouts/base.php'); ?>
<!--//头部-->
<!--header-wrapper-->
<div class="header-wrapper">
    <div class="container">
        <ul class="left">
            <li class="hidden"><i class="icon computer"></i>
                <?= Html::a(Yii::t('app.admin', '站点浏览'), ['/site/index'], ['class' => 'update', 'target' => '_blank']) ?>
            </li>
        </ul>
        <ul class="right">
            <li><a href="javascript:;"><?= ArrayHelper::getValue(Yii::$app->user->identity, 'username', '无') ?></a></li>
            <li><?= Html::a(Yii::t('app.admin', '清理缓存'), ['default/clear-cache']) ?></li>
            <li><?= Html::a(Yii::t('app.admin', '修改密码'), ['default/reset-pwd']) ?></li>
            <li><?= Html::a(Yii::t('app.admin', '退出'), ['default/logout'], ['class' => 'exit']) ?></li>
        </ul>
    </div>
</div>
<!--/header-wrapper-->

<!--left-menu-wrap-->
<div class="left-menu-wrap">
    <div class="logo">
        <?= Html::a(Html::img('@web/static/admin/images/logo.png') , ['/site/index'] , ['target' => '_blank']) ?>
    </div>
    <div class="col-l">
        <div class="nav-list">
            <?php foreach ($leftMenus as $item):?>
                <div class="nav-item">
                    <?php if(!empty($item['label'])):?>
                        <h3><?= Yii::t('app.admin', $item['label']) ?></h3>
                    <?php endif;?>
                    <?= Menu::widget([
                        'encodeLabels' => false,
                        'activateParents' => true,
                        'options' => ['class' => 'list'],
                        'items' => $item['items'] ?? [],
                    ]) ?>
                </div>
            <?php endforeach;?>
        </div>
        <div class="version">V 1.0.0</div>
    </div>
</div>
<!--/left-menu-wrap-->
<!--main-wrapper-->
<div class="main-wrapper <?= ArrayHelper::getValue($this->params, 'main-class', 'list-wrapper') ?>">
    <?php if(Yii::$app->controller->action->id == 'index'):?>
        <?= $content ?>
    <?php else:?>
        <div class="box-content">
            <?php if (isset($this->params['title'])): ?>
                <h4 class="tit"><?= $this->params['title'] ?></h4>
            <?php endif; ?>
            <div class="content">
                <?= $content ?>
            </div>
        </div>
    <?php endif;?>
</div>
<!--/main-wrapper-->
<!--//底部-->
<!--footer-wrapper-->
<div class="footer-wrapper">
    <div class="container">
        <p>Powered by x-sure © <?= date('Y') ?> <?= Yii::t('app.admin', '版权所有') ?></p>
    </div>
</div>
<!--/footer-wrapper-->


<style>
    .modal {
        z-index: 1050;
    }
</style>
<!--ajax模拟框加载-->
<div class="modal fade" id="ajaxModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <?= Html::img('@web/static/admin/images/loading.gif', ['class' => 'loading']) ?>
                <span>加载中... </span>
            </div>
        </div>
    </div>
</div>
<!--ajax大模拟框加载-->
<div class="modal fade" id="ajaxModalLg" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <?= Html::img('@web/static/admin/images/loading.gif', ['class' => 'loading']) ?>
                <span>加载中... </span>
            </div>
        </div>
    </div>
</div>
<!--ajax最大模拟框加载-->
<div class="modal fade" id="ajaxModalMax" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 80%">
        <div class="modal-content">
            <div class="modal-body">
                <?= Html::img('@web/static/admin/images/loading.gif', ['class' => 'loading']) ?>
                <span>加载中... </span>
            </div>
        </div>
    </div>
</div>
<!--初始化模拟框-->
<div id="ModalBody" class="hide">
    <div class="modal-body">
        <?= Html::img('@web/static/admin/images/loading.gif', ['class' => 'loading']) ?>
        <span>加载中... </span>
    </div>
</div>
<?php
$this->registerJs(<<<JS

// 模拟框
$('#ajaxModal,#ajaxModalLg,#ajaxModalMax').on('hide.bs.modal', function (e) {
    if (e.target == this) {
        $(this).removeData("bs.modal");
        $(this).find('.modal-content').html($('#ModalBody').html());
    }
}).on('shown.bs.modal', function (e) {});
JS
);
?>
<?php $this->endContent(); ?>
