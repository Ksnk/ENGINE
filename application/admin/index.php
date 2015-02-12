<?php
/**
 * Индексный файл админки сайта. Находимся в каталоге admin
 * <%=point('hat','jscomment');




%>
 */
error_reporting(E_ALL ^ E_NOTICE);
define('INDEX_DIR', dirname(__FILE__));
/* <% if ('directory'==$target) { %> */
define('SITE_PATH', realpath(INDEX_DIR . '/../site/'));
define('SYSTEM_PATH', realpath(INDEX_DIR . '/../system/'));
/* <% } else { %> */
define('SITE_PATH', realpath(INDEX_DIR . '/../../site/'));
define('SYSTEM_PATH', realpath(INDEX_DIR . '/../../system/'));
/* <% } %> */
define('TEMPLATE_PATH', realpath(SITE_PATH . '/admin/templates/'));

include (SYSTEM_PATH.'/_engine.php');

ENGINE::set_option(include SITE_PATH.'/config/host.config.php');
ENGINE::set_option(include SITE_PATH.'/config/xsite.admin.config.php');
ENGINE::init(array(
    'page.rootsite'=>'<%=$root_url%>/admin/' , // добавка для шаблонов админки. В нормальном сайте ее нет
    'page.root'=>'<%=$root_url%>/' , // добавка для шаблонов админки. В нормальном сайте ее нет
    'upload_dir'=>str_replace('\\','/',realpath(INDEX_DIR . '/../upload/')),
));

ENGINE::run();
