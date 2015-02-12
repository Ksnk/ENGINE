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
$_icons_file_name = 'img/icons.' . $buildtime . '.png';
} else {
    $_icons_file_name = 'img/icons' . '.png';
}
// настройка плагина - json описание кадров
$_icons_json = <<< JSON
{ // 0 - usual, 1 hover, 2 - clicked , 4 passive
    engine:{bg:['0 0'],w:96,h:24,type:'icon'},
    online:{bg:['0 -30px'],w:62,h:20,type:'icon'},
    xilen:{bg:['0 -50px'],w:100,h:40,type:'icon'},
    mail:{bg:['-100px 0','-100px -40px'],w:14,h:10,type:'hover'},
    home:{bg:['-115px 0','-115px -40px'],w:14,h:10,type:'hover'},
    sitemap:{bg:['-130px 0','-130px -40px'],w:14,h:10,type:'hover'},
    book:{bg:['-100px -10px'],w:14,h:10,type:'icon'},
    page:{bg:['-115px -10px'],w:14,h:10,type:'icon'},
    arrow:{bg:['-115px -10px'],w:14,h:10,type:'icon'},
    cross:{bg:['-130px -20px'],w:14,h:10,type:'icon'},
    zoom:{bg:['-100px -20px'],w:20,h:20,type:'icon'},
    arrrt:{bg:['0 -90px','-37px -90px'],w:36,h:40,type:'hover'},
    arrlt:{bg:['-74px -90px','-112px -90px'],w:36,h:410,type:'hover'},
    nothing:''
}
JSON;

//
%>
?>
<style type="text/css">
    <% POINT::start('css_site');
    echo '
._states {
    outline:none;
    display:inline-block;
    background: url(../' . $_icons_file_name . ') no-repeat 0 0;
    border: 0;
    cursor: pointer;
}
a._states {
    text-decoration:none;
    border:0;
}
';
    $json = str_replace("'", '"', preg_replace(array('#//.*?$#m', '/(\w+):/'), array("", '"\1":'), $_icons_json));
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
        if (isset($v->type) && $v->type == 'hover') {
            echo '
._states.' . $k . ':hover {
     background-position:' . $v->bg[1] . ';
}
';
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

    POINT::finish('css_site'); %>
</style>