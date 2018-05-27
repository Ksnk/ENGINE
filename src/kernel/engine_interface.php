<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Сергей
 * Date: 08.06.13
 * Time: 13:32
 * To change this template use File | Settings | File Templates.
 */

class ENGINE_interface
{
    /* <% POINT::start('ENGINE_header') %>*/

    static private $interface = array();

    /* <% POINT::start('ENGINE_body') %>*/
    /**
     * Регистрация нового интерфейса или сброс, если второго параметра нет
     *
     * @static
     * @param $method - имя метода
     * @param null|callable $callable - параметры регистрации
     * @return null - последнее значение хандлера для возможности вернуть предыдущее
     */
    static public function register_interface($method, $callable = null)
    {
        $result = null;
        if (!is_string($method)) {
            ENGINE::error('wrong interface definition');
            return $result;
        }
        if (isset(self::$interface[$method])) {
            // so return the past one.
            $result = self::$interface[$method];
        }
        if (is_null($callable))
            unset(self::$interface[$method]);
        else
            self::$interface[$method] = $callable;
        return $result;
    }

    /**
     * Just a little magic
     * Простой механизм для монтажа расширений
     *
     * @param $method
     * @param $args
     * @return mixed
     */

    static public function __callStatic($method, $args)
    {
        return ENGINE::exec(self::$interface[$method], $args);
    }

    /* <% POINT::finish() %>*/
}
