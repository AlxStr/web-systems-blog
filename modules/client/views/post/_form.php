<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
/* @var $this yii\web\View */
/* @var $model app\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>


    <?= $form->field($model, 'category_id')->dropDownList($categories) ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'imageFile')->widget(FileInput::class, [
        'options' =>[
            'options' => [
                'accept' => 'image/*',
                'multiple' => false
            ]
        ]
    ])?>
    <?= $form->field($model, 'description')->textarea() ?>

    <?= $form->field($model, 'body')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'custom',
        'clientOptions' => [
            'toolbarGroups' => [
                ['name' => 'undo'],
                ['name' => 'insert', 'groups' => ['insert']],
            ],
            'removeButtons' => 'Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe',
        ],
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
