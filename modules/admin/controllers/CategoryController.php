<?php

namespace app\modules\admin\controllers;

use app\models\forms\CategoryForm;
use app\models\repositories\CategoryRepository;
use app\models\services\CategoryManageService;
use Yii;
use app\models\Category;
use app\models\forms\CategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
{
    private $categoryService;

    public function __construct($id, $module, CategoryManageService $catSer, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->categoryService = $catSer;
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

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
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

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $cat = $this->findModel($id);
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

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->categoryService->remove($id);
        return $this->redirect(['index']);
    }

    protected function findModel($id): Category
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
