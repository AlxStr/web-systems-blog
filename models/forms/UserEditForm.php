<?php

namespace app\models\forms;

use app\models\User;
use yii\base\Model;

class UserEditForm extends Model
{
    public $id;
    public $username;
    public $email;
    public $role;

    private $_user;

    /**
     * CreateUserForm constructor.
     * @param $username
     * @param $email
     * @param $password
     * @param $role
     */
    public function __construct(User $user, $config = [])
    {
        parent::__construct($config);
        $this->username = $user->username;
        $this->email = $user->email;
        $this->_user = $user;
    }


    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This username has already been taken.',
                'filter' => ['<>', 'id', $this->_user->id]],
            ['username', 'string', 'min' => 3, 'max' => 255],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This email address has already been taken.'],
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