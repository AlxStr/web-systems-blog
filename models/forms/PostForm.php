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
            [['title', 'body', 'category_id', 'description'], 'required'],
            [['title', 'logo'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'category_id' => 'Category',
            'logo' => 'Photo\Logotype',
            'status' => 'Status',
            'title' => 'Title',
            'description' => 'Description',
            'body' => 'Content',
            'author' => 'Author'
        ];
    }
}