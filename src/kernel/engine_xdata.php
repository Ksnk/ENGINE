<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Сергей
 * Date: 08.06.13
 * Time: 13:32
 * To change this template use File | Settings | File Templates.
 */

/* <% POINT::start('ENGINE_top') %>*/

/**
 * Class xData -data-holder,
 * базовый класс для хранителя данных для шаблонов
 */
class xData implements Iterator
{

    /**
     * @var array
     */
    static $items = array();

    /**
     * система кэширования однотипных данных. Все данные различаются полем ID.
     * @static
     * @param $class
     * @param $id
     * @param array $data
     * @return mixed
     */
    static function get($class, $id, $data = array())
    {
        if (!isset(self::$items[$class]))
            self::$items[$class] = array();
        if (is_array($id)) {
            if (!isset(self::$items[$class][$id['id']]))
                self::$items[$id['id']] = new $class($id, $data);
            return self::$items[$class][$id['id']];
        } else if (!isset(self::$items[$class][$id])) {
            self::$items[$class][$id] = new $class($id, $data);
        }
        return self::$items[$class][$id];
    }

    protected $data = array();
    private $def = '';

    function getData()
    {
        return $this->data;
    }

    function __construct($data, $def = '')
    {
        foreach ($data as $k => $v) {
            if (is_array($v))
                $this->data[$k] = new self($v, $def);
            else
                $this->data[$k] = $v;
        }
        $this->def = $def;
    }

    protected function  &resolve($name)
    {
        if (!array_key_exists($name, $this->data))
            $this->data[$name] = $this->def;
        return $this->data[$name];
    }

    function &__get($name)
    {
        if (array_key_exists($name, $this->data))
            return $this->data[$name];
        else {
            $x = $this->resolve($name);
            return $x;
        }
    }

    public function __set($name, $value)
    {
        // echo "Setting '$name' to '$value'\n";
        $this->data[$name] = $value;
    }

    // итератор
    function rewind()
    {
        // $this->eoa=true;//count($this->data==0);
        reset($this->data);
    }

    function current()
    {
        return current($this->data);
    }

    function key()
    {
        return key($this->data);
    }

    function next()
    {
        return next($this->data);
    }

    function valid()
    {
        $key = key($this->data);
        return ($key !== NULL && $key !== FALSE);
    }

}
/* <% POINT::finish() %>*/
