<?php

if (!function_exists('phpunit_autoload')) {
    ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . dirname(dirname(__FILE__)));
    require 'PHPUnit/Autoload.php';
}

include ('header.inc.php');

class engine_optionsTest extends PHPUnit_Framework_TestCase
{

    function test_Options_basic()
    {
        $this->assertEmpty(ENGINE::option('xxx'), "testing uninitialized options failed\n");
        ENGINE::set_option('xxx', 'yyy');
        $this->assertEquals(ENGINE::option('xxx'), "yyy");
        ENGINE::set_option('xxx', '');
        $this->assertEmpty(ENGINE::option('xxx'));
    }

/*
    // сессии никак не проверить, плачет о хидерах, тварь такая
    function test_Options_session()
    {
        ENGINE::create_options(array('name', 'family', 'Unit designations'), 'session');
        $_SESSION = array();
        ENGINE::option('name', 'Vasia');
        $this->assertEquals($_SESSION, array('name' => 'Vasia'));
        ENGINE::option(array('name' => 'Petia', 'family' => 'Wolf clan'));
        $this->assertEquals($_SESSION, array('name' => 'Petia', 'family' => 'Wolf clan'));
    }
*/
    function test_Options_vardump()
    {
        $name = str_replace('\\', '/', dirname(__FILE__)) . '/xxx.php.txt';
        if (file_exists($name)) unlink($name);

        ENGINE::set_option(array('name1', 'family1', 'Unit designations1'), 'varexport|' . $name);
        ENGINE::set_option('name1', 'Vasia');
        ENGINE::set_option();

        $this->assertEquals(file_get_contents($name), "<?php\nreturn array (\n" .
            "  'name1' => 'Vasia',\n);");
        if (file_exists($name)) unlink($name);
    }

}

if (!function_exists('phpunit_autoload')) {
    $suite = new PHPUnit_Framework_TestSuite('engine_optionsTest');
    PHPUnit_TextUI_TestRunner::run($suite);
}
?>