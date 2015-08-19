// сохранить значение к LS или куке
function supportLS(){
    try {
        return 'localStorage' in window && window['localStorage'] !== null;
    } catch (e) {
        return false;
    }
}
function store(name,value,opt){
    if(!supportLS) return cookie(name,value,opt);
    if (typeof value != 'undefined'){
        localStorage.setItem(name,value);
        return true;
    } else {
        return localStorage.getItem(name);
    }
}

// поставить куку cookie.
function cookie(name,value,opt){
    if (typeof value != 'undefined') { // name and value given, set cookie
        if(typeof value == 'object' && !(value instanceof String)){
            // сворачиваем простой одноуровневый объект в структуру
            var str=[];
            for(a in value) str.push(a+'='+encodeURIComponent(value[a]||0));
            value='&'+str.join('&');
        }
        opt = opt || {};
        if (value === null) {
            value = '';
            opt.expires = -1;
        }
        var expires = '';//expires:10
        if (opt.expires && (typeof (opt.expires) == 'number' || opt.expires.toUTCString)) {
            var date;
            if (typeof opt.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + Math.round(opt.expires * 24 * 60 * 60 * 1000));
            }
            else {
                date = opt.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        document.cookie = name + '=' + encodeURIComponent(value) + expires +
            (opt.path ? '; path=' + opt.path : '') +
            (opt.domain ? '; domain=' + opt.domain : '') +
            (opt.secure ? '; secure' : '')
    }
    else { // only name given, get cookie
        if (document.cookie && document.cookie != '') {
            var cook = (new RegExp(";\\s*" + name + "\\s*=([^;]+)")).exec(';' + document.cookie);
            var cook=cook && decodeURIComponent(cook[1]),
                reg=new RegExp("[\b|&]([^=]+)=([^&]+)","g"),resa=[],res={},obj=false;
            while((resa=reg.exec(cook))){
                res[resa[1]]=resa[2];
                obj=true;
            }
            if(obj)
                return res;
            else
                return cook;
        }
        return null;
    }
};