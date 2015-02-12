<?php
/**
 * Индексный файл  сайта. Находимся в руте сайта
 * <%=point('hat','jscomment');





%>
 */
error_reporting(E_ALL ^ E_NOTICE);
define('INDEX_DIR', dirname(__FILE__));
/* <% if ('directory'==$target) { %> */
define('SITE_PATH', realpath(INDEX_DIR . '/site/'));
define('SYSTEM_PATH', realpath(INDEX_DIR . '/system/'));
/* <% } else { %> */
define('SITE_PATH', realpath(INDEX_DIR . '/../site/'));
define('SYSTEM_PATH', realpath(INDEX_DIR . '/../system/'));
/* <% } %> */
define('TEMPLATE_PATH', realpath(SITE_PATH . '/templates/'));

include (SYSTEM_PATH.'/_engine.php');
if(is_readable($cfg= SITE_PATH.'/config/host.config.php')) ENGINE::set_option(include $cfg);
if(is_readable($cfg= SITE_PATH.'/config/xsite.install.config.php')) ENGINE::set_option(include $cfg);
ENGINE::init(array(
    'page.rootsite'=>'<%=$root_url%>/' , // добавка для шаблонов админки. В нормальном сайте ее нет
));

ENGINE::run();


