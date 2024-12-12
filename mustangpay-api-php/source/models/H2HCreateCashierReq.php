<?php

namespace app\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * H2HCreateCashierReq represents the data structure for creating a cashier request.
 */
class H2HCreateCashierReq extends Model
{
    public $merchantName;
    public $country;
    public $currency;
    public $reference;
    public $amount;
    public $callbackUrl;
    public $returnUrl;
    public $cancelUrl;
    public $ip;
    public $product;
    public $productList;
    public $payMethods;
    public $expireAt = 30;
    public $vat;
    public $vatNumber;
    public $sn;
    public $metadata;
    public $remark;
    public $merchantId;
    public $businessType;
    public $payType;
    public $bankCardNo;
    public $cardExpiryDate;
    public $cardCvv;
    public $email;
    public $orderNo;
    public $firstName;
    public $middleName;
    public $lastName;
    public $mobile;
    public $bankCode;
    public $bankName;

    /**
     * Define validation rules for the model
     */
    public function rules()
    {
        return [
            [['merchantName', 'country', 'currency', 'reference', 'amount', 'product'], 'required', 'message' => '{attribute} is required'],
            [['reference'], 'string', 'max' => 255],
            [['amount', 'vat'], 'safe'], // Assuming Amount is another model (this should be validated accordingly)
            [['callbackUrl', 'returnUrl', 'cancelUrl'], 'string', 'max' => 255],
            [['sn', 'merchantId', 'businessType', 'payType'], 'string', 'max' => 255],
            [['expireAt'], 'integer', 'min' => 1, 'max' => 43200, 'message' => 'Expire time must be between 1 and 43200 minutes'],
            [['bankCardNo'], 'string', 'max' => 19],
            [['cardExpiryDate'], 'string', 'max' => 32],
            [['cardCvv'], 'string', 'max' => 4],
            [['email'], 'email'],
            [['firstName', 'middleName', 'lastName'], 'string', 'max' => 50],
            [['mobile'], 'string', 'max' => 32],
            [['bankCode'], 'string', 'max' => 32],
            [['bankName'], 'string', 'max' => 64],
            [['metadata'], 'safe'], // assuming it's a key-value map, can also use 'array' validation
            [['productList'], 'safe'], // assuming it's a list of products
            [['payMethods'], 'safe'], // assuming it's a list of pay methods
        ];
    }

    /**
     * @return array the list of attribute labels
     */
    public function attributeLabels()
    {
        return [
            'merchantName' => 'Merchant Name',
            'country' => 'Country',
            'currency' => 'Currency',
            'reference' => 'Reference',
            'amount' => 'Amount',
            'callbackUrl' => 'Callback URL',
            'returnUrl' => 'Return URL',
            'cancelUrl' => 'Cancel URL',
            'ip' => 'IP Address',
            'product' => 'Product',
            'productList' => 'Product List',
            'payMethods' => 'Payment Methods',
            'expireAt' => 'Expire Time (minutes)',
            'vat' => 'VAT Amount',
            'vatNumber' => 'VAT Number',
            'sn' => 'SN',
            'metadata' => 'Metadata',
            'remark' => 'Remark',
            'merchantId' => 'Merchant ID',
            'businessType' => 'Business Type',
            'payType' => 'Payment Type',
            'bankCardNo' => 'Bank Card No',
            'cardExpiryDate' => 'Card Expiry Date',
            'cardCvv' => 'Card CVV',
            'email' => 'Email',
            'orderNo' => 'Order No',
            'firstName' => 'First Name',
            'middleName' => 'Middle Name',
            'lastName' => 'Last Name',
            'mobile' => 'Mobile',
            'bankCode' => 'Bank Code',
            'bankName' => 'Bank Name',
        ];
    }
}
