<?php

if (!function_exists('phpunit_autoload')) {
    ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . dirname(dirname(__FILE__)));
    require 'PHPUnit/Autoload.php';
}

include ('header.inc.php');

class engine_eventsTest extends PHPUnit_Framework_TestCase
{

    function sample_event_handler($event, $arg = null)
    {
        $msg='';
        if (!empty($arg)) $msg .= ' ' . implode(',', $arg);
        printf( 'event1 %s happen %s',$event, $msg);
    }

    function sample_event_handler2($event, $arg = null)
    {
        $msg='';
        if (!empty($arg)) $msg .= ' ' . implode(',', $arg);
        printf( 'event2 %s happen %s',$event, $msg);
    }
    function sample_event_handler3($event, $arg = null)
    {
        $msg='';
        if (!empty($arg)) $msg .= ' ' . implode(',', $arg);
        printf( 'event3 %s happen %s',$event, $msg);
    }

    function test_Event_basic()
    {
        ob_start();
        ENGINE::trigger_event('handle', array(1, 2, 3, 4, 5));
        echo "|\n"; // border with test
        ENGINE::register_event_handler('handle',array($this,'sample_event_handler'));
        ENGINE::trigger_event('handle', array(1, 2, 3, 4, 5));
        echo "|\n"; // border with test
        ENGINE::unregister_event_handler('handle',array($this,'sample_event_handler'));
        ENGINE::trigger_event('handle', array(1, 2, 3, 4, 5));
        echo "|\n"; // border with test

        $msg = ob_get_contents();
        ob_end_clean();
        $this->assertEquals("|\nevent1 handle happen  1,2,3,4,5|\n|\n", $msg);
    }
    function test_Event_phase()
    {
        ob_start();
        ENGINE::register_event_handler('handle',array($this,'sample_event_handler'));
        ENGINE::trigger_event('handle', array(1, 2, 3, 4, 5));
        echo "|\n"; // border with test
        ENGINE::register_event_handler('handle',array($this,'sample_event_handler2'),'pre');
        ENGINE::trigger_event('handle', array(1, 2, 3, 4, 5));
        echo "|\n"; // border with test
        ENGINE::register_event_handler('handle',array($this,'sample_event_handler3'),'post');
        ENGINE::trigger_event('handle', array(1, 2, 3, 4, 5));
        echo "|\n"; // border with test
        ENGINE::unregister_event_handler('handle',array($this,'sample_event_handler'));
        ENGINE::trigger_event('handle', array(1, 2, 3, 4, 5));
        echo "|\n"; // border with test

        $msg = ob_get_contents();
        ob_end_clean();
        $this->assertEquals("event1 handle happen  1,2,3,4,5|\nevent2 handle happen  1,2,3,4,5event1 handle happen  1,2,3,4,5|\nevent2 handle happen  1,2,3,4,5event1 handle happen  1,2,3,4,5event3 handle happen  1,2,3,4,5|\nevent2 handle happen  1,2,3,4,5event3 handle happen  1,2,3,4,5|\n", $msg);
    }
}

if (!function_exists('phpunit_autoload')) {
    $suite = new PHPUnit_Framework_TestSuite('engine_eventsTest');
    PHPUnit_TextUI_TestRunner::run($suite);
}
?>