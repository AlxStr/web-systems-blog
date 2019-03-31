<?php
$this->params['breadcrumbs'][] = ['label' => 'Posts'];
?>

<?= $this->render('_list', [
    'dataProvider' => $dataProvider,
]) ?>


