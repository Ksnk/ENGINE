<?php
/**
 * this file is created automatically at "16 Mar 2013 14:46". Never change anything, 
 * for your changes can be lost at any time. 
 */ 
class tpl_boilerplate extends tpl_base {
function __construct(){
parent::__construct();
}

function _header_css(&$par){
$result='
        <link rel="stylesheet" href="'
    .(isset($par['rootsite'])?$par['rootsite']:"")
    .'css/main.1303161446.css">';
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
    .'js/main.1303161446.js"></script>';
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
<body onselectstart="return false;" data-id="'
    .(isset($par['pageid'])?$par['pageid']:"")
    .'" data-root="'
    .(isset($par['rootsite'])?$par['rootsite']:"")
    .'">
<!--[if lt IE 7]>
<p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser
    today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better
    experience this site.</p>
<![endif]-->'
    .($this->_data($par))
    .' 
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