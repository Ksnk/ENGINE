<?php
/**
 * Реализация инерфейса cache для варианта с xcached
 * User: Сергей
 * Date: 10.06.13
 * Time: 10:52
 * To change this template use File | Settings | File Templates.
 */

class ENGINE_nocache
{
    /*<% POINT::start('ENGINE_body'); %>*/
    /**
     *  доступ к xcached
     * @static
     * @param $key
     * @param null $value
     * @param int $time в секундах 28800 - 8 часов
     * @return bool|null
     */
    static function cache($key, $value = null, $time = 28800)
    {
        return false;
    }
    /*<% POINT::finish(); %>*/
}