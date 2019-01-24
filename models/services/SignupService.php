<?php

namespace app\models\services;

use app\models\SignupForm;
use app\models\User;

class SignupService
{

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