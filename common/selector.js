/**
 * .carret jQuery plugins
 *
 * <%=point('hat','comment');%>
 */
/**
 * работа с курсором. Работаем с первым элементом коллекции.
 * .carret('get')|| .carret() - выдать позицию курсора, верхняя граница отмеченного участка
 * .carret('sel') - выдать позицию курсора, верхняя и нижняя граница отмеченного участка в массиве
 * .carret('set',X,Y)|| carret(X,Y)|| carret(X) - установить курсор в поззицию
 * .carret('is',X) - проверить, что курсор в позиции X (отмеченая область считается курсором)
 * @param act
 * @param selectionStart
 * @param selectionEnd
 */
$.fn.carret=function(act,selectionStart, selectionEnd){
    var input=this[0];
    if(!input) return 0 ;
    // анализ параметров
    if(undefined==act){
        act='get';
    } else if(typeof(act)=="number"){
        selectionEnd=selectionStart;
        selectionStart=act;
        act='set';
    } else if(undefined==selectionEnd)
        selectionEnd=selectionStart;

    if(act=='set'){
        if (input.setSelectionRange) {
            input.focus();
            input.setSelectionRange(selectionStart, selectionEnd);
        }
        else if (input.createTextRange) {
            var range = input.createTextRange();
            range.collapse(true);
            range.moveEnd('character', selectionEnd);
            range.moveStart('character', selectionStart);
            range.select();
        }
        return this;
    } else {
        $(input).focus();
        var cursor=[];
        if(typeof(input.selectionStart)!='undefined'){
            cursor=[ input.selectionStart,input.selectionEnd];
        } else
        if (document.selection) {
            for(var i=0;i<2;i++){
                var sel=document.selection.createRange();
                var clone=sel.duplicate();
                sel.collapse(0==i);
                clone.moveToElementText(input);
                clone.setEndPoint(0==i?'EndToStart':'EndToEnd',sel);
                cursor[i]=clone.text.length;
            }
        }
        if(act=='is'){
            return cursor[0]<=selectionStart && selectionStart<=cursor[1];
        }else if(act=='sel'){
            return cursor;
        } else
            return cursor[0];
    }
};
