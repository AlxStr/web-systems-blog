<?php

namespace app\modules\v2\controllers;

use app\models\forms\UserCreateForm;
use app\models\forms\UserEditForm;
use app\models\providers\MapDataProvider;
use app\models\repositories\UserRepository;
use app\models\services\UserManageService;
use app\models\User;
use app\modules\admin\models\UserSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use yii\web\ServerErrorHttpException;

class UserController extends Controller
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
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::className(),
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['admin'],
                ],
            ],
        ];
        return $behaviors;
    }

    public function verbs(): array
    {
        return [
            'index' => ['GET'],
            'view' => ['GET'],
            'create' => ['POST'],
            'update' => ['PUT'],
            'delete' => ['DELETE'],
            'ban' => ['GET'],
            'unban' => ['GET']
        ];
    }

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search([]);
        return new MapDataProvider($dataProvider, [$this, 'serializeList']);
    }

    public function actionView($id)
    {
        $user = $this->userRepository->get($id);
        return $this->serializeView($user);
    }

    public function actionCreate(){
        $form = new UserCreateForm();
        $form->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($form->validate()) {
            $user = $this->userService->create($form);
            Yii::$app->response->setStatusCode(201);
            return $user;
        }elseif($form->hasErrors()){
            return $form;
        }
        throw new ServerErrorHttpException('Failed to create the user for unknown reason.');
    }

    public function actionUpdate($id){
        $user = $this->userRepository->get($id);
        $form = new UserEditForm($user);
        $form->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($form->validate()) {
            $this->userService->edit($user->id, $form);
            return $this->userRepository->get($id);
        }elseif($form->hasErrors()){
            return $form;
        }
        throw new ServerErrorHttpException('Failed to update the user for unknown reason.');
    }

    public function actionDelete($id)
    {
        $this->userService->remove($id);
        Yii::$app->response->setStatusCode(204);
        return [];
    }

    public function actionBan($id){
        $this->userService->ban($id);
        $response = \Yii::$app->getResponse();
        $response->setStatusCode(204);
        return [];
    }

    public function actionUnban($id){
        $this->userService->unban($id);
        $response = \Yii::$app->getResponse();
        $response->setStatusCode(204);
        return [];
    }

    public function serializeList(User $user){
        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'status' => [
                'code' => $user->status,
                'active' => boolval($user->status),
            ],
            'role' => $user->role,
        ];
    }

    public function serializeView(User $user){
        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'status' => [
                'code' => $user->status,
                'active' => boolval($user->status),
            ],
            'role' => $user->role,
            'register' => $user->created_at,
        ];
    }
}