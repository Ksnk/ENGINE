<?php

class ENGINE_router
{
    /* <% POINT::start('ENGINE_header') %>*/
    static $url_par = null;
    static $url_path = null;

    /* <% POINT::start('ENGINE_body') %>*/
    /**
     * организация массива - регулярка, имена захваченных
     * @var array
     *
     */
    static function route($rules = null)
    {

        /** @var array $rules */
        if (empty($rules)) {
            /**
             * дефолтное правило - если нет роутинга в конфиге
             * - показываем стартовую страницу
             */
            $rules = ENGINE::option(
                'router.rules',
                array('', array('class' => 'Main', 'method' => 'do_Default'))
            );
        }

        /**
         * @var string $query_string - очищенная от стартового
         * каталога строка запроса
         */
        $query_string = preg_replace(
            '#^' . ENGINE::option('page.rootsite') . '#i',
            '',
            $_SERVER['REQUEST_URI']
        );

        /** аварийное правило, если никакое правило роутинга не подойдет  */
        ENGINE::set_option(
            array('class' => 'Main', 'method' => 'do_404')
        );

        foreach ($rules as $rule) {
            if (empty($rule[0]) || preg_match($rule[0], $query_string, $m)) {
                foreach ($rule[1] as $k => $v) {
                    if (is_int($k)) {
                        if (!empty($m[$k])) {
                            if ($v == 'method') {
                                $m[$k] = 'do_' . $m[$k];
                            }
                            ENGINE::set_option($v, $m[$k]);
                        }
                    } else {
                        ENGINE::set_option($k, $v);
                    }
                }
                break;
            }
        }
    }

    /**
     * строим ссылку, по полученным параметрам
     * @param string $z - адре для перехода
     * @param string|array $act - действие
     * @param null $par - дополнительные параметры
     * @return string
     */
    static function link($z = '', $act = '', $par = null)
    {
        if (is_null(self::$url_par)) {
            self::$url_par = $_GET;
        }
        if (is_null(self::$url_path)) {
            self::$url_path = preg_replace("/\?.*$/", "", $_SERVER['REQUEST_URI']);
        }
        //$uri = ENGINE::option('page.rootsite');
        $z = str_replace('\\', '/', $z);
        $host = 'http://'
            . $_SERVER["SERVER_NAME"]
            . (80 == $_SERVER["SERVER_PORT"] ? '' : ':' . $_SERVER["SERVER_PORT"]);
        if (!is_array($act)) {
            $act = array(array($act, $par));
        }
        foreach ($act as $x) {
            $action = $x[0];
            $param = $x[1];
            switch ($action) {
                case '+':
                    if (empty($param))
                        self::$url_par = array();
                    self::$url_par = array_merge(self::$url_par, $param);
                    break;
                case '-':
                    if (empty($param)) {
                        self::$url_par = array();
                    } else {
                        self::$url_par = array_diff_key(self::$url_par, array_flip($param));
                    }
                    break;
                case 'file2url':
                    if(empty($par)){
                        $query='';
                    } else {
                        $query=$par;
                    }
                    if (!empty($query))
                        $query = '?' . $query;
                    $z=str_replace(str_replace('\\', '/',INDEX_DIR),'',$z);
                    return $z . $query;
                case 'root':
                    self::$url_path=ENGINE::option('page.rootsite', self::$url_path).$z;
                    break;
                case 'replace':
                    self::$url_par = $param;
                    break;
            }
        }
        $query = http_build_query(self::$url_par);
        if (!empty($query))
            $query = '?' . $query;
        return self::$url_path . $query;

    }

    /* <% POINT::finish() %>*/
}