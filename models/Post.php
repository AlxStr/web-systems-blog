<?php

namespace app\models;

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

    public static function create($title, $categoryId, $description, $body, $logo = null): self
    {
        $post = new static();
        $post->title = $title;
        $post->category_id = $categoryId;
        $post->description = $description;
        $post->body = $body;
        $post->author = Yii::$app->user->identity->getId();
        $post->logo = $logo;
        $post->created_at = time();
        $post->updated_at = time();

        return $post;
    }

    public function edit($title, $categoryId, $description, $body, $logo = null): void
    {
        $this->title = $title;
        $this->category_id = $categoryId;
        $this->description = $description;
        $this->body = $body;
        $logo != null ? $this->logo = $logo: null;
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
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getPostAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author']);
    }

    public function getStatusName(){
        $list = self::getStatusList();
        return $list[$this->status];
    }

    public static function getStatusList(){
        return ['0' => 'Ожидает модерации', '1' => 'Активно'];
    }
}
