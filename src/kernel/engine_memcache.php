<?php
/**
 * Реализаwия инерфейса cache для варианта с memcached
 * User: Сергей
 * Date: 10.06.13
 * Time: 10:52
 * To change this template use File | Settings | File Templates.
 */

class ENGINE_memcache {
    /*<% POINT::start('ENGINE_header'); %>*/
    /** @var Memcache null */
    private static $memcached = null;

    /*<% POINT::start('ENGINE_body'); %>*/
    /**
     * упрощенный доступ к memcached
     * @static
     * @param $key
     * @param null $value
     * @param int $time в секундах 28800 - 8 часов
     * @param array $tags - список дополнительных тегов.
     * @return bool|null
     */
    static function cache($key, $value = null, $time = null,$tags=null)
    {
        if(!class_exists('Memcache'))
            return false;
        if(is_null($time))$time=28800;
        if (empty(self::$memcached)) {
            self::$memcached = new Memcache;
            self::$memcached->connect('localhost', 11211);
            if (empty(self::$memcached)) return false;
        }
        if(!empty($tags)){
            foreach($tags as $tag){
                $x=self::$memcached->get($tag);
                if(empty($x)) self::$memcached->set($tag,$x=1);
                $key.='.'.$x;
            }
        }
        if (strlen($key) > 40) $key = md5($key);
        if (is_null($value)) {
            if ($GLOBALS['SERVER_PORT'] == "8080") return false;
            return self::$memcached->get($key);
        } else {
            self::$memcached->set($key, $value, MEMCACHE_COMPRESSED,  $time);
        }
        return false;
    }
    /*<% POINT::finish(); %>*/
    static function done(){
        /*<% POINT::start('ENGINE_shutdown'); %>*/
        if (self::$memcached) {
            self::$memcached->close();
            self::$memcached = null;
        }
        /*<% POINT::finish(); %>*/
    }
}