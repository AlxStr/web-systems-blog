<?php
namespace app\modules\v1\models\helpers;
class DateHelper
{
    public static function formatApi($timestamp)
    {
        return $timestamp ? date(DATE_RFC3339, $timestamp) : null;
    }
}