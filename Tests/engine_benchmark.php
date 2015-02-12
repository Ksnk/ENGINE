<?php

if (!function_exists('phpunit_autoload')) {
    ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . dirname(dirname(__FILE__)));
    require 'PHPUnit/Autoload.php';
}

include ('header.inc.php');

class engine_benchmark extends PHPUnit_Framework_TestCase
{

    function staticCallXTime($x){
        // so we can use aninitialized error handler to test static call
        $dsp=microtime(true);
        for($i=0;$i<1000;$i++) ;
        $dsp=microtime(true) - $dsp ;
        ob_start();
        $time=microtime(true);
        for($i=0;$i<1000;$i++) ENGINE::error('12345');
        $time=microtime(true) - $time-$dsp;
        ob_end_clean();
        return $time;
    }

    function staticHandleXTime($x){
        // so we can use aninitialized error handler to test static call
        $dsp=microtime(true);
        for($i=0;$i<1000;$i++) ;
        $dsp=microtime(true) - $dsp ;
        ob_start();
        $past = ENGINE::register_interface('handle', array('ENGINE','error'));
        $time=microtime(true);
        for($i=0;$i<1000;$i++) ENGINE::handle('12345');
        $time=microtime(true) - $time-$dsp;
        ENGINE::register_interface('handle', $past) ;
        ob_end_clean();
        return $time;
    }

    function dinamicHandleXTime($x){
        // so we can use aninitialized error handler to test static call
        $dsp=microtime(true);
        for($i=0;$i<1000;$i++) ;
        $dsp=microtime(true) - $dsp ;
        ob_start();
        $past = ENGINE::register_interface('handle', array('ENGINE','error'));
        $time=microtime(true);
        for($i=0;$i<1000;$i++) ENGINE::$I->handle('12345');
        $time=microtime(true) - $time-$dsp;
        ENGINE::register_interface('handle', $past) ;
        ob_end_clean();
        return $time;
    }

    function functionHandleXTime($x){
        // so we can use aninitialized error handler to test static call
        $dsp=microtime(true);
        for($i=0;$i<1000;$i++) ;
        $dsp=microtime(true) - $dsp ;
        ob_start();
        $past = ENGINE::register_interface('handle', array('ENGINE','error'));
        $time=microtime(true);
        for($i=0;$i<1000;$i++) ENGINE::II()->handle('12345');
        $time=microtime(true) - $time-$dsp;
        ENGINE::register_interface('handle', $past) ;
        ob_end_clean();
        return $time;
    }

    function sample_error_handler($msg, $arg = null)
    {
        if (!empty($arg)) $msg .= ' ' . implode(',', $arg);
        echo 'ERROR happen ' . $msg;
    }

    function test_Benchmark_static_vs_all()
    {

        $static = $this->staticCallXTime(10000) ;
        $handle = $this->staticHandleXTime(10000) ;
        $dinam = $this->dinamicHandleXTime(10000) ;
        $func = $this->functionHandleXTime(10000);
        printf ("so spent %f sec for static, %f sec for ::,%f sec for ::\$I->,%f sec for ::II()->\n"
            ,$static,$handle,$dinam,$func) ;
        $this->assertTrue(true);
    }


}

if (!function_exists('phpunit_autoload')) {

    $suite = new PHPUnit_Framework_TestSuite('engine_benchmark');
    PHPUnit_TextUI_TestRunner::run($suite);
}
?>