<?php

namespace app\models\forms;

use yii\base\Model;

class UserCreateForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $role;

    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 3, 'max' => 255],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This email address has already been taken.'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6,'max' => 16],
            ['role', 'string', 'min' => 4]
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Login',
            'email' => 'Email',
            'password' => 'Password',
        ];
    }
}