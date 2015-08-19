<?php
/**
 * Собрание всяких полезных и не очень функций, засоряющих начала файлов index и main
 */
/**
 * замер времени выполнения
 */
if(version_compare("5.0.0", phpversion() , "<")){
    function mkt($store=false){
        static $tm ;  $x = microtime(true);
        if($store)$tm=$x;
        return $x-$tm ;
    } ;
}
else {
    function mkt($store=false){
        static $tm ;  list($usec, $sec) = explode(" ", microtime());
        if($store)$tm=(float)$usec + (float)$sec; // microtime(1) ;
        return ((float)$usec + (float)$sec)-$tm ;
    } ;
}
mkt(true);

/* usefull stuff */
//*******************************************************
// пользительные функции!!!
if ( !function_exists('htmlspecialchars_decode') )
{
    function htmlspecialchars_decode($text)
    {
        return strtr($text, array_flip(get_html_translation_table(HTML_SPECIALCHARS)));
    }
}
if(!function_exists('http_build_query')) {
    function http_build_query($data,$prefix=null,$sep='',$key='') {
        $ret = array();
        if(!empty($data))
        foreach((array)$data as $k => $v) {
            $k = urlencode($k);
            if(is_int($k) && $prefix != null) {
                $k = $prefix.$k;
            };
            if(!empty($key)) {
                $k = $key."[".$k."]";
            };
            if(is_array($v) || is_object($v)) {
                array_push($ret,http_build_query($v,"",$sep,$k));
            }
            else {
                array_push($ret,$k."=".urlencode($v));
            };
        };
        if(empty($sep)) {
            $sep = '&';//ini_get("arg_separator.output");
        };
        return implode($sep, $ret);
    };
};

//if(!function_exists('json_encode')){
/**
 * Convert PHP scalar, array or hash to JS scalar/array/hash.
 */
function detectUTF8($string)
{
    return preg_match('%(?:
       [\xC2-\xDF][\x80-\xBF]        		# non-overlong 2-byte
       |\xE0[\xA0-\xBF][\x80-\xBF]          # excluding overlongs
       |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}   # straight 3-byte
       |\xED[\x80-\x9F][\x80-\xBF]          # excluding surrogates
       |\xF0[\x90-\xBF][\x80-\xBF]{2}    	# planes 1-3
       |[\xF1-\xF3][\x80-\xBF]{3}           # planes 4-15
       |\xF4[\x80-\x8F][\x80-\xBF]{2}    	# plane 16
       )+%xs', $string);
}

function json_encode_cyr($str) {
    $arr_replace_utf = array('\u0410', '\u0430','\u0411','\u0431','\u0412','\u0432',
        '\u0413','\u0433','\u0414','\u0434','\u0415','\u0435','\u0401','\u0451','\u0416',
        '\u0436','\u0417','\u0437','\u0418','\u0438','\u0419','\u0439','\u041a','\u043a',
        '\u041b','\u043b','\u041c','\u043c','\u041d','\u043d','\u041e','\u043e','\u041f',
        '\u043f','\u0420','\u0440','\u0421','\u0441','\u0422','\u0442','\u0423','\u0443',
        '\u0424','\u0444','\u0425','\u0445','\u0426','\u0446','\u0427','\u0447','\u0428',
        '\u0448','\u0429','\u0449','\u042a','\u044a','\u042d','\u044b','\u042c','\u044c',
        '\u042d','\u044d','\u042e','\u044e','\u042f','\u044f');
    $arr_replace_cyr = array('А', 'а', 'Б', 'б', 'В', 'в', 'Г', 'г', 'Д', 'д', 'Е', 'е',
        'Ё', 'ё', 'Ж','ж','З','з','И','и','Й','й','К','к','Л','л','М','м','Н','н','О','о',
        'П','п','Р','р','С','с','Т','т','У','у','Ф','ф','Х','х','Ц','ц','Ч','ч','Ш','ш',
        'Щ','щ','Ъ','ъ','Ы','ы','Ь','ь','Э','э','Ю','ю','Я','я');
    $str1 = json_encode($str);
    $str2 = str_replace($arr_replace_utf,$arr_replace_cyr,$str1);
    return $str2;
}

function php2js($a)
{
    if (is_null($a)) return 'null';
    if ($a === false) return 'false';
    if ($a === true) return 'true';
    if (is_scalar($a)) {
        $a = addslashes($a);
        $a = str_replace("\n", '\n', $a);
        $a = str_replace("\r", '\r', $a);
        $a = preg_replace('{(</)(script)}i', "$1'+'$2", $a);
        return "'$a'";
    }
    $isList = true;
    for ($i=0, reset($a); $i<count($a); $i++, next($a))
        if (key($a) !== $i) { $isList = false; break; }
    $result = array();
    if ($isList) {
        foreach ($a as $v) $result[] = php2js($v);
        return '[ ' . join(', ', $result) . ' ]';
    } else {
        foreach ($a as $k=>$v)
            $result[] = php2js($k) . ': ' . php2js($v);
        return '{ ' . join(', ', $result) . ' }';
    }
}
//}
//**********************************************************************************
//*** 1 - неопределенные переменные
//***     pp($_POST['xxx'],'') - без надобности проверки на существование
//*** 2 - оформление с обрамлением и дефолтом
//***     pp($a,'[',']','<а нету>')
//**********************************************************************************
/**
 * @param  $x - значение для проверки
 * @param string $pre - префикс, если значение не пусто
 * @param string $post - суффикс - если значение не пусто
 * @param string $def - умолчание, если значение пусто
 * @return string
 */
function pp(&$x,$pre=' ',$post='',$def='') { return (!empty($x))?$pre.$x.$post:$def;}
function pps(&$x,$def='') { return (!empty($x))?$x:$def;}
function ppi(&$x,$def=0) { return (!empty($x))?intval($x):$def;}
function ppx($x,$def='') { return (!empty($x))?$x:$def;}

function toUrl($z=''){
    $dr = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    $z = str_replace('\\', '/', $z);
    return preg_replace('~^(.\:|/usr)?'.preg_quote($dr,'~:').'|index.php(\?|\b)~is', '', $z);
}

if(!function_exists('filter_var')){
    define('FILTER_VALIDATE_EMAIL',1000);
    define('FILTER_DEFAULT',0);

    function filter_var($var,$filter= FILTER_DEFAULT){
        if ($filter==FILTER_VALIDATE_EMAIL){
            return preg_match(
                '/^([a-z0-9][a-z0-9-]*[a-z0-9]\.?)*[a-z0-9]@([a-z0-9]\.|[a-z0-9][a-z0-9-]*[a-z0-9]\.)+[a-z]{2,6}$/i',
                $var);
        }
        return true;
    }
}

function UpLow($string,$registr='up'){
    $upper = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $lower = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяabcdefghijklmnopqrstuvwxyz';
    if($registr == 'up') $string = strtr($string,$lower,$upper);
    else $string = strtr($string,$upper,$lower);
    return $string;
}

/**
 * функция преобразования текста в комментарий
 *
 */
function post2comment($s){
    $s=preg_replace(array('/^    */m','/^  /m','/^ /m')
        ,array('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;','&nbsp;&nbsp;&nbsp;&nbsp;','&nbsp;&nbsp;'),$s);
    return nl2br(strip_tags($s));
}

/**
 * транслитерация, для получения удобоваримого имени файла ил загруженного.
 * @param $s
 * @return string
 */
function translit($s)
{
    /*	$s=str_replace(" ","_",$s); // сохраняем пробел от перехода в %20
        $s=str_replace(",",".h",$s); // сохраняем запятую
        $s=str_replace('"',"&quot;",$s); // сохраняем кавычки
    */
    $s=urldecode($s);
//    $s=str_replace('"',"&quot;",$s); // сохраняем кавычки

    $s= strtr($s,array(
        "ЫА"=>"YHA",
        "ЫО"=>"YHO",
        "ЫУ"=>"YHU",
        "ыа"=>"yha",
        "ыо"=>"yho",
        "ыу"=>"yhu",
        'А' => 'A',
        'Б' => 'B',
        'В' => 'V',
        'Г' => 'G',
        'Д' => 'D',
        'Е' => 'E',
        'Ё' => 'YO',
        'Ж' => 'ZH',
        'З' => 'Z',
        'И' => 'I',
        'Й' => 'J',
        'К' => 'K',
        'Л' => 'L',
        'М' => 'M',
        'О' => 'O',
        'П' => 'P',
        'Р' => 'R',
        'С' => 'S',
        'Т' => 'T',
        'У' => 'U',
        'Ф' => 'F',
        'Х' => 'H',
        'Ц' => 'C',
        'Ч' => 'CH',
        'Ш' => 'SH',
        'Щ' => 'CSH',
        'Ь' => '',
        'Ы' => 'Y',
        'Ъ' => '',
        'Э' => 'E',
        'Ю' => 'YU',
        'Я' => 'YA',

        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'yo',
        'ж' => 'zh',
        'з' => 'z',
        'и' => 'i',
        'й' => 'j',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'h',
        'ц' => 'c',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'csh',
        'ь' => '',
        'ы' => 'y',
        'ъ' => '',
        'э' => 'e',
        'ю' => 'yu',
        'я' => 'ya',
        " "=>"_",
        "№"=>"n",
//        '"'=>"&quot;"
    )) ;
    $s= strtr($s,array(
        '&'=>'',
        '#'=>'',
        ';'=>'',
        '*'=>'',
        '?'=>''
    ));
    for($i=0;$i<strlen($s);$i++){
        if($s{$i}>"\x80")$s{$i}='_';
    }

    return $s;
}
/**
 * Сканирование параметров в html элементе
 *
 * @param string $s - строка с параметрами
 * @param array $arr - массив имен параметров
 * @return array - массив параметров
 */
function scanPar($s,$arr){
    preg_match_all("/(\w*)=(['\"])([^\\2]*)\\2/U", $s,$x);
    //print_r($x);
    $par=array('par'=>'');
    foreach($x[1] as $k=>$v){$v=strtolower($v);
        if(isset($arr[$v]))
            $par[$arr[$v]]=$x[3][$k];
        else
            $par['par'].=' '.$v.'='.$x[2][$k].$x[3][$k].$x[2][$k];
    }
    return $par;
}

/**
 * определить, что строка needle начинается с символов haystack
 * @param $needle
 * @param $haystack
 * @return bool
 */
function stringBeginsWith($needle, $haystack) {
    return (substr($haystack, 0, strlen($needle))==$needle);
}

/**
 * трансляция в русские даты аглицкой мовы
 * @param null $daystr
 * @param string $format
 * @return mixed
 */
function toRusDate($daystr=null,$format="j F, Y г."){
    //print_r($datstr);
    if ($daystr) $daystr=strtotime($daystr);
    else $daystr=time();
    return	str_replace( //XXX: нужно проверить английские имена месяцев
        array('january','february','march','april','may','june','july',
            'august','september','october','november','december',
            'jan','feb','mar','apr','may','jun','jul',
            'aug','sep','oct','nov','dec'),
        array('января','февраля','марта','апреля','мая','июня','июля',
            'августа','сентября','октября','ноября','декабря',
            'янв','фвр','мрт','апр','мая','июн','июл',
            'авг','сен','окт','ноя','дек'),
        strtolower(date($format,
            $daystr)));
}

