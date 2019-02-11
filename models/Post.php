<?php

namespace app\models;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * This is the model class for table "posts".
 *
 * @property int $id
 * @property int $category_id
 * @property string $logo
 * @property string $title
 * @property string $body
 * @property Categories $category
 */
class Post extends \yii\db\ActiveRecord
{
    const INACTIVE = 0;
    const ACTIVE = 1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%posts}}';
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
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['photo'],
            ],
        ];
    }
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public function updatePhoto(UploadedFile $photo): void
    {
        $this->photo = Photo::create($photo);;
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
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getPostAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author']);
    }

    public function getPhoto(){
        return $this->hasOne(Photo::class, ['post_id' => 'id']);
    }

    public function getStatusName(){
        $list = self::getStatusList();
        return $list[$this->status];
    }

    public static function getStatusList(){
        return ['0' => 'Ожидает модерации', '1' => 'Активно'];
    }
}
