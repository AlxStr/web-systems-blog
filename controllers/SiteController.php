<?php

namespace app\controllers;

use app\models\Category;
use app\models\Post;
use app\models\PostSearch;
use Yii;
use yii\web\Controller;

class SiteController extends Controller
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new PostSearch();
        Yii::$app->request->setQueryParams(['only' => 'active']);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = '5';
        $pages = $dataProvider->getPagination();
        $models = $dataProvider->getModels();

        return $this->render('index', [
            'posts' => $models,
            'pages' => $pages,
        ]);
    }

    public function actionPost($id)
    {
        $post = Post::findPost($id);
        $title_cat = Category::getTitle($post->category_id);
        return $this->render('view', [
            'post' => $post,
            'category' => $title_cat,
        ]);
    }
}
