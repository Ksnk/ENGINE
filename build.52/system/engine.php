<?php
/**
 * статический класс - преставитель CMS в космосе.
 * ------------------------------------------------------------------------------------------
 * $Id: X-Site cms (2.0), written by Ksnk (sergekoriakin@gmail.com)
 *  Rev: 1408, Modified: 
 *  SVN: file:///C:/notebook_svn/svn/xilen/cms$
 * ------------------------------------------------------------------------------------------
 * License MIT - Serge Koriakin - Jule 2012
 * ------------------------------------------------------------------------------------------
 */

/**
 * @method static xDatabase db
 * @method static string template
 * @method static bool has_rights
 * @method static set_option
 * @method static link
 * @method static action
 */
class ENGINE
{

    static private $extensions = array();

    private static $class_list = array();
    private static $class_alias = array();

    /*  --- point::ENGINE_header --- */


    public static $I;

    

    private static $events = array();

    

    static private $options = array(); // пары: имя-значение
    static private $transport = array(); // пары: имя - механизм сохранения
    static public $default_transport = '';
    static private $transports = array(); // строка -> объект

    

    static public $session_started=false;

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
        } else if (is_null($callable)) { // so remove handler
            unset(self::$extensions[$method]);
            return $result;
        } else if (isset(self::$extensions[$method])) {
            $result = self::$extensions[$method];
        }
        ;
        self::$extensions[$method] = $callable;

        return $result;
    }

    /**
     * простейший строковый шаблон для вывода минимально параметризованных строк.
     * a-la YII
     * @static
     * @param $msg
     * @param $arg
     * @return mixed
     */
    static public function _t($msg, &$arg)
    {
        if (is_array($arg) && count($arg) > 0) {
            foreach ($arg as $k => $v) {
                if(is_object($v)){
                    $v=get_class($v);
                }
                $msg = str_replace($k, $v, $msg);
            }
        }
        return $msg;
    }

    /**
     * функция вывода сообщения об ошибке. Не желательно надеятся только на перегрузку метода
     * стандартным интерфейсным механизмом, так как функция обязана работать в любом случае.
     *
     * @param $msg
     * @param null|mixed $args
     * @param string $rule
     * @return mixed
     */
    static public function error($msg, $args = null, $rule = '')
    {
        if (array_key_exists('error', self::$extensions)) {
            return call_user_func(self::$extensions['error'], $msg, $args, $rule);
        } else {
            $error = ENGINE::option('page.error');
            if (!empty($error)) $error .= "<hr>\n";
            ENGINE::set_option('page.error', $error . self::_t($msg, $args));
            //echo self::_t($msg, $args) . " <br>\n";
            return null;
        }
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
        if (array_key_exists($method, self::$extensions)) {
            return self::exec(self::$extensions[$method], $args);
        } else {
            return self::error('ENGINE: Interface `{{interface}}` not defined', array('{{interface}}' => $method));
        }
    }

    /**
     * инициализация системы и прописка основных обработчиков
     * @static
     * @param string|array $options
     */
    static function init($options)
    {
        ini_set('session.use_trans_sid', '0');
        ini_set('session.use_cookies', '1');
        ini_set('session.bug_compat_42', '0');
        ini_set('allow_call_time_pass_reference', '1');

        if (defined('ROOT_URI')) ENGINE::set_option('page.root', ROOT_URI);

        if (is_array($options)) {
            ENGINE::set_option($options);
        } else if (is_readable($options)) {
            ENGINE::set_option(include ($options));
        } else {
            ENGINE::error('Init: parameter failed');
        }
        //////////////////////////////////////////////////////////////////////////////////
// register all default interfaces
        foreach (ENGINE::option('engine.interfaces', array()) as $k => $v)
            ENGINE::register_interface($k, $v);

//////////////////////////////////////////////////////////////////////////////////
// include all classes from `engine.include_files`
        foreach (ENGINE::option('engine.include_files', array()) as $f)
            include_once($f);

        self::$class_alias = ENGINE::option('engine.aliaces', array());

        foreach (ENGINE::option('external.options', array()) as $k => $v)
            ENGINE::set_option($k, null, $v);

        foreach (ENGINE::option('engine.event_handler', array()) as $k => $v) {
            if (is_array($v) && count($v) > 0) {
                foreach ($v as $vv) {
                    ENGINE::register_event_handler($k, $vv);
                }
            }
        }
    }

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
     * get an object from aliac record
     * @static
     * @param $name
     */
    static function getObj($name){
        if (!isset(self::$class_list[$name])) {
            $class = $name;
            if (isset(self::$class_alias[$name]))
                $class = self::$class_alias[$name];
            self::$class_list[$name] = new $class();
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
    static function exec($func, $args = array(), $error_rule = '')
    {
        if (is_array($func)) {
            $cls = $func[0];
            if (is_string($func[0])) {
                $func[0]=self::getObj($func[0]);
            }
        }
        if (!is_callable($func)) {
            if (is_array($func))
                ENGINE::error('unresolved callable {{class}}->{{method}}', array('{{class}}' => $func[0], '{{method}}' => $func[1]), $error_rule);
            else
                ENGINE::error('unresolved callable {{function}}', array('{{class}}' => $func), $error_rule);
        }
        return call_user_func_array($func, &$args);
    }

    static function _(&$val,$def=''){
        if(empty($val))
            return $def;
        else
            return $val;
    }


    /*  --- point::ENGINE_body --- */

    /**
     * Just a little magic (2) - копия предыдущей магии
     * Простой механизм для монтажа расширений
     *
     * @param $method
     * @param $args
     * @return mixed
     */

    public function __call($method, $args)
    {
        if (array_key_exists($method, self::$extensions)) {
            return self::exec(self::$extensions[$method], $args);
        } else {
            return self::error('ENGINE: Interface `{{interface}}` not defined', array('{{interface}}' => $method));
        }
    }

    static function II()
    {
        return self::$I;
    }

    

    /**
     * Регистрация обработчика событий
     *
     * @static
     * @param string|array $event
     * @param null|callable $handler
     * @param string $phase - значения pre||post
     */
    static public function register_event_handler($event, $handler = null, $phase = '')
    {
        if (is_array($event)) {
            foreach ($event as $ev) {
                self::register_event_handler($ev, $handler, $phase);
            }
            return;
        }
        if (!empty($phase)) {
            if (!preg_match('/^post|pre$/', $phase))
                self::error(sprintf('ENGINE: Wrong $phase($s) value awhile registerring event handler `$s`'
                    , $phase, $handler));
            else {
                $event .= '/' . $phase;
            }
        }
        if (!isset(self::$events[$event]))
            self::$events[$event] = array();
        array_push(self::$events[$event], $handler);
    }

    /**
     * убрать обработчик событий
     *
     * @static
     * @param $event
     * @param null|callable $handler - либо функция, либо символическое имя. при отсутствии параметра чистится вся очередь события
     */
    static public function unregister_event_handler($event, $handler = null)
    {
        if (!isset(self::$events[$event]))
            return;
        if (is_null($handler))
            self::$events[$event] = array();
        else if (is_callable($handler)) {
            foreach (array($event . '/pre', $event, $event . '/post') as $ev)
                if (isset(self::$events[$ev])) {
                    $key = array_search($handler, self::$events[$ev]);
                    if ($key !== false)
                        unset(self::$events[$ev][$key]);
                }
        }
    }

    /**
     * вызвать все обработчики события
     *
     * @static
     * @param $event
     * @param null $args
     */
    static public function trigger_event($event, $args = null)
    {
        foreach (array($event . '/pre', $event, $event . '/post') as $ev)
            if (isset(self::$events[$ev]))
                foreach (self::$events[$ev] as &$handle) {
                    self::exec($handle, array($event, &$args));
                }
    }
    

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
     * @param string|object $transport
     * @return bool|mixed
     */
    static public function set_option($name = '', $value = null, $transport = '')
    {
        if (is_array($name))
            $transport = $value;

        if (!empty($transport) && is_string($transport)) {
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
                if (!is_array($value) || !array_key_exists($name, self::$options)) {
                    self::$options[$name] = $value;
                } else {
                    array_merge_deep(self::$options[$name], $value);
                }
                return true;
            } else
                return self::$options[$name];
        }

        if (!is_null($value)) {
            call_user_func(array($transport, 'set'), $name, &$value);
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
            if (is_callable(array('engine_options_' . $x[0], 'init')))
                self::$transports[$transport] =
                    call_user_func(array('engine_options_' . $x[0], 'init'), $x[1]);
            else
                self::$transports[$transport] = 'engine_options_' . $x[0];
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

    

    static public function _autoload($cls)
    {
        $classes = ENGINE::option('engine.class_vocabular');
        if (is_array($classes) && isset($classes[$cls])) {
            $x = $classes[$cls];
            if (substr($x[1], -1, 1) == '/') $x .= $cls.'.php' ;
        } else {
            $x=$cls.'.php';
        }  ;
        //$x=strtolower($x) ;
        if (is_readable($x))
            include_once($x);
        elseif (defined('SITE_PATH') && is_readable(SITE_PATH .'/'. $x))
            include_once(SITE_PATH .'/'. $x);
        elseif(defined('TEMPLATE_PATH') && is_readable(TEMPLATE_PATH .'/'. $x))
            include_once(TEMPLATE_PATH .'/'. $x);
        elseif(defined('TEMPLATE_PATH') && is_readable(TEMPLATE_PATH .'/../../templates'. $x))
            include_once(TEMPLATE_PATH .'/../../templates'. $x);
        else if (defined('SYSTEM_PATH') && is_readable(SYSTEM_PATH .'/'. $x))
            include_once(SYSTEM_PATH .'/'. $x);
        else if (defined('SYSTEM_PATH') && is_readable(SYSTEM_PATH .'/plugins/'. $x))
            include_once(SYSTEM_PATH .'/plugins/'. $x);
        else {
            $x = dirname(__FILE__) . '/plugins/' . $x . '.php';
            if (is_readable($x)) {
                include_once($x);
                return;
            }
        }
    }
    

    static function start_session(){
        if(!self::$session_started){
            $session_name=ENGINE::option('engine.sessionname');
            if(!empty($session_name)){
                session_name($session_name);
            }
            session_start();
            self::$session_started=true;
        }
    }

    static function startSessionIfExists(){
        $session_name=ENGINE::option('engine.sessionname',session_name());
        if( array_key_exists($session_name,$_GET)
            && array_key_exists($session_name,$_COOKIE)
        ){
            ENGINE::set_option('action', 'reload');
        }
        if( array_key_exists($session_name,$_GET)
            || array_key_exists($session_name,$_COOKIE)
        ){
            ENGINE::start_session();    //session_start();
        }
    }

    static function close_session(){
        if(self::$session_started){
            session_write_close();
            self::$session_started=false;
        }
    }

}

/*  --- point::ENGINE_bottom --- */

ENGINE::$I = new ENGINE();


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
                if(count($tomerge[$k])==0){
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



spl_autoload_register('ENGINE::_autoload');



/**
 * класс для хранения параметров в сессии
 */
class engine_options_session
{

    function get($name)
    {
        if(ENGINE::$session_started)
            return $_SESSION[$name];
        else
            return null;
    }

    function set($name, $value = null)
    {
        ENGINE::start_session();
        if (empty($value))
            unset($_SESSION[$name]);
        else
            $_SESSION[$name] = $value;
    }

}
