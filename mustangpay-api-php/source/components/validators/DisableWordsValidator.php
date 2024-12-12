<?php

namespace app\components\validators;

use app\models\Param;
use yii\validators\Validator;

class DisableWordsValidator extends Validator
{
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = '对不起你输入的 {attribute} 含有禁用词';
        }
    }

    public function validateAttribute($model, $attribute)
    {
        $disableWordArr = explode('|', trim(Param::getValue('disable_words_text')));
        foreach ($disableWordArr as $item) {
            if (substr_count($model->$attribute, $item) > 0) {
                $this->addError($model, $attribute, $this->message);
                return;
            }
        }
    }
}
