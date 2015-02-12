<?php
/**
 * плагин для логирования действий пользователя .
 * Реализован лог в файл и лог в базу. Почему обы в одном файле - не спрашивать. не скажу ))
 */

class ENGINE_logger
{

    static $name = '';

    static function file_logger($msg, $arg=array())
    {
        $arg['{{date}}'] = date(DATE_RFC822);
        error_log(ENGINE::_t('{{date}} ' . $msg, $arg) . "\n",3,self::$name);
    }

    static function db_logger($msg, $arg=array())
    {
        if (!isset($arg['type'])) $arg['type'] = 'userlog';
        ENGINE::db()->query(
            'insert into `' . self::$name . '` set msg=?,type=?;'
            , array(ENGINE::_t($msg, $arg), $arg['type']));
    }

    static function log($msg, $arg=array())
    {
        $uri = parse_url(ENGINE::option('engine.logger_uri', 'file://logfile.log')); // m 'db:///logtable

        switch ($uri['scheme']) {
            case 'file':
                if (!empty($uri['host'])) $uri['path'] = $uri['host'] . $uri['path']; // so correct parse troubles
                // тут мы будем инициировать объект.
                self::$name=$uri['path'];
                ENGINE::register_interface('log', 'ENGINE_logger::file_logger');
                break;

            case 'db': // so let's use current database for logging
                self::$name = ENGINE::option('engine.log_table','userlog');
                ENGINE::db()->query('create table if not exists `' . self::$name . '`(
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`msg` VARCHAR( 200 ) NOT NULL ,
`type` VARCHAR( 8 ) NOT NULL ,
INDEX (  `date` )
) ENGINE = MYISAM ;');
                ENGINE::register_interface('log', 'ENGINE_logger::db_logger');
                break;
            //check if
        }

        ENGINE::log($msg, $arg);
    }

}

//ENGINE::register_interface('log','ENGINE_logger::check_logger') ;