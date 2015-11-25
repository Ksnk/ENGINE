<?php
/**
 * Created by JetBrains PhpStorm.
 * User: —ергей
 * Date: 07.10.12
 * Time: 22:48
 * To change this template use File | Settings | File Templates.
 */
/**
 * класс дл€ хранени€ параметров в сессии
 */
class engine_options_cookie
{

    var $options =array();

    function get($name)
    {
        return isset($_COOKIE[$name])?$_COOKIE[$name]:null;
    }

    function set($name, $value = null)
    {
        if (is_null($value))
            setcookie($name, '', 1);
        else
            setcookie($name, '', 1);
    }

}