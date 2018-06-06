<?php
/**
 * Одна база на проект.
 * Доступ к базе примитивом ENGINE::db() с параметрами, которые передаются в базу
 * данных. Класс базы доступен по алиасу Database
 *
 * User: Сергей
 * Date: 08.06.13
 * Time: 13:48
 * To change this template use File | Settings | File Templates.
 */

class ENGINE_db_handler
{

    /* <% POINT::start('ENGINE_header') %>*/
    /** @var <%=$ns%>xDatabaseLapsi */
    static private $db = null;

    /* <% POINT::start('ENGINE_body') %>*/

    /**
     * хяндлер ENGINE::DB
     *
     * @param string $option строка параметров
     *
     * @return <%=$ns%>xDatabaseLapsi
     */
    static public function &db($option = '')
    {
        if (empty(self::$db)) {
            self::$db = self::getObj('Database', $option);
        }
        if (!empty(self::$db)) self::$db->set_option($option);
        return self::$db;
    }

    /* <% POINT::finish() %>*/

    static public function _report()
    {
        /* <% POINT::start('ENGINE_final_report') %>*/
        if (!empty(self::$db)) {
            echo self::$db->report();
        }
        /* <% POINT::finish() %>*/
    }

}