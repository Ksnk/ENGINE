<?php
/**
 * Класс - хранитель содержимого страницы
 */

class xPage extends engine_plugin {

    static function get_install_info()
    {
        return array(
            'aliace' => 'Page',
        );
    }

    // вывести содержимое страницы
    function show(){

        return 'xxx';
    }

}