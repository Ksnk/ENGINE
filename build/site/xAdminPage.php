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
            $element=xElement::get($v['type'], $v['typeid']);
            $v['data'] = $element->data();
        }
        return ENGINE::template('tpl_admin', '_page', array('data' => $page));
    }

    /**
     * добавить-сменить атрибут у записи
     * Добавить фото, сменить текст
     */
    function do_attr()
    {
        if(!($element=xElement::get($_POST['type'],$_POST['eid']))) {
            ENGINE::error('incorrect TYPE argument');
            return '';
        }
        if($element->attr($_POST))
            return 'attr ok' ;
        else
            return 'attr fault' ;
    }

    /**
     * удалить инфоблок из таблиц
     * @return string
     */
    function do_delete()
    {
        if(!($element=xElement::get($_POST['type'],$_POST['eid']))) {
            ENGINE::error('incorrect TYPE argument');
            return '';
        }
        if($element->delete())
            return 'delete ok' ;
        else
            return 'delete fault' ;
    }

    /**
     * добавить новый инфоблок на страницу в конец списка
     * клик по кнопке без извращений
     * @return string
     */
    function do_append()
    {
        if(!($element=xElement::get($_POST['type']))) {
            ENGINE::error('incorrect TYPE argument');
            return '';
        }

        $sitemap = ENGINE::exec(array('Sitemap', 'getSiteMap'));
        $id = 0+$_POST['id'];
        $current = $sitemap[$id];

        $text_id = $element->getnew();

        $data = array('typeid' => $text_id, 'type' => $element->type, 'order' => 1);

        if (empty($current['page'])) {
            $page_id = ENGINE::db()->insert('insert into {{prefix}}_pages ({{?|format("`%s`",",")}}) values ({{?1|values|join(",")}})', $data);
            ENGINE::db()->update('update {{prefix}}_sitemap set `page`={{?|int}} where `id`={{?|int}}', $page_id, $current['id']);
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

    /**
     * добавить элеммент в инфоблок
     * @return string
     */
    function do_insertAttr()
    {
        if(!($element=xElement::get($_POST['type'],$_POST['eid']))) {
            ENGINE::error('incorrect TYPE argument');
            return '';
        }
        if(method_exists($element,$method='insert_'.$_POST['attr']))
            $element->$method($_POST);
        return '';
    }
}

class xElement {

    static $cache=array();

    var $id,$type,$data=null;

    function __construct($id,$type){
        $this->id=$id;
        $this->type=$type;
    }

    /**
     * статический генератор элемета нужного типа с нужным ID
     * @static
     * @param $type
     * @param $id
     * @return xElement
     */
    static function get($type,$id=0){
        $tp= 'x'.ucfirst($type);
        if(class_exists($tp)){
            if(!isset(self::$cache[$tp][$id])) {
                self::$cache[$tp][$id]=new $tp($id);
            }
            return self::$cache[$tp][$id];
        } else
            return false;
    }


    function getnew(){
        $this->id=ENGINE::db()->insert('insert into {{prefix}}_pages_' . $this->type . ' set text=""');
        return $this->id;
    }

    function data(){
        if(is_null($this->data)){
            $this->data= ENGINE::db()->selectRow('select * from {{prefix}}_pages_' . $this->type
                . ' where `typeid`={{?|int}};', $this->id);
            if(!empty($this->data['_'])) {
                $this->data=array_merge($this->data,unserialize($this->data['_']));
            };
        }
        return $this->data;
    }

    function delete(){
        $type = $this->type;
        $eid = $this->id;
        ENGINE::db()->delete('delete from {{prefix}}_pages_' . $type . ' where `ID`={{?|int}}',$eid);
        ENGINE::db()->delete('delete from {{prefix}}_pages where `type`={{?}} and  `typeid`={{?|int}}',$type,$eid);
        return 'delete ok';
    }

    function attr ($arr){
        return false ;
    }
}

class xText extends xElement {

    var $fields=array(
        '{{prefix}}_pages_text' => array(
            'ID' => array('type' => 'int', 'PRI' => true, 'AUTO' => true),
            'text' => array('type' => 'text'),
            'foto' => array('type' => 'int'),
            '_' => array('type' => 'text'),
        ));

    function __construct($id){
        parent::__construct($id,'text');
    }

    function attr($post){
            ENGINE::db()->update('update {{prefix}}_pages_' . $this->type . ' set text={{?}} where `ID`={{?|int}}', $post['text'],$this->id);
            return true;
    }

    function insert_picture($arr){
        $data=$this->data();
        if(empty($data['foto'])){
            $gallery= new xGallery();
            $data['foto']=$gallery->getnew();
        }
        $picture= new xPicture();
        $picture->getnew();
        $picture->fillFrom($arr['url']);
    }
}
class xPicture extends xElement {

}
class xFoto extends xElement {
    function __construct($id){
        parent::__construct($id,'foto');
    }
}