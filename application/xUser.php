<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Сергей
     * Date: 11.10.12
     * Time: 19:14
     * To change this template use File | Settings | File Templates.
     */

class xUser extends ENGINE_user
{

    static $DROP_SELF_SQL = "
---
    drop table if exists {{prefix}}_right_self;
    DROP TABLE IF EXISTS {{prefix}}_right_users;
    drop table if exists {{prefix}}_right_tree;
    drop table if exists {{prefix}}_users ;
";

    static $CREATE_SELF_SQL = "
        --
-- Table structure for table {{TABLE_SELF}}
--

CREATE TABLE IF NOT EXISTS {{prefix}}_right_self (
    `ID_AXO` int(11) NOT NULL,
  `ID_ARO` int(11) NOT NULL,
  `allow` tinyint(1) NOT NULL,
  `action` varchar(30) NOT NULL,
  `level` int(11) NOT NULL,
  KEY `ID_ARO` (`ID_ARO`),
  KEY `ID_AXO` (`ID_AXO`),
  KEY `allow` (`allow`),
  KEY `level` (`level`)
) ;

--
-- Table structure for table {{prefix}}_right_users
--
CREATE TABLE {{prefix}}_right_users (
    `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `value` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
)  ;

--
-- Table structure for table {{prefix}}_right_tree
--

CREATE TABLE IF NOT EXISTS {{prefix}}_right_tree(
    `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL DEFAULT '0',
  `value` varchar(128) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `param` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  KEY `name` (`name`),
  KEY `type` (`type`)
) ;

--
--

    INSERT INTO {{prefix}}_right_tree (`id`, `parent`, `value`, `name`, `type`) VALUES
(1, 0, '', 'Пользователи и права','axo_group'),
(2, 1, '3|0|*', '','right'),
(3, 0, '', 'Объекты','aro_group');

--
-- Table structure for table `xsite_users`
--

CREATE TABLE IF NOT EXISTS {{prefix}}_users (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `password` varchar(100) NOT NULL,
  `rights` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `password` (`password`),
  KEY `rights` (`rights`)
)  ;

--
-- Dumping data for table {{prefix}}_users
--

INSERT INTO {{prefix}}_users (`id`, `name`, `password`, `rights`) VALUES
(1, 'Admin', 'password', 6);
";

    function install($install = true)
    {
        $sql = self::$DROP_SELF_SQL;
        if ($install)
            $sql .= self::$CREATE_SELF_SQL;

        ENGINE::db()->sql_dump($sql);
        if ($install)
            ENGINE::exec(array('Rights','create_first'));
    }



    function _construct(){

    }

    function do_logout(){
        ENGINE::set_option('USER','');
        return 'ok';
    }

    /**
     * интерфейсная функция - получить список пользователей в виде ассоциативного массива
     */
    function getList(){
        return ENGINE::db()->select(
            'select * from {{prefix}}_right_users;'
        ) ;
    }

    function user_find($login, $password)
    {
      //  SELECT a.id as userid, b.value as rights from xsite_users as a left join xsite_right_users as b on a.rights=b.id where a.name='' and a.password=''
       // @var xDatabase $db
        return ENGINE::db()->selectRow('SELECT a.id as id, b.value as rights '
            .'from {{prefix}}_users as a left join {{prefix}}_right_users as b on a.rights=b.id where '
            .'a.name={{?}} and a.password={{?}};',$login,$password  );
    }


}