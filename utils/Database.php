<?php

/**
 * Created by PhpStorm.
 * User: ale
 * Date: 4/10/15
 * Time: 16:09
 */
class DataBase
{
    const
        DB_USER = 'DB_USER',
        DB_PWD = 'DB_PWD',
        DB_NAME = 'DB_NAME',
        DB_HOST = 'DB_HOST';


    private static $connection = null;

    public static function connect()
    {
        if (!self::$connection) {
            $db_host = getenv(self::DB_HOST);
            $db_user = getenv(self::DB_USER);
            $db_pwd = getenv(self::DB_PWD);
            $db_name = getenv(self::DB_NAME);
            self::$connection = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8", $db_user, $db_pwd);
        }
        return self::$connection;
    }
}