/**
 * .editcell jQuery plugins
 * depends: jQuery, _scrollItoView, .carret
 *
 * <%=point('hat','comment');%>
 */
(function ($) {
    var options = {
        // селектор для автоматической установки редактора
        selector:'.editable',

        /** примерный CSS для элемента textarea */
        textarea:{
            width:'100%',
            height:'100%',
            outline:0,
            border:'1px #CCCCCC dotted',
            resize:'none',
            margin:0,
            overflow:'hidden',
            'min-height':'1.4em',
        },

        /** примерный CSS для контейнера div */
        css:{
            position:'absolute', border:0, display:'none'
        },

        /**
         * хандлер "дай редактируемый текст"
         * @param object options - параметры вызова
         * @return string - текст, который будет редактироваться
         * this указывает на редактируемый объект, а не на устанавливаемый объект
         */
        get_text:function (/*options*/) {
            return $(this).text()
        },

        /**
         * хандлер "получи отредактированный текст"
         * @param string txt - отредактированный редактором текст
         * @param object options - параметры вызова
         * @return string - текст, который будет вставлен в .html элементу
         * this указывает на редактируемый объект, а не на устанавливаемый объект
         */
        set_text:function (txt/*,options*/) {
            $(this).text(txt);
            return txt
        },

        /**
         * хандлер "редактор закрывается"
         * @param object options - параметры вызова
         * this указывает на редактируемый объект, а не на устанавливаемый объект
         */
        //exit:function(options){},

        empty:''
    };

    function hide() {
        if (options._control) {
            //console.log('hide');
            var val = $('textarea', options._editor).val();
            if (!options._cancel) {
                if (options.set_text.call(options._control, val, options) === false) {
                    return; // no any santaclause!
                }
                options._cancel = false;
            }
            if (options.exit)
                options.exit.call(options._control, options);
            options._editor.hide();
            $(options._control).parents('div,body').unbind('scroll', scroll);
            options._control = null;
        }
    }

    function scroll() {
        if (!cell_editor.internalScroll)
            hide();
        else
            cell_editor.internalScroll = false;
    }

    /** scroll editor window into view */
    function cell_editor(t) {
        //cell_editor.internalScroll=false;
        options.exit_key = false;
        options._cancel=false;
        cell_editor.internalScroll = true;
        _scrollIntoView(t,options);
        /** @var jQuery $self */
        var $self = $(t);
        // scroll into view
        var position = $self.offset(),
            rect = {
                height:$self.innerHeight(),
                width:$self.innerWidth(),
                top:position.top - 1, //+parseInt($self.css('padding-top')),
                left:position.left + parseInt($self.css('padding-left'))
            },
            tt = 1 + (($self.outerHeight() - $self.innerHeight()) >> 1);
        rect.top -= tt;
        tt = 1 + (($self.outerWidth() - $self.innerWidth()) >> 1);
        rect.left -= tt;

        options._editor.css(rect).show();
        $self.parents('div,body').bind('scroll', scroll);

        var txt = options.get_text.call(t, options);
        $('textarea', options._editor)
            .css({
                'padding':$self.css('padding-top') + ' 0 0 ' + $self.css('padding-left'),
                'font-size':$self.css('font-size'),
                'font-style':$self.css('font-style'),
                'font-family':$self.css('font-family')
            })
            .focus().val(txt)
            .carret('set', 0, txt.length);
        options._control = t;
    }

    function init(action, o){
        // устанавливаем редактор. Только раз в жизни
        options._editor=$('<div/>').append(
            $('<textarea></textarea>').css(options.textarea)
        ).css(options.css)
            .appendTo(document.body);
        $(document).data('editcell-editor',options._editor);

        $('textarea', options._editor)
            .blur(hide)
            //todo: не очень удачная попытка изменить размер поля редактирования
            /*             .keyup(function(){
             if(this.scrollTop>0 || this.scrollWidth>$(this).width()+10){
             $(options._editor).css('width',this.scrollWidth+10);
             this.scrollTop(0);
             }
             })*/
            .keydown(function (event) {
                var $this = $(this);
                if (event.keyCode == '13') {//enter
                    event.preventDefault();
                    hide();
                } else if (event.keyCode == '27') {// esc
                    options._cancel = true;
                    hide();
                }
                switch (event.keyCode) {
                    case 37:
                        if (!$this.carret('is', 0)) break;
                    case 38:
                        options.exit_key = event.keyCode;
                        hide();
                        event.preventDefault();
                        break;
                    case 39:
                        if (!$this.carret('is', $this.val().length)) break;
                    case 40:
                        options.exit_key = event.keyCode;
                        hide();
                        event.preventDefault();
                        break;
                }
            });
        $.fn.editcell = function (action, o) {
            if (!!o)
                $.extend(options, o);

            if (action == 'go') {
                if (this.length > 0)
                    cell_editor(this[0]);
            } else {
                this.click(function (e) {
                    var t = e.target;
                    if ($(t).is(options.selector))
                        cell_editor(t);
                });
            }
        };
        this.editcell(action,o);
    }

    $.fn.editcell = init;
})(jQuery);