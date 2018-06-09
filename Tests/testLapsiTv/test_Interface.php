<?php
/**
 * Created by PhpStorm.
 * User: Аня
 * Date: 03.06.2018
 * Time: 20:06
 */

namespace Ksnk\Tests\testLapsiTv;

require "../../vendor/autoload.php";

class tE extends \Ksnk\core\ENGINE {};

class engine_cache_testcache  {

    static $instance_conter=0;

    function __construct(){
        self::$instance_conter++;
    }

    function cache($key, $value = null, $time = null, $tags = null){
        static $store=[];
        if(is_null($value))
            if(isset($store[$key]))
                return '>>>'.$store[$key];
            else
                return null;
        else
            $store[$key]=$value;
       // echo 'here';
       // return $key.'>>1'.PHP_EOL;
        return null;
    }
}

class test_Interface extends \PHPUnit\Framework\TestCase
{

    function test1stage()
    {
        tE::set_option('axaxa', 'oxoxo');
        $this->assertEquals(
            'oxoxo'
            , tE::option('axaxa')
        );
    }

    function test1_5stage(){
        $x= new \Ksnk\Tests\testLapsiTv\engine_cache_testcache();
        $x::cache('aaa1','bbb1');
        $this->assertEquals(
            '>>>bbb1',
            $x::cache('aaa1')
        );
    }

    function test2stage()
    {
        $ic=engine_cache_testcache::$instance_conter;
        tE::register_interface('cache');
        tE::set_option('interface.cache','testcache');
        tE::register_interface('cache',[__NAMESPACE__.'\engine_cache_testcache','cache']);

        // проверяем, что результат из нашего метода
        tE::cache('aaa','bbb');
        $this->assertEquals(
            '>>>bbb',
            tE::cache('aaa')
        );
        // проверяем, что объект заново инициализирован
        $this->assertEquals(
            $ic+1,
            engine_cache_testcache::$instance_conter
        );
        // проверяем, что результат из нашего метода
        tE::cache('ссс','ddd');
        $this->assertEquals(
            '>>>ddd',
            tE::cache('ссс')
        );
        // проверяем, что объект больше не инициализируется
        $this->assertEquals(
            $ic+1,
            engine_cache_testcache::$instance_conter
        );
    }
}

