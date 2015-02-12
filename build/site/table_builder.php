<?php
/**
 * статический класс для автоматизации строительства и обслуживания типичных запрососв в mysql
 *
 * Исходными данными является массивс описанием полей.
 */

class SQL_BUILDER {

    /**
     * @param $dbname
     * @param array $dbfields
     */
    function build_alter_table($dbname,$dbfields){
        // show all fields
        $sql= '';
        $fields=ENGINE::db()->select('SHOW FIELDS FROM {{?|noescape}}',$dbname);
        // build delete fields query
        foreach($fields as $value){
            if(!array_key_exists($value['Field'],$dbfields)){
               $sql.="alter table `".$dbname."` drop `".$value['Field']."`;\n\n";
            }
        }
        // build insert fields
        foreach($fields as $value){
            if(!array_key_exists($value['Field'],$dbfields)){
                $sql.="alter table `".$dbname."` drop `".$value['Field']."`;\n\n";
            }
        }
        reset($sql);next($sql); $sql= current($sql);
        $result=array();
        // scan for field list

    }

    function field_type($field) {
        $result='int(3) NOT NULL';
        switch($field['type']){
            case 'str':
                return ' varchar(200) NOT NULL';
            case 'file':
                return ' varchar(128) NOT NULL';
            case 'int':
                $result= ' int(11) NOT NULL';
        };
        if(!!$field['AUTO'])
            $result.=' AUTO_INCREMENT';
        return $result;
    }

    function build_create_table($dbname,$dbfields){
        $sql=array();
        $keys[]='PRIMARY KEY (`id`)';
        foreach($dbfields as $k=>$field) {
            $sql[]= "\t`".$k.'`'.$this->field_type($field);
            if ($field['UNI']) {
                $keys[]="\tKEY `".$k.'` (`'.$k.'`)';
            } elseif ($field['IND']) {
                $keys[]="\tKEY `".$k.'` (`'.$k.'`)';
            }
        }
        return 'CREATE TABLE IF NOT EXISTS '.$dbname."(\r\n"
            .implode(",\r\n",$sql).",\r\n"
            .implode(",\r\n",$keys)
            ."\r\n);";
    }

}