<style>
/* разметка некоторых участков шаблона */
<% point_start('css_site');

// меню 2-х уровневое, элементы разделяются палочками. 
// внизу строчка меню отчеркнута линией 
// ... некоторые цвета...

//$border_color = '';
$ulmenu_border_color='rgb(222,225,227)';//rgb(165,154,125)';
$ulmenu_text_color='rgb(99,111,125)';
$menu_height=36;//32;
$menu_padding='12px';
$ulmenu_hover_color = '#2a2a2c';
/**
 *  пример вставки на страничку
 *
<div class="ulmenuContainer" STYLE="margin-bottom:32px;">
<ul class="ulmenu">{::menu:top}</ul>	
</div>
*/

/*
function translit($s)
{
	$s=urldecode($s);
	$s=str_replace('"',"&quot;",$s); // сохраняем кавычки
	
	$s= strtr(strtoupper($s),array(
                  "ЫА"=>"yha",
                  "ЫО"=>"yho",
                  "ЫУ"=>"yhu",
                  "Ё"=>"yo",
                  "Ж"=>"zh"));
	$s = strtr($s, "АБВГДЕЗИЙКЛМНОПРСТУФХЦ"
				 , "abvgdezijklmnoprstufxc");
	$s= strtr($s,array( 
                  "Ч"=>"ch",
                  "Ш"=>"sh",
                  "Щ"=>"shh",
                  "Ъ"=>"qh",
                  "Ы"=>"y",
                  "Ь"=>"q",
                  "Э"=>"eh",
                  "Ю"=>"yu",
                  "Я"=>"ya",
                  " "=>"_",
 				  "№"=>"n",
				  '"'=>"&quot;"
	)) ;
	$s= strtr($s,array(
		'&'=>'',
		'#'=>'',
		';'=>'',
		'*'=>'',
		'?'=>''
	)); 
	for($i=0;$i<strlen($s);$i++){
		if($s{$i}>"\x80")$s{$i}='_';
	}
	
    return strtolower($s);              
}

$x=glob('img/рус/*.gif');
foreach($x as $f){
	copy($f,'img/'.translit(basename($f)));
}
*/
 %>
<%/* часть про механику меню*/%>
div.ulmenuContainer {
	height:<%=$menu_height%>px;
	z-index:1000;
} 
ul.ulmenu {
	font-weight:bold;
	z-index:100;
	padding:0; margin:0; 
	list-style: none;
}
ul.ulmenu ul{
	list-style: none;
	z-index:1000;
	zoom:1;
}
ul.ulmenu li {
	float:left; position:relative;
	display:block;
	padding:0 <%=$menu_padding%>;
	height:<%=$menu_height%>px;
    list-style: none;
}
    ul.ulmenu li ul {
        position:absolute;
        top:<%=$menu_height-6%>px; left:0;
        width:auto;
        display: none;
    }
    ul.ulmenu li a {
        display:block ; /*float:left;*/
        cursor:pointer;
    }
    ul.ulmenu li ul li{
        float:none;
        display:block;
        height:auto;
    }
    ul.ulmenu li li a {
        float:none; display:block;
        white-space:nowrap;
        cursor:pointer;
    }
    <%/* часть про раскраску меню*/%>

ul.ulmenu li a {
    display:inline-block;
    padding:2px 10px 1px 10px;
    font-size:16px;
    text-decoration: none;
    text-shadow: 1px 1px 2px white;
    font-weight: bold;
    line-height:1.4em;
	color:<%=$ulmenu_hover_color%>;
}

ul.ulmenu li a:hover {
    color: white;
    text-shadow: none;
    background: #266397;
}
ul.ulmenu li a img {
	margin:8px 0 4px 0;
}

ul.ulmenu li ul {
  background:rgb(235,238,242);
	padding:8px 8px 20px 8px;
	margin:6px 0 0 0;
}

ul.ulmenu li li.first a {
	border-top:0;
}
ul.ulmenu li li a {
	border-right:none;
	border-top:1px solid <%=$ulmenu_border_color%>;
	color:<%=$ulmenu_text_color%>;
	text-decoration:none;
	font-size:14px;
	margin: 0 11px;
	padding:4px 2px 4px 2px ;
}
ul.ulmenu li ul li.level2 a{
	padding:4px 2px 4px 22px 
}
ul.ulmenu li li a:hover, ul.ulmenu li li a.current {
	background:transparent;
	color: <%=$ulmenu_hover_color%>;
}
ul.ulmenu li ul li ul{
    top:5px;
    left:100%;
}


    <% point_finish('css_site') %>
</style>
