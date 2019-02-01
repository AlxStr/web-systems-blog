<?php

namespace app\modules\admin\controllers;

use app\models\forms\PostForm;
use app\models\services\PostManageService;
use app\models\services\UploadService;
use Yii;
use app\models\UploadFile;
use app\models\Category;
use app\models\Post;
use app\models\forms\PostSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
    private $postService;
    private $uploadService;

    public function __construct($id, $module, PostManageService $postService, UploadService $uploadService,  $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->postService = $postService;
        $this->uploadService = $uploadService;
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
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $categories = Category::find()->all();
        $categories = ArrayHelper::map($categories, 'id', 'title');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'categories' => $categories
        ]);
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $title_cat = Category::getTitle($model->category_id);
        return $this->render('view', [
            'model' => $model,
            'title_cat' => $title_cat,
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $categories = ArrayHelper::map(Category::find()->all(), 'id', 'title');

        $form = new PostForm();
        $form->author = Yii::$app->user->getId();
        $form->status = Post::ACTIVE;
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $form->logo = $this->uploadService->checkUpload($form);
                $post = $this->postService->create($form);
                return $this->redirect(['view', 'id' => $post->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $form,
            'categories' => $categories,
        ]);
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $post = $this->findModel($id);
        $categories = Category::find()->all();
        $categories = ArrayHelper::map($categories, 'id', 'title');

        $form = new PostForm($post);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $form->logo = $this->uploadService->checkUpload($form);
                $this->postService->edit($post->id, $form);
                return $this->redirect(['view', 'id' => $post->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'categories' => $categories,
        ]);
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->postService->remove($id);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionActive($id)
    {
        $this->postService->activate($id);

        return $this->redirect(['index']);
    }
}
