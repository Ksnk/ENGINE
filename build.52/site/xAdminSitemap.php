<?php
/**
 * Sitemap для админки  изменение дерева сайта
 */

class xAdminSitemap extends xSitemap
{

    static $fields = array(
        '{{prefix}}_sitemap' => array(
            'id' => array('type' => 'int', 'PRI' => true, 'AUTO' => true),
            'parent' => array('type' => 'int', 'IND' => true),
            'page' => array('type' => 'int', 'IND' => true),
            'name' => array('type' => 'str'),
            'url' => array('type' => 'str', 'IND' => true),
            'alt_url' => array('type' => 'str'),
            'order' => array('type' => 'str'),
            'title' => array('type' => 'str'),
            'descr' => array('type' => 'str'),
            'keywords' => array('type' => 'str'),
            'flags' => array('type' => 'int'),
        ));

    function install($install = true)
    {
        $sql = '';
        foreach (self::$fields as $table => $fields) {
            if ($install) {
                $sql .= SQL_BUILD::create($table, $fields);
                ENGINE::db()->sql_dump($sql);
                $sql .= SQL_BUILD::alter($table, $fields);
            } else {
                $sql .= SQL_BUILD::drop($table);
            }
            echo $sql;
            ENGINE::db()->sql_dump($sql);
        }
    }

    /**
     * вывод формы редактирования свойств ENGINE
     * @return bool
     */
    function do_param()
    {
        $sitemap = $this->getSiteMap();

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            if (!isset($_POST['id'])) return 'fail';
            $current = $sitemap[ppi($_POST['id'], 2)];
            $result = array();
            foreach (self::$fields['{{prefix}}_sitemap'] as $k => $field) {
                if (isset($_POST[$k])) {
                    $result[$k] = $_POST[$k];
                }
            }
            unset($result['id']);
            ENGINE::db()->update('update {{prefix}}_sitemap set {{?|pair}} where `id`={{?|int}}',
                $result, $current['id']);
            return 'Ok';
        } else {
            $current = $sitemap[ppi($_GET['id'], 2)];
            // параметры сайта, по версии Сергея
            $current['handler'] = 'Sitemap::param';
            $param = array(
                'ID раздела' => array(
                    'type' => 'hidden',
                    'data' => 'id'
                ),
                'handler формы' => array(
                    'type' => 'hidden',
                    'data' => 'handler',
                ),
                'Раздел виден только зарегистрированным посетителям' => array(
                    'type' => 'checkbox',
                    'data' => array('flags', 1)
                ),
                'Показывать раздел в меню' => array(
                    'type' => 'checkbox',
                    'data' => array('flags', 3)
                ),
                'Название раздела в адресной строке' => array(
                    'title' => 'Эта страница будет доступна по этому адресу',
                    'type' => 'str',
                    'data' => 'alt_url'
                ),
                'Адрес перехода' => array(
                    'title' => 'Внешний адрес или зеркало страницы',
                    'type' => 'link',
                    'data' => 'url'
                ),
                'Ключевые слова для продвижения сайта' => array(
                    'type' => 'title'
                ),
                'Title' => array(
                    'title' => 'Подсказко - большое фото',
                    'type' => 'str',
                    'data' => 'title'
                ),
                'Description' => array(
                    'title' => 'Подсказко - рамка фото',
                    'type' => 'str',
                    'data' => 'descr'
                ),
                'Keywords' => array(
                    'title' => 'Подсказко - шрифт 2',
                    'type' => 'str',
                    'data' => 'keywords'
                )
            );
            ENGINE::set_option(array('ajax.title' => 'Параметры раздела', 'ajax.title1' =>
            ENGINE::template('tpl_admin', '_pageparam', array('data' => array(
                    'Название' => array(
                        'type' => 'header',
                        'data' => 'name'
                    )), 'values' => $current)
            )));
            // echo 'xxx '.ENGINE::option('id'); print_r($current);
            return ENGINE::template('tpl_admin', '_pageparam'
                , array('data' => $param, 'values' => $current)
            );
        }
    }

    function do_add()
    {
        $id = +$_POST['data'];
        if ($id && isset($_POST['name'])) {
            $this->appendNode($id, $_POST['name']);
        } else {
            return 'Параметры кривы';
        }
        return 'append node. ok';
    }

    function do_delete()
    {
        $id = +$_POST['data'];
        if ($id > 1) {
            $this->deleteNode($id);
        } else {
            return 'Параметры кривы';
        }
        return 'delete node. ok';
    }

    function appendNode($parent, $name, $title = '')
    {
        return ENGINE::db()->insert(
            'INSERT INTO {{prefix}}_sitemap ( `parent`, `name`, `title`,`flags`)'
                . ' VALUES ({{?|int}}, {{?}}, {{?}},1);', $parent, $name, $title
        );
    }

    function deleteNode($id)
    {
        return ENGINE::db()->delete(
            'DELETE from {{prefix}}_sitemap where `id`={{?|int}};', $id
        );
    }
}