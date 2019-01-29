<?php

namespace app\models\repositories;

use app\models\Post;
use yii\web\NotFoundHttpException;

class PostRepository
{
    public function save(Post $post): Post
    {
        if (!$post->save()){
            throw new \RuntimeException('Saving error.');
        }
        return $post;
    }

    public function getAllPosts()
    {
        return Post::find()->all();
    }
    public function getAllPostsWhere($query)
    {
        return Post::findAll($query);
    }

    public function getAllActivePosts()
    {
        return $this->getAllPostsWhere(['status' => Post::ACTIVE]);
    }
    public function getAllInactivePosts()
    {
        return $this->getAllPostsWhere(['status' => Post::INACTIVE]);
    }

    public function get($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}