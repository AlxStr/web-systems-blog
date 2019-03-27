<?php

namespace app\modules\v1\controllers;

use app\models\forms\UserEditForm;
use app\models\repositories\UserRepository;
use app\models\services\UserManageService;
use app\models\helpers\DateHelper;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use yii\web\ServerErrorHttpException;

class ProfileController extends Controller
{
    private $userService;
    private $userRepository;

    public function __construct($id, $module, UserManageService $service, UserRepository $repository, $config = [])
    {
        parent::__construct($id, $module, $config = []);
        $this->userService = $service;
        $this->userRepository = $repository;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['index', 'update'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::className(),
        ];
        return $behaviors;
    }

    public function verbs(): array
    {
        return [
            'index' => ['GET'],
            'update' => ['PUT'],
        ];
    }

    public function actionIndex()
    {
        return $this->serializeUser(\Yii::$app->user->identity);
    }

    public function actionUpdate()
    {
        $id = \Yii::$app->user->id;
        $user = $this->userRepository->get($id);
        $form = new UserEditForm($user);
        $form->load(\Yii::$app->getRequest()->getBodyParams(), '');
        if ($form->validate()) {
            $this->userService->edit($user->id, $form);
            return $this->serializeUser($this->userRepository->get($id));
        }elseif($form->hasErrors()){
            return $form;
        }
        throw new ServerErrorHttpException('Failed to update the user for unknown reason.');
    }

    private function serializeUser($user): array
    {
        return [
            'name' => $user->username,
            'email' => $user->email,
            'date' => [
                'created' => DateHelper::formatApi($user->created_at),
                'updated' => DateHelper::formatApi($user->updated_at),
            ],
        ];
    }
}