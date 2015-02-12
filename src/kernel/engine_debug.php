<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Сергей
 * Date: 27.05.13
 * To change this template use File | Settings | File Templates.
 */
class ENGINE_debug {
    /* <% POINT::start('ENGINE_body') %>*/
    /**
     * функция получения информации о стеке вызовов.
     * @param array $opt - массив с ключами для вывода
     * @param int $count - количество позиций
     * @return string
     */
    static function backtrace($opt=array(),$count=1){
        $x=debug_backtrace();
        if(empty($opt)) $opt=array('function'=>'debug');
        if(isset($opt['count'])) $count=$opt['count'];
        $result=array();
        while(count($x)>0){
            foreach(array('function','class') as $xx) {
                if(isset($opt[$xx]) && isset($x[0][$xx]) && (0===strpos($x[0][$xx],$opt[$xx]))){
                    //array_shift($x);
                    break 2;
                }
                elseif(isset($opt['!'.$xx]) && isset($x[0][$xx]) && (false===strpos($x[0][$xx],$opt['!'.$xx])))
                    break 2;
            }
            array_shift($x);
        }
        if(empty($x)){
            $x=debug_backtrace();$count=max(3,$count);array_shift($x);
        } else if(!empty($opt['shift'])){
            array_shift($x);
        }
        while($count-- && (count($x)>0)) {
            $xx=array();
            $y=array_shift($x);
            foreach(array('function'=>'func','class'=>'cls','file'=>'file','line'=>'line') as $k=>$v) {
                if(isset($y[$k]))
                    if($k=='file')
                        $xx[]=$v.':'.str_replace($_SERVER['DOCUMENT_ROOT'],'',$y[$k]);
                    else
                        $xx[]=$v.':'.$y[$k];
            }
            if(!empty($xx))
                $result[]= implode(',',$xx);
        }
        return implode("\n|",$result);
    }

    static function debug()
    {
        $backtrace_options=array('function'=>'debug');$backtrace_count=1;
        $na=func_num_args();
        $out='';
        for ($i=0; $i<$na;$i++){
            $msg=func_get_arg($i);

            if(is_string($msg) && strlen($msg)>1 && $msg{0}=='~'){
                $x=explode('|',substr($msg,1).'||');
                $backtrace_options[$x[0]]=$x[1];
            } else {
                $out.=self::varlog($msg)."\r\n";
            }
        }
        if(empty($backtrace_options))$backtrace_count=4;
        echo '<!--xxx-debug-'.self::backtrace($backtrace_options,$backtrace_count).' '.str_replace('-->','--&gt;',trim(substr($out,0,16000))).'-->';
    }

    /**
     * лог объекта в печатную форму
     *
     * @param $varInput
     * @param string $var_name
     * @param string $reference
     * @param string $method
     * @param bool $sub
     *
     * @return string
     *
     * http://us2.php.net/manual/en/function.var-dump.php#76072
     */
    static function varlog(
        &$varInput, $var_name='', $reference='', $method = '=', $sub = false
    ) {

        static $output ;
        static $depth ;

        if ( $sub == false ) {
            $output = '' ;
            $depth = 0 ;
            $reference = $var_name ;
            $var = serialize( $varInput ) ;
            $var = unserialize( $var ) ;
        } else {
            ++$depth ;
            $var =& $varInput ;
        }

        // constants
        $nl = "\n" ;
        $block = 'a_big_recursion_protection_block';

        $c = $depth ;
        $indent = '' ;
        while( $c -- > 0 ) {
            $indent .= '|  ' ;
        }

        // if this has been parsed before
        if ( is_array($var) && isset($var[$block])) {

            $real =& $var[ $block ] ;
            $name =& $var[ 'name' ] ;
            $type = gettype( $real ) ;
            $output .= $indent.$var_name.' '.$method.'& '.($type=='array'?'Array':get_class($real)).' '.$name.$nl;

            // havent parsed this before
        } else {

            // insert recursion blocker
            $var = Array( $block => $var, 'name' => $reference );
            $theVar =& $var[ $block ] ;

            // print it out
            $type = gettype( $theVar ) ;
            switch( $type ) {

                case 'array' :
                    $output .= $indent . $var_name . ' '.$method.' Array ('.$nl;
                    $keys=array_keys($theVar);
                    foreach($keys as $name) {
                        $value=&$theVar[$name];
                        self::varlog($value, $name, $reference.'["'.$name.'"]', '=', true);
                    }
                    $output .= $indent.')'.$nl;
                    break ;

                case 'object' :
                    $output .= $indent;
                    if(!empty($var_name)){
                        $output .= $var_name.' = ';
                    }
                    $output .= '{'.var_export ($theVar,true).$indent.'}'.$nl;
                    break;
              /*      if( !class_exists('ReflectionClass')){
                        $output .= get_class($theVar).' {'.$nl;
                        foreach((array)$theVar as $name=>$value) {
                            self::varlog($value, $name, $reference.'->'.$name, '->', true);
                        }
                    } else {
                        $reflect = new ReflectionClass($theVar);
                        $output .= $reflect->getName().' {'.$nl;
                        $props   = $reflect->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);

                        foreach ($props as $prop) {
                           // if()
                            self::varlog($theVar->{$prop}, $prop, $reference.'->'.$prop, '->', true);
                            print $prop->getName() . "\n";
                        }
                    }
                    $output .= $indent.'}'.$nl;
                    break ;*/

                case 'string' :
                    $output .= $indent . $var_name . ' '.$method.' "'.$theVar.'"'.$nl;
                    break ;

                default :
                    $output .= $indent . $var_name . ' '.$method.' ('.$type.') '.$theVar.$nl;
                    break ;

            }

            // $var=$var[$block];

        }

        -- $depth ;

        if( $sub == false )
            return $output ;

    }

    /* <% POINT::finish() %>*/
}

