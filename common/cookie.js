// сохранить значение в LS или куке
function supportLS() {
    try {
        return 'localStorage' in window && window['localStorage'] !== null;
    } catch (e) {
        return false;
    }
}

/**
 * поставить/удалить/выдать значение куки.
 * @param {string} name - имя куки
 * @param {mixed} value - значение куки для установки,
 *        {null} value - удалить куку,
 *          отсутствующий параметр - выдать значение куки
 * @param {object} opt - параметры установки
 *  - {number} expires: DAY - количество дней
 *  - {date} expires: финальная дата
 *  - {string} path - путь куки
 *  - {string} domain - домен куки
 *  - {boolean} secure - секретность
 * @return {mixed}
 */
function cookie(name, value, opt) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        opt = opt || {};
        if (value === null) {
            value = '';
            opt.expires = -1;
        }
        var expires = '';
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
    else { // only name given, so get the cookie
        if (document.cookie && document.cookie != '') {
            var cook = (new RegExp(";\\s*" + name + "\\s*=([^;]+)")).exec(';' + document.cookie),
                reg = new RegExp("[\b|&]([^=]+)=([^&]+)", "g"), res = {}, obj = false, resa;
            cook = cook && decodeURIComponent(cook[1]);

            while ((resa = reg.exec(cook))) {
                res[resa[1]] = resa[2];
                obj = true;
            }
            if (obj)
                return res;
            else
                return cook;
        }
        return null;
    }
}
var store = !supportLS() ? cookie : function (name, value) {
    if (typeof value != 'undefined') {
        localStorage.setItem(name, value);
        return true;
    } else {
        return localStorage.getItem(name);
    }
};
