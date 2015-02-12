/**
 *  скрипт админки
 *
 * <% POINT::start('js_prefix');
 require_once('../../../common/debug.js');
 require_once('../../../common/menu.js');
 require_once('../../../common/cMenu.js');
 require_once('../../../common/lofty.js');
 require_once('../../../common/cookie.js');
 require_once('../../../common/treemenu.js');
 require_once('../../js/modal.js');
 require_once('../../js/winalert.js');
 point_finish();
 echo POINT::get('hat','comment');%>
 */

$(function () {
    /* <%=POINT::get("js_prefix"); %>*/
    /* <%=POINT::get('js_body');%> */
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
            case 'createPicture':
                try{ eval('data='+($(o.self).parents().filter('[data-element]').eq(0).attr('data-element')||'false'));}catch(e){}
                data.handler='Page::insertAttr';
                data.attr='picture';
                data.file=o.file;
                ajax({
                    type:'post',
                    url:$(document.body).attr('data-root'),
                    data:$.param(data),
                    complete:function () {
                       // ADMIN('reload');
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
