<?php

namespace app\models;

use yii\base\Model;

/**
 * GatewayEncryptReq represents the data structure for the request to encrypt gateway data.
 */
class GatewayEncryptReq extends Model
{
    public $merchantId;
    public $encryptData;
    public $encryptKey;

    /**
     * Define validation rules for the model
     */
    public function rules()
    {
        return [
            [['merchantId', 'encryptData', 'encryptKey'], 'required', 'message' => '{attribute} is required'],
            [['merchantId'], 'string', 'max' => 255],
            [['encryptData', 'encryptKey'], 'string'],
        ];
    }

    /**
     * @return array the list of attribute labels
     */
    public function attributeLabels()
    {
        return [
            'merchantId' => 'Merchant ID',
            'encryptData' => 'Encrypted Data',
            'encryptKey' => 'Encrypted Key',
        ];
    }
}
