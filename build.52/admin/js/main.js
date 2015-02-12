/**
 *  скрипт админки
 *
 * ------------------------------------------------------------------------------------------
 * $Id: X-Site cms (2.0), written by Ksnk (sergekoriakin@gmail.com)
 *  Rev: 1408, Modified: 
 *  SVN: file:///C:/notebook_svn/svn/xilen/cms$
 * ------------------------------------------------------------------------------------------
 * License MIT - Serge Koriakin - Jule 2012
 * ------------------------------------------------------------------------------------------
 */

$(function () {
    /*  --- point::js_prefix --- */
if (window.console && window.console.debug){
    window.debug=function(){
        var args = []; // empty array
        for(var i = 0; i < arguments.length; i++)
        {
            args.push(arguments[i]);
        }
        console.debug.apply(console, args);
    }
} else {
    window.console = {debug:function () {
        for (var i = 0; i < debug.arguments.length; i++) {
            var text = 'x3';
            if (typeof(debug.arguments[i]) == 'undefined')
                text = 'undefined';
            else if (debug.arguments[i].toString)
                text = debug.arguments[i].toString();
            $('<span>').html(text).appendTo('#debug');
        }
        $('<hr>').appendTo('#debug');
    }};
    console.log=console.debug;
    window.debug=console.debug;
}
//<% } %>
/**
 * Установка выпадающего меню
 */
function menu(_self,param){
    if(!param) param={};
    else if(typeof(param)=='function')
    	param={show:param};
    if(!(_self=$(_self)[0])) return;

	function checkMouse (e){
	     var el = e.target;
	     while (true){
			if (el == _self) {
				return true;
			} else if (el == document) {
				hide_menu();
				return false;
			} else {
				el = el.parentNode;
			}
		}
    }
    function show_menu(){
	  if(param.show) param.show.apply(_self);
	  else $(_self).show();
	  _self.shown=true;
	  $(document).bind('mousedown', checkMouse);
	  return false;
    }
    function hide_menu(){
	  $(document).unbind('mousedown', checkMouse);
	  if(param.hide)
	  	param.hide.apply(_self);
	  else
	  	$(_self).hide();
	  setTimeout(function(){_self.shown=false},500);
	  return false;
    }
    _self.show_menu=show_menu;
	_self.hide_menu=hide_menu;
	$(window).bind('unload', function(){_self=null});
}/**
 * context Menu
 *
 * срабатывает на пробел, кнопку "свойства" и на правую кнопку мыши. Встраивается в низ контрола
 * <code>
 * // пример простого меню, генерируемого на лету
 * $('input').contextMenu({
 *     menu:['О нас#about','Привет#hello','Opps!'],
 *     action:function(action){
 *          switch(action){
 *              case 'about': alert('about'); break;
 *              case 'hello': alert('hello'); break;
 *              case 'Opps!': alert('Opps!'); break;
 *          }
 *     },
 *     hotkey:{'Shift-F1':'help','Alt-R':'rename','Del':'delete'}
 * })
 * </code>
 *
 * вывести контекстное меню
 * <code>
 *     $('input').contextMenu('show',$('#this_input'))
 * </code>
 *
 * вывести контекстное меню
 * <code>
 *     $('input').contextMenu('disable',['hello'])
 * </code>
 *
 * вывести контекстное меню
 * <code>
 *     $('input').contextMenu('enable',['hello'])
 * </code>
 *
 *  * вывести контекстное меню
  * <code>
  *     $('input').contextMenu('action',{'hello':})
  * </code>

 * @param string action
 * @param object o
 * o.menu array|string|function
 *
 */
var CMenu_keymap =
{
    F1:112,
    F2:113,
    F3:114,
    F4:115,
    F5:116,
    F6:117,
    F7:118,
    F8:119,
    F9:120,
    F10:121,
    Space:32,
    BackSpace:8,
    Tab:9,
    Enter:13,
    Shift:16,
    Ctrl:17,
    Alt:18,
    CapsLock:20,
    Esc:27,
    Insert:45,
    PageUp:33,
    PageDown:34,
    End:35,
    Home:36,
    Back:37,
    Up:38,
    Right:39,
    Down:40,
    Del:46,
    PrintScreen:44,
    ScrollLock:145,
    Pause:19,
    NumLock:144
};

$.fn.contextMenu = function (action, o,o2) {

    var options;

    if(typeof(action)=='string'){

    } else {
        if(typeof(o)=='undefined')
            o=action;
        action='create';
    }

    function keypress(e) {
        if(options._nokeyboard) return;
        if(options._displayed){
            // движения клавиш - обрабатывает меню
            var key=e.keyCode; e.keyCode=0;
            //console.log(e);
            switch (key) {
                case 37: //left
                    if($('LI.hover>ul>li.hover',options._menu).length>0){
                        $('LI.hover>ul>li',options._menu).removeClass('hover');
                    }
                    break;
                case 39: // right
                    if ($('LI.hover>ul>li',options._menu).length>0) {//есть 2 уровень
                        if($('LI.hover>ul>li.hover',options._menu).length==0){ // пока еще не там
                            // переходим на нижний уровень
                            $('LI.hover>ul',options._menu).find('LI').filter(':not(.disabled,.separator)').eq(0).addClass('hover');
                        }
                    }
                    break;
                case 38: case 40: // up
                    var pos,func;
                    if (key==38){ pos='last';func='prevAll'}
                    else { pos='first';func='nextAll'}
                    if ($('LI.hover',options._menu).length==0) {
                        $('LI:'+pos,options._menu).addClass('hover');
                    } else {
                        //уровень?
                        if($('LI.hover>ul>li.hover',options._menu).length>0){
                            $('LI.hover>ul>li.hover',options._menu).removeClass('hover')[func]('LI').filter(':not(.disabled,.separator)').eq(0).addClass('hover');
                            if ($('LI.hover>ul>li.hover',options._menu).length == 0) $('LI.hover>ul>li:'+pos,options._menu).addClass('hover');
                        } else {
                            $('LI.hover',options._menu).removeClass('hover')[func]('LI').filter(':not(.disabled,.separator)').eq(0).addClass('hover');
                            if ($('LI.hover',options._menu).length == 0) $('LI:'+pos,options._menu).addClass('hover');
                        }
                    }
                    break;
                case 13: // enter
                    $('LI.hover>A',options._menu).last().trigger('click');
                    break;
                case 27: // esc
                    $(document).trigger('mousedown');
                    break;
                default:
                  // e.keyCode=key;
            }
            e.stopPropagation();
        }
        //console.log(e);
        //обрабатываем оставшиеся hotkey
        if(!$(e.target).is('input,textarea')){
            if(options.hotkey[e.keyCode]){
                var action=options.hotkey[e.keyCode];
                if ( !options._disabled[action.action]
                    && e.altKey==action.alt
                    && e.ctrlKey==action.ctrl
                    && e.shiftKey==action.shift
                ){
                    if( options.action )
                         options.action.call(null, action.action, e);
                    e.preventDefault();
                    e.stopPropagation();
                    e.result=false;
                    return false;
                }
            }
        }
    }

    function showMenu(tgtElement,X,Y){
        var _menu = getMenu(tgtElement);//options.menu.call(srcElement);
        if(!_menu) return;
        if(options._mode!='contextmenu') $(_menu).css('overflow-y','auto');
        $('li',_menu).removeClass('disabled');
        for(a in options._disabled){
            $('a',_menu).find('[href$="#' + a + '"]').parent().addClass('disabled');
        }

        options._menu=_menu;
        // Show the menu
        if (!_menu.show_menu){
            menu(_menu,{
                show:function(){
                    if(options.show)options.show.call();
                    options._displayed=true;

                    $(this).fadeIn(options.inSpeed)
                },
                hide:function(){
                    if(options.hide)options.hide.call();
                    options._displayed=false;

//                    $(document).unbind('keydown',keypress);
                    if(options._xmenu){
                        $(options._xmenu).remove();
                        options._xmenu=false;
                    }
                    $(this).fadeOut(options.outSpeed)
                }
            });
            $('A',_menu).mouseover( function() {
                 $(_menu).find('LI.hover').removeClass('hover');
                 $(this).parents('LI').addClass('hover');
            }).click(function(event){
                _menu.hide_menu() ;
                if( options._mode=='select'){
                    if (options.set_text){
                        options.set_text.call(tgtElement,$(this).attr('href').substr(1))
                    }
                } else if( options.action ) {
                    options.action.call(tgtElement,
                        $(this).attr('href').substr(1),
                        event
                    )
                }
                return false;
            });
        }
        $(_menu).css({ top:Y, left:X });
        _menu.show_menu();
    }

    function _getMenu(menu,el){
        if (typeof(menu) == 'function'){
            menu=menu.call(el,options);
        }
        if (menu instanceof Array) {
            // строим меню
            var xmenu=$('<ul/>').addClass("contextMenu");
            if(options.class){
                xmenu.addClass(options.class);
            }
            for(var i in menu){
                var line=menu[i];
                if ( line=='' ) {
                    xmenu.append('<li class="separator"></li>');
                    continue;
                }
                if(typeof(line)=='string'){
                    line=line.split('#');
                    line={'title':line[0],'href':line[1]||line[0]};
                    var xx = $('<li><a href="#' + line.href + '">' + line.title.replace(' ', '&nbsp;') + '</a></li>');
                    if(line.href==options._defaultaction)
                        $('a',xx).addClass("default");
                    if(options._act_hk[line.href])
                        $('a',xx).before("<span class='shortcut'>"+options._act_hk[line.href]+"</span>");
                    xmenu.append(xx);
                } else if(line.children) {
                    $('<li><span class="regedit-icon-trig"></span><a href="#'+(line.href||'')+'">'+line.title+'</a>'
                         +'</li>').append(_getMenu(line.children,el)).appendTo(xmenu);
                }
            }
            return xmenu[0];

        } else if (typeof(options.menu) == 'string') {
            // ищем селектор
            menu = $(options.menu)[0]
        }
        return menu;
    }

    function getMenu(el){
        return options._xmenu=$(_getMenu(options.menu,el)).appendTo(options.parent||document.body)[0];
    }

    function create(){
        options = {
            slowClick_timer:null,
            slowClick_low: 400,
            slowClick_high:2000,

            inSpeed:150,
            outSpeed:75,
            menu:function () {
                return $($(this).data('contextmenu'))[0]
            },
            hotkey:{},
            _disabled:{},       // комплект задизейбленых акций
            _defaultaction:'',  // акцио по даблклику
            _act_hk:{},         // клавиатурные сокращения акций
            _displayed:false,   // показывается или нет
            _nokeyboard:false,
            empty:''
        };
        if (!o) o = {};
        else if (typeof(o) == 'string')
            o = {xxx:o};
        var hotkey=o.hotkey||{},
            reg=/^((\d*)|(Ctrl?[-+ ])?(Alt[-+ ])?(Shift?[-+ ])?(\w+)~?)$/i;
        if(o.hotkey) delete (o.hotkey);
        $.extend(options, o);
        for(var a in hotkey){

            var idx=a
                ,res=reg.exec(''+a)
                ,key={ctrl:false,shift:false,alt:false,action:hotkey[a]};
            if(!res)
                continue;
            if(res[3]){
                key.ctrl=true;
            }
            if(res[4]){
                key.alt=true;
            }
            if(res[5]){
                key.shift=true;
            }
            if('default'==res[6]){
                options._defaultaction=hotkey[a];
            } else if(res[6]){
                if(CMenu_keymap[res[6]]){
                    idx=CMenu_keymap[res[6]];
                } else if (res[6].length==1){
                    idx=res[6].toLowerCase().charCodeAt(0);
                    options.hotkey[idx]=key;
                    idx=res[6].toUpperCase().charCodeAt(0);
                }
                options._act_hk[hotkey[a]]=a;
            }
            options.hotkey[idx]=key;//hotkey[a];
        }
        $(this).data('contextMenu',options);

        // Defaults
        function mouseup(event) {
            event.stopPropagation();
            var tgtElement=event.target;
            $(this).unbind('mouseup',mouseup);
            // Hide context menus that may be showing
            //$(".contextMenu").hide();
            // Get this context menu
            options._mode='contextmenu';
            showMenu(tgtElement,event.pageX+1, event.pageY+1);
        }

        this.mousedown(function (e) {
            if (e.button == 2) {
                 $(this).mouseup(mouseup);
                e.stopPropagation();
            }
        }).bind('contextmenu',function(e) {
            if($(e.target).is('input,textarea')) return ;
            if(e.ctrlKey) return;
            return false;
        });
        $(document)
            .bind('keydown',keypress)
            .bind('dblclick',function(e){
                if (options.slowClick_timer)
                    clearTimeout(options.slowClick_timer);
                options._lasttgt = null;
                if(options._defaultaction)
                    if( options.action )
                         options.action.call(e.target,
                             options._defaultaction,
                             event
                         );
            })
            .click(function(event){
                var $tgt = $(event.target);
                // отслеживаем двойной медленный клик
                if (options.slowClick_timer)
                    clearTimeout(options.slowClick_timer);
                if (!options._lasttgt != event.target) {
                    options.slowClick_timer = (function (tgt) {
                        return setTimeout(function () {
                            options._lasttgt = tgt;
                            options.slowClick_timer = setTimeout(function () {
                                options._lasttgt = null;
                                options.slowClick_timer = null;
                            }, options.slowClick_high)
                        }, options.slowClick_low)
                    })(event.target);
                }
                if (!!options._lasttgt && options._lasttgt == event.target) {
                    if(!options._displayed && options.action )
                        options.action.call($tgt,
                            'slowdbl',
                            event
                        );
                }
            });
    }
    var a,x,pos;
    switch (action){
        case 'create': // создать меню и поставить хандлеры
            create.call(this);
            break;
        case 'keyboard':
            options=$(this).data('contextMenu');
            options._nokeyboard=!o;
            break;
        case 'enable':
            options=$(this).data('contextMenu');
            x=o.split(',');
            for(a in x)
                if(options._disabled[a])
                    delete options._disabled[a];
            break;
        case 'disable':
            options=$(this).data('contextMenu');
            x=o.split(',');
            for(a in x)
                options._disabled[x[a]]=true;
            break;
        case 'select': // показать меню в стиле select
            options=$(this).data('contextMenu');
            options._mode='select';
            if (!o2) o2 = {};
            $.extend(options, o2);
            if(o instanceof $){
                pos=o.position();
                showMenu.call(this,o,pos.left,pos.top+o.height());
            } else {
                console.log('Блин!');
            }
            break;
        case 'show': // показать меню
            options=$(this).data('contextMenu');
            options._mode='contextmenu';
            if(o instanceof $){
                pos=o.position();
                if(options._position=='down')
                    showMenu.call(this,o,pos.left,pos.top+o.height()-1);
                else
                    showMenu.call(this,o,pos.left+(o.width()>>1),pos.top+o.height()-3);
            } else {
                pos=o.position();
                showMenu.call(this,o,pos.left+20,pos.top+20);
            }
            break;
    }

    return this;
};
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
;// поставить куку cookie.
function cookie(name,value,opt){
    if (typeof value != 'undefined') { // name and value given, set cookie
        if(typeof value == 'object' && !(value instanceof String)){
            // сворачиваем простой одноуровневый объект в структуру
            var str=[];
            for(a in value) str.push(a+'='+encodeURIComponent(value[a]||0));
            value='&'+str.join('&');
        }
        opt = opt || {};
        if (value === null) {
            value = '';
            opt.expires = -1;
        }
        var expires = '';//expires:10
        if (opt.expires && (typeof (opt.expires) == 'number' || opt.expires.toUTCString)) {
            var date;
            if (typeof opt.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + Math.round(opt.expires * 24 * 60 * 60 * 1000));
            }
            else {
                date = opt.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        document.cookie = name + '=' + encodeURIComponent(value) + expires +
            (opt.path ? '; path=' + opt.path : '') +
            (opt.domain ? '; domain=' + opt.domain : '') +
            (opt.secure ? '; secure' : '')
    }
    else { // only name given, get cookie
        if (document.cookie && document.cookie != '') {
            var cook = (new RegExp(";\\s*" + name + "\\s*=([^;]+)")).exec(';' + document.cookie);
            var cook=cook && decodeURIComponent(cook[1]),
                reg=new RegExp("[\b|&]([^=]+)=([^&]+)","g"),resa=[],res={},obj=false;
            while((resa=reg.exec(cook))){
                res[resa[1]]=resa[2];
                obj=true;
            };
            if(obj)
                return res;
            else
                return cook;
        }
        return null;
    }
};$('.treemenu li').not('.current').find('ul').hide().parent().addClass('collapsed');
$('.treemenu li.current').children('ul').show().parent().addClass('expanded');
$('.treemenu').click(function (e) {
    var el = e.target || e.srcElement;
    //try{
    while (el && (!el.tagName ||
        (el.tagName.toLowerCase() != 'li') && el.tagName.toLowerCase() != 'a'))
        el = el.parentNode;
    if (!el || el.tagName && el.tagName.toLowerCase() == "a") return;
    if ($(el).hasClass('collapsed')) {
        $(el).removeClass('collapsed').addClass('expanded').children('ul').show();
    }
    else if ($(el).hasClass('expanded')) {
        $(el).removeClass('expanded').addClass('collapsed').children('ul').hide();
    }
    //}//catch(e){;}
});
/* нагло потырено из twitter bootstrap и безжалостно почикано
 * ====================== */

var Modal = function (element, options) {
    this.options = options;
    this.$element = $(element);
};

Modal.prototype = {

    constructor:Modal, show:function () {
        if (this.isShown) return;

        this.isShown = true;

        var that = this;

        this.backdrop(function () {

            if (!that.$element.parent().length) {
                that.$element.appendTo(document.body); //don't move modals dom position
            } else {
                that.no_remove=true;
            }
            if($.fn._4state)
                $('._states',that.$element)._4state();

            that.options.oninit && that.options.oninit.call(that.$element);
            that.$element.show();

            //           if (transition) {
            //               that.$element[0].offsetWidth ;// force reflow
            //           }

            that.$element.focus();

            //var that = this  ;
            $(document).on('focusin.modal', function (e) {
                if (that.$element[0] !== e.target && !that.$element.has(e.target).length) {
                    that.$element.focus()
                }
            });

        })
    }, hide:function (ok) {
        if (!this.isShown) return;

        this.isShown = false;

        this.$element.hide();

        this.backdrop();
        this.options.onclose && this.options.onclose.call(this.$element,ok);
        if(!this.no_remove)
            this.$element.remove();

    }, backdrop:function (callback) {

        if (this.isShown) {

            this.$backdrop = $('<div class="backdrop fade_in' + '" />')
                .appendTo(document.body);
            callback()

        } else if (!this.isShown && this.$backdrop) {
            this.$backdrop.remove();
            this.$backdrop = null
        } else if (callback) {
            callback();
        }
    }
};

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

    /*  --- point::js_body --- */
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

function Color(a, type) {
    this.type = type;
    this.color = a;
}
Color.prototype.changeHue = function (Hue) {
    if (this.type != 'hsv'){
        this.color = this.rgb2hsv(this.color);
        this.type='hsv';
    }
    this.color[0] = Hue;
}
Color.prototype.changeBrightness= function (Br) {
    if (this.type != 'hsv'){
        this.color = this.rgb2hsv(this.color);
        this.type='hsv';
    }
    this.color[2] = Br;
}
Color.prototype.toHexStr = function () {
    function hex(c) {
        c = parseInt(c).toString(16);
        return c.length < 2 ? "0" + c : c;
    }

    var rgb = this.color;
    if (this.type == 'hsv')
        rgb = this.hsv2rgb(rgb);

    return ("#" + hex(rgb[0]) + hex(rgb[1]) + hex(rgb[2])).toUpperCase();
}
Color.prototype.toRgbStr =function(){
    var rgb = this.color;
    if (this.type == 'hsv')
        rgb = this.hsv2rgb(rgb);

    return "rgb(" + Math.ceil(rgb[0])+',' +Math.ceil(rgb[1])+','+Math.ceil(rgb[2])+')';
}
Color.prototype.getWebSafeColor = function (color) {
    var rMod = color.r % 51;
    var gMod = color.g % 51;
    var bMod = color.b % 51;

    if ((rMod == 0) && (gMod == 0) && (bMod == 0)) return color;

    var wsColor = {};

    wsColor.r = (rMod <= 25 ? Math.floor(color.r / 51) * 51 : Math.ceil(color.r / 51) * 51);
    wsColor.g = (gMod <= 25 ? Math.floor(color.g / 51) * 51 : Math.ceil(color.g / 51) * 51);
    wsColor.b = (bMod <= 25 ? Math.floor(color.b / 51) * 51 : Math.ceil(color.b / 51) * 51);

    return wsColor;
};

Color.prototype.rgb2hsv = function (rgb) {
    var r, g, b, h, s, min, max;
    r = rgb[0];
    g = rgb[1];
    b = rgb[2];

    min=Math.min(r,g,b);
    max=Math.max(r,g,b);

    if(max==min){
        h=0
    } else if ((max==r)&& (g>=b)){
        h=60*(g-b)/(max-min)
    } else if ((max==r)&& (g<b)){
        h=360+60*(g-b)/(max-min)
    } else if (max==g){
        h=120+60*(b-r)/(max-min)
    } else if (max==b){
        h=240+60*(r-g)/(max-min)
    }
    if(max==0){
        s=0
    } else {
        s=1-min/max
    }

    return [h,s*100,max*100/256];
};
Color.prototype.hsv2rgb = function (hsv) {
    var h = hsv[0],s = hsv[1],v = hsv[2];

    var hi=Math.round((h-h%60)/60), vmin=(100-s)*v/100,alpha=(v-vmin)*(h % 60)/60,
        vinc=vmin+alpha,vdec=v-alpha,res;
    if(hi==0 || hi==6)
        res= [v,vinc,vmin];
    else if (hi==1)
        res= [vdec,v,vmin];
    else if (hi==2)
        res= [vmin,v,vinc];
    else if (hi==3)
        res= [vmin,vdec,v];
    else if (hi==4)
        res= [vinc,vmin,v];
    else if (hi==5)
        res= [v,vmin,vdec];

    return [2.56* res[0], 2.56* res[1], 2.56* res[2]];
};

Color.prototype.parseColor = function (colorText) {
    var sType = typeof(colorText);
    if (sType == "string") {
        if (/^\#?[0-9A-F]{6}$/i.test(colorText)) {
            return {
                r:eval('0x' + colorText.substr(colorText.length == 6 ? 0 : 1, 2)),
                g:eval('0x' + colorText.substr(colorText.length == 6 ? 2 : 3, 2)),
                b:eval('0x' + colorText.substr(colorText.length == 6 ? 4 : 5, 2)),
                a:255
            };
        }
    } else if (sType == "object") {
        if (colorText.hasOwnProperty("r") &&
            colorText.hasOwnProperty("g") &&
            colorText.hasOwnProperty("b")) {
            return colorText;
        }
    }
    return null;

};

/**
 * колор пикер - окошко, которое появляется под-над элементом при клике на него, прижато к правому краю элемента.
 */
$.fn.colorPicker = function (o) {
    var testcanvas = $("<canvas></canvas>");
    if (!testcanvas[0].getContext) {
        this.addClass('disabled');
        return this;
    }
    delete testcanvas;

    this.click(
        function (event) {
            ColorPicker.initialize({
                parent:this,
                inSpeed:'slow',
                outSpeed:'slow'
            }, event.pageX, event.pageY)
        }
    );
};

var ColorPicker = {
    initialize:function (options, X, Y) {
        //if this is a color us it if not try to make a color out of it.
        var _menu = $('#colorpicker')[0];
// Show the menu
        if (!_menu.show_menu) {
            this.HueBar = $('.rule', _menu)[0];
            this.SVBox = $('.pane', _menu)[0];
            this.drawHueBar();
            //Draw the SVBox
            this.drawSVBox();

            menu(_menu, {
                show:function () {
                    if (options.show)options.show.call();
                    options._displayed = true;

                    $(this).fadeIn(options.inSpeed)
                },
                hide:function () {
                    if (options.hide)options.hide.call();
                    options._displayed = false;

                    if (options._xmenu) {
                        $(options._xmenu).remove();
                        options._xmenu = false;
                    }
                    $(this).fadeOut(options.outSpeed)
                }
            });
            /*    $('A',_menu).mouseover( function() {
        $(_menu).find('LI.hover').removeClass('hover');
        $(this).parents('LI').addClass('hover');
    }).click(function(event){
            _menu.hide_menu() ;
            if( options._mode=='select'){
                if (options.set_text){
                    options.set_text.call(tgtElement,$(this).attr('href').substr(1))
                }
            } else if( options.action ) {
                options.action.call(tgtElement,
                    $(this).attr('href').substr(1),
                    event
                )
            }
            return false;
        });    */
        }
        $(_menu).css({ top:Y, left:X });
        _menu.show_menu();

    },
    CurColor:[0,99,99],
    //Iterates through all 360 hues and creates a 1px by 30px div for each hue.
    drawHueBar:function () {
        //get the multiplyer for the hue range based on the height of the hue bar.
        var hSteps = 360 / this.HueBar.height;
        var hColor = new Color([255, 0, 0], 'rgb');
        var myCTX = this.HueBar.getContext('2d');
        for (var hi = this.HueBar.height; hi > 0; hi--) {
            hColor.changeHue(hi * hSteps);
            myCTX.fillStyle = hColor.toRgbStr();
            myCTX.fillRect(0, this.HueBar.height - hi, this.HueBar.width, 1);
        }
    },
    //Draw a SV box that is as tall as the HUE Bar.
    drawSVBox:function (objsvDiv) {
        //Get the value multiplyer for the number of steps over the height of the SVBox
        var width=this.SVBox.width, height=this.SVBox.height;
        //Create a new color for calculating each rows color ranges.
        var svColor = new Color([this.CurColor[0], 99, 99], "hsv");
        //Get a 2d context to the SVBox canvas element.
        var myCTX = this.SVBox.getContext('2d');
        //Iterate over the hieght of the SVBox.
        for (var vi = height; vi > 0; vi--) {
            //Set the brightness for this row
            svColor.changeBrightness(vi * 100/height);
            //Get the hex string for the current brightness.
            //Create a new linear Gradient from the canvas context that goes
            //from the left to the right.
            var myLinearGrad = myCTX.createLinearGradient(0, 0, width, 1);
            //Add a color stop to the gradient that is the current
            //bright ness no need to to the HSV conversion here
            var x =Math.ceil(vi * 255/height);
            myLinearGrad.addColorStop(0, "rgb(" + x + "," + x + "," + x + ")");
            //Add a color stop that is based on the current Hue
            myLinearGrad.addColorStop(1, svColor.toRgbStr());
            //Set the canvas context's fill style to our current gradient.
            myCTX.fillStyle = myLinearGrad;
            //Fill the row at 1px high.
            myCTX.fillRect(0, height - vi, width, 1);
        }
    },
    //Update the pixels in the SVBox when the hue is clicked.
    UpdateSVBox:function () {
        if (this.supportsCanvas) {
            vSteps = 255 / this.SVBox.height;
            svSteps = 100 / this.SVBox.height;
            svColor = new Color([this.CurColor.hsv[0], 100, 100], "hsv");
            myCTX = this.SVBox.getContext('2d');
            strsvHex = "";
            for (vi = this.SVBox.height; vi > 0; vi--) {
                svColor.changeBrightness(vi * svSteps);
                strsvHex = svColor.rgbToHex();
                myLinearGrad = myCTX.createLinearGradient(0, 0, this.SVBox.width, 0);
                myLinearGrad.addColorStop(0, "rgb(" + vi + "," + vi + "," + vi + ")");
                myLinearGrad.addColorStop(1, strsvHex);
                myCTX.fillStyle = myLinearGrad;
                myCTX.fillRect(0, this.SVBox.height - vi, this.SVBox.height, 1);
            }
        } else {
            //Get the child rows in the SVBox
            arSVRows = this.SVBox.getChildren();
            //set the svSize to the number of rows.
            svSize = arSVRows.length;
            //Get the multiple required to go from 0 to 100 is svSize steps.
            svStep = 100 / svSize;
            //Create a color for calculating the HSV of each pixel.
            svColor = new Color([this.CurColor[0], this.CurColor[1], this.CurColor[2]]);

            //Loop over all of the rows.
            for (vi = svSize - 1; vi >= 0; vi -= 1) {
                //set the brightness for this row.
                svColor.changeBrightness(vi * svStep);
                //Get the children of the row.
                siChildren = arSVRows[svSize - vi - 1].getChildren();
                //Iterate the children of this row.
                for (si = 0; si < siChildren.length; si++) {
                    //Change the saturation for each pixe.
                    svColor.changeSaturation(si * svStep);
                    siChildren[si].setStyle("background-color", svColor);
                }
            }
        }

    },
    setCurrentHue:function (e) {
        //IE uses srcElement instead of target to specify the
        //element that was clicked.
        if (!e.target)
            e.target = e.srcElement;

        if (this.supportsCanvas) {
            //Get a 2d context to the SVBox canvas element.
            myCTX = this.HueBar.getContext('2d');
            //Get the coordinates for our hue bar.
            hBoxCoords = this.HueBar.getCoordinates();
            //subtract the left and top of the hue bar from the event.clentX and y then add the window.scrollX and Y
            // to get the click position in the Hue bar and pass those in to the contexts getImageData function.
            myImageData = myCTX.getImageData(e.clientX - hBoxCoords.left + window.scrollX, e.clientY - hBoxCoords.top + window.scrollY, 1, 1);
            //Create a hue color based of the ImageData returned by the getImageData function.
            CurHueColor = new Color([myImageData.data[0], myImageData.data[1], myImageData.data[2]]);
        } else {
            //Create a color object from the background of the target so we can
            //get its Hue.
            CurHueColor = new Color(e.target.getStyle("background-color"));
        }
        //Set the Hue of the current color.
        this.CurColor.changeHue(CurHueColor.hsv[0]);
        //Tell the SVBox to update.
        this.UpdateSVBox();
        //Set the selected color to the current color.
        this.SelectedColor.setStyle("background-color", this.CurColor);
        //Update the hsv and rgb text boxes.
        this.hInput.value = this.CurColor.hsv[0];
        this.sInput.value = this.CurColor.hsv[1];
        this.vInput.value = this.CurColor.hsv[2];
        this.rInput.value = this.CurColor[0];
        this.gInput.value = this.CurColor[1];
        this.bInput.value = this.CurColor[2];
    },
    setPreviewColor:function (e) {
        //IE uses srcElement instead of target to specify the
        //element that was clicked.
        if (!e.target)
            e.target = e.srcElement;
        if (this.supportsCanvas) {
            myCTX = this.SVBox.getContext('2d');
            SVBoxCoords = this.SVBox.getCoordinates();

            myImageData = myCTX.getImageData(e.clientX - SVBoxCoords.left + window.scrollX, e.clientY - SVBoxCoords.top + window.scrollY, 1, 1);
            nColor = new Color([myImageData.data[0], myImageData.data[1], myImageData.data[2]]);
            this.PreviewColor.setStyle("background-color", nColor);
        } else {
            nColor = new Color(e.target.getStyle("background-color"));
            this.PreviewColor.setStyle("background-color", nColor);
        }
    },

    setSelectedColor:function (e) {
        //IE uses srcElement instead of target to specify the
        //element that was clicked.
        if (!e.target)
            e.target = e.srcElement;
        if (this.supportsCanvas) {
            myCTX = this.SVBox.getContext('2d');
            SVBoxCoords = this.SVBox.getCoordinates();

            myImageData = myCTX.getImageData(e.clientX - SVBoxCoords.left + window.scrollX, e.clientY - SVBoxCoords.top + window.scrollY, 1, 1);
            nColor = new Color([myImageData.data[0], myImageData.data[1], myImageData.data[2]]);
        } else {
            nColor = new Color(e.target.getStyle("background-color"));
        }
        //nColor = new Color(e.target.getStyle("background-color"));
        this.CurColor = nColor;
        this.SelectedColor.setStyle("background-color", nColor);
        this.hInput.value = nColor.hsv[0];
        this.sInput.value = nColor.hsv[1];
        this.vInput.value = nColor.hsv[2];
        this.rInput.value = nColor[0];
        this.gInput.value = nColor[1];
        this.bInput.value = nColor[2];

    },
    setRGB:function () {
        nc = new Color([this.rInput.value, this.gInput.value, this.bInput.value]);
        this.CurColor = nc;
        this.hInput.value = nc.hsv[0];
        this.sInput.value = nc.hsv[1];
        this.vInput.value = nc.hsv[2];
        this.SelectedColor.setStyle("background-color", nc);
        this.PreviewColor.setStyle("background-color", nc);
        this.UpdateSVBox();
    },
    setHSL:function () {
        nc = new Color([this.hInput.value, this.sInput.value, this.vInput.value], 'hsv');
        this.CurColor = nc;
        this.rInput.value = nc[0];
        this.gInput.value = nc[1];
        this.bInput.value = nc[2];
        this.SelectedColor.setStyle("background-color", nc);
        this.PreviewColor.setStyle("background-color", nc);
        this.UpdateSVBox();
    },
    //When the ok button is clicked call this.OKCallBack();
    handleOK:function () {
        if (this.OKCallback != null)
            this.OKCallback();
    },
    //When the ok button is clicked call this.CancelCallback();
    handleCancel:function () {
        if (this.CancelCallback != null)
            this.CancelCallback();
    }

};


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

// контрол - загрузчик файлов
    var active_element = null;
    var locked_element = null;
    $('input[type="file"]', '#uploader').on('change', function () {
        locked_element=active_element;
        $(this).parents('form').eq(0).submit();
    });
    $(document).on('click', '.upload', function () {
        if(locked_element) return false;
        activeElement=this;
        $('input:file','#uploader').trigger('click');
        return false;
    });
     window.upload_OnSuccess=  function (data) {
    // console.log(data);
    //alert('ok');
     if(data && data.data){
         // поиск атрибутов для выполнения
         var x,o;
         if(x=$(locked_element).attr('data-admin')){
             x= x.split('#',2);
             o={};
             try{
                x[1] && eval('o='+x[1]);
             }catch(e){}
             if(!o)o={};
             o.element=locked_element;
             for( var a in data.data){
                 o.filename= data.data[a];
                 o.name=a;
                 ADMIN(x[0],o);
             }
         }
     }
     locked_element=null;
/*            $complete = true;
            if (data.error) alert(data.error);
            if (data.debug) alert(data.debug);
            toggleWait(null, true);
            //alert([data.data,data.error]);
            $upload_results = data.result || data;
            var inp = $x && $x.getElementsByTagName('input');
            if (!inp || !inp[0])
                inp = $x && $x.parentNode.getElementsByTagName('input');
            if (inp && inp[0]) {
                inp = inp[0];
                if (data.data.match(/javascript/i))
                    inp.value = '';
                else
                    inp.value = data.data;
                if (!!inp.onclick) {
                    if (typeof(inp.onclick) == 'function') {
                        //debug.trace('function');
                        inp.onclick.apply(inp);
                    }
                    else {
                        //debug.trace('eval');
                        eval(inp.onclick);
                    }
                }
                else
                    inp.click();
            }
            inp = $x && $x.parentNode.getElementsByTagName('img');
            if (!inp)
                inp = $x && $x.parentNode.parentNode.getElementsByTagName('img');
            if (inp && inp[0]) {
                //var xx=inp[0].src;
                inp[0].src = inp[0].src.replace(/\/uploaded.*$/, '' + data.data);
                //alert([xx,inp[0].src]);
            }
            inp = null;
            need_Save();  */
        }

var states ={ // 0 - usual, 1 hover, 2 - clicked , 4 passive
    treepoint:{bg:['-20px -50px','-10px -50px','0 -50px'],w:10,h:10,type:'tree','class':['','collapsed','expanded']},
    help:{bg:['0 0','-20px 0','-40px 0'],w:18,h:16},
    // X для угла главного окна
    logout:{bg:['0 -20px','-20px -20px','-40px -20px'],w:18,h:16},
    // стрелка вправо черно-зеленая
    arrowrt:	{bg:['0 -40px','-10px -40px'],w:10,h:10},
    // уголок вниз черно-зеленый
    downcrn:	{bg:['-40px -50px','-50px -50px'],w:10,h:10},
    sidebarroll:{bg:['-40px -160px','0 -160px','-20px -160px'],w:14,h:30,type:'tree','class':['','collapsed','expanded']},
    xilenium:{bg:['0 -320px'],w:100,h:37,type:'icon'},
    param:{bg:['-100px -320px','-100px -340px'],w:100,h:18},
    dbutton:{bg:['-200px -320px','-200px -320px','-200px -320px','-200px -350px']
        ,col:['#ffffff','#8fcd50','#7e9298','#7e9298'],w:110,h:24},
    redo:{bg:['0 -200px','0 -230px','0 -260px'],w:32,h:25},
    bigparam:{bg:['-60px 0','-190px 0'],w:130,h:80},
    bigtext:{bg:['-60px -80px','-130px -80px'],w:60,h:80},
    biglink:{bg:['-60px -160px','-130px -160px'],w:60,h:80},
    bigcolumns:{bg:['-60px -240px','-130px -240px'],w:60,h:80},
    bigfoto:{bg:['-200px -80px','-270px -80px'],w:60,h:80},
    bigcomment:{bg:['-200px -160px','-270px -160px'],w:60,h:80},
    greenbutton:{bg:['-200px -240px'],w:70,h:30,type:'icon'},
    ffolder:{bg:['-270px -240px'],w:20,h:20,type:'icon'},
    ffile:{bg:['-270px -260px'],w:20,h:20,type:'icon'},
    xexit:{bg:['0 -100px'],w:13,h:12,type:'icon'} ,
    xhelp:{bg:['-20px -100px'],w:18,h:18,type:'icon'} ,
    xbar:{bg:['-290px -240px'],w:31,h:54,type:'icon'} ,
    xbardivider:{bg:['-323px -240px'],w:2,h:54,type:'icon'} ,
    xbarsepar:{bg:['-325px -240px'],w:2,h:27,type:'icon'} ,
    xupdn:{bg:['-40px -80px'],w:12,h:14,type:'icon'} ,
    xltrt:{bg:['-40px -100px'],w:12,h:14,type:'icon'} ,
    xup:{bg:['-20px -120px'],w:12,h:14,type:'icon'} ,
    xdn:{bg:['-40px -120px'],w:12,h:14,type:'icon'} ,
    xlt:{bg:['-20px -140px'],w:12,h:14,type:'icon'} ,
    xrt:{bg:['-40px -140px'],w:12,h:14,type:'icon'} ,
    // большие панели на список инфоблоков
    pgreen:{bg:['-320px 0','-360px 0'],w:32,h:65,type:'tree','class':['','hiddenitem']} ,
    // сепаратор для панели инфоблоков
    pseparator:{bg:['-354px 0'],w:3,h:65,type:'icon'} ,
    // крестик закрытия
    pdel:{bg:['0 -60px','-20px -60px'],w:13,h:12} ,
    // стрелки вверх-вниз
    p_up:{bg:['-20px -40px','-30px -40px'],w:8,h:8} ,
    p_dn:{bg:['-40px -40px','-50px -40px'],w:8,h:8} ,
    // метка для инпута
    p_sqr:{bg:['-40px -60px'],w:13,h:12,type:'icon'} ,
    nothing:''
};

    $.fn._4state = function (o) {
        var $this = $(this);
        if ($this.length == 0) return;
        if ($this.length > 1) {
            var func = arguments.callee;
            $this.each(function () {
                func.call(this, o)
            });
            return;
        }
        if (!o) {
            var x = [];
            for (var a in states)if(states.hasOwnProperty(a))x.push(a);
            var className = $this[0].className, reg = new RegExp(x.join('|'));
            if (x = className.match(reg)) {
                if (!states[x[0]].type)
                    o = {name:x[0]};
                else
                    return this;
            } else {
                return $this;
            }
        }

        if (typeof(o) == 'string') o = {name:o};
        if (!(o || {}).name) return this;
        if (!!this._4state) return this;
        this._4state=true;
        return $this.hover(
            function () {
                if (!$(this).attr('disabled'))setState(this, o.name, 1);
            }
            ,function () {
                setState(this, o.name, $(this).attr('disabled') ? 3 : 0);
            }
        ).mousedown(function () {
                if (!$(this).attr('disabled'))
                    setState(this, o.name, 2);
            })
            .mouseup(function () {
                if (!$(this).attr('disabled'))
                    setState(this, o.name, 1);
            })
            .trigger('mouseout');
    };

    function setState(e, obj, n) {
        n = Number(n) || 0;
        if (!states[obj].bg[n]) n = 0;
        e.style.backgroundPosition = states[obj].bg[n];
        if (states[obj].col) {
            if (!states[obj].col[n]) n = 0;
            if (states[obj].col[n] === 'false') {
                e.style.color = 'inherit';
            } else
                e.style.color = states[obj].col[n];
        }
    }

    $('._states')._4state();

    var option;

    function ajax(o) {
        $.ajax({
            url:o.url || $(document.body).attr('data-root'),
            data:o.data || null,
            type:o.type || 'get',
            complete:function (xmr, status) {
                var idx = 0, realtext = '', data, text = xmr.responseText.split('}');
                while (idx < text.length && typeof(data) == typeof(undefined)) {
                    realtext += text[idx++] + '}';
                    try {
                        data = JSON.parse(realtext);
                    } catch (e) {
                    }
                    // выковыриваем
                }
                if (typeof(data) != typeof(undefined)) {
                    o.complete && o.complete(data);

                    if (data.error) {
                        $('#error').html(data.error).show('slow');
                    } else {
                        $('#error:visible').html('').hide('slow');
                    }
                    if (data.debug) {
                        if (!!window.console)
                            console.log('debug: ' + data.debug);
                    }
                    if (data.log) {
                        $('.content', '#log').html(data.log);
                        $('#log').show('slow');
                    }
                } else {
                    $('#error').html('server error').show('slow');
                }
            }
        })
    }

    window.ADMIN = function (action, o) {
        if (typeof(action) != 'string') {
            if (typeof(o) == 'undefined')
                o = action;
            action = 'create';
        }

        function set_option(o) {
            $.extend(option, o);
            $('#xsite-adm').data('admin', option);
        }

        function create(o) {
            set_option(o);
        }

        option = $('#xsite-adm').data('admin') || {mode:''};
        var data;

        switch (action) {
            case 'updatemenu':
                ADMIN('reload');
                break;
            case 'preview':
                var location = document.location.toString().replace(/\/admin/, '');
                window.open(location, 'preview');
                break;
            case 'add':
                // проверяем, что вызвало такое событие
                if (o.element) {
                    data = $(o.element).attr('data-context');
                    if (!data) {
                        data = $(o.element).parents('[data-context]').eq(0).attr('data-context');
                    }
                    win_confirm('Введите название раздела', function (name) {
                        ajax({
                            type:'post',
                            data:'handler=Sitemap::add&name=' + encodeURIComponent(name)
                                + '&data=' + encodeURIComponent(data),
                            complete:function () {
                                ADMIN('updatemenu');
                            }
                        })
                    })
                }
                else
                    console.log(event);
                break;
            case 'del':
                // проверяем, что вызвало такое событие
                if (o.element) {
                    data = $(o.element).attr('data-context');
                    if (!data) {
                        data = $(o.element).parents('[data-context]').eq(0).attr('data-context');
                    }
                    win_alert('Удалить раздел?',
                        function () {
                            ajax({
                                type:'post',
                                data:'handler=Sitemap::delete&&data='
                                    + encodeURIComponent(data),
                                complete:function () {
                                    ADMIN('updatemenu');
                                }
                            })
                        })
                }
                else
                    console.log(event);
                break;
            case 'create':
                create(o);
                break;
            case 'setopt':
                set_option(o);
                break;
            case 'htmleditor':
                try{ eval('data='+($(o).parents().filter('[data-element]').eq(0).attr('data-element')||'false'));}catch(e){}
                var $dialog = $(o.template || '#htmleditor');
                $('input[name=eid]').val(data.eid||0);
                $('input[name=type]').val(data.type||'');
                if (o.msg) {
                    $('.inner', $dialog).html(o.msg);
                }
                if (!ADMIN.tinymce) {
                    ADMIN.tinymce = true;
                    $('textarea.tinymce', $dialog).tinymce({
                        // Location of TinyMCE script
                        script_url:$(document.body).attr('data-root') + '/js/tiny_mce/tiny_mce.js',

                        // General options
                        theme:"advanced",
                        plugins:"autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",

                        // Theme options
                        theme_advanced_buttons1:"save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
                        theme_advanced_buttons2:"cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
                        theme_advanced_buttons3:"tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
                        theme_advanced_buttons4:"insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
                        theme_advanced_toolbar_location:"top",
                        theme_advanced_toolbar_align:"left",
                        theme_advanced_statusbar_location:"bottom",
                        theme_advanced_resizing:true,

                        // Example content CSS (should be your site CSS)
                        content_css:"css/content.css",

                        // Drop lists for link/image/media/template dialogs
                        template_external_list_url:"lists/template_list.js",
                        external_link_list_url:"lists/link_list.js",
                        external_image_list_url:"lists/image_list.js",
                        media_external_list_url:"lists/media_list.js",

                        // Replace values for the template plugin
                        template_replace_values:{
                            username:"Some User",
                            staffid:"991234"
                        }
                    });
                }
                ADMIN('modal', new Modal($dialog, {
                    onclose:function (ok) {
                        if (ok && callback) callback();
                    }
                }));
                break;
            case 'saveform': // кнопка - сохранить форму
                if(o && o.form){
                    var form=o.form;
                    //$(form).on('submit',function(){return false;});
                    ajax({
                        data:$(form).serialize(),
                        type:'post',
                        complete:function () {
                            ADMIN('close');
                        }
                    })
                }
                break;
            case 'menuParam':
            case 'pageParam':
                data=(action=='menuParam'
                    ?$(o.element).attr('data-context')
                    :$(document.body).attr('data-id'));
                if(data)
                win_dialog({
                    remote:$(document.body).attr('data-root') + 'Sitemap/param?id='+data
                });
                break;
            case 'showpar':
                win_dialog({
                    remote:$(document.body).attr('data-root') + 'param'
                });
                break;
            case 'modal':
                if (option.mode == 'modal') return;
                option.mode = 'modal';
                set_option({modal:o});
                option.modal.show();
                break;
            case 'ok':
            case 'close':
            case 'cancel':
                if (option.mode == 'modal') {
                    option.mode = '';
                    option.modal.hide(action == 'ok');
                    option.modal = null;
                    option.onclose = null;
                    option.oninit = null;
                }
                break;
            case 'logout':
                win_alert('Вы уверены, что хотите выйти?', function () {
                    ajax({
                        url:$(document.body).attr('data-root') + '/User/logout',
                        complete:function () {
                            ADMIN('reload');
                        }
                    })
                });
                break;
            case 'reload':
                if (cookie('debug')) {
                    win_alert('Перезагрузить?'
                        , function () {
                            window.location.reload();
                        });
                } else {
                    window.location.reload();
                }
                break;
            case 'help':
                win_alert('Вы уверены, что хотите удалить элемент "текст"?'
                    , function () {
                        alert('ok')
                    });
                break;
            case 'addFoto':
                // добавить фото в галлерее
                var $data = 'handler=Page::attr';
                delete o.element;
                for (var a in o) {
                    if (o.hasOwnProperty(a))
                        $data += '&' + encodeURIComponent(a)
                            + '=' + encodeURIComponent(o[a]);
                }
                ajax({
                    type:'post',
                    url:$(document.body).attr('data-root'),
                    data:$data,
                    complete:function () {
                        ADMIN('reload');
                    }
                });
                break;
            case 'deleteEl':
                // определяем тип элемента
                try{ eval('data='+($(o).parents().filter('[data-element]').eq(0).attr('data-element')||'false'));}catch(e){}
                win_alert('Вы уверены, что хотите удалить элемент "'+(data.type||'x3')+'"?'
                    , function () {
                        data.handler= 'Page::delete';
                        data.id= $(document.body).attr('data-id');
                        ajax({
                            type:'post',
                            url:$(document.body).attr('data-root'),
                            data:data,
                            complete:function () {
                                ADMIN('reload');
                            }
                        });
                    });
                break;
            case 'appendText':
            case 'appendFoto':
                var t = {appendText:'text', appendFoto:'foto'}[action] || 'text';
                ajax({
                    type:'post',
                    url:$(document.body).attr('data-root'),
                    data:'handler=Page::append&type=' + t
                        + '&id=' + $(document.body).attr('data-id'),
                    complete:function () {
                        ADMIN('reload');
                    }
                });
                break;
        /**
         * TODO: save, preview, showpar, help, search({val:xxx}) ,
         */
            /*
             case 'delNode':
             deleteRow(option.activeNode);
             break;
             case 'update':
             $.extend(option,o);
             update.call(this);
             break;  */
        }

        return false;
    };

    $('#modulelist a').click(function () {
        win_dialog({
            remote:$(this).attr('href')
        });
        return false;
    });


    $(document).contextMenu({
        hotkey:{
            'Esc':'cancel',
            32:'contextMenu',
            93:'contextMenu',
            37:'keyright',
            38:'keyup',
            39:'keyleft',
            40:'keydown',
            Del:'del',
            'Alt-R':'rename',
            'Enter':'open',
            'default':'open'
        },
        menu:function (/* o */) {
            var menu = $(this).add($(this).parents()).filter('[data-contents]').eq(0).attr('data-contents');
            if (!menu) return false;
            return JSON.parse('["'
                + menu.replace(/\|/g, '","')
                .replace(/\]\"/g, '"]}')
                .replace(/(\"[^\"]+)\[/g, '{"title":$1","children":["')
                + '"]');

        },
        action:function (act) {
            // alert(act)
            ADMIN(act, {element:this});
        }
    });

})
;