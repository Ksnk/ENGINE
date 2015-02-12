
if (window.console && window.console.debug){
    window.debug=function(){
        var args = []; // empty array
        for(var i = 0; i < arguments.length; i++)
        {
            args.push(arguments[i]);
        }
        console.debug.apply(console, args);
    }
} else {
    window.console = {debug:function () {
        for (var i = 0; i < debug.arguments.length; i++) {
            var text = 'x3';
            if (typeof(debug.arguments[i]) == 'undefined')
                text = 'undefined';
            else if (debug.arguments[i].toString)
                text = debug.arguments[i].toString();
            $('<span>').html(text).appendTo('#debug');
        }
        $('<hr>').appendTo('#debug');
    }};
    console.log=console.debug;
    window.debug=console.debug;
}
//<% } %>
