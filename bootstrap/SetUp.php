<?php

namespace app\bootstrap;

use app\models\services\LoginService;
use app\models\services\SignupService;
use app\models\services\UploadService;
use yii\base\Application;
use yii\base\BootstrapInterface;

class SetUp implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
    }
}