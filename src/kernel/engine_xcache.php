<?php
/**
 * ���������� ��������� cache ��� �������� � xcached
 * User: ������
 * Date: 10.06.13
 * Time: 10:52
 * To change this template use File | Settings | File Templates.
 */

class ENGINE_xcache {
    /*<% POINT::start('ENGINE_body'); %>*/
    /**
     *  ������ � xcached
     * @static
     * @param $key
     * @param null $value
     * @param int $time � �������� 28800 - 8 �����
     * @return bool|null
     */
    static function cache($key, $value = null, $time = 28800)
    {
        if(!function_exists('xcache_isset')) return false;
        if (strlen($key) > 40) $key = md5($key);
        if (is_null($value)) {
            if ($GLOBALS['SERVER_PORT'] == "8080") return false;
            if(!xcache_isset($key)) return false;
            return xcache_get($key);
        } else {
            xcache_set($key, $value, $time);
        }
        return false;
    }
    /*<% POINT::finish(); %>*/
}