<?php

/**
 *
 * Базовый класс исполнительного движка шаблонизатора.
 * содержит описания нестандартных фильтров и тестов
 * Фильтры обязаны иметь первым параметром данные для фильтрации
 *
 */
class tpl_base
{

    function __construct()
    {
        $this->macro = array();
    }

    /**
     * преобразовать строку в url
     * -- чистим теги, символы . + / и \
     * @param $s
     * @return string
     */
    function func_debug($s)
    {
        ENGINE::debug(func_get_args());
        return $s;
    }

    /**
     * преобразовать строку в url
     * -- чистим теги, символы . + / и \
     * @param $s
     * @return string
     */
    function func_2url($s)
    {
        return str_replace(' ', '+',
            preg_replace('~[\+/\.]~', '',
                preg_replace('~\s+|&nbsp;~', ' ', strip_tags($s))
            )
        );
    }

    /**
     * дополнить русские числительные
     * @param mixed $n
     * @param string $one - пользователь
     * @param string $two - пользователя
     * @param string $five - пользователей
     * @return string
     */
    function func_finnumb($n, $one, $two, $five)
    {
        if (is_array($n))
            $n = count($n);
        if ($n > 4 && $n < 21)
            return $five;
        $n = $n % 10;
        if ($n == 0)
            return $five;
        if ($n < 2)
            return $one;
        if ($n < 5)
            return $two;
        return $five;

    }

    /**
     * еще один вариант
     * выдать один из вариантов, в зависимости от параметра
     * {{ number }} огур{{ number | rusuf('ец|ца|цов')}}
     * @param $n
     * @param string $search
     * @param string $replace
     * @internal param string $suf
     * @return array
     */
    function func_replace($n, $search = '', $replace = '')
    {
        return str_replace($$search, $replace, $n);
    }

    /**
     * еще один вариант
     * выдать один из вариантов, в зависимости от параметра
     * {{ number }} огур{{ number | rusuf('ец|ца|цов')}}
     * @param $n
     * @param string $suf
     * @return array
     */
    function func_russuf($n, $suf = '')
    {
        list($one, $two, $five, $trash) = explode('|', $suf . '|||', 4);
        if ($n < 20 && $n > 9) return $five;
        $n = $n % 10;
        if ($n == 1) return $one;
        if ($n < 5 && $n > 1) return $two;
        return $five;
    }

    var $loriem_ipsum = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi malesuada est nec magna scelerisque tincidunt. In ut tellus id augue consectetur luctus. Nullam quis lorem dignissim nibh vehicula interdum quis id nisl. Maecenas non nibh et neque pretium tempor. Vestibulum aliquet eros et ligula elementum nec placerat purus hendrerit. Donec dignissim erat at mauris ultrices sit amet tincidunt ipsum laoreet. Aenean et cursus nisl. Sed nunc ante, pellentesque eget molestie non, pellentesque quis tortor. Aenean faucibus sapien non tellus lobortis rutrum. In hac habitasse platea dictumst. Fusce eget enim augue, ac viverra nulla. Vestibulum pretium rhoncus enim, suscipit convallis eros vulputate ut. Vestibulum non facilisis dolor. Etiam pretium, erat a porta accumsan, ante eros pulvinar urna, sit amet iaculis quam risus convallis nisi.

Nunc laoreet, diam ut pellentesque facilisis, ipsum erat laoreet augue, non ornare velit odio ac ipsum. Nullam quis viverra diam. Ut odio orci, congue non gravida semper, sodales id erat. Donec eleifend massa vel massa sodales at mollis dui molestie. Donec commodo interdum velit, tincidunt egestas sem tincidunt euismod. Donec non erat nibh, vel rhoncus est. Morbi nec semper orci. Praesent diam diam, gravida sit amet tincidunt vel, malesuada in lectus. Vivamus vestibulum ipsum sed tellus pretium rutrum. In placerat tellus id nunc tincidunt scelerisque. Sed sollicitudin aliquam gravida. Sed pellentesque placerat convallis. Donec quis nibh dui, a porttitor mi. Sed ut risus vel odio mattis congue. Aliquam a urna eget sapien pulvinar venenatis.

Pellentesque dictum scelerisque urna, sed porta odio venenatis ut. Integer auctor elit nec ante aliquam elementum. Phasellus ipsum ligula, viverra id dignissim in, cursus sed massa. Sed eu aliquam enim. Etiam at mi id mi cursus rhoncus. Aenean scelerisque turpis eget lorem elementum ac pharetra lectus posuere. Integer sed nisl scelerisque velit blandit facilisis. Aliquam erat volutpat. Fusce sem tellus, lobortis a sagittis pellentesque, ultrices mollis erat. Ut aliquet enim eget urna sagittis suscipit. Mauris varius, elit sit amet fringilla semper, mauris libero consequat nunc, dignissim tempor eros ipsum nec arcu. Aliquam eget risus quam.';

    /**
     * filter rights - работа с импом RIGHTS в объекте right ...
     * @param $u
     * @param string $sect
     * @return int
     */
    function func_rights($u, $sect = '*')
    {
        if (is_array($u) && isset($u['right']))
            $u = $u['right'];
        if (is_array($u) && isset($u[$sect])) {
            if (empty($u[$sect]) && !empty($u['*']))
                return $u['*'];
            return $u[$sect];
        } elseif (isset($u['*'])) {
            return $u['*'];
        }
        return 0;
    }

    /**
     * выдать массив от 0 до max с шагом step
     * {{ for i in range(5) }} {{i}} <br> {{endfor}}
     * @param int $max
     * @param int $step
     * @return array
     */
    function func_range($max = 5, $step = 1)
    {
        $result = array();
        for ($i = 0; $i < $max; $i += $step) $result[] = $i;
        return $result;
    }

    function func_date($s, $format = "d m Y")
    {
        if (function_exists('toRusDate')) {
            // debug($s);
            return toRusDate($s, $format);
        }
        if(is_numeric($s))
            return date($format,$s);
        else
            return date($format, strtotime($s));
 //       return date($format, strtotime($s));
    }

    function func_truncate($s, $length = 255, $killwords = False, $end = ' ...')
    {
        if ($killwords === 0) {
            if (preg_match('/(?:\S+\s+){' . $length . '}/', strip_tags($s), $m))
                return $m[0] . $end;
            else
                return $s;
        }
        $s = trim(preg_replace('~\s+|&nbsp;~', ' ', strip_tags($s)));
        if ($killwords) {
            if (strlen($s) > $length)
                return substr($s, 0, $length) . $end;
            return $s;
        }
        if (strlen($s) > $length)
            return substr($s, 0, $length) . $end;
        return $s;
    }

    function func_keys($s)
    {
        if (is_array($s))
            return array_keys($s);
        else
            return array();
    }

    /**
     * функция - выровнять текстовый кусок влево и порезать на строки
     */
    function func_justifyL($s, $size = 80, $left = 0, $right = 0)
    {
        $result = '';
        $leftspaces = "";
        if (is_string($left))
            $leftspaces = $left;
        elseif ($left > 0)
            $leftspaces = str_pad(' ', $left);
        $rsize = $size - $left - $right;
        while ($s != "") {
            if (strlen($s) > $rsize)
                $i = strrpos(substr($s, 0, $rsize), ' ');
            else
                $i = strlen($s);
            if ($i === FALSE) $i = strlen($s);
            $result .= $leftspaces . trim(substr($s, 0, $i)) . "
";
            $s = trim(substr($s, $i + 1));
        }
        return $result;
    }

    /**
     * функция lipsum
     */
    function func_lipsum($n = 5, $html = True, $min = 20, $max = 100)
    {
        static $counter = 0;
        $result = '';
        if (+$n <= 0) $n = 1;
        for ($i = 0; $i < $n; $i++) {
            $words = rand($min, $max);
            for ($j = 0; $j < $words; $j++) {
                preg_match('~\S*\s*~', $this->loriem_ipsum, $m, 0, $counter);
                $result .= $m[0];
                $counter += strlen($m[0]);
                if ($counter + 10 > strlen($this->loriem_ipsum)) $counter = 0;
            }
        }
        return $result;
    }

    /**
     * 3 возможных варианта
     * -- класс-метод
     * -- объект-атрибут
     * -- класс-метод-параметры
     * @param string $plugin
     * @param string $method
     * @param null $par1
     * @param null $par2
     * @param null $par3
     */
    public function attr($p1 = 'MAIN', $p2 = '_handle', $p3 = null, $p4=null)
    {
        if ($p1 == '') $p1 = $this;
        // ENGINE::debug($p2,is_object($p1),method_exists($p1, $p2),is_array($p3));
        if (is_string($p1)) {
            $callable = array($p1, $p2);
            return ENGINE::exec($callable, $p3);
        } else if (is_object($p1)) {
            $x = array();
            if ($p1 instanceof tpl_Base) {
                $p2 = '_' . $p2;
                if (empty($p3)) $p3 = $x;
                else if (!is_array($p3)) {
                    $p3 = array(&$x, $p3, $p4);
                }
            }
            if (method_exists($p1, $p2)) {
                if (is_null($p3))
                    return call_user_func(array($p1, $p2));
                if (is_array($p3))
                    return call_user_func_array(array($p1, $p2), $p3);
                else
                    return call_user_func(array($p1, $p2), &$p3);
            } else if (property_exists($p1, $p2)) {
                return $p1->$p2;
            }
            ENGINE::error('no method/property ' . $p2);
        }
    }

    /**
     * Интерфейсная функция - вызов данных снаружи шаблонизатора
     * @param string $plugin
     * @param string $method
     */
    public function callex($plugin = 'MAIN', $method = '_handle', $par1 = null, $par2 = null, $par3 = null)
    {
        if (class_exists('ENGINE')) {
            $callable = array($plugin, $method);
            return ENGINE::exec($callable, array($par1, $par2, $par3));
        } else {
            global $engine;
            if (!empty($engine)) {
                return $engine->export($plugin, $method, $par1, $par2, $par3);
            }
            return array();
        }
    }

    /**
     * Интерфейсная функция - метода, передаваемого параметром
     * @param string $plugin
     * @param string $method
     */
    public function call(&$par, $s)
    {
        if (method_exists($this, '_' . $s)) {
            $args = func_get_args();
            array_splice($args, 1, 1);
            $args[0] =& $par;
            return call_user_func_array(array($this, '_' . $s), $args);
        }
        return null;
    }

    /**
     * фильтр join
     * @param array $pieces
     * @param string $glue
     */
    function filter_join($pieces, $glue)
    {
        if (!is_array($pieces)) return $pieces;
        return implode($glue, $pieces);
    }

    /**
     * фильтр in_array - проверка на наличие значения в массиве
     * @param mixed $p - mixed
     * @param array $a
     * @return bool
     */
    function func_in_array($p, $a)
    {
        if (is_array($a))
            return in_array($p, $a);
        else
            return $p == $a;
    }

    /**
     * фильтр is_array - массив или траверсабл
     * @param mixed $p - mixed
     * @return bool
     */
    function func_is_array($p)
    {
        return (is_array( $p ) || $p instanceof Traversable);
    }

    /**
     * фильтр slice - вывод значения по умолчанию, при пустом параметре
     * @param $value
     * @param $slices
     * @param string $fill_with
     * @return array
     */
    function func_slice($value, $slices, $fill_with = '')
    {
        if (!is_array($value)) return array();
        $res = array();
        for ($i = 0; $i < count($value); $i += $slices) {
            $res[] = array_slice($value, $i, $slices);
        }
        return $res;
    }

    /**
     * фильтр default - вывод значения по умолчанию, при пустом параметре
     * @param $par
     * @param $def
     */
    function filter_default($par, $def)
    {
        return empty($par) ? $def : $par;
    }

    /**
     * Хелпер для циклов.
     * @param unknown_type $loop_array
     */
    function loopcycle(&$loop_array)
    {
        if (!is_array($loop_array) || count($loop_array) <= 0) return '';
        $s = array_shift($loop_array);
        array_push($loop_array, $s);
        return $s;
    }

    /**
     * вырезка из элемента. Для сокращения слов в шаблоне
     */
    function func_bk(&$el)
    {
        $x = func_get_args();
        array_shift($x);
        $result =& $el;
        foreach ($x as $idx) {
            if (is_array($result) && array_key_exists($idx, $result)) $result =& $result[$idx];
            elseif (is_object($result))
                @$result =& $result->$idx;
            else return '';
        }
        return $result;
    }


}