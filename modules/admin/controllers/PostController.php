<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\UploadFile;
use app\models\Category;
use app\models\Post;
use app\models\PostSearch;
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
    /**
     * {@inheritdoc}
     */
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
        $model = new Post();
        $model->author = Yii::$app->user->getId();
        $model->status = '1'; // admin posts active by default
        $categories = ArrayHelper::map(Category::find()->all(), 'id', 'title');

        $fileModel = new UploadFile();
        if ($model->load(Yii::$app->request->post())) {
            $fileModel->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if (isset($fileModel->imageFile)) {
                if ($fileModel->upload()) {
                    $model->logo = $fileModel->imageFile->baseName . '.' . $fileModel->imageFile->extension;
                }
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }


        return $this->render('create', [
            'model' => $model,
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
        $model = $this->findModel($id);

        $categories = Category::find()->all();
        $categories = ArrayHelper::map($categories, 'id', 'title');

        if ($model->load(Yii::$app->request->post())) {
            $fileModel = new UploadFile();
            $fileModel->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if (isset($fileModel->imageFile)) {
                if ($fileModel->upload()) {
                    $model->logo = $fileModel->imageFile->baseName . '.' . $fileModel->imageFile->extension;
                }
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
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
        $model = $this->findModel($id);
        $model->delete();

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

    public function actionModerate()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $categories = Category::find()->all();
        $categories = ArrayHelper::map($categories, 'id', 'title');

        $dataProvider->query->andFilterWhere(['status' => 'mod']);
        return $this->render('moderate', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'categories' => $categories
        ]);
    }

    // Method makes post status active
    public function actionActive($id)
    {
        $model = $this->findModel($id);
        $model->status = 1;
        $model->save();

        return $this->redirect(['moderate']);
    }
}
