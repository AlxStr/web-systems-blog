<?php

namespace app\controllers;

use app\models\forms\PostForm;
use app\models\forms\PostSearch;
use app\models\Post;
use app\models\repositories\PostRepository;
use app\models\services\PostManageService;
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

        return $this->render('index');
    }
}
