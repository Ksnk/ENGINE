<?php
/**
 * Файл для обслуживания менюшки справа - список модулей и дерево сайта
 */
?>
<script type="text/javascript">
/*<% POINT::start('js_body') %>*/
$('.rowheader').click(function(){
    if($(this).is('.collapsed')){
        // so open
        $(this).removeClass('collapsed').addClass('expanded');
        $('.rowheader').not(this).removeClass('expanded').addClass('collapsed')
    var
        hidden= $('.rowdata:hidden',$(this).parent()),
        visible=$('.rowdata:visible',$(this).parent()),
        height=visible.height();
    visible.hide();
    hidden.show().height(height);
    }
}) ;
/*<% POINT::finish() %>*/
</script>