<?php

namespace app\components\validators;

use yii\helpers\Json;
use yii\validators\Validator;

class MobileValidator extends Validator
{
    public $allowEmpty = true;
    public $mobilePrefix = array(13, 14, 15, 16, 17, 18, 19);
    public $mobilePattern = '/^(\d{2})\d{9}$/';
    public $message = '无效的手机号码';

    public function validate($value, &$error = null)
    {
        if (preg_match($this->mobilePattern, "$value", $matches)) {
            if (!in_array($matches[1], $this->mobilePrefix)) {
                $error = $this->message;
                return false;
            }
        } else {
            $error = $this->message;
            return false;
        }
        return true;
    }

    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        if ($this->allowEmpty && $this->isEmpty($value)) {
            return;
        }

        if (!$this->validate($value, $message)) {
            $this->addError($model, $attribute, $message);
        }
    }

    public function clientValidateAttribute($model, $attribute, $view)
    {
        $pattern = strtr($this->mobilePattern, ['(\d{2})' => '(' . implode('|', $this->mobilePrefix) . ')']);
        $message = Json::encode($this->message);
        $js = <<<EOF
if (!value.match($pattern)) {
	messages.push($message);
}
EOF;

        if ($this->allowEmpty) {
            $js = <<<EOF
if(jQuery.trim(value)!='') {
	$js
}
EOF;
        }

        return $js;
    }
}
