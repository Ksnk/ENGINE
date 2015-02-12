<?php
/**
 * this file is created automatically at "16 Mar 2013 13:23". Never change anything, 
 * for your changes can be lost at any time. 
 */ 
class tpl_macros extends tpl_base {
function __construct(){
parent::__construct();
$this->macro['output_element']=array($this,'_output_element');
}

function _output_element(&$namedpar,$element=0,$ingroup=0){
extract($namedpar);
$result='';
if( (('fieldset')==($this->func_bk($element,'type'))) ) {

$result.='
<fieldset'
    .($this->func_bk($element,'attributes'))
    .'>
    <legend>'
    .$this->func_bk($element,'label')
    .'</legend>';
$loop1_array=ps($this->func_bk($element,'elements'));
if (is_array($loop1_array) && !empty($loop1_array)){
foreach($loop1_array as $child){

$result.='
    ';
if(!empty($this->macro['output_element']))
$result.=call_user_func($this->macro['output_element'],array(),$child);
}};
$result.='
</fieldset>';
}
elseif( defined($this->func_bk($element,'elements')) ) {

$result.='
<div class="row">
    <label class="element">';
if( $this->func_bk($element,'required') ) {

$result.='<span class="required">* </span>';
};
$result.='
        '
    .$this->func_bk($element,'label')
    .'
    </label>

    <div class="element group';
if( $this->func_bk($element,'error') ) {

$result.=' error';
};
$result.='">';
if( $this->func_bk($element,'error') ) {

$result.='<span class="error">'
    .$this->func_bk($element,'error')
    .'<br/></span>';
};
$loop1_array=ps($this->func_bk($element,'elements'));$loop1_index=0;
if (is_array($loop1_array) && !empty($loop1_array)){
foreach($loop1_array as $child){    $loop1_index++;

$result.='
        ';
if(!empty($this->macro['output_element']))
$result.=call_user_func($this->macro['output_element'],array(),$child, (isset($par['true'])?$par['true']:""));
$result.='
        '
    .($this->func_bk($element,'separator',($loop1_index-1)));
}};
$result.='
    </div>
</div>';
}
elseif( $ingroup ) {

$result.='
'
    .($this->func_bk($element,'html'));
}
else {

$result.='
<div class="row">
    <label for="'
    .$this->func_bk($element,'id')
    .'" class="element">';
if( $this->func_bk($element,'required') ) {

$result.='<span class="required">* </span>';
};
$result.='
        '
    .$this->func_bk($element,'label')
    .'
    </label>

    <div class="element';
if( $this->func_bk($element,'error') ) {

$result.=' error';
};
$result.='">';
if( $this->func_bk($element,'error') ) {

$result.='<span class="error">'
    .$this->func_bk($element,'error')
    .'<br/></span>';
};
$result.='
        '
    .($this->func_bk($element,'html'))
    .'
    </div>
</div>';
};
    return $result;
}

function _ (&$par){
$result='';
    return $result;
}
}