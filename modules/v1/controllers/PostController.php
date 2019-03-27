<?php

namespace app\modules\v1\controllers;

use app\models\forms\PostForm;
use app\models\forms\PostSearch;
use app\models\repositories\PostRepository;
use app\models\services\PostManageService;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;
use yii\web\ServerErrorHttpException;

class PostController extends ActiveController
{
    private $postRepository;
    private $postService;
    public $modelClass = 'app\models\Post';

    public function __construct($id, $module, PostRepository $repository, PostManageService $service, $config = [])
    {
        parent::__construct($id, $module, $config = []);
        $this->postRepository = $repository;
        $this->postService = $service;
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
                    'roles' => ['author'],
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
        $form = new PostForm();
        $form->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($form->validate()) {
            $post = $this->postService->create($form);
            Yii::$app->response->setStatusCode(201);
            return $post;
        }elseif($form->hasErrors()){
            return $form;
        }
        throw new ServerErrorHttpException('Failed to create the post for unknown reason.');
    }

    public function actionUpdate($id){
        $post = $this->postRepository->get($id);
        if (!Yii::$app->user->can('ownPostsManage', ['post' => $post]) && !Yii::$app->user->can('admin')){
            throw  new ForbiddenHttpException('Forbidden. You are not the author of the post.');
        }

        $form = new PostForm($post);
        $form->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($form->validate()) {
            $this->postService->edit($post->id, $form);
            return $this->postRepository->get($id);
        }elseif($form->hasErrors()){
            return $form;
        }
        throw new ServerErrorHttpException('Failed to update the post for unknown reason.');
    }

    public function actionActivate($id){
        if (!Yii::$app->user->can('admin'))
            throw  new ForbiddenHttpException('Forbidden. ');
        $this->postService->activate($id);
        $response = Yii::$app->response;
        $response->setStatusCode(204);
        return [];
    }

    public function prepareDataProvider()
    {
        $searchModel = new PostSearch();
        Yii::$app->request->setQueryParams(['activeOnly' => true]);
        return $searchModel->search(Yii::$app->request->queryParams);
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if (in_array($action, ['view', 'delete'])) {
            if (Yii::$app->user->can('ownPostsManage', ['post' => $model])){
                return true;
            }
            if (Yii::$app->user->can('admin')){
                return true;
            }
            throw  new ForbiddenHttpException('Forbidden. Post is under moderation or you are not the author.');
        }
    }
}