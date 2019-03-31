<?php
namespace app\models\queries;
use app\models\Post;
use yii\db\ActiveQuery;

class PostQuery extends ActiveQuery
{
    public function active($alias = null)
    {
        return $this->andWhere([
            ($alias ? $alias . '.' : '') . 'status' => Post::ACTIVE,
        ]);
    }
}