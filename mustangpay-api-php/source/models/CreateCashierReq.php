<?php

namespace app\models;

use yii\base\Model;
use yii\validators\EmailValidator;
use yii\validators\NumberValidator;
use yii\validators\StringValidator;
use yii\validators\RequiredValidator;
use yii\validators\UrlValidator;
use yii\base\DynamicModel;
use yii\helpers\ArrayHelper;

/**
 * CreateCashierReq represents the data structure and validation rules
 * for the request to create a cashier.
 */
class CreateCashierReq extends Model
{
    public $merchantName;
    public $country;
    public $currency;
    public $reference;
    public $amount; // Type of Amount class
    public $callbackUrl;
    public $returnUrl;
    public $cancelUrl;
    public $ip;
    public $product; // Type of Product class
    public $productList; // List of ProductItem
    public $payMethods;
    public $expireAt = 30;
    public $vat;
    public $vatNumber;
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

    //payMethods

    /**
     * Define validation rules for the model
     */
    public function rules()
    {
        return [
            [['merchantName', 'country', 'currency'], 'string'],
            [['reference'], 'required', 'message' => 'Reference is empty'],
            [['amount'], 'validateAmount'],
            [['callbackUrl', 'returnUrl', 'cancelUrl', 'ip'], 'string'],
            [['product'], 'validateProduct'],
            [['payMethods'], 'each', 'rule' => ['string']],
            [['expireAt'], 'integer', 'min' => 1, 'max' => 43200, 'tooSmall' => 'expireAt min 1 minute', 'tooBig' => 'expireAt max 30 days'],
            [['vat'], 'validateAmount'],
            [['vatNumber'], 'string'],
            [['metadata'], 'validateMetadata'],
            [['remark'], 'string'],
            [['merchantId', 'businessType', 'payType'], 'string'],
            [['bankCardNo'], 'string', 'max' => 19],
            [['cardExpiryDate'], 'string', 'max' => 32],
            [['cardCvv'], 'string', 'max' => 4],
            [['email'], 'email'],
            [['orderNo'], 'string'],
            [['firstName', 'middleName', 'lastName'], 'string', 'max' => 50],
            [['mobile'], 'string', 'max' => 32],
            [['bankCode'], 'string', 'max' => 32],
            [['bankName'], 'string', 'max' => 64],
        ];
    }

    /**
     * Custom validation for the Amount field
     */
    public function validateAmount($attribute, $params)
    {
        if (!$this->$attribute instanceof Amount) {
            $this->addError($attribute, 'Invalid amount');
        }
    }

    /**
     * Custom validation for the Product field
     */
    public function validateProduct($attribute, $params)
    {
        if (!$this->$attribute instanceof Product) {
            $this->addError($attribute, 'Invalid product');
        }
    }

    /**
     * Custom validation for metadata
     */
    public function validateMetadata($attribute, $params)
    {
        if (!is_array($this->$attribute)) {
            $this->addError($attribute, 'Metadata must be an array');
        }
    }

    /**
     * @return array the list of attributes to be ignored during serialization (JSON or array)
     */
    public function fields()
    {
        return ArrayHelper::merge(parent::fields(), [
            // Optionally add any fields to exclude from the JSON serialization here.
            'amount' => function ($model) {
                return $model->amount ? $model->amount->toArray() : null; // Assuming Amount is an object
            },
            'product' => function ($model) {
                return $model->product ? $model->product->toArray() : null; // Assuming Product is an object
            },
            'productList' => function ($model) {
                return $model->productList ? array_map(function ($item) { return $item->toArray(); }, $model->productList) : [];
            }
        ]);
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
            'reference' => 'Merchant Order No',
            'amount' => 'Amount',
            'callbackUrl' => 'Callback URL',
            'returnUrl' => 'Return URL',
            'cancelUrl' => 'Cancel URL',
            'ip' => 'Pay User Client IP',
            'product' => 'Product Info',
            'productList' => 'Product List',
            'payMethods' => 'Pay Methods',
            'expireAt' => 'Expire Time (minutes)',
            'vat' => 'VAT Amount',
            'vatNumber' => 'VAT Number',
            'metadata' => 'Metadata',
            'remark' => 'Remark',
            'merchantId' => 'Merchant ID',
            'businessType' => 'Business Type',
            'payType' => 'Pay Type',
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
