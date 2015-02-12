<?php

class engine_php52 extends ENGINE
{
    /* <% POINT::start('ENGINE_header') %>*/

    public static $I;

    /* <% POINT::start('ENGINE_body') %>*/
    /**
     * Just a little magic (2) - копия предыдущей магии
     * Простой механизм для монтажа расширений
     *
     * @param $method
     * @param $args
     * @return mixed
     */

    public function __call($method, $args)
    {
        if (array_key_exists($method, self::$interface)) {
            return self::exec(self::$interface[$method], $args);
        } else {
            return self::error('ENGINE: Interface `{{interface}}` not defined', array('{{interface}}' => $method));
        }
    }

    static function II()
    {
        return self::$I;
    }

    /* <% POINT::finish() %>*/
}

/* <% POINT::start('ENGINE_bottom') %>*/
ENGINE::$I = new ENGINE();
/* <% POINT::finish() %>*/