$('.treemenu li').not('.current').find('ul').hide().parent().addClass('collapsed');
$('.treemenu li.current').children('ul').show().parent().addClass('expanded');
$('.treemenu').click(function (e) {
    var el = e.target || e.srcElement;
    //try{
    while (el && (!el.tagName ||
        (el.tagName.toLowerCase() != 'li') && el.tagName.toLowerCase() != 'a'))
        el = el.parentNode;
    if (!el || el.tagName && el.tagName.toLowerCase() == "a") return;
    if ($(el).hasClass('collapsed')) {
        $(el).removeClass('collapsed').addClass('expanded').children('ul').show();
    }
    else if ($(el).hasClass('expanded')) {
        $(el).removeClass('expanded').addClass('collapsed').children('ul').hide();
    }
    //}//catch(e){;}
});
