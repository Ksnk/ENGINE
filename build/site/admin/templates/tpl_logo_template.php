<?php
/**
 * this file is created automatically at "16 Mar 2013 13:23". Never change anything, 
 * for your changes can be lost at any time. 
 */ 
class tpl_logo_template extends tpl_boilerplate {
function __construct(){
parent::__construct();
}

function _styles(&$par){
$result='
<style type="text/css">
    html {
        height: 100%;
        overflow: auto;
        margin: 0;
    }

    body {
        height: 100%;
        position: relative;
        overflow: hidden;
        background-color: white;
        margin: 0;
    }

    #loginform {
        position: absolute;
        z-index: 2;
        left: 50%;
        top: 50%;
        width: 290px;
        height: 316px;
        text-align: left;
        cursor: default;
        margin: -221px 0 0 -145px;
        padding: 200px 0 0 30px;
        background: url('
    .(isset($par['rootsite'])?$par['rootsite']:"")
    .'img/login_form.png) no-repeat;
    }

    #loginform label {
        color: white;
    }

    div.error {
        margin: 40px 60px 0 0;
        text-align: center;
        border: 1px solid red;
    }

</style>';
    return $result;
}

function _data(&$par){
$result='
<div id="loginform">
    <form action="" method="POST">
        <table style="margin-left:20px;">
            <tr>
                <td style="height:32px">
                    <label for="name"> Имя:</label></td>
                <td><input id="name" name="name" type="text" placeholder="Имя пользователя" value=""
                           autofocus="autofocus" class="login input11"></td>
            </tr>
            <tr>
                <td style="height:32px"><label for="password" class="login"> Пароль: </label></td>
                <td><input id="password" name="password" type="password" placeholder="Пароль" class="login input11"
                           value=""></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right;">
                    <input type="submit" class="_states redo" name="Ok" value=""></td>
            </tr>
        </table>
        <input type="hidden" name="handler" value="User::login:name:password:1">
    </form>';
if( (isset($par['error']) && !empty($par['error'])) ) {

$result.='
    <div class="error">'
    .(isset($par['error'])?$par['error']:"")
    .'</div>';
};
$result.='
</div>';
    return $result;
}
}