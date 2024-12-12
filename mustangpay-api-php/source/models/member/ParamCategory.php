<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%param_category}}".
 *
 * @property int $id
 * @property int|null $order_by 排序
 * @property string|null $title 标题
 * @property int $status 状态
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 编辑时间
 */
class ParamCategory extends BaseActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%param_category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['order_by', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_by' => '排序',
            'title' => '标题',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '编辑时间',
        ];
    }
}
