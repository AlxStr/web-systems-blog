<?php

namespace app\controllers;

use app\models\Category;
use app\models\forms\LoginForm;
use app\models\Post;
use app\models\PostSearch;
use app\models\services\LoginService;
use app\models\services\SignupService;
use app\models\forms\SignupForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
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


    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $form = new LoginForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            (new LoginService())->login($form);
            return $this->goBack();
        }

        $form->password = '';

        return $this->render('login', [
            'model' => $form,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup()
    {
        $form = new SignupForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $user = (new SignupService())->signup($form);
            if (Yii::$app->getUser()->login($user)) {
                return $this->goHome();
            }
        }

        return $this->render('signup', [
            'model' => $form,
        ]);
    }
}
