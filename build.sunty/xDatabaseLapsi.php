<?php
/**
 * ����� ���� ������ �������
 * ������� ����������
 *  ENGINE::debug, ::error, ::option, ::cache
 * ----------------------------------------------------------------------------------
 * $Id: X-Site cms (2.0, Sunty build), written by Ksnk (sergekoriakin@gmail.com)
 *  Rev: 1410, Modified: 
 *  SVN: file:///C:/notebook_svn/svn/xilen/cms$
 * ----------------------------------------------------------------------------------
 * License MIT - Serge Koriakin - Jule 2012
 * ----------------------------------------------------------------------------------






 */

/**
 * �����, ���������� ������� insertValues
 */
class dbInsertValues
{

    /** @var string */
    private $_sql_start = '', $_sql_finish = '';
    /** @var xDatabaseLapsi */
    private $_parentDb;
    /** @var array */
    private $_result_values = array();
    /** @var int - ����� ��������������� ������� */
    private $_result_length = 0;
    /** @var int */
    private $_max_result_length = 32000;

    /**
     * �����������
     *
     * @param string $start ����� sql ������� ���� value
     * @param string $finish ����� sql ����� ���� value
     * @param xDatabaseLapsi $parent - ������������ ��������
     */
    public function __construct($start, $finish, $parent)
    {
        $this->_sql_start = $start;
        $this->_sql_finish = $finish;
        $this->_parentDb = $parent;
        $this->_result_length = strlen($start) + strlen($finish);
    }

    /**
     * �������� ��������� ������ �����
     *
     * @param array $values ������
     *
     * @return null
     */
    public function insert($values)
    {
        $v = $this->_parentDb->_(array('(?[?2])', $values));
        $this->_result_length += strlen($v) + 1;
        if ($this->_result_length > $this->_max_result_length) {
            $this->flush();
        }
        $this->_result_values[] = $v;
    }

    /**
     * ���������� ���������� �������.
     *
     * @return null
     */
    public function flush()
    {
        if (count($this->_result_values) > 0) {
            $this->_parentDb->query(
                $this->_sql_start .
                    implode(',', $this->_result_values) .
                    $this->_sql_finish
            );
            $this->result_values = array();
            $this->result_length = strlen($this->_sql_start) +
                strlen($this->_sql_finish);
        }
    }

    /**
     * ���, ����������
     */
    function __destruct()
    {
        $this->flush();
    }
}

/**
 * �����, ������������ � ����� �� ������� select
 */
class dbIterator implements Iterator
{
    private $_position = 0;
    private $_dbresult = null;
    private $_data = null;

    /**
     * ���������. ����������� ������ �������������� ��������������� �������.
     *
     * @param resource $dbresult ��������
     */
    public function __construct($dbresult)
    {
        $this->_dbresult = $dbresult;
        $this->_position = 0;
    }

    /**
     * ��������� - ��������� ���������. ��������� �������
     *
     * @return null
     */
    function rewind()
    {
        $this->_data = mysql_fetch_assoc($this->_dbresult);
        $this->_position = 0;
    }

    /**
     * ��������� - ��������� ���������. ��� ������
     *
     * @return mixed
     */
    function current()
    {
        return $this->_data;
    }

    /**
     * ��������� - ��������� ���������. ��� ������
     *
     * @return int
     */
    function key()
    {
        return $this->_position;
    }

    /**
     * ��������� - ��������� ���������. ��������� ������
     *
     * @return null
     */
    function next()
    {
        $this->_data = mysql_fetch_assoc($this->_dbresult);
    }

    /**
     * ��������� - ��������� ���������. � �� ���?
     *
     * @return boolean
     */
    function valid()
    {
        return is_array($this->_data);
    }

    /**
     * ����������, ���� �����������.
     */
    public function __destruct()
    {
        if (is_resource($this->_dbresult)) {
            mysql_free_result($this->_dbresult);
        }
    }

}

/**
 * ����� ��������� ���� ������������� ���������
 */
class xDatabase_parent
{
    protected $once_options = array();
    /** 
     * @var bool - ���� - ����� �� ������������������ �� ������.
     * ������������� ���������� � �.�.
     */
    protected $_init = true;
    /** @var bool - ���� - �� ��������� �������, � ������ ����������.... */
    protected $_test = false;
    /** @var int - ������� ����������� �������� */
    protected $q_count = 0;
    /** @var null|resource - ���������������� ���������� - ���� ������ �
     * �������� �����
     */
    protected $db_link = null;
    /** @var string -��������� ���������� ��� �������� ����� ���� */
    protected $cachekey = '';

    /**
     * ��������������� �������
     * -- ������� �������,  �������� �����
     * -- ������������� ��������
     *
     * @param string $option ���������
     */
    function __construct($option = '')
    {
        if (!empty($option)) {
            $this->set_option($option);
        }
        if ($this->_init) {
            $this->db_link = mysql_connect(
                ENGINE::option('database.host'),
                ENGINE::option('database.user'),
                ENGINE::option('database.password')
            );
            if (empty($this->db_link)) {
                ENGINE::error(
                    'can\'t connect: ' .
                        ENGINE::option('database.host') . "\n" .
                        ENGINE::option('database.user') . "\n" .
                        ENGINE::option('database.password')
                );
            }
            mysql_select_db(ENGINE::option('database.base') /* , $this->db_link */);
        }
    }

    /**
     * ���������� ���������. ��������� �������� � ���� ������ �� ������� �����
     * ������.
     * �������� - ��������� ����������� ���������� ������ � ������ `_��������`
     * � ������� ������ ���������
     * - init(*), noinit
     * - debug, nodebug(*)
     * - cache(*), nocache
     *
     * @param string $option ������ � ����������, ����� ������
     *
     * @return null
     */
    function set_option($option)
    {
        $prop = array();
        $once = false;
        foreach (explode(' ', $option) as $o) {
            if ($o == 'once') {
                $once = true;
                continue;
            } else if (strpos($o, 'no') === 0) {
                $o = substr($o, 2);
                $val = false;
            } else {
                $val = true;
            }
            $prop[$o] = $val;
        }

        if (!empty($this->once_options)) {
            foreach ($this->once_options as $o => $val) {
                $this->$o = $val;
            }
            $this->once_options = array();
        }
        if (!empty($prop)) {
            foreach ($prop as $o => $val) {
                if (property_exists($this, $o = '_' . $o) && ($this->$o != $val)) {
                    if ($once) {
                        $this->once_options[$o] = $this->$o;
                    }
                    $this->$o = $val;
                }
            }
        }
    }

    /**
     * ����� ���������� ������� ��� ���������� ��������.
     *
     * @param string $format - ������ ������ �������. ����� ����������.
     *
     * @return string
     */
    function report($format = "%s queries,")
    {
        return sprintf($format, $this->q_count);
    }

    /**
     * ������� ������ ���� � ����������� �������. LIMIT 1 ������ ��������������.
     *
     * @return mixed
     */
    function selectCell()
    {
        $result = $this->_query(func_get_args(), true);
        if (!is_resource($result)) {
            return $result;
        }
        $rows = mysql_fetch_row($result);
        $this->free($result);
        if (!$rows) {
            return false;
        }
        return $this->cache($this->cachekey, $rows[0]);
    }

    /**
     * ������� ������ ���� � ����������� �������. LIMIT 1 �������������.
     *
     * @return mixed
     */
    function selectCol()
    {
        $result = $this->_query(func_get_args(), true);
        if (!is_resource($result)) {
            return $result;
        }
        $res = array();
        while ($row = mysql_fetch_row($result)) {
            $res[] = $row[0];
        }
        $this->free($result);
        return $this->cache($this->cachekey, $res);
    }

    /**
     * ������� ������ ������.
     * @return mixed
     */
    function selectRow()
    {
        $result = $this->_query(func_get_args(), true);
        if (!is_resource($result)) {
            return $result;
        }
        $rows = mysql_fetch_assoc($result);
        $this->free($result);
        return $this->cache($this->cachekey, $rows);
    }

    function selectAll()
    {
        $result = $this->_query(func_get_args(), true);
        if (!is_resource($result)) {
            return $result;
        }
        $res = array();
        while ($row = mysql_fetch_assoc($result)) {
            $res[] = $row;
        }
        $this->free($result);
        return $this->cache($this->cachekey, $res);
    }

    /**
     * ������� ���, ������ ������ - ������������� ������.
     *
     * @return resource|boolean
     */
    function select()
    {
        $result = $this->_query(func_get_args(), true);
        if (!is_resource($result)) {
            return $result;
        }
        $res = array();
        while ($row = mysql_fetch_assoc($result)) {
            $res[] = $row;
        }
        $this->free($result);
        return $this->cache($this->cachekey, $res);
    }

    /**
     * ������� ��� �� �������� �������, ������� ��������.
     *
     * @return boolean|dbIterator
     */
    function selectLong()
    {
        $result = $this->_query(
            func_get_args(), false // �� ���������� ������� �������
        );
        if (!is_resource($result)) {
            return $result;
        }
        return new dbIterator($result);
    }

    /**
     * ������� ��� �� �������, ������� ������ � ���������.
     *
     * @param int $idx �������� ��� �������
     *
     * @return array
     */
    function selectByInd($idx)
    {
        $arg=func_get_args();
        array_shift($arg);
        $result = $this->_query($arg, true);
        if (!is_resource($result)) {
            return $result;
        }
        $res = array();
        while ($row = mysql_fetch_assoc($result)) {
            if (!empty($row[$idx])) {
                $res[$row[$idx]] = $row;
            }
        }
        $this->free($result);
        return $this->cache($this->cachekey, $res);
    }

    /**
     * ��������� ������ � �����������.
     * sql �������� � ����������� �����������.
     *
     * @param array $arg - ������ + ��������� �������
     * @param string $options - ����� �������
     *
     * @return resource
     */
    protected function _query($arg, $options = '')
    {
        ENGINE::error('unrealised awhile ');
        return null;
    }

    /**
     * free
     *
     * @param resource $handle �����
     *
     * @return null
     */
    function free($handle)
    {
        if (is_resource($handle)) {
            mysql_free_result($handle);
        }
    }

    /**
     * ������� ����������� ������� �� sql
     *
     * @param string $name
     * @param bool $data
     *
     * @return bool
     */
    function cache($name, $data = false)
    {
        return $data;
    }

    /**
     * ��������. ������� ��������� ����������� ������.
     */
    function insert($query)
    {
        $result = $this->_query(func_get_args());
        $this->free($result);
        return @mysql_insert_id($this->db_link);
    }

    /**
     * �������. ������� ��������� ��� �������� ��������� �������
     *
     * @return int
     */
    function delete()
    {
        $result = $this->_query(func_get_args());
        $this->free($result);
        return @mysql_affected_rows($this->db_link);
    }

    /**
     * ��������, ������ �� ����������.
     *
     * @return null
     */
    function update()
    {
        $result = $this->_query(func_get_args());
        $this->free($result);
    }

    /**
     * ������� ������������������.
     * �� �����, �� ������� ������� �����
     */
    function __destruct()
    {
        if (!empty($this->db_link) && is_resource($this->db_link)) {
            mysql_close($this->db_link);
        }
    }

    /**
     * ��������� ��������� ���������� sql ��������. ������ �� ����������
     *
     * @param string $sql ���������� ����
     *
     * @return null
     */
    public function sql_dump($sql)
    {
        foreach (explode(";\n", str_replace("\r", '', $sql)) as $s) {
            $s = trim(preg_replace('~^\-\-.*?$|^#.*?$~m', '', $s));
            if (!empty($s)) {
                $this->query($s);
            }
        }
    }

    /**
     * ��������� ������, ������� ��������� � ����������� �� ���� �������
     *
     * @param $query
     *
     * @return mixed
     */
    function query($query)
    {
        $result = $this->_query(func_get_args());
        $this->free($result);
    }
}

/**
 * x3 ����� �� �����
 * �� ��� - ������� ����� ��� ������ � mysql ��� ���������
 */
class xDatabase extends xDatabase_parent
{
    /**
     * ������� - ����� ��� �� ����.
     *
     * @param array $arg
     * @param string $options
     *
     * @return resource
     */
    protected function _query($arg, $options = '')
    {
        return mysql_query($arg[0] /* , $this->db_link */);
    }
}

/**
 * ������� xDatabase ��� ������ � mysql - Xilen style
 * ������������ twig-like ���� ��������.
 */
class xDatabaseXilen extends xDatabase_parent
{
    protected $_debug = false;
    private $tpl = null;

    function __construct()
    {
        parent::__construct();
        $this->tpl = new sql_template();
        $this->tpl->regcns('prefix', ENGINE::option('database.prefix', 'xsite'));
        $this->tpl->regcns('CODE', ENGINE::option('database.code', 'UTF8'));
        if ($this->_init) {
            $this->query("SET NAMES {{CODE}}");
        }
    }

    /**
     * ��������� ������ � �����������.
     * sql �������� � ����������� �����������.
     * @param array $arg - ������ + ��������� �������
     * @param string $options - ����� �������
     * @return resource
     */
    protected function _query($arg, $options = '')
    {
        $func = $this->tpl->parse($arg[0]);
        $sql = call_user_func_array($func, $arg);
        if (!$this->_test) {
            $result = mysql_query($sql /* , $this->db_link */);
            if (!$result) {
                ENGINE::error('Invalid query: ' . mysql_error() . "\n" .
                    'Whole query: ' . $sql);
            } else {
                $this->q_count += 1;
            }
        } else {
            ENGINE::debug('TEST: ' . $sql);
            $result = false;
        }
        if ($this->_debug) {
            ENGINE::debug('QUERY: ' . $sql);
        }
        return $result;
    }
}

/**
 * ������� xDatabase ��� ������ � mysql � memcache-������������ (LAPSI style)
 */
class xDatabaseLapsi extends xDatabase_parent
{
    /** @var bool|Memcache */
    // private $mcache = false;
    public $_cache = true;
    protected $_debug = false;
    private $c_count = 0;

    /**
     * �����������
     *
     * @param string $option ��������
     */
    function __construct($option = '')
    {
        parent::__construct($option);
        $this->prefix = ENGINE::option('database.prefix', 'xsite');
        if ($option=ENGINE::option('database.options')) {
            $this->set_option($option);
        }

        if ($this->_init) {
            $this->query(
                "SET NAMES " . ENGINE::option('database.code', 'UTF8') . ";"
            );
        }
    }

    /**
     * ����� ���������� ������� ��� ���������� ��������.
     * 
     * @param string $format ����� �������
     * 
     * @return string
     */
    function report($format = "mysql:[%s(%s) queries] ")
    {
        return sprintf($format, $this->q_count, $this->c_count);
    }

    /**
     * ��������. ������������� ����� ������� ��������.
     * 
     * @return dbInsertValues
     */
    function insertValues()
    {
        $sql = $this->_(func_get_args());
        list($start, $finish) = explode('()', $sql);
        return new dbInsertValues($start, $finish, $this);
    }

    /**
     * ��������� ������ � �����������.
     * sql �������� � ����������� �����������.
     * 
     * @param array $arg    ������ + ��������� �������
     * @param bool  $cached ���������� ��� ����
     * 
     * @return resource
     */
    protected function _query($arg, $cached = false)
    {
        $start = 0;
        if ($this->_debug) {
            $start = microtime(true);
        }
        $sql = $this->_($arg);
        if ($cached) {
            $this->cachekey = ENGINE::option('cache.prefix', 'x') . md5($sql);
            if (false !== ($result = $this->cache($this->cachekey))) {
                if ($this->_debug) {
                    ENGINE::debug(
                        'QUERY(cache)' . sprintf('[%f]', microtime(true) - $start) . 
                        ': ' . $sql . "\n",
                        '~function|_query', '~shift|1'
                    );
                }
                return $result;
            }
        }
        //ENGINE::debug( 222/* ,$this->db_link */);
        if (!$this->_test) {
            $result = mysql_query($sql /* , $this->db_link */);
            if (!$result) {
                ENGINE::error(
                    'Invalid query: ' . mysql_error() . "\n" . 'Whole query: ' . $sql
                );
            } else {
                $this->q_count += 1;
            }
        } else {
            ENGINE::debug(
                "QUERY-TEST:\n" . $sql . "\n", '~function|_query', '~shift|1'
            );
            $result = false;
        }
        if ($this->_debug) {
            ENGINE::debug(
                'QUERY' . sprintf('[%f]', microtime(true) - $start) .
                ":\n" . $sql . "\n", '~function|_query', '~shift|1'
            );
        }

        return $result;
    }

    /**
     * helper-����������� sql �����������.
     * ������ �����������
     *  ?_ - ���������� ������� �������, ��������� ����������� �� ������������
     *  ?12x - ���������� 12 �� ����� ��������. ��������� ���������� �� ������������
     *      ��� ������ - ��������� ������������ �� ��������� ��������
     *  ?x - ���������� �������� ��� ���������
     *  ?d, ?i - �������� - ��c��. ���� ���������� � ��������� ��������, ������ ���.
     *  ?k - �������� - ��� ����, ����������� `` ���������
     *  ?s - �������� - ������ - ��������� � ������� ��������,
     *      �������� mysql_real_escape_string
     *  ? - ������������� ��������, ��� ����� �� ����������� �������,
     *      ��� ����� �������� ������
     *  ?[...] - �������� - ������, ��� ������ ���� ����-�������� �������
     *      ����������� ������ �� ������. ����������� ��������
     *
     * @example
     * ������� insert
     *    - $db->_(array('insert into ?k (?(?k)) values (?2(?2))','x_table'
     *           ,array('one'=>1,'two'=>2,'three'=>'�����')))
     *     ==> insert into `x_table` (`one`,`two`,`three`) values (1,2,"�����")
     *
     * insert on duplicate key
     *    - $db->_(array('insert into ?k (?(?k)) values (?2(?2))
     *      on duplicate key set ?2(?k=?)','x_table'
     *      ,array('one'=>1,'two'=>2,'three'=>'�����')))
     *     ==> insert into `x_table` (`one`,`two`,`three`) values (1,2,"�����")
     *      on duplicate key set `one`=1,`two`=2,`three`="�����"
     *  - $db->query(
     *      'insert into `laptv_video` (`LASTUPDATE`,?[?k]) values (NOW(),?1[?2])'.
     *      'on duplicate key update  `LASTUPDATE`=NOW(),?1[?1k=VALUES(?1k)];'
     *      ,$data);
     *
     * ��������� ��������
     *   - $x=array(
     *         array('x'=>1,'y'=>2,'z'=>3),
     *         array('x'=>1,'y'=>2,'z'=>3),
     *         array('x'=>1,'y'=>2,'z'=>3),
     *       ...
     *    )
     *    $part=array();
     *    foreach($x as $xx) $part[]=...->_(array(array('(?(?2))',$xx)));
     *    ->_(array('insert into ?k (?(?k)) values ?3(?2x);','table',$x[0],$part)))
     * 
     * @param array $args ������� �������� - ������
     * 
     * @return string
     */
    function _($args)
    {
        static $pref;
        //$args=func_get_args();
        $format = $args[0];
        $cnt = 1;
        $start = 0;
        while (preg_match('/(?<!\\\\)\?(\d*)([id\#ayxk_s]|\[([^\]]+)\]|)/i'
            , $format, $m, PREG_OFFSET_CAPTURE, $start)
        ) {
            $x = '';
            $cur = $m[1][0];
            if (empty($cur)) {
                $cur = $cnt++;
            }
            if (empty($m[2][0])) {
                if ('' === $args[$cur]) {
                    $x = '""';
                } elseif (0 === $args[$cur]) {
                    $x = 0;
                } elseif ('0' === $args[$cur]) {
                    $x = 0;
                } elseif (empty($args[$cur])) {
                    $x = 'null';
                } elseif (is_int($args[$cur]) || ctype_digit($args[$cur])) {
                    $x = (0 + $args[$cur]);
                } else {
                    $x = '"' . mysql_real_escape_string($args[$cur]) . '"';
                }
                $xx = '';
            } else {
                switch ($xx = $m[2][0]) {
                case '_':
                    if (!isset($pref)) {
                        $pref = ENGINE::option('database.prefix', 'xxx_');
                    }
                    if (empty($m[1][0])) {
                        $cnt--;
                    }
                    $x = $pref;
                    break;
                case 'i':
                case 'd':
                    $x = (0 + $args[$cur]);
                    break;
                case 'x':
                    $x = $args[$cur];
                    break;
                case 'k':
                    $x = '`' . str_replace("`", "``", $args[$cur]) . '`';
                    break;
                case 's':
                    $x = '"' . mysql_real_escape_string($args[$cur]) . '"';
                    break;
                case 'y':
                    $x = mysql_real_escape_string($args[$cur]);
                    break;
                default: //()
                    $explode = ',';
                    if ($xx == 'a') { // ?a
                        reset($args[$cur]);
                        if (key($args[$cur])) {
                            $tpl = '?k=?';
                        } else {
                            $tpl = '?2';
                        }
                    } else if ($xx == '#') { //?#
                        $tpl = '?2k';
                    } else { // ������ � ����������
                        $tpl = $m[3][0]; //if(!empty($m[4][0]))$tpl.=$m[4][0];
                        if (false === ($pos = strpos($tpl, '|'))) {
                            $explode = ', ';
                        } else {
                            $explode = substr($tpl, $pos + 1);
                            $tpl = substr($tpl, 0, $pos);
                        }
                    }
                    if (is_array($args[$cur])) {
                        if (empty($args[$cur])) {
                            return 'null';
                        }
                        $s = array();
                        foreach ($args[$cur] as $k => $v) {
                            $s[] = $this->_(array($tpl, $k, $v));
                        }
                        $x = implode($explode, $s);
                    }
                }
            }
            $format = substr($format, 0, $m[0][1]) . $x . 
                substr($format, $m[2][1] + strlen($xx));
            $start = $m[0][1] + strlen($x);
        }
        return $format;
    }

    /**
     * �����������. ����������� �� ��������� �����������, � ��������������
     * ����������� ������
     *
     * @param string $name ��� 
     * @param bool $data ��������
     * @param int $time �� �����
     * 
     * @return bool|mixed
     */
    function cache($name, $data = false, $time = 28800)
    {
        if (!$this->_cache) {
            return $data;
        }
        if (false === $data) {
            if (false !== ($result = ENGINE::cache($name))) {
                $this->c_count += 1;
                return unserialize($result);
            }
            return false;
        } else {
            ENGINE::cache($name, serialize($data), $time);
        }
        return $data;
    }


}