<?php

/**
 * реализация
 */

class xRights extends ENGINE_rights
{

    /**
     * массив дерева Adjacency List в рекурсивную форму
     * @param $result
     * @return array
     */
    private function convert($result)
    {
        $node = array();
        $root = array();
        //debug($result);
        foreach ($result as $v) {
            $parent = $v['parent'];
            if ('right' == $v['type']) {
                list($xid, $xallow, $xaction) = explode('|', $v['value']);
                $node[$v['id']] = array('key' => $v['id'], 'type' => $v['type'], 'id' => $xid, 'allow' => $xallow, 'action' => $xaction);
                $node[$parent]['prop'][] =& $node[$v['id']];
            } elseif ('action' == $v['type']) {
                $node[$v['id']] = array('key' => $v['id'], 'type' => $v['type'], 'value' => $v['value']);
                $node[$parent]['prop'][] =& $node[$v['id']];
            } else {
                $node[$v['id']] = array('key' => $v['id'], 'type' => $v['type'], 'title' => $v['name'], 'value' => $v['value']);
                if ($parent != 0) {
                    $node[$parent]['children'][] =& $node[$v['id']];
                } else {
                    $root[] =& $node[$v['id']];
                }
            }
        }
        // debug($root);

        return $root;
    }

    /**
     * реализация для случая 3-х таблиц
     * -- таблица self - список установленных прав
     * -- таблица users - список объектов ARO с дополнительными параметрами
     * -- таблица tree - определение групп ARO и AXO и Aco как корневые ветки в дереве
     *
     * интерфейс с внешним миром
     * -- регистрация нового пользователя
     * --- RIGHTS\tree::user($name,$group) - вставка нового юзера в группу $default
     */


    function has_rights($object, $user = '', $right = 'read')
    {
        if (empty($user)) {
            $user = ENGINE::option('USER');
        }
        if (empty($user)) {
            $user = 'Unauthorised';
        }
        return ENGINE_rights::can($user, $object, $right);
    }


    static $CREATE_SELF_SQL = "
";

    /**
     * добавить пользователя в список пользователей
     * @param $name
     * @param $default
     * @return void
     */
    private function user($name, $default)
    {

    }

    /**
     * return only one leaf from tree
     * @param $id
     * @return mixed
     */
    static function get_leaf($id)
    {
        if (is_numeric($id))
            return self::$DATABASE->selectRow(
                'select * from {{prefix}}_right_tree where `id`={{?|int}}', $id);
        else
            return self::$DATABASE->selectRow('select * from {{prefix}}_right_tree where `name`=?', $id);
    }

    /**
     * return object names only
     * @return array|null|void
     */
    static function get_objects()
    {
        return self::$DATABASE->selectCol(
            'select `name` from {{prefix}}_right_tree where `type`="aro" ORDER BY `name`;');
    }

    /**
     * return only one leaf from tree
     * @param $type
     * @internal param $id
     * @return mixed
     */
    static function get_root($type)
    {
        return self::$DATABASE->selectRow(
            'select * from {{prefix}}_right_tree where `parent`=0 and `type`={{?}}', $type);
    }

    private static function node($id = 0, $childonly = false)
    {
        if (empty($id) || $childonly)
            $result = array();
        else
            $result = self::$DATABASE->select('select * from {{prefix}}_right_tree where `id`={{?}};', $id);
        $childs = self::$DATABASE->select('select * from {{prefix}}_right_tree where `parent`={{?}} order by `name`;', $id);
        $result = array_merge($result, $childs);
        foreach ($childs as $v) {
            $result = array_merge($result, self::node($v['id'], true));
        }
        return $result;
    }

    /**
     * get subtree from node $id
     * @param $id - node
     * @return array
     */
    static function get($id)
    {
        return self::node($id);
    }

    /**
     * delete subtree from node $id
     * @param $id - node
     * @return array
     */
    static function delete($id)
    {
        $node = self::node($id);
        $indexes = array();
        if (empty($node))
            return;
        // debug($node);
        foreach ($node as &$v)
            $indexes[] = $v['id'];
        //debug($indexes);
        if (!empty($indexes))
            self::$DATABASE->select('delete from {{prefix}}_right_tree where `ID` in (' . implode(',', $indexes) . ');');
    }

    /**
     * построить все пути от всех вершин с именем $name
     * @static
     * @param $name
     * @param $id
     * @return array
     */
    static function build_path($name, $id = -1)
    {
        if ($id == 0) {
            return array();
        } elseif ($id > 0) {
            $self = self::$DATABASE->select('select `id`,`parent` from {{prefix}}_right_tree where `id`={{?|int}};', $id);
        } else {
            $self = self::$DATABASE->select('select `id`,`parent` from {{prefix}}_right_tree where `name`={{?}};', $name);
        }
        $result = array();
        foreach ($self as $v) {
            $result[] = $v['id'];
            $result = array_merge($result, self::build_path('', $v['parent']));
        }
        return $result;
    }

    /**
     * выдать список всех действий, допустимых над объектом с именем $name
     * @static
     * @param string $name
     * @return mixed
     */
    static function get_actions($name)
    {
        $parents = array_unique(self::build_path($name), true);
        self::$DATABASE->selectCol('select distinct `name` from {{prefix}}_right_tree where `parent` in ({{?|join(",")}}) and type="aco" order by `name`;', $parents);
    }

    /**
     * update leaf into node $id
     * @param string $name
     * @param int $id - node
     * @param string $type
     * @param string $value
     * @return array
     */
    static function update($name, $parent = 0, $type = '', $value = '', $id = 0)
    {
        // $leaf=ENGINE::get_leaf($id);
        if ($id == 0) {
            $x = self::$DATABASE->selectCell('select `id` from {{prefix}}_right_tree '
                    . ' where name={{?}} and parent={{?|int}} and `type`={{?}} and value ={{?}};'
                , $name, $parent, $type, $value);
            if (empty($x))
                return self::$DATABASE->insert('insert into {{prefix}}_right_tree '
                        . '(name,parent,type,`value`)values({{?}},{{?|int}},{{?}},{{?}});'
                    , $name, $parent, $type, $value);
            return
                $x;
        } else {
            self::$DATABASE->query('update {{prefix}}_right_tree '
                    . 'set name={{?}},parent={{?|int}},type={{?}},value={{?}} where id={{?|int}};'
                , $name, $parent, $type, $value, $id);
            return $id;
        }
    }

    /**
     * update leaf into node $id
     * @param string $name
     * @param int $id - node
     * @param string $type
     * @param string $value
     * @return array
     */
    /*
    static function update($name,$id=0, $parent=0, $type='',$value=''){
        $x=ENGINE::$DATABASE->selectCell('select `id` from '.TABLE_TREE.' where name=? and parent={{?|in}} and `type`=? and value =?;',$name,$id,$type,$value);
        if(empty($x))
           return ENGINE::$DATABASE->query('insert into '.TABLE_TREE.' (name,parent,type,`value`)values(?,{{?|in}},?,?);',$name,$id,$type,$value);
        return
           $x;
    }
      */
    static function xxx(&$subtree, &$path, &$user, &$rights, $prefix = '')
    {
        foreach ($subtree as &$v) {
            // заполняем таблицу users
            if (!isset($users[$v['key']]))
                $users[$v['key']] = array();
            if ($v['type'] == 'right') {
                //debug($v);
                $x = count($path);
                $rights[] = array('id_aro' => $path[$x - 1], 'id_axo' => $v['id']
                , 'allow' => $v['allow'], 'action' => $v['action'], 'level' => count($path));
            } else if (preg_match('/^a[rx]o/', $v['type'])) {
                if (!isset($user[$prefix . $v['title']]))
                    $user[$prefix . $v['title']] = array();
                $user[$prefix . $v['title']] = array_unique(array_merge($path, $user[$prefix . $v['title']], array($v['key'])));
            }
            if (isset($v['children'])) {
                array_push($path, $v['key']);
                if (preg_match('/_prefix$/', $v['type']))
                    $pref = $v['title'] . '|';
                else
                    $pref = '';
                self::xxx($v['children'], $path, $user, $rights, $pref);
                array_pop($path);
            }
            if (isset($v['prop'])) {
                array_push($path, $v['key']);
                self::xxx($v['prop'], $path, $user, $rights);
                array_pop($path);
            }
        }
    }

    /**
     * @static
     * @param array $tree - рекурсивный список
     * @return string
     */
    static function compile($tree)
    {
        // create SQL statment
        $user = array();
        $rights = array();
        $path = array();
        self::xxx($tree, $path, $user, $rights);

        //debug('path',$path,'user',$user,'rights',$rights);
        $sql_users = array(); //"INSERT INTO {{prefix}}_right_users (`name`, `value`) VALUES ";
        foreach ($user as $k => $v) {
            $sql_users[] = "'" . mysql_real_escape_string($k) . "','" . mysql_real_escape_string(implode(',', $v)) . "'";
        }
        $sql_users = "INSERT INTO {{prefix}}_right_users (`name`, `value`) VALUES
    ("
            . implode('),
    (', $sql_users) . ");\n";

        $sql_rights = array(); //"INSERT INTO {{prefix}}_right_self (`id_aro`, `id_axo`,`allow`,`action`,`level`) VALUES ";
        foreach ($rights as $v) {
            $sql_rights[] = $v['id_aro'] . ","
                . $v['id_axo'] . ",'"
                . mysql_real_escape_string($v['allow']) . "','"
                . mysql_real_escape_string($v['action']) . "',"
                . $v['level'];
        }
        $sql_rights = "INSERT INTO {{prefix}}_right_self(`ID_ARO`, `ID_AXO`,`allow`,`action`,`level`) VALUES
    ("
            . implode('),
    (', $sql_rights) . ");\n";

        //($sql_rights, $rights);
        $support_sql = " OPTIMIZE TABLE  {{prefix}}_right_self;\nOPTIMIZE TABLE  {{prefix}}_right_users;\n";

//        return ;
        /**
         * recreate 2 tables
         */
        ENGINE::db()->sql_dump($sql_rights . $sql_users . $support_sql);

        return '';
    }

    /**
     * функция для выполнения списка SQL запросов. Пустой список -
     * выполняется создание базы.
     * @static
     * @param string $sql - sql для выполнения. Если пусто - выполняется "создание базы"
     * @return string
     */
    static function create($sql = '')
    {
        /*  $prefix = ENGINE::option('database.prefix');
      if (empty($sql)) $sql = self::$CREATE_TREE_SQL . self::$CREATE_SELF_SQL;
      self::$DATABASE->regcns('ENGINE', 'MyISAM');
      self::$DATABASE->regcns('CODE', 'UTF8');
      self::$DATABASE->regcns('TABLE_SELF', '`' . $prefix . self::TABLE_SELF . '`');
      self::$DATABASE->regcns('TABLE_TREE', '`' . $prefix . self::TABLE_TREE . '`');
      self::$DATABASE->regcns('TABLE_USERS', '`' . $prefix . self::TABLE_USERS . '`');
      self::$DATABASE->sql_dump($sql);
      return ''; */
    }

    function create_first()
    {
        $this->create();
        $root = $this->get_root('aro_group');
        $root_axo = $this->get_root('axo_group');
        // создаем группы юзеров  - Admin, Developer, Users, Unauthorised
        $user_groups = array();
        foreach (array('Admins', 'Developers', 'Users', 'Unauthorised') as $v) {
            $user_groups[$v] = $this->update($v, $root_axo['id'], 'axo_group');
        }
        $object_groups = array();
        foreach (array(RIGHT::siteAdmin, RIGHT::site) as $v) {
            $object_groups[$v] = $this->update($v, $root['id'], 'aro_class');
        }
        // добавляем юзера admin
        $this->update('admin', $user_groups['Admins'], 'axo');

        // расставляем права
        // админу позволено читать админку
        $this->update('', $user_groups['Admins'], 'right', sprintf('%d|1|*', $object_groups[RIGHT::siteAdmin]));
        // админу позволено читать сайт
        $this->update('', $user_groups['Admins'], 'right', sprintf('%d|1|*', $object_groups[RIGHT::site]));
        $this->update('', $user_groups['Users'], 'right', sprintf('%d|1|*', $object_groups[RIGHT::site]));
        $this->update('', $user_groups['Developers'], 'right', sprintf('%d|1|*', $object_groups[RIGHT::siteAdmin]));
        $this->update('', $user_groups['Developers'], 'right', sprintf('%d|1|*', $object_groups[RIGHT::site]));
        $this->update('', $user_groups['Unauthorised'], 'right', sprintf('%d|1|read', $object_groups[RIGHT::site]));

        $this->compile($this->convert($this->get(0)));
    }

}