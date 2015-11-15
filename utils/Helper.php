<?php

/**
 * Created by PhpStorm.
 * User: ale
 * Date: 10/10/15
 * Time: 20:29
 */
class Helper
{

    public static function getDateTime($time, $date)
    {
        return $date . ' ' . $time;
    }

    public static function getDateFormat($dateTime)
    {
        return date('d/m/Y', strtotime($dateTime));
    }

    public static function getTimeFormat($dateTime)
    {
       return date('H:i', strtotime($dateTime));
    }
}