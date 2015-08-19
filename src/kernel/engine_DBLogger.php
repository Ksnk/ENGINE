<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Сергей
 * Date: 23.08.13
 * Time: 12:51
 * To change this template use File | Settings | File Templates.
 */

class engine_Db_logger
{
    /* <% POINT::start('ENGINE_header') %>*/
    static private $_logger_table = false;

    /* <% POINT::start('ENGINE_body') %>*/
    /**
     * вывод собощения в системный лог.
     * Записывается сессия пользователя, если она есть.
     * По дороге делается попытка проинициировать таблицу лога.
     *
     * @param string $msg сообщение
     * @param array  $arg параметры в стиле _t + параметр type -
     *
     * @return empty
     */
    static function log($msg, $arg = array())
    {
        if (!self::$_logger_table) {
            $uri = parse_url(
                ENGINE::option('engine.logger_uri', 'db://userlog')
            );
            if (!empty($uri['host'])) {
                $uri['path'] = $uri['host'] . ENGINE::_($uri['path']);                    }
            self::$_logger_table = ENGINE::option('engine.log_table', $uri['path']);
        }
        if (is_string($arg)) {
            $arg = array('type' => $arg);
        } else if (!isset($arg['type'])) {
            $arg['type'] = 'userlog';
        }
        if (!isset($arg['key'])) {
            $arg['key'] = session_id();
        }
        for($i=1;$i<2;$i++){
        $res=ENGINE::db()->query(
            'insert into `' . self::$_logger_table .
            '` set `msg`=?,`type`=?,`key`=?;',
            ENGINE::_t($msg, $arg), $arg['type'], $arg['key']
        );
            if(!$res){
                ENGINE::db()->query(
                    'create table if not exists `' . self::$_logger_table . '`(
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`msg` TEXT ,
`type` VARCHAR( 8 ) NOT NULL ,
`key` VARCHAR( 40 ) NOT NULL ,
  PRIMARY KEY (`id`),
  KEY `date` (`date`),
  KEY `key` (`key`)
) ENGINE = MYISAM ;'
                );
            }
        }
    }
    /* <% POINT::finish() %>*/
}
