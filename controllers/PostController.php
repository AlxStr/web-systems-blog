<?php

namespace app\controllers;

use app\models\forms\PostSearch;
use app\models\repositories\PostRepository;
use yii\web\Controller;

class PostController extends Controller
{
    private $posts;

    public function __construct($id, $module, PostRepository $posts, $config = [])
    {
        parent::__construct($id, $module, $config = []);
        $this->posts = $posts;
    }

    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(['only_active' => true], 5);
        $pages = $dataProvider->getPagination();
        $models = $dataProvider->getModels();

        return $this->render('index', [
            'posts' => $models,
            'pages' => $pages,
        ]);
    }

    public function actionView($id)
    {
        $post = $this->posts->get($id);
        return $this->render('view', [
            'post' => $post,
        ]);
    }
}