/**
 * задержка открытия - 0.5сек
 * задержка закрытия
 */
var lastopen=[];

function dd_menu(level){
    if(!level) level=0;
    if(level>0)
        menu(this);
    else
        menu(this,{
            show:function(){
                $(this).stop(true,true).css({display:'block','height':1})
                    .animate({ height:100},'fast',function(){this.style.height='auto';})
                    .parent().addClass('open');
            }
            ,hide:function(){
                $(this).stop(false,true).animate({ height:1},'fast',function(){
                    clearTimeout(this.__timeout);
                    $(this).hide();
                    if (lastopen[level]==this){
                        lastopen.pop();
                    }
                }).parent().removeClass('open');
            }
        });
    var self=this;
    $(this).parent().bind('click mouseenter',function(){
        if(!!self.__timeout__){
            clearTimeout(self.__timeout__);
        }
        self.__timeout__=setTimeout(function(){
            self.__timeout__=false;
            //console.log(lastopen);
            clearTimeout(self.__timeout);
            if(lastopen.length>level){
                if(lastopen[level]!=self){
                    while(lastopen.length>level)
                        lastopen.pop().hide_menu();
                }
            };
            if(lastopen[level]!=self){
                self.show_menu();
                lastopen[level]=self;
            }
        },300);
    }).bind('mouseleave',function(){
            clearTimeout(self.__timeout);
            if(!!self.__timeout__){
                clearTimeout(self.__timeout__);
                self.__timeout__=false;
                return;
            }
            debug('out',self.__timeout__)
            self.__timeout=setTimeout(function(){
                self.hide_menu();
            },1000);
        });
    var x=$(this).children('li');
    x.children('ul').each(function(){
        dd_menu.call(this,level+1);
    });
    $(x[0]).addClass('first');
}

$('ul.ulmenu>li').each(function(){

    if($(this).find('ul').each(function(){
        dd_menu.call(this,0);
    }).length==0) {
        $(this).mouseover(function(){
            if(lastopen.length>0){
                lastopen.pop().hide_menu();
            }
        });
    }
});
$('ul.ulmenu>li:last').addClass('last');
$('ul.ulmenu ul').find('li:last').addClass('last');
