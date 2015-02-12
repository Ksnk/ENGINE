<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Сергей
 * Date: 27.09.12
 * Time: 10:39
 * To change this template use File | Settings | File Templates.
 */
class ENGINE_session
{
    /* <% POINT::start('ENGINE_header') %>*/
    static public $session_started = false;

    /* <% POINT::start('ENGINE_body') %>*/
    static function start_session($log=true)
    {
        if (!self::$session_started) {
            $session_name = ENGINE::option('engine.sessionname');
            if (!empty($session_name)) {
                session_name($session_name);
            }
            session_start();
            if($log){
            $log = array();
            foreach (array('REMOTE_ADDR', 'X-Forwarded-For', 'X-Real-IP') as $name) {
                if (isset($_SERVER[$name])) {
                    $log[$_SERVER[$name]] = $name;
                }
            }
            ENGINE::log(
                'Старт сессии.
IP:{{IP}}
REF:"{{HTTP_REFERER}}"
UA:"{{HTTP_USER_AGENT}}"', array('type' => 'session',
                    '{{IP}}' => implode(',', array_keys($log)),
                    '{{HTTP_REFERER}}' => ENGINE::_($_SERVER['HTTP_REFERER'], '-'),
                    '{{HTTP_USER_AGENT}}' => ENGINE::_($_SERVER['HTTP_USER_AGENT'], '-')
                )
            );
            }
            self::$session_started = true;
        }
    }

    static function startSessionIfExists()
    {
        if (self::$session_started) {
            return;
        }

        $session_name = ENGINE::option('engine.sessionname', session_name());
        if (array_key_exists($session_name, $_GET)
            && array_key_exists($session_name, $_COOKIE)
        ) {
            ENGINE::set_option('action', 'reload');
        }
        if (array_key_exists($session_name, $_GET)
            || array_key_exists($session_name, $_COOKIE)
        ) {
            ENGINE::start_session(false); //session_start();
        }
    }

    static function close_session()
    {
        if (self::$session_started) {
            session_write_close();
            self::$session_started = false;
        }
    }
    /* <% POINT::finish() %>*/
}

/* <% POINT::start('ENGINE_bottom') %>*/
/**
 * класс для хранения параметров в сессии
 */
class engine_options_session
{

    function get($name)
    {
        if (ENGINE::$session_started)
            return $_SESSION[$name];
        else
            return null;
    }

    function set($name, $value = null)
    {
        ENGINE::start_session();
        if (empty($value))
            unset($_SESSION[$name]);
        else
            $_SESSION[$name] = $value;
    }

}

/* <% POINT::finish() %>*/

