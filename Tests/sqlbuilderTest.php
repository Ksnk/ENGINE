<?php

if (!function_exists('phpunit_autoload')) {
    ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . dirname(dirname(__FILE__)));
    require 'PHPUnit/Autoload.php';
}

include ('header.inc.php');

$sqlbuildbody=preg_replace('/^\s*<\?php/','',str_replace('mysql_real_escape_string','mysql_escape_string',file_get_contents(SITE_PATH . "/SQL_BUILD.php")));
eval($sqlbuildbody);
//include_once (SITE_PATH . "/SQL_BUILD.php");

class sqlbuilderTest extends PHPUnit_Framework_TestCase
{

    function test_CreateSql()
    {
        $arr = array(
            'id' => array('type' => 'int', 'PRI' => true, 'AUTO' => true),
            'parent' => array('type' => 'int', 'IND' => true),
            'page' => array('type' => 'int', 'IND' => true),
            'name' => array('type' => 'str'),
            'url' => array('type' => 'str', 'IND' => true),
            'alt_url' => array('type' => 'str'),
            'order' => array('type' => 'str'),
            'title' => array('type' => 'str'),
            'descr' => array('type' => 'str'),
            'keywords' => array('type' => 'str'),
            'flags' => array('type' => 'int'),
        );
        $pattern = 'CREATE TABLE IF NOT EXISTS {{prefix}}_sitemap(
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`parent` int(11) NOT NULL,
	`page` int(11) NOT NULL,
	`name` varchar(200) NOT NULL,
	`url` varchar(200) NOT NULL,
	`alt_url` varchar(200) NOT NULL,
	`order` varchar(200) NOT NULL,
	`title` varchar(200) NOT NULL,
	`descr` varchar(200) NOT NULL,
	`keywords` varchar(200) NOT NULL,
	`flags` int(11) NOT NULL,
	PRIMARY KEY (`id`),
	KEY `parent` (`parent`),
	KEY `page` (`page`),
	KEY `url` (`url`)
);
';
        $this->assertEquals($pattern, SQL_BUILD::create(
            '{{prefix}}_sitemap', $arr
        ));
    }

    function test_InsertSql()
    {
        $arr = array(
            'id' => array('type' => 'int', 'PRI' => true, 'AUTO' => true),
            'parent' => array('type' => 'int', 'IND' => true),
            'page' => array('type' => 'int', 'IND' => true),
            'name' => array('type' => 'str'),
            'url' => array('type' => 'str', 'IND' => true),
            'alt_url' => array('type' => 'str'),
            'order' => array('type' => 'str'),
            'title' => array('type' => 'str'),
            'descr' => array('type' => 'str'),
            'keywords' => array('type' => 'str'),
            'flags' => array('type' => 'int'),
        );
        $pattern = 'INSERT INTO {{prefix}}_sitemap (`id`, `name`, `flags`, `parent`) VALUES
(1, "Главная", 1, 0),
(2, "Титульная страница", 0, 1);
';
        $this->assertEquals($pattern, SQL_BUILD::insert('{{prefix}}_sitemap', array(
            array('id' => 1, 'name' => 'Главная', 'flags' => 1),
            array('id' => 2, 'parent' => 1, 'name' => 'Титульная страница'),
        ), $arr));
    }

    function test_InsertManyTables()
    {
        $fields = array(
            '{{prefix}}_pages' => array(
                'id' => array('type' => 'int', 'IND' => true, 'AUTO' => true),
                'order' => array('type' => 'int'),
                'type' => array('type' => 'str10'),
                'typeid' => array('type' => 'int'),
                'name' => array('type' => 'str80')
            ),
            '{{prefix}}_pages_text' => array(
                'typeid' => array('type' => 'int', 'PRI' => true, 'AUTO' => true),
                'text' => array('type' => 'text')
            ),
            '{{prefix}}_pages_foto' => array(
                'typeid' => array('type' => 'int', 'PRI' => true, 'AUTO' => true),
                'text' => array('type' => 'text')
            ),
        );
        $pattern = array(
            '{{prefix}}_pages' => "CREATE TABLE IF NOT EXISTS {{prefix}}_pages(
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`order` int(11) NOT NULL,
	`type` varchar(10) NOT NULL,
	`typeid` int(11) NOT NULL,
	`name` varchar(80) NOT NULL,
	KEY `id` (`id`)
);
",
            '{{prefix}}_pages_text' => "CREATE TABLE IF NOT EXISTS {{prefix}}_pages_text(
	`typeid` int(11) NOT NULL AUTO_INCREMENT,
	`text` text,
	PRIMARY KEY (`typeid`)
);
",
            '{{prefix}}_pages_foto' => "CREATE TABLE IF NOT EXISTS {{prefix}}_pages_foto(
	`typeid` int(11) NOT NULL AUTO_INCREMENT,
	`text` text,
	PRIMARY KEY (`typeid`)
);
");
        foreach ($fields as $table => $field) {
            $this->assertEquals($pattern[$table], SQL_BUILD::create($table, $field));
        }
    }
}

if (!function_exists('phpunit_autoload')) {
    $suite = new PHPUnit_Framework_TestSuite('sqlbuilderTest');
    PHPUnit_TextUI_TestRunner::run($suite);
}
?>