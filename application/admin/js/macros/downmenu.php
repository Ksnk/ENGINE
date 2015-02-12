<?php
/**
 * Файл для обслуживания выпадающей внихз менюшки - a-la selectbox
 *
 */
?>
<script type="text/javascript">
    /*<% POINT::start('js_body') %>*/
    $('.downmenu').click(function () {
        var parent= $(this).data('parent');
        if (!parent) {
            var menu = $(this).data('downmenu');
            if (!menu) {
                parent = $(this).add($(this).parents()).filter('[data-menu]').eq(0);
                $(this).data('parent',parent);
                menu = parent.attr('data-menu');
                if (!menu) return false;
                parent.contextMenu({
                    parent:parent,
                    class:'white',
                    _position:'down',
                    menu:JSON.parse('["'
                        + menu.replace(/\|/g, '","')
                        .replace(/\]\"/g, '"]}')
                        .replace(/(\"[^\"]+)\[/g, '{"title":$1","children":["')
                        + '"]'),
                    action:function (action) {
                        ADMIN(action)
                    }
                    //     hotkey:{'Shift-F1':'help','Alt-R':'rename','Del':'delete'}
                })
            }
        }
        parent.contextMenu('show',parent);
    });
    /*<% POINT::finish() %>*/
</script>