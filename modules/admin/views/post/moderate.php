<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;

?>
<h2>Модерация</h2>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Все посты', ['index'], ['class' => 'btn btn-info']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {active} {inactive}',
                'buttons' => [
                    'active' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-ok" title="Активно"></span>', $url);
                    },
                ],
            ],
            [
                'attribute' => 'status',
                'value' => 'statusName',
                'filter' => app\models\Post::getStatusList(),
            ],

            'title',
            [
                'attribute' => 'category_id',
                'value' => 'category.title',
                'filter' => $categories,
            ],
            'logo',
            [
                    'attribute' => 'author',
                    'value' => 'postAuthor.username',
            ],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}'],
        ],
    ]);
    ?>
</div>
