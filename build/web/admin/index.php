<?php
/**
 * Индексный файл админки сайта. Находимся в каталоге admin
 * ------------------------------------------------------------------------------------------
 * $Id: X-Site cms (2.0), written by Ksnk (sergekoriakin@gmail.com)
 *  Rev: 1410, Modified: 
 *  SVN: file:///C:/notebook_svn/svn/xilen/cms$
 * ------------------------------------------------------------------------------------------
 * License MIT - Serge Koriakin - Jule 2012
 * ------------------------------------------------------------------------------------------
 */
error_reporting(E_ALL ^ E_NOTICE);
define('INDEX_DIR', dirname(__FILE__));

define('SITE_PATH', realpath(INDEX_DIR . '/../../site/'));
define('SYSTEM_PATH', realpath(INDEX_DIR . '/../../system/'));

define('TEMPLATE_PATH', realpath(SITE_PATH . '/admin/templates/'));

include (SYSTEM_PATH.'/_engine.php');

ENGINE::set_option(include SITE_PATH.'/config/host.config.php');
ENGINE::set_option(include SITE_PATH.'/config/xsite.admin.config.php');
ENGINE::init(array(
    'page.rootsite'=>'/projects/cms/build/web/admin/' , // добавка для шаблонов админки. В нормальном сайте ее нет
    'page.root'=>'/projects/cms/build/web/' , // добавка для шаблонов админки. В нормальном сайте ее нет
    'upload_dir'=>str_replace('\\','/',realpath(INDEX_DIR . '/../upload/')),
));

ENGINE::run();