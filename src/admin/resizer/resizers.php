<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Сергей
 * Date: 13.10.12
 * Time: 14:48
 * To change this template use File | Settings | File Templates.
 */

?>
<style>
    <% POINT::start('css_body'); %>
    .vresizer {
        cursor: n-resize;
    }
    <% POINT::finish() %>
</style>
<script type="text/javascript">
    /* <% POINT::start('js_body'); %> */
    $('.vresizer').ddr({
        on_drag_start:function (event, options) {
            options.start = event.pageY;
            var $self = $(this), data = $self.data('vresizer');
            if (!data) {
                var $x = $self.closest('tr'),
                    maxheight = $x.height() + $x.prev('tr').height() + $x.next('tr').height();
                data = [
                    $self.closest('tr').next('tr').find('td').eq(0), -1, maxheight];
                $self.data('vresizer', data);
            }
            options.startheight = parseInt(data[0].css('height'));
        }, on_drag:function (event, options) {
            var xxx = $(this).data('vresizer'),
                w = options.startheight + (event.pageY - options.start) * xxx[1];
// console.log(xxx);
            if (w > 15 && w < xxx[2])
                xxx[0].css('height', w);
        }
    })
    /*   <% POINT::finish() %> */
</script>