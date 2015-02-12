<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Сергей
 * Date: 09.10.12
 * Time: 14:29
 * To change this template use File | Settings | File Templates.
 */

/**
 * определение путей системы
 */
define('INDEX_DIR',dirname(__FILE__));
define('SYSTEM_PATH',dirname(dirname(__FILE__)).'/build/system');
define('SITE_PATH',dirname(dirname(__FILE__)).'/build/site');
define('TEMPLATE_PATH',realpath(SITE_PATH.'/template/'));
define('ROOT_URI','/projects/cms/build/web/index.php');

include_once (SYSTEM_PATH. "/engine.php");
include_once (SYSTEM_PATH. "/plugins/xDatabaseXilen.php");
ENGINE::set_option(
    array (
        'database.host' => 'localhost',
        'database.user' => 'root',
        'database.password' => '',
        'database.prefix' => 'xsite',
        'database.base' => 'cms',
    )
);
new xDatabase();

