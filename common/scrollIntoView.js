/**
 * scrollIntoView function. To make sure your control placed into user viewable position.
 * be sure your control have dispaly:block or display:inline-block style to allow size calculation.
 * @param el HMLControl|selector|jueryObject - object to look at
 * depends: jQuery
 */
function _scrollIntoView(el){
    var el=$(el),
        pos = el.position(),
        topdisp=el.height()+2; // смещение до верха
    if(el.length>0)
    el.parents().add(window).each(function(){
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
    })
}
