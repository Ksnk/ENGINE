<?php
/**
 * Декорация. Отметка времени начала выполнения и вывод финального репорта.
 * Совместно с шатдауном и репортом всех сервисов.
 * User: Сергей
 * Date: 08.06.13
 * Time: 14:00
 * To change this template use File | Settings | File Templates.
 */

class ENGINE_shutdown
{
    /* <% POINT::start('ENGINE_header') %>*/
    static $start_time;

    /* <% POINT::start('ENGINE_body') %>*/
    static public function _report()
    {
        echo '<!--';
        /* <%=POINT::get('ENGINE_final_report'); %>*/
        printf("%f sec spent (%s)"
            , microtime(true) - self::$start_time, date("Y-m-d H:i:s"));
        echo '-->';
    }

    static public function _shutdown()
    {
        /* <%=POINT::get('ENGINE_shutdown');%> */

        if (ENGINE::option('noreport')) return;
        ENGINE::_report();
    }
    /* <% POINT::finish() %>*/

}

/* <% POINT::start('ENGINE_bottom') %>*/
register_shutdown_function(__NAMESPACE__.'\ENGINE::_shutdown');
ENGINE::$start_time = microtime(true);
/* <% POINT::finish() %>*/
