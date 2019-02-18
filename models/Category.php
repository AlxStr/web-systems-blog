<?php

namespace app\models;

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
}
