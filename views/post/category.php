<?php
$this->params['breadcrumbs'][] = ['label' => $category->title, 'url' => ['post/category', 'id' => $category->id]];
?>

<?= $this->render('_list', [
    'dataProvider' => $dataProvider,
]) ?>


