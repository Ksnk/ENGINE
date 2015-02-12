<?php
/**
 * Админка для плагина Page
 */

class xAdminPage extends xPage
{

    const
        TYPE_TEXT = 'text',
        TYPE_FOTO = 'foto';

    static $fields = array(
        '{{prefix}}_pages' => array(
            'id' => array('type' => 'int', 'IND' => true, 'AUTO' => true),
            'order' => array('type' => 'int'),
            'type' => array('type' => 'str10'),
            'typeid' => array('type' => 'int'),
            'name' => array('type' => 'str80')
        ),
        '{{prefix}}_pages_text' => array(
            'typeid' => array('type' => 'int', 'PRI' => true, 'AUTO' => true),
            'text' => array('type' => 'text'),
            'foto' => array('type' => 'int')
        ),
        '{{prefix}}_pages_foto' => array(
            'id' => array('type' => 'int', 'PRI' => true, 'AUTO' => true),
            'typeid' => array('type' => 'int', 'IND' => true),
            'small' => array('type' => 'str'),
            'swidth' => array('type' => 'int'),
            'sheight' => array('type' => 'int'),
            'big' => array('type' => 'str'),
            'bwidth' => array('type' => 'int'),
            'bheight' => array('type' => 'int'),
            'name' => array('type' => 'str'),
            'descr' => array('type' => 'text'),
            'order' => array('type' => 'int'),
        ),
    );


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

    function getElement($type, $id)
    {
        return ENGINE::db()->select('select * from {{prefix}}_pages_' . $type
            . ' where `typeid`={{?|int}};', $id);
    }

    function show()
    {
        $current = ENGINE::exec(array('Sitemap', 'current'));
        ENGINE::set_option('page.pageid', $current['id']);
        $page = ENGINE::db()->select(
            'select * from {{prefix}}_pages where `id`={{?|int}} order by `order`;'
            , $current['page']);
        foreach ($page as &$v) {
            $v['data'] = $this->getElement($v['type'], $v['typeid']);
        }
        return ENGINE::template('tpl_admin', '_page', array('data' => $page));
    }

    /**
     * добавить-сменить атрибут у записи
     * Добавить фото, сменить текст
     */
    function do_attr()
    {
        $type = $_POST['type'];
        $eid = $_POST['eid'];
        switch ($type) {
            case 'text':
                ENGINE::db()->update('update {{prefix}}_pages_' . $type . ' set text={{?}} where `typeid`={{?|int}}', $_POST['text'],$eid);
                break;
            /*case 'foto':
                ENGINE::db()->update('update {{prefix}}_pages_' . $type . ' set text={{?}}', $data);
                break;*/
            default :
                return "fail";
        }
        return 'ok';
    }

    /**
     * удалить инфоблок из таблиц
     * @return string
     */
    function do_delete()
    {
        $sitemap = ENGINE::exec(array('Sitemap', 'getSiteMap'));
        $id = $_POST['id'];
        $type = $_POST['type'];
        $eid = $_POST['eid'];
        if (!in_array($type, array(self::TYPE_TEXT, self::TYPE_FOTO))) {
            ENGINE::error('incorrect TYPE argument');
            return '';
        }
        $current = $sitemap[$id];
        ENGINE::db()->delete('delete from {{prefix}}_pages_' . $type . ' where `typeid`={{?|int}}',$eid);
        ENGINE::db()->delete('delete from {{prefix}}_pages where `type`={{?}} and  `typeid`={{?|int}}',$type,$eid);
        return 'delete ok';
    }

    /**
     * добавить новый инфоблок на страницу в конец списка
     * клик по кнопке без извращений
     * @return string
     */
    function do_append()
    {
        $sitemap = ENGINE::exec(array('Sitemap', 'getSiteMap'));
        $id = $_POST['id'];
        $type = $_POST['type'];
        if (!in_array($type, array(self::TYPE_TEXT, self::TYPE_FOTO))) {
            ENGINE::error('incorrect TYPE argument');
            return '';
        }
        $current = $sitemap[$id];
        $text_id = ENGINE::db()->insert('insert into {{prefix}}_pages_' . $type . ' set text=""');

        $data = array('typeid' => $text_id, 'type' => $type, 'order' => 1);

        if (empty($current['page'])) {
            $page_id = ENGINE::db()->insert('insert into {{prefix}}_pages ({{?|format("`%s`",",")}}) values ({{?1|values|join(",")}})', $data);
            ENGINE::db()->update('update {{prefix}}_sitemap set `page`={{?|int}} where `id`={{?|int}}', $page_id, $current['id']);
            //        echo '111--'.$page_id;
        } else {
            $page_id = $current['page'];
            $max_order = ENGINE::db()->selectCell(
                'select max(`order`) from {{prefix}}_pages where `id`={{?|int}}'
                , $page_id);

            if (!$max_order) {
                $data['order'] = 1;
                // so no element found!
            } else {
                $data['id'] = $page_id;
                $data['order'] = $max_order + 1;
            }
            ENGINE::db()->insert('insert into {{prefix}}_pages ({{?|format("`%s`",",")}}) values ({{?1|values|join(",")}})', $data);
            //      echo '222--'.$page_id;
        }

        return 'ok';
    }

}