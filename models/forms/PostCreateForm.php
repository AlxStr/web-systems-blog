<?php

namespace app\models\forms;

use app\models\Category;
use app\models\CompositeForm;


class PostCreateForm extends CompositeForm
{
    public $id;
    public $title;
    public $category_id;
    public $logo;
    public $description;
    public $body;
    public $status;
    public $author;
    public $created_at;
    public $updated_at;

    /**
     * PostForm constructor.
     * @param $_photo
     */
    public function __construct($config = [])
    {
        $this->photo = new PhotoForm();

        parent::__construct($config);
    }


    public function rules()
    {
        return [
            [['category_id'], 'integer'],
            [['body', 'description'], 'string'],
            [['title', 'body'], 'required'],
            [['description'], 'required'],
            [['title'], 'string', 'max' => 255],
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

    protected function internalForms(): array
    {
        return ['photo'];
    }
}