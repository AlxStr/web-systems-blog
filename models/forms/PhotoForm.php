<?php

namespace app\models\forms;


use yii\base\Model;
use yii\web\UploadedFile;

class PhotoForm extends Model
{
    public $file;

    public function rules(): array
    {
        return [
            ['file', 'each', 'rule' => ['image']],
        ];
    }

    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->file = UploadedFile::getInstances($this, 'file');
            return true;
        }
        return false;
    }
}