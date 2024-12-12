<?php

namespace app\models;

use yii\base\Model;

/**
 * PageResponseResult represents the paginated response data.
 */
class PageResponseResult extends Model
{
    public $total;
    public $pageNo;
    public $pageSize;
    public $list; // List of items (generic type)

    /**
     * Define validation rules for the model
     */
    public function rules()
    {
        return [
            [['total', 'pageNo', 'pageSize'], 'integer'],
            [['list'], 'safe'], // Assuming list is an array or object, 'safe' allows any data
        ];
    }

    /**
     * @return array the list of attribute labels
     */
    public function attributeLabels()
    {
        return [
            'total' => 'Total',
            'pageNo' => 'Page Number',
            'pageSize' => 'Page Size',
            'list' => 'List of Items',
        ];
    }

    /**
     * Optional: You can add a method to set the list of items, depending on the context
     * @param array $items
     */
    public function setList(array $items)
    {
        $this->list = $items;
    }
}
