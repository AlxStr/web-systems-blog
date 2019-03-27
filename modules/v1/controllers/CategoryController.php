<?php

namespace app\modules\v1\controllers;

use app\models\forms\PostSearch;
use Yii;
use app\models\forms\CategoryForm;
use app\models\forms\CategorySearch;
use app\models\repositories\CategoryRepository;
use app\models\services\CategoryManageService;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;
use yii\web\ServerErrorHttpException;

class CategoryController extends ActiveController
{
    private $categoryService;
    private $categoryRepository;
    public $modelClass = 'app\models\Category';

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
            'only' => ['create', 'update', 'delete'],
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
        $form = new CategoryForm();
        if ($form->load(Yii::$app->getRequest()->getBodyParams(), '') && $form->validate()) {
            $category = $this->categoryService->create($form);
            return $category;
        }elseif($form->hasErrors()){
            return $form;
        }
        throw new ServerErrorHttpException('Failed to create the category for unknown reason.');
    }

    public function actionUpdate($id){
        $category = $this->categoryRepository->get($id);
        $form = new CategoryForm($category);
        if ($form->load(Yii::$app->getRequest()->getBodyParams(), '') && $form->validate()) {
            $this->categoryService->edit($category->id, $form);
            return $this->categoryRepository->get($id);
        }elseif($form->hasErrors()){
            return $form;
        }
        throw new ServerErrorHttpException('Failed to update the category for unknown reason.');
    }

    public function prepareDataProvider()
    {
        $searchModel = new CategorySearch();
        return $searchModel->search(\Yii::$app->request->queryParams);
    }
}