<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property string $title
 *
 * @property Posts[] $posts
 */
class Category extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%categories}}';
    }

    public static function create($title)
    {
        $cat = new static();
        $cat->title = $title;

        return $cat;
    }
    public function edit($title)
    {
        $this->title = $title;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['category_id' => 'id']);
    }

    public static function getTitle($id)
    {
        $title = Category::find()->where(['id' => $id])->one();
        return $title;
    }
}
