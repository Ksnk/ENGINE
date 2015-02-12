<?php
/**
 * базовый класс админки PHP
 */
class xAdmin extends xWebApplication
{

    static function get_install_info()
    {
        $result = parent::get_install_info();
        array_merge_deep($result, array(
            'engine.event_handler' => array(
                'BEFORE_GETDATA' =>
                array('checkIsUserAdmin' => array('Main', 'checkIsUserAdmin'))
            )
        ));
        return $result;
    }

    function __call($method, $param)
    {
        $class = str_replace('do_', '', $method);
        switch (ENGINE::exec(array('Install', 'class_status'), array($class))) {
            case 'on':
                return ENGINE::exec(array($class, 'admin'), $param);
            case 'off':
                return ENGINE::exec(array('Install', 'install'), array($class));
            default:
                return 'Неведома зверушка, sorry!';
        }
    }

    function relogin()
    {
        $x = ENGINE::template('tpl_logo_template', '_', ENGINE::slice_option('page.'));
        echo $x;
        exit();
    }

    function do_users()
    {
        $param = ENGINE::exec(array('User', 'getList'));
        return ENGINE::template('tpl_admin', '_users', array('data' => $param));
    }

    /**
     * вывод формы редактирования свойств ENGINE
     * @return bool
     */
    function do_param()
    {
        // параметры сайта, по версии Сергея
        $param = array(
            'Параметры фотографий' => array(
                'type' => 'title'
            ),
            'Размер мал. фото (в пикселях)' => array(
                'title' => 'Подсказко - маленькое фото',
                'type' => 'XxX',
                'data' => array('param.picture.little.x', 'param.picture.little.y')
            ),
            'Размер бол. фото (в пикселях)' => array(
                'title' => 'Подсказко - большое фото',
                'type' => 'XxX',
                'data' => array('param.picture.big.x', 'param..picture.big.y')
            ),
            '',
            'Рамка фотографии (в пикселях)' => array(
                'title' => 'Подсказко - рамка фото',
                'type' => 'ramka',
                'data' => array('param.picture.border.x', 'param..picture.border.y', 'param..picture.border.color', 'param..picture.has_border')
            ),
            'Отступ фото от текста (в пикселях)' => array(
                'title' => 'Подсказко - отступ фото',
                'type' => 'offset',
                'data' => array('param.picture.offset.top', 'param.picture.offset.bottom', 'param.picture.offset.right', 'param.offset.left')
            ),
            'Расстояние между фото (в пикселях)' => array(
                'title' => 'Подсказко - расстояние фото',
                'type' => 'disp',
                'data' => array('param.picture.disp.x', 'param.picture.disp.y', 'param.picture.has_name')
            ),
            'Параметры анимации' => array(
                'title' => 'Подсказко - анимация',
                'type' => 'animation',
                'data' => array('param.picture.animation.time', 'param.picture.animation.type')
            ),
            'Параметры текста' => array(
                'type' => 'title'
            ),
            'Шрифт' => array(
                'title' => 'Подсказко - шрифт',
                'type' => 'font1',
                'data' => array('param.text.font.type', 'param.text.font.size', 'param.text.font.line')
            ),
            'Стиль шрифта' => array(
                'title' => 'Подсказко - шрифт 2',
                'type' => 'font2',
                'data' => array('param.text.font.color', 'param.text.font.bold', 'param.text.font.cursive', 'param.text.font.underline')
            )
        );
        ENGINE::set_option(array('ajax.title' => 'Параметры сайта'));
        return ENGINE::template('tpl_admin', '_param', array('data' => $param));
    }

    /**
     * обрабочик события BEFORE_GETDATA. Проверка, что юзер залогинен и имееи права
     */
    function checkIsUserAdmin()
    {
        if (!ENGINE::has_rights(RIGHT::siteAdmin)) {
            ENGINE::set_option(array(
                'class' => 'Main',
                'method' => 'relogin'
            ));
        }
    }

    /**
     * вывести список модулей для отображения в админке
     * item
     *  :active - текущий отображаемый модуль
     *  :url - ссылка
     *  :name - выводится название в ссылке
     *  :childs  - если есть наследники - выводим и их
     */
    function modulelist()
    {
        /*
            $modules=ENGINE::option('engine.modules',array());
            $result=array();
            if(count($modules)>0)
            foreach($modules as $module){

            }
        */
        return array(
            'fileman' => array(
                'name' => 'Загруженные файлы',
                'url' => 'fileman',
            ),
            'users' => array(
                'name' => 'Пользователи',
                'url' => 'users',
            )
        );
    }

    /**
     * получаем часть url без приставки root. для вставки в базу данных и работы с ajax
     * @param $path
     * @return mixed
     */
    function path2url($path){
        // отрезаем
        return str_replace(
            str_replace(rtrim(ENGINE::option('page.rootsite'),'/')
                ,'',$this->xslash(INDEX_DIR))
                .ENGINE::option('page.root')
            ,'',$this->xslash(realpath($path)));
    }

    /*<%=POINT::get('admin_extension'); %>*/
}