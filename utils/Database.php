<?php

/**
 * Created by PhpStorm.
 * User: ale
 * Date: 4/10/15
 * Time: 16:09
 */
class DataBase
{
    const USER = 'root';
    const PASSWORD = 'root';
    private static $connection = null;

    public static function connect()
    {
        if (!self::$connection) {
            self::$connection = new PDO('mysql:host=localhost;dbname=ping-pong', self::USER, self::PASSWORD);
        }
        return self::$connection;
    }

}