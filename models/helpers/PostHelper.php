<?php
namespace app\models\helpers;
use app\models\Post;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
class PostHelper
{
    public static function statusList(): array
    {
        return [
            Post::INACTIVE => 'Under moderation',
            Post::ACTIVE => 'Active',
        ];
    }
    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }
    public static function statusLabel($status): string
    {
        switch ($status) {
            case Post::INACTIVE:
                $class = 'label label-default';
                break;
            case Post::ACTIVE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }
        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }
}