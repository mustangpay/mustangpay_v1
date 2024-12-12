<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;

/**
 * CashierCreateResp model class
 */
class CashierCreateResp extends Model
{
    public $reference;
    public $orderNo;
    public $orderStatus;
    public $amount;  // You can define it as Amount model or a custom type if needed
    public $vat;     // You can define it as Amount model or a custom type if needed
    public $errorCode;
    public $errorMessage;
    public $cashierUrl;
    public $merchantId;
    public $redirectPayUrl;
    public $redirectType;
    public $redirectPayParam;  // This can be an array or custom model

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['reference', 'orderNo', 'orderStatus', 'errorCode', 'errorMessage', 'cashierUrl', 'merchantId', 'redirectPayUrl', 'redirectType'], 'string'],
            [['amount', 'vat'], 'safe'], // Assuming Amount is another class/model
            [['redirectPayParam'], 'safe'],  // Can be an array, a Map or JSON data
        ];
    }

    /**
     * @return array the attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'reference' => 'Merchant Order No',
            'orderNo' => 'Trade Order No',
            'orderStatus' => 'Order Status',
            'amount' => 'Order Amount (without VAT)',
            'vat' => 'VAT Amount',
            'errorCode' => 'Error Code',
            'errorMessage' => 'Error Message',
            'cashierUrl' => 'Cashier URL',
            'merchantId' => 'Merchant ID',
            'redirectPayUrl' => 'Redirect Pay URL',
            'redirectType' => 'Redirect Type',
            'redirectPayParam' => 'Redirect Pay Param',
        ];
    }

    /**
     * Convert the `amount` and `vat` properties to Amount objects if needed.
     *
     * @param Amount $amount
     * @param Amount $vat
     * @return void
     */
    public function setAmountAndVat($amount, $vat)
    {
        $this->amount = $amount; // Assuming Amount is a valid model
        $this->vat = $vat;       // Assuming Amount is a valid model
    }

    /**
     * Set redirectPayParam as an associative array or JSON.
     *
     * @param array $params
     * @return void
     */
    public function setRedirectPayParam($params)
    {
        $this->redirectPayParam = is_array($params) ? $params : json_decode($params, true);
    }

    /**
     * Get the redirectPayParam as an associative array or JSON string.
     *
     * @return array|string
     */
    public function getRedirectPayParam()
    {
        return is_array($this->redirectPayParam) ? $this->redirectPayParam : json_decode($this->redirectPayParam, true);
    }
}
