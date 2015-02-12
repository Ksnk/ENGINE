<?php

/**
 * Плагин SiteMap
 * Хранит структуру сайта в деревянном виде и
 * определяет его наполнение (getSiteMap)
 */

class xSitemap extends engine_plugin
{

    const FLAG_HIDDEN = 1;
    /**
     * @var array - сохраненное дерево сайта
     */
    var $sitemap = null;

    /**
     * @static - выдать информацию о параметрах
     * @return array
     */
    static function get_install_info()
    {
        return array(
            'aliace' => 'Sitemap',
        );
    }

    /**
     * внутренняя функция - перечитать следующий слой меню.
     * Заполняет массив $sitemap
     * @param $id - текущая читаемая
     * @return array
     */
    protected function readSiteMap($id=0)
    {
        $tree=array(null);
        do {
            $result = ENGINE::db()->select('select * from {{prefix}}_sitemap where '
                    . (!is_array($id)
                        ? '`parent`={{?|int}}'
                        : '`parent` in ({{?|values|join(",")}})'
                    )
                , $id);
            // собираем список парентов
            $id = array();
            if (count($result) == 0) break;
            foreach ($result as &$val) {
                $tree[$val['id']]=$val;
                $x=&$tree[$val['id']];
                $x['childs']=array();
                $x['parent']=&$tree[$val['parent']];
                $x['parent']['childs'][]=&$tree[$val['id']];
                $id[]=$val['id'];
            }
        } while (true);
        $id=ENGINE::option('id');
        unset($val,$x);
        while(!empty($id) && array_key_exists($id,$tree)){
            $tree[$id]['active']=true;
            $id= $tree[$id]['parent']['id'];
        }
        return $tree;
    }

    /**
     * Чтение системы меню
     * функция-хелпер для вывода меню в шаблоне.
     *
     * @param int $id - верхний элемент меню
     * @param bool $rebuild - перечитать сайтмар из базы или нет
     * @return mixed Объект - наследник xMenuLine
     */
    function &getSiteMap($id = 0, $rebuild = false)
    {
        // TODO: проверить, что можно читать неполное дерево
        // чтение таблицы сайта и трансформация его в дерево
        if($rebuild || is_null($this->sitemap)){
            $this->sitemap= $this->readSiteMap();
        }

        return  $this->sitemap;
    }

    /**
     *
     * Хранитель navbar'а
     *
     */
    function current()
    {
        $sitemap=$this->getSiteMap();
        $id=ENGINE::option('id');
        if(!empty($id) && array_key_exists($id,$sitemap)){
            return $sitemap[$id];
        }
        return '';
    }

    /**
     *
     * Хранитель navbar'а
     *
     */
    function navbar()
    {
        $sitemap=$this->getSiteMap();
        $result=array();
        $id=ENGINE::option('id');
        while(!empty($id) && array_key_exists($id,$sitemap)){
            array_unshift($result,$sitemap[$id]);
            $id= $sitemap[$id]['parent']['id'];
        }
        //TODO: убрать затычку
        return $result;
    }
}

?>