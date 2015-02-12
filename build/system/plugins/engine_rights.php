<?php
/**
 * система прав
 *
 * Действия бывают разные
 *
 * Субьекты права бывают - юзеры, группы юзеров
 *
 * объекты права бывают
 *  - обязательные группы - админ, девелопер, юзер, аноним
 *  - классы, таблицы базы, и еще что придумается.
 *  Все классы - подклассы объекта "модули"
 *  Все таблицы - подклассы объекта "база данных"
 *
 */
class ENGINE_rights
{

    const
        TABLE_USERS='_right_users',
        TABLE_SELF='_right_self',
        TABLE_TREE='_right_tree';

    /** @var xDatabase */
    static $DATABASE;

    function __construct(){
        self::$DATABASE=ENGINE::db();
    }
    /**
     * по имени юзера получить информацию о его группах
     * @param string $name - имя объекта
     * @param string $default - имя группы объектов, если никто не добавлял его в структуру прав
     * @return string
     */
    static protected function get_path($name,$default=''){
        static $cache=array();
        if(array_key_exists($name,$cache)) return $cache[$name];
        $result = self::$DATABASE->selectCell(
            'select `value` from {{prefix}}_right_users where `name`={{?}}',$name);
        if(empty($result)&&!empty($default)){
            if(isset($cache[$default])) {
                $cache[$name]=&$cache[$default];
                return $cache[$default];
            }
            $result = self::$DATABASE->selectCell(
                'select `value` from {{prefix}}_right_users where `name`={{?}}',$default);
            $cache[$default]=$result;
        }
        $cache[$name]=$result;
        return $result;
    }
    /**
     * @static
     * @param string $aro - name of ARO object
     * @param string $axo - name of AXO object
     * @param $what - action to check
     * @return boolean
     * <code>
     * if (RIGHTS::can(engine::user,'form_1234','read')) do_read();
     * </code>
     */
    static function can($aro,$axo,$what='read'){
        if(is_array($aro) && !empty($aro['rights'])) {
            $aro_parents= $aro['rights'];
        } else {
            $aro_parents=self::get_path($aro,'aro');
        }
        //$aro_parents=self::get_path($aro,'aro');
        $axo_parents=self::get_path($axo,'axo');
        if(empty($aro_parents)|| empty($axo_parents))
            return false;
        $result=self::$DATABASE->selectCell(
            'select `allow` from {{prefix}}_right_self'
                .' where '
                .'ID_ARO in('.$aro_parents.') '
                .'and ID_AXO in ('.$axo_parents.') '
                .'and (`action`={{?}} or `action`="*") '
                .'order by level DESC LIMIT 1;',$what);
        return !!$result;
    }

}
