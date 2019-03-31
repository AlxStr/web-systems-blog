<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>


<?php if(\Yii::$app->user->can('admin')): ?>
<div class="well">
    <?= Html::a('Update', ['admin/post/update', 'id' => $post_id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Delete', ['admin/post/delete', 'id' => $post_id], [
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
    <?= Html::a('Update', ['client/post/update', 'id' => $post_id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Delete', ['client/post/delete', 'id' => $post_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
    ?>
</div>
<?php endif; ?>