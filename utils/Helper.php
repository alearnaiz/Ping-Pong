<?php

/**
 * Created by PhpStorm.
 * User: ale
 * Date: 10/10/15
 * Time: 20:29
 */
class Helper
{

    public static function getDateFormat($date_time)
    {
        return date('d/m/Y', strtotime($date_time));
    }

    public static function getTimeFormat($date_time)
    {
       return date('H:i', strtotime($date_time));
    }

    public static function isPowerOfTwo($number)
    {
        while (($number % 2 == 0) && $number > 1) {
            $number = $number /2;
        }

        return $number == 1;
    }
}