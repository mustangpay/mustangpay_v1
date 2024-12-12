<?php

namespace app\components;

use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ListColumn extends DataColumn
{
    /**
     * @var array 用于取得数组的值
     */
    public $list = [];

    public $optionsList = [];

    public $format = 'raw';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->filter = $this->list;
    }

    /**
     * @inheritdoc
     */
    public function getDataCellValue($model, $key, $index)
    {
        $value = parent::getDataCellValue($model, $key, $index);

        return Html::tag('span', ArrayHelper::getValue($this->list, $value, $value), ArrayHelper::getValue($this->optionsList, $value, []));
    }
}
