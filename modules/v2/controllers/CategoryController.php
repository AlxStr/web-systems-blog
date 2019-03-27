<?php

namespace app\modules\v2\controllers;

use app\models\Category;
use app\models\forms\CategoryForm;
use app\models\forms\CategorySearch;
use app\models\Post;
use app\models\providers\MapDataProvider;
use Yii;
use app\models\repositories\CategoryRepository;
use app\models\services\CategoryManageService;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Url;
use yii\rest\Controller;
use yii\web\ServerErrorHttpException;

class CategoryController extends Controller
{
    private $categoryService;
    private $categoryRepository;
    public function __construct($id, $module, CategoryManageService $service, CategoryRepository $repository, $config = [])
    {
        parent::__construct($id, $module, $config = []);
        $this->categoryService = $service;
        $this->categoryRepository = $repository;
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
                    'actions' => ['index', 'view'],
                    'allow' => true,
                    'roles' => ['author'],
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
            'delete' => ['DELETE']
        ];
    }

    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return new MapDataProvider($dataProvider, [$this, 'serializeList']);
    }

    public function actionView($id)
    {
        $post = $this->categoryRepository->get($id);
        return $this->serializeView($post);
    }

    public function actionCreate()
    {
        $form = new CategoryForm();
        $form->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($form->validate()) {
            $category = $this->categoryService->create($form);
            Yii::$app->response->setStatusCode(201);
            return $this->serializeView($category);
        }elseif($form->hasErrors()){
            return $form;
        }
        throw new ServerErrorHttpException('Failed to create the category for unknown reason.');
    }

    public function actionUpdate($id)
    {
        $category = $this->categoryRepository->get($id);
        $form = new CategoryForm($category);
        $form->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($form->validate()) {
            $this->categoryService->edit($category->id, $form);
            return $this->serializeView($this->categoryRepository->get($id));
        }elseif($form->hasErrors()){
            return $form;
        }
        throw new ServerErrorHttpException('Failed to update the category for unknown reason.');
    }

    public function actionDelete($id)
    {
        $this->categoryService->remove($id);
        Yii::$app->response->setStatusCode(204);
        return [];
    }

    public function serializeList(Category $category)
    {
        return [
            'id' => $category->id,
            'title' => $category->title,
            '_links' => [
                'self' => ['href' => Url::to(['view', 'id' => $category->id], true)],
            ],
        ];
    }

    public function serializeView(Category $category)
    {
      return [
          'id' => $category->id,
          'title' => $category->title,
          'posts' => array_map(function (Post $post) {
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
                  '_links' => [
                      'self' => ['href' => Url::to(['post/view', 'id' => $post->id], true)],
                  ],
              ];
          }, $category->posts),
      ];
    }
}