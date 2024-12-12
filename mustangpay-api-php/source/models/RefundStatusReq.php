<?php

namespace app\models;

use yii\base\Model;
use yii\validators\RequiredValidator;

class RefundStatusReq extends Model
{
    public $merchantId;
    public $merchantOrderNo;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // 设定字段验证规则
            [['merchantId', 'merchantOrderNo'], 'required', 'message' => '{attribute} is empty'],
        ];
    }

    /**
     * 自定义验证方法
     */
    public function validateAttributes()
    {
        if (!$this->validate()) {
            return false;
        }

        return true;
    }

    /**
     * 获取商户 ID
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * 设置商户 ID
     * @param string $merchantId
     */
    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;
    }

    /**
     * 获取商户订单号
     * @return string
     */
    public function getMerchantOrderNo()
    {
        return $this->merchantOrderNo;
    }

    /**
     * 设置商户订单号
     * @param string $merchantOrderNo
     */
    public function setMerchantOrderNo($merchantOrderNo)
    {
        $this->merchantOrderNo = $merchantOrderNo;
    }
}
