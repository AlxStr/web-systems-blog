<?php

namespace app\modules\client\controllers;

use app\models\UploadFile;
use app\models\Category;
use Yii;
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
    public function beforeAction($action)
    {
        if (Yii::$app->user->can('author'))
            return true;
        $this->redirect('/login');
        return false;
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->can('admin'))
            $this->redirect('/admin/post/index');

        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // The user receives only his entries
        $dataProvider->query->andFilterWhere(['author'=>Yii::$app->user->id]);

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
            $categories = ArrayHelper::map(Category::find()->all(), 'id', 'title');

            $fileModel = new UploadFile();
            if ($model->load(Yii::$app->request->post())) {
                $fileModel->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if (isset($fileModel->imageFile)){
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

        if (Yii::$app->user->can('updateOwnPost', ['post' => $model])) {
            $categories = Category::find()->all();
            $categories = ArrayHelper::map($categories, 'id', 'title');

            if ($model->load(Yii::$app->request->post())) {
                $fileModel = new UploadFile();
                $fileModel->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if (isset($fileModel->imageFile)){
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

        throw new \yii\web\HttpException(403, 'You don\'t have permission to access');
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
        if (Yii::$app->user->can('updateOwnPost', ['post' => $model = $this->findModel($id)])) {
            $model->delete();

            return $this->redirect(['index']);
        }
        throw new \yii\web\HttpException(403, 'You don\'t have permission to access');
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

}
