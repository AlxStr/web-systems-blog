<?php

namespace app\models\forms;

use app\models\Category;
use app\models\Post;
use yii\base\Model;


class PostEditForm extends Model
{
    public $id;
    public $title;
    public $category_id;
    public $description;
    public $body;
    public $status;
    public $author;
    public $created_at;
    public $updated_at;


    private $_post;

    /**
     * PostForm constructor.
     * @param $_photo
     */
    public function __construct(Post $post, $config = [])
    {
        $this->title = $post->title;
        $this->category_id = $post->category_id;
        $this->description = $post->description;
        $this->body = $post->body;
        $this->_post = $post;

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
}