<?php

namespace app\models\helpers;

use app\models\repositories\CategoryRepository;
use yii\helpers\ArrayHelper;

class CategoryHelper
{
    public function getCategoriesList(){
        return ArrayHelper::map((new CategoryRepository())->getAllCategories(), 'id', 'title');
    }
}