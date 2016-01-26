<?php namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $user_email;
    public $user_password_hash;
    public $rememberMe = false;
    private $_user = false;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_email'], 'email'],
            [['user_email', 'user_password_hash'], 'required'],
            ['rememberMe', 'boolean'],
            ['user_password_hash', 'validatePassword'],
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
            if (!$user || !$user->validatePassword($this->user_password_hash)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }
    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->user_email);
        }
        return $this->_user;
    }
}
