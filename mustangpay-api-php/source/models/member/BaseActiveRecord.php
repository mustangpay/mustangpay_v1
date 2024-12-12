<?php
namespace app\models;

use yii\helpers\ArrayHelper;
use \yii\db\ActiveRecord ;
class BaseActiveRecord extends ActiveRecord
{
    public function behaviors()
    {
        return [
             [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * map
     *
     * @param string $from
     * @param string $to
     * @return array
     */
    public static function map($from = 'id' , $to = 'title')
    {
        $models = static::find()
            ->where(['status' => STATUS_ACTIVE])
            ->all();
        return ArrayHelper::map($models , $from , $to);
    }
	
}