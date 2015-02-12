�� �������� ������������� � ��������� "���������" ����������� ���������� �������� ���������� �� Yii. ��� ���������� �� 5.2, � �������� �� ���� __callStatic, ��� ��� �� ����� ������ ��������� ������, �� �� ���� - �� �� �����.

����� - ����� ���������� ��� ����������. ��� �������� � �������� �� Yii, �� "������ �������" ;)

��������� ������������. ��� ��������� ������������ �������� �� ����� � ������� ������� ENGINE:: option($name,$default=''). ��������������� ENGINE::set_option($name,$value, $options=null). ��������� ��������� �������� � ������ ������, � ���������, �  ����� ��� ���������� php-��������. �������� ������ ����
[PHP]<?php
/**
 * �������� ���� ������������ �������.
 * ��� ������ ������� ������������ ������������� ����� ������������ ��������������� �������
 */
return array(
    /**
     * database options
     */
    'database.host' => 'localhost',
    'database.user' => 'root',
    'database.password' => '',
    'database.base' => 'cms',
    'database.prefix' => 'tmp',

    /**
     * ��������������� �������� �������� �������.
     */
    'engine.aliaces' => array(
        'Main' => 'xAdmin',
        'User' => 'xUser',
        'Sitemap' => 'xSitemap',
        'Database' => 'xDatabaseWithMemcache',
        'Install' => 'installManager',
        'Rights' => 'xRights',
    ),

    /**
     * ������ ������������� ������� �����������
     */
    'engine.interfaces' => array(
        'user_find' => 'User::user_find',
        'link' => array('ENGINE_router', 'link'),
        'log' => 'ENGINE_logger::log',
        'template' => array('Main', 'template'),
        'db' => array('Database', 'getInstance'),
        'run' => array('Main', 'run'),
        'action' => array('ENGINE_action', 'action'),
        'has_rights' => array('Rights', 'has_rights'),
    ),
...
[/PHP]
�������� ��� ����� �� ����� ��������, �� ��������������� ��� ���������� ���������� � �������� ��������� �������,  ��� ����  ����������� ��������, �� ������������� ������� ��� ��� ����� �����.

������� ENGINE::getObj($sim_name) - "���������" �����, ������� �� �������������� ����� ����������, ���� ��� ��� �� ���� � ������ ������ ��� ������� ������-������. ������ - ������� ���������� ������������� ����, � ������������� � ������� �����������. ��� ���������, ������ ��� �����, ������� �������� � ������� ENGINE:: option.
������������ ������������� ����  �������� ������ ������� �������� � ������ 'engine.aliaces'

������� ENGINE::exec($callable,$arguments). ���� is_callable($callable) �� ������ �������� ��� � ������� call_user_func_array. ���� $callable - ������, �� 0-� ������� ������� ���������� �� ENGINE::getObj($callable[0]). ���� � ���������� ��������� is_callable - ��������, ����� �������.
[PHP]/**
     * ������� �����-�����.
     * ���� ����� �� ������� - �������� ������ �� �������.
     *
     * @param callable $func
     * @param null|array $args
     * @param string $error_rule
     * @return mixed
     */
    static function exec(&$func, $args = array(), $error_rule = '')
    {
        if(empty($func)){
            ENGINE::error('callable not defined yet','',$error_rule);
            return null;
        }
        if (is_array($func) && is_string($func[0])) {
            $func[0] = self::getObj($func[0]);
        }
        if (is_callable($func)) 
            return call_user_func_array($func, &$args);
        if (is_array($func))
                ENGINE::error('unresolved callable {{class}}->{{method}}', array('{{class}}' => $func[0], '{{method}}' => $func[1]), $error_rule);
        else
            ENGINE::error('unresolved callable {{function}}', array('{{class}}' => $func), $error_rule);
        return null;
    }
[/PHP]    
������ 'engine.interfaces' ��������� ���������� ���������� ����� ���������� �������. ��� ��������, ����������, ���� ���������� � ��������� ������ self::$interface

�� � ��� ����� ���������� �����
[PHP]static public function __callStatic($method, $args)
    {
        return self::exec(self::$interface[$method], $args);
    }
[/PHP]

� �����, �������� ������ ��� ENGINE::db() �� �������
-- ������� 'Database'=>'xDatabase' ������� self::$aliases ��������� �� 'Database'=>new xDatabaseWithMemcache; �������� �����������, ���� ����������������.
-- ������� 'db'=>array('Database', 'getInstance'), ������� self::$interface ��������� �� array(&self::$aliaces['Database'],'getInstance')
-- � ����� ����� ������ ����� &self::$aliaces['Database']->getInstance � ��� ��������� ����� ���������.

� ��������� ��� �� ����� ������, ��� ������ � ������� self::$interface ������ ���� is_callable � ����� ������� ������ �����.

� ���� ������ ������� getInstance �� Database ������ ������ ��� ��������� $this.