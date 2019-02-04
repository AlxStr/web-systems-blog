<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<h3>Профіль</h3>
<div class="box">
    <div class="box-body">
    <?php $activeForm = ActiveForm::begin(); ?>

        <?= $activeForm->field($form, 'username')->textInput(['maxlength' => true]) ?>

        <?= $activeForm->field($form, 'email')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Зберегти', ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
