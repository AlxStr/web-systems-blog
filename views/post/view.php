<?php
use yii\helpers\Html;
?>

<div class="well">
    <div class="media">
        <a class="pull-left" href="">
            <?= Html::img( $post->getPhotoUrl(), ['class' => 'media-object', 'width' => '150px', 'height' => '150px']) ?>
        </a>
        <div class="media-body">
            <h4 class="media-heading"><?= $post->title ?></h4>
            <p class="text-right">Author: <?= $post->postAuthor->username ?></p>
            <p>
                <?=$post->body ?>
            </p>
            <ul class="list-inline list-unstyled">
                <li><span><i class="glyphicon glyphicon-calendar"></i>
                        <?= Yii::$app->formatter->asDatetime($post->updated_at) ?>
                    </span></li>
            </ul>
        </div>
    </div>
</div>