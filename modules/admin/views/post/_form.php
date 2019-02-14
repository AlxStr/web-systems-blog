<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
/* @var $this yii\web\View */
/* @var $model app\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="box box-default">
        <div class="box-body">
            <div class="row">
                <div class="col-md-8">
                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'category_id')->dropDownList($categories) ?>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'description')->textarea(['rows' => 15]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model->photo, 'file')->widget(FileInput::class, [
                        'options' =>[
                            'options' => [
                                'accept' => 'image/*',
                                'multiple' => false
                            ]
                        ]
                    ])?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'body')->widget(CKEditor::className(), [
                        'options' => ['rows' => 6],
                        'preset' => 'custom',
                        'clientOptions' => [
                            'toolbarGroups' => [
                                ['name' => 'undo'],
                                ['name' => 'insert', 'groups' => ['insert']],
                            ],
                        ],
                    ]) ?>
                </div>
            </div>

        </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
