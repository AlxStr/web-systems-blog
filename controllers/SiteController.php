<?php

namespace app\controllers;


use app\models\forms\PostSearch;
use app\models\Post;
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
        $dataProvider = $searchModel->search(['only_active' => true]);
        $models = $dataProvider->getModels();


        echo '<pre>';

        foreach ($models as $model){
            if (isset($model->photo)){
                if($model->photo->getImageFileUrl('file')){
                    print($model->photo->getImageFileUrl('file'));
                }
            }
        }
        echo '<br>';



        die;

        return $this->render('index');
    }
}
