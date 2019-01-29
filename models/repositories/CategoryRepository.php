<?php

namespace app\models\repositories;


use app\models\Category;
use yii\web\NotFoundHttpException;

class CategoryRepository
{
    public function save(Category $cat): Category
    {
        if (!$cat->save()){
            throw new \RuntimeException('Saving error.');
        }
        return $cat;
    }
    public function remove(Category $category): void
    {
        if (!$category->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    public function getAllPosts()
    {
        return Category::find()->all();
    }
    public function getAllPostsWhere($query)
    {
        return Category::findAll($query);
    }

    public function get($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}