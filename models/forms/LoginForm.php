<?php

namespace app\models\forms;

use app\models\services\LoginService;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }
    public function validatePassword($attribute, $params)
    {
        if ($e = (new LoginService())->validatePassword($this)) {
            $this->addError($attribute, $e);
        }
    }
}
