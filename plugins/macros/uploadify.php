<?php
/**
 * uploadify plugin integration
 *
 * to make button uploadable set it class to "upload"
 */
//<%
/**
 * this one defined additional files to support uploadify plugin
 */
$this->xml_read('
<config>
    <files dstdir="$web/admin" dir="../../../uploadify">
        <copy dstdir="uploadify">*.js</copy>
        <copy dstdir="uploadify">*.swf</copy>
        <copy dstdir="../img">uploadify-cancel.png</copy>
    </files>
</config>
');
POINT::start('admin_js_include');%>
<script src="{{root}}admin/uploadify/jquery.uploadify-3.1.js"></script>
<% POINT::finish() ;%>
?>
<style>
/**
 * some styles to support uploadify structures
 */
<% POINT::start('css_body'); // точка для вставки в css админки%>

    .uploadify-queue {
        margin-bottom: 1em;
    }

    .uploadify-queue-item {
        background-color: #F5F5F5;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        font: 11px Verdana, Geneva, sans-serif;
        margin-top: 5px;
        max-width: 350px;
        padding: 10px;
    }

    .uploadify-error {
        background-color: #FDE5DD !important;
    }

    .uploadify-queue-item .cancel a {
        background: url('../img/uploadify-cancel.png') 0 0 no-repeat;
        float: right;
        height: 16px;
        text-indent: -9999px;
        width: 16px;
    }

    .uploadify-queue-item.completed {
        background-color: #E5E5E5;
    }

    .uploadify-progress {
        background-color: #E5E5E5;
        margin-top: 10px;
        width: 100%;
    }

    .uploadify-progress-bar {
        background-color: #0099FF;
        height: 3px;
        width: 1px;
    }
<% POINT::finish() ;%>
</style>
    <script type="text/javascript">
<% POINT::start('js_body');%>
var root=$(document.body).attr('data-root');
if($.fn.uploadify)$('#upload_input').uploadify( {
    buttonText    :'&nbsp;',
    height        : 14,
    formData : { handler:'Main::uploadify'},
    overrideEvents: ['onDialogClose'],
    swf           : root+'uploadify/uploadify.swf',
    uploader      : root+'admin/?ajax=1',
    width         : 60 ,
    onUploadSuccess : function(file, data, response) {
        try {
            data = JSON.parse(data);
            if(data.data!='1')
                alert(data.data);
            else {
                var active=$('#uploader').data('active');
                var x=JSON.parse(active.attr('data-upload'));
                ADMIN(x.action,{self:active,'file':data.file});
            }
            console.log(data);
        } catch (e) {
        }
    },
    onDialogClose : function(queueData) {
        var active=$('#uploader').css({display:'block', opacity:1,
            top:0, left:0,
            width:'auto', height:'auto'
        }).data('active');
        active.removeClass('hover');
    }
});
$('#uploader').mouseleave(function(){
    var active=$('#uploader').data('active');
    if(active)
        active.removeClass('hover');
}).mouseenter(function(){
    var active=$('#uploader').data('active');
    if(active)
        active.addClass('hover');
});
$(document).on('mouseover', '.upload', function () {
    var $this = $(this);
    $('.upload.hover').removeClass('hover');
    $this.addClass('hover');// emulate mouse hover
    var offset = $this.offset()
        ,data=JSON.parse($this.attr('data-upload'));
    if(data.mask=='picture'){
        $('#upload_input').uploadify('settings','fileTypeDesc','Image Files');
        $('#upload_input').uploadify('settings','fileTypeExts','*.gif; *.jpeg; *.jpg; *.png');
    } else if(data.mask=='files'){
        $('#upload_input').uploadify('settings','fileTypeDesc','All Files');
        $('#upload_input').uploadify('settings','fileTypeExts','*.*');
    } else{
        $('#upload_input').uploadify('settings',data.mask);
    }
    $('#uploader').css({
        display:'block', opacity:0.02,
        top:offset.top, left:offset.left,
        width:$this.width(), height:$this.height()
    }).data('active',$this);
});
<% POINT::finish() ;%>
    </script>
<% POINT::start('plugins_html')%>
<form id="uploader" class="nocontext" style="position:absolute;top:0;left:0">
    <input type="file" id="upload_input" name="file[]" multiple="multiple" >
</form>
<% POINT::finish() ;%>



<?php
/*<% POINT::start('admin_extension'); %>*/
    function do_uploadify(){
        if (!ENGINE::has_rights(RIGHT::siteAdmin)) {
            return 'Недостаточно прав для совершения операции';
        }
        // Define a destination
        $targetFolder = ENGINE::option('upload_dir');

        if (!empty($_FILES)) {
            $tempFile = $_FILES['Filedata']['tmp_name'];
            $targetPath = $targetFolder;
            $targetFile = rtrim($targetPath,'/') . '/' . translit($_FILES['Filedata']['name']);

            if(!move_uploaded_file($tempFile,$targetFile))
                return 'file cannot be moved';
            ENGINE::set_option('ajax.file',$this->path2url($targetFile));
          /*  ENGINE::set_option('ajax.name',$targetFile);
            ENGINE::set_option('ajax.sitepath',INDEX_DIR);
            ENGINE::set_option('ajax.page.rootsite', ENGINE::option('page.rootsite'));
            ENGINE::set_option('ajax.page.root', ENGINE::option('page.root'));
            ENGINE::set_option('ajax.SCRIPT_NAME', $_SERVER['SCRIPT_NAME']);
            ENGINE::set_option('ajax.document.root',realpath($_SERVER['DOCUMENT_ROOT']));

            ENGINE::set_option('ajax.tmp1',
                str_replace(rtrim(ENGINE::option('page.rootsite'),'/'),'',$this->xslash(INDEX_DIR)).ENGINE::option('page.root'));  */
            return  1;
        }
        return 'No files uploded';
    }
/*<% POINT::finish() ;%>*/