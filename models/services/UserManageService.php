<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.01.19
 * Time: 11:17
 */

namespace app\models\services;


use app\models\forms\UserCreateForm;
use app\models\forms\UserEditForm;
use app\models\repositories\UserRepository;
use app\models\User;
use Yii;

class UserManageService
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(UserCreateForm $form): User
    {
        $user = User::create(
            $form->username,
            $form->email,
            $form->password,
            $form->role
        );
        $this->repository->save($user);

        return $user;
    }

    public function setAuthorRole($user){
        $authManager = Yii::$app->authManager;
        $role = $authManager->getRole('author');
        $authManager->assign($role, $user->getId());
    }

    public function edit($id, UserEditForm $form): void
    {
        $user = $this->repository->get($id);
        $user->edit(
            $form->username,
            $form->email,
            $form->role
        );
        $this->repository->save($user);
    }

    public function remove($id): void
    {
        $user = $this->repository->get($id);
        $this->repository->remove($user);
    }

    public function ban($id): void
    {
        $user = $this->repository->get($id);
        $user->status = User::STATUS_INACTIVE;
        $this->repository->save($user);
    }

    public function unban($id): void
    {
        $user = $this->repository->get($id);
        $user->status = User::STATUS_ACTIVE;
        $this->repository->save($user);
    }
}