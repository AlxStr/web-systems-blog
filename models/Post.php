<?php

namespace app\models;

use app\models\behaviors\FileUploadBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "posts".
 *
 * @property int $id
 * @property int $category_id
 * @property string $logo
 * @property string $title
 * @property string $description
 * @property string $body
 * @property int $author
 * @property Categories $category
 */
class Post extends \yii\db\ActiveRecord
{
    const INACTIVE = 0;
    const ACTIVE = 1;

    public static function tableName()
    {
        return '{{%posts}}';
    }

    public function fields() {
        return [
            'id',
            'title',
            'category' => function(){
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->title,
                ];
            },
            'logo' => function(){
                return $this->logo ? $this->getPhotoUrl():null;
            },
            'status' => function(){
                return [
                    'code' => $this->status,
                    'active' => boolval($this->status)
                ];
            },
            'author' => function(){
                return [
                    'id' => $this->author,
                    'username' => $this->postAuthor->username,
                ];
            },
            'description',
            'body',
        ];
    }

    public static function create($title, $categoryId, $description, $body): self
    {
        $post = new static();
        $post->title = $title;
        $post->category_id = $categoryId;
        $post->description = $description;
        $post->body = $body;
        $post->author = Yii::$app->user->identity->getId();
        $post->created_at = time();
        $post->updated_at = time();

        return $post;
    }

    public function edit($title, $categoryId, $description, $body): void
    {
        $this->title = $title;
        $this->category_id = $categoryId;
        $this->description = $description;
        $this->body = $body;
        $this->updated_at = time();
    }

    public function activate(){
        return $this->status = Post::ACTIVE;
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => FileUploadBehavior::class,
                'folder' => getenv('UPLOAD_IMAGES_FOLDER'),
            ]
        ];
    }

    public static function findPost($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getPostAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'author']);
    }
}
