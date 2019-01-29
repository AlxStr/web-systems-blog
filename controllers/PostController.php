<?php

namespace app\controllers;

use app\models\Category;
use app\models\forms\PostSearch;
use app\models\repositories\PostRepository;
use Yii;
use yii\web\Controller;

class PostController extends Controller
{
    private $posts;

    public function __construct($id, $module, $config = [], PostRepository $posts)
    {
        parent::__construct($id, $module, $config = []);
        $this->posts = $posts;
    }

    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(['only_active' => true]);
        $dataProvider->pagination->pageSize = '5';
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
        $title_cat = Category::getTitle($post->category_id);
        return $this->render('view', [
            'post' => $post,
            'category' => $title_cat,
        ]);
    }
}