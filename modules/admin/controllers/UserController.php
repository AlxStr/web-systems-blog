<?php

namespace app\modules\admin\controllers;

use app\models\forms\UserCreateForm;
use app\models\forms\UserEditForm;
use app\models\repositories\UserRepository;
use app\models\services\UserManageService;
use Yii;
use app\modules\admin\models\UserSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;


class UserController extends Controller
{

    private $userService;
    private $userRepository;
    public function __construct($id, $module, UserManageService $service, UserRepository $repository, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->userService = $service;
        $this->userRepository = $repository;
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->userRepository->get($id),
        ]);
    }

    public function actionCreate()
    {
        $form = new UserCreateForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try{
                $user = $this->userService->create($form);
                return $this->redirect(['view', 'id' => $user->id]);
            }catch (\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }


        return $this->render('create', [
            'model' => $form,
        ]);
    }

    public function actionUpdate($id)
    {
        $user = $this->userRepository->get($id);

        $form = new UserEditForm($user);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try{
                $this->userService->edit($user->id, $form);
                return $this->redirect(['view', 'id' => $user->id]);
            }catch (\DomainException $e){
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
        ]);
    }

    public function actionDelete($id)
    {
        $this->userService->remove($id);
        return $this->redirect(['index']);
    }

    public function actionBan($id){
        $this->userService->ban($id);
        return $this->redirect(['index']);
    }

    public function actionUnban($id){
        $this->userService->unban($id);
        return $this->redirect(['index']);
    }

}
