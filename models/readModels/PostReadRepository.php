<?php

namespace app\models\readModels;

use app\models\Category;
use app\models\forms\SearchForm;
use app\models\Post;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class PostReadRepository
{

    public function find($id): ?Post
    {
        return Post::find()->active()->andWhere(['id' => $id])->one();
    }

    public function getAllActive(){
        $query = Post::find()->active()->with('category', 'postAuthor');
        return $this->getProvider($query);
    }

    public function getAll(){
        $query = Post::find()->with('category', 'postAuthor');
        return $this->getProvider($query);
    }

    public function getAllByCategory(Category $category): DataProviderInterface
    {
        $query = Post::find()->active()->andWhere(['category_id' => $category->id])->with('category', 'postAuthor');
        return $this->getProvider($query);
    }

    public function search(SearchForm $form): DataProviderInterface
    {
        $query = Post::find()->active()->with('category');
        if (!empty($form->text)) {
            $query->andWhere(['or', ['like', 'body', $form->text], ['like', 'title', $form->text]]);
        }
        return $this->getProvider($query);
    }

    private function getProvider(ActiveQuery $query): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'updated_at' => SORT_DESC,
                ],
                'attributes' => [
                    'id' => [
                        'asc' => ['id' => SORT_ASC],
                        'desc' => ['id' => SORT_DESC],
                    ],
                    'title' => [
                        'asc' => ['title' => SORT_ASC],
                        'desc' => ['title' => SORT_DESC],
                    ],
                    'updated_at' => [
                        'asc' => ['updated_at' => SORT_ASC],
                        'desc' => ['updated_at' => SORT_DESC],
                    ],
                ],
            ],
            'pagination' => [
                'pageSizeLimit' => [5, 50],
                'pageSize' => \Yii::$app->request->get('per-page') ?? 10,
            ]
        ]);
    }



}