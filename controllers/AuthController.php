<?php

namespace app\controllers;

use Yii;
use app\models\forms\LoginForm;
use app\models\services\LoginService;
use app\models\services\SignupService;
use app\models\forms\SignupForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;


class AuthController extends Controller
{
    private $loginService;
    private $signupService;

    public function __construct($id, $module, LoginService $loginService, SignupService $signupService,  $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->signupService = $signupService;
        $this->loginService = $loginService;
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
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

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $form = new LoginForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try{
                $this->loginService->login($form);
                return $this->goBack();
            }catch (\DomainException $e){
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
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
            $user = $this->signupService->signup($form);
            if (Yii::$app->getUser()->login($user)) {
                return $this->goHome();
            }
        }

        return $this->render('signup', [
            'model' => $form,
        ]);
    }
}