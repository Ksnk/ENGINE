/**
 * поддержка расширения элементов на всю доступную броузеру высот
 * .lofty
 *
 * элемент вынимается из лейаута(hide), после чего ему ставится нужный размер(parent.client.height)
 */

$(function () {
    setTimeout(function () {
        $('.lofty').css({display:'none', overflow:'auto'});
        setTimeout(function () {
            $('.lofty').each(function () {
                var prev, parent = this;
                while (true) {
                    prev = parent;
                    parent = parent.parentNode;
                    if (!parent) break;
                    if (parent.style.height == '100%') {

                        var $h = $(parent).innerHeight() - (prev==this?0:$(prev).innerHeight());
                        if ($h > 0) {
                            $(this).css({height:$h, display:'block'})
                        }
                        break;
                    }
                }
                var oldheight = $(document.body).height()
                    ,oldscroll=document.body.scrollHeight-oldheight;
                $(window).bind('resize', function () {
                    var newheight = $(document.body).height()
                        , disp = newheight - oldheight
                        ,scroll= oldscroll-(document.body.scrollHeight-newheight);// - newheight
                        ;
                    oldheight = newheight;
                    //oldscroll = document.body.scrollHeight-newheight;
                    if (!!disp) {
                        // вычисляем - если у приложения есть скроллер - уменьшаем на размер скроллера
                        if (scroll > 0)
                            if (disp > 0) {
                                disp = Math.max(0, disp - scroll);
                            } else {
                                disp = 0;
                            }
                        // вычисляем минимальный шаг
                        $('.lofty').each(function () {
                            var self = $(this), mh;
                            if (mh = self.attr('min-height')) {
                                if (mh < self.height() + disp) {
                                    disp = mh - self.height();
                                }
                            }
                        })
                        // шагаем
                        $('.lofty').each(function () {
                            $(this).css('height', $(this).height() + disp);
                        })
                        oldscroll = document.body.scrollHeight-newheight;// - $().height();
                    }
                });
            });
        }, 10);
    }, 10);


})
;