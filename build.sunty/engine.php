<?php
/**
 * статический класс - преставитель CMS в космосе.
 * ----------------------------------------------------------------------------------
 * $Id: X-Site cms (2.0, Sunty build), written by Ksnk (sergekoriakin@gmail.com)
 *  Rev: 1410, Modified: 
 *  SVN: file:///C:/notebook_svn/svn/xilen/cms$
 * ----------------------------------------------------------------------------------
 * License MIT - Serge Koriakin - Jule 2012
 * ----------------------------------------------------------------------------------
 */

/*  --- point::ENGINE_top --- */

/**
 * Class xData -data-holder,
 * базовый класс для хранителя данных для шаблонов
 */
class xData implements  Iterator {

    /**
     * @var array
     */
    static $items=array();

    /**
     * система кэширования однотипных данных. Все данные различаются полем ID.
     * @static
     * @param $class
     * @param $id
     * @param array $data
     * @return mixed
     */
    static function get($class,$id,$data=array()){
        if(!isset(self::$items[$class]))
            self::$items[$class]=array();
        if(is_array($id)) {
            if(!isset(self::$items[$class][$id['id']]))
                self::$items[$id['id']]=new $class($id,$data);
            return self::$items[$class][$id['id']];
        } else if(!isset(self::$items[$class][$id])) {
            self::$items[$class][$id]=new $class($id,$data);
        }
        return self::$items[$class][$id];
    }

    protected $data=array();
    private $def='',$eoa=false;

    function getData(){
        return $this->data;
    }

    function __construct($data,$def=''){
        foreach($data as $k=>$v){
            if(is_array($v))
                $this->data[$k]=new self($v,$def);
            else
                $this->data[$k]=$v;
        }
        $this->def=$def;
    }

    protected function  &resolve($name){
        if(!array_key_exists($name,$this->data))
            $this->data[$name]=$this->def;
        return $this->data[$name];
    }

    function &__get($name){
        if(array_key_exists($name,$this->data))
            return $this->data[$name];
        else {
            $x=$this->resolve($name);
            return $x;
        }
    }
    public function __set($name, $value)
    {
        // echo "Setting '$name' to '$value'\n";
        $this->data[$name] = $value;
    }
    // итератор
    function rewind() {
        $this->eoa=count($this->data==0);
        reset($this->data);
    }

    function current() {
        return current($this->data);
    }

    function key() {
        return key($this->data);
    }

    function next() {
        return $this->eoa=(false!==next($this->data));
    }

    function valid() {
        return $this->eoa;
    }

}

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

    static private $options = array(); // пары: имя-значение
    static private $transport = array(); // пары: имя - механизм сохранения
    static public $default_transport = '';
    static private $transports = array(); // строка -> объект

    

    /** @var xDatabaseLapsi */
    static private $db = null;

    

    static $start_time ;

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

    /*  --- point::ENGINE_body --- */

    /**
     *    xcached
     * @static
     * @param $key
     * @param null $value
     * @param int $time   28800 - 8 
     * @return bool|null
     */
    static function cache($key, $value = null, $time = 28800)
    {
        return false;
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
        static $include_path;
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
    

    /**
     *      .
     * @param array $opt -     
     * @param int $count -  
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
            $msg=func_get_arg($i);ob_start();

            if(is_array($msg)) {
                var_dump($msg);
                $out.=ob_get_contents()."\r\n";
            } else if(is_string($msg) && strlen($msg)>1 && $msg{0}=='~'){
                $x=explode('|',substr($msg,1).'||');
                $backtrace_options[$x[0]]=$x[1];
            } else {
                var_dump($msg);
                $out.=ob_get_contents()."\r\n";

            }
            ob_end_clean();
        }
        if(empty($backtrace_options))$backtrace_count=4;
        echo '<!--xxx-debug-'.self::backtrace($backtrace_options,$backtrace_count).' '.str_replace('-->','--&gt;',trim(substr($out,0,16000))).'-->';
    }

    

    /**
     *      
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
     *  ENGINE::DB
     *
     * @param string $option  
     *
     * @return xDatabaseLapsi
     */
    static public function &db($option = '')
    {
        if (empty(self::$db)) {
            self::$db = self::getObj('Database', $option);
        }
        self::$db->set_option($option);
        return self::$db;
    }

    

    static public function _report()
    {
        echo '<!--';
        /*  --- point::ENGINE_final_report --- */

        if (!empty(self::$db)) {
            echo self::$db->report();
        }

        printf("%f sec spent (%s)"
            ,microtime(true)-self::$start_time,date("Y-m-d H:i:s"));
        echo '-->';
    }
    static public function _shutdown()
    {
        /*  --- point::ENGINE_shutdown --- */

        if(ENGINE::option('noreport'))return;
        ENGINE::_report();
    }

}

/*  --- point::ENGINE_bottom --- */

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



register_shutdown_function('ENGINE::_shutdown');
ENGINE::$start_time=microtime(true);
