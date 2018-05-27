<?php
/**
 * хелпер-генератор форм для twig-шаблонизатора
 *
 * @property array fields
 * @property array errors
 * @property array plainerrors
 */

/**
 *  структура поля name=>field
 * - ^name      - имя поля для атрибута name
 * - value      - значение поля
 * - filter     - араметр для фильтрации - параметр функции filter_var
 * - filter_reg - регулярка для провери поля (php style)
 * - fcall      - callable - функция проверки, вызываемая при олучении данных.
 * - type       - captchaBitrix - капча в стиле Битрикс
 *              - captcha - кача в моем стиле
 *              - captcha.new - новая капча
 * - error_msg  - сообщение об ошибке
 * - require    - обязательное поле?
 *
 */

/**
 * Class xSimpleForm
 */
class xSimpleForm
{

    static function samepassword($field, $form)
    {
        if ($field->value == $form->fields->password->value) {
            return true;
        }
        return false;
    }

    protected $has_error = false;
    var $error = '';
    var $report = '';
    public $name = '';
    protected $fielderror = array();
    protected $plainerrors = array();
    var $fields = array();
    protected $method;
    static $names=array();

    function __construct($fields, $name = '', $method = 'POST')
    {
        if (is_array($fields)) {
            $this->fields = new xData($fields);
            if(!empty($this->fields))
            foreach($this->fields as $n=>$v){
                if(!$v->name)
                    $v->name=$n;
            }
        } else {
            $this->fields = $fields;
        }
        $this->name = $name;
        if(!empty($name)){
            self::$names[$name]=$this;
        }
        $this->method = $method;
    }

    static function byname($name){
        if(isset(self::$names[$name]))
            return self::$names[$name];
        return null;
    }

    function _error($msg, $field = null,$store=true)
    {
        $this->has_error = true;
        $this->_report($msg, $field, 'error');
        if($store)$this->store();
    }

    function _report($msg, $field = null, $fld = 'report')
    {
        if (is_string($field)) {
            $field = $this->fields->$field;
        }
        $msg=nl2br($msg);
        if (empty($field) || !is_object($field)) {
            if ('' == $this->{$fld}) {
                $this->{$fld} = array($msg);
            } else {
                $this->{$fld}[] = $msg;
            }
            $this->store();
            return;
        }
        if ('' == $field->{$fld}) {
            $field->{$fld} = array($msg);
        } else {
            $field->{$fld}[] = $msg;
        }
       // $this->store();
    }

    /**
     * возвращаем МАССИВ ключ-значение. Только то, что пришло из POST или
     * имеет значения по умолчанию
     * Для вставки в sql запрос. метод-хелпер
     *
     * @return array
     */
    function values()
    {
        $result = array();
        foreach ($this->fields as $key => $field) {
            if ($field->novalue) {
                continue;
            }
            // indirect modification
            @$result[$key] = ENGINE::_($field->value, $field->default);
        }
        return $result;
    }

    /**
     * сохранить форму
     *
     * @return null
     */
    function load()
    {
        ENGINE::start_session();
        if (empty($this->name)) {
            return;
        }
        if (empty($_SESSION['forms'])) {
            $_SESSION['forms'] = array();
        }
        if (empty($_SESSION['forms'][$this->name])) {
            return;
        }
        $values =& $_SESSION['forms'][$this->name];
        foreach ($this->fields as $name => $field) {
            if (isset($values[$name]) && $field->type != 'submit') {
                $field->value = $values[$name];
            }
        }
        foreach (array('report', 'error') as $x) {
            foreach ($this->fields as $name => $field) {
                if(isset($values[$x]) && isset($values[$x][$name])) {
                    $y = 'past_' . $x;
                    $field->$y = $values[$x][$name];
                }
            }
            if ($_SESSION['forms'][$this->name][$x]) {
                $this->error = $_SESSION['forms'][$this->name][$x];
                $_SESSION['forms'][$this->name][$x] = '';
            }
        }
    }

    function store()
    {
        ENGINE::start_session();

        if (empty($this->name)) {
            return;
        }
        if (empty($_SESSION['forms'])) {
            $_SESSION['forms'] = array();
        }
        if (empty($_SESSION['forms'][$this->name])) {
            $_SESSION['forms'][$this->name] = array();
        }
        $values =& $_SESSION['forms'][$this->name];
        foreach ($this->fields as $name => $field) {
            $values[$name] = $field->value;
        }
        foreach (array('report', 'error') as $x) {
            $values[$x] = array();
            foreach ($this->fields as $name => $field) {
                if ($val = $field->{$x}) {
                    $values[$x][$name] = $val;
                }
            }
            if ($val=$this->$x) {
                $_SESSION['forms'][$this->name][$x] = $val;
            }
        }
    }

    /**
     * очиcтить содержимое области сохранения
     */
    function clear()
    {
        if (empty($this->name)
            || empty($_SESSION['forms'])
            || empty($_SESSION['forms'][$this->name])
        ) {
            return;
        }
        unset($_SESSION['forms'][$this->name]);
    }

    /**
     * @param $name - сюда хотели бы скопировать файл
     * @param $oldone - отсюда хотим скопировать файл
     * @return mixed|string - имя файла, куда можно скопировать файл
     */
    function createUniqueFileName($name, $oldone)
    {
        $name = ENGINE::option('engine.upload_dir', INDEX_DIR . 'uploaded') .
            '/' . $name;
        $stat_old=stat($oldone);
        $x = 1;$cnt=5;
        while (file_exists($name) && $cnt-->0) {
            $stat_new=stat($name);
            if($stat_new['size']==$stat_old['size']){
                if(!isset($stat_old['md5'])){
                    $stat_old['md5']=md5_file($oldone);
                }
                if(md5_file($name)==$stat_old['md5'])
                    return $name;
            }
            $name = preg_replace('/(?:\[\d+\])?(\.\w+)$/', '[' . ($x++) . ']\1', $name);
        }
        return $name;
    }

    static function gotByName($values,$name=''){
        $result=null;
        if(isset($name) && false===strpos($name,'[') && isset($values[$name])){
            $result=$values[$name];
        } else if(isset($name) && false!==strpos($name,'[')) {
            $parts=preg_split('/\]\[|\[|\]/',$name);
            $cur=&$values;
            for($i=0;$i<count($parts)-1;$i++){
                if(isset($cur[$parts[$i]])) {
                    $cur=&$cur[$parts[$i]];
                    if(is_string($cur))
                        $result=stripslashes($cur);
                    else
                        $result=$cur;
                }
                else {
                    $result=null;
                    break;
                }
            }
        }
        return $result;
    }
    /**
     * проверка, что в поле таки что-то введено и введено корректно
     */
    function handle($nostore=false)
    {
        global $APPLICATION; // интергация с битрикс, такая уж интеграция.
        $files=array();
        $this->load();
        $headers=ENGINE::headers();
        $urldecode=false;
        if(isset($headers['Content-Type']) && preg_match('~^multipart/form\-data~',$headers['Content-Type'] ))
            $urldecode=true;

        if ($_SERVER['REQUEST_METHOD'] != $this->method) {
            return false;
        }

        if ($this->method == 'POST') {
            $request =& $_POST;
            if($urldecode){
                foreach($request as &$x){
                    $x=urldecode($x);
                }
            }
        } else {
            $request =& $_GET;
        }

        foreach ($this->fields as $name => $field) {
            if (!is_int($name)) {

                if($field->type == 'signature'){
                    if($field->value != trim($request[$name]))
                        return false;
                } elseif($field->type == 'files'){
                    if(isset($_FILES) && isset($_FILES[$name])&& isset($_FILES[$name]['name'])){
                        $killold=$field->killold;
                        foreach($_FILES[$name]['name'] as $k=>$v){
                            if(''==trim($v)) continue;
                            if(0==$_FILES[$name]['error'][$k]){
                                if(false!==preg_match('/%[D-F]\d%/',$v))
                                    $v=urldecode($v);
                                //ENGINE::debug($k,$v);
                                if (($code = ENGINE::option('page.code', 'UTF-8')) != 'UTF-8') {
                                    if (detectUTF8($v)) {
                                        $v
                                            = iconv('UTF-8', $code . '//ignore', $v);
                                    }
                                }
                                $xname=$this->createUniqueFileName(translit($v),$_FILES[$name]['tmp_name'][$k]);
                                if($killold){
                                    if(is_array($field->value))
                                        foreach($field->value as $vv){
                                            unlink($vv['tmp_name']);
                                        };
                                    $field->value=array();
                                    $killold=false;
                                }
                                move_uploaded_file($_FILES[$name]['tmp_name'][$k],$xname);
                                if(''==$field->value)$field->value=array();
                                //ENGINE::debug($k,$field,$_FILES[$name]);
                                $field->value[$v]=array(
                                    'tmp_name'=>$xname,
                                    'size'=>ENGINE::_($_FILES[$name]['size'][$k])
                                );
                            }
                        }
                    }
                } else {
                    $value=self::gotByName($request,$name);
                    if (!is_null($value)) {
                        if($field->type!='submit'){
                            $field->value = trim($value);
                            if(false!==preg_match('/%[D-F]\d%/',$field->value))
                                $field->value=urldecode($field->value);
                            if (($code = ENGINE::option('page.code', 'UTF-8')) != 'UTF-8') {
                                if (detectUTF8($field->value)) {
                                    $field->value
                                        = iconv('UTF-8', $code . '//ignore', $field->value);
                                }
                            }
                        }

                        if ($field->filter) {
                            if (!filter_var($field->value, $field->filter)) {
                                $this->_error(ENGINE::_($field->error_msg, 'Неверные данные'), $field,false);
                            }
                        }
                        if ($field->filter_reg) {
                            if (!preg_match($field->filter_reg, $field->value))
                                $this->_error(ENGINE::_($field->error_msg, 'Неверные данные'), $field,false);
                        }
                        if ($field->fcall) {
                            if (!call_user_func($field->fcall, $field, $this))
                                $this->_error(ENGINE::_($field->error_msg, 'Неверные данные'), $field,false);
                        }

                        if ($field->type == 'captchaBitrix') {
                            if (!$GLOBALS['APPLICATION']->CaptchaCheckCode($value, $request["captcha_code"])) {
                                $this->_error(ENGINE::_($field->error_msg, 'Неверно введены цифры'), $field,false);
                            }
                        } else if ($field->type == 'captcha') {
                            if ($_SESSION["captcha"] != $value) {
                                $this->_error(ENGINE::_($field->error_msg, 'Неверно введены цифры'), $field,false);
                            }
                        } else if ($field->type == 'captcha.new') {
                            $c = $field->captcha;
                            if (!$c->is_correct($field->value)) {
                                $this->_error(ENGINE::_($field->error_msg, 'Неверно введены цифры') . '!', $field,false);
                            }
                        }
                    }

                    else if ($field->fcall) {
                        if (!call_user_func($field->fcall, $field, $this))
                            $this->_error(ENGINE::_($field->error_msg, 'Неверные данные'), $field,false);
                    }
                    else if ($field->type == 'file') {
                        $files[$name]=1;
                    } else {
                        //ENGINE::debug($field);
                        if ($field->require || $field->type == 'captcha.new') {
                            $this->_error(ENGINE::_($field->error_msg, 'Неправильно заполнено поле '), $field,false);
                        }
                        if($field->type!='submit')
                            $field->value = '';
                    }
                }
            }
        }
        if(!$this->has_error && !empty($files)){ // загрузка файлов

            foreach($files as $key=>$val){
                $x=$_FILES[$key];
                if(!isset($x['name']) || empty($x['name'][0])) continue;
                $files[$key]='/upload/picture/'.$x['name'][0];
                move_uploaded_file($x['tmp_name'][0],
                    realpath($_SERVER["DOCUMENT_ROOT"].'/../lapsi.msk.ru'.$files[$key]));
                $this->fields->{$key}->value=$files[$key];
            }
        }
        //ENGINE::debug($_FILES,$files,$this);
        if(!$nostore)$this->store();
        return !$this->has_error;
    }
    /*
        var $fileds=array();

        function __construct(){

        }


    function SimpleFormPrint($x){
        $res=array();
        //debug($x);
        $even=0;
        foreach($x as $k=>$v){
            if(is_int($k) && is_string($v)) // типо индекс
            {
                $k='';$v=array($v,'text');
            }
            if(is_int($k)) $k='';
            $v[1]=pps($v[1],'input');
            $xx='';
            switch($v[1]){
                case 'text':
                    $xx=array('text'=>array('text'=>$v[0]),'even'=>($even++ & 1)==0);
                    break;
                case 'scrolltext':
                    $xx=array('scrolltext'=>array('title'=>$k,'text'=>$v[0]));
                    break;
                case 'input':case 'textarea':
                if(!empty($_POST[$v[0]]))
                    $xx=array($v[1]=>array('tit'=>$k,'value'=>$_POST[$v[0]]),'even'=>($even++ & 1)==0);
                break;
                case 'checkbox':
                    $xx=array(); $y=explode('|',$v[2]);$z=array();
                    foreach($_POST[$v[0]] as $vv){
                        $z[]=$y[$vv-1];
                    }
                    foreach($z as $kk=>$vv)
                        $xx[]=array('name'=>$v[0],'value'=>$kk,'text'=>$vv);
                    if(empty($xx))$xx='';
                    $xx=array('checkbox'=>array('tit'=>$k,'check'=>$xx),'even'=>($even++ & 1)==0);
                    break;
                case 'radio':
                    $xx=array(); $y=explode('|',$v[2]);$z=array();
                    foreach($y as $kk=>$vv)
                        if(ppi($_POST[$v[0]])==($kk+1))
                            $xx[]=array('name'=>$v[0],'value'=>$kk,'text'=>$vv);
                    if(empty($xx))$xx='';
                    $xx=array('checkbox'=>array('tit'=>$k,'check'=>$xx),'even'=>($even++ & 1)==0);
                    break;
            }
            if(empty($k)){
                $xx[key($xx)]['nocol']=true;
            }
            if(!isset($v['noprint'])&& !empty($xx))
                $res[]=$xx;
        }
        return $res;
    }

    function SimpleForm($fields){
        global $cache;
        if(!empty($cache)){
            $cache->cleanForGroup();
            $cache->setOptions(array('is_enabled'=>false));
        }
        $this->sessionstart();
        if (defined('SECOND_TPL')) $this->tpl=SECOND_TPL;
        $res=array();
        $even=0;
        $values=array();
        $hidden=array();
        if(is_array($fields)){
            foreach($fields as $k=>$v){
                if(is_int($k) && is_string($v)) // типо индекс
                {
                    $k='';$v=array($v,'text');
                }
                if(is_int($k)) $k='';
                if(isset($v['value'])){
                    $values[$v[0]]=$v['value'];
                }
                $v[1]=pps($v[1],'input');
                switch($v[1]){
                    case 'text':
                        $x=array('text'=>array('text'=>$v[0]));
                        break;
                    case 'scrolltext':
                        $x=array('scrolltext'=>array('title'=>$k,'text'=>$v[0]));
                        break;
                    case 'input':case 'textarea':
                    $x=array($v[1]=>array('tit'=>$k,'name'=>$v[0]));
                    break;
                    case 'checkbox':
                        $x=array();
                        foreach(explode('|',$v[2]) as $kk=>$vv)
                            $x[]=array('name'=>$v[0],'value'=>$kk+1,'text'=>$vv);
                        $x=array('checkbox'=>array('tit'=>$k,'check'=>$x));
                        break;
                    case 'radio':
                        $x=array();
                        foreach(explode('|',$v[2]) as $kk=>$vv)
                            $x[]=array('name'=>$v[0],'value'=>$kk+1,'text'=>$vv);
                        $x=array('radio'=>array('tit'=>$k,'check'=>$x));
                        break;
                    case 'hidden':
                        $hidden[]=array('text'=>$v[0]);
                        continue 2;
                    default:
                        continue 2;
                }
                $x['even']=($even++ & 1)==0;
                if(empty($k)){
                    $x[key($x)]['nocol']=true;
                }
                if(!isset($v['require'])){
                    $x[key($x)]['nostar']=true;
                }
                if(isset($v['comment'])){
                    $x[key($x)]['comment']='<br><span class="comment">'.$v['comment'].'</span>';
                }
                $res[]=$x;
            }
            if(!empty($res))
                $res=array('list'=>$res);
            if(!isset($_SESSION['human_user']))	{
                $res['list'][]=array('captcha'=>array(),'even'=>($even++ & 1)==0);
            }
            $res['list'][]=array('submit'=>array(),'even'=>($even++ & 1)==0);
        } else {
            $res['list']=$fields;
        }
        $res['error']=pps($_SESSION['errormsg']);
        if(!empty($hidden))
            $res['hidden']=$hidden;

        $form=new form('callback');
        $form->scanHtml(smart_template(array('tpl_admin','callback'),$res));
        if($form->handle()){
            // проверка обязательных полей
            $error=false;
            $encoding=false;
            foreach($fields as $k=>$v){
                if($encoding || detectUTF8($form->var[$v[0]])){
                    $encoding=true;
                    $form->var[$v[0]]=iconv('UTF-8','cp1251//IGNORE',$form->var[$v[0]]);
                }
                if(!is_int($k) && isset($v['require'])) // типо индекс
                {
                    if(empty($form->var[$v[0]])){
                        $error=true;
                        $this->error('поле "'.$k.'" не заполнено<br>');
                    }
                }
            }
            if (isset($_SESSION["captcha"])){
                if(!$_SESSION['human_user'] && ($_SESSION["captcha"]!==$form->var["captcha"])) {
                    $error=true;
                    $this->error('Неверно введен номер');
                    $form->storevalues();
                } else {
                    unset($_SESSION['captcha']);
                    $_SESSION['human_user']=true;
                }
            }
            if($error)
                $this->go($this->curl());

            return $form ;
        }

        if(isset($_SESSION['USER_ID'])){
            foreach($form->var as $k=>$v){
                if (preg_match('/^cust_/',$k) && isset($this->user[$k])){
                    $form->var[$k]=$this->user[$k];
                }
            }
        }

        foreach($values as $k=>$v){
            $form->var[$k]=$v;
        }
        $form->var["captcha"]="";
        return $form->getHtml(' ');
    }
    */
}
