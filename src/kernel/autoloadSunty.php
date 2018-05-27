<?php
/**
 *
 */
class engine_autoload
{
    /* <% POINT::start('ENGINE_body') %>*/
    static public function _autoload($cls)
    {
        static $class_map;
        if(!isset($class_map))
            $class_map=ENGINE::option('engine.class_vocabular');
        if(isset($class_map[$cls]))
            $cls=$class_map[$cls];
        if (is_readable($y = ROOT_PATH . '/engine/' . $cls . '.php'))
            include($y);
        elseif (is_readable($y = ROOT_PATH . '/admin/engine/' . $cls . '.php'))
            include($y);
        elseif (is_readable($y = ROOT_PATH . '/admin/engine/plugins/' . $cls . '.php'))
            include($y);
        elseif (defined('TEMPLATE_PATH') && (is_readable($y = TEMPLATE_PATH . '/' . $cls . '.php')))
            include($y);
        else if (function_exists("__autoload"))
        {
            __autoload($cls) ;
        }

    }
    /* <% POINT::finish() %>*/
}

/* <% POINT::start('ENGINE_bottom') %>*/

spl_autoload_register('<%=$namespace."\"%>ENGINE::_autoload');

/* <% POINT::finish() %>*/