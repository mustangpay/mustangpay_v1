<?php
/**
 * Created by zsz
 * User zsz
 * Time: 2024/1/10 14:00
 */

namespace app\components;

use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ImageColumn extends DataColumn
{
    public $format = 'raw';

    /**
     * @inheritdoc
     */
    public function getDataCellValue($model, $key, $index)
    {
        return Html::a(Html::img(ArrayHelper::getValue($model, $this->attribute), ['height' => '50']), ArrayHelper::getValue($model, $this->attribute), ['class' => 'fancybox', 'data-fancybox' => 'gallery']);
    }
}