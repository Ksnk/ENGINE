<?php

class ENGINE_router
{

    /**
     * строим ссылку, по полученным параметрам
     * @param string $z
     * @return string
     */
    function link($z = '')
    {
        $uri=ENGINE::option('page.rootsite');
        $z = str_replace('\\', '/', $z);
        return 'http://' . $_SERVER["SERVER_NAME"] . $uri
            . preg_replace('~^(.\:|/usr)?' . preg_quote($uri, '~:') . '|index.php(\?|\b)~is', '', $z);
    }

    /**
     * организация массива - регулярка, имена захваченных
     * @var array
     */
    function route($rules)
    {
        $query_string = preg_replace('#^.*' . preg_quote(ENGINE::option('page.rootsite')) . '#i', '', $_SERVER['REQUEST_URI']);
        foreach ($rules as $rule) if(!empty($rule)){
            if (preg_match($rule[0], $query_string, $m)) {
               // if(!isset($rule[1])) ENGINE::debug($rule);
                foreach ($rule[1] as $k => $v) {
                    if (is_int($k)) {
                        if (!empty($m[$k])) {
                            if($v=='method') $m[$k]='do_'.$m[$k];
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
}