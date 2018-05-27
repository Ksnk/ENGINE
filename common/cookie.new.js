/**
 * helper object to hold cookie in one place
 * @type {{get: get, set: set, delete: delete}}
 */
var cookie = {
    get: function (name) {
        if (document.cookie && document.cookie.toString() != '') {
            var cook = (new RegExp(";\\s*" + name + "\\s*=([^;]+)")).exec(';' + document.cookie);
            cook = cook && decodeURIComponent(cook[1]);
            try {
                return JSON.parse(cook);
            } catch (e) {
                // trust a Force, Luke
            }
            return cook;
        }
        return null;
    },
    set: function (name, value, opt) {
        opt = opt || {};
        if (value === null) {
            value = '';
            opt.expires = -1;
        }
        var expires = '';
        if (typeof(value) != 'string') {
            value = JSON.stringify(value);
        }
        if (opt.expires && (typeof (opt.expires) == 'number' || opt.expires.toUTCString)) {
            var date;
            if (typeof opt.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + Math.round(opt.expires * 24 * 60 * 60 * 1000));
            }
            else {
                date = opt.expires;
            }
            expires = '; expires=' + date.toUTCString();
        }
        if (typeof (opt.path) == 'undefined') {
            opt.path = '/';
        }
        document.cookie = name + '=' + encodeURIComponent(value) + expires +
            (opt.path ? '; path=' + opt.path : '') +
            (opt.domain ? '; domain=' + opt.domain : '') +
            (opt.secure ? '; secure' : '')
    },
    'delete': function (name) {
        this.set(name, true, {expires: -1})
    }
};
