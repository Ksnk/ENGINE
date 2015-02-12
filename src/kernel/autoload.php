<?php
/**
 *
 */
class engine_autoload
{
    /* <% POINT::start('ENGINE_body') %>*/
    static public function _autoload($cls)
    {
        //static $include_path;
        $classes = ENGINE::option('engine.class_vocabular');
        if (is_array($classes) && isset($classes[$cls])) {
            $x = $classes[$cls];
            if (substr($x, -1, 1) == '/') {
                $x.=$cls;
            }
        } else {
            $x=$cls;
        }  ;
        $x.= '.php' ;
        if(!isset($include_path)) {
            $include_path=ENGINE::option('engine.include_path',array('.'));
        }
        foreach($include_path as $path) {
            if (is_readable($path .'/'.self::$SUBDIR. $x)){
                include_once($path .'/'.self::$SUBDIR. $x);
                return;
            }
        }
        if (function_exists("__autoload"))
        {
            __autoload($cls) ;
        }

    }
    /* <% POINT::finish() %>*/
}

/* <% POINT::start('ENGINE_bottom') %>*/

spl_autoload_register('ENGINE::_autoload');

/* <% POINT::finish() %>*/