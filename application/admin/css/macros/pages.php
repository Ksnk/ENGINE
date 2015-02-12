<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Сергей
 * Date: 25.11.12
 * Time: 14:25
 * To change this template use File | Settings | File Templates.
 */
?>
<style>
    /*<% POINT::start('css_body') %>*/
    .page_subtitle {
        position: relative;
    }
    .page_subtitle .checkbox  {
        padding: 2px 5px;
        border: 1px solid gray;
        height: 17px;
        display: inline-block;
        position: relative;
        z-index:10;
        background: white;
    }
    .page_subtitle .menu{
        position: absolute;
        top:22px;
        display: none;
        left:0;
        border: 1px solid gray;
        z-index:9;
    }
    .page_subtitle .menu ul {
        margin:0;
    }
    .wrapper{
        overrflow-y:auto;
        padding-right:20px;
    }
    .editorline {
        background: url(../img/pane_bg.png) repeat-x;
    }
    .wrapper tr.editorline td{
        vertical-align: middle;
    }
    ._states.pdel{
        margin:28px 12px 0 12px;
    }
    ._states.pgreen {
        text-align: center;
    }
    ._states.pgreen span.number {
        display:inline-block;
        color:white;
        font-size:11px;
        font-family: arial;
        margin:9px auto 5px auto;
    }
    ._states.pgreen input {
        display:block;
        margin:0 auto 0 auto;
    }
    .editorline .xaction {
        margin-top:18px;
    }
    .editorline .xaction a {
        font-size:11px;
        font-weight:bold;
    }
    /*<% POINT::finish('css_body') %>*/
</style>