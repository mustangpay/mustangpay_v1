<?php

namespace app\models;

use yii\base\Model;
use yii\validators\RequiredValidator;

/**
 * CreateRefundReq Model.
 */
class CreateRefundReq extends Model
{
    public $merchantId;
    public $merchantName;
    public $merchantOrderNo;
    public $originalMerchantOrderNo;
    public $amount;
    public $country;
    public $remark;
    public $businessType;
    public $callbackUrl;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchantId', 'merchantOrderNo', 'originalMerchantOrderNo', 'country', 'businessType', 'callbackUrl'], 'required', 'message' => '{attribute} is empty'],
            [['merchantName', 'remark'], 'string'],
            [['amount'], 'safe'], // Assuming Amount can be an object, we treat it as safe data
        ];
    }

    /**
     * 设置金额
     * @param Amount $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * 获取金额
     * @return Amount
     */
    public function getAmount()
    {
        return $this->amount;
    }
}
