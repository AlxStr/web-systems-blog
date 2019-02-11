<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use \yiidreamteam\upload\ImageUploadBehavior;

class Photo extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%photos}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => ImageUploadBehavior::class,
                'attribute' => 'file',
                'createThumbsOnRequest' => true,
                'thumbs' => [
                    'admin' => ['width' => 100, 'height' => 70],
                    'thumb' => ['width' => 150, 'height' => 150],
                ],
                'filePath' => '@upload/[[attribute_post_id]]/[[pk]].[[extension]]',
                'fileUrl' => '@upload/[[attribute_post_id]]/[[pk]].[[extension]]',
                'thumbPath' => '@upload/cache/[[attribute_post_id]]/[[profile]]_[[pk]].[[extension]]',
                'thumbUrl' => '@upload/cache/[[attribute_post_id]]/[[profile]]_[[pk]].[[extension]]',
            ],
        ];
    }

    public static function create(UploadedFile $file): self
    {
        $photo = new static();
        $photo->file = $file;
        return $photo;
    }

    public function isIdEqualTo($id): bool
    {
        return $this->id == $id;
    }

}