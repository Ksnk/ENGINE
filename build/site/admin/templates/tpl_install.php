<?php
/**
 * this file is created automatically at "16 Mar 2013 13:31". Never change anything, 
 * for your changes can be lost at any time. 
 */ 
class tpl_install extends tpl_boilerplate {
function __construct(){
parent::__construct();
$this->macro['element']=array($this,'_element');
$this->macro['step3']=array($this,'_step3');
$this->macro['step4']=array($this,'_step4');
}

function _header_css(&$par){
$result='';
    return $result;
}

function _styles(&$par){
$result='
<style>
    body {
        font-family: Arial, Tahoma, Helvetica;
        background-color: #eee;
        margin: 0;
    }

    .main {
        position: relative;
        top: 50px;
        width: 740px;
        display: block;
        height: 350px;
        left: auto;
        right: auto;
        background-color: #f8f8f8;
        border: 1px solid #000;
        font-size: 11pt;
        margin: 0 auto;
        padding: 30pt;
    }

    .top {
        font-size: 20pt;
    }

    .tabs {
        margin: 15px auto;
        height: 360px;
        padding: 0;
        width: 90%;
        min-width: 600px;
        position: relative;
        overflow: hidden;
    }

    .tabs>dd {
        display: none;
        position: absolute;
        top: 40px;
        left: 0;
    }

    .tabs>dt, .tabs>dd {
        padding: 0;
        margin: 0;
    }

    #tabs>dt {
        float: left;
        cursor: pointer;
        padding: 0 10px;
        margin-bottom: -20px;
        height: 40px;
        overflow: hidden;
        white-space: nowrap;
        text-align: center;
        text-overflow: ellipsis;
        o-text-overflow: ellipsis;
        background: #fff;
        border: 1px solid darkgray;
        border-bottom: none;

        border-top-left-radius: 6px;

        -moz-border-top-left-radius: 6px;
        -webkit-border-top-left-radius: 6px;
        -o-border-top-left-radius: 6px;

        border-top-right-radius: 6px;

        -moz-border-top-right-radius: 6px;
        -webkit-border-top-right-radius: 6px;
        -o-border-top-right-radius: 6px;

        line-height: 20px;
        font-size: 12px;
    }

    #tabs>dt.disabled {
        color: lightgray;
        font-weight: normal;
    }

    #tabs>dt.disabled:hover {
        color: lightgray;
        font-weight: normal;
        background: white;
    }

    #tabs>dt:hover {
        background: darkgray;

        color: #fff;
    }

    #tabs>dd>form>div {
        background: lightgray;
    }

    #tabs>dd {
        width: 100%;
        height: 280px;
        overflow: hidden;
        text-align: justify;
    }

    #tabs>dd>form>div {
        border: 1px solid darkgray;
        overflow: hidden;

        -moz-border-bottom-left-radius: 6px;
        -webkit-border-bottom-left-radius: 6px;
        -o-border-bottom-left-radius: 6px;
        border-bottom-left-radius: 6px;

        -moz-border-bottom-right-radius: 6px;
        -webkit-border-bottom-right-radius: 6px;
        -o-border-bottom-right-radius: 6px;
        border-bottom-right-radius: 6px;

        padding: 20px 10px;
        overflow: hidden;

        box-shadow: 3px 3px 7px rgba(122, 122, 122, 0.4), -3px 3px 7px rgba(122, 122, 122, 0.4);
        -moz-box-shadow: 3px 3px 7px rgba(122, 122, 122, 0.4), -3px 3px 7px rgba(122, 122, 122, 0.4);
        -webkit-box-shadow: 3px 3px 7px rgba(122, 122, 122, 0.4), -3px 3px 7px rgba(122, 122, 122, 0.4);
        -o-border-box-shadow: 3px 3px 7px rgba(122, 122, 122, 0.4), -3px 3px 7px rgba(122, 122, 122, 0.4);

    }

    .activeTab {
        border-bottom: none !important;
        background: darkgray !important;
        color: #fff;
        font-weight: bold;
    }

    .activeTab + dd {
        display: block;
    }

    #error {
        position: absolute;
        left: 50%;
        bottom: 10px;
        border: 1px solid darkgray;
        padding: 20px 15px;
    }

    #log {
        position: absolute;
        height: 300px;
        overflow: auto;
        background: rgba(255, 255, 255, 0.9);
        left: 60px;
        width: 600px;
        bottom: 10px;
        border: 1px solid darkgray;
        padding: 20px 15px;
    }
    #log pre{
        font-size:11px;;
    }

</style>';
    return $result;
}

function _element(&$namedpar,$name=0,$element=0){
extract($namedpar);
$result='';
if( (($this->func_bk($element,'type'))==('text')) ) {

$result.='<tr>
    <td>'
    .$this->func_bk($element,'label')
    .'</td>
    <td><input type="text" name="'
    .(htmlspecialchars($name))
    .'"';
if( $this->func_bk($element,'value') ) {

$result.=' value="'
    .(htmlspecialchars($this->func_bk($element,'value')))
    .'"';
};
$result.='></td>
</tr>';
}
elseif( (($this->func_bk($element,'type'))==('password')) ) {

$result.='<tr>
    <td>'
    .$this->func_bk($element,'label')
    .'</td>
    <td><input type="password" name="'
    .($name)
    .'"';
if( $this->func_bk($element,'value') ) {

$result.=' value="'
    .(htmlspecialchars($this->func_bk($element,'value')))
    .'"';
};
$result.='></td>
</tr>';
}
else {

$result.='
<tr>
    <td colspan=2>'
    .($element)
    .'</td>
</tr>';
};
    return $result;
}

function _step3(&$namedpar,$text=0,$dir=0,$installed=0){
extract($namedpar);
$result='
<input style="float:right;" value="Diff"  type="submit"name="show_diff"><input style="float:right;" type="submit" value="Save" name="save">'
    .($text)
    .'<br>

<div class=\'column\'>';
$loop1_array=ps($dir);$loop1_index=0;
if (is_array($loop1_array) && !empty($loop1_array)){
foreach($loop1_array as $fl =>$nm){    $loop1_index++;

$indd=($loop1_index-1);
if( (($this->func_bk($nm,'type'))==('multy')) ) {

$result.='
    <label><input type="checkbox" name="install['
    .($indd)
    .']" value="'
    .($fl)
    .'"';
if( $this->func_bk($installed,$fl) ) {

$result.='
                  checked="checked"';
};
$result.='> '
    .($fl)
    .':</label><select name="aliace['
    .($indd)
    .']">';
$loop2_array=ps($this->func_bk($nm,'element'));
if (is_array($loop2_array) && !empty($loop2_array)){
foreach($loop2_array as $elem){

$result.='
    <option';
if( (($this->func_bk($installed,$fl))==($elem)) ) {

$result.=' selected';
};
$result.='>'
    .($elem)
    .'</option>';
}};
$result.='
</select>
    <br>';
}
elseif( (($fl)!=($nm)) ) {

$result.='
    <label><input type="checkbox" name="install['
    .($indd)
    .']" value="'
    .($fl)
    .'"';
if( $this->func_bk($installed,$fl) ) {

$result.='
                  checked="checked"';
};
$result.='> '
    .($fl)
    .':'
    .($nm)
    .'
    <input type="hidden" name="aliace['
    .($indd)
    .']" value="'
    .($nm)
    .'">
    </label><br>';
}
else {

$result.='
    <label><input type="checkbox" name="install['
    .($indd)
    .']" value="'
    .($fl)
    .'"';
if( $this->func_bk($installed,$fl) ) {

$result.='
                  checked="checked"';
};
$result.='> '
    .($fl)
    .'</label><br>';
};
}};
$result.='
</div>';
    return $result;
}

function _step4(&$namedpar,$text=0,$dir=0,$installed=0){
extract($namedpar);
$result='
<input type="submit" style="float:right;" value="Diff" name="show_diff"><input style="float:right;" type="submit" value="Save" name="save">'
    .($text)
    .'<br>

<div class=\'column\'>';
$loop1_array=ps($dir);$loop1_index=0;
if (is_array($loop1_array) && !empty($loop1_array)){
foreach($loop1_array as $fl =>$nm){    $loop1_index++;

$indd=($loop1_index-1);
if( (($this->func_bk($nm,'type'))==('multy')) ) {

$result.='
    <label><input type="checkbox" name="install['
    .($indd)
    .']" value="'
    .($fl)
    .'"';
if( $this->func_bk($installed,$fl) ) {

$result.='
                  checked="checked"';
};
$result.='> '
    .($fl)
    .':</label><select name="aliace['
    .($indd)
    .']">';
$loop2_array=ps($this->func_bk($nm,'element'));
if (is_array($loop2_array) && !empty($loop2_array)){
foreach($loop2_array as $elem){

$result.='
    <option';
if( (($this->func_bk($installed,$fl))==($elem)) ) {

$result.=' selected';
};
$result.='>'
    .($elem)
    .'</option>';
}};
$result.='
</select>
    <br>';
}
elseif( (($fl)!=($nm)) ) {

$result.='
    <label><input type="checkbox" name="install['
    .($indd)
    .']" value="'
    .($fl)
    .'"';
if( $this->func_bk($installed,$fl) ) {

$result.='
                  checked="checked"';
};
$result.='> '
    .($fl)
    .':'
    .($nm)
    .'
        <input type="hidden" name="aliace['
    .($indd)
    .']" value="'
    .($nm)
    .'">
    </label><br>';
}
else {

$result.='
    <label><input type="checkbox" name="install['
    .($indd)
    .']" value="'
    .($fl)
    .'"';
if( $this->func_bk($installed,$fl) ) {

$result.='
                  checked="checked"';
};
$result.='> '
    .($fl)
    .'</label><br>';
};
}};
$result.='
</div>';
    return $result;
}

function _data(&$par){
$result='
<div class="main">
    <div class="top">Установка x-Site</div>
    <dl id="tabs" class="tabs">';
$loop1_array=ps($par['tabs']);$loop1_index=0;
if (is_array($loop1_array) && !empty($loop1_array)){
foreach($loop1_array as $step =>$tab){    $loop1_index++;

$result.='
        <dt id="'
    .($step)
    .'"';
if( !($loop1_index==1) ) {

$result.=' class="disabled"';
};
$result.='>
            <span class="pair">
                <span>'
    .$this->func_bk($tab,'title')
    .'</span><br>
                <span class="status">'
    .$this->func_bk($tab,'status')
    .'</span>
            </span>
        </dt>
        <dd>
            <form method="post" action="">
                <div>
                    <input type="hidden" name="handler" value="::'
    .($step)
    .'">
                    <input type="hidden" name="action">';
if( $this->func_bk($tab,'form') ) {

$result.='
                    <table style="width:100%">';
$loop2_array=ps($this->func_bk($tab,'form'));
if (is_array($loop2_array) && !empty($loop2_array)){
foreach($loop2_array as $name =>$elem){

if(!empty($this->macro['element']))
$result.=call_user_func($this->macro['element'],array(),$name, $elem);
}};
$result.='
                    </table>';
};
if( $this->func_bk($tab,'remote') ) {

$result.='
                    <div class="remote" style="height:200px; overflow:auto;" data-url="'
    .$this->func_bk($tab,'remote')
    .'">

                    </div>';
};
$result.='
                </div>
                <input style="position:absolute;bottom:0;left:0;" type="submit" name="submit"
                       value="'
    .($this->filter_default($this->func_bk($tab,'button'),'Продолжить'))
    .'">
            </form>

        </dd>';
}};
$result.='
    </dl>
    <div id="error" style="display:none;"></div>
    <div id="log" style="display:none;">
        <pre class="content"></pre>
    </div>
    <!--
  <div class="status_bar">
      <div class="pair">
          <span id="item0" style="color: rgb(0, 0, 0); opacity: 1; ">Проверка хостинга</span><br>
          <span id="status0">еще не производилась</span>
      </div>
      <div id="arrow0">→</div>
      <div class="pair">
          <span id="item1">Проверка базы данных</span><br>
          <span id="status1">еще не производилась</span>
      </div>
      <div id="arrow1">→</div>
      <div class="pair">
          <span id="item2">Установка x-Site</span><br>
          <span id="status2">сначала все проверим</span>
      </div>
      <div style="clear:both;"></div>
  </div>  -->

</div>';
    return $result;
}

function _scripts(&$par){
$result='
<script type="text/javascript">

    function ajax(o) {
        $.ajax({
            url:o.url,
            data:o.data || null,
            type:o.type || \'get\',
            complete:function (xmr, status) {
                var idx = 0, realtext = \'\', data, text = xmr.responseText.split(\'}\');
                while (idx < text.length && typeof(data) == typeof(undefined)) {
                    realtext += text[idx++] + \'}\';
                    try {
                        data = JSON.parse(realtext);
                    } catch (e) {
                    }
                    // выковыриваем
                }
                if (typeof(data) != typeof(undefined)) {
                    o.complete && o.complete(data);

                    if (data.error) {
                        $(\'#error\').html(data.error).show(\'slow\');
                    } else {
                        $(\'#error:visible\').html(\'\').hide(\'slow\');
                    }
                    if (data.debug) {
                        if (!!window.console)
                            console.log(\'debug: \' + data.debug);
                    }
                    if (data.log) {
                        $(\'.content\', \'#log\').html(data.log);
                        $(\'#log\').show(\'slow\');
                    }
                } else {
                    $(\'#error\').html(\'server error\').show(\'slow\');
                }
            }
        })
    }

    $(function () {
        $(\'dl dt\').click(function () {
            if ($(this).is(\'.disabled\'))
                return false;
            $(\'dt\').not(this).removeClass(\'activeTab\');
            $(this).addClass(\'activeTab\');
            var self = $(\'.remote\', $(this).next(\'dd\')).eq(0).each(function () {
                ajax({
                    url:\'?do=\'+$(this).attr(\'data-url\'),
                    complete:function (data) {
                        if (data.data) {
                            $(self).html(data.data);
                        }

                    }
                })
            });
        });
        if ($(\'dt.activeTab\').length == 0)
            $(\'dl dt\').eq(0).trigger(\'click\');

        $(\'#log\').click(function () {
            $(this).hide();
        })

        $(\'#log\').click(function () {
            $(this).hide();
        })

        $(document).on(\'click\', \'input[type="submit"]\', function () {
            var name = $(this).attr(\'name\')||\'\';
            $(\'input[name="action"]\',$(this).parents(\'form\').eq(0))
            .attr(\'value\', name);
        })

        $(\'form\').submit(function () {
            var $form = $(this);
            ajax({
                url:$form.attr(\'action\'),
                data:$form.serialize(),
                type:\'post\',
                complete:function (data) {
                    if (data.allow) {
                        var t = $(\'#\' + data.allow.join(\', #\'));
                        $(\'dt\').not(t).addClass(\'disabled\');
                        t.removeClass(\'disabled\');
                    }
                    if (data.goto) {
                        $(\'#\' + data.goto).removeClass(\'disabled\').trigger(\'click\');
                    }
                }
            });
            return false;
        })
    });

</script>';
    return $result;
}
}