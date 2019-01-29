<?php

namespace app\models\forms;


use yii\base\Model;

class CategoryForm extends Model
{
    public $id;
    public $title;

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
        ];
    }
}