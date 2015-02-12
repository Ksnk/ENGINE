<?php
/**
 * статический класс - преставитель CMS в космосе.
 * <%=point('hat','comment');




%>
 */

/* <%=POINT::get('ENGINE_top') %> */

/**
 * @method static xDatabase db
 * @method static bool has_rights
 * @method static set_option
 * @method static link
 * @method static action
 * @method static run
 */
class ENGINE
{

    private static $class_list = array();
    private static $class_alias = array();
    private static $SUBDIR='';

    /* <%=POINT::get('ENGINE_header') %> */

    /**
     * простейший строковый шаблон для вывода минимально параметризованных строк.
     * a-la YII
     * @static
     * @param $msg
     * @param $arg
     * @return mixed
     */
    static public function _t($msg, $arg)
    {
        if (is_array($arg) && count($arg) > 0) {
            foreach ($arg as $k => $v) {
                if (is_object($v)) {
                    $v = get_class($v);
                }
                $msg = str_replace($k, $v, $msg);
            }
        }
        return $msg;
    }

    /**
     * функция вывода сообщения об ошибке. Управляется функция с помошью параметров
     * --error.handler и error.format
     * стандартный handler - printf
     * для ajax
     *
     * @param string $msg - собственно собщение
     * @param array $par - параметры для функции _t
     * @param array $backtrace - параметры для вывода backtrace
     * @return mixed
     */
    static function error($msg, $par = array(), $backtrace = array())
    {
        $error_handler = self::option('error.handler', 'echo');
        $error_format = self::option('error.format'
            , "<!--xxx-error-{backtrace}\n{msg}\n-->");
        $msg = self::_t($error_format, array(
            '{backtrace}' => self::backtrace($backtrace),
            '{msg}' => self::_t($msg, $par)
        ));
        if ($error_handler == 'echo' || $error_handler == 'printf')
            echo $msg;
        else
            call_user_func($error_handler, self::_t($error_format, array(
                '{backtrace}' => self::backtrace($backtrace),
                '{msg}' => self::_t($msg, $par)
            )));
        return false;
    }

    /*
    if (array_key_exists('error', self::$interface)) {
            return call_user_func(self::$interface['error'], $msg, $args, $rule);
        }

        $error = ENGINE::option('page.error');
        if (!empty($error)) $error .= "<hr>\n";
        ENGINE::set_option('page.error', $error . self::_t($msg, $args));
        //echo self::_t($msg, $args) . " <br>\n";
        return null;
    }
*/

    /**
     * выдать имя алиаса по имени класса
     * @static
     * @param $object
     * @return mixed|string
     */
    static function get_class($object)
    {
        if (method_exists($object, 'get_alias')) {
            $classname = $object->get_alias();
        } else {
            $classname = get_class($object);
            $key = array_search($classname, self::$class_alias);
            if ($key !== false) {
                $classname = $key;
            }
        }
        return $classname;
    }

    /**
     * place object in factory storage with selected name
     * @param $name
     * @param $obj
     */
    static function setObj($name, &$obj)
    {
        self::$class_list[$name] = $obj;
    }

    static function callFirst($method, $par)
    {
        foreach (self::$class_list as $k => &$v)
            if (method_exists($v, $method)) {
                if (!is_null($x = call_user_func_array(array(&$v, $method), $par)))
                    return $x;
            }
        return null;
    }

    static function callAll($method, $par = '')
    {
        $result = false;
        foreach (self::$class_list as $k => &$v)
            if (method_exists($v, $method)) {
                $result = $result || call_user_func(array($v, $method), $par);
            }
        return $result;
    }

    /**
     * @param $name
     * @return string - aliaced name of class
     */
    static function getAliace($name)
    {
        if (empty(self::$class_alias)) {
            self::$class_alias = ENGINE::option('engine.aliaces');
        }
        if (isset(self::$class_alias[$name]))
            return self::$class_alias[$name];
        return $name;
    }

    /**
     * get an object from alias record
     * @static
     * @param $name
     */
    static function getObj($name, $par = null)
    {
        $dir = '';
        if (($pos = strrpos($name, '/')) !== false) {
            $dir = substr($name, 0, $pos + 1);
            $name = substr($name, $pos + 1);
        }
        self::$SUBDIR=$dir;
        if (!isset(self::$class_list[$name])) {
            $class = self::getAliace($name);
            if (class_exists($class)) {
                self::$class_list[$name] = new $class($par);
            } else {
                if ($name == $class) {
                    self::error(
                        'class `{class}` not found in (cwd:{dir})',
                        array('{class}' => $class, '{dir}' => getcwd())
                    );
                } else {
                    self::error(
                        'class `{class}({name})` not found in (cwd:{dir})',
                        array(
                            '{name}' => $name,
                            '{class}' => $class,
                            '{dir}' => getcwd()
                        )
                    );
                }
                return null;
            }
        }
        return self::$class_list[$name];
    }

    /**
     * вызвать класс-метод.
     * Если класс из массива - вызывать объект из фабрики.
     *
     * @param callable $func
     * @param null|array $args
     * @param string $error_rule
     * @return mixed
     */
    static function exec(&$func, $args = array(), $error_rule = '')
    {
        if (is_array($func) && is_string($func[0])) {
            $func[0] = self::getObj($class = $func[0]);
        }
        if (is_callable($func)) {
            if (is_array($args))
                return call_user_func_array($func, $args);
            else
                return call_user_func($func);
        }

        if (empty($func)) {
            // попытка выковырять параметры со стека вызовов
            $db = debug_backtrace();
            switch ($db[1]['function']) {
                case '__callStatic':
                    $func = $db[1]['args'][0];
                    break;
                default:
                    $func = "I dont know how to dig for function name.";
            }
        }

        if (is_array($func)) {
            if (empty($class)) {
                $class = get_class($func[0]);
            }
            ENGINE::error('unresolved callable {{class}}({{real}})->{{method}}', array('{{class}}' => $class, '{{method}}' => $func[1], '{{real}}' => $class), $error_rule);
        } else
            ENGINE::error('unresolved callable {{function}}', array('{{function}}' => $func), $error_rule);
        return '';
    }

    static function _(&$val, $def = '')
    {
        return empty($val) ? $def : $val;
    }

    static function template($name, $method, $par = array())
    {
        static $cache;
        if (is_array($name)) {
            if ($method == '_') {
                $method = '_' . $name[1];
                $name = $name[0];
            } else {
                $name = $name[0];
            }
        }
//$par = array_merge($this->par, $par);
        if (empty($cache[$name])) {
            if (!class_exists($name)) {
                ENGINE::error('method {{method}} not found',
                    array('{{method}}' => $method . '::' . $name),
                    array('function' => 'template', 'count' => 4)
                );
                return '';
            }
            $cache[$name] = new $name();
        }
//debug($name,$par);
        $x = $cache[$name]->$method($par);
        return $x;
    }

    /* <%=POINT::get('ENGINE_body') %> */
}

/* <%=POINT::get('ENGINE_bottom') %> */
