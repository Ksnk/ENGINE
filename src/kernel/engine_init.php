<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Сергей
 * Date: 19.07.13
 * Time: 21:33
 * To change this template use File | Settings | File Templates.
 */

class ENGINE_init {
    /* <% POINT::start('ENGINE_body') %>*/
    /**
     * инициализация системы и прописка основных обработчиков
     * @static
     * @param string|array $options
     */
    static function init($options)
    {
        ini_set('session.use_trans_sid', '0');
        ini_set('session.use_cookies', '1');
        ini_set('session.bug_compat_42', '0');
        ini_set('allow_call_time_pass_reference', '1');

        if (defined('ROOT_URI')) ENGINE::set_option('page.root', ROOT_URI);

        if (is_array($options)) {
            ENGINE::set_option($options);
        } else if (is_readable($options)) {
            ENGINE::set_option(include ($options));
        } else {
            ENGINE::error('Init: parameter failed');
        }
        //////////////////////////////////////////////////////////////////////////////////
// register all default interfaces
        foreach (ENGINE::option('engine.interfaces', array()) as $k => $v)
            ENGINE::register_interface($k, $v);

//////////////////////////////////////////////////////////////////////////////////
// include all classes from `engine.include_files`
        foreach (ENGINE::option('engine.include_files', array()) as $f)
            include_once($f);

        self::$class_alias = ENGINE::option('engine.aliaces', array());

        foreach (ENGINE::option('external.options', array()) as $k => $v)
            ENGINE::set_option($k, null, $v);

        foreach (ENGINE::option('engine.event_handler', array()) as $k => $v) {
            if (is_array($v) && count($v) > 0) {
                foreach ($v as $vv) {
                    ENGINE::register_event_handler($k, $vv);
                }
            }
        }
    }

    /* <% POINT::finish() %>*/
}