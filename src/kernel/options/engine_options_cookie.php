<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Сергей
 * Date: 07.10.12
 * Time: 22:48
 * To change this template use File | Settings | File Templates.
 */
/**
 * класс для хранения параметров в сессии
 */
/* <%=POINT::get('ENGINE_namespace') %> */

class engine_options_cookie implements engine_options
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