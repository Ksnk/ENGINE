<?php
/**
 * Клиентская часть загрузчика
 */
?>
<% POINT::start('plugins_html') %>
<iframe src='about:blank' id='uploadFrame' name='uploadFrame' class="hidden">
</iframe>

<form id="uploader" class="nocontext flyaway" target="uploadFrame" action=""
      method="POST" enctype="multipart/form-data">
    <input type="hidden" name="handler" value="Main::upload">

    <input type="file" name="file[]" multiple="multiple" >
    <input type="submit" value="Submit">
</form>

<% POINT::finish() %>

<script type="text/javascript">
    <% POINT::start('js_body') %>
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
    <% if(false) { %>

    $(document).on('mouseover', '.upload', function () {
        active_element = this;
        var $this = $(this);
        $this.addClass('hover');// emulate mouse hover
        var offset = $this.offset();
        $('#uploader').css({display:'block', opacity:0.02,
            top:offset.top, left:offset.left,
            width:$this.width(), height:$this.height()
        });

 /*       function upload_OnComplete() {
            if (!$complete) {
                toggleWait(null, true);
                alert('Загрузка прервана!');
            }
            element.$('uploadForm').reset();
        }  */
    });
        <% } %>
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
    <% POINT::finish() %>
</script>

<?php
function do_file_uploader()
{
    if (!$this->parent->has_rights(right_WRITE)) {
        $this->parent->error('Нужно авторизоваться!!!!!');
        return ' ';
    }
    $this->parent->sessionstart();
    $this->parent->tpl = array(ELEMENTS_TPL, 'ajax');
    ob_start();
    $result = '';
    $this->parent->ajaxdata = array();
    $form = new form('form',
//	array(CNT_INPUT,'MAX_FILE_SIZE'),
        array(CNT_INPUT, 'xaction'),
        array(CNT_INPUT, 'file', 'file')
    );
// print_r($_FILES); echo $form->upload_dir;
// print_r($_POST);
    if ($form->handle()) {
        ini_set('memory_limit', '128M');
//echo'Hello!'; print_r($_FILES) ;
        $x = array_keys($form->files);
        if (empty($x)) return 'не удалось загрузить файлы!';
        $x = pps($x[0]);
        if (basename($x) != $form->files[$x]) {
            @unlink($x);
            $x = dirname($x) . '/' . $form->files[$x];
        }
        $w = ppi($this->parent->getPar('pictute_xwidth'), 100);
        $h = ppi($this->parent->getPar('pictute_xheight'), 80);
        $xw = ppi($this->parent->getPar('pictute_xxwidth'), 800);
        $xh = ppi($this->parent->getPar('pictute_xxheight'), 600);
        $bg = $this->parent->getPar('pictute_background', -1);
        $width = 0;
        $result = false;
        if (pps($form->var['xaction']) == 'small' || pps($form->var['xaction']) == 'both') {
            list($width, $height, $type, $attr) = @getimagesize($x);
            $y = '';
            if (pps($width) && ($w < $width || $h < $height)) {
                require_once('pic.inc.php');
                $y = preg_replace('~\s*(\.[^\.]*)$~', '_' . $w . 'x' . $h . '\1', $x);
                $result = img_resize($x, $y, $w, $h, $bg, 70);
            }
            if (!empty($result))
                $this->parent->ajaxdata['small'] = toUrl(pps($y, $x));
            else
                $this->parent->ajaxdata['small'] = toUrl($x);
        }
        $result = false;
        if (pps($form->var['xaction']) == 'big' || pps($form->var['xaction']) == 'both') {
            if (empty($width))
                list($width, $height, $type, $attr) = @getimagesize($x);
            $y = '';
            if (pps($width) && ($xw < $width || $xh < $height)) {
                require_once('pic.inc.php');
                $y = preg_replace('~\s*(\.[^\.]*)$~', '_' . $xw . 'x' . $xh . '\1', $x);
                $result = img_resize($x, $y, $xw, $xh, $bg, 70);
            }
            if (!empty($result))
                $this->parent->ajaxdata['big'] = toUrl(pps($y, $x));
            else
                $this->parent->ajaxdata['big'] = toUrl($x);
        }
        if (empty($width))
            list($width, $height, $type, $attr) = @getimagesize($x);
        if (!empty($y)) $x = $y;
//print_r($this->parent->ajaxdata);
        $form->files = array();
        $form->storevalues();
        $result = toUrl($x);
    } else {
        if (!empty($_POST))
            $this->parent->error('wrong!!');
    }
    $this->parent->export('fileman', 'do_read', true);
    unset($_SESSION['store_picture'], $_SESSION['store_files']);
    $result = array('data' => $result);
    if ($this->parent->par['error'])
        $result['error'] = trim($this->parent->par['error']);
    if (!empty($this->parent->ajaxdata))
        $result['result'] = $this->parent->ajaxdata;
    if ($x = ob_get_contents()) {
        $result['debug'] = trim($x);
    }
    ;
    ob_end_clean();
    return '<script type="text/javascript">
    top.upload_OnSuccess(' . php2js($result) . ');
</script>' . "\n//";
}