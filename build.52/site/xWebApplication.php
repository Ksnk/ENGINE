<?php
/**
 * тестовое Web приложение
 */

class xWebApplication  extends engine_plugin
{

    static function get_install_info()
    {
        return array(
            'aliace' => 'Main',
            'external.options' => array(
                'session.page.error' => 'session',
            ),

            /**
             * список потенциальных внешних интерфейсов
             */
            'engine.interfaces' => array(
                'template' => array('Main', 'template'),
                'run' => array('Main', 'run'),
            ),

            /**
             *  список файлов для непосредственного инклюда
             */
            'engine.include_files' => array(
                SYSTEM_PATH . '/func.php',
            ),

            /**
             *  словарь для системы автолода
             */
            'engine.class_vocabular' => array(
                'template_compiler' => SYSTEM_PATH . '/plugins/template_compiler.php',
            ),

            /**
             *  список обработчиков событий
             */
            'engine.event_handler' => array(
                'INITIALIZE/pre' => array(
                    'ENGINE::startSessionIfExists',
                ),
                'INITIALIZE' => array(
                    array('Main', 'init_tpl'),
                ),
            )

        );
    }

    function __call($method,$param){
        ENGINE::exec(array($method,'handle'),$param);
    }

    /**
     * метод, устанавливается на INITIALIZE в опциях системы.
     */
    function init_tpl()
    {
        template_compiler::options('TEMPLATE_EXTENSION', 'twig');
        template_compiler::checktpl();
    }

    function template($name, $method, $par = array())
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
                include_once TEMPLATE_PATH . DIRECTORY_SEPARATOR . $name . ".php";
            }
            if (!class_exists($name)) {
                ENGINE::debug(sprintf('method %s::%s not found', $name, $method));
                return '';
            }
            $cache[$name] = new $name();
        }
        //debug($name,$par);
        $x = $cache[$name]->$method($par);
        return $x;
    }

    function do_Default()
    {
        return ENGINE::exec("Нету тут ничего");
    }

    function navbar()
    {
        static $x;
        if (isset($x)) return $x;
        // формируем меню второго уровня
        return $x=ENGINE::template('tpl_elements','navbar',
           ENGINE::exec('Sitemap','navbar')
        );
    }

    function option($name,$default=''){
        return ENGINE::option($name,$default);
    }

    function router(){
        ENGINE::exec(array('engine_router', 'route'), array(array(
            array('#^/?(\w+)/(\w+)($|\?.*)#', array(1 => 'class', 2 => 'method', 3 => 'query'))
        , array('#^/?(\d+)($|\?.*)#', array('class'=>'Page', 'method'=>'show', 1 =>'id',2 => 'query'))
        , array('#^/?(\w+)($|\?.*)#', array('class'=>'Main', 1 => 'method', 2 => 'query'))
        , array('#($|\?.*)#', array('class'=>'Page', 'method'=>'show', 'id'=>2))
        )));
    }

    function run()
    {

//////////////////////////////////////////////////////////////////////////////////
// so here is a router
        // todo: wtf? Злой хак. Утопить в системе!
        if(ENGINE::_($_GET['debug'])) {
            if($_GET['debug'] && !isset($_COOKIE['debug'])){
                setcookie('debug',$_GET['debug']);
            } else {
                setcookie ("debug", "", time() - 3600);
            }
            header('location:'.ENGINE::link());
        }
        if(ENGINE::_($_COOKIE['debug'])){
            ENGINE::set_option('page.debug',true);
        }
        $this->router();

//////////////////////////////////////////////////////////////////////////////////
// so let's go
        ENGINE::trigger_event('INITIALIZE'); // trigger an INITIALIZE event

//////////////////////////////////////////////////////////////////////////////////
// now filling a datablock for one of the page-templates
        ENGINE::action();

//////////////////////////////////////////////////////////////////////////////////
//
        ENGINE::trigger_event('DONE'); // trigger an INITIALIZE event
    }
}
