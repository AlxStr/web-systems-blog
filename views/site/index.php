<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

?>


<div class="client-default-index">


    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <div class="well">
                <div class="media">
                    <a class="pull-left" href="<?= Url::toRoute(['/article/' . $post->id])?>">

                        <?php if (is_null($post->logo) || empty($post->logo)): ?>
                            <?= Html::img('@web/images/no-image.png', ['class' => 'media-object']) ?>
                        <?php else: ?>
                            <?= Html::img('@web/images/' . $post->logo, ['class' => 'media-object', 'width' => '150px', 'height' => '150px']) ?>
                        <?php endif; ?>

                    </a>
                    <div class="media-body">
                        <h4 class="media-heading"><?= Html::a($post->title, ['/article/' . $post->id]) ?></h4>
                        <p class="text-right">Author: <?= $post->postAuthor->username ?></p>
                        <p>
                            <?= $post->description ?>
                        </p>
                        <ul class="list-inline list-unstyled">
                            <li><span><i class="glyphicon glyphicon-calendar"></i>
                                    <?= Yii::$app->formatter->asDatetime($post->updated_at) ?>
                                </span></li>
                        </ul>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    <?php else: ?>
        <div class="panel panel-default">
            <div class="panel-body">
                No posts here :c
            </div>
        </div>
    <?php endif;?>

    <?php
    echo LinkPager::widget([
        'pagination' => $pages,
    ]);
    ?>

</div>
