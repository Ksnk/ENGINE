<?php
/**
 * основной файл конфигурации системы.
 * При вызове админки производится доопределение файла конфигурации дополнительными опциями
 */
return array(
    /** значение в шаблон - root для сайта */
    'page.root' => '<%=$root_url%>',
    'page.rootsite' => '<%=$root_url%>/',
    /**
     * database options
     */
    'database.host' => 'localhost',
    'database.user' => 'root',
    'database.base' => 'cms',
    'database.prefix' => 'xsite',

    /**
     * переопределение ключевых объектов системы.
     */
    'engine.aliaces' => array(
        'Main' => 'xAdmin',
        'User' => 'xUser',
        'Sitemap' => 'xSitemap',
        'Database' => 'xDatabaseXilen',
        'Install' => 'installManager',
        'Rights' => 'xRights',
    ),

    /**
     * external options
     */
    'external.options' => array(
        /**
         * параметры обслуживания логина
         */
        // кука - cookie|expire|path|domain|secure|httponly
        'login' => 'cookie|' . (60 * 60 * 24 * 30), // 30 дней для куки
        'USER' => 'session',
        'session.page.error' => 'session',
        'past_browser_agent'=>'session',
    ),

    /**
     * список потенциальных внешних интерфейсов
     */
    'engine.interfaces' => array(
        'user_find' => 'User::user_find',
        'link' => array('ENGINE_router', 'link'),
        'log' => 'ENGINE_logger::log',
        'template' => array('Main', 'template'),
        'db' => array('Database', 'getInstance'),
        'run' => array('Main', 'run'),
        'action' => array('ENGINE_action', 'action'),
        'has_rights' => array('Rights', 'has_rights'),
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
        // модули
        //       'Auth'=>ROOT_PATH.'/'.ADMIN.'/engine/users.php',

        // плагины
        //       'sitemap'=>ROOT_PATH.'/'.ADMIN.'/engine/plugins/',
    ),

    /**
     *  список обработчиков событий
     */
    'engine.event_handler' => array(
        'INITIALIZE/pre' => array(
            'ENGINE::startSessionIfExists',
        ),
        'INITIALIZE' => array(
            array('User', 'auth_check'),
            array('Main', 'init_tpl'),
        ),
    )

);