<?php

namespace app\modules\client\controllers;

use app\models\forms\UserEditForm;
use app\models\repositories\UserRepository;
use app\models\services\UserManageService;
use Yii;

class ProfileController extends \yii\web\Controller
{

    private $userService;
    private $repository;

    public function __construct($id, $module, UserManageService $userService, UserRepository $repository, $config = [])
    {
        parent::__construct($id, $module, $config = []);
        $this->userService = $userService;
        $this->repository = $repository;
    }

    public function actionIndex()
    {
        $user_id = Yii::$app->user->getId();
        $user = $this->repository->get($user_id);

        $form = new UserEditForm($user);
        if ($form->load(Yii::$app->request->post()) && $form->validate()){
            $form->role = $user->role;
            $this->userService->edit($user->id, $form);
            Yii::$app->session->setFlash('success', 'Successfully changed');
        }

        return $this->render('index', compact('form'));
    }
}
