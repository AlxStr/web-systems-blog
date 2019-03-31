<?php
$this->params['breadcrumbs'][] = ['label' => 'Search'];
?>

<?= $this->render('_list', [
    'dataProvider' => $dataProvider,
]) ?>


