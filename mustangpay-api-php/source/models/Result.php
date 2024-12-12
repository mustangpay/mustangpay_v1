<?php

namespace app\models;

use yii\base\BaseObject;

/**
 * Result represents a response result with a code, message, and data.
 */
class Result extends BaseObject
{
    public $code;
    public $msg;
    public $data;

    /**
     * Constructor for the Result class
     */
    public function __construct($code = null, $msg = null, $data = null, $config = [])
    {
        parent::__construct($config);
        $this->code = $code;
        $this->msg = $msg;
        $this->data = $data;
    }

    /**
     * Create a failure result with a custom message
     */
    public static function fail($msg)
    {
        $result = new self();
        $result->setCode("000001");
        $result->setMsg($msg);
        return $result;
    }

    /**
     * Create a success result with data
     */
    public static function success($data)
    {
        $result = new self();
        $result->setCode("000000");
        $result->setMsg("success");
        $result->setData($data);
        return $result;
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

    // Getters and Setters for code, msg, and data
    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setMsg($msg)
    {
        $this->msg = $msg;
    }

    public function getMsg()
    {
        return $this->msg;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}
