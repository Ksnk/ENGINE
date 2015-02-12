/**
 * Базовые модальные возможности в версии для Modal
 */

window.win_alert=function($msg,callback){
    ADMIN('modal',new Modal($('#alert').clone().removeAttr('id'),{
        oninit:function(){$('.message',this).html($msg)},
        onclose:function(ok){
            if(ok && callback) callback();
        }
    }));
}

window.win_confirm=function($msg,callback){
    ADMIN('modal',new Modal($('#confirm').clone().removeAttr('id'),{
        oninit:function(){$('.message',this).html($msg)},
        onclose:function(ok){
            if(ok && callback) callback($('.text',this).val());
        }
    }));
}


window.win_dialog=function(o){
    var $dialog=$(o.template||'#dialog').clone().removeAttr('id');
    if(o.isrc){
        $('<iframe></iframe>').appendTo($dialog).attr('src',o.isrc) ;
    }
    if(o.remote){
        $.getJSON(o.remote,function(data){
            if(data || data.data)
                $(".inner>div",$dialog).html(data.data).find('.color').colorPicker();
            if(data || data.title)
                $(".header>.title",$dialog).html(data.title);
            if(data || data.title1)
                $(".header>.title1",$dialog).html(data.title1);
            if($.fn._4state)
                $('._states',$dialog)._4state();
            if(data.error)
                win_alert(data.error);
            if(data.debug)
                console.log(data.debug);
        },'json') ;
    }
    if(o.msg){
        $('.inner',$dialog).html(o.msg);
    }
    ADMIN('modal',new Modal($dialog,{
        onclose:function(ok){
            if(ok && callback) callback();
        }
    }));
}
