<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin(); ?>
    <h3>Профіль</h3>

<?= $form->field($user, 'username') ?>

<?= $form->field($user, 'email') ?>

    <div class="form-group">
        <?= Html::submitButton('Зберегти', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>