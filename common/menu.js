/**
 * Установка выпадающего меню
 */
function menu(_self,param){
    if(!param) param={};
    else if(typeof(param)=='function')
    	param={show:param};
    if(!(_self=$(_self)[0])) return;

	function checkMouse (e){
	     var el = e.target;
	     while (true){
			if (el == _self) {
				return true;
			} else if (el == document) {
				hide_menu();
				return false;
			} else {
				el = el.parentNode;
			}
		}
    }
    function show_menu(){
	  if(param.show) param.show.apply(_self);
	  else $(_self).show();
	  _self.shown=true;
	  $(document).bind('mousedown', checkMouse);
	  return false;
    }
    function hide_menu(){
	  $(document).unbind('mousedown', checkMouse);
	  if(param.hide)
	  	param.hide.apply(_self);
	  else
	  	$(_self).hide();
	  setTimeout(function(){_self.shown=false},500);
	  return false;
    }
    _self.show_menu=show_menu;
	_self.hide_menu=hide_menu;
	$(window).bind('unload', function(){_self=null});
}