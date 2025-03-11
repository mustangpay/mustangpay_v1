<?php

namespace app\models;

use yii\base\Model;

/**
 * MerchantOrderStatusReq represents the request data for checking merchant order status.
 */
class MerchantOrderStatusReq extends Model
{
    public $merchantId;
    public $merchantOrderNo;

    /**
     * Define validation rules for the model
     */
    public function rules()
    {
        return [
            [['merchantId', 'merchantOrderNo'], 'required', 'message' => '{attribute} is required'],
            [['merchantId', 'merchantOrderNo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @return array the list of attribute labels
     */
    public function attributeLabels()
    {
        return [
            'merchantId' => 'Merchant ID',
            'merchantOrderNo' => 'Merchant Order No',
        ];
    }
}
