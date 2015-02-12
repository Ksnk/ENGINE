<?php
/**
 * this file is created automatically at "16 Mar 2013 14:46". Never change anything, 
 * for your changes can be lost at any time. 
 */ 
class tpl_main extends tpl_boilerplate {
function __construct(){
parent::__construct();
$this->macro['ul_li']=array($this,'_ul_li');
$this->macro['ulli']=array($this,'_ulli');
$this->macro['url']=array($this,'_url');
}

function _scripts(&$par){
$result='
<script src="'
    .(isset($par['rootsite'])?$par['rootsite']:"")
    .'js/main.1303161446.js"></script>
<script src="'
    .(isset($par['rootsite'])?$par['rootsite']:"")
    .'js/tiny_mce/jquery.tinymce.js"></script>
<script src="'
    .(isset($par['root'])?$par['root']:"")
    .'admin/uploadify/jquery.uploadify-3.1.js"></script>';
    return $result;
}

function _styles(&$par){
$result='';
    return $result;
}

function _ul_li(&$namedpar,$name=0,$item=0){
extract($namedpar);
$result='<li';
if( $this->func_bk($item,'active') ) {

$result.=' class="active"';
};
$result.='><span class="_states treepoint"></span>
    <a href="'
    .$this->func_bk($item,'url')
    .'">'
    .($this->filter_default($this->func_bk($item,'name'),$name))
    .'</a>';
if( $this->func_bk($item,'childs') ) {

$result.='
    <ul>';
$loop1_array=ps($this->func_bk($item,'childs'));
if (is_array($loop1_array) && !empty($loop1_array)){
foreach($loop1_array as $name1 =>$item1){

$result.='
        ';
if(!empty($this->macro['ul_li']))
$result.=call_user_func($this->macro['ul_li'],array(),$name1, $item1);
}};
$result.='</ul>';
};
$result.='
</li>';
    return $result;
}

function _ulli(&$namedpar,$element=0){
extract($namedpar);
$result='';
$loop1_array=ps($this->func_bk($element,'childs'));
if (is_array($loop1_array) && !empty($loop1_array)){
foreach($loop1_array as $child){

$result.='
<li class="';
if( $this->func_bk($child,'active') ) {

$result.='current';
};
if( !((($this->func_bk($child,'flags'))&(1))) ) {

$result.=' off';
};
$result.='"><span class="_states treepoint">

</span> <a data-context="'
    .$this->func_bk($child,'id')
    .'" href="'
    .$this->func_bk($child,'id')
    .'">'
    .$this->func_bk($child,'name')
    .'</a>';
if( ((count($this->func_bk($child,'childs')))>(0)) ) {

$result.='<ul>';
if(!empty($this->macro['ulli']))
$result.=call_user_func($this->macro['ulli'],array(),$child);
$result.='</ul>';
};
$result.='</li>';
}};
    return $result;
}

function _url(&$namedpar,$el=0){
extract($namedpar);
$result='';
if( (($this->func_bk($el,'id'))==(1)) ) {

}
elseif( $this->func_bk($el,'id') ) {

$result.=$this->func_bk($el,'id');
}
else {

$result.='xxx';
};
    return $result;
}

function _data(&$par){
$result='
<table id="xsite-adm" class="tahoma10 state_tree xsite-adm"
       style="background:url('
    .(isset($par['rootsite'])?$par['rootsite']:"")
    .'img/logo.png) 17px 62px no-repeat;table-layout:fixed;height:100%;width:100%;min-height:100px;">
    <col width="218">
    <col width="28">
    <col>
    <tr>
        <th style="text-align:left;height:44px;" colspan=3 class="blackbg"> 
            <div style="height:44px;overflow:hidden;">
            <span class="_states logout" style="float:right;margin:15px 20px 0 0;" title="Выход"
                  onclick="return ADMIN(\'logout\');"></span>
                <span class="_states help" style="float:right;margin:15px 14px 0 0;" title="Помощь"
                      onclick="return ADMIN(\'help\');"></span> 
            <div style="float:right;margin:14px 40px 0 0">
                <form id="searchform" onsubmit="return ADMIN(\'search\',{val:$(\'input[name=search]\',this).val()});">
                    <div style="position:relative;">
                        <input name="search" class="input11" type="text" placeholder="Поиск">
                        <input type="submit" class="_states arrowrt" value="">
                    </div>
                </form>
            </div> 
            <div style="margin:14px 0 0 38px;">';
$loop1_array=ps($this->callex('Sitemap','navbar'));$loop1_index=0;
if (is_array($loop1_array) && !empty($loop1_array)){
foreach($loop1_array as $item){    $loop1_index++;

if( !($loop1_index==1) ) {

$result.='&nbsp;&nbsp;-&nbsp;&nbsp;';
};
$result.='<a href="';
if(!empty($this->macro['url']))
$result.=call_user_func($this->macro['url'],array(),$item);
$result.='">'
    .($this->filter_default($this->func_bk($item,'name'),'xxx'))
    .'</a>';
}};
$result.='
            </div>
            </div>
        </th>
    </tr>
    <tr>
        <td>
            <div style="display:table;height:100%;width:100%;">
                <div style="display:table-row;height:134px;">

                </div>

                <div class="rowheader collapsed" style="display:table-row;height:30px;">
                    <span class="_states sidebarroll"></span>

                    <div> Модули</div>
                </div>
                <div id="modulelist" class="rowdata" style="display:none;overflow:hidden;height:100%;">
                    <ul style="min-height:60px;" class="treemenu"
                        data-contents="Развернуть#open|Свернуть#open||Переименовать#renam|Преобразовать в[Группу#trn_xgroup|Префикс#trn_yprefix]|Удалить#del||Copy#copy|Paste#paste">';
$loop1_array=ps($this->callex('Main','modulelist'));
if (is_array($loop1_array) && !empty($loop1_array)){
foreach($loop1_array as $name =>$item){

$result.='
                        ';
if(!empty($this->macro['ul_li']))
$result.=call_user_func($this->macro['ul_li'],array(),$name, $item);
}};
$result.='</ul>

                </div>
                <div class="rowheader expanded" style="display:table-row;height:30px;"
                     data-contents="Добавить#add||Copy#copy|Paste#paste||Параметры#showpar">
                    <span class="_states sidebarroll"></span>';
$map=$this->callex('Sitemap','getSiteMap');
$result.='
                    <div class="rowtitle" data-context="'
    .$this->func_bk($map,1,'id')
    .'"> Разделы</div>
                </div>

                <div  class="rowdata" style="display:table-row;overflow:auto;">
                    <ul class="treemenu"
                         data-contents="Добавить#add||Переименовать#renam|Удалить#del||Copy#copy|Paste#paste||Параметры#menuParam">
                        ';
if(!empty($this->macro['ulli']))
$result.=call_user_func($this->macro['ulli'],array(),$this->func_bk($map,1));
$result.='
                    </ul>
                </div>
            </div>

        </td>
        <td></td>
        <td>
            <div style="height:100%;overflow:auto;">
                '
    .(isset($par['data'])?$par['data']:"")
    .'
            </div>
        </td> 
    </tr>
    <tr>
        <th style="height:60px;" colspan=3>
            <a style="float:right;margin:0 60px 0 0;" class="_states xilenium" href="#">
                &nbsp;
            </a>
            <a style="float:left;margin:10px 180px 0 8px;" class="_states param" href="#"
               onclick="return ADMIN(\'showpar\');">
                &nbsp;
            </a>
            <button class="_states dbutton" style="float:left;margin:10px 10px 0 0;" title="Выход" disabled="disabled"
                    onclick="return ADMIN(\'save\');">
                Сохранить
            </button>
            <button class="_states dbutton" style="float:left;margin:10px 10px 0 0;" title="Выход"
                    onclick="return ADMIN(\'preview\');">Посмотреть
            </button> 
        </th> 
    </tr>
</table> 
<!--  ---- point::plugins_html ----  -->
<blockquote id="tooltip" class="nodeDescription nodeDescriptionTooltip baseHtml xenTooltip nodeDescriptionTip"
            id="nodeDescription-4" style="position: absolute; top: 260px; left: 355px; display: none;"><span
    class="text"></span><span
    class="arrow"></span></blockquote>

<form id="uploader" class="nocontext" style="position:absolute;top:0;left:0">
    <input type="file" id="upload_input" name="file[]" multiple="multiple" >
</form>

<div style="display:none;" id="colorpicker">
    <canvas class="rule"></canvas>
    <canvas class="pane"></canvas>
    <div></div>
    <button>ok</button>
</div>

<!--  -->
<div id="htmleditor" class="modal dialog tahoma10" style="display:none;">
    <form action="" method="POST" style="width:100%;height:100%;" onsubmit="return false;">
        <input type="hidden" name="handler" value="Page::attr">
        <input type="hidden" name="eid" value="0">
        <input type="hidden" name="type" value="">
    <table style="width:100%;height:100%;">
        <tr>
            <td height=40 class="header"><span class="notext _states xexit" onclick="ADMIN(\'close\');">x</span><span class="title"></span><span class="title1"></span></td>
        </tr>
        <tr>
            <td class="inner" style="padding:0;">
                <textarea name="text" class="tinymce" style="margin:0;height:100%;width:98%"></textarea>
            </td>
        </tr>
        <tr>
            <td height=40 class="footer">
                <button class="_states dbutton" onclick="ADMIN(\'saveform\',this);">Сохранить</button>
            </td>
        </tr>
    </table>
    </form>
</div>
<div class="hidden">
    <div id="slider">
    </div>
    <div id="contextmenu" class="toolbox tahoma menu cltext"
         style="width:200px;background:white; position:absolute; z-index:23;padding:5px; border: 1px solid #dddddd; display:none;"></div>
        <div id="alert" class="modal alert tahoma10">
        <div class="message">What?</div>
        <button class="_states greenbutton" onclick="ADMIN(\'ok\');">ok</button>
        <button class="_states greenbutton" onclick="ADMIN(\'close\');">Cancel</button>
    </div>

    <div id="confirm" class="modal alert tahoma10">
        <div class="message">What?</div>
        <input type="text" value="" class="text"> <br>
        <button class="_states greenbutton" onclick="ADMIN(\'ok\');">ok</button>
        <button class="_states greenbutton" onclick="ADMIN(\'close\');">Cancel</button>
    </div>



    <div id="dialog" class="modal dialog tahoma10">
        <form action="" method="POST" style="width:100%;height:100%;" onsubmit="return false;">
        <table style="width:100%;height:100%;">
            <tr>
                <td height=40 class="header"><span class="notext _states xexit" onclick="ADMIN(\'close\');">x</span><span class="title"></span><span class="title1"></span></td>
            </tr>
            <tr>
                <td class="inner">
                    <div style="overflow:auto;height:100%;">

                    </div>
                </td>
            </tr>
            <tr>
                <td height=40 class="footer"><button class="_states dbutton" onclick="ADMIN(\'saveform\',this);">Сохранить</button>
                </td>
            </tr>
        </table>
        </form>
    </div>

    <div id="add_aro" title="Добавить операцию">
        <form>
            <input type="hidden" name="type" value="action">
            <label>
                <input type="text" name="value"/>
            </label>
        </form>
    </div>
    <div id="add_axo_group" title="Введите данные">
        <form>
            <label>
                <select class="ajax" name="type"
                        onchange="$(this).closest(\'form\').find(\'.tab\').hide().end().find(\'.x_\'+this.value).show();"
                        >
                    <option value="axo_group">группа</option>
                    <option value="axo">пользователь</option>
                    <option value="right">правило</option>
                </select>
            </label>

            <label>
                <input type="text" class="tab x_axo x_axo_group" name="name"/>
            </label>
            <label>
                <select class="tab x_right" name="object">
                    <option>*</option>
                </select>
            </label>
            <label>
                <select class="tab x_right" name="action"></select>
            </label>
            <label>
                <select class="tab x_right" name="allow">
                    <option value="0">запретить</option>
                    <option value="1">разрешить</option>
                </select>
            </label>
            <label>
                <input class="tab x_right" type="text" name="value"/>
            </label>
        </form>
    </div>
    <div id="add_aхo" title="Правило доступа">
        <form>
            <input type="hidden" name="type" value="right">
            <label>
                <select name="action"></select>
            </label>
            <label>
                <select name="allow">
                    <option value="0">запретить</option>
                    <option value="1">разрешить</option>
                </select>
            </label>
            <label>
                <input type="text" name="value"/>
            </label>
        </form>
    </div>

</div>
<div id="debug" style="max-height:100px;"></div>';
    return $result;
}
}