<?php

namespace App\Helpers;

class Helper
{
    public static function formatPrice($price)
    {
        return '￥'.number_format($price);
    }
}
