<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
?>

<div class="client-default-index">
    <div class="row">
        <div class="col-md-4 col-md-offset-2">
                <?php $form = ActiveForm::begin(['action' => ['post/search'], 'method' => 'get']); ?>
                <label for="search">Search</label>
                <div class="input-group">
                        <input id="search" type="text" name="text" placeholder="Type query" class="form-control" value="">
                    <div class="input-group-btn">
                        <?= Html::submitButton('Search', ['class' => 'btn btn-default']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
        </div>

        <div class="col-md-3">
            <label for="input-sort">Sort By:</label>
            <select id="input-sort" class="form-control" onchange="location = this.value;">
                <?php

                $values = [
                    '' => 'Newest',
                    'updated_at' => 'Oldest',
                    'title' => 'Name (A - Z)',
                    '-title' => 'Name (Z - A)',
                ];
                $current = Yii::$app->request->get('sort');
                ?>
                <?php foreach ($values as $value => $label): ?>
                    <option value="<?= Html::encode(Url::current(['sort' => $value ?: null])) ?>" <?php if ($current == $value): ?>selected="selected"<?php endif; ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label for="input-limit">Per page</label>
            <select id="input-limit" class="form-control" onchange="location = this.value;">
                <?php
                $values = [5, 10, 20];
                $current = $dataProvider->getPagination()->getPageSize();
                ?>
                <?php foreach ($values as $value): ?>
                    <option value="<?= Html::encode(Url::current(['per-page' => $value])) ?>" <?php if ($current == $value): ?>selected="selected"<?php endif; ?>><?= $value ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <br>
    <?php if (!empty($posts = $dataProvider->getModels())): ?>
        <?php foreach ($posts as $post): ?>
            <div class="well">
                <div class="media">
                    <a class="pull-left" href="<?= Url::toRoute(['post/view', 'id' => $post->id])?>">
                        <?= Html::img($post->getPhotoUrl(), ['class' => 'media-object', 'width' => '150px', 'height' => '150px']) ?>
                    </a>
                    <div class="media-body">
                        <h4 class="media-heading"><?= Html::a(Html::encode($post->title), ['post/view', 'id' => $post->id]) ?></h4>
                        <p>
                            <?= Html::encode($post->description) ?>
                        </p>
                        <br>
                        <br>
                        <br>
                        <ul class="list-inline list-unstyled">
                            <li><span><i class="glyphicon glyphicon-calendar"></i> <?= Yii::$app->formatter->asDatetime($post->updated_at) ?></span></li>
                            <li><span>Category: <?= Html::a(Html::encode($post->category->title), ['post/category', 'id' => $post->category->id]) ?></span></li>
                            <li><span>Author: <?= Html::encode($post->postAuthor->username) ?></span></li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="panel panel-default">
            <div class="panel-body">
                No posts here :c
            </div>
        </div>
    <?php endif;?>

<?php
if (!empty($posts)){
    echo LinkPager::widget([
        'pagination' => $dataProvider->getPagination(),
        // Отключаю ссылку "Следующий"
        'nextPageLabel' => false,
        // Отключаю ссылку "Предыдущий"
        'prevPageLabel' => false,
    ]);
}
?>
</div>

