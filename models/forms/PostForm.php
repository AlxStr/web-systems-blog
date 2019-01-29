<?php

namespace app\models\forms;

use app\models\Category;
use yii\base\Model;


class PostForm extends Model
{
    public $id;
    public $title;
    public $category_id;
    public $logo;
    public $description;
    public $body;
    public $status;
    public $author;
    public $imageFile;
    public $created_at;
    public $updated_at;

    public function rules()
    {
        return [
            [['category_id'], 'integer'],
            [['body', 'description'], 'string'],
            [['title', 'body'], 'required'],
            [['description'], 'required'],
            [['title', 'logo'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'category_id' => 'Категория',
            'logo' => 'Логотип',
            'status' => 'Статус',
            'title' => 'Заголовок',
            'description' => 'Описание',
            'body' => 'Текст',
            'author' => 'Автор'
        ];
    }
}