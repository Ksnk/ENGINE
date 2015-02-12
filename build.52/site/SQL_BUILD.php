<?php
/**
 * статический класс для автоматизации строительства и обслуживания типичных запросов в mysql
 *
 * Исходными данными является массив с описанием полей.
 */

class SQL_BUILD
{

    const NL="\r\n";

    /**
     * @param $dbname
     * @param array $dbfields
     */
    static function alter($dbname, $dbfields)
    {
        // show all fields
        $sql = '';
        $fields = ENGINE::db()->select('SHOW FIELDS FROM `'.$dbname.'`', $dbname);
        // build delete fields query
        foreach ($fields as $value) {
            if (!array_key_exists($value['Field'], $dbfields)) {
                $sql .= "alter table `" . $dbname . "` drop `" . $value['Field'] . "`;".self::NL;
            }
        }
        // build insert fields
        foreach ($dbfields as $name=>$field) {
            if (!array_key_exists($name, $fields)) {
                $sql .= "alter table `" . $dbname . "` add `" . $name . "` ".self::field_type($field).self::NL;
            }
        }

        echo $sql;
        return '';//$sql;
    }

    static private function field_type($field)
    {
        $result = 'int(3) NOT NULL';
        switch ($field['type']) {
            case 'str':
                return ' varchar(200) NOT NULL';
            case 'str10':
                return ' varchar(10) NOT NULL';
            case 'str80':
                return ' varchar(80) NOT NULL';
            case 'text':
                return ' text';
            case 'file':
                return ' varchar(128) NOT NULL';
            case 'int':
                $result = ' int(11) NOT NULL';
        }
        if (!!$field['AUTO'])
            $result .= ' AUTO_INCREMENT';
        return $result;
    }

    static function drop($dbname){
        return sprintf('drop table if exists %s;'.self::NL,$dbname);
    }

    static function create($dbname, $dbfields)
    {
        $sql = array();
        $keys = array();
        foreach ($dbfields as $k => $field) {
            $sql[] = "\t`" . $k . '`' . self::field_type($field);
            if (ENGINE::_($field['UNI'])) {
                $keys[] = "\tKEY `" . $k . '` (`' . $k . '`)';
            } elseif (ENGINE::_($field['PRI'])) {
                $keys[] = "\tPRIMARY KEY (`".$k."`)";
            }elseif (ENGINE::_($field['IND'])) {
                $keys[] = "\tKEY `" . $k . '` (`' . $k . '`)';
            }
        }
        return 'CREATE TABLE IF NOT EXISTS ' . $dbname . "(".self::NL
            . implode(",".self::NL, $sql) . ",".self::NL
            . implode(",".self::NL, $keys)
            .self::NL. ");".self::NL;
    }

    static private function get_def($field,$dbfields) {
         if(array_key_exists($field,$dbfields)){
             if(array_key_exists('default',$dbfields[$field]))
                 return $dbfields[$field]['default'];
             switch($dbfields[$field]['type']){
                 case 'int':
                     return 0;
                 default:
                     return '';
             }
         } else {
             // todo :wtf, It's an error!
             return '';
         }
    }

    static function insert($dbname, $fields,$dbfields)
    {
        if(empty($fields)) return '';
        $sql='INSERT INTO '.$dbname.' ';
        $keys=array();
        foreach($fields as $f)
        foreach($f as $k=>$v) {
            $keys[$k]=true;
        }

        $sql.='(`'.implode('`, `',array_keys($keys)).'`) VALUES'.self::NL;
        $xx=array();
        foreach($fields as $f)    {
            $x=array();
            foreach($keys as $k=>$val) {
                $v=array_key_exists($k,$f)?$f[$k]:self::get_def($k,$dbfields);
                if(ctype_digit(''.$v))
                    $x[]=$v;
                else
                    $x[]='"'.mysql_real_escape_string($v).'"';
            }
            $xx[]='('.implode(', ',$x).')';
        }
        return $sql. implode($xx,','.self::NL).";".self::NL;
    }

}