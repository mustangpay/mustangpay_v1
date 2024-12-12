<?php

namespace app\modules\admin\models;

use app\models\User;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 * @property string $username  用户名
 * @property string $password  密码
 * @property boolean $rememberMe
 * @property string $verifyCode 验证码
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $verifyCode;

    private $_user = false;

    public static $duration = 3600 * 24 * 7;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['verifyCode', 'required'],
            ['verifyCode', 'captcha', 'captchaAction' => '/admin/default/captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app.model','帐号'),
            'password' => Yii::t('app.model','密码'),
            'rememberMe' => Yii::t('app.model','记住密码'),
            'verifyCode' => Yii::t('app.model','验证码'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) {
                $this->addError($attribute, Yii::t('app.model','用户不存在'));
                return;
            }
            if (!$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app.model','帐号或密码不正确.'));
            }
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
     * @return \app\models\UserBaseObject
     */
    public function getUser()
    {

        if ($this->_user === false) {
            $this->_user = UserObject::findByUsername($this->username);
        }

        return $this->_user;
    }
}
