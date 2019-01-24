<?php

namespace app\models\services;

use app\models\LoginForm;
use app\models\User;
use Yii;

class LoginService
{
    private $_user = false;

    public function login($form)
    {
        if ($user = $this->getUser($form->username)) {
            return Yii::$app->user->login($user, true ? 3600 * 24 * 30 : 0);
        }
        return false;
    }

    public function getUser($username)
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($username);
        }

        return $this->_user;
    }

    public function validatePassword(LoginForm $form)
    {
        if (!$form->hasErrors()) {
            $user = User::findByUsername($form->username);
            if (!$user || !$user->validatePassword($form->password)) {
                return ('Incorrect username or password.');
            }
        }
        return null;
    }
}