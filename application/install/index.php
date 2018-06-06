<?php
/**
 * Файл инсталляции системы. Находимся в каталоге web/install
 * <%=point('hat','jscomment');





%>
 */
error_reporting(-1); //E_ALL ^ E_NOTICE);
ini_set('display_errors', '1');
define('INDEX_DIR', dirname(__FILE__));
/* <% if ('directory'==$target) { %> */
define('SITE_PATH', realpath(INDEX_DIR . '/../site/'));
define('SYSTEM_PATH', realpath(INDEX_DIR . '/../system/'));
/* <% } else { %> */
define('SITE_PATH', realpath(INDEX_DIR . '/../../site/'));
define('SYSTEM_PATH', realpath(INDEX_DIR . '/../../system/'));
/* <% } %> */
define('TEMPLATE_PATH', realpath(SITE_PATH . '/admin/templates/'));

include (SYSTEM_PATH . '/_engine.php');

class xInstall extends xWebApplication
{

    var $installOption = array();

    function router()
    {
        if (isset($_GET['do']))
            ENGINE::set_option(array('class' => 'Main', 'method' => 'do_' . $_GET['do']));
        else
            ENGINE::exec(array('engine_router', 'route'), array(array(
                array('#^/?(\w+)/(\w+)($|\?.*)#', array(1 => 'class', 2 => 'method', 3 => 'query'))
            , array('#^/?(\d+)($|\?.*)#', array('class' => 'Page', 'method' => 'show', 1 => 'id', 2 => 'query'))
            , array('#^/?(\w+)($|\?.*)#', array('class' => 'Main', 1 => 'method', 2 => 'query'))
            )));
    }

    /**
     * по имени класса вернуть его инсталляционную запись
     * @param $name
     * @return array|bool
     */
    function getClassInstallInfo($name)
    {
        if (class_exists($name)) {
            $class = new ReflectionClass($name);
            if ($class->isSubclassOf('engine_plugin')) {
                $info = array('aliace' => $name);
                if ($class->hasMethod('get_install_info')) {
                    $info = array_merge($info, call_user_func(array($name, 'get_install_info')));
                }
                return $info;
            }
        }
        return false;
    }

    function build_plugin_list()
    {
        $dir = array_merge(
            glob(SYSTEM_PATH . '/plugins/*.php'),
            glob(SITE_PATH . '/*.php')
        );
        $result = array();
        foreach ($dir as $f) {
            $content = file_get_contents($f);
            if (preg_match_all('/class\s+(\w+)\s*(?:extends[^{]*)?{/', $content, $m)) {
                $name = $m[1][0];
                $info = $this->getClassInstallInfo($m[1][0]);
                if ($info !== false) {
                    if (!empty($result[$info['aliace']])) {
                        if (is_string($result[$info['aliace']])) {
                            $result[$info['aliace']] =
                                array(
                                    'type' => 'multy',
                                    'element' => array($result[$info['aliace']])
                                );
                        }
                        $result[$info['aliace']]['element'][] = $name;
                    } else
                        $result[$info['aliace']] = $name;
                }
            }
        }
        //print_r($result) ;
        return $result;
    }


    function do_step1()
    {
        ENGINE::set_option(array(
            'ajax.goto' => 'step2'
        ));
        return 'Ok!';
    }

    function do_step2()
    {
        ENGINE::set_option(array(
            'database.host' => $_POST['host'],
            'database.user' => $_POST['user'],
            'database.password' => $_POST['password'],
            'database.prefix' => $_POST['prefix'],
            'database.base' => $_POST['base'],
        ));
        //ENGINE::debug(ENGINE::option());
        $result = ENGINE::db()->select('SHOW TABLES');
        //($result);
        $error = ENGINE::option('page.error');
        if (empty($error)) {
            ENGINE::set_option(array(
                'ajax.goto' => 'step3'
            ));
        }

        return 'Ok!';
    }

    function get_installed_plugin($prefix = 'install.')
    {
        return ENGINE::option($prefix . 'installed_plugin', array());
    }

    function installModule($cl, $name, $prefix = "install.")
    {
        $val = array();
        $val[$cl] = $name;
        $info = $this->getClassInstallInfo($name);
        /**
         * setting a parameters
         */
        if (false === $info) return;

        if ($info['aliace'] != $name) {
            $info['engine.aliaces'] = array();
            $info['engine.aliaces'][$info['aliace']] = $name;
        }
        unset ($info['aliace']);
        array_merge_deep($this->installOption, $info);

        ENGINE::set_option($prefix . 'installed_plugin', $val);
        // install module
        ENGINE::exec(array($name, 'install'), array(true));
    }

    function uninstallModule($cl, $name, $prefix = "install.")
    {
        $val = array();
        $val[$cl] = null;
        $info = $this->getClassInstallInfo($name);
        /**
         * setting a parameters
         */
        if (false === $info) return;

        if ($info['aliace'] != $name) {
            $info['engine.aliaces'] = array();
            $info['engine.aliaces'][$info['aliace']] = $name;
        }
        unset ($info['aliace']);
        array_clear_deep($this->installOption, $info);

        ENGINE::set_option($prefix . 'installed_plugin', $val);
        ENGINE::exec(array($name, 'install'), array(false));
    }

    function do_step3($prefix = '', $nextstep = '', $tpl = '')
    {
        if (empty($prefix))
            $prefix = 'admin.';
        $config_name = '/config/xsite.' . $prefix . 'config.php';
        if (empty($nextstep))
            $nextstep = 'step4';
        if (empty($tpl))
            $tpl = '_step3';
        $installed = $this->get_installed_plugin($prefix);
        if ('POST' == $_SERVER['REQUEST_METHOD']) {

            // парамерты требующиеся для ENGINE
            $this->installOption = array(

                /**
                 * переопределение ключевых объектов системы.
                 */
                'engine.aliaces' => array(
                    'Database' => 'xDatabaseXilen',
                    'Rights' => 'xRights',
                ),

                /**
                 * список потенциальных внешних интерфейсов
                 */
                'engine.interfaces' => array(
                    'link' => array('engine_router', 'link'),
                    'log' => array('ENGINE_logger', 'log'),
                    'db' => array('Database', 'getInstance'),
                    'action' => array('ENGINE_action', 'action'),
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
                    'INITIALIZE' => array(
                        'init_tpl' => array('Main', 'init_tpl'),
                    ),
                )
            );


            foreach ($installed as $name) {
                $info = $this->getClassInstallInfo($name);
                /**
                 * setting a parameters
                 */
                if (false === $info) continue;

                if ($info['aliace'] != $name) {
                    $info['engine.aliaces'] = array();
                    $info['engine.aliaces'][$info['aliace']] = $name;
                }
                unset ($info['aliace']);
                array_merge_deep($this->installOption, $info);
            }

            // do post
            // print_r($_POST);
            if (isset($_POST['install']) && is_array($_POST['install']) && !empty($_POST['install'])) {
                // список модулей для
                foreach ($_POST['install'] as $k => $v) {
                    $aliace = $v;
                    if (array_key_exists($k, $_POST['aliace'])) {
                        $aliace = $_POST['aliace'][$k];
                    }

                    $rname = isset($installed[$v]) ? $installed[$v] : $v;

                    if ($rname != $aliace) {
                        $this->uninstallModule($v, $rname, $prefix);
                        $this->installModule($v, $aliace, $prefix);
                    }
                    unset($installed[$v]);
                }
                // so uninstall unused plugins
                if (!empty($installed)) {
                    foreach ($installed as $k => $v) {
                        $this->uninstallModule($k, $v, $prefix);
                    }
                }
            }

            switch ($_POST['action']) {
                case 'show_diff':
                    // just show difference and stay here
                    $old_config = include (SITE_PATH . $config_name);
                    $tmp = $old_config;
                    array_clear_deep($tmp, $this->installOption);
                    $log = '<hr>deleted:<hr>' . var_export($tmp, true);
                    $tmp = $this->installOption;
                    array_clear_deep($tmp, $old_config);
                    $log .= '<hr>appended:<hr>' . var_export($tmp, true);
                    ENGINE::set_option('ajax.log', $log);
                    $log .= '<hr>full config:<hr>' . var_export($this->installOption, true);
                    ENGINE::set_option('ajax.log', $log);
                    break;
                case 'save':
                    // just show difference and stay here
                    file_put_contents(SITE_PATH . $config_name, "<?php\nreturn "
                        . var_export($this->installOption, true) . ";\n");
                    break;
                default:
                    ENGINE::set_option(array(
                        'ajax.goto' => $nextstep
                    ));
            }

        } else {
            // scan for data
            return ENGINE::template('tpl_install', $tpl, array(
                'text' => 'Укажите модули, включенные в систему.',
                'dir' => $this->build_plugin_list(),
                'installed' => $installed
            ));
        }

        $error = ENGINE::option('page.error');
        if (!empty($error)) {
            ENGINE::set_option(array(
                'ajax.goto' => null
            ));
        }

        return 'Ok';
    }

    function do_step4()
    {
        return $this->do_step3('install.', 'step5', '_step4');
    }

    function do_Default()
    {
        $aliace=ENGINE::option('engine.aliaces');
        ENGINE::set_option('page.tabs', array(
            'step1' => array(
                'title' => "Проверка хостинга",
                //'next'=>'step2', //
                'form' => array(
                    'Перед началом работы необходимо проверить сервер на соответствие требованиям СМС.'
                ),
                'button' => 'Начать'
            ),
            'step2' => array(
                'title' => "Настройка базы данных",
                'form' => array(
                    'Установите параметры для доступа к базе данных',
                    'host' => array('type' => 'text', 'label' => 'Хост ',
                        'value' => ENGINE::option('database.host')),
                    'port' => array('type' => 'text', 'label' => 'Порт ',
                        'value' => ENGINE::option('database.port')),
                    'user' => array('type' => 'text', 'label' => 'Пользователь БД',
                        'value' => ENGINE::option('database.user')),
                    'password' => array('type' => 'password', 'label' => 'Пароль',
                        'value' => ENGINE::option('database.password')),
                    'base' => array('type' => 'text', 'label' => 'Имя базы ',
                        'value' => ENGINE::option('database.base')),
                    'prefix' => array('type' => 'text', 'label' => 'Префикс',
                        'value' => ENGINE::option('database.prefix')),
                )
            ),
            'step3' => array(
                'title' => "Конф. админки",
                'remote' => 'step3'
            ),
            'step4' => array(
                'title' => "Конф. сайта",
                'remote' => 'step4'
            ),
            'step5' => array(
                'title' => "Конф. сайта",
                'remote' => 'step3'
            ),
            'step6' => array(
                'title' => "Конф. сайта",
                'remote' => 'step3'
            ),
            'step7' => array(
                'title' => "Конф. сайта",
                'remote' => 'step3'
            ),
            'step8' => array(
                'title' => "Конф. сайта",
                'remote' => 'step3'
            ),
            'step9' => array(
                'title' => "Конф. сайта",
                'remote' => 'step3'
            ),
            'step10' => array(
                'title' => "Конф. сайта",
                'remote' => 'step3'
            ),
        ));

        return '';
    }
}

// чукча не читатель, чукча писатель
ENGINE::set_option(array(
        'database.host',
        'database.user',
        'database.password',
        'database.port',
        'database.base',
        'database.prefix'),
    'varexport|' . SITE_PATH . '/config/host.config.php'
);

ENGINE::set_option(array(
    'engine.aliaces' =>
    array(
        'Main' => 'xInstall',
        'Database' => 'xDatabaseXilen',
        'User' => 'xUser',
        'Sitemap' => 'xSitemap',
        'Rights' => 'xRights',
    ),
    'engine.interfaces' =>
    array(
        'log' => 'ENGINE_logger::log',
        'template' => array('Main', 'template'),
        'db' => array('Database', 'getInstance'),
        'run' => array('Main', 'run'),
        'action' => array('ENGINE_action', 'action'),
    ),
    'engine.include_files' => array(SYSTEM_PATH . '/func.php'),
    'engine.class_vocabular' => array('template_compiler'
    => SYSTEM_PATH . '/plugins/template_compiler.php'),
    'engine.event_handler' =>
    array(
        'INITIALIZE' => array(
            array('Main', 'init_tpl'),
        ),
    )
));

ENGINE::set_option('install.installed_plugin', null, 'varexport|' . SITE_PATH . '/config/xsite.install.php');
ENGINE::set_option('admin.installed_plugin', null, 'varexport|' . SITE_PATH . '/config/xsite.install.php');

ENGINE::read_options('varexport|' . SITE_PATH . '/config/xsite.install.php');

ENGINE::init(array(
    'page_tpl' => 'tpl_install', // базовый шаблон для инсталлятора
    'page.title' => 'Установка CMS xSite',
    'page.rootsite' => '<%=$root_url%>/install/',
));

//ENGINE::set_option('engine.aliaces', array('Config' => 'xConfig'));

ENGINE::run();
//var_dump(ENGINE::option('engine.aliaces')) ;
