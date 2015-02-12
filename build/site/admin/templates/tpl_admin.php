<?php
/**
 * this file is created automatically at "16 Mar 2013 13:23". Never change anything, 
 * for your changes can be lost at any time. 
 */ 
class tpl_admin extends tpl_base {
function __construct(){
parent::__construct();
$this->macro['first']=array($this,'_first');
$this->macro['longinput']=array($this,'_longinput');
$this->macro['hidden']=array($this,'_hidden');
$this->macro['input']=array($this,'_input');
$this->macro['select']=array($this,'_select');
$this->macro['check']=array($this,'_check');
$this->macro['color']=array($this,'_color');
$this->macro['text']=array($this,'_text');
$this->macro['param']=array($this,'_param');
$this->macro['pageparam']=array($this,'_pageparam');
$this->macro['users']=array($this,'_users');
$this->macro['edlinefinish']=array($this,'_edlinefinish');
$this->macro['page']=array($this,'_page');
}

function _first(&$namedpar,$title=0,$help=0){
extract($namedpar);
$result='
<span class="_states xbardivider notext floatright">|</span>
<div class="first">
    <span class="_states xbar notext">|</span><span class="title">'
    .($title)
    .'</span>';
if( $help ) {

$result.='
    <span class="_states xhelp notext" title="'
    .($help)
    .'">?</span>';
};
$result.='
</div>
<span class="_states xbardivider notext">|</span>';
    return $result;
}

function _longinput(&$namedpar,$option=0,$value=0,$def=''){
extract($namedpar);
$result='
<input class="long" type="text" name="'
    .($option)
    .'" value="'
    .($this->filter_default($this->func_bk($value,$option),$def))
    .'">';
    return $result;
}

function _hidden(&$namedpar,$option=0,$value=0){
extract($namedpar);
$result='
<input type="hidden" name="'
    .($option)
    .'" value="'
    .($this->filter_default($this->func_bk($value,$option),''))
    .'">';
    return $result;
}

function _input(&$namedpar,$option=0,$value=0){
extract($namedpar);
$result='
<input type="text" name="'
    .($option)
    .'" value="'
    .($this->filter_default($this->func_bk($value,$option),''))
    .'">';
    return $result;
}

function _select(&$namedpar,$option=0,$sel=0){
extract($namedpar);
$result='
<select name="'
    .($option)
    .'" value="'
    .($this->callex('Main','option',$option))
    .'">';
$loop1_array=ps($sel);
if (is_array($loop1_array) && !empty($loop1_array)){
foreach($loop1_array as $ind =>$val){

$result.='
    <option value="'
    .($ind)
    .'">'
    .($val)
    .'</option>';
}};
$result.='
</select>';
    return $result;
}

function _check(&$namedpar,$option=0){
extract($namedpar);
$result='
<input type="checkbox" name="'
    .($option)
    .'"';
if( $this->callex('Main','option',$option) ) {

$result.='checked="checked"';
};
$result.='">';
    return $result;
}

function _color(&$namedpar,$option=0){
extract($namedpar);
$result='
<span>Цвет:</span>
<input type="text" class="color" name="'
    .($option)
    .'" value="'
    .($this->callex('Main','option',$option))
    .'">';
    return $result;
}

function _text(&$namedpar,$option=0){
extract($namedpar);
$result='
<textarea class="hidden tinyEdit" name="'
    .($option)
    .'">
    '
    .($this->callex($this->filter_default('Main','option',$option),'нажмите сюда для вставки текста'))
    .'
</textarea>';
    return $result;
}

function _param(&$namedpar,$data=0 ){
extract($namedpar);
$result='';
$loop1_array=ps($data);
if (is_array($loop1_array) && !empty($loop1_array)){
foreach($loop1_array as $title =>$body){

$bdata=$this->func_bk($body,'data');
if( !($body) ) {

$result.='
<div style="height:20px;"></div>';
}
elseif( (($this->func_bk($body,'type'))==('title')) ) {

$result.='<div class="titleline">'
    .($title)
    .'</div>';
}
elseif( (($this->func_bk($body,'type'))==('str')) ) {

$result.='<div class="dialogline"> ';
if(!empty($this->macro['first']))
$result.=call_user_func($this->macro['first'],array(),$title, $this->func_bk($body,'title'));
$result.='
    ';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$bdata);
$result.='
</div>';
}
elseif( (($this->func_bk($body,'type'))==('XxX')) ) {

$result.='<div class="dialogline"> ';
if(!empty($this->macro['first']))
$result.=call_user_func($this->macro['first'],array(),$title, $this->func_bk($body,'title'));
$result.='
    ';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$this->func_bk($bdata,0));
$result.='x';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$this->func_bk($bdata,1));
$result.='
</div>';
}
elseif( (($this->func_bk($body,'type'))==('ramka')) ) {

$result.='<div class="dialogline"> ';
if(!empty($this->macro['first']))
$result.=call_user_func($this->macro['first'],array(),$title, $this->func_bk($body,'title'));
$result.='
    <span class="_states xupdn notext">|</span>
    ';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$this->func_bk($bdata,0));
$result.='
    <span class="_states xltrt notext">|</span>
    ';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$this->func_bk($bdata,1));
$result.='
    <span class="_states xbarsepar notext">|</span>
    ';
if(!empty($this->macro['color']))
$result.=call_user_func($this->macro['color'],array(),$this->func_bk($bdata,3));
$result.='
    <span class="_states xbarsepar notext">|</span>
    ';
if(!empty($this->macro['check']))
$result.=call_user_func($this->macro['check'],array(),$this->func_bk($bdata,4));
$result.='
</div>';
}
elseif( (($this->func_bk($body,'type'))==('offset')) ) {

$result.='<div class="dialogline"> ';
if(!empty($this->macro['first']))
$result.=call_user_func($this->macro['first'],array(),$title, $this->func_bk($body,'title'));
$result.='
    <span class="_states xup notext">|</span>
    ';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$this->func_bk($bdata,0));
$result.='
    <span class="_states xdn notext">|</span>
    ';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$this->func_bk($bdata,1));
$result.='
    <span class="_states xlt notext">|</span>
    ';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$this->func_bk($bdata,2));
$result.='
    <span class="_states xrt notext">|</span>
    ';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$this->func_bk($bdata,3));
$result.='
</div>';
}
elseif( (($this->func_bk($body,'type'))==('disp')) ) {

$result.='<div class="dialogline"> ';
if(!empty($this->macro['first']))
$result.=call_user_func($this->macro['first'],array(),$title, $this->func_bk($body,'title'));
$result.='
    <span class="_states xupdn notext">|</span>
    ';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$this->func_bk($bdata,0));
$result.='
    <span class="_states xltrt notext">|</span>
    ';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$this->func_bk($bdata,1));
$result.='
    <span class="_states xbarsepar notext">|</span>
    <span>Название фото</span>
    ';
if(!empty($this->macro['check']))
$result.=call_user_func($this->macro['check'],array(),$this->func_bk($bdata,4));
$result.='
</div>';
}
elseif( (($this->func_bk($body,'type'))==('animation')) ) {

$result.='<div class="dialogline"> ';
if(!empty($this->macro['first']))
$result.=call_user_func($this->macro['first'],array(),$title, $this->func_bk($body,'title'));
$result.='
    <span>Задержка, сек.</span>
    ';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$this->func_bk($bdata,0));
$result.='
    <span class="_states xbarsepar notext">|</span>
    <span>Вид.</span>
    ';
if(!empty($this->macro['select']))
$result.=call_user_func($this->macro['select'],array(),$this->func_bk($bdata,0), 'простой','затухание');
$result.='
    <span class="_states xltrt notext">|</span>
    ';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$this->func_bk($bdata,1));
$result.='
    <span class="_states xbarsepar notext">|</span>
    <span>Название фото</span>
    ';
if(!empty($this->macro['check']))
$result.=call_user_func($this->macro['check'],array(),$this->func_bk($bdata,4));
$result.='
</div>';
}
else {

$result.='
<div class="dialogline"> ';
if(!empty($this->macro['first']))
$result.=call_user_func($this->macro['first'],array(),$title, $this->func_bk($body,'title'));
$result.='
    '
    .$this->func_bk($body,'type')
    .'
</div>';
};
}};
    return $result;
}

function _pageparam(&$namedpar,$data=0 ,$values=0 ){
extract($namedpar);
$result='';
$loop1_array=ps($data);
if (is_array($loop1_array) && !empty($loop1_array)){
foreach($loop1_array as $title =>$body){

$bdata=$this->func_bk($body,'data');
if( !($body) ) {

$result.='
<div style="height:20px;"></div>';
}
elseif( (($this->func_bk($body,'type'))==('header')) ) {

$result.=($title)
    .'
';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$bdata, $values);
}
elseif( (($this->func_bk($body,'type'))==('checkbox')) ) {

$result.='<div class="checkboxline">
    '
    .($title)
    .'
    ';
if(!empty($this->macro['check']))
$result.=call_user_func($this->macro['check'],array(),$this->func_bk($bdata,4));
$result.='
</div>';
}
elseif( (($this->func_bk($body,'type'))==('title')) ) {

$result.='<div class="titleline">'
    .($title)
    .'</div>';
}
elseif( (($this->func_bk($body,'type'))==('str')) ) {

$result.='<div class="dialogline"> ';
if(!empty($this->macro['first']))
$result.=call_user_func($this->macro['first'],array(),$title, $this->func_bk($body,'title'));
$result.='
    ';
if(!empty($this->macro['longinput']))
$result.=call_user_func($this->macro['longinput'],array(),$bdata, $values);
$result.='
</div>';
}
elseif( (($this->func_bk($body,'type'))==('hidden')) ) {

if(!empty($this->macro['hidden']))
$result.=call_user_func($this->macro['hidden'],array(),$bdata, $values);
}
elseif( (($this->func_bk($body,'type'))==('link')) ) {

$result.='<div class="dialogline"> ';
if(!empty($this->macro['first']))
$result.=call_user_func($this->macro['first'],array(),$title, $this->func_bk($body,'title'));
$result.='
    ';
if(!empty($this->macro['longinput']))
$result.=call_user_func($this->macro['longinput'],array(),$bdata, $values);
$result.='
</div>';
}
elseif( (($this->func_bk($body,'type'))==('XxX')) ) {

$result.='<div class="dialogline"> ';
if(!empty($this->macro['first']))
$result.=call_user_func($this->macro['first'],array(),$title, $this->func_bk($body,'title'));
$result.='
    ';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$this->func_bk($bdata,0));
$result.='x';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$this->func_bk($bdata,1));
$result.='
</div>';
}
elseif( (($this->func_bk($body,'type'))==('ramka')) ) {

$result.='<div class="dialogline"> ';
if(!empty($this->macro['first']))
$result.=call_user_func($this->macro['first'],array(),$title, $this->func_bk($body,'title'));
$result.='
    <span class="_states xupdn notext">|</span>
    ';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$this->func_bk($bdata,0));
$result.='
    <span class="_states xltrt notext">|</span>
    ';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$this->func_bk($bdata,1));
$result.='
    <span class="_states xbarsepar notext">|</span>
    ';
if(!empty($this->macro['color']))
$result.=call_user_func($this->macro['color'],array(),$this->func_bk($bdata,3));
$result.='
    <span class="_states xbarsepar notext">|</span>
    ';
if(!empty($this->macro['check']))
$result.=call_user_func($this->macro['check'],array(),$this->func_bk($bdata,4));
$result.='
</div>';
}
elseif( (($this->func_bk($body,'type'))==('offset')) ) {

$result.='<div class="dialogline"> ';
if(!empty($this->macro['first']))
$result.=call_user_func($this->macro['first'],array(),$title, $this->func_bk($body,'title'));
$result.='
    <span class="_states xup notext">|</span>
    ';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$this->func_bk($bdata,0));
$result.='
    <span class="_states xdn notext">|</span>
    ';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$this->func_bk($bdata,1));
$result.='
    <span class="_states xlt notext">|</span>
    ';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$this->func_bk($bdata,2));
$result.='
    <span class="_states xrt notext">|</span>
    ';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$this->func_bk($bdata,3));
$result.='
</div>';
}
elseif( (($this->func_bk($body,'type'))==('disp')) ) {

$result.='<div class="dialogline"> ';
if(!empty($this->macro['first']))
$result.=call_user_func($this->macro['first'],array(),$title, $this->func_bk($body,'title'));
$result.='
    <span class="_states xupdn notext">|</span>
    ';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$this->func_bk($bdata,0));
$result.='
    <span class="_states xltrt notext">|</span>
    ';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$this->func_bk($bdata,1));
$result.='
    <span class="_states xbarsepar notext">|</span>
    <span>Название фото</span>
    ';
if(!empty($this->macro['check']))
$result.=call_user_func($this->macro['check'],array(),$this->func_bk($bdata,4));
$result.='
</div>';
}
elseif( (($this->func_bk($body,'type'))==('animation')) ) {

$result.='<div class="dialogline"> ';
if(!empty($this->macro['first']))
$result.=call_user_func($this->macro['first'],array(),$title, $this->func_bk($body,'title'));
$result.='
    <span>Задержка, сек.</span>
    ';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$this->func_bk($bdata,0));
$result.='
    <span class="_states xbarsepar notext">|</span>
    <span>Вид.</span>
    ';
if(!empty($this->macro['select']))
$result.=call_user_func($this->macro['select'],array(),$this->func_bk($bdata,0), 'простой','затухание');
$result.='
    <span class="_states xltrt notext">|</span>
    ';
if(!empty($this->macro['input']))
$result.=call_user_func($this->macro['input'],array(),$this->func_bk($bdata,1));
$result.='
    <span class="_states xbarsepar notext">|</span>
    <span>Название фото</span>
    ';
if(!empty($this->macro['check']))
$result.=call_user_func($this->macro['check'],array(),$this->func_bk($bdata,4));
$result.='
</div>';
}
else {

$result.='
<div class="dialogline"> ';
if(!empty($this->macro['first']))
$result.=call_user_func($this->macro['first'],array(),$title, $this->func_bk($body,'title'));
$result.='
    '
    .$this->func_bk($body,'type')
    .'
</div>';
};
}};
    return $result;
}

function _users(&$namedpar,$data=0 ){
extract($namedpar);
$result='';
$loop1_array=ps($data);
if (is_array($loop1_array) && !empty($loop1_array)){
foreach($loop1_array as $title =>$body){

$result.='
user<br>';
}};
    return $result;
}

function _edlinefinish(&$namedpar,$data=0 ){
extract($namedpar);
$result='
<span class="_states pseparator floatright notext">|</span>
<span class="_states pdel floatright notext"></span>
<span class="_states pseparator floatright notext">|</span>
<div class="floatright" style="text-align: center;margin:5px 10px;">
    <span class="_states p_up  notext">|</span><br>
    <span class="_states p_sqr  notext">|</span> <br>
    <span class="_states p_dn  notext">|</span>
</div>
<span class="_states pseparator floatright notext">|</span>
<span class="_states pdel floatright notext"></span>
<span class="_states pseparator floatright notext">|</span>';
    return $result;
}

function _page(&$namedpar,$data=0 ){
extract($namedpar);
$result='
<div class="page_title">
    <span class="_states bigparam notext" onclick="return ADMIN(\'pageParam\');" style="float:right;">param</span>
    <span class="_states bigtext notext" onclick="return ADMIN(\'appendText\');">Text</span>
    <span class="_states bigfoto notext" onclick="return ADMIN(\'appendFoto\');">foto</span>
    <span class="_states biglink notext" onclick="return ADMIN(\'appendLink\');">link</span>
    <span class="_states bigcomment notext" onclick="return ADMIN(\'appendComment\');">comment</span>
    <span class="_states bigcolumns notext" onclick="return ADMIN(\'appendColumns\');">columns</span>
</div>
<div class="page_subtitle valign-middle">
    <span data-menu="Добавить#add||Переименовать#renam|Удалить#del||Copy#copy|Paste#paste||Параметры#menuParam" style="position:relative;" class="checkbox"><input type="checkbox"><span style="margin: 0 5px 0 10px;" class="_states downcrn notext downmenu">X</span> </span> 

    <div class="titleline">';
$item=$this->callex('Sitemap','current');
$result.=$this->func_bk($item,'name')
    .' </div>
</div>
<div class="wrapper">
    <table class="fixed long">
        <col width=32>
        <col width=80>
        <col>
        <col width=100>
        <col width=40>
        <col width=40>
        <col width=40>';
$loop1_array=ps($data);$loop1_index=0;
if (is_array($loop1_array) && !empty($loop1_array)){
foreach($loop1_array as $elem){    $loop1_index++;

$result.='
        <tr class="editorline" data-element="{type:\''
    .$this->func_bk($elem,'type')
    .'\',eid:'
    .$this->func_bk($elem,'typeid')
    .'}">
            <td';
if( (($this->func_bk($elem,'type'))==('text')) ) {

}
elseif( (($this->func_bk($elem,'type'))==('foto')) ) {

}
else {

$result.=' class="hiddenitem"';
};
$result.='>
            <span class="_states pgreen floatleft">
        <span class="number">'
    .($loop1_index)
    .'</span>
        <input type="checkbox">
    </span></td>';
if( (($this->func_bk($elem,'type'))==('text')) ) {

$result.='
            <td></td>';
$xxx=$this->func_truncate($this->func_bk($elem,'data',0,'text'),100);
$result.='
            <td onclick="ADMIN(\'htmleditor\',this)">'
    .($this->filter_default($xxx,'Нажмите сюда для вставки нового текста'))
    .'
            </td>
            <td>
                <span class="_states pseparator floatright notext">|</span>
                <div class="xaction">
                    <a class="upload"
                       data-upload=\'{"action":"createPicture","mask":"picture"}\'
                       href="javascript:ADMIN(\'upload\',this)">Доб.
                    фото</a><span>('
    .($this->filter_default((isset($par['xx'])?$par['xx']:""),0))
    .')</span><br>
                    <a href="javascript:ADMIN(\'htmleditor\',this)">Подробнее</a>
                </div>
            </td>
            <td>
                <span class="_states pseparator floatright notext">|</span>
                <span class="_states pdel floatright notext"></span>
            </td>';
}
elseif( (($this->func_bk($elem,'type'))==('foto')) ) {

$result.='
            <td colspan=2>
            </td>
            <td>
                <span class="_states pseparator floatright notext">|</span>

                <div class="xaction"><a class="upload" data-admin="addFoto#{id:'
    .$this->func_bk($elem,'id')
    .',type:\''
    .$this->func_bk($elem,'type')
    .'\'}"
                                        href="javascript:ADMIN(\'upload\',this)">Доб.
                    фото</a><span>('
    .($this->filter_default((isset($par['xx'])?$par['xx']:""),0))
    .')</span>
                </div>
            </td>
            <td>
                <span class="_states pseparator floatright notext">|</span>
                <span class="_states pdel floatright notext"></span>
            </td>';
}
else {

$result.='
            <td colspan=4>
                unsupported type
            </td>';
};
$result.='
            <td>
                <span class="_states pseparator floatright notext">|</span>
                <div class="" style="text-align: center;margin:5px 10px;">
                    <span class="_states p_up  notext">|</span><br>
                    <span class="_states p_sqr  notext">|</span> <br>
                    <span class="_states p_dn  notext">|</span>

                </div>
            </td>
            <td>
                <span class="_states pseparator floatright notext">|</span>
                <span title="удалить" onclick="ADMIN(\'deleteEl\',this);" class="_states pdel floatright notext"></span>
            </td>
        </tr>
        <tr>
            <td colspan=6 style="height:3px;"></td>
        </tr>';
}};
$result.='
    </table>
</div>';
    return $result;
}

function _ (&$par){
$result='';
    return $result;
}
}