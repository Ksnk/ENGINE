<?php
/**
 * this file is created automatically at "21 May 2013 22:34". Never change anything, 
 * for your changes can be lost at any time. 
 */ 
class tpl_main extends tpl_boilerplate {
function __construct(){
parent::__construct();
}

function _styles(&$par){
$result='';
    return $result;
}

function _data(&$par){
$result='
<table class="fixed long wide">
    <col width="100px">
    <col width="10%">
    <col width="200px">
    <col width="10%">
    <col width="50px">
    <col width="10%">
    <col width="420px">
    <col width="50%">
    <col width="20%">
    <col width="130">
    <tr style="background:url('
    .(isset($par['root'])?$par['root']:"")
    .'img/gray_bg.png);">
        <td colspan=6 style="height:84px;">
            <a href="'
    .(isset($par['root'])?$par['root']:"")
    .'/" class="_states mail" style="float:right; margin:10px 10px 0 0;">&nbsp;</a>
            <a href="'
    .(isset($par['root'])?$par['root']:"")
    .'/" class="_states sitemap" style="float:right; margin:10px 60px 0 0;">&nbsp;</a>
            <a href="'
    .(isset($par['root'])?$par['root']:"")
    .'/" class="_states home" style="float:right; margin:10px 60px 0 0;">&nbsp;</a>
        </td>
        <td colspan=4>
            <a href="'
    .(isset($par['root'])?$par['root']:"")
    .'/" class="_states online" style="float:right; margin:15px 20px 0 0;">&nbsp;</a>
        </td>
    </tr>
    <tr style="background:url('
    .(isset($par['root'])?$par['root']:"")
    .'img/gray_bg.png);">
        <td colspan=6 style="background:black;height:90px;">
            <a href="'
    .(isset($par['root'])?$par['root']:"")
    .'/" class="_states engine" style="float:right; margin:15px 20px 0 0;">&nbsp;</a>
        </td>
        <td colspan=4>
        </td>
    </tr>
    <tr>
        <td colspan=10 style="height:30px;">';
$map=$this->callex('Sitemap','getSiteMap');
$result.='
            <div id="menu_container" style="height:30px;">
                <ul class="treemenu ulmenu">
                ';
if(!empty($this->macro['ulli']))
$result.=call_user_func($this->macro['ulli'],array(),$this->func_bk($map,1));
$result.='
                </ul>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan=2></td>
        <td colspan=2>{::news - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - </td>
        <td colspan=2></td>
        <td colspan=2>{data  - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</td>
        <td colspan=2></td>
    </tr>

    <tr>
        <td colspan=10  style="height:50px;background:url('
    .(isset($par['root'])?$par['root']:"")
    .'img/gray_bg.png);">
            <a class="_states xilen" style="float:right;margin:5px 60px 0 0;" href="#">&nbsp;
            </a>
        </td>
    </tr>
</table>
<div id="debug"></div>';
    return $result;
}
}