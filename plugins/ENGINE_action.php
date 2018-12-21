<?php

class ENGINE_action
{

    /* <% POINT::start('ENGINE_body') %>*/

    static function relocate($link)
    {
        if (ENGINE::hasflag('norelock') || ENGINE::option('debug', false)) {
            echo '<a href="' . $link . '">Press link to redirect</a>';
        } else {
            header('location:' . $link);
        }
        exit;
    }

    static function  ajax_action()
    {
        ENGINE::set_option('ajax', true);
        header('Content-type: application/json; charset=UTF-8');
        if ('POST' == $_SERVER['REQUEST_METHOD']&&
            (array_key_exists('handler', $_POST) || !ENGINE::option('skip_post',false))
        ) {
            if (array_key_exists('handler', $_POST)) {
                preg_match(
                    '/^([^:]*)::([^:]+)(?::([^:]+))?(?::([^:]+))?(?::([^:]+))?$/',
                    str_replace('%3A',':',$_POST['handler']), $m
                );
                if (empty($m[1])) {
                    $m[1] = 'Main';
                }
                if (empty($m[2])) {
                    ENGINE::error('Wrong handler.');
                }
                for ($i = 3; $i < 6; $i++) {
                    if (!array_key_exists($i, $m)) {
                        $m[$i] = '';
                    }
                }
                $act = array($m[1], 'do_' . $m[2]);
                $data = ENGINE::exec($act, array($m[3], $m[4], $m[5]));
            } else {
                //ENGINE::error('Wrong usage of POST method.');
                $data = self::getData();
            }
        } else {
            /* <%=POINT::get('BEFORE_GETDATA') %>*/
            $data = self::getData();
        }

        $result = array('data' => $data);
        $x = ob_get_contents();
        $x .= trim(ENGINE::option('page.debug'));
        if (!empty($x)) {
            $result['debug'] = utf8_encode($x);
        }
        ob_end_clean();
        self::_finish_ajax($result);

    }

    static function _finish_ajax($result=array()){
        $data = ENGINE::slice_option('ajax.');
        if (!empty($data)) {
            $result = array_merge($result, $data);
        }
        $error = ENGINE::option('page.error');
        if (!empty($error))
            $result['error'] = utf8_encode($error);
        $x = ob_get_contents();
        $x .= trim(ENGINE::option('page.debug'));
        if (!empty($x)) {
            $result['debug'] = utf8_encode($x);
        }
        ob_end_clean();
        if (session_id() != "") {
            $result['session'] = array('name' => session_name(), 'value' => session_id());
        }

        // $q=ENGINE::db()->exec('Database',get_request_count
        ob_start();
        ENGINE::_report();
        $result['stat'] = ob_get_contents();
        ob_end_clean();
        ENGINE::set_option('noreport',1);
        //echo json_encode_cyr($result);
        if(isset($_POST['ajax']) && $_POST['ajax']=='iframe'){
            echo '<script type="text/javascript"> top.ajax_handle('.utf8_encode(json_encode($result)).'</script>';
        } else
            echo utf8_encode(json_encode($result));
    }

    static function getData()
    {
        $x = array(ENGINE::option('class', 'Main'), ENGINE::option('method', 'do_Default'));
        //if(class_exists())
        if(!ENGINE::getObj($x[0])){
            $x=array('Main','do_404');
        }
        return ENGINE::exec($x);
    }

    static function headers(){
        static $headers=array();
        if (empty($headers)) {
            if (is_callable('apache_request_headers')) {
                $headers = apache_request_headers();
            } else {
                $headers=array();
                foreach($_SERVER as $key=>$value) {
                    if (substr($key,0,5)=="HTTP_") {
                        $key=str_replace(" ","-",ucwords(strtolower(str_replace("_"," ",substr($key,5)))));
                        $headers[$key]=$value;
                    }else{
                        $headers[$key]=$value;
                    }
                }
            }
        }
        return $headers;
    }

    static function action()
    {
        try {
        ob_start();
        $error = ENGINE::option('session.page.error');
        if (!empty($error)) {
            ENGINE::set_option('session.page.error', '');
            ENGINE::error($error);
        }
        $headers=ENGINE::headers();

        if ((isset($headers['X-Requested-With']) && $headers['X-Requested-With']=='XMLHttpRequest')
            || isset($_GET['ajax'])
            || (isset($_POST) && isset($_POST['ajax']))) {
            self::ajax_action();
            return;
        }
        header('Content-Type:text/html; charset=' . ENGINE::option('page.code', 'UTF-8'));
        header('X-UA-Compatible: IE=edge,chrome=1');

        if(isset($_SESSION['SAVE_POST'])){
            if('POST' != $_SERVER['REQUEST_METHOD']) {
                $_SERVER['REQUEST_METHOD']='POST';
                $_POST=$_SESSION['SAVE_POST'];
                $_FILES=$_SESSION['SAVE_FILES'];
            }
            unset($_SESSION['SAVE_POST'],$_SESSION['SAVE_FILES']);
        }

        if ('POST' == self::_($_SERVER['REQUEST_METHOD']) &&
                (array_key_exists('handler', $_POST) || ENGINE::option('skip_post',false))
        ) {
            if (array_key_exists('handler', $_POST)) {
                preg_match('/^([^:]*)::([^:]+)(?::([^:]+))?(?::([^:]+))?(?::([^:]+))?$/'
                    , $_POST['handler'], $m);
                if (empty($m[1])) $m[1] = 'Main';
                if (empty($m[2])) ENGINE::error('Wrong handler.');
                for ($i = 3; $i < 6; $i++)
                    if (!array_key_exists($i, $m)) $m[$i] = '';
                $act = array($m[1], 'do_' . $m[2]);
                ENGINE::exec($act, array($m[3], $m[4], $m[5]));
            } else
                ENGINE::error('Wrong usage of POST method.');
            $error = ENGINE::option('page.error');
            if (!empty($error)) {
                ENGINE::set_option('session.page.error', $error);
            }
            ENGINE::relocate(ENGINE::link());
        }
        /* <%=POINT::get('BEFORE_GETDATA') %>*/
        $data = self::getData();

        $x = ENGINE::template(
            ENGINE::option('page_tpl', 'tpl_main')
            , ENGINE::option('page_macro', '_')
            , array_merge(array('data' => $data), ENGINE::slice_option('page.'))
        );
        if (!trim($x)) {
            ENGINE::error($x = ENGINE::_t('template `{{tpl}}::{{macro}}` not defined',
                array('{{tpl}}' => ENGINE::option('page_tpl', 'tpl_main'),
                    '{{macro}}' => ENGINE::option('page_macro', '_'))));
            $x = '<html><head><title>Oops</title></head><body>' . $x . '</body></html>';
        }
        echo $x;
        } catch (Exception $e) {
            ENGINE::error($x = ENGINE::_t('Exception pending `{{msg}}`',
                array('{{msg}}' => $e->getMessage())));
            $x = '<html><head><title>Oops</title></head><body>' . $x . '</body></html>';
        }

    }
    /* <% POINT::finish() %>*/
}