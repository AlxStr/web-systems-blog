<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            ['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {ban}',
                'buttons' => [
                    'ban' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-minus-sign" title="Ban\Unban"></span>', $url);
                    },
                ],
            ],

            'id',
            'username',
            'role',
            'email:email',
            [
                    'attribute' => 'status',
                    'filter' => app\models\User::getStatusList(),
                    'value' =>  'statusName',
            ],
            'created_at:datetime',
            ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}'],


        ],
    ]); ?>
</div>
