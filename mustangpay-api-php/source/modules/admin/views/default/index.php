<?php
/**
 * @var $this \yii\web\View
 * @var $dateArr array
 * @var $countArr array
 * @var $uvArr array
 * @var $pvArr array
 */
use yii\helpers\Url;
use app\models\Param;
$this->context->layout = 'admin';

$this->title = Yii::t('app.admin', '首页');
$this->params['main-class'] = 'index-wrapper';

\yii\bootstrap\BootstrapPluginAsset::register($this);

?>
<div class="box-content">
    <div class="box-site">
        <h3><?= Param::getValue('site_name') ?></h3>
        <div class="box-btn">
            <a class="visit" href="<?= Url::to(['/site/index']);?>"  target="_blank"><i></i><?= Yii::t('app.admin', '访问') ?></a>
        </div>
    </div>
    <ul class="box-link">
    </ul>
</div>
<div class="box-content config-item" style="margin-top: 20px;" id="environment">
    <h4 class="tit">环境配置</h4>
    <table class="table table-striped table-bordered">
        <tbody>
            <tr>
                <td>PHP版本</td>
                <td><?= phpversion();?></td>
            </tr>
            <tr>
                <td>Mysql版本</td>
                <td><?= Yii::$app->db->pdo->getAttribute(\PDO::ATTR_SERVER_VERSION); ?></td>
            </tr>
            <tr>
                <td>解析引擎</td>
                <td><?= $_SERVER['SERVER_SOFTWARE']; ?></td>
            </tr>
            <tr>
                <td>数据库大小</td>
                <td><?= Yii::$app->formatter->asShortSize($mysql_size, 2); ?></td>
            </tr>
            <tr>
                <td>超时时间</td>
                <td><?= ini_get('max_execution_time'); ?>秒</td>
            </tr>
            <tr>
                <td>客户端信息</td>
                <td><?= $_SERVER['HTTP_USER_AGENT'] ?></td>
            </tr>
        </tbody>
    </table>
</div>
<div class="box-content" style="margin-top: 20px;">
    <div class="content">
        <div class="chart1" id="chart1" style="width: 100%; height: 302px;"></div>
    </div>
</div>

<?php
$echatTitle = Yii::t('app.admin', '网站访问统计');
$this->registerJsFile('@web/static/admin/js/echarts.js', ['depends' => 'app\modules\admin\assets\AdminAsset']);
$this->registerJs(<<<JS

var myChart1 = echarts.init(document.getElementById('chart1'));
myChart1.setOption({
    title:{
        text:'',
    },
    tooltip:{},
    legend:{
        
    },
    xAxis: {
        type: 'category',
        boundaryGap: false,
        data: {$dateArr}
    },
    yAxis: {
        type: 'value',
        min: 0,
        max: {$countArr['pv']},
        splitNumber: 3
    },
    grid: {
        left: '3%',
        top: '10%',
        right: '3%',
        bottom: '14%',
    },
    series: [
        { 
            'name' : 'uv',
            data: {$uvArr},
            type: 'line',
        },
        {
            'name' :'pv',
            data: {$pvArr},
            type: 'line',
        },
    ]
});

// 屏幕改变大小图表跟随改变打小
window.addEventListener("resize", function() {
    myChart1.resize();
});
JS
);