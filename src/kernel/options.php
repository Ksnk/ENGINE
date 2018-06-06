<?php
/**
 * корневой файл работы с параметрами
 */

class engine_options
{
    /* <% POINT::start('ENGINE_header') %>*/
    static private $options = array(); // пары: имя-значение
    static private $transport = array(); // пары: имя - механизм сохранения
    static public $default_transport = '';
    static private $transports = array(); // строка -> объект

    /* <% POINT::start('ENGINE_body') %>*/
    /**
     * Выдать параметр по имени
     * @param string|array $name
     * @param mixed $default
     * @return mixed
     */
    static public function option($name = '', $default = '')
    {
        if (array_key_exists($name, self::$transport)) {
            $x = call_user_func(array(self::$transport[$name], 'get'), $name);
            if (is_null($x)) return $default;
            return $x;
        } else if (array_key_exists($name, self::$options)) {
            return self::$options[$name];
        } else {
            return $default;
        }
    }

    /**
     * @static
     * @param string|array $name
     * @param null $value
     * @param string|\object $transport
     * @return bool|mixed
     */
    static public function set_option($name = '', $value = null, $transport = '')
    {
        if (is_array($name))
            $transport = $value;

        if (!empty($transport) && is_string($transport) && !isset(self::$transports[$transport])) {
            self::read_options($transport);
            $transport = self::$transports[$transport];
        }

        if (empty($name)) {
            foreach (self::$transports as $v) {
                if (is_callable(array($v, 'save')))
                    call_user_func(array($v, 'save'));
            }
            return true;
        } else if (is_array($name)) {
            foreach ($name as $k => $v) {
                if (!is_numeric($k))
                    self::set_option($k, $v, $transport);
                else
                    self::$transport[$v] = $transport;
            }
            return true;
        } else if (!empty($transport)) {
            self::$transport[$name] = $transport;
        }
        if (array_key_exists($name, self::$transport)) {
            $transport = self::$transport[$name];
        } else if (!empty(self::$default_transport)) {
            $transport = self::$default_transport;
        } else {
            if (!is_null($value)) {
                if (!is_array($value)
                    || !isset(self::$options[$name])
                    || !is_array(self::$options[$name])
                    || !array_key_exists($name, self::$options)
                ) {
                    self::$options[$name] = $value;
                } else {
                    array_merge_deep(self::$options[$name], $value);
                }
                return true;
            } else if (array_key_exists($name, self::$options))
                return self::$options[$name];
            else
                return null;
        }
        if (is_string($transport))
            class_exists($transport);
        if (!is_null($value)) {
            call_user_func(array($transport, 'set'), $name, $value);
            return true;
        } else {
            return call_user_func(array($transport, 'get'), $name);
        }
    }

    /**
     * Создать пакет параметров
     * @param string $transport
     */
    static public function read_options($transport = '')
    {
        if (!empty($transport)
            && !array_key_exists($transport, self::$transports)
        ) {
            // отделяем имя от параметра
            $x = explode('|', $transport . '|');
            $y = explode('~', $x[0] . '~');
            if (is_callable(array('engine_options_' . $y[0], 'init')))
                self::$transports[$transport] =
                    call_user_func(array('engine_options_' . $y[0], 'init'), $y[1], $x[1]);
            else
                self::$transports[$transport] = 'engine_options_' . $y[0];
        }
    }

    static function slice_option($start)
    {
        $res = array();
        $reg = '#^' . preg_quote($start) . "(.*)$#";
        foreach (self::$transport as $k => $v) {
            if (preg_match($reg, $k, $m)) {
                $v = self::option($k);
                $res[$m[1]] = $v;
            }
        }
        foreach (self::$options as $k => $v) {
            if (preg_match($reg, $k, $m)) {
                $res[$m[1]] = $v;
            }
        }
        return $res;
    }

    /* <% POINT::finish() %>*/
}

/* <% POINT::start('ENGINE_bottom') %>*/
/**
 * умная склейка массивов в глубину
 * @param $tomerge
 * @param $part
 * @return bool
 */
function array_merge_deep(&$tomerge, $part)
{
    $result = false;
    // ассоциативный массив
    foreach ($part as $k => &$v) {
        if (array_key_exists($k, $tomerge)) {
            if (is_array($tomerge[$k]) && is_array($v)) {
                $result = array_merge_deep($tomerge[$k], $v) || $result;
            } elseif (is_null($v)) {
                if (isset($tomerge[$k])) {
                    unset($tomerge[$k]);
                    $result = true;
                }
            } else {
                if (isset($tomerge[$k]) && $tomerge[$k] != $v) {
                    $tomerge[$k] = $v;
                    $result = true;
                }
            }
        } elseif (!is_null($v)) {
            $tomerge[$k] = $v;
            $result = true;
        }
    }
    unset ($v);
    return $result;
}

/**
 * умная очистка значений в глубину
 * @param $tomerge
 * @param $part
 * @return bool
 */
function array_clear_deep(&$tomerge, &$part)
{
    $result = false;
    foreach ($part as $k => &$v) {
        if (array_key_exists($k, $tomerge)) {
            if (is_array($tomerge[$k]) && is_array($v)) {
                $result = array_clear_deep($tomerge[$k], $v) || $result;
                if (count($tomerge[$k]) == 0) {
                    unset($tomerge[$k]);
                    $result = true;
                }
            } else {
                if (isset($tomerge[$k])) {
                    unset($tomerge[$k]);
                    $result = true;
                }
            }
        }
    }
    unset ($v);
    return $result;
}
/* <% POINT::finish() %>*/
