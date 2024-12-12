<?php

namespace app\models\member;

use app\models\User;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $mobile;
    public $rememberMe = true;
    public $agree;
    public $smsCode;

    private $_user = false;
    public static $duration = 3600 * 24 * 7;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['mobile', 'smsCode', 'agree'], 'required'],
            ['rememberMe', 'boolean'],
            ['smsCode', 'validateSmsCode'],
            ['agree', 'in', 'range' => [1], 'message' => Yii::t('app.model', '登录前请先阅读并同意条款'), 'skipOnEmpty' => false],
        ];
    }

    public function attributeLabels()
    {
        return [
            'mobile' => '手机号',
            'smsCode' => '验证码',
            'rememberMe' => '记住密码',
        ];
    }

    /**
     * Validates the smsCode.
     * This method serves as the inline validation for smsCode.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateSmsCode($attribute, $params)
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addError($attribute, '用户不存在');
            return;
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? self::$duration : 0);
        }
        return false;
    }

    /**
     * Finds user by [[mobile]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByMobile($this->mobile);
        }

        return $this->_user;
    }
}
