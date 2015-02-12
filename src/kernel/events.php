<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Сергей
 * Date: 24.09.12
 * Time: 17:31
 * To change this template use File | Settings | File Templates.
 */
class engine_events
{
    /* <% POINT::start('ENGINE_header') %>*/
    private static $events = array();

    /* <% POINT::start('ENGINE_body') %>*/
    /**
     * Регистрация обработчика событий
     *
     * @static
     * @param string|array $event
     * @param null|callable $handler
     * @param string $phase - значения pre||post
     */
    static public function register_event_handler($event, $handler = null, $phase = '')
    {
        if (is_array($event)) {
            foreach ($event as $ev) {
                self::register_event_handler($ev, $handler, $phase);
            }
            return;
        }
        if (!empty($phase)) {
            if (!preg_match('/^post|pre$/', $phase))
                self::error(sprintf('ENGINE: Wrong $phase($s) value awhile registerring event handler `$s`'
                    , $phase, $handler));
            else {
                $event .= '/' . $phase;
            }
        }
        if (!isset(self::$events[$event]))
            self::$events[$event] = array();
        array_push(self::$events[$event], $handler);
    }

    /**
     * убрать обработчик событий
     *
     * @static
     * @param $event
     * @param null|callable $handler - либо функция, либо символическое имя. при отсутствии параметра чистится вся очередь события
     */
    static public function unregister_event_handler($event, $handler = null)
    {
        if (!isset(self::$events[$event]))
            return;
        if (is_null($handler))
            self::$events[$event] = array();
        else if (is_callable($handler)) {
            foreach (array($event . '/pre', $event, $event . '/post') as $ev)
                if (isset(self::$events[$ev])) {
                    $key = array_search($handler, self::$events[$ev]);
                    if ($key !== false)
                        unset(self::$events[$ev][$key]);
                }
        }
    }

    /**
     * вызвать все обработчики события
     *
     * @static
     * @param $event
     * @param null $args
     */
    static public function trigger_event($event, $args = null)
    {
        foreach (array($event . '/pre', $event, $event . '/post') as $ev)
            if (isset(self::$events[$ev]))
                foreach (self::$events[$ev] as &$handle) {
                    self::exec($handle, array($event, &$args));
                }
    }
    /* <% POINT::finish() %>*/

    function soTest (){
        /* <% POINT::start('BEFORE_GETDATA') %>*/
        ENGINE::trigger_event('BEFORE_GETDATA');
        /* <% POINT::finish() %>*/
    }
}
