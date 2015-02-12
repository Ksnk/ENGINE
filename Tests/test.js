//<input id="xxx">
//<button onclick="alert($('#xxx').val())">click</button>

$.fn.caret=function(begin, end) {
    if (this.length == 0) return;
    if (typeof begin == 'object'){
        begin=begin.begin;
        end=bein.end;
    }
    if (typeof begin == 'number') {
        end = (typeof end == 'number') ? end : begin;
        return this.each(function() {
            if (this.setSelectionRange) {
                this.setSelectionRange(begin, end);
            } else if (this.createTextRange) {
                var range = this.createTextRange();
                range.collapse(true);
                range.moveEnd('character', end);
                range.moveStart('character', begin);
                range.select();
            }
        });
    } else {
        if (this[0].setSelectionRange) {
            begin = this[0].selectionStart;
            end = this[0].selectionEnd;
        } else if (document.selection && document.selection.createRange) {
            var range = document.selection.createRange();
            begin = 0 - range.duplicate().moveStart('character', -100000);
            end = begin + range.text.length;
        }
        return { begin: begin, end: end };
    }
};


$(function(){
    $('#xxx').on('keyup',function(){
        var caret=$(this).caret();
        $(this).val(this.val().toLocaleLowerCase().replace(/([A-Za-z]*)/g,''));
        $(this).caret(caret);
    })
});