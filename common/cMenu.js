/**
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
    F1: 112,
    F2: 113,
    F3: 114,
    F4: 115,
    F5: 116,
    F6: 117,
    F7: 118,
    F8: 119,
    F9: 120,
    F10: 121,
    Space: 32,
    BackSpace: 8,
    Tab: 9,
    Enter: 13,
    Shift: 16,
    Ctrl: 17,
    Alt: 18,
    CapsLock: 20,
    Esc: 27,
    Insert: 45,
    PageUp: 33,
    PageDown: 34,
    End: 35,
    Home: 36,
    Back: 37,
    Up: 38,
    Right: 39,
    Down: 40,
    Del: 46,
    PrintScreen: 44,
    ScrollLock: 145,
    Pause: 19,
    NumLock: 144
};

var options;
function keypress(e) {
    if (options._nokeyboard) return;
    if (options._displayed) {
        // движения клавиш - обрабатывает меню
        var key = e.keyCode;
        e.keyCode = 0;
        //console.log(e);
        switch (key) {
            case 37: //left
                if ($('LI.hover>ul>li.hover', options._menu).length > 0) {
                    $('LI.hover>ul>li', options._menu).removeClass('hover');
                }
                break;
            case 39: // right
                if ($('LI.hover>ul>li', options._menu).length > 0) {//есть 2 уровень
                    if ($('LI.hover>ul>li.hover', options._menu).length == 0) { // пока еще не там
                        // переходим на нижний уровень
                        $('LI.hover>ul', options._menu).find('LI').filter(':not(.disabled,.separator)').eq(0).addClass('hover');
                    }
                }
                break;
            case 38:
            case 40: // up
                var pos, func;
                if (key == 38) {
                    pos = 'last';
                    func = 'prevAll'
                }
                else {
                    pos = 'first';
                    func = 'nextAll'
                }
                if ($('LI.hover', options._menu).length == 0) {
                    $('LI:' + pos, options._menu).addClass('hover');
                } else {
                    //уровень?
                    if ($('LI.hover>ul>li.hover', options._menu).length > 0) {
                        $('LI.hover>ul>li.hover', options._menu).removeClass('hover')[func]('LI').filter(':not(.disabled,.separator)').eq(0).addClass('hover');
                        if ($('LI.hover>ul>li.hover', options._menu).length == 0) $('LI.hover>ul>li:' + pos, options._menu).addClass('hover');
                    } else {
                        $('LI.hover', options._menu).removeClass('hover')[func]('LI').filter(':not(.disabled,.separator)').eq(0).addClass('hover');
                        if ($('LI.hover', options._menu).length == 0) $('LI:' + pos, options._menu).addClass('hover');
                    }
                }
                break;
            case 13: // enter
                $('LI.hover>A', options._menu).last().trigger('click');
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
    if (!$(e.target).is('input,textarea')) {
        if (options.hotkey[e.keyCode]) {
            var action = options.hotkey[e.keyCode];
            if (!options._disabled[action.action]
                && e.altKey == action.alt
                && e.ctrlKey == action.ctrl
                && e.shiftKey == action.shift
            ) {
                if (options.action)
                    options.action.call(null, action.action, e);
                e.preventDefault();
                e.stopPropagation();
                e.result = false;
                return false;
            }
        }
    }
}

function showMenu(tgtElement, X, Y) {
    var _menu = getMenu(tgtElement);//options.menu.call(srcElement);
    if (!_menu) return;
    if (options._mode != 'contextmenu') $(_menu).css('overflow-y', 'auto');
    $('li', _menu).removeClass('disabled');
    for (a in options._disabled) {
        $('a', _menu).find('[href$="#' + a + '"]').parent().addClass('disabled');
    }

    options._menu = _menu;
    // Show the menu
    if (!_menu.show_menu) {
        menu(_menu, {
            show: function () {
                if (options.show)options.show.call(this);
                options._displayed = true;

                $(this).fadeIn(options.inSpeed)
            },
            hide: function () {
                if (options.hide)options.hide.call(this);
                options._displayed = false;

//                    $(document).unbind('keydown',keypress);
                if (options._xmenu) {
                    $(options._xmenu).remove();
                    options._xmenu = false;
                }
                $(this).fadeOut(options.outSpeed)
            }
        });
        $('A', _menu).mouseover(function () {
            $(_menu).find('LI.hover').removeClass('hover');
            $(this).parents('LI').addClass('hover');
        }).click(function (event) {
            _menu.hide_menu();
            if (options._mode == 'select') {
                event.target=tgtElement;
                if (options.set_text) {
                    options.set_text.call(tgtElement, $(this).attr('href').substr(1))
                }
            } else if (options.action) {
                event.target=tgtElement;
                options.action.call(tgtElement,
                    $(this).attr('href').substr(1),
                    event
                )
            }
            return false;
        });
    }
    $(_menu).css({ top: Y, left: X });
    _menu.show_menu();
}

function _getMenu(menu, el) {
    if (typeof(menu) == 'function') {
        menu = menu.call(el, options);
    }
    if (menu instanceof Array) {
        // строим меню
        var xmenu = $('<ul/>').addClass("contextMenu");
        if (options.class) {
            xmenu.addClass(options.class);
        }
        for (var i in menu) {
            var line = menu[i];
            if (line == '') {
                xmenu.append('<li class="separator"></li>');
                continue;
            }
            if (typeof(line) == 'string') {
                line = line.split('#');
                if(line[0].substr(0,1)=='~'){
                    line[0]=line[0].substr(1);
                    options._defaultaction=line[1] || line[0];
                }
                line = {'title': line[0], 'href': line[1] || line[0]};
                var xx = $('<li><a href="#' + line.href + '">' + line.title.replace(' ', '&nbsp;') + '</a></li>');
                if (line.href == options._defaultaction)
                    $('a', xx).addClass("default");
                if (options._act_hk[line.href])
                    $('a', xx).before("<span class='shortcut'>" + options._act_hk[line.href] + "</span>");
                xmenu.append(xx);
            } else if (line.children) {
                $('<li><span class="regedit-icon-trig"></span><a href="#' + (line.href || '') + '">' + line.title + '</a>'
                    + '</li>').append(_getMenu(line.children, el)).appendTo(xmenu);
            }
        }
        return xmenu[0];

    } else if (typeof(options.menu) == 'string') {
        // ищем селектор
        menu = $(options.menu)[0]
    }
    return menu;
}

function getMenu(el) {
    return options._xmenu = $(_getMenu(options.menu, el)).appendTo(options.parent || document.body)[0];
}

function create(o) {
    if ($(this).data('contextMenu'))
        return;
    options = {
        slowClick_timer: null,
        slowClick_low: 400,
        slowClick_high: 2000,

        inSpeed: 150,
        outSpeed: 75,
        menu: function () {
            return $($(this).data('contextmenu'))[0]
        },
        hotkey: {},
        _disabled: {},       // комплект задизейбленых акций
        _defaultaction: '',  // акцио по даблклику
        _act_hk: {},         // клавиатурные сокращения акций
        _displayed: false,   // показывается или нет
        _nokeyboard: false,
        empty: ''
    };
    if (!o) o = {};
    else if (typeof(o) == 'string')
        o = {xxx: o};
    var hotkey = o.hotkey || {},
        reg = /^((\d*)|(Ctrl?[-+ ])?(Alt[-+ ])?(Shift?[-+ ])?(\w+)~?)$/i;
    if (o.hotkey) delete (o.hotkey);
    $.extend(options, o);
    for (var a in hotkey) {

        var idx = a
            , res = reg.exec('' + a)
            , key = {ctrl: false, shift: false, alt: false, action: hotkey[a]};
        if (!res)
            continue;
        if (res[3]) {
            key.ctrl = true;
        }
        if (res[4]) {
            key.alt = true;
        }
        if (res[5]) {
            key.shift = true;
        }
        if ('default' == res[6]) {
            options._defaultaction = hotkey[a];
        } else if (res[6]) {
            if (CMenu_keymap[res[6]]) {
                idx = CMenu_keymap[res[6]];
            } else if (res[6].length == 1) {
                idx = res[6].toLowerCase().charCodeAt(0);
                options.hotkey[idx] = key;
                idx = res[6].toUpperCase().charCodeAt(0);
            }
            options._act_hk[hotkey[a]] = a;
        }
        options.hotkey[idx] = key;//hotkey[a];
    }
    $(this).data('contextMenu', options);

    // Defaults
    function mouseup(event) {
        event.stopPropagation();
        var tgtElement = event.target;
        $(this).unbind('mouseup', mouseup);
        // Hide context menus that may be showing
       // $(".contextMenu").hide();
        // Get this context menu
        options._mode = 'contextmenu';
        showMenu(tgtElement, event.pageX + 1, event.pageY + 1);
    }

    this.mousedown(function (e) {
        if (e.button == 2) {
            $(this).mouseup(mouseup);
            //e.stopPropagation();
        }
    }).bind('contextmenu', function (e) {
            if ($(e.target).is('input,textarea')) return;
            if (e.ctrlKey) return;
            return false;
        });
    $(document)
        .bind('keydown', keypress)
        .bind('dblclick', function (e) {
            if (options.slowClick_timer)
                clearTimeout(options.slowClick_timer);
            options._lasttgt = null;
            if(!options._menu){
                options._menu = getMenu(e.target);
            }
            if (options._defaultaction)
                if (options.action)
                    options.action.call(e.target,
                        options._defaultaction,
                        event
                    );
        })
        .click(function (event) {
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
                if (!options._displayed && options.action)
                    options.action.call($tgt,
                        'slowdbl',
                        event
                    );
            }
        });
}

$.fn.contextMenu = function (action, o, o2) {


    if (typeof(action) == 'string') {

    } else {
        if (typeof(o) == 'undefined')
            o = action;
        action = 'create';
    }

    var a, x, pos;
    switch (action) {
        case 'create': // создать меню и поставить хандлеры
            create.call(this, o);
            break;
        case 'keyboard':
            options = $(this).data('contextMenu');
            options._nokeyboard = !o;
            break;
        case 'enable':
            options = $(this).data('contextMenu');
            x = o.split(',');
            for (a in x)
                if (options._disabled[a])
                    delete options._disabled[a];
            break;
        case 'disable':
            options = $(this).data('contextMenu');
            x = o.split(',');
            for (a in x)
                options._disabled[x[a]] = true;
            break;
        case 'select': // показать меню в стиле select
            options = $(this).data('contextMenu');
            options._mode = 'select';
            if (!o2) o2 = {};
            $.extend(options, o2);
            if (o instanceof $) {
                pos = o.position();
                showMenu.call(this, o, pos.left, pos.top + o.height());
            } else {
                console.log('Блин!');
            }
            break;
        case 'show': // показать меню
            options = $(this).data('contextMenu');
            options._mode = 'contextmenu';
            if (typeof(o) == 'string')
                o = $(o, this);
            else if (!o)
                o = $(this);
            if (o.target)
                showMenu.call(this, o.target, o.pageX + 1, o.pageY + 1);
            else if (o instanceof $) {
                pos = o.position();
                if (options._position == 'down')
                    showMenu.call(this, o, pos.left, pos.top + o.height() - 1);
                else
                    showMenu.call(this, o, pos.left + (o.width() >> 1), pos.top + o.height() - 3);
            } else {
                pos = o.position();
                showMenu.call(this, o, pos.left + 20, pos.top + 20);
            }
            break;
    }

    return this;
};
