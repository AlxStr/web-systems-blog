<?php

use app\models\helpers\UserHelper;
use app\models\User;
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    ['class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update} {ban} {unban}',
                        'buttons' => [
                            'ban' => function ($url, $model, $key) {
                                if ($model->status == \app\models\User::STATUS_ACTIVE)
                                return Html::a('<span class="glyphicon glyphicon-stop" title="Ban"></span>', $url);
                            },
                            'unban' => function ($url, $model, $key) {
                                if ($model->status == \app\models\User::STATUS_INACTIVE)
                                return Html::a('<span class="glyphicon glyphicon-play" title="Unban"></span>', $url);
                            },
                        ],
                    ],

                    'id',
                    'username',
                    'role',
                    'email:email',
                    [
                        'attribute' => 'status',
                        'filter' => UserHelper::statusList(),
                        'value' => function (User $model) {
                            return UserHelper::statusLabel($model->status);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'created_at',
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'date_from',
                            'attribute2' => 'date_to',
                            'type' => DatePicker::TYPE_RANGE,
                            'separator' => '-',
                            'pluginOptions' => [
                                'todayHighlight'=>true,
                                'autoclose'=>true,
                                'format' => 'yyyy-mm-dd'
                            ]
                        ]),
                        'format' => 'datetime',
                    ],
                    ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}'],

                ],
            ]); ?>
        </div>
    </div>

</div>
