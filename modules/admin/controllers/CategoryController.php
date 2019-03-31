<?php

namespace app\modules\admin\controllers;

use app\models\forms\CategoryForm;
use app\models\repositories\CategoryRepository;
use app\models\services\CategoryManageService;
use Yii;
use app\models\forms\CategorySearch;
use yii\web\Controller;
use yii\filters\VerbFilter;

class CategoryController extends Controller
{
    private $categoryService;
    private $categoryRepository;

    public function __construct($id, $module, CategoryManageService $service, CategoryRepository $repository, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->categoryService = $service;
        $this->categoryRepository = $repository;
    }
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->categoryRepository->get($id),
        ]);
    }

    public function actionCreate()
    {
        $form = new CategoryForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $cat = $this->categoryService->create($form);
            return $this->redirect(['view', 'id' => $cat->id]);
        }
        return $this->render('create', [
            'model' => $form,
        ]);
    }

    public function actionUpdate($id)
    {
        $cat = $this->categoryRepository->get($id);
        $form = new CategoryForm($cat);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $this->categoryService->edit($id, $form);
            return $this->redirect(['view', 'id' => $cat->id]);
        }
        return $this->render('update', [
            'model' => $form,
            'categories'
        ]);
    }

    public function actionDelete($id)
    {
        $this->categoryService->remove($id);
        return $this->redirect(['index']);
    }
}
