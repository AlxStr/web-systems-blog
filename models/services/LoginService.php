<?php

namespace app\models\services;

use Yii;
use app\models\forms\LoginForm;
use app\models\repositories\UserRepository;
use app\models\User;

class LoginService
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function login(LoginForm $form){
        $user = $this->auth($form);
        return Yii::$app->user->login($user, $form->rememberMe ? 3600 * 24 * 30 : 0);
    }

    public function auth(LoginForm $form): User
    {
        $user = $this->users->findByUsernameOrEmail($form->username);

        if (!$user || !$user->validatePassword($form->password)) {
            throw new \DomainException('Undefined user or password.');
        }
        if (!$user->isActive()){
            throw new \DomainException('User is banned.');
        }
        return $user;
    }
}