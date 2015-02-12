<?php
if (!function_exists('phpunit_autoload')) {
    $GLOBALS['runit']=true;
    ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . dirname(dirname(__FILE__)));
    require 'PHPUnit/Autoload.php';
}

include ('header.inc.php');

class just_a_test_class
{
    static $var = 0;

    function __construct()
    {
        self::$var++;
    }

    function interface3()
    {
        echo 'Calling interface3 function ' . self::$var . "\r\n";
    }

    static function interface1()
    {
        echo 'Calling interface1 function ' . self::$var . "\r\n";
    }

    static function interface2()
    {
        echo 'Calling interface2 function ' . self::$var . "\r\n";
    }

}

class engine_baseTest extends PHPUnit_Framework_TestCase
{
    function usual_handler($msg){
        ENGINE::set_option('page.error',ENGINE::option('page.error').$msg);
    }

    function sample_error_handler($msg, $arg = null)
    {
        if (!empty($arg)) $msg .= ' ' . implode(',', $arg);
        echo 'ERROR happen ' . $msg;
    }

    function test_Error_with_ujual_initialisation()
    {

        ENGINE::set_option('error.handler',array($this,'usual_handler'));
        ENGINE::set_option('error.format' ,"{msg}\r\n");
        ob_start();

        ENGINE::error('12345');
        $msg = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('12345

', ENGINE::option('page.error').$msg);
    }

    function test_Registerring_error_handler()
    {
        ENGINE::set_option('error.handler', array($this, 'sample_error_handler'));
        ob_start();
        ENGINE::error('12-{{hello}}-345',array('{{hello}}'=>'xxx'));
        ENGINE::error('67890');
        $msg = ob_get_contents();
        ob_end_clean();
       // ENGINE::register_interface('error', $past);
        $this->assertEquals("ERROR happen 12-xxx-345

ERROR happen 67890

", $msg);
    }

    function test_Registerring_any_handler()
    {
        ENGINE::set_option('page.error','');
        $past = ENGINE::register_interface('handle', array($this, 'sample_error_handler'));
        ob_start();
        ENGINE::handle('12345', array(1, 2, 3, 4, 5));
        ENGINE::handle('67890');
        ENGINE::register_interface('handle', $past);
        ENGINE::handle('abcdef');
        $msg = ob_get_contents();
        ob_end_clean();
        $this->assertEquals(
            "ERROR happen 12345 1,2,3,4,5ERROR happen 67890ERROR happen unresolved callable handle

|"
            , $msg.'|'. ENGINE::option('page.error'));
    }

    function test_Registerring_array_from_class()
    {
        ENGINE::register_interface('interface1', 'just_a_test_class::interface1');
        ENGINE::register_interface('interface2', 'just_a_test_class::interface2');
        ENGINE::register_interface('interface3', array('just_a_test_class', 'interface3'));
        ob_start();
        ENGINE::interface1('12345', array(1, 2, 3, 4, 5));
        ENGINE::interface2('67890');
        ENGINE::interface3('67890');
        ENGINE::interface1('12345', array(1, 2, 3, 4, 5));
        ENGINE::interface2('67890');
        ENGINE::interface3('67890');
        $msg = ob_get_contents();
        ob_end_clean();
        $this->assertEquals("Calling interface1 function 0
Calling interface2 function 0
Calling interface3 function 1
Calling interface1 function 1
Calling interface2 function 1
Calling interface3 function 1
"
            , $msg);
    }

}

if (isset($GLOBALS['runit'])) {
    $suite = new PHPUnit_Framework_TestSuite('engine_baseTest');
    PHPUnit_TextUI_TestRunner::run($suite);
}
?>