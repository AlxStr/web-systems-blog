<?php

namespace app\modules\v1\controllers;

use app\models\forms\UserCreateForm;

use app\models\forms\UserEditForm;
use app\models\repositories\UserRepository;
use app\models\services\UserManageService;
use app\modules\admin\models\UserSearch;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class UserController extends ActiveController
{
    private $userService;
    private $userRepository;
    public $modelClass = 'app\models\User';

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

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function actionCreate(){
        $form = new UserCreateForm();
        $form->load(\Yii::$app->getRequest()->getBodyParams(), '');
        if ($form->validate()) {
            $user = $this->userService->create($form);
            \Yii::$app->response->setStatusCode(201);
            return $user;
        }elseif($form->hasErrors()){
            return $form;
        }
        throw new ServerErrorHttpException('Failed to create the user for unknown reason.');
    }

    public function actionUpdate($id){
        $user = $this->userRepository->get($id);
        $form = new UserEditForm($user);
        $form->load(\Yii::$app->getRequest()->getBodyParams(), '');
        if ($form->validate()) {
            $this->userService->edit($user->id, $form);
            return $this->userRepository->get($id);
        }elseif($form->hasErrors()){
            return $form;
        }
        throw new ServerErrorHttpException('Failed to update the user for unknown reason.');
    }

    public function actionBan($id){
        try{
            $this->userService->ban($id);
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(204);
            return [];
        }catch (\DomainException $e){
            throw new NotFoundHttpException('User not found.');
        }
    }

    public function actionUnban($id){
        try{
            $this->userService->unban($id);
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(204);
            return [];
        }catch (\DomainException $e){
            throw new NotFoundHttpException('User not found.');
        }
    }

    public function prepareDataProvider()
    {
        $searchModel = new UserSearch();
        return $searchModel->search(\Yii::$app->request->queryParams);
    }
}