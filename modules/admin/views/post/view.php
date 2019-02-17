<?php

use app\models\helpers\PostHelper;
use app\models\Post;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            ['attribute'=>'title',
                'value'=>function($model){
                    return \yii\helpers\StringHelper::truncate(strip_tags($model->title),400);
                }
            ],
            [
                'attribute' => 'logo',
                'value' => function($model){
                    return  yii\helpers\Html::img($model->getPhotoUrl(), ['height' => '150', 'width' => '100']);
                },
                'format' => 'raw'
            ],
            ['attribute'=>'description',
                'value'=> function($model){
                    return \yii\helpers\StringHelper::truncate(strip_tags($model->description),200);
                }
            ],
            array(
                'attribute' => 'status',
                'filter' => PostHelper::statusList(),
                'value' => function (Post $model) {
                    return PostHelper::statusLabel($model->status);
                },
                'format' => 'raw',
            ),
            [
                'attribute' => 'category_id',
                'value' => function($model){
                    return $model->category->title;
                }
            ],
            'author',
            ['attribute'=>'body',
                'value'=> function($model){
                    return \yii\helpers\StringHelper::truncate(strip_tags($model->body),400);
                }
            ],
        ],
    ]) ?>

</div>
