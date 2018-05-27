<?php
/**
 * Реализация инерфейса cache для варианта с xcached
 * User: Сергей
 * Date: 10.06.13
 * Time: 10:52
 * To change this template use File | Settings | File Templates.
 */

class ENGINE_apc
{
    /*<% POINT::start('ENGINE_body'); %>*/
    /**
     * @static
     * @param $key
     * @param null $value
     * @param int $time в секундах 28800 - 8 часов
     * @return bool|null
     */
    static function cache($key, $value = null, $time = 28800)
    {
        if (!function_exists('apc_fetch')) return false;
        if (strlen($key) > 40) $key = md5($key);
        if (is_null($value)) {
            if ($GLOBALS['SERVER_PORT'] == "8080") return false;
            $success = true;
            $value = apc_fetch($key, $success);
            if (!$success) return false;
            return $value;
        } else {
            apc_store($key, $value, $time);
        }
        return false;
    }
    /*<% POINT::finish(); %>*/
}