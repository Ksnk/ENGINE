<?php
//<%
$this->xml_read('
<config>
    <files dstdir="$dst/system/plugins" dir="../../../templater/build">
        <files dir="lib">
            <file>template_parser.class.php</file>
            <file>template_compiler.php</file>
            <file>compiler.php.php</file>
            <file>nat2php.class.php</file>
        </files>
        <files  dir="render">
            <file>tpl_base.php</file>
            <file>tpl_compiler.php</file>
        </files>
    </files>
</config>
');
POINT::start('site_includes'); %>
include_once 'engine/compiler.class.php';
template_compiler::checktpl();

/*<% POINT::finish(); %> */

