<?php

namespace app\modules\admin\controllers;

use app\models\forms\CategorySearch;
use app\models\forms\PostSearch;
use app\models\User;
use app\modules\admin\models\UserSearch;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $postCount = $searchModel->search(Yii::$app->request->queryParams)->getTotalCount();

        $searchModel = new UserSearch();
        $userCount = $searchModel->search(Yii::$app->request->queryParams)->getTotalCount();;

        $searchModel = new CategorySearch();
        $categoryCount = $searchModel->search(Yii::$app->request->queryParams)->getTotalCount();


        return $this->render('index', compact('postCount', 'userCount', 'categoryCount'));
    }
}
