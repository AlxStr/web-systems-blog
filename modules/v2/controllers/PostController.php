<?php

namespace app\modules\v2\controllers;

use Yii;
use app\models\forms\PostForm;
use app\models\forms\PostSearch;
use app\models\Post;
use app\models\repositories\PostRepository;
use app\models\services\PostManageService;
use app\models\providers\MapDataProvider;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use yii\web\ServerErrorHttpException;

class PostController extends Controller
{
    private $postRepository;
    private $postService;
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
                    'roles' => ['admin'],
                ],
                [
                    'actions' => ['index', 'create'],
                    'allow' => true,
                    'roles' => ['author'],
                ],
                [
                    'allow' => true,
                    'actions' => ['view', 'delete', 'update'],
                    'roles' => ['ownPostsManage'],
                    'roleParams' => function($rule) {
                        return ['post' => $this->postRepository->get(Yii::$app->request->get('id'))];
                    },
                ],
            ],
            'denyCallback' => function ($rule, $action) {
                throw new \yii\web\ForbiddenHttpException('You are not allowed to access');
            }
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
            'activate' => ['GET']
        ];
    }

    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(['activeOnly' => true]);
        return new MapDataProvider($dataProvider, [$this, 'serializeView']);
    }

    public function actionView($id)
    {
        $post = $this->postRepository->get($id);
        return $this->serializeView($post);
    }

    public function actionCreate()
    {
        $form = new PostForm();
        $form->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($form->validate()) {
            $post = $this->postService->create($form);
            Yii::$app->response->setStatusCode(201);
            return $this->serializeView($post);
        }elseif($form->hasErrors()){
            return $form;
        }
        throw new ServerErrorHttpException('Failed to create the post for unknown reason.');
    }

    public function actionUpdate($id)
    {
        $post = $this->postRepository->get($id);
        $form = new PostForm($post);
        $form->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($form->validate()) {
            $this->postService->edit($post->id, $form);
            return $this->serializeView($this->postRepository->get($id));
        }elseif($form->hasErrors()){
            return $form;
        }
        throw new ServerErrorHttpException('Failed to update the post for unknown reason.');
    }

    public function actionDelete($id)
    {
        $this->postService->remove($id);
        Yii::$app->response->setStatusCode(204);
        return [];
    }

    public function actionActivate($id)
    {
        $this->postService->activate($id);
        $response = Yii::$app->response;
        $response->setStatusCode(204);
        return [];
    }

    public function serializeView(Post $post): array
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'category' => [
                'id' => $post->category->id,
                'name' => $post->category->title,
            ],
            'logo' => $post->logo ? $post->getPhotoUrl():null,
            'status' => [
                'code' => $post->status,
                'active' => boolval($post->status)
            ],
            'author' => [
                'id' => $post->author,
                'username' => $post->postAuthor->username,
            ],
            'description' => $post->description,
            'body' => $post->body,
            'created' => $post->created_at,
        ];
    }
}