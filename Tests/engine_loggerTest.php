<?php

if (!function_exists('phpunit_autoload')) {
    ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . dirname(dirname(__FILE__)));
    require 'PHPUnit/Autoload.php';
}

include ('header.inc.php');

class engine_loggerTest extends PHPUnit_Framework_TestCase
{

    function remove_starting_date($msg){
        return preg_replace('/^.*\d\d:\d\d:\d\d ([+-]\d+ )?/m','',$msg);
    }

   function test_file_logger()
    {
        $file='xxx.log';
        ENGINE::register_interface('log','ENGINE_logger::log');
        ENGINE::set_option('engine.logger_uri','file://'.$file);
        ENGINE::log("12345");
        ENGINE::log("{{hello}}",array('{{hello}}'=>'56789'));
        $msg = file_get_contents($file);
        unlink($file);
        $this->assertEquals("12345\n56789", trim($this->remove_starting_date($msg)));
    }

}

if (!function_exists('phpunit_autoload')) {
    $suite = new PHPUnit_Framework_TestSuite('engine_loggerTest');
    PHPUnit_TextUI_TestRunner::run($suite);
}
?>