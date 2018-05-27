<?php
/**
 * статический класс - преставитель CMS в космосе.
 * ----------------------------------------------------------------------------------
 * $Id: X-Site cms (2.0), written by Ksnk (sergekoriakin@gmail.com),
 * ver: , Last build: 1505251519
 * GIT: $
 * ----------------------------------------------------------------------------------
 * License MIT - Serge Koriakin - Jule 2012
 * ----------------------------------------------------------------------------------
 */

/*  --- point::ENGINE_top --- */

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

    /*  --- point::ENGINE_header --- */


    public static $I;

    

    private static $events = array();

    

    static private $options = array(); // пары: имя-значение
    static private $transport = array(); // пары: имя - механизм сохранения
    static public $default_transport = '';
    static private $transports = array(); // строка -> объект

    

    static public $session_started = false;

    


    static private $interface = array();

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
        if (!error_reporting()) return;
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
                if (!error_reporting()) return;
                else if ($name == $class) {
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
        if(!is_string($name)){
            self::debug('wtf?','~count|15');
        }
        $x = $cache[$name]->$method($par);
        return $x;
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
        if (array_key_exists($method, self::$interface)) {
            return self::exec(self::$interface[$method], $args);
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
            } else if (array_key_exists($name,self::$options))
                return self::$options[$name];
            else
                return null;
        }
        if(is_string($transport))
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
            $y=explode('~',$x[0].'~');
            if (is_callable(array('engine_options_' . $y[0], 'init')))
                self::$transports[$transport] =
                    call_user_func(array('engine_options_' . $y[0], 'init'), $y[1],$x[1]);
            else
                self::$transports[$transport] = 'engine_options_' . $y[0];
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
        //static $include_path;
        $classes = ENGINE::option('engine.class_vocabular');
        if (is_array($classes) && isset($classes[$cls])) {
            $x = $classes[$cls];
            if (substr($x, -1, 1) == '/') {
                $x.=$cls;
            }
        } else {
            $x=$cls;
        }  ;
        $x.= '.php' ;
        if(!isset($include_path)) {
            $include_path=ENGINE::option('engine.include_path',array('.'));
        }
        foreach($include_path as $path) {
            if (is_readable($path .'/'.self::$SUBDIR. $x)){
                include_once($path .'/'.self::$SUBDIR. $x);
                return;
            }
        }
        if (function_exists("__autoload"))
        {
            __autoload($cls) ;
        }

    }
    

    static function start_session($log=true)
    {
        if (!self::$session_started) {
            $session_name = ENGINE::option('engine.sessionname');
            if (!empty($session_name)) {
                session_name($session_name);
            }
            session_set_cookie_params ( ENGINE::option('engine.session_lifetime',600),ENGINE::option('engine.session_path','/'),ENGINE::option('engine.session_domain',null));
            session_start();
            setcookie(session_name(),session_id(),time()+ENGINE::option('engine.session_lifetime',600),ENGINE::option('engine.session_path','/'),ENGINE::option('engine.session_domain',null));
            if($log){
            $log = array();
            foreach (array('REMOTE_ADDR', 'X-Forwarded-For', 'X-Real-IP') as $name) {
                if (isset($_SERVER[$name])) {
                    $log[$_SERVER[$name]] = $name;
                }
            }
            ENGINE::log(
                'Старт сессии.
IP:{{IP}}
REF:"{{HTTP_REFERER}}"
UA:"{{HTTP_USER_AGENT}}"', array('type' => 'session',
                    '{{IP}}' => implode(',', array_keys($log)),
                    '{{HTTP_REFERER}}' => ENGINE::_($_SERVER['HTTP_REFERER'], '-'),
                    '{{HTTP_USER_AGENT}}' => ENGINE::_($_SERVER['HTTP_USER_AGENT'], '-')
                )
            );
            }
            self::$session_started = true;
        }
    }

    static function startSessionIfExists()
    {
        if (self::$session_started) {
            return;
        }

        $session_name = ENGINE::option('engine.sessionname', session_name());
        if (array_key_exists($session_name, $_GET)
            && array_key_exists($session_name, $_COOKIE)
        ) {
            ENGINE::set_option('action', 'reload');
        }
        if (array_key_exists($session_name, $_GET)
            || array_key_exists($session_name, $_COOKIE)
        ) {
            ENGINE::start_session(false); //session_start();
        }
    }

    static function close_session()
    {
        if (self::$session_started) {
            session_write_close();
            self::$session_started = false;
        }
    }
    

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
     * функция получения информации о стеке вызовов.
     * @param array $opt - массив с ключами для вывода
     * @param int $count - количество позиций
     * @return string
     */
    static function backtrace($opt=array(),$count=1){
        $x=debug_backtrace();
        if(empty($opt)) $opt=array('function'=>'debug');
        if(isset($opt['count'])) $count=$opt['count'];
        $result=array();
        while(count($x)>0){
            foreach(array('function','class') as $xx) {
                if(isset($opt[$xx]) && isset($x[0][$xx]) && (0===strpos($x[0][$xx],$opt[$xx]))){
                    //array_shift($x);
                    break 2;
                }
                elseif(isset($opt['!'.$xx]) && isset($x[0][$xx]) && (false===strpos($x[0][$xx],$opt['!'.$xx])))
                    break 2;
            }
            array_shift($x);
        }
        if(empty($x)){
            $x=debug_backtrace();$count=max(3,$count);array_shift($x);
        } else if(!empty($opt['shift'])){
            array_shift($x);
        }
        while($count-- && (count($x)>0)) {
            $xx=array();
            $y=array_shift($x);
            foreach(array('function'=>'func','class'=>'cls','file'=>'file','line'=>'line') as $k=>$v) {
                if(isset($y[$k]))
                    if($k=='file')
                        $xx[]=$v.':'.str_replace($_SERVER['DOCUMENT_ROOT'],'',$y[$k]);
                    else
                        $xx[]=$v.':'.$y[$k];
            }
            if(!empty($xx))
                $result[]= implode(',',$xx);
        }
        return implode("\n|",$result);
    }

    static function debug()
    {
        $backtrace_options=array('function'=>'debug');$backtrace_count=1;
        $na=func_num_args();
        $out='';
        for ($i=0; $i<$na;$i++){
            $msg=func_get_arg($i);

            if(is_string($msg) && strlen($msg)>1 && $msg{0}=='~'){
                $x=explode('|',substr($msg,1).'||');
                $backtrace_options[$x[0]]=$x[1];
            } else {
                $out.=self::varlog($msg)."\r\n";
            }
        }
        if(empty($backtrace_options))$backtrace_count=4;
        echo '<!--xxx-debug-'.self::backtrace($backtrace_options,$backtrace_count).' '.str_replace('-->','--&gt;',trim(substr($out,0,16000))).'-->';
    }

    /**
     * лог объекта в печатную форму
     *
     * @param $varInput
     * @param string $var_name
     * @param string $reference
     * @param string $method
     * @param bool $sub
     *
     * @return string
     *
     * http://us2.php.net/manual/en/function.var-dump.php#76072
     */
    static function varlog(
        &$varInput, $var_name='', $reference='', $method = '=', $sub = false
    ) {

        static $output ;
        static $depth ;

        if ( $sub == false ) {
            $output = '' ;
            $depth = 0 ;
            $reference = $var_name ;
            $var = serialize( $varInput ) ;
            $var = unserialize( $var ) ;
        } else {
            ++$depth ;
            $var =& $varInput ;
        }

        // constants
        $nl = "\n" ;
        $block = 'a_big_recursion_protection_block';

        $c = $depth ;
        $indent = '' ;
        while( $c -- > 0 ) {
            $indent .= '|  ' ;
        }
        if($depth>5) return '...';
        // if this has been parsed before
        if ( is_array($var) && isset($var[$block])) {

            $real =& $var[ $block ] ;
            $name =& $var[ 'name' ] ;
            $type = gettype( $real ) ;
            $output .= $indent.$var_name.' '.$method.'& '.($type=='array'?'Array':get_class($real)).' '.$name.$nl;

            // havent parsed this before
        } else {

            // insert recursion blocker
            $var = Array( $block => $var, 'name' => $reference );
            $theVar =& $var[ $block ] ;

            // print it out
            $type = gettype( $theVar ) ;
            switch( $type ) {

                case 'array' :
                    $output .= $indent . $var_name . ' '.$method.' Array ('.$nl;
                    $keys=array_keys($theVar);
                    foreach($keys as $name) {
                        $value=&$theVar[$name];
                        self::varlog($value, $name, $reference.'["'.$name.'"]', '=', true);
                    }
                    $output .= $indent.')'.$nl;
                    break ;

                case 'object' :
                    $output .= $indent;
                    if(!empty($var_name)){
                        $output .= $var_name.' = ';
                    }
                    $output .= '{'.var_export ($theVar,true).$indent.'}'.$nl;
                    break;
              /*      if( !class_exists('ReflectionClass')){
                        $output .= get_class($theVar).' {'.$nl;
                        foreach((array)$theVar as $name=>$value) {
                            self::varlog($value, $name, $reference.'->'.$name, '->', true);
                        }
                    } else {
                        $reflect = new ReflectionClass($theVar);
                        $output .= $reflect->getName().' {'.$nl;
                        $props   = $reflect->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);

                        foreach ($props as $prop) {
                           // if()
                            self::varlog($theVar->{$prop}, $prop, $reference.'->'.$prop, '->', true);
                            print $prop->getName() . "\n";
                        }
                    }
                    $output .= $indent.'}'.$nl;
                    break ;*/

                case 'string' :
                    $output .= $indent . $var_name . ' '.$method.' "'.$theVar.'"'.$nl;
                    break ;

                default :
                    $output .= $indent . $var_name . ' '.$method.' ('.$type.') '.$theVar.$nl;
                    break ;

            }

            // $var=$var[$block];

        }

        -- $depth ;

        if( $sub == false )
            return $output ;

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
        if (ENGINE::$session_started)
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
