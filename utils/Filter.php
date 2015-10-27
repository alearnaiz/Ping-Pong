<?php
/**
 * Created by PhpStorm.
 * User: ale
 * Date: 17/10/15
 * Time: 18:05
 */


class Filter
{
    public static function isRegisteredUser($app) {
        return function() use($app) {
            if (!isset($_SESSION['id'])) {
                $app->redirectTo('login');
            }
        };
    }
}
