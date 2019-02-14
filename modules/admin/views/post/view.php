<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
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
            [
                    'attribute' => 'status',
                    'value' => $model->getStatusName(),
            ],
            [
                'attribute' => 'category_id',
                'value' => $title_cat->title,
            ],
            'title',
            'logo',
            'author',
            'description:ntext',
            'body:ntext',
        ],
    ]) ?>


    <div class="box" id="photo">
        <div class="box-header with-border">Photo</div>
        <div class="box-body">

            <?php if ($model->photo): ?>
                <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', ['delete-photo', 'id' => $model->id, 'photo_id' => $model->photo->id], [
                    'class' => 'btn btn-default',
                    'data-method' => 'post',
                    'data-confirm' => 'Remove photo?',
                ]); ?>
                <div>
                    <?= Html::a(
                        Html::img($model->photo->getThumbFileUrl('file', 'thumb')),
                        $model->photo->getUploadedFileUrl('file'),
                        ['class' => 'thumbnail', 'target' => '_blank']
                    ) ?>
                </div>
            <?php else: ?>
                <?php $form = ActiveForm::begin([
                    'options' => ['enctype'=>'multipart/form-data'],
                ]); ?>
                <?= $form->field($photoForm, 'file[]')->label(false)->widget(FileInput::class, [
                    'options' => [
                        'accept' => 'image/*',
                        'multiple' => false,
                    ]
                ]) ?>
                <div class="form-group">
                    <?= Html::submitButton('Upload', ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>

            <?php endif; ?>
        </div>
    </div>


</div>
