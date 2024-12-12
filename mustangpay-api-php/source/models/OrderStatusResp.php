<?php

namespace app\models;

use yii\base\Model;

/**
 * OrderStatusResp represents the response data for checking order status.
 */
class OrderStatusResp extends Model
{
    public $merchantId;
    public $merchantOrderNo;
    public $orderNo;
    public $orderStatus;
    public $merchantName;
    public $vatNumber;
    public $errorCode;
    public $errorMessage;
    public $vat; // Amount type
    public $productName;
    public $productShortName;
    public $productDesc;
    public $amount; // Amount type
    public $returnUrl;
    public $completeTime;
    public $createTime;
    public $updateTime;
    public $payMethod;

    /**
     * Define validation rules for the model
     */
    public function rules()
    {
        return [
            [['merchantId', 'merchantOrderNo', 'orderNo', 'orderStatus', 'merchantName', 'vatNumber', 'errorCode', 'errorMessage', 'productName', 'productShortName', 'productDesc', 'payMethod'], 'string', 'max' => 255],
            [['amount', 'vat'], 'safe'], // Assuming Amount is an object, 'safe' allows it to be passed through
            [['completeTime', 'createTime', 'updateTime'], 'integer'],
            [['returnUrl'], 'url', 'message' => 'Invalid URL format'],
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
            'orderNo' => 'Order No',
            'orderStatus' => 'Order Status',
            'merchantName' => 'Merchant Name',
            'vatNumber' => 'VAT Number',
            'errorCode' => 'Error Code',
            'errorMessage' => 'Error Message',
            'vat' => 'VAT Amount',
            'productName' => 'Product Name',
            'productShortName' => 'Product Short Name',
            'productDesc' => 'Product Description',
            'amount' => 'Amount',
            'returnUrl' => 'Return URL',
            'completeTime' => 'Complete Time',
            'createTime' => 'Create Time',
            'updateTime' => 'Update Time',
            'payMethod' => 'Payment Method',
        ];
    }
}
