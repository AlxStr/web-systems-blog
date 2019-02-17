<?php
namespace app\models\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\Model;
use yii\web\UploadedFile;

class FileUploadBehavior extends Behavior
{
    public $folder;
    public $placeholder = 'no-image.png';

    private $_file;

    public function check(Model $form)
    {
        $this->_file = UploadedFile::getInstance($form, 'imageFile');
        if (isset($this->_file)){
            return true;
        }
        return false;
    }

    public function loadPhoto(Model $form){
           if ($this->check($form)){
               $this->owner->logo = $this->savePhoto($this->_file);
           }
           return false;
    }

    public function getPhotoUrl(){
        if ($this->owner->logo){
            return sprintf('/%s/%s',  $this->folder, $this->owner->logo);
        }
        return sprintf('/%s/%s',  $this->folder, $this->placeholder);
    }


    public function savePhoto(UploadedFile $file = null){
        if ($file){
            $file->saveAs(Yii::getAlias('@upload') . $file->baseName . '.' . $file->extension);
            return $file->baseName . '.' . $file->extension;
        }
    }
}