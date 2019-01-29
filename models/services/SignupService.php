<?php

namespace app\models\services;

use app\models\forms\SignupForm;
use app\models\repositories\UserRepository;
use app\models\User;

class SignupService
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function signup(SignupForm $form): User
    {
        $user = User::signup(
            $form->username,
            $form->email,
            $form->password
        );

        if(!$user->save()){
            throw new \RuntimeException('Saving error.');
        }

        return $user;
    }
}