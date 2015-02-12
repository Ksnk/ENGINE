<?php
/**
 * плагин для вставки 4states контролов в систему
 *
 * + формируем css классы для начальной демонстрации кнопок
 */
//<%
/**
 * типы контролов -
 * type="btn" значение по уомлчанию, получает способность менять фон по наведению, нажатию и в disabled состоянии
 *   0 состояние - обычное, 1 - ховер, 2 - clicked, 3 - disabled
 * type = "icon" - одно изображение, без дополнительных функций
 *   единственный bg
 * type = "tree" - несколько изображений, меняющихся в css с предшествующими классами
 *   bg - 0 - без дополнительных классов, классы перечислены в classes
 * настройка плагина - картинка
 *
 */
 if($target!='directory') {
$_4states_config_name = 'img/align.' . $buildtime . '.png';
 } else {
$_4states_config_name = 'img/align'  . '.png';
 }
// настройка плагина - json описание кадров
$_4states_config_json = <<< JSON
{ // 0 - usual, 1 hover, 2 - clicked , 4 passive
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
}
JSON;

//
%>
?>
<script type="text/javascript">
    <% POINT::start('js_body'); %>
    var states =<%=$_4states_config_json%>;

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

    /* <% POINT::finish('js_body'); %> */
</script>
<style type="text/css">
    <% POINT::start('css_body');
    echo '
._states {
    outline:none;
    display:inline-block;
    background: url(../' . $_4states_config_name . ') no-repeat 0 0;
    border: 0;
    cursor: pointer;
    padding:0;
}
a._states {
    text-decoration:none;
    border:0;
}
';
    $json = str_replace("'", '"', preg_replace(array('#//.*?$#m', '/(\w+):/'), array("", '"\1":'), $_4states_config_json));
    $states = get_object_vars(json_decode($json));

//$this->log(1,$states);

    foreach ($states as $k => $v) {
        if (isset($v->type) && $v->type == 'tree') {
            $xx=1;
            foreach($v->class as $kk) if(!empty($kk)) {
            echo '
.' . $kk . '>._states.' . $k . ' {
     background-position:' . $v->bg[$xx++] . ';
}
';
            }
        }
        if (isset($v->bg)) {
            echo '
._states.' . $k . ' {
     background-position:' . $v->bg[0] . ';
     width: ' . (empty($v->w) ? '0' : $v->w . 'px') . ';
     height: ' . (empty($v->h) ? '0' : $v->h . 'px') . ';
}
';
        }


    }

    POINT::finish('css_body'); %>
</style>