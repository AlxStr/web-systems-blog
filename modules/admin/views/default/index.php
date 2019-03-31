<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>


<div class="admin-default-index">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?= $userCount ?></h3>

                <p>User Registrations</p>
            </div>
            <div class="icon">
                <i class="fa fa-user"></i>
            </div>
            <?= Html::a('More info', Url::to('admin/user', true), ['class' => 'small-box-footer']) ?>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?= $postCount?> </h3>

                <p>Post Count</p>
            </div>
            <div class="icon">
                <i class="fa fa-clipboard"></i>
            </div>
            <?= Html::a('More info', Url::to('admin/post', true), ['class' => 'small-box-footer']) ?>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?= $categoryCount ?> </h3>

                <p>Category Count</p>
            </div>
            <div class="icon">
                <i class="fa fa-list-ol"></i>
            </div>

            <?= Html::a('More info', Url::to('admin/category', true), ['class' => 'small-box-footer']) ?>
        </div>
    </div>
</div>
