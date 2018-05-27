/**
 * scrollIntoView function. To make sure your control placed into user viewable position.
 * be sure your control have dispaly:block or display:inline-block style to allow size calculation.
 * @param el HMLControl|selector|jueryObject - object to look at
 * depends: jQuery
 */
function _scrollIntoView(el,options){
    el=$(el);
    var topdisp=el.height()+ 2,
        tdisp=options && options.topdisp||10,// смещение до верха
        bdisp=options && options.botdisp||10; // смещение до низа
    if(el.length>0){
        var xpos=$(window).scrollTop(),
            ofs=el.offset();
        if(ofs.top<xpos+tdisp){ // 44 - высота тулбара админки Joomla
            $(window).scrollTop(ofs.top-tdisp);
        } else if(ofs.top+topdisp > xpos+$(window).height()-bdisp){// 30 - высота футера админки Joomla
            $(window).scrollTop(ofs.top+10+bdisp+topdisp-$(window).height());
        }
    }
    /*el.parents().add(window).each(function(){
        var xx = $(this),xpos;
        if (xx.is(document.body)) return;
        if(xx[0]==window)
            xpos={top:xx.scrollTop()};
        else {
            if (this.scrollHeight==xx.height()) return;
            xpos=xx.position();
        }
        pos.top-=xpos.top;
        if ( pos.top+topdisp>xx.height() ){
            xx.scrollTop(xx.scrollTop() +topdisp + pos.top-xx.height() );
            topdisp=Math.max(xx.height(),topdisp);
        } else {
            topdisp+=pos.top;
            if ( pos.top <0 )
                xx.scrollTop(xx.scrollTop() + pos.top  );
        }
        pos=xpos;
    })*/
}
