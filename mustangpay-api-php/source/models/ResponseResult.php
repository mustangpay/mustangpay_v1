<?php

namespace app\models;

use yii\base\BaseObject;


/**
 * ResponseResult represents a generic response result with a code, message, and data.
 */
class ResponseResult extends BaseObject
{
    public $code;
    public $msg;
    public $data;

    /**
     * Constructor for the ResponseResult class
     */
    public function __construct($code = null, $msg = null, $data = null, $config = [])
    {
        parent::__construct($config);
        $this->code = $code;
        $this->msg = $msg;
        $this->data = $data;
    }

    /**
     * Create a successful response with no data
     */
    public static function success()
    {
        return new self(CommonResultEnum::getCode(), CommonResultEnum::getMessage());
    }

    /**
     * Create a successful response with data
     */
    public static function successWithData($data)
    {
        return new self(CommonResultEnum::getCode(), CommonResultEnum::getMessage(), $data);
    }

    /**
     * Create an error response with custom code and message
     */
    public static function error($code, $msg)
    {
        return new self($code, $msg);
    }

    /**
     * Create an error response using BaseResultEnum with data
     */
    public static function errorWithData($baseResultEnum, $data)
    {
        return new self($baseResultEnum->getCode(), $baseResultEnum->getMessage(), $data);
    }

    /**
     * Create an error response using BaseResultEnum
     */
    public static function errorWithEnum($baseResultEnum)
    {
        return new self($baseResultEnum->getCode(), $baseResultEnum->getMessage());
    }

    /**
     * Create an error response with a custom error message
     */
    public static function errorWithMessage($errorMsg)
    {
        return new self(CommonResultEnum::getCode(), $errorMsg);
    }

    /**
     * Define the validation rules for the model
     */
    public function rules()
    {
        return [
            [['code', 'msg'], 'string'],
            ['data', 'safe'],
        ];
    }

    /**
     * Define attribute labels for the model
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Response Code',
            'msg' => 'Response Message',
            'data' => 'Response Data',
        ];
    }
}
