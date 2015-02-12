<?php
/**
 * this file is created automatically at "25 May 2013 17:26". Never change anything, 
 * for your changes can be lost at any time. 
 */ 
class tpl_boilerplate extends tpl_base {
function __construct(){
parent::__construct();
$this->macro['ulli']=array($this,'_ulli');
}

function _header_css(&$par){
$result='
        <link rel="stylesheet" href="'
    .(isset($par['rootsite'])?$par['rootsite']:"")
    .'css/main.1305251726.css">';
    return $result;
}

function _styles(&$par){
$result='';
    return $result;
}

function _data(&$par){
$result='';
    return $result;
}

function _scripts(&$par){
$result='
<script src="'
    .(isset($par['rootsite'])?$par['rootsite']:"")
    .'js/main.1305251726.js"></script>';
    return $result;
}

function _ulli(&$namedpar,$element=0){
extract($namedpar);
$result='';
$loop1_array=ps($this->func_bk($element,'childs'));
if (is_array($loop1_array) && !empty($loop1_array)){
foreach($loop1_array as $child){

if( (($this->func_bk($child,'flags'))&(1)) ) {

$result.='
<li';
if( $this->func_bk($child,'active') ) {

$result.=' class="current"';
};
$result.='>
     <a  href="'
    .(isset($par['root'])?$par['root']:"")
    .($this->filter_default($this->func_bk($child,'url'),$this->func_bk($child,'id')))
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
};
$result.=(isset($par['endif'])?$par['endif']:"");
}};
    return $result;
}

function _ (&$par){
$result='<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>'
    .(isset($par['title'])?$par['title']:"")
    .'</title>';
if( (isset($par['description']) && !empty($par['description'])) ) {

$result.='<meta name="description" content="'
    .(isset($par['description'])?$par['description']:"")
    .'">';
};
$result.=($this->_header_css($par))
    .' 
    <base href="'
    .(isset($par['rootsite'])?$par['rootsite']:"")
    .'">'
    .($this->_styles($par))
    .'

</head>
<body onselectstart="return false;" data-root="'
    .(isset($par['rootsite'])?$par['rootsite']:"")
    .'">
<!--[if lt IE 7]>
<p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser
    today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better
    experience this site.</p>
<![endif]-->'
    .($this->_data($par))
    .'

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script>window.jQuery || document.write(\'<script src="'
    .(isset($par['rootsite'])?$par['rootsite']:"")
    .'../js/vendor/jquery-1.8.2.min.js"><\\/script>\')</script>'
    .($this->_scripts($par))
    .' 
</body>
</html>';
    return $result;
}
}