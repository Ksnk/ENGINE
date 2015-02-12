<?php
/**
 * jQuery-file-upload plugin integration
 *
 * to make button uploadable select class "upload"
 */
//<%
$this->xml_read('
<config>
    <files dstdir="$web/fupload" dir="c:/users/Сергей/PhpStormProjects/blueimp/jQuery-File-Upload/js">
        <copy>jquery.fileupload-fp.js</copy>
        <copy>jquery.fileupload.js</copy>
        <copy>vendor/jquery.ui.widget.js</copy>
        <copy>jquery.iframe-transport.js</copy>
    </files>
</config>
');
POINT::start('admin_js_include');%>
<script src="{{root}}fupload/jquery.ui.widget.js"></script>
<script src="{{root}}fupload/jquery.iframe-transport.js"></script>
<script src="{{root}}fupload/jquery.fileupload.js"></script>
<script src="http://blueimp.github.com/JavaScript-Load-Image/load-image.min.js"></script>
<script src="http://blueimp.github.com/JavaScript-Canvas-to-Blob/canvas-to-blob.min.js"></script>
<script src="{{root}}fupload/jquery.fileupload-fp.js"></script>
<% POINT::finish(); %>

?>
<style>
    <% POINT::start('css_body'); // точка для вставки в css админки%>

    .fileinput-button {
        position: relative;
        overflow: hidden;
        float: left;
        margin-right: 4px;
    }
    .fileinput-button input {
        position: absolute;
        top: 0;
        right: 0;
        margin: 0;
        opacity: 0;
        filter: alpha(opacity=0);
        transform: translate(-300px, 0) scale(4);
        font-size: 23px;
        direction: ltr;
        cursor: pointer;
    }
    .fileupload-buttonbar .btn,
    .fileupload-buttonbar .toggle {
        margin-bottom: 5px;
    }
    .files .progress {
        width: 200px;
    }
    .progress-animated .bar {
        background: url(../img/progressbar.gif) !important;
        filter: none;
    }
    .fileupload-loading {
        position: absolute;
        left: 50%;
        width: 128px;
        height: 128px;
        background: url(../img/loading.gif) center no-repeat;
        display: none;
    }
    .fileupload-processing .fileupload-loading {
        display: block;
    }

        /* Fix for IE 6: */
    * html .fileinput-button {
        line-height: 24px;
        margin: 1px -3px 0 0;
    }

        /* Fix for IE 7: */
    * + html .fileinput-button {
        padding: 2px 15px;
        margin: 1px 0 0 0;
    }

    @media (max-width: 767px) {
        .files .btn span {
            display: none;
        }
        .files .preview * {
            width: 40px;
        }
        .files .name * {
            width: 80px;
            display: inline-block;
            word-wrap: break-word;
        }
        .files .progress {
            width: 20px;
        }
        .files .delete {
            width: 60px;
        }
    }    <% POINT::finish() ;%>
</style>
<script type="text/javascript">
    <% POINT::start('js_body');%>
    $('.upload').fileupload({
        dataType: 'json',
        add: function (e, data) {
            data.context = $('<button/>').text('Upload')
                .appendTo(document.body)
                .click(function () {
                    $(this).replaceWith($('<p/>').text('Uploading...'));
                    data.submit();
                });
        },
        done: function (e, data) {
            data.context.text('Upload finished.');
        }
    });

    <% POINT::finish() ;%>
</script>