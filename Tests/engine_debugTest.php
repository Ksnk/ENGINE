<?php

if (!function_exists('phpunit_autoload')) {
    ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . dirname(dirname(__FILE__)));
    require 'PHPUnit/Autoload.php';
}

include ('header.inc.php');

class engine_debugTest extends PHPUnit_Framework_TestCase
{

    function test_backtrace()
    {
        // отладочное тестирование для коррекции функций вывода информации
        $this->assertEquals(ENGINE::backtrace(), 'func:test_backtrace,cls:engine_debugTest'."\n".
'|func:invokeArgs,cls:ReflectionMethod,file:Z:\usr\local\php5\pear\pear\PHPUnit\Framework\TestCase.php,line:942'."\n".
'|func:runTest,cls:PHPUnit_Framework_TestCase,file:Z:\usr\local\php5\pear\pear\PHPUnit\Framework\TestCase.php,line:804');
    }

    function func1($x){
        return $this->func2($x);
    }
    function func2($x){
        return $this->func3($x);
    }
    function func3($x){
        return ENGINE::backtrace($x);
    }

    function test_func1()
    {
        // отладочное тестирование для коррекции функций вывода информации
        $this->assertEquals($this->func1(array('function'=>'func1'))
            , 'func:func1,cls:engine_debugTest,file:D:\projects\cms\Tests\engine_debugTest.php,line:34');
    }

    function test_func2()
    {
        // отладочное тестирование для коррекции функций вывода информации
        $this->assertEquals($this->func1(array('function'=>'func2'))
            , 'func:func2,cls:engine_debugTest,file:D:\projects\cms\Tests\engine_debugTest.php,line:22');
    }

    function test_func3()
    {
        // отладочное тестирование для коррекции функций вывода информации
        $this->assertEquals($this->func1(array('function'=>'func3'))
            , 'func:func3,cls:engine_debugTest,file:D:\projects\cms\Tests\engine_debugTest.php,line:25');
    }

}

if (!function_exists('phpunit_autoload')) {
    $suite = new PHPUnit_Framework_TestSuite('engine_debugTest');
    PHPUnit_TextUI_TestRunner::run($suite);
}
?>