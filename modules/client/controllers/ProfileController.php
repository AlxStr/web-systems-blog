<?php

namespace app\modules\client\controllers;

use app\models\User;
use Yii;
use yii\web\NotFoundHttpException;

class ProfileController extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        if (Yii::$app->user->can('author'))
            return true;
        $this->redirect('/login');
        return false;
    }

    public function actionIndex()
    {
        $user = Yii::$app->user->identity;

        if ($user->load(Yii::$app->request->post())){
            $user->save();
            Yii::$app->session->setFlash('success', 'Successfully changed');
        }

        return $this->render('index', compact('user'));
    }
}
