## editcell.js - редактор ячеек таблицы

Редактирует содержимое элемента, выводя редактор - textarea поверх занимаемого элементом прямоугольника. Делается попытка скопировать основные стили элемента в редактор, чтобы внешний вид не сильно изменился.

##Способы применения:

### проект Ledcross

Таблица, с вставленными в редактируемый элемент DIV'ами с классом `editable`.
Редактируемая часть из содержимого выделяется регуляркой. Результат редактирования вставляется вместо содержимого

    var options={
        topdisp:70, // смещение до верха. 10 по умолчанию
        botdisp:30, // смещение до низа. 10 по умолчанию
        exit: function(options){
            var newcell=false,
                parentrow=$(this).parents('tr').eq(0),
                col=$('.editable',parentrow).index(this);

            if(options.exit_key==39){
                newcell=$('.editable',parentrow).eq(col+1);
                if(newcell.length==0){
                    col=0;options.exit_key=40;
                }
            } else if(options.exit_key==37){
                newcell=$('.editable',parentrow).eq(col-1);
                if(newcell.length==0){
                    col=$('.editable',parentrow).length-1;options.exit_key=38;
                }
            }
            if(options.exit_key==38){
                newcell=$('.editable',parentrow.prev()).eq(col);
            } else if(options.exit_key==40){
                newcell=$('.editable',parentrow.next()).eq(col);
            }
            if(newcell && newcell.length>0)
                setTimeout(function (){
                    newcell.trigger('click');
                },100);
        },
        get_text:function(){
            var format=$(this).data('format');
            if(format) {
                format=new RegExp(format)||'';
                var match=$(this).text().match(format);
                return match && match[0]||'';
            } else
                return $(this).text();
        },
        set_text:function (txt) {
            var txtx,id=$(this).parents('tr').eq(0).find('input[type=checkbox]').val(),
                format=$(this).data('format');
            if(format){
                format=new RegExp(format)||'';
                txtx=$(this).text().replace(format,txt)
            } else {
                txtx=txt;
            }
            $(this).html(txtx);

            if(''!=txt){
                var url='...',data='...';
                //$.post(url,data);
            }
            return txt;
        }
    }

    $(document).on('click','.editable',function(){
        $(this).editcell('go',options);
    })

Как результат - при нажатии на клавиши перемещения курсора, указатель перемещается на следующий редактируемый элемент.
