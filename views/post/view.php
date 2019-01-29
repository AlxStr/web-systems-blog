<?php


use yii\helpers\Html;
?>


<div class="well">
    <div class="media">
        <a class="pull-left" href="">

            <?php if(is_null($post->logo) || empty($post->logo)): ?>
                <?= Html::img('@web/images/no-image.png', ['class' => 'media-object']) ?>
            <?php else:?>
                <?= Html::img('@web/images/' . $post->logo, ['class' => 'media-object', 'width' => '150px', 'height' => '150px']) ?>
            <?php endif; ?>

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