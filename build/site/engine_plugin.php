<?php

/**
 * базовый класс плагинов системы
 */

class engine_plugin
{
    // работа с sql - конвертация полей в SQL запросы
    //var $fields=array();
//    var $db_table;




    /**
     * процедура инсталляции-деинсталляции модуля - выдать опции модуля для экспорта в систему
     * @return array
     */
    function get_options()
    {
        return array();
    }

    /**
     * процедура инсталляции-деинсталляции модуля
     * @param bool $install
     */
    function install($install = true)
    {

    }

    /**
     * Имеются ли права у текущего юзера на действие $action с объектом $object - по умолчанию - модулем
     * @param $action
     * @param string $object
     * @return mixed
     */
    function has_rights($action, $object = '')
    {
        if (empty($object)) {
            $object = ENGINE::get_class($this);
        }
        return ENGINE::has_rights($action, $object);
    }

    function error($msg,$par=array(),$rule='')
    {
        ENGINE::error($msg,$par,$rule);
    }
}

