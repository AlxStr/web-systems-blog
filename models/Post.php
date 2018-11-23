<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

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

    public $imageFile;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%posts}}';
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
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

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
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
