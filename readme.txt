За примером использования и тонкостей "скелетной" организации приложения разумнее посмотреть на Yii. Они стартовали на 5.2, у которого не было __callStatic, так что по форме вызовы несколько другие, но по сути - то же самое.

Далее - очень схематично моя реализация. Она писалась с оглядкой на Yii, но "своими словами" ;)

Системная конфигурация. Все параметры конфигурации доступны по имени с помощью функции ENGINE:: option($name,$default=''). Устанавливаются ENGINE::set_option($name,$value, $options=null). Системные параметры хранятся в разных местах, в частности, в  одном или нескольких php-конфигах. Примерно такого вида
[PHP]<?php
/**
 * основной файл конфигурации системы.
 * При вызове админки производится доопределение файла конфигурации дополнительными опциями
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
     * переопределение ключевых объектов системы.
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
     * список потенциальных внешних интерфейсов
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
Реальный вид файла не такой красивый, он пересохраняется при изменениии параметров в процессе настройки системы,  при этом  комментарии теряются, но редактировать вручную его все равно можно.

функция ENGINE::getObj($sim_name) - "фабричный" метод, который по символическому имени инициирует, если его еще не было и выдает наружу уже готовый объект-плагин. Плагин - объекты достаточно произвольного вида, с конструктором с пустыми параметрами. Все параметры, нужные для жизни, объекты забирают с помощью ENGINE:: option.
Соответствие символических имен  реальным именам классов задается в секции 'engine.aliaces'

функция ENGINE::exec($callable,$arguments). Если is_callable($callable) то просто вызываем его с помощью call_user_func_array. Если $callable - массив, то 0-й элемент массива заменяется на ENGINE::getObj($callable[0]). Если в результате получился is_callable - вызываем, иначе ругаеся.
[PHP]/**
     * вызвать класс-метод.
     * Если класс из массива - вызывать объект из фабрики.
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
секция 'engine.interfaces' системных параметров определяет набор вызываемых функций. Все элементы, фактически, тупо копируются в приватный массив self::$interface

Ну а сам вызов совершенно прост
[PHP]static public function __callStatic($method, $args)
    {
        return self::exec(self::$interface[$method], $args);
    }
[/PHP]

В итоге, встретив первый раз ENGINE::db() мы получим
-- строчка 'Database'=>'xDatabase' массива self::$aliases заменится на 'Database'=>new xDatabaseWithMemcache; Стартует конструктор, база инициализируется.
-- строчка 'db'=>array('Database', 'getInstance'), массива self::$interface заменится на array(&self::$aliaces['Database'],'getInstance')
-- в итоге будет вызван метод &self::$aliaces['Database']->getInstance и его результат будет возвращен.

В следующий раз мы сразу увидим, что запись в таблице self::$interface вполне себе is_callable и сразу вызовем нужный метод.

В моей версии функция getInstance от Database просто служит для получения $this.