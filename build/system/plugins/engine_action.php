<?php

class ENGINE_action {

    function  ajax_action(){
        ENGINE::set_option('ajax',true);
        ob_start();
        header('Content-type: application/json; charset=UTF-8');
        $data=array();
        if('POST'==$_SERVER['REQUEST_METHOD']){
            if(array_key_exists('handler',$_POST)){
                preg_match('/^([^:]*)::([^:]+)(?:([^:]+))?(?:([^:]+))?(?:([^:]+))?/',$_POST['handler'],$m);
                if(empty($m[1]))$m[1]='Main';
                if(empty($m[2])) ENGINE::error('Wrong handler.');
                for($i=3;$i<6;$i++)
                    if(!array_key_exists($i,$m))$m[$i]='';
                $data=ENGINE::exec(array($m[1],'do_'.$m[2]),array($m[3],$m[4],$m[5]));
            } else
                ENGINE::error('Wrong usage of POST method.');
        } else {
            ENGINE::trigger_event('BEFORE_GETDATA');
            $data=$this->getData();
        }

        $result=array('data'=>$data);
        $data=ENGINE::slice_option('ajax.');
        if(!empty($data)){
            $result=array_merge($result,$data);
        }
        $error = ENGINE::option('page.error');
        if(!empty($error))
            $result['error']=utf8_encode($error);
        $x=ob_get_contents();
        $x.=trim(ENGINE::option('page.debug'));
        if(!empty($x)){
            $result['debug']=utf8_encode($x);
        }
        ob_end_clean();
        if(session_id() != ""){
            $result['session']=array('name'=>session_name(),'value'=>session_id());
        }

       // $q=ENGINE::db()->exec('Database',get_request_count

        $result['stat']=sprintf(" %f sec spent"
            //,$this->req_cnt>>1
            ,mkt());
        echo json_encode_cyr($result);
    }

    function getData(){
        return ENGINE::exec(array(ENGINE::option('class','Main'),ENGINE::option('method','do_Default')));
    }

    function action(){
        $error=ENGINE::option('session.page.error');
        if(!empty($error)) {
            ENGINE::set_option('session.page.error','');
            ENGINE::error($error);
        }
        if(is_callable('apache_request_headers'))
            $headers=apache_request_headers ();
        else
            $headers=array();
        if(isset($headers['X-Requested-With']) || isset($_GET['ajax'])){
            $this->ajax_action();
            return;
        }
        header('Content-Type:text/html; charset=UTF-8');

        if('POST'==$_SERVER['REQUEST_METHOD']){
            if(array_key_exists('handler',$_POST)){
                preg_match('/^([^:]*)::([^:]+)(?::([^:]+))?(?::([^:]+))?(?::([^:]+))?/',$_POST['handler'],$m);
                if(empty($m[1]))$m[1]='Main';
                if(empty($m[2])) ENGINE::error('Wrong handler.');
                for($i=3;$i<6;$i++)
                    if(!array_key_exists($i,$m))$m[$i]='';
                ENGINE::exec(array($m[1],'do_'.$m[2]),array($m[3],$m[4],$m[5]));
            } else
                ENGINE::error('Wrong usage of POST method.');
            $error=ENGINE::option('page.error');
            if (!empty($error)){
                ENGINE::set_option('session.page.error',$error);
            }
            header('location:'.ENGINE::link());
            exit;
        }

        ENGINE::trigger_event('BEFORE_GETDATA');
        $data=$this->getData();

        $x = ENGINE::template(
            ENGINE::option('page_tpl', 'tpl_main')
            , ENGINE::option('page_macro', '_')
            , array_merge(array('data' => $data), ENGINE::slice_option('page.'))
        );
        if (!trim($x)) {
            $x=ENGINE::_t('template `{{tpl}}::{{macro}}` not defined',
                array('{{tpl}}' => ENGINE::option('page_tpl', 'tpl_main'),
                    '{{macro}}' => ENGINE::option('page_macro', '_')));
            ENGINE::error($x) ;
            $x = '<html><head><title>Oops</title></head><body>'.$x.'</body></html>';
        }
        echo $x;

//        unset($_SESSION['errormsg']);
    }
}