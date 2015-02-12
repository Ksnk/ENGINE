<?php

trait Singleton
{
    public static function getInstance()
    {
        static $instance;

        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    private function __construct()
    {}

    private function __clone()
    {}

    private function __wakeup()
    {}
}

class xSingleton use Singleton {
    var $x=5;
    function xxx(){
        return $x++;
    }
}


$x=new xSingleton(); echo ' '.$x->xx();