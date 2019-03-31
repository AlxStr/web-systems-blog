<?php

namespace app\models\forms;


use yii\base\Model;

class SearchForm extends Model
{
    public $text;

    public function rules(): array
    {
        return [
            [['text'], 'string', 'min' => 2, 'max' => 100],
        ];
    }

    public function formName()
    {
        return '';
    }

    public function attributeLabels()
    {
        return [
            'text' => '',
        ];
    }

}