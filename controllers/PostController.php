<?php

namespace app\controllers;

use app\models\Category;
use app\models\forms\SearchForm;
use app\models\readModels\PostReadRepository;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PostController extends Controller
{
    private $posts;

    public function __construct($id, $module, PostReadRepository $posts, $config = [])
    {
        parent::__construct($id, $module, $config = []);
        $this->posts = $posts;
    }

    public function actionIndex()
    {
        $dataProvider = $this->posts->getAllActive();
        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionView($id)
    {
        if (!$post = $this->posts->find($id)){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        return $this->render('view', [
            'post' => $post,
        ]);
    }

    public function actionSearch()
    {
        $form = new SearchForm();
        $form->load(\Yii::$app->request->queryParams);
        $form->validate();
        $dataProvider = $this->posts->search($form);
        return $this->render('search', [
            'dataProvider' => $dataProvider,
            'searchForm' => $form,
        ]);
    }

    public function actionCategory($id){
        if (!$category = Category::find()->where(['id' => $id])->one()){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $dataProvider = $this->posts->getAllByCategory($category);
        return $this->render('category', [
           'dataProvider' => $dataProvider,
           'category' => $category,
        ]);
    }
}