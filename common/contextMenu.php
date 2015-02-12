<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Сергей
 * Date: 27.12.11
 * Time: 17:05
 * To change this template use File | Settings | File Templates.
 */?>
<style type="text/css">
    <% POINT::start('css_body') %>

        /* Generic context menu styles */
    .contextMenu {
        position: absolute;
        min-width:100px;
        max-height:400px;
        z-index: 99999;
        border: solid 1px #ccc;
        background: #f0f0f0;
        padding: 2px 1px;
        margin: 0;
        display: none;
        box-shadow:         3px 3px 3px #8e8e8e;
    }
    .contextMenu.white{
        background:white;
        border: 1px solid gray;
    }

    .contextMenu LI {
        list-style: none;
        padding: 0 ;
        margin: 0  ;
    }

    span.shortcut,.contextMenu A{
        font-size: 9pt;
        font-family: Arial,sans-serif;
        text-decoration: none;
        vertical-align: middle;
        line-height: 1.8em;

    }

    .contextMenu A {
        color: #333;
        display: block;
        background-position: 6px center;
        background-repeat: no-repeat;
        outline: none;
        padding: 1px 62px 1px 18px ;
        border:0;
    }

    .contextMenu LI.hover>A, .contextMenu LI.hover>span.shortcut {
        color: #FFF;
        background-color: #3399FF;
    }

    .contextMenu LI.disabled A {
        color: #AAA;
        cursor: default;
    }

    .contextMenu LI.hover.disabled A {
        background-color: transparent;
    }

    .contextMenu LI.separator {
        border-top: 1px solid #e0e0e0;
        margin:4px 0;
        /* overflow:hidden;*/
        height:1px;
        background:white;

    }

    a.default{
        font-weight: bold;
    }

    span.shortcut {
        float:right;
        margin:0 10px;
    }

    .contextMenu .regedit-icon-trig {
        float:right;
    }

        /* отображаем вложенное меню при наведении мыши */
    .contextMenu li.hover {
        position: relative;
    }
        /* скрываем вложенные пункты меню */
    .contextMenu li ul {
        margin-left:100%;
        position: absolute;
        left: -10px;
        top: 2px;
    }

    .contextMenu li.hover > ul {
        display:block;
    }

    <% POINT::finish(); %>


</style>

