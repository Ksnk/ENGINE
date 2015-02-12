<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Сергей
 * Date: 21.11.12
 * Time: 15:09
 * To change this template use File | Settings | File Templates.
 */
?>
<% POINT::start('plugins_html') %>
<blockquote id="tooltip" class="nodeDescription nodeDescriptionTooltip baseHtml xenTooltip nodeDescriptionTip"
            id="nodeDescription-4" style="position: absolute; top: 260px; left: 355px; display: none;"><span
    class="text"></span><span
    class="arrow"></span></blockquote>
<% POINT::finish() %>
<style>
    <% POINT::start('css_body') %>

    .nodeDescriptionTip {
        padding: 4px 10px;
        margin: -16px 0 1em 10px;
        line-height: 1.5;
        width: 350px;
        height: 40px;
    }

    .xenTooltip {
        font-size: 11px;
        color: white;
        cursor: default;
        background: rgba(0, 0, 0, 0.6);
        padding: 5px 10px;
        border-radius: 5px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        -khtml-border-radius: 5px;
        display: none;
        z-index: 15000;
    }

    .xenTooltip .arrow {
        border-top: 6px solid black;
        border-top: 6px solid rgba(0, 0, 0, 0.6);
        _border-top: 6px solid rgb(0, 0, 0);
        border-right: 6px solid transparent;
        border-bottom: 1px none black;
        border-left: 6px solid transparent;
        position: absolute;
        bottom: -6px;
        line-height: 0px;
        width: 0px;
        height: 0px;
        left: 9px;
        _display: none;
    }

    .nodeDescriptionTip .arrow {
        border: 6px solid transparent;
        border-right-color: black;
        border-right-color: rgba(0, 0, 0, 0.6);
        _border-right-color: rgb(0, 0, 0);
        border-left: 1px none black;
        top: 50%;
        margin-top: -7px;
        left: -6px;
        bottom: auto;
    }

    <% POINT::finish() %>
</style>
<script type="txt/javascript">
    <% POINT::start('js_body') %>
    function showTT(){
        showTT.timeout=0;
        var position = $(showTT.elem).offset();
        position.left += $(showTT.elem).width();
        $('.text', '#tooltip').html($(showTT.elem).attr('_title'));
        $('#tooltip').css(position).stop(true, true).show('slow');
    }

    $(document).on('mouseover mouseout', '.xhelp', function (event) {
        var title;
        if(showTT.timeout) clearTimeout(showTT.timeout);
        if(event.type=='mouseover') {
            if(title=$(this).attr('title'))
                $(this).attr('_title',title).removeAttr('title');
            showTT.elem=this;
            showTT.timeout=setTimeout(showTT,500);
        } else {
            $('#tooltip').hide('slow');
        }
    });
    <% POINT::finish() %>
</script>