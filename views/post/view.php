<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\StringHelper;

$this->title = $post->title;
$this->params['breadcrumbs'][] = ['label' => $post->category->title, 'url' => ['post/category', 'id' => $post->category->id]];
$this->params['breadcrumbs'][] = StringHelper::truncateWords($this->title, 8);
?>

<?php if(\Yii::$app->user->can('admin')): ?>
<div class="well">
    <?= Html::a('Update', ['admin/post/update', 'id' => $post->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Delete', ['admin/post/delete', 'id' => $post->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
    ?>
</div>
<?php elseif(\Yii::$app->user->can('ownPostsManage', ['post' => $post])): ?>
<div class="well">
    <?= Html::a('Update', ['client/post/update', 'id' => $post->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Delete', ['client/post/delete', 'id' => $post->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
    ?>
</div>
<?php endif; ?>

<div class="well">
    <div class="media">
        <div class="pull-right">
        <a href="">
            <?= Html::img( $post->getPhotoUrl(), ['class' => 'media-object', 'width' => '150px', 'height' => '150px']) ?>
        </a>
            <p class="text-right">Author: <?= Html::encode($post->postAuthor->username) ?></p>
        </div>
        <div class="media-body">
            <h4 class="media-heading"><?= Html::encode($post->title) ?></h4>
            <p>
                <?= $post->body ?>
            </p>
            <ul class="list-inline list-unstyled">
                <li><span><i class="glyphicon glyphicon-calendar"></i>
                        <?= Yii::$app->formatter->asDatetime($post->updated_at) ?>
                    </span></li>
            </ul>
        </div>
    </div>
</div>