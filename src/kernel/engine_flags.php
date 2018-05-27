<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Сергей
 * Date: 27.05.13
 * To change this template use File | Settings | File Templates.
 */
class DBG
{
    /* <% POINT::start('ENGINE_header') %>*/
    static $cookie_name = 'testing';

    /* <% POINT::start('ENGINE_body') %>*/

    /**
     * @param $flag
     * @return int
     * @sample if(DBG::hasflag('test1')){ ...
     */
    static function hasflag($flag)
    {
        return isset($_COOKIE) &&
            isset($_COOKIE[self::$cookie_name]) &&
            preg_match('/\b' . preg_quote($flag) . '\b/', $_COOKIE[self::$cookie_name]);
    }

    /**
     * setting up cookie in case user want to start debuging
     * @return bool
     * @sample if(DBG::initflags()) header('location: '.#clear URL from testing parameter#)
     */
    static function initflags()
    {
        if (isset($_GET) && isset($_GET[self::$cookie_name]) && !preg_match('/[^\w,\+\-\.\s]/', $_GET[self::$cookie_name])) {
            if (preg_match('/[\-\s\+]/', $_GET[self::$cookie_name])) {
                if (isset($_COOKIE[self::$cookie_name])) {
                    $cookie = preg_split('/[,]+/', $_COOKIE[self::$cookie_name]);
                } else {
                    $cookie = array();
                }
                if (preg_match_all('/([\s\-\+])([\w]*)/', $_GET[self::$cookie_name], $m))
                    foreach ($m[0] as $k => $v) {
                        if ($m[1][$k] != '-') $cookie[] = $m[2][$k];
                        else {
                            $cookie = array_diff($cookie, array($m[2][$k]));
                        }
                    }
                $cookie = implode(',', array_unique($cookie));
            } else {
                $cookie = trim($_GET[self::$cookie_name]);
            }
            if (!empty($cookie))
                setcookie(self::$cookie_name, $cookie);
            else
                setcookie(self::$cookie_name, "", time() - 3600);
            return true;
        }
        return false;
    }
    /* <% POINT::finish() %>*/
}
