<?php
/**
 * this file is created automatically at "16 Mar 2013 13:23". Never change anything, 
 * for your changes can be lost at any time. 
 */ 
class tpl_fileman extends tpl_base {
function __construct(){
parent::__construct();
$this->macro['fileman']=array($this,'_fileman');
}

function _fileman(&$namedpar,$list=1,$pages=1,$type=1,$filter=0 ,$columns=2,$colsize=20){
extract($namedpar);
$result='
<form method="POST" action="">
    <table class="long thetable tahoma" ><tr>
        <th class="bblue">показать
            <a class="button';
if( (($type)==(1)) ) {

$result.=' pressed';
};
$result.='" href="/fileman/1"}}">Файлы</a>
            <a class="button';
if( (($type)==(2)) ) {

$result.=' pressed';
};
$result.='" href="/fileman/2">Картинки</a>
            <a class="button';
if( (($type)==(4)) ) {

$result.=' pressed';
};
$result.='" href="/fileman/4">Иконки</a>';
if( $filter ) {

$result.='
            <a class="button';
if( (($type)==(3)) ) {

$result.=' pressed';
};
$result.='" href="/fileman/3">фильтр('
    .(htmlspecialchars($filter))
    .')</a>';
};
$result.='
        </th>
        <th class="bblue align_center">
            <table class="tahoma"><tr><td class="uploader"><b>загрузить</b><input type="button" onclick="$must_save=false;__goto();" style="display:none;"></td></tr></table>
        </th>
        <th class="bblue align_left">
            <input type="checkbox" class="glass" id="aaa" name="ff">
            <input class="button" type="submit" name="delete" value="Удалить">
        </th>
    </tr>
        <tr><td colspan=2>
        </td></tr></table>
    <div id="pages">'
    .($pages)
    .'</div>
    <div id="fman_panel" style="width:100%;height:100%;overflow:auto;">
        <table class="long tahoma thetable"><tr>';
$xlist=$this->func_slice($list,$colsize);
$loop1_array=ps($this->func_range($columns));$loop1_index=0;$loop1_last=count($loop1_array);
if (is_array($loop1_array) && !empty($loop1_array)){
foreach($loop1_array as $cc){    $loop1_index++;

if( (($type)!=(4)) ) {

$result.='
            <th>Имя</th><th>размер</th><th>info</th>';
if( !($loop1_index==$loop1_last) ) {

$result.='<td></td>';
};
}
else {

$result.='
            <th></th><th>изображение</th>';
if( !($loop1_index==$loop1_last) ) {

$result.='<td></td>';
};
};
}};
$loop1_array=ps($this->func_range($colsize));$loop1_index=0;$loop1_cycle=array('even','odd');
if (is_array($loop1_array) && !empty($loop1_array)){
foreach($loop1_array as $xx){    $loop1_index++;

$result.='
            <tr class="'
    .($this->loopcycle($loop1_cycle))
    .'">';
$idx=($loop1_index-1);
$loop2_array=ps($this->func_range($columns));$loop2_index=0;$loop2_last=count($loop2_array);
if (is_array($loop2_array) && !empty($loop2_array)){
foreach($loop2_array as $cc){    $loop2_index++;

$col=$this->func_bk($xlist,($loop2_index-1));
$l=$this->func_bk($col,$idx);
if( (($type)!=(4)) ) {

if( $l ) {

$result.='
                <td ><nobr><label><input type="checkbox" class="glass select" value="'
    .(htmlspecialchars($this->func_bk($l,'url')))
    .'" name="ff[]">'
    .(htmlspecialchars($this->func_bk($l,'url')))
    .'</label></nobr></td><td>'
    .$this->func_bk($l,'size')
    .'</td><td>'
    .$this->func_bk($l,'info')
    .'</td>';
}
else {

$result.='
                <td >&nbsp;</td><td></td><td></td>';
};
}
else {

if( $l ) {

$result.='
                <td ><input type="checkbox" class="glass select" value="'
    .(htmlspecialchars($this->func_bk($l,'url')))
    .'" name="ff[]"></td>
                <td> <img alt="'
    .(htmlspecialchars($this->func_bk($l,'url')))
    .'" width="80" height="80" onload="checkImg(this,80,80)" src="../uploaded/'
    .(htmlspecialchars($this->func_bk($l,'url')))
    .'"</td>';
}
else {

$result.='
                <td >&nbsp;</td><td></td>';
};
};
if( !($loop2_index==$loop2_last) ) {

$result.='<td style="background-color:white"></td>';
};
}};
$result.='
            </tr>';
}};
$result.='
        </table>
    </div>

    <script type="text/javascript">
        element.add_event(element.$(\'aaa\'),\'click\',function(){
            var e = this;
            element.allClass(this.form,\'select\',function(el){
                el.checked = e.checked;
            })
            e=null;
        })

    </script>
    <div  style="display:none;">
        <div style="float:left;" id="fman_tpl">
            <table class="tahoma thetable"><tr>
                <th>Имя</th><th>размер</th><th>info</th>
            </tr><tr><td>%data%</td></tr>
            </table>
        </div>
        <div id="fman_column">
            <table>
                <tr class="%odd%">
                    <td ><nobr><label><input type="checkbox" class="glass select" value="%url%" name="ff[]">%name%</label></nobr></td><td>%size%</td><td>%info%</td>
                </tr>
            </table>
        </div>
    </div>

</form>';
    return $result;
}

function _ (&$par){
$result=($this->loopcycle($loop1_cycle))
    .'';
    return $result;
}
}