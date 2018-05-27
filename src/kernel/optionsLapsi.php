<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Сергей
 * Date: 20.07.13
 * Time: 15:06
 * To change this template use File | Settings | File Templates.
 */
class optionsLapsi
{
    /* <% POINT::start('ENGINE_header') %>*/
    static private $options = array(); // пары: имя-значение

    /* <% POINT::start('ENGINE_body') %>*/
    /**
     * Выдать параметр по имени
     * @param string|array $name
     * @param mixed $default
     * @return mixed
     */

    static public function option($name = '', $default = '')
    {
        if (array_key_exists($name, self::$options)) {
            return self::$options[$name];
        } else {
            return $default;
        }
    }

    /**
     * @static
     * @param string|array $name
     * @param null $value
     * @return bool|mixed
     */
    static public function set_option($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                if (!is_numeric($k))
                    self::set_option($k, $v);
            }
            return;
        }
        if (!is_null($value)) {
            if (!is_array($value) || !array_key_exists($name, self::$options)) {
                self::$options[$name] = $value;
            } else {
                self::$options[$name] = array_merge(self::$options[$name], $value);
            }
        }
    }

    static function slice_option($start)
    {
        $res = array();
        $reg = '#^' . preg_quote($start) . "(.*)$#";
        foreach (self::$options as $k => $v) {
            if (preg_match($reg, $k, $m)) {
                $res[$m[1]] = $v;
            }
        }
        return $res;
    }

    /* <% POINT::finish() %>*/

}