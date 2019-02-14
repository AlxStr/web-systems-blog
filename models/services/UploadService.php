<?php

namespace app\models\services;


use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadService extends Model
{

    public $imageFile;

    public function checkUpload($form)
    {
        $this->imageFile = UploadedFile::getInstance($form, 'imageFile');

        if (isset($this->imageFile)){
            if ($this->upload()) {
                return $this->imageFile->baseName . '.' . $this->imageFile->extension;
            }
            return null;
        }
    }

    public function upload(){

        if ($this->validate()) {
            $this->imageFile->saveAs(Yii::getAlias('@upload') . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            return true;
        } else {
            return false;
        }
    }
}