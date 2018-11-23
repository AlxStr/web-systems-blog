<?php

namespace app\modules\client\controllers;

use app\models\Category;
use app\models\Post;
use app\models\PostSearch;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `client` module
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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['status'=> 1]);
        $dataProvider->query->orderBy(['updated_at' => SORT_DESC]);
        $dataProvider->pagination->pageSize = '5';
        $pages = $dataProvider->getPagination();
        $models = $dataProvider->getModels();

        return $this->render('index', [
            'posts' => $models,
            'pages' => $pages,
        ]);
    }

    public function actionPost($id){
        $post = $this->findPost($id);
        $title_cat = Category::getTitle($post->category_id);
        return $this->render('view', [
            'post' => $post,
            'category' => $title_cat,
        ]);
    }

    protected function findPost($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
