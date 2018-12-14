<?php

namespace app\modules\client;

use yii\filters\AccessControl;

/**
 * client module definition class
 */
class Module extends \yii\base\Module
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['author'],
                    ],
                ],
            ],
        ];
    }

    public $controllerNamespace = 'app\modules\client\controllers';

    public function init()
    {
        parent::init();
        $this->layout = 'main';
    }
}
