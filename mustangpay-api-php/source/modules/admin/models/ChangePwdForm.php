<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;

/**
 * 修改密码 Form Model
 */
class ChangePwdForm extends Model
{
    /* @var UserObject */
    public $user;
    public $oldPassword;
    public $password;
    public $passwordRepeat;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['user', 'oldPassword', 'password', 'passwordRepeat'], 'required'],
            ['oldPassword', 'validatePassword'],
            [['password'], 'string', 'max' => 50, 'min' => 6],
            ['passwordRepeat', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('app.model', '两次密码不一致！')],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app.model', '用户名'),
            'oldPassword' => Yii::t('app.model', '原密码'),
            'password' => Yii::t('app.model', '新密码'),
            'passwordRepeat' => Yii::t('app.model', '重复密码'),
        ];
    }

    /**
     * 验证原密码 Model 验证函数
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!Yii::$app->security->validatePassword($this->oldPassword, $this->user->password_hash)) {
            $this->addError($attribute, Yii::t('app.model', '原密码不正确'));
        }
    }

    public function doSave()
    {
        if (!$this->user) {
            $this->addError('user', Yii::t('app.model', '用户未设置'));
            return false;
        }

        if ($this->validate() && $this->user) {
            $this->user->setPassword($this->password);
            return $this->user->save();
        }

        return false;
    }
}
