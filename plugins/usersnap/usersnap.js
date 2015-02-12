/**
 * Created with JetBrains PhpStorm.
 * User: Сергей
 * Date: 27.06.13
 * Time: 15:42
 * To change this template use File | Settings | File Templates.
 */
(function() {
    var e = void 0, h = !0, k = null, l = !1, m, p = {gb:"//d3mvnvhjmkxpjz.cloudfront.net/", yb:"//api.usersnap.com/report/sessionkey", wb:"//api.usersnap.com/report/html2png", xb:"//api.usersnap.com/report/sendReport", cc:"//api.usersnap.com/report/sendScreenshot", bc:"//api.usersnap.com/report/carbonCopy", Ja:l, sb:"http://usersnap.com/yuno", vb:"3110", ub:"3110", dd:""}, q = {Jc:"Use the highlighter tool to emphasize proposals and issues on the page.", Ic:"We are serious about your privacy. Black out areas with personal information.",
        Lc:"Add sticky notes to the webpage if highlights and blackouts are not expressive enough.", Mc:"Use this pen to draw something on your screen.", xd:"Send a screenshot of this site.", vd:"Display the help screen.", wd:"Minimize window.", Kc:"Maximize window.", Cd:"Do not show this window next time.", Dd:"Hide help window.", rc:"Pen", pc:"Highlight", oc:"Blackout", qc:"Note", Yc:"Next", Wc:"Cancel", ad:"Send", Ed:"Send copy", Zc:"No, thanks.", Rb:"Need help?", Xc:"Close", ab:"Close this window", $c:"Ok",
        md:"Please add comments here", nd:"Your email address", Bc:"The specified email address is not valid!", od:"Please place add at least one note or highlight an area!", Id:"There are highlights and/or notes which are not in your current view, these elements will not be sent.", pd:"Submitting your screenshot", Gd:"Email:", Hd:"Your screenshot has been sent!<br>Do you want to get a copy of the feedback in your mailbox?", ld:"The specified API key for this website is invalid!", yd:"Send a screenshot with your annotations.",
        sd:"Enter note here.", Hc:"Use this arrow to point out something!", nc:"Arrow", zc:"Please specify a comment!", Ac:"Please enter an email address!"}, r;
    r || (r = {});
    (function() {
        function a(a) {
            return 10 > a ? "0" + a : a
        }
        function b(a) {
            f.lastIndex = 0;
            return f.test(a) ? '"' + a.replace(f, function(a) {
                var b = n[a];
                return"string" === typeof b ? b : "\\u" + ("0000" + a.charCodeAt(0).toString(16)).slice(-4)
            }) + '"' : '"' + a + '"'
        }
        function d(a, c) {
            var f, n, G, M, Y = g, N, z = c[a];
            z && ("object" === typeof z && "function" === typeof z.Xa) && (z = z.Xa(a));
            "function" === typeof y && (z = y.call(c, a, z));
            switch(typeof z) {
                case "string":
                    return b(z);
                case "number":
                    return isFinite(z) ? String(z) : "null";
                case "boolean":
                    ;
                case "null":
                    return String(z);
                case "object":
                    if(!z) {
                        return"null"
                    }
                    g += j;
                    N = [];
                    if("[object Array]" === Object.prototype.toString.apply(z)) {
                        M = z.length;
                        for(f = 0;f < M;f += 1) {
                            N[f] = d(f, z) || "null"
                        }
                        G = 0 === N.length ? "[]" : g ? "[\n" + g + N.join(",\n" + g) + "\n" + Y + "]" : "[" + N.join(",") + "]";
                        g = Y;
                        return G
                    }
                    if(y && "object" === typeof y) {
                        M = y.length;
                        for(f = 0;f < M;f += 1) {
                            "string" === typeof y[f] && (n = y[f], (G = d(n, z)) && N.push(b(n) + (g ? ": " : ":") + G))
                        }
                    }else {
                        for(n in z) {
                            Object.prototype.hasOwnProperty.call(z, n) && (G = d(n, z)) && N.push(b(n) + (g ? ": " : ":") + G)
                        }
                    }
                    G = 0 === N.length ? "{}" : g ? "{\n" + g + N.join(",\n" + g) + "\n" + Y + "}" : "{" + N.join(",") + "}";
                    g = Y;
                    return G
            }
        }
        "function" !== typeof Date.prototype.Xa && (Date.prototype.Xa = function() {
            return isFinite(this.valueOf()) ? this.getUTCFullYear() + "-" + a(this.getUTCMonth() + 1) + "-" + a(this.getUTCDate()) + "T" + a(this.getUTCHours()) + ":" + a(this.getUTCMinutes()) + ":" + a(this.getUTCSeconds()) + "Z" : k
        }, String.prototype.Xa = Number.prototype.Xa = Boolean.prototype.Xa = function() {
            return this.valueOf()
        });
        var c = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g, f = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g, g, j, n = {"\b":"\\b", "\t":"\\t", "\n":"\\n", "\f":"\\f", "\r":"\\r", '"':'\\"', "\\":"\\\\"}, y;
        "function" !== typeof r.stringify && (r.stringify = function(a, b, c) {
            var f;
            j = g = "";
            if("number" === typeof c) {
                for(f = 0;f < c;f += 1) {
                    j += " "
                }
            }else {
                "string" === typeof c && (j = c)
            }
            if((y = b) && "function" !== typeof b && ("object" !== typeof b || "number" !== typeof b.length)) {
                throw Error("USJSON.stringify");
            }
            return d("", {"":a})
        });
        "function" !== typeof r.parse && (r.parse = function(a, b) {
            function d(a, c) {
                var f, g, j = a[c];
                if(j && "object" === typeof j) {
                    for(f in j) {
                        Object.prototype.hasOwnProperty.call(j, f) && (g = d(j, f), g !== e ? j[f] = g : delete j[f])
                    }
                }
                return b.call(a, c, j)
            }
            var f;
            a = String(a);
            c.lastIndex = 0;
            c.test(a) && (a = a.replace(c, function(a) {
                return"\\u" + ("0000" + a.charCodeAt(0).toString(16)).slice(-4)
            }));
            if(/^[\],:{}\s]*$/.test(a.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, "@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, "]").replace(/(?:^|:|,)(?:\s*\[)+/g, ""))) {
                return f = eval("(" + a + ")"), "function" === typeof b ? d({"":f}, "") : f
            }
            throw new SyntaxError("USJSON.parse");
        })
    })();
    function aa(a) {
        return a ? /^(([^<>()\[\]\\.,;:\s@\"]+(\.[^<>()\[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(a) : l
    }
    function ba() {
        return/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent || navigator.vendor || window.opera)
    }
    function s(a, b) {
        var d = arguments;
        return d[0].replace(/\{(\d+)\}/g, function(a, b) {
            b = parseInt(b, 10);
            return"undefined" != typeof d[b + 1] ? d[b + 1] : a
        })
    }
    function t(a) {
        var b = k;
        document.qd ? b = document.qd[a] : document.all ? (b = document.all[a], "undefined" === typeof b && (b = k)) : document.getElementById && (b = document.getElementById(a));
        return b
    }
    function u() {
        return 0 <= navigator.userAgent.search(/MSIE/)
    }
    function ca(a, b) {
        var d = {width:a, height:b};
        if(v()) {
            var c = da(), f = 0, f = fa();
            0 < f - c.width ? d.width -= 21 : (d.width -= 4, d.height = c.height)
        }else {
            if(0 <= navigator.userAgent.search(/MSIE 9/) || 0 <= navigator.userAgent.search(/MSIE 10/)) {
                d.width = document.documentElement.scrollWidth
            }
        }
        return d
    }
    function v() {
        return 0 <= navigator.userAgent.search(/MSIE 8/)
    }
    function A() {
        return 0 <= navigator.userAgent.search(/MSIE 7/)
    }
    function ga(a) {
        return!a.which && a.button !== e ? a.button & 1 ? 1 : a.button & 2 ? 3 : a.button & 4 ? 2 : 0 : 0 <= navigator.userAgent.search(/MSIE 9/) ? window.event.button : a.ed !== e ? a.ed : a.which
    }
    function B(a) {
        return u() ? a.srcElement : a.target
    }
    function ha(a) {
        a = a.replace(RegExp("[" + (e || "\\s") + "]+$", "g"), "");
        return a.replace(RegExp("^[" + (e || "\\s") + "]+", "g"), "")
    }
    function C(a, b) {
        if(a !== k) {
            for(var d = b.split(";"), c = 0;c < d.length;c++) {
                "" !== ha(d[c].split(":")[0]) && (a.style[ha(d[c].split(":")[0])] = ha(d[c].split(":")[1]))
            }
        }
    }
    function D(a, b) {
        a !== k && (0 <= navigator.userAgent.search(/MSIE 7/) ? a.setAttribute("className", b) : a.setAttribute("class", b))
    }
    function ia(a) {
        if(a === k) {
            return""
        }
        var b = k;
        return b = 0 <= navigator.userAgent.search(/MSIE 7/) ? a.getAttribute("className") : a.getAttribute("class")
    }
    function ja(a) {
        if(a === k) {
            return l
        }
        a = ia(a);
        if(a !== k) {
            a = a.split(" ");
            for(var b = 0;b < a.length;b++) {
                if("us_report_note" === a[b]) {
                    return h
                }
            }
        }
        return l
    }
    function ka() {
        var a = document;
        return Math.max(Math.max(a.body.scrollHeight, a.documentElement.scrollHeight), Math.max(a.body.offsetHeight, a.documentElement.offsetHeight), Math.max(a.body.clientHeight, a.documentElement.clientHeight))
    }
    function fa() {
        var a = document;
        return Math.max(Math.max(a.body.scrollWidth, a.documentElement.scrollWidth), Math.max(a.body.offsetWidth, a.documentElement.offsetWidth), Math.max(a.body.clientWidth, a.documentElement.clientWidth))
    }
    function E(a, b, d) {
        a !== k && (a.removeEventListener ? a.removeEventListener(b, d, l) : a.detachEvent && "undefined" !== typeof d && a.detachEvent("on" + b, d))
    }
    function F(a, b, d) {
        a !== k && (a.addEventListener ? a.addEventListener(b, d, l) : a.attachEvent && a.attachEvent("on" + b, d))
    }
    function H() {
        var a, b;
        A() || v() ? (b = document.documentElement.scrollTop, a = document.documentElement.scrollLeft) : (b = window.pageYOffset, a = window.pageXOffset);
        return[a, b]
    }
    function la(a, b, d) {
        var c = new Date, f = new Date;
        if("undefined" === typeof d || d === k || 0 === d) {
            d = 365
        }
        f.setTime(c.getTime() + 864E5 * d);
        document.cookie = a + "=" + escape(b) + ";expires=" + f.toGMTString()
    }
    function ma(a) {
        var b = " " + document.cookie, d = b.indexOf(" " + a + "=");
        -1 === d && (d = b.indexOf(";" + a + "="));
        if(-1 === d || "" === a) {
            return""
        }
        var c = b.indexOf(";", d + 1);
        -1 === c && (c = b.length);
        return unescape(b.substring(d + a.length + 2, c))
    }
    function da() {
        var a, b;
        "undefined" != typeof window.innerWidth ? (a = window.innerWidth, b = window.innerHeight) : "undefined" !== typeof document.documentElement && "undefined" !== typeof document.documentElement.clientWidth && 0 !== document.documentElement.clientWidth ? (a = document.documentElement.clientWidth, b = document.documentElement.clientHeight) : (a = document.getElementsByTagName("body")[0].clientWidth, b = document.getElementsByTagName("body")[0].clientHeight);
        if(A() || v()) {
            ka() > b && (a += 17)
        }
        return{width:a, height:b}
    }
    function na() {
        return window.pageXOffset ? window.pageXOffset : document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft
    }
    function oa() {
        return window.pageYOffset ? window.pageYOffset : document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop
    }
    function pa(a, b) {
        var d;
        a.currentStyle ? d = a.currentStyle[b] : window.getComputedStyle && (d = document.defaultView.getComputedStyle(a, k).getPropertyValue(b));
        return d
    }
    function I(a, b) {
        return a === document ? l : "undefined" === typeof a.getAttribute ? I(a.parentNode, b) : a.getAttribute("id") === b ? h : a.getAttribute("class") === b ? h : a.getAttribute("className") === b ? h : I(a.parentNode, b)
    }
    var qa = window;
    function ra() {
        var a = Math.round(100 * (window.screen.deviceXDPI / window.screen.logicalXDPI)) / 100;
        return{zoom:a, xa:a * (window.devicePixelRatio || 1)}
    }
    function sa() {
        var a = Math.round(100 * (document.documentElement.offsetHeight / window.innerHeight)) / 100;
        return{zoom:a, xa:a * (window.devicePixelRatio || 1)}
    }
    function ta() {
        var a = (90 == Math.abs(window.orientation) ? window.screen.height : window.screen.width) / window.innerWidth;
        return{zoom:a, xa:a * (window.devicePixelRatio || 1)}
    }
    function ua() {
        var a = document.createElement("div");
        a.innerHTML = "1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>0";
        a.setAttribute("style", "font: 100px/1em sans-serif; -webkit-text-size-adjust: none; text-size-adjust: none; height: auto; width: 1em; padding: 0; overflow: visible;".replace(/;/g, " !important;"));
        var b = document.createElement("div");
        b.setAttribute("style", "width:0; height:0; overflow:hidden; visibility:hidden; position: absolute;".replace(/;/g, " !important;"));
        b.appendChild(a);
        document.body.appendChild(b);
        a = 1E3 / a.clientHeight;
        a = Math.round(100 * a) / 100;
        document.body.removeChild(b);
        return{zoom:a, xa:a * (window.devicePixelRatio || 1)}
    }
    function va() {
        function a(b, c, f) {
            var g = (b + c) / 2;
            return 0 >= f || 1E-4 > c - b ? g : d("(min--moz-device-pixel-ratio:" + g + ")").matches ? a(g, c, f - 1) : a(b, g, f - 1)
        }
        var b, d, c, f;
        window.matchMedia ? d = window.matchMedia : (b = document.getElementsByTagName("head")[0], c = document.createElement("style"), b.appendChild(c), f = document.createElement("div"), f.className = "mediaQueryBinarySearch", f.style.display = "none", document.body.appendChild(f), d = function(a) {
            var b = l;
            A() || (c.sheet.insertRule("@media " + a + "{.mediaQueryBinarySearch {text-decoration: underline} }", 0), b = "underline" == window.getComputedStyle(f, k).textDecoration, c.sheet.deleteRule(0));
            return{matches:b}
        });
        var g = a(0, 10, 20);
        f && (b.removeChild(c), document.body.removeChild(f));
        b = Math.round(100 * g) / 100;
        return{zoom:b, xa:b}
    }
    function wa() {
        return{zoom:va().zoom, xa:window.devicePixelRatio || 1}
    }
    function xa() {
        var a = window.top.outerWidth / window.top.innerWidth, a = Math.round(100 * a) / 100;
        return{zoom:a, xa:a * (window.devicePixelRatio || 1)}
    }
    var ya;
    function J() {
        return{zoom:1, xa:1}
    }
    !isNaN(window.screen.logicalXDPI) && !isNaN(window.screen.systemXDPI) ? J = ra : window.navigator.msMaxTouchPoints ? J = sa : "orientation" in window && "string" === typeof document.body.style.webkitMarquee ? J = ta : "string" === typeof document.body.style.webkitMarquee ? J = ua : 0 <= navigator.userAgent.indexOf("Opera") ? J = xa : window.devicePixelRatio ? J = wa : 0.0010 < va().zoom && (J = va);
    ya = J;
    qa.detectZoom = {zoom:function() {
        return ya().zoom
    }, Fd:function() {
        return ya().xa
    }};
    var p, K, L, za, Aa;
    p.Ja || (window.UserSnap = {}, q.b_pen = q.rc, q.b_arrow = q.nc, q.b_blackout = q.oc, q.b_highlight = q.pc, q.b_note = q.qc, q.t_pen = q.Mc, q.t_arrow = q.Hc, q.t_blackout = q.Ic, q.t_highlight = q.Jc, q.t_note = q.Lc);
    function Ba(a) {
        !u() && p.Ja === h && window.console.log.apply(window.console, arguments)
    }
    function Ca(a) {
        !u() && p.Ja === h && window.console.warn.apply(window.console, arguments)
    }
    function O(a) {
        !u() && p.Ja === h && window.console.error.apply(window.console, arguments)
    }
    var Q = k;
    K = function(a) {
        Q !== k ? O("Only one concurrent request is possible!") : (this.Va = k, a.Va && (this.Va = a.Va), this.Va.callback = "UserSnap.util.JsonP.callback", this.kb = document.createElement("script"), this.kb.type = "text/javascript", Q = {na:a.na, scope:a.scope, url:a.url, Dc:l}, this.url = a.url, A() || v() ? this.kb.onreadystatechange = function() {
            "loaded" === this.readyState && window.setTimeout(function() {
                if(Q !== k) {
                    var a = Q.na, d = Q.scope;
                    Q = k;
                    a.call(d, k, l)
                }
            }, 100)
        } : this.kb.onerror = function() {
            var a = Q.na, d = Q.scope;
            Q = k;
            a.call(d, k, l)
        })
    };
    K.prototype.jb = function() {
        var a;
        a = this.Va;
        var b = [], d;
        for(d in a) {
            "function" !== typeof a[d] && b.push(d + "=" + encodeURIComponent(a[d]))
        }
        a = b.join("&");
        b = this.url + "?" + a;
        1 < a.length && (b += "&dc=" + (new Date).getTime());
        this.kb.src = b;
        document.getElementsByTagName("head")[0].appendChild(this.kb);
        Q.Dc = h
    };
    K.jb = function(a) {
        (new K(a)).jb()
    };
    K.na = function(a) {
        if(Q === k || !Q.Dc) {
            O("No request running")
        }else {
            var b = Q.na, d = Q.scope;
            Q = k;
            b.call(d, a, h)
        }
    };
    p.Ja || (window.UserSnap.util = {}, window.UserSnap.util.JsonP = K, window.UserSnap.util.JsonP.callback = K.na);
    function Da(a) {
        this.ja = document.createElement("iframe");
        this.Ra = "iframe_post" + (new Date).getTime();
        document.body.appendChild(this.ja);
        this.ja.style.display = "none";
        this.ja.contentWindow.name = this.Ra;
        this.ob = l;
        var b = this;
        u() ? F(this.ja, "load", function() {
            b.ob = l;
            Ea(b);
            Fa = l;
            Ga = k;
            Ha()
        }) : this.ja.onload = function() {
            b.ob = l;
            Ea(b);
            Fa = l;
            Ga = k;
            Ha()
        };
        this.R = document.createElement("form");
        this.R.style.display = "none";
        this.R.target = this.Ra;
        this.R.action = a.url;
        this.R.method = "POST";
        this.R.acceptCharset = "utf-8";
        document.body.appendChild(this.R);
        (v() || A()) && R(this, "ieencoding", "\u9760")
    }
    var Fa = l, S = [], Ga = k;
    function Ha() {
        0 < S.length && !Fa && (Fa = h, Ga = S.shift(), Ga.jb())
    }
    function Ia(a) {
        for(var b = 0;b < S.length;b++) {
            if("undefined" !== typeof S[b] && "id" === S[b].Ra) {
                S.splice(b, 1);
                return
            }
        }
        b = Ga;
        b !== k && b.Ra === a && (Ea(b), Fa = l, Ga = k)
    }
    F(window, "beforeunload", function(a) {
        if(Ga !== k || 0 < S.length) {
            return"undefined" === typeof a && (a = window.event), a && (a.returnValue = "Usersnap is currently uploading a report, please wait a few seconds otherwise your report will be LOST!"), "Usersnap is currently uploading a report, please wait a few seconds otherwise your report will be LOST!"
        }
    });
    function Ea(a) {
        a.ob && (u() || a.ja.contentWindow.stop());
        window.setTimeout(function() {
            a.ja !== k && (document.body.removeChild(a.R), document.body.removeChild(a.ja), a.R = k, a.ja = k)
        }, 500)
    }
    function R(a, b, d) {
        var c = document.createElement("input");
        c.type = "hidden";
        c.name = b;
        c.value = d;
        a.R.appendChild(c)
    }
    Da.prototype.jb = function() {
        this.ob = h;
        this.R.submit()
    };
    function Ja(a) {
        this.n = document.createElement("button");
        var b = "Feedback";
        a.text !== e && (b = a.text);
        this.n.innerHTML = "middle" === a.ca ? v() ? '<div class="rotate ie8">' + b + "</div>" : A() ? '<div class="rotate ie7">' + b + "</div>" : '<div class="rotate">' + b + "</div>" : b;
        this.Sa = document.createElement("div");
        this.Sa.setAttribute("id", "us_report_button");
        this.Sa.appendChild(this.n);
        this.Hb = l;
        document.body.appendChild(this.Sa);
        a.ca === e && (a.ca = "bottom");
        a.oa === e && (a.oa = "right");
        this.ya = a;
        "middle" === this.ya.ca && "left" === this.ya.oa ? (D(this.n, "us_button_vertical_left"), C(this.n, "top: 37%; left: 0;")) : "bottom" === this.ya.ca && "left" === this.ya.oa ? (D(this.n, "us_button_horizontal"), C(this.n, "bottom: 0; left: 30px;")) : "middle" === this.ya.ca && "right" === this.ya.oa ? (D(this.n, "us_button_vertical_right"), C(this.n, "top: 37%; right: 0;")) : (D(this.n, "us_button_horizontal"), C(this.n, "bottom: 0; right: 30px;"));
        var d = this;
        this.hc = function(a) {
            d.fd.apply(d, arguments);
            return l
        };
        F(this.n, "click", this.hc);
        this.X = h;
        L(this.n, q.yd)
    }
    Ja.prototype.pa = function() {
        this.X && (C(this.n, "display: none;"), this.X = l)
    };
    Ja.prototype.show = function() {
        this.X || (C(this.n, "display: block;"), this.X = h)
    };
    Ja.prototype.fd = function() {
        this.Hb || this.ya.kd()
    };
    Ja.prototype.D = function() {
        this.Sa !== k && (E(this.n, "click", this.hc), za(), document.body.removeChild(this.Sa), this.Sa = k)
    };
    var T = {}, Ka = '<div unselectable="on" class="us_footer"><input unselectable="on" id="us_btn_cancel" type="button" class="us_btn_cancel" value="' + q.Wc + '" /><input id="us_btn_send" type="button" class="us_btn_send" value="' + q.ad + '" /></div><div unselectable="on" class="us_copy">Powered by <a href="http://usersnap.com" target="_blank">usersnap.com</a>.</div></form>';
    function La(a) {
        this.c = document.createElement("div");
        this.c.setAttribute("id", "us_report_window");
        this.c.setAttribute("unselectable", "on");
        this.Mb = h;
        "undefined" === typeof a.f && (a.f = ["highlight", "blackout", "note"]);
        this.e = a;
        Ma(this);
        for(var b in T) {
            "object" === typeof T[b] && (T[b].index = -1)
        }
        a = '<form unselectable="on" class="us_form" id="us_form"><div unselectable="on" class="us_header"><input type="button" id="us_btn_help" class="us_btn_help" value="Help"/><input type="button" id="us_btn_close" class="us_btn_close" value="Close"/><input type="button" id="us_btn_min" class="us_btn_move" value="Minimize"/></div><div unselectable="on" class="us_middle">';
        Na(this);
        for(var d = b = 0;d < this.e.f.length;d++) {
            -1 === T[this.e.f[d].toLowerCase()].index && (a += T[this.e.f[d].toLowerCase()].data, T[this.e.f[d].toLowerCase()].index = b, b++)
        }
        a += "</div>";
        b = "";
        this.Ea = eval("'" + q.nd.replace(/&#x([0-9a-z]{4});/g, "\\u$1") + "'");
        this.e.xc !== e && (this.Ea = this.e.xc);
        this.Za = this.e.Ua === h;
        this.Na = eval("'" + q.md.replace(/&#x([0-9a-z]{4});/g, "\\u$1") + "'");
        this.e.vc !== e && (this.Na = this.e.vc);
        this.Db = this.e.Ta === h;
        this.e.cb === h && (b += s('<input placeholder="{0}" id="us_add_email" type="text" value="{1}" />', this.Ea, this.e.Tb !== e ? this.e.Tb : ""));
        this.e.bb === h && (b += s('<textarea placeholder="{0}" id="us_add_comment"></textarea>', this.Na));
        "" !== b && (a += s('<div unselectable="on" class="us_desc_container" id="us_email_text_box">{0}</div>', b));
        a += Ka + s('<div class="us_minimize" id="us_minimize"><div class="us_powered">{0}</div><input type="button" id="us_btn_max" class="us_btn_max" value="Maximize"></div>', q.Kc);
        this.c.innerHTML = a;
        document.body.appendChild(this.c);
        var c = this;
        this.h = l;
        this.ga = {};
        for(var f in this.e.hb) {
            this.e.hb.hasOwnProperty(f) && (this.ga[f] = k, T[f] !== e && -1 !== T[f].index && Oa(this, f))
        }
        this.Jb = t("us_btn_help");
        this.ic = t("us_btn_min");
        this.Rc = t("us_btn_max");
        this.Pc = t("us_btn_close");
        this.Qb = {width:220, height:145};
        this.l = t("us_add_email");
        this.l !== k && u() && ("" === c.l.value && (c.l.value = this.Ea), F(this.l, "focus", function() {
            c.l.value === c.Ea && (c.l.value = "")
        }), F(this.l, "blur", function() {
            "" === c.l.value && (c.l.value = c.Ea)
        }));
        this.F = t("us_add_comment");
        this.F !== k && u() && ("" === c.F.value && (c.F.value = this.Na), F(this.F, "focus", function() {
            c.F.value === c.Na && (c.F.value = "")
        }), F(this.F, "blur", function() {
            "" === c.F.value && (c.F.value = c.Na)
        }));
        this.V = k;
        this.m = l;
        L(this.Jb, q.vd);
        L(this.ic, q.wd);
        L(this.Rc, q.Kc);
        L(t("us_btn_send"), q.xd);
        F(this.Jb, "click", function() {
            c.Jb.blur();
            c.fb.apply(c, arguments)
        });
        F(t("us_btn_send"), "click", function() {
            c.Ma.apply(c, arguments)
        });
        F(t("us_btn_cancel"), "click", function() {
            c.Y.apply(c, arguments)
        });
        this.jc = t("us_minimize");
        this.R = t("us_form");
        F(this.ic, "click", function() {
            c.fb.apply(c, ["close"]);
            C(c.R, "display:none;");
            C(c.jc, "display:block;");
            C(c.c, "height: 20px;");
            c.Mb = l
        });
        F(this.Pc, "click", function() {
            c.Y.apply(c, arguments)
        });
        F(this.c, "click", function(a) {
            !this.Mb && !I(B(a), "us_form") && (C(c.R, "display:block;"), C(c.jc, "display:none;"), C(c.c, "height: auto;"), c.Mb = h)
        })
    }
    function Ma(a) {
        T = {};
        for(var b in a.e.hb) {
            a.e.hb.hasOwnProperty(b) && (T[b] = {data:'<input unselectable="on" id="us_btn_' + b + '" type="button" class="us_btn_' + b + '" value="' + q["b_" + b] + '" />', index:-1})
        }
    }
    function Na(a) {
        if(0 === a.e.f.length) {
            a.e.f = ["highlight"]
        }else {
            var b = [], d = 0;
            try {
                for(var c = 0;c < a.e.f.length;c++) {
                    for(var f in a.e.hb) {
                        if(a.e.f[c].toLowerCase() === f) {
                            b.push(a.e.f[c].toLowerCase());
                            d++;
                            break
                        }
                    }
                }
                0 === b.length && (b = ["highlight"]);
                a.e.f = b
            }catch(g) {
                a.e.f = ["highlight"]
            }
        }
    }
    m = La.prototype;
    m.start = function() {
        for(var a in T) {
            if("object" === typeof T[a] && 0 === T[a].index) {
                Pa(this, a);
                this.e.Gc(T[a].index, a);
                break
            }
        }
    };
    m.Wb = function(a) {
        return-1 !== T[a].index
    };
    m.$ = function() {
        this.h || (this.m = h)
    };
    m.nb = function(a) {
        var b = da(), d = b.width - this.Qb.width, b = b.height - this.Qb.height, c = l;
        a.b > d && (a.b = d, c = h);
        a.a > b && (a.a = b, c = h);
        0 > a.a && (a.a = 0, c = h);
        return c
    };
    m.aa = function(a) {
        if(this.m) {
            var b = H();
            a = {b:a.clientX + b[0], a:a.clientY + b[1]};
            this.V !== k ? (this.J = {b:this.J.b + (a.b - this.V.b), a:this.J.a + (a.a - this.V.a)}, this.nb(this.J), C(this.c, " top: " + this.J.a + "px; left: " + this.J.b + "px;")) : (this.J = {b:this.c.offsetLeft, a:this.c.offsetTop}, C(this.c, "position: fixed; height: " + this.Qb.height + "px;"));
            this.V = a
        }
    };
    m.ba = function() {
        !this.h && this.m && (this.V = k, this.m = l)
    };
    m.uc = function(a) {
        var b = document.getElementById("us_email_text_box");
        b !== k && (a.commentBox !== h && a.emailBox !== h ? b.parentElement.removeChild(b) : a.commentBox !== h ? this.F !== k && b.removeChild(this.F) : a.emailBox !== h && this.l !== k && b.removeChild(this.l))
    };
    m.enable = function() {
        this.h = l
    };
    m.disable = function() {
        this.h = h
    };
    function Pa(a, b) {
        for(var d in a.ga) {
            "string" === typeof d && a.ga[d] !== k && (b === d ? D(a.ga[d], "us_btn_" + d + " us_active") : D(a.ga[d], "us_btn_" + d))
        }
    }
    function Oa(a, b) {
        a.ga[b] = t("us_btn_" + b);
        L(a.ga[b], q["t_" + b]);
        F(a.ga[b], "click", function() {
            a.ga[b].blur();
            a.zd.apply(a, [b])
        })
    }
    m.zd = function(a) {
        this.h || (Pa(this, a), this.e.Gc(T[a].index, a))
    };
    m.Ec = function(a) {
        this.l !== k && (this.l.value = a)
    };
    m.fb = function(a) {
        this.h || ("close" === a ? this.e.fb("close") : this.e.fb())
    };
    m.Ma = function() {
        if(!this.h) {
            var a = "", b = k;
            if(this.l !== k) {
                u() ? this.l.value !== this.Ea && (a = this.l.value) : "" !== this.l.value && (a = this.l.value);
                if(this.Za && "" === a) {
                    b = new U("alert", q.Ac);
                    b.show();
                    return
                }
                if((this.Za || "" !== a) && !aa(a)) {
                    b = new U("alert", q.Bc);
                    b.show();
                    return
                }
            }
            b = "";
            this.F !== k && this.F.value !== this.Na && (b = this.F.value);
            this.Db && "" === b ? (b = new U("alert", q.zc), b.show()) : (la("usersnap_email", a), this.e.Ma({tb:b, wc:a}))
        }
    };
    m.Y = function() {
        this.e.Y()
    };
    m.D = function() {
        document.body.removeChild(this.c)
    };
    m.Vb = function() {
        var a = pa(this.c, "height");
        return a = "auto" === a ? this.c.clientHeight : parseInt(a.substr(0, a.length - 2), 10)
    };
    m.show = function() {
        C(this.c, "display:block; bottom: 0px; right: 15px;");
        var a = ma("usersnap_email");
        a !== k && ("" !== a && this.l !== k) && (u() ? this.l.value === this.Ea && (this.l.value = a) : "" === this.l.value && (this.l.value = a))
    };
    m.pa = function() {
        C(this.c, "display: none;")
    };
    function Qa(a) {
        this.e = a;
        this.c = document.createElement("div");
        this.c.setAttribute("id", "us_report_ios");
        this.c.setAttribute("unselectable", "on");
        this.c.innerHTML = '<div class="us_container" aria-disabled="false"><div class="us_header" role="banner">Usersnap Feedback</div><div class="us_body" role="main"><div class="us_email">Email: <input type="text" class="us_input" id="us_add_email"/></div><div><textarea class="us_input" id="us_add_comment"></textarea></div><p class="us_small">A screenshot is attached!</p><div class="us_footer"><div class="us_right"><button id="us_btn_send" class="us_btn_mobile us_btn_primary">Send</button></div><div class="us_right"><button id="us_btn_cancel" class="us_btn_mobile">Cancel</button></div></div></div></div>';
        document.body.appendChild(this.c);
        this.Za = this.e.Ua === h;
        this.Db = this.e.Ta === h;
        this.l = t("us_add_email");
        this.F = t("us_add_comment");
        var b = this;
        F(t("us_btn_send"), "click", function() {
            b.Ma.apply(b, arguments)
        });
        F(t("us_btn_cancel"), "click", function() {
            b.Y.apply(b, arguments)
        })
    }
    m = Qa.prototype;
    m.uc = function() {
    };
    m.enable = function() {
        this.h = l
    };
    m.disable = function() {
        this.h = h
    };
    m.Ec = function(a) {
        this.l !== k && (this.l.value = a)
    };
    m.start = function() {
    };
    m.Vb = function() {
        var a = pa(this.c, "height");
        return a = "auto" === a ? this.c.clientHeight : parseInt(a.substr(0, a.length - 2), 10)
    };
    m.Wb = function() {
        return l
    };
    m.Ma = function() {
        if(!this.h) {
            var a = "";
            if(this.l !== k) {
                "" !== this.l.value && (a = this.l.value);
                if(this.Za && "" === a) {
                    window.alert(q.Ac);
                    return
                }
                if((this.Za || "" !== a) && !aa(a)) {
                    window.alert(q.Bc);
                    return
                }
            }
            var b = "";
            this.F !== k && (b = this.F.value);
            this.Db && "" === b ? window.alert(q.zc) : (la("usersnap_email", a), this.e.Ma({tb:b, wc:a}))
        }
    };
    m.Y = function() {
        this.e.Y()
    };
    m.D = function() {
        document.body.removeChild(this.c)
    };
    m.show = function() {
        var a = da(), b = pa(this.c, "width"), d = pa(this.c, "height"), c = H();
        C(this.c, "display:block; top: " + Math.floor(a.height / 2 - parseInt(d, 10) / 2 + c[1]) + "px; left: " + Math.floor(a.width / 2 - parseInt(b, 10) / 2 + c[0]) + "px;");
        a = ma("usersnap_email");
        a !== k && ("" !== a && this.l !== k) && "" === this.l.value && (this.l.value = a)
    };
    m.pa = function() {
        C(this.c, "display: none;")
    };
    function U(a, b, d) {
        this.c = document.createElement("div");
        this.c.setAttribute("id", "us_report_infoscreen");
        this.rb = a;
        this.ec = "undefined" === typeof d ? k : d;
        switch(this.rb) {
            case "progress":
                this.c.innerHTML = s('<div class="us_feedback">{0}</div><div class="us_progress"><div class="us_data" id="us_progress_data"></div></div>', b);
                this.Nb = "default";
                this.mb = 2E3;
                this.va = {width:450, height:90};
                break;
            case "alert":
                this.c.innerHTML = s('<div class="us_feedback">{0}</div><div><input id="us_btn_alert_ok" type="button" class="us_btn_alert" value="{1}"/></div>', b, q.$c);
                this.Nb = "default";
                this.mb = 0;
                this.va = {width:370, height:90};
                break;
            case "info":
                this.c.innerHTML = s('<div class="us_content">{0}</div>', b), this.Nb = "corner", this.mb = 6E4, this.va = {width:200, height:50}, a = ia(this.c), D(this.c, a + " us_error")
        }
        this.Pa = l;
        document.body.appendChild(this.c)
    }
    U.prototype.show = function() {
        var a = "";
        this.va !== e ? a = "width: " + this.va.width + "px; height: " + this.va.height + "px; " : this.va = {width:400, height:50};
        if("corner" === this.Nb) {
            a += "bottom: 35px; right: 30px; position: fixed;"
        }else {
            var b = da(), d = H(), a = a + ("top: " + (b.height / 2 - this.va.height / 2 + d[1]) + "px; left: " + (b.width / 2 - this.va.width / 2 + d[0]) + "px;")
        }
        C(this.c, a);
        var c = this;
        if("info" === this.rb) {
            window.setTimeout(function() {
                c.D()
            }, this.mb)
        }else {
            if("progress" === this.rb) {
                var f = t("us_progress_data"), g = 0, j = window.setInterval(function() {
                    100 == g && (window.clearInterval(j), window.setTimeout(function() {
                        c.dc.call(c)
                    }, 600));
                    C(f, "width: " + Math.ceil(2.8 * g) + "px;");
                    g += 1
                }, Math.ceil((this.mb - 600) / 100))
            }else {
                "alert" === this.rb && (this.Oc = t("us_btn_alert_ok"), F(this.Oc, "click", function() {
                    c.dc.call(c)
                }))
            }
        }
    };
    U.prototype.dc = function() {
        this.D();
        this.ec !== k && this.ec()
    };
    U.prototype.D = function() {
        this.Pa || (document.body.removeChild(this.c), this.Pa = h)
    };
    var Ra = {footer:'<div class="us_entry"><input id="us_need_help" class="us_btn_blue" type="button" value="' + q.Rb + '"/><a id="us_help_no" href="#">' + q.Zc + '</a></div><div class="us_arrowdown"></div><div class="us_close" id="us_help_close">&times;</div>', highlight:'<div class="us_box"><div class="us_animation us_highlight"></div><div class="us_info_box"><div class="us_btn_cont"><input type="button" value="' + q.pc + '" class="us_btn_highlight us_deactive" unselectable="on"></div><div class="us_description">' +
        q.Jc + '</div><div><input type="button" id="us_next_btn_highlight" class="us_btn_blue" value="{0}"/><a id="us_close_btn_highlight" href="#">' + q.ab + "</a></div></div></div>", blackout:'<div class="us_box"><div class="us_animation us_blackout"></div><div class="us_info_box"><div class="us_btn_cont"><input type="button" value="' + q.oc + '" class="us_btn_blackout us_deactive" unselectable="on"></div><div class="us_description">' + q.Ic + '</div><div><input type="button" id="us_next_btn_blackout" class="us_btn_blue" value="{0}"/><a id="us_close_btn_blackout" href="#">' +
        q.ab + "</a></div></div></div>", note:'<div class="us_box"><div class="us_animation us_note"></div><div class="us_info_box"><div class="us_btn_cont"><input type="button" value="' + q.qc + '" class="us_btn_note us_deactive" unselectable="on"></div><div class="us_description">' + q.Lc + '</div><div><input type="button" id="us_next_btn_note" class="us_btn_blue" value="{0}"/><a id="us_close_btn_note" href="#">' + q.ab + "</a></div></div></div>", pen:'<div class="us_box"><div class="us_animation us_pen"></div><div class="us_info_box"><div class="us_btn_cont"><input type="button" value="' +
        q.rc + '" class="us_btn_pen us_deactive" unselectable="on"></div><div class="us_description">' + q.Mc + '</div><div><input type="button" id="us_next_btn_pen" class="us_btn_blue" value="{0}"/><a id="us_close_btn_pen" href="#">' + q.ab + "</a></div></div></div>", arrow:'<div class="us_box"><div class="us_animation us_arrow"></div><div class="us_info_box"><div class="us_btn_cont"><input type="button" value="' + q.nc + '" class="us_btn_arrow us_deactive" unselectable="on"></div><div class="us_description">' +
        q.Hc + '</div><div><input type="button" id="us_next_btn_arrow" class="us_btn_blue" value="{0}"/><a id="us_close_btn_arrow" href="#">' + q.ab + "</a></div></div></div>"};
    function Sa(a) {
        this.e = a;
        this.X = this.Ad = this.sa = l;
        this.Pa = h;
        this.qb = this.Ya = 0;
        var b = this;
        this.kc = function(a) {
            b.X && (!I(B(a), "us_help_window") && !I(B(a), "us_report_window")) && (b.pa(), E(document.body, "mousedown", b.kc))
        }
    }
    function Ta(a, b) {
        a.Ya = b;
        for(var d = 0;d < a.qb;d++) {
            d === b ? D(a.c.childNodes[d], "us_box us_visible") : D(a.c.childNodes[d], "us_box")
        }
    }
    Sa.prototype.pa = function() {
        this.sa && (D(this.c.childNodes[this.Ya], "us_box"), C(this.c, "display:none;"), this.X = l, la("usersnap_hide_help", "true", 365))
    };
    Sa.prototype.show = function(a) {
        a === e && (a = l);
        if(!this.sa) {
            this.c = document.createElement("div");
            this.c.setAttribute("id", "us_help_window");
            this.qb = 0;
            var b = "", d = [], c = 0, f = k, g;
            for(g in this.e.f) {
                if("object" === typeof this.e.f[g]) {
                    for(f in this.e.f) {
                        "object" === typeof this.e.f[f] && this.e.f[f].index === c && d.push(f)
                    }
                    c++
                }
            }
            this.qb = d.length;
            for(c = 0;c < d.length;c++) {
                b = c === d.length - 1 ? b + s(Ra[d[c]], q.Xc) : b + s(Ra[d[c]], q.Yc)
            }
            b += Ra.footer;
            this.c.innerHTML = b;
            this.Pa = l;
            document.body.appendChild(this.c);
            this.Sc = t("us_need_help");
            var j = this;
            F(this.Sc, "click", function() {
                j.show(h)
            });
            b = function() {
                j.Ya === j.qb - 1 ? j.pa() : Ta(j, j.Ya + 1)
            };
            d = function(a) {
                j.pa();
                a = a || window.event;
                a.preventDefault && a.preventDefault();
                return a.returnValue = l
            };
            g = c = k;
            for(f in this.e.f) {
                "object" === typeof this.e.f[f] && this.e.f[f] !== k && (c = t("us_next_btn_" + f), g = t("us_close_btn_" + f), c !== k && (F(c, "click", b), F(g, "click", d)))
            }
            F(t("us_help_no"), "click", d);
            F(t("us_help_close"), "click", d);
            this.sa = h
        }
        f = 15;
        a ? (la("usersnap_hide_help", "true", 365), D(this.c, ""), this.Ya = 0, Ta(this, 0)) : (D(this.c, "us_small"), f = 215);
        C(this.c, "display:block; bottom: " + (this.e.bd + 29) + "px; right: " + f + "px;");
        this.X = h;
        F(document.body, "mousedown", this.kc)
    };
    Sa.prototype.D = function() {
        this.Pa || (document.body.removeChild(this.c), this.Pa = h)
    };
    function Ua(a) {
        this.Fa = [];
        this.sa = l;
        this.t = a;
        this.Qc = a.rd;
        this.ta = this.la = k
    }
    function Va(a, b) {
        switch(b) {
            case "crosshair":
                C(a.ta, "cursor: crosshair;");
                break;
            case "default":
                C(a.ta, "cursor: default;")
        }
    }
    Ua.prototype.D = function() {
        if(this.sa) {
            for(var a = 0;a < this.Fa.length;a++) {
                this.la.removeChild(this.Fa[a])
            }
            document.body.removeChild(this.la);
            document.body.removeChild(this.ta)
        }
    };
    function Wa(a, b) {
        if(a.sa) {
            var d = l;
            0 === b.length && (d = h, b.push([[10, 10], [10, 10]]));
            var c = [], f = [], g, j, n;
            for(j = 0;j < b.length;j++) {
                b[j] !== e && (c.push(b[j][0][0]), c.push(b[j][1][0]), f.push(b[j][0][1]), f.push(b[j][1][1]))
            }
            for(j = 0;j < c.length;j++) {
                for(g = 0;g < c.length - 1;g++) {
                    c[g] > c[g + 1] && (n = c[g + 1], c[g + 1] = c[g], c[g] = n)
                }
            }
            for(j = 0;j < f.length;j++) {
                for(g = 0;g < f.length - 1;g++) {
                    f[g] > f[g + 1] && (n = f[g + 1], f[g + 1] = f[g], f[g] = n)
                }
            }
            c.splice(0, 0, 0);
            f.splice(0, 0, 0);
            c.push(a.t.width);
            f.push(a.t.height);
            if(1 < c.length) {
                for(j = 1;j < c.length;j++) {
                    c[j] === c[j - 1] && (c.splice(j, 1), j--)
                }
            }
            if(1 < f.length) {
                for(j = 1;j < f.length;j++) {
                    f[j] === f[j - 1] && (f.splice(j, 1), j--)
                }
            }
            n = 0;
            var y, w = l, x;
            for(j = 1;j < f.length;j++) {
                for(g = 1;g < c.length;g++) {
                    w = l;
                    for(y = 0;y < b.length;y++) {
                        if(x = b[y], x !== e && x[0][0] <= c[g - 1] && x[1][0] >= c[g] && x[0][1] <= f[j - 1] && x[1][1] >= f[j]) {
                            w = h;
                            break
                        }
                    }
                    w || (n < a.Fa.length ? C(a.Fa[n], "top: " + f[j - 1] + "px; left: " + c[g - 1] + "px; width: " + (c[g] - c[g - 1]) + "px; height: " + (f[j] - f[j - 1]) + "px;display:block;") : (y = a, w = "top: " + f[j - 1] + "px; left:" + c[g - 1] + "px; width: " + (c[g] - c[g - 1]) + "px; height: " + (f[j] - f[j - 1]) + "px;", x = document.createElement("div"), C(x, w), D(x, "us_report_overlay_element"), x.setAttribute("unselectable", "on"), y.la.appendChild(x), y = x, a.Fa.push(y)), n++)
                }
            }
            for(j = n;j < a.Fa.length;j++) {
                C(a.Fa[j], "display:none;")
            }
            d && b.splice(0, 1)
        }
    }
    function Xa(a) {
        this.m = l;
        this.ra = [];
        this.q = this.A = k;
        this.h = h;
        this.L = [];
        this.t = a;
        this.ia = a.M;
        this.A = document.createElement("div");
        D(this.A, "us_report_selector_high");
        document.body.appendChild(this.A);
        var b = this;
        this.g = {O:function(a) {
            b.aa.call(b, a)
        }, N:function(a) {
            2 !== a.button && (I(B(a), "us_report_infoscreen") || I(B(a), "us_help_window") || I(B(a), "us_report_remove") || I(B(a), "us_report_window") || I(B(a), "us_report_note") || b.$.call(b, a))
        }, P:function(a) {
            2 !== a.button && (I(B(a), "us_report_infoscreen") || I(B(a), "us_help_window") || I(B(a), "us_report_remove") || I(B(a), "us_report_window") || I(B(a), "us_report_note") || b.ba.call(b, a))
        }};
        F(document.body, "mousemove", this.g.O);
        F(document.body, "mousedown", this.g.N);
        F(document.body, "mouseup", this.g.P)
    }
    m = Xa.prototype;
    m.disable = function() {
        this.m = l;
        this.h = h
    };
    m.enable = function() {
        this.h = l;
        Va(this.ia, "crosshair")
    };
    m.cancel = function() {
        if(!this.m) {
            return l
        }
        this.m = l;
        C(this.A, "display: none;");
        this.q = k;
        return h
    };
    m.Sb = function() {
        this.m && (this.m = l, C(this.A, "display: none;"), this.q = k)
    };
    m.aa = function(a) {
        if(this.m) {
            var b = H(), d = a.clientX + b[0];
            a = a.clientY + b[1];
            b = Math.abs(d - this.H.b) - 2;
            0 > b && (b = 0);
            var c = Math.abs(a - this.H.a) - 2;
            0 > c && (c = 0);
            var f = this.H.b, g = this.H.a;
            0 > d - this.H.b && (f = d);
            0 > a - this.H.a && (g = a);
            C(this.A, "display: block; top: " + g + "px; left: " + f + "px; width: " + b + "px; height: " + c + "px;");
            this.q = {top:g, left:f, width:b, height:c, right:f + b, bottom:g + c}
        }
    };
    m.$ = function(a) {
        if(this.h) {
            Ba("disabled")
        }else {
            var b = H();
            this.H = {b:a.clientX + b[0], a:a.clientY + b[1]};
            C(this.A, "display: block; top: " + this.H.a + "px; left: " + this.H.b + "px; width: 0px; height: 0px;");
            this.m = h
        }
    };
    m.ba = function() {
        !this.m || this.q === k ? this.Sb() : (Ba("dragging false"), this.m = l, C(this.A, "display: none;"), 10 <= this.q.height && 10 <= this.q.width && (this.Eb(this.q), this.ra.push([[this.q.left, this.q.top], [this.q.right, this.q.bottom]])), this.q = k)
    };
    m.D = function() {
        E(document.body, "mousemove", this.g.O);
        E(document.body, "mousedown", this.g.N);
        E(document.body, "mouseup", this.g.P);
        for(var a = 0;a < this.L.length;a++) {
            this.L[a] !== e && document.body.removeChild(this.L[a])
        }
        document.body.removeChild(this.A)
    };
    m.Eb = function(a) {
        var b = document.createElement("div");
        C(b, "top: " + a.top + "px; left: " + a.left + "px; width: " + a.width + "px; height: " + a.height + "px;");
        D(b, "us_report_blackout");
        b.innerHTML = '<div class="us_report_remove"></div><div class="us_inner" unselectable="on"></div>';
        b.setAttribute("unselectable", "on");
        document.body.appendChild(b);
        var d = this.L.length;
        this.L.push(b);
        var c = this;
        F(b.childNodes[0], "click", function() {
            document.body.removeChild(c.L[d]);
            c.L[d] = e;
            c.ra[d] = e
        });
        return b
    };
    function Ya(a) {
        this.m = l;
        this.Z = [];
        this.q = this.A = k;
        this.h = h;
        this.L = [];
        this.t = a;
        this.ia = a.M;
        this.A = document.createElement("div");
        D(this.A, "us_report_selector_high");
        document.body.appendChild(this.A);
        var b = this;
        this.g = {O:function(a) {
            b.aa.call(b, a)
        }, N:function(a) {
            2 !== a.button && (I(B(a), "us_report_infoscreen") || I(B(a), "us_help_window") || I(B(a), "us_report_remove") || I(B(a), "us_report_window") || I(B(a), "us_report_note") || b.$.call(b, a))
        }, P:function(a) {
            2 !== a.button && b.ba.call(b, a)
        }};
        F(document.body, "mousemove", this.g.O);
        F(document.body, "mousedown", this.g.N);
        F(document.body, "mouseup", this.g.P)
    }
    function Za() {
        for(var a = V.f.highlight, b = [], d = na(), c = oa(), f = Math.round, g = 0;g < a.Z.length;g++) {
            a.Z[g] !== e && b.push([[f(a.Z[g][0][0] - d), f(a.Z[g][0][1] - c)], [f(a.Z[g][1][0] - d), f(a.Z[g][1][1] - c)]])
        }
        return b
    }
    m = Ya.prototype;
    m.disable = function() {
        this.m = l;
        this.h = h
    };
    m.enable = function() {
        this.h = l;
        Va(this.ia, "crosshair")
    };
    m.cancel = function() {
        if(!this.m) {
            return l
        }
        this.m = l;
        C(this.A, "display: none;");
        this.q = k;
        return h
    };
    m.Sb = function() {
        this.m && (this.m = l, C(this.A, "display: none;"), this.q = k)
    };
    m.aa = function(a) {
        if(this.m) {
            var b = H(), d = a.clientX + b[0];
            a = a.clientY + b[1];
            var b = this.H.b, c = this.H.a, f = Math.abs(d - this.H.b) - 2;
            0 > f && (f = 0);
            var g = Math.abs(a - this.H.a) - 2;
            0 > g && (g = 0);
            0 > d - this.H.b && (b = d);
            0 > a - this.H.a && (c = a);
            C(this.A, "display: block; top: " + c + "px; left: " + b + "px; width: " + f + "px; height: " + g + "px;");
            this.q = {top:c, left:b, width:f, height:g, right:b + f, bottom:c + g}
        }
    };
    m.$ = function(a) {
        if(this.h) {
            Ba("disabled")
        }else {
            var b = H();
            this.H = {b:a.clientX + b[0], a:a.clientY + b[1]};
            C(this.A, "display: block; top: " + this.H.a + "px; left: " + this.H.b + "px; width: 0px; height: 0px;");
            this.m = h
        }
    };
    m.ba = function() {
        !this.m || this.q === k ? this.Sb() : (Ba("dragging false"), this.m = l, C(this.A, "display: none;"), 10 <= this.q.height && 10 <= this.q.width && (this.Eb(this.q), this.Z.push([[this.q.left, this.q.top], [this.q.right, this.q.bottom]]), Wa(this.t.M, this.Z)), this.q = k)
    };
    m.D = function() {
        E(document.body, "mousemove", this.g.O);
        E(document.body, "mousedown", this.g.N);
        E(document.body, "mouseup", this.g.P);
        for(var a = 0;a < this.L.length;a++) {
            this.L[a] !== e && document.body.removeChild(this.L[a])
        }
        document.body.removeChild(this.A)
    };
    m.Eb = function(a) {
        var b = document.createElement("div");
        C(b, "top: " + a.top + "px; left: " + a.left + "px; width: " + a.width + "px; height: " + a.height + "px;");
        D(b, "us_report_highlight");
        b.innerHTML = '<div class="us_report_remove"></div><div class="us_inner" unselectable="on"></div>';
        b.setAttribute("unselectable", "on");
        document.body.appendChild(b);
        var d = this.L.length;
        this.L.push(b);
        var c = this;
        F(b.childNodes[0], "click", function() {
            document.body.removeChild(c.L[d]);
            c.L[d] = e;
            c.Z[d] = e;
            Wa(c.t.M, c.Z)
        });
        return b
    };
    function $a(a) {
        this.m = l;
        this.W = [];
        this.h = h;
        this.J = this.V = this.Qa = k;
        this.Fb = q.sd;
        this.Kb = this.Oa = this.Ib = k;
        this.t = a;
        this.ia = a.M;
        var b = this;
        this.g = {O:function(a) {
            I(B(a), "us_report_window") ? C(b.Q, "display:none;") : I(B(a), "us_report_infoscreen") ? C(b.Q, "display:none;") : I(B(a), "us_help_window") ? C(b.Q, "display:none;") : b.aa.call(b, a)
        }, N:function(a) {
            2 !== a.button && (I(B(a), "us_report_infoscreen") || I(B(a), "us_report_note_text") || I(B(a), "us_report_note_rem") || I(B(a), "us_help_window") || I(B(a), "us_report_remove") || I(B(a), "us_report_window") || b.$.call(b, a))
        }, P:function(a) {
            2 !== a.button && (I(B(a), "us_report_infoscreen") || I(B(a), "us_report_note_text") || I(B(a), "us_report_note_rem") || I(B(a), "us_help_window") || I(B(a), "us_report_remove") || I(B(a), "us_report_window") || b.ba.call(b, a))
        }};
        F(document.body, "mousemove", this.g.O);
        F(document.body, "mousedown", this.g.N);
        F(document.body, "mouseup", this.g.P);
        this.Q = document.createElement("div");
        D(this.Q, "us_report_note_cursor");
        this.Q.setAttribute("unselectable", "on");
        C(this.Q, "display:none;");
        document.body.appendChild(this.Q)
    }
    function ab() {
        for(var a = V.f.note, b = [], d, c, f, g = na(), j = oa(), n = Math.round, y = eval("'" + a.Fb.replace(/&#x([0-9a-z]{4});/g, "\\u$1") + "'"), w = 0;w < a.W.length;w++) {
            a.W[w] !== e && (d = parseInt(a.W[w].style.left.replace(/px/, ""), 10), c = parseInt(a.W[w].style.top.replace(/px/, ""), 10), f = a.W[w].children[1].value, "" === f || f === y || b.push([n(d - g), n(c - j), f]))
        }
        return b
    }
    m = $a.prototype;
    m.disable = function() {
        this.h = h;
        C(this.Q, "display:none;")
    };
    m.enable = function() {
        this.h = l;
        Va(this.ia, "default")
    };
    m.cancel = function() {
        return this.m ? (this.m = l, this.J = this.V = this.Qa = k, h) : l
    };
    m.nb = function(a, b) {
        "undefined" === typeof b && (b = l);
        var d, c;
        b ? (d = this.t.width - 45, c = this.t.height - 45) : (d = this.t.width - 205, c = this.t.height - 200);
        a.b > d && (a.b = d);
        a.a > c && (a.a = c);
        0 > a.a && (a.a = 0)
    };
    m.aa = function(a) {
        var b;
        this.m ? (C(this.Q, "display:none;"), b = {b:a.clientX, a:a.clientY}, this.V !== k ? (this.J = {b:this.J.b + (b.b - this.V.b), a:this.J.a + (b.a - this.V.a)}, this.nb(this.J), C(this.Qa, "display:block; top: " + this.J.a + "px; left: " + this.J.b + "px;")) : this.J = {b:parseInt(this.Qa.style.left.replace(/px/, ""), 10), a:parseInt(this.Qa.style.top.replace(/px/, ""), 10)}, this.V = b) : this.h || (b = B(a), ja(B(a)) ? (D(b, "us_report_note us_hover"), this.Kb = b) : this.Kb !== k && D(this.Kb,
            "us_report_note"), b = H(), b = {b:a.clientX + b[0], a:a.clientY + b[1]}, this.nb(b, h), I(B(a), "us_report_note") ? C(this.Q, "display:none;") : (a = b.b + 16, v() && a > this.t.width - 50 && (a = this.t.width - 50), C(this.Q, "display:block; top: " + (b.a + 16) + "px; left: " + a + "px;")))
    };
    m.$ = function(a) {
        ja(B(a)) && (this.m = h, this.Qa = B(a))
    };
    m.ba = function(a) {
        if(this.m) {
            this.m = l, this.J = this.V = this.Qa = k
        }else {
            if(!this.h) {
                this.Oa = bb(this);
                var b = H(), d = a.clientX + b[0];
                a = a.clientY + b[1];
                var c = da();
                d - b[0] > c.width - 200 && (d = c.width - 200 + b[0]);
                a - b[1] > c.height - 180 && (a = c.height - 180 + b[1]);
                C(this.Oa, "display:block; top: " + a + "px; left: " + d + "px;");
                D(this.Oa.childNodes[0], "us_report_note_rem");
                var f = this.W.length - 1, g = this;
                F(this.Oa.childNodes[0], "click", function() {
                    document.body.removeChild(g.W[f]);
                    g.W[f] = e
                });
                this.Ib = this.Oa.getElementsByTagName("textarea")[0];
                this.Oa = k;
                this.Ib !== k && this.Ib.focus()
            }
        }
    };
    m.D = function() {
        E(document.body, "mousemove", this.g.O);
        E(document.body, "mousedown", this.g.N);
        E(document.body, "mouseup", this.g.P);
        for(var a = 0;a < this.W.length;a++) {
            this.W[a] !== e && document.body.removeChild(this.W[a])
        }
        document.body.removeChild(this.Q);
        this.Q = k
    };
    function bb(a) {
        var b = document.createElement("div");
        D(b, "us_report_note");
        b.innerHTML = '<div class="us_report_note_rem_empty"></div><textarea class="us_report_note_text">' + a.Fb + "</textarea>";
        b.setAttribute("unselectable", "on");
        C(b, "display:none;");
        document.body.appendChild(b);
        var d = b.childNodes[1], c = eval("'" + a.Fb.replace(/&#x([0-9a-z]{4});/g, "\\u$1") + "'");
        F(d, "focus", function() {
            d.value === c && (d.value = "")
        });
        F(d, "blur", function() {
            "" === d.value && (d.value = c)
        });
        a.W.push(b);
        return b
    }
    function cb(a) {
        this.h = h;
        this.G = l;
        this.j = this.s = k;
        this.Ha = l;
        this.w = [];
        this.K = k;
        this.u = [];
        this.ha = k;
        this.B = document.createElement("div");
        this.B.setAttribute("id", "us_painter");
        this.B.setAttribute("unselectable", "on");
        this.t = a;
        this.ia = a.M;
        var b = ca(a.width, a.height);
        a.width = b.width;
        a.height = b.height;
        C(this.B, "width: " + a.width + "px; height: " + a.height + "px;");
        document.body.appendChild(this.B);
        if(A() || v()) {
            this.Ha = l;
            try {
                document.namespaces.v || document.namespaces.add("v", "urn:schemas-microsoft-com:vml"), this.Ha = h
            }catch(d) {
                O("failed to add namespace")
            }
        }else {
            this.s = document.createElement("canvas"), this.s.setAttribute("width", a.width), this.s.setAttribute("height", a.height), this.B.appendChild(this.s), this.j = this.s.getContext("2d")
        }
        var c = this;
        this.ka = l;
        this.g = {O:function(a) {
            c.Ob.call(c, a);
            if(!c.h) {
                var b = ga(a);
                u() && (9 >= document.documentMode && !window.event.button) && (c.ka = l);
                1 === b && !c.ka && (b = 0);
                1 !== b && (c.G = l, c.K = k);
                c.aa.call(c, a)
            }
        }, N:function(a) {
            c.h || (1 === ga(a) && (c.ka = h), 2 !== a.button && (I(B(a), "us_report_infoscreen") || I(B(a), "us_help_window") || I(B(a), "us_report_remove") || I(B(a), "us_report_window") || I(B(a), "us_report_note") || I(B(a), "us_path_remove") || c.$.call(c, a)))
        }, P:function(a) {
            c.h || (1 === ga(a) && (c.ka = l), 2 !== a.button && (I(B(a), "us_report_remove") || I(B(a), "us_path_remove") || c.ba.call(c, a)))
        }, ib:function() {
            c.Uc.call(c, c.i.Ka)
        }};
        F(document, "mousemove", this.g.O);
        F(document, "mousedown", this.g.N);
        F(document, "mouseup", this.g.P);
        this.ac("#EEF521", 10);
        this.S = [];
        this.i = {Zb:Math.pow(20, 2), Ka:k, state:l, T:k, qa:k};
        this.ma = document.createElement("div");
        this.ma.setAttribute("id", "us_path_remove");
        this.ma.setAttribute("unselectable", "on");
        document.body.appendChild(this.ma);
        F(this.ma, "click", this.g.ib)
    }
    m = cb.prototype;
    m.Ob = function(a) {
        if(0 !== this.S.length && "us_path_remove" !== B(a).getAttribute("id")) {
            this.i.qa !== k && window.clearTimeout(this.i.qa);
            var b = a.clientX;
            a = a.clientY;
            for(var d = k, c = k, f = "", g, j, n, y, w, x, ea = l, P = 0;P < this.S.length;P++) {
                d = this.S[P];
                c = this.u[P].path;
                if(b >= d.Ba && b <= d.za && a >= d.Ca && a <= d.Aa) {
                    for(var G = 0;G < d.La.length;G++) {
                        j = 2 * G;
                        g = d.La[G].k;
                        var M = d.La[G].d, Y = a + g * b, N = -g;
                        n = Math.max(c[j], c[j + 2]);
                        w = Math.min(c[j], c[j + 2]);
                        y = Math.max(c[j + 1], c[j + 3]);
                        x = Math.min(c[j + 1], c[j + 3]);
                        if(1E5 === g) {
                            if(g = c[j], j = a, j < x || j > y) {
                                continue
                            }
                        }else {
                            if(0 === g) {
                                if(g = b, j = c[j + 1], g < w || g > n) {
                                    continue
                                }
                            }else {
                                if(g = (M - Y) / (2 * N), j = g * N + Y, g < w || g > n || j < x || j > y) {
                                    continue
                                }
                            }
                        }
                        n = Math.pow(g - b, 2);
                        y = Math.pow(j - a, 2);
                        if(n + y < this.i.Zb) {
                            d = this.u[P].path.length;
                            c = H();
                            f = "display: block; top:" + (this.u[P].path[d - 1] - 10 + c[1]) + "px; left: " + (this.u[P].path[d - 2] - 10 + c[0]) + "px;";
                            this.i.Ka = P;
                            ea = h;
                            break
                        }
                    }
                }
                if(ea) {
                    break
                }
            }
            var z = this;
            ea ? (this.i.T !== k && (window.clearTimeout(this.i.T), this.i.T = k), this.i.qa = window.setTimeout(function() {
                z.i.state = h;
                z.i.qa = k;
                C(z.ma, f)
            }, 50)) : this.i.Ka !== k && (this.i.T === k && this.i.state) && (this.i.T = window.setTimeout(function() {
                z.i.state = l;
                C(z.ma, "display: none;");
                z.i.T = k
            }, 250))
        }
    };
    m.Uc = function(a) {
        if(a !== k) {
            if(this.S.splice(a, 1), this.u.splice(a, 1), C(this.ma, "display: none;"), this.s !== k) {
                this.j.clearRect(0, 0, this.s.width, this.s.height);
                for(a = 0;a < this.u.length;a++) {
                    this.j.strokeStyle = this.u[a].color;
                    this.j.lineWidth = this.u[a].lineWidth;
                    this.j.beginPath();
                    for(var b = 0;b < this.u[a].path.length;b += 2) {
                        0 === b ? this.j.moveTo(this.u[a].path[b], this.u[a].path[b + 1]) : this.j.lineTo(this.u[a].path[b], this.u[a].path[b + 1]), this.j.stroke()
                    }
                }
                this.j.strokeStyle = this.ha.color;
                this.j.lineWidth = this.ha.lineWidth
            }else {
                this.B.removeChild(this.w[a].ctn), this.w.splice(a, 1)
            }
        }
    };
    function db() {
        for(var a = V.f.pen, b = [], d = 0;d < a.u.length;d++) {
            b.push(a.u[d].path)
        }
        return b
    }
    m.ac = function(a, b) {
        "undefined" === typeof b && (b = 10);
        this.ha = {color:a, lineWidth:b};
        this.s !== k && (this.j.strokeStyle = a, this.j.lineWidth = b)
    };
    m.aa = function(a) {
        if(this.G) {
            var b = H(), d = Math.round, b = {b:d(a.clientX) + b[0], a:d(a.clientY) + b[1]};
            if(!(5 > Math.abs(b.b - this.K.b) && 5 > Math.abs(b.a - this.K.a))) {
                if(this.s !== k) {
                    this.j.lineTo(b.b, b.a), this.j.stroke()
                }else {
                    var c = this.w[this.w.length - 1].p.v, c = c.substr(0, c.length - 2) + " l" + b.b + "," + b.a + " e";
                    this.w[this.w.length - 1].p.v = c
                }
                this.u[this.u.length - 1].path.push(d(a.clientX), d(a.clientY));
                this.K = b
            }
        }
    };
    m.$ = function(a) {
        if(!this.G) {
            this.G = h;
            var b = H(), d = Math.round, c = {b:d(a.clientX) + b[0], a:d(a.clientY) + b[1]};
            this.K = c;
            if(this.s !== k) {
                this.j.beginPath(), this.j.moveTo(c.b, c.a)
            }else {
                var f, g;
                this.Ha ? (b = document.createElement('<v:shape class="usvml" style="position: absolute; left: 0px; top: 0px; width: 1px; height: 1px;" coordsize="1,1">'), f = document.createElement('<v:stroke class="usvml" weight="' + this.ha.lineWidth + '" color="' + this.ha.color + '">'), g = document.createElement('<v:fill class="usvml" on="false">'), c = document.createElement('<v:path class="usvml" v="m' + c.b + "," + c.a + " l" + c.b + "," + c.a + ' e">')) : (b = document.createElement('<shape xmlns="urn:schemas-microsoft.com:vml" class="usvml" style="position: absolute; left: 0px; top: 0px; width: 1px; height: 1px;" coordsize="1,1">'),
                    f = document.createElement('<stroke xmlns="urn:schemas-microsoft.com:vml" class="usvml" weight="' + this.ha.lineWidth + '" color="' + this.ha.color + '">'), g = document.createElement('<fill xmlns="urn:schemas-microsoft.com:vml" class="usvml" on="false">'), c = document.createElement('<path xmlns="urn:schemas-microsoft.com:vml" class="usvml" v="m' + c.b + "," + c.a + " l" + c.b + "," + c.a + ' e">'));
                b.appendChild(c);
                b.appendChild(g);
                b.appendChild(f);
                this.w.push({p:c, ctn:b});
                this.B.appendChild(b)
            }
            this.u.push({path:[d(a.clientX), d(a.clientY)], color:this.ha.color, width:this.ha.lineWidth})
        }
    };
    m.ba = function(a) {
        if(this.G) {
            var b = H(), d = Math.round, c = d(a.clientX) + b[0], b = d(a.clientY) + b[1];
            if(this.s !== k) {
                this.j.lineTo(c, b), this.j.stroke()
            }else {
                var f = this.w[this.w.length - 1].p.v, f = f.substr(0, f.length - 2) + " l" + c + "," + b + " e";
                this.w[this.w.length - 1].p.v = f
            }
            this.u[this.u.length - 1].path.push(d(a.clientX), d(a.clientY));
            a = this.u[this.u.length - 1].path;
            d = {La:[], Ba:k, za:k, Ca:k, Aa:k};
            for(c = 0;c < a.length;c += 2) {
                d.Ba = d.Ba === k ? a[c] : Math.min(a[c], d.Ba), d.za = d.za === k ? a[c] : Math.max(a[c], d.za), d.Ca = d.Ca === k ? a[c + 1] : Math.min(a[c + 1], d.Ca), d.Aa = d.Aa === k ? a[c + 1] : Math.max(a[c + 1], d.Aa)
            }
            d.Ba -= 5;
            d.za += 5;
            d.Ca -= 5;
            d.Aa += 5;
            for(c = 0;c < a.length - 2;c += 2) {
                b = (a[c + 1] - a[c + 3]) / (a[c] - a[c + 2]), isFinite(b) || (b = 1E5), d.La.push({k:b, d:a[c + 1] - b * a[c]})
            }
            this.S.push(d);
            this.G = l;
            this.K = k
        }
    };
    m.D = function() {
        E(document, "mousemove", this.g.O);
        E(document, "mousedown", this.g.N);
        E(document, "mouseup", this.g.P);
        E(this.ma, "click", this.g.ib);
        document.body.removeChild(this.B);
        document.body.removeChild(this.ma);
        this.S = []
    };
    m.disable = function() {
        this.h = h
    };
    m.enable = function() {
        this.h = l;
        Va(this.ia, "crosshair")
    };
    m.cancel = function() {
        if(!this.G) {
            return l
        }
        this.G = l;
        this.K = k;
        return h
    };
    function eb(a) {
        this.h = h;
        this.G = l;
        this.j = this.s = k;
        this.Ha = l;
        this.w = [];
        this.Pb = "";
        this.Bd = this.K = k;
        this.o = [];
        this.B = document.createElement("div");
        this.B.setAttribute("id", "us_arrow");
        this.B.setAttribute("unselectable", "on");
        this.t = a;
        this.ia = a.M;
        var b = ca(a.width, a.height);
        a.width = b.width;
        a.height = b.height;
        C(this.B, "width: " + a.width + "px; height: " + a.height + "px;");
        document.body.appendChild(this.B);
        if(A() || v()) {
            this.Ha = l;
            try {
                document.namespaces.v || document.namespaces.add("v", "urn:schemas-microsoft-com:vml"), this.Ha = h
            }catch(d) {
                O("failed to add namespace")
            }
        }else {
            this.s = document.createElement("canvas"), this.s.setAttribute("width", a.width), this.s.setAttribute("height", a.height), this.B.appendChild(this.s), this.j = this.s.getContext("2d")
        }
        var c = this;
        this.ka = l;
        this.g = {O:function(a) {
            c.Ob.call(c, a);
            if(!c.h) {
                var b = ga(a);
                u() && (9 >= document.documentMode && !window.event.button) && (c.ka = l);
                1 === b && !c.ka && (b = 0);
                1 !== b && (c.G = l, c.K = k);
                c.aa.call(c, a)
            }
        }, N:function(a) {
            c.h || (1 === ga(a) && (c.ka = h), 2 !== a.button && (I(B(a), "us_report_infoscreen") || I(B(a), "us_help_window") || I(B(a), "us_report_remove") || I(B(a), "us_report_window") || I(B(a), "us_report_note") || I(B(a), "us_path_remove") || c.$.call(c, a)))
        }, P:function(a) {
            c.h || (1 === ga(a) && (c.ka = l), 2 !== a.button && (I(B(a), "us_report_remove") || I(B(a), "us_path_remove") || c.ba.call(c, a)))
        }, ib:function() {
            c.Tc.call(c, c.i.Ka)
        }};
        F(document, "mousemove", this.g.O);
        F(document, "mousedown", this.g.N);
        F(document, "mouseup", this.g.P);
        this.ac("#EEF521");
        this.S = [];
        this.i = {Zb:Math.pow(20, 2), Ka:k, state:l, T:k, qa:k};
        this.ea = document.createElement("div");
        this.ea.setAttribute("id", "us_path_remove");
        this.ea.setAttribute("unselectable", "on");
        document.body.appendChild(this.ea);
        F(this.ea, "click", this.g.ib)
    }
    m = eb.prototype;
    m.Ob = function(a) {
        if(0 !== this.S.length && "us_path_remove" !== B(a).getAttribute("id")) {
            this.i.qa !== k && window.clearTimeout(this.i.qa);
            var b = a.clientX;
            a = a.clientY;
            for(var d = k, c = "", f = k, f = k, g, j, n, y, w = l, x = 0;x < this.S.length;x++) {
                d = this.S[x];
                f = this.o[x].z;
                f = [f.start[0], f.start[1], f.end[0], f.end[1]];
                if(b >= d.Ba && b <= d.za && a >= d.Ca && a <= d.Aa) {
                    g = d.La[0].k;
                    var ea = d.La[0].d, P = a + g * b, G = -g, d = Math.max(f[0], f[2]);
                    n = Math.min(f[0], f[2]);
                    j = Math.max(f[1], f[3]);
                    y = Math.min(f[1], f[3]);
                    if(1E5 === g) {
                        if(g = f[0], f = a, f < y || f > j) {
                            continue
                        }
                    }else {
                        if(0 === g) {
                            if(g = b, f = f[1], g < n || g > d) {
                                continue
                            }
                        }else {
                            if(g = (ea - P) / (2 * G), f = g * G + P, g < n || g > d || f < y || f > j) {
                                continue
                            }
                        }
                    }
                    d = Math.pow(g - b, 2);
                    f = Math.pow(f - a, 2);
                    if(d + f < this.i.Zb) {
                        c = "display: block; top:" + (this.o[x].Wa.end.a - 10) + "px; left: " + (this.o[x].Wa.end.b - 10) + "px;";
                        this.i.Ka = x;
                        w = h;
                        break
                    }
                }
                if(w) {
                    break
                }
            }
            var M = this;
            w ? (this.i.T !== k && (window.clearTimeout(this.i.T), this.i.T = k), this.i.qa = window.setTimeout(function() {
                M.i.state = h;
                M.i.qa = k;
                C(M.ea, c)
            }, 50)) : this.i.Ka !== k && (this.i.T === k && this.i.state) && (this.i.T = window.setTimeout(function() {
                M.i.state = l;
                C(M.ea, "display: none;");
                M.i.T = k
            }, 250))
        }
    };
    m.Tc = function(a) {
        a !== k && (this.S.splice(a, 1), this.o.splice(a, 1), C(this.ea, "display: none;"), fb(this))
    };
    function gb() {
        for(var a = V.f.arrow, b = [], d = Math.round, c = 0;c < a.o.length;c++) {
            b.push([d(a.o[c].z.start[0]), d(a.o[c].z.start[1]), d(a.o[c].z.end[0]), d(a.o[c].z.end[1])])
        }
        return b
    }
    m.ac = function(a) {
        this.s !== k ? (this.j.strokeStyle = a, this.j.fillStyle = a, this.j.lineWidth = 10) : this.Pb = a
    };
    function fb(a) {
        var b, d;
        a.s !== k && a.j.clearRect(0, 0, a.s.width, a.s.height);
        var c, f;
        c = k;
        for(var g = 0;g < a.o.length;g++) {
            if(b = a.o[g].Wa.start, d = a.o[g].Wa.end, a.s !== k) {
                a.j.beginPath();
                a.j.moveTo(b.b, b.a);
                a.j.lineTo(d.b, d.a);
                a.j.stroke();
                c = b = Math.atan2(d.a - b.a, d.b - b.b);
                b = d.b;
                d = d.a;
                var j = [[30, 0], [-2, -20], [-2, 20]];
                f = [];
                var n = k;
                for(n in j) {
                    "function" !== typeof n && f.push([j[n][0] * Math.cos(c) - j[n][1] * Math.sin(c), j[n][0] * Math.sin(c) + j[n][1] * Math.cos(c)])
                }
                c = [];
                for(n in f) {
                    "function" !== typeof n && c.push([f[n][0] + b, f[n][1] + d])
                }
                b = a;
                b.j.beginPath();
                b.j.moveTo(c[0][0], c[0][1]);
                d = k;
                for(d in c) {
                    0 < d && b.j.lineTo(c[d][0], c[d][1])
                }
                b.j.lineTo(c[0][0], c[0][1]);
                b.j.fill()
            }else {
                f = "m" + b.b + "," + b.a + " l" + d.b + "," + d.a + " e", g >= a.w.length ? (a.Ha ? (b = document.createElement('<v:shape class="usvml" style="position: absolute; left: 0px; top: 0px; width: 1px; height: 1px;" coordsize="1,1">'), c = document.createElement('<v:stroke endarrow="classic" class="usvml" weight="10" color="' + a.Pb + '">'), d = document.createElement('<v:fill class="usvml" on="false">'), f = document.createElement('<v:path class="usvml" v="' + f + '">')) : (b = document.createElement('<shape xmlns="urn:schemas-microsoft.com:vml" class="usvml" style="position: absolute; left: 0px; top: 0px; width: 1px; height: 1px;" coordsize="1,1">'),
                    c = document.createElement('<stroke endarrow="classic" xmlns="urn:schemas-microsoft.com:vml" class="usvml" weight="10" color="' + a.Pb + '">'), d = document.createElement('<fill xmlns="urn:schemas-microsoft.com:vml" class="usvml" on="false">'), f = document.createElement('<path xmlns="urn:schemas-microsoft.com:vml" class="usvml" v="' + f + '">')), b.appendChild(f), b.appendChild(d), b.appendChild(c), a.B.appendChild(b), a.w.push({r:b, p:f})) : a.w[g].p.v = f
            }
        }
        if(a.s === k && g < a.w.length) {
            for(b = g;b < a.w.length;b++) {
                a.B.removeChild(a.w[b].r)
            }
            a.w.splice(g, a.w.length - g)
        }
    }
    m.aa = function(a) {
        if(this.G) {
            var b = H(), b = {b:a.clientX + b[0], a:a.clientY + b[1]};
            5 > Math.abs(b.b - this.K.b) && 5 > Math.abs(b.a - this.K.a) || (this.o[this.o.length - 1].Wa.end = b, this.o[this.o.length - 1].z.end = [a.clientX, a.clientY], fb(this), this.K = b)
        }
    };
    m.$ = function(a) {
        if(!this.G) {
            this.G = h;
            var b = H();
            this.K = b = {b:a.clientX + b[0], a:a.clientY + b[1]};
            this.o.push({Wa:{start:b, end:k}, z:{start:[a.clientX, a.clientY], end:k}})
        }
    };
    m.ba = function(a) {
        if(this.G) {
            var b = H();
            this.o[this.o.length - 1].Wa.end = {b:a.clientX + b[0], a:a.clientY + b[1]};
            this.o[this.o.length - 1].z.end = [a.clientX, a.clientY];
            a = this.o[this.o.length - 1];
            b = (a.z.start[1] - a.z.end[1]) / (a.z.start[0] - a.z.end[0]);
            isFinite(b) || (b = 1E5);
            a = {La:[{k:b, d:a.z.start[1] - b * a.z.start[0]}], Ba:Math.min(a.z.start[0], a.z.end[0]) - 5, za:Math.max(a.z.start[0], a.z.end[0]) + 5, Ca:Math.min(a.z.start[1], a.z.end[1]) - 5, Aa:Math.max(a.z.start[1], a.z.end[1]) + 5};
            this.S.push(a);
            fb(this);
            this.G = l;
            this.K = k
        }
    };
    m.D = function() {
        E(document, "mousemove", this.g.O);
        E(document, "mousedown", this.g.N);
        E(document, "mouseup", this.g.P);
        E(this.ea, "click", this.g.ib);
        document.body.removeChild(this.B);
        document.body.removeChild(this.ea);
        this.S = []
    };
    m.disable = function() {
        this.h = h
    };
    m.enable = function() {
        this.h = l;
        Va(this.ia, "crosshair")
    };
    m.cancel = function() {
        if(!this.G) {
            return l
        }
        this.G = l;
        this.o.splice(this.o.length - 1, 1);
        this.B.removeChild(this.w[this.w.length - 1].r);
        fb(this);
        this.K = k;
        return h
    };
    var W = k, hb = k, ib = k, jb = k, kb = l, lb = k, mb = k;
    L = function(a, b) {
        W === k && (W = document.createElement("div"), hb = document.createElement("div"), jb = document.createElement("div"), D(hb, "us_tooltip"), D(jb, "us_tip_arrow"), D(W, "us_tooltip_cont"), W.appendChild(hb), W.appendChild(jb), document.body.appendChild(W));
        F(a, "mouseover", function() {
            kb = h;
            mb !== k && (window.clearTimeout(mb), mb = k);
            lb = window.setTimeout(function() {
                if(kb) {
                    var d, c = a;
                    d = 0;
                    for(var f = a, g = 0;f;) {
                        d += f.offsetTop, f = f.offsetParent
                    }
                    for(;c;) {
                        g += c.offsetLeft, c = c.offsetParent
                    }
                    c = H();
                    d += c[1];
                    g += c[0];
                    d = [g, d];
                    hb.innerHTML = b;
                    C(W, "display: block;");
                    ib = {width:fa() - (u() ? 30 : 15), height:ka() - 15};
                    g = d[0];
                    c = d[1] - (W.clientHeight + 5);
                    g + W.clientWidth > ib.width ? (g = ib.width - (W.clientWidth + 15), C(jb, "left: " + (d[0] - g) + "px;")) : C(jb, "left: 7px;");
                    C(W, "top: " + c + "px; left: " + g + "px;")
                }
            }, 600)
        });
        F(a, "mouseout", function() {
            lb !== k && (window.clearTimeout(lb), lb = k);
            kb = l;
            mb = window.setTimeout(function() {
                C(W, "display: none;")
            }, 200)
        })
    };
    Aa = function() {
        W !== k && (lb !== k && (window.clearTimeout(lb), lb = k), mb !== k && (window.clearTimeout(mb), mb = k), C(W, "display: none;"))
    };
    za = function() {
        W !== k && (document.body.removeChild(W), W = k)
    };
    function nb() {
        this.$a = 0
    }
    function ob(a, b) {
        if(b === k) {
            return""
        }
        var d;
        d = "";
        if(A() || v()) {
            d = b.all[0].text, d === e && (d = "")
        }else {
            var c = b.doctype;
            c !== k && c !== e && (d = c.systemId === k ? '<!DOCTYPE HTML PUBLIC "' + c.publicId + '">' : '<!DOCTYPE HTML PUBLIC "' + c.publicId + '" "' + c.systemId + '">')
        }
        for(var c = b.getElementsByTagName("*"), f, g, j = 0, n = c.length;j < n;j++) {
            c[j].removeAttribute("data-usscrolly"), c[j].removeAttribute("data-usscrollx"), 0 < c[j].scrollTop && c[j].setAttribute("data-usscrolly", c[j].scrollTop), 0 < c[j].scrollLeft && c[j].setAttribute("data-usscrollx", c[j].scrollLeft), f = c[j], g = f.nodeName.toLowerCase(), "input" === g ? "text" === f.getAttribute("type") || "password" === f.getAttribute("type") ? f.setAttribute("data-usval", escape(f.value)) : ("checkbox" === f.getAttribute("type") || "radio" === f.getAttribute("type")) && f.checked ===
                h && f.setAttribute("data-uscheck", "true") : "select" === g ? f.setAttribute("data-usval", f.value) : "textarea" === g && f.setAttribute("data-usval", escape(f.value))
        }
        f = k;
        f = "undefined" === typeof b.defaultView ? b.parentWindow : b.defaultView;
        for(c = 0;c < f.frames.length;c++) {
            try {
                f.frames[c].frameElement.setAttribute("data-usid", c)
            }catch(y) {
            }
        }
        var c = b.documentElement.outerHTML || (new XMLSerializer).serializeToString(b.documentElement), w = k, w = k, n = w = "";
        if(c === e) {
            g = k
        }else {
            if(g = c.match(/<iframe.+?>.*?<\/iframe>/ig), g === k) {
                g = k
            }else {
                for(var j = [], n = [], x = "", x = "", w = 0;w < g.length;w++) {
                    x = g[w], x = x.match(/data-usid=["|'](.+?)["|']/i), x === k ? (j.push(g[w]), n.push("usblank")) : (x = parseInt(x[1], 10), j.push(g[w]), n.push(f.frames[x]))
                }
                g = {ud:j, td:n}
            }
        }
        if(g !== k) {
            f = g.ud;
            g = g.td;
            for(j = 0;j < g.length;j++) {
                "undefined" !== typeof f[j] && (n = f[j], "usblank" === g[j] ? (n = n.match(/(src=").+?(")/) !== k ? n.replace(/(.+?src=").+?(".+?)/i, "$1about:usblank$2") : n.match(/(src=').+?(')/) !== k ? n.replace(/(.+?src=').+?('.+?)/i, "$1about:usblank$2") : n.replace(/(.+?src=).+?(\s.+?)/i, "$1about:usblank$2"), c = c.replace(f[j], n)) : (w = g[j].document, w = encodeURIComponent(ob(a, w)), w = '<usframe type="text/javascript" id="usiftag_' + a.$a + '">{"data":"' + w + '", "id": "usiftag_' + a.$a + '", "url": "' +
                    g[j].location.href + '"}</usframe>', n = n.match(/(src=").+?(")/) !== k ? n.replace(/(.+?src=").+?(".+?)/i, "$1about:blank$2") : n.match(/(src=').+?(')/) !== k ? n.replace(/(.+?src=').+?('.+?)/i, "$1about:blank$2") : n.replace(/(.+?src=).+?(\s.+?)/i, "$1about:blank$2"), c = c.replace(f[j], n + w), a.$a++))
            }
        }
        f = b.getElementsByTagName("canvas");
        g = "";
        for(j = 0;j < f.length;j++) {
            if(!u()) {
                try {
                    g += '<uscanvas data-id="' + j + '">' + f[j].toDataURL() + "</uscanvas>"
                }catch(ea) {
                }
            }
        }
        c += g;
        f = b.styleSheets;
        j = g = "";
        for(n = 0;n < f.length;n++) {
            if((f[n].href === k || "" === f[n].href) && !u() && f[n].cssRules !== k) {
                j = [];
                for(w = 0;w < f[n].cssRules.length;w++) {
                    j.push(f[n].cssRules[w].cssText)
                }
                g += j.join("\n")
            }
        }
        return d + (c + ("<usstyle>" + g + "</usstyle>"))
    }
    var pb = {highlight:Ya, blackout:Xa, note:$a, pen:cb, arrow:eb}, V = {fc:l, ua:"none", gc:l, lb:k, U:k, n:k, eb:k, Yb:k, C:k, Jd:k, Da:k, Bb:k, Ab:k, zb:k, Gb:k, Cb:k, Lb:k, Ga:[], wa:"normal", pb:l, f:{highlight:k, blackout:k, pen:k, arrow:k, note:k}, Ia:k, M:k};
    function qb() {
        for(var a in pb) {
            pb.hasOwnProperty(a) && (V.f[a] = k)
        }
    }
    function rb(a) {
        V.gc && window._gaq.push(["_trackEvent", "Usersnap", "click", a])
    }
    var X = k, sb = k, tb;
    function ub(a, b) {
        rb("switchtool_" + b);
        V.Ia !== k && V.Ia.disable();
        V.Ia = V.f[b];
        V.Ia.enable()
    }
    function vb(a) {
        a = a || window.event;
        a.preventDefault && a.preventDefault();
        a.returnValue = l
    }
    function wb(a) {
        for(var b = [37, 38, 39, 40, 33, 34, 35, 36, 32], d = b.length;d--;) {
            if(a.keyCode === b[d]) {
                if(I(B(a), "us_report_note_text")) {
                    break
                }
                if(I(B(a), "us_email_text_box")) {
                    break
                }
                if(I(B(a), "us_email")) {
                    break
                }
                vb(a);
                break
            }
        }
    }
    var xb = k;
    function yb() {
        window.scrollTo(xb[0], xb[1])
    }
    var zb = document.onselectstart;
    function Ab(a) {
        if(!V.pb) {
            V.C.D();
            V.C = k;
            V.lb = k;
            V.U = k;
            if("finished" !== a) {
                var b = V.Ga[V.Ga.length - 1];
                b.Xb !== k && Ia(b.Xb);
                b.$b !== k && Ia(b.$b)
            }
            for(var d in V.f) {
                "object" === typeof V.f[d] && V.f[d] !== k && V.f[d].D()
            }
            V.M.D();
            V.Da !== k && V.Da.D();
            V.eb !== k && (V.eb.D(), V.eb = k);
            V.Da = k;
            qb();
            V.Ia = k;
            V.M = k;
            Aa();
            "normal" === V.wa && (window.removeEventListener && window.removeEventListener("DOMMouseScroll", vb, l), window.onmousewheel = document.onmousewheel = k, E(document, "keydown", wb), E(window, "scroll", yb), document.onselectstart = zb);
            if(V.Cb !== k && ("resize" === a || "cancel" === a)) {
                a = {type:a};
                try {
                    V.Cb.apply(window, [a])
                }catch(c) {
                }
            }
        }
    }
    function Bb() {
        V.C !== k && Ab("resize")
    }
    function Cb(a) {
        27 === a.keyCode && V.C !== k && V.Ia !== k && (V.Ia.cancel() || Ab("cancel"))
    }
    function Db(a) {
        sb === h && (85 === a.keyCode && a.ctrlKey === h) && (Eb(), vb(a))
    }
    function Fb() {
        rb("cancel");
        Ab("cancel")
    }
    function Gb(a) {
        var b = {highlight:[], blackout:[], note:[], paths:[], arrows:[]};
        V.f.highlight !== k && (b.highlight = Za());
        if(V.f.blackout !== k) {
            for(var d = V.f.blackout, c = [], f = na(), g = oa(), j = Math.round, n = 0;n < d.ra.length;n++) {
                d.ra[n] !== e && c.push([[j(d.ra[n][0][0] - f), j(d.ra[n][0][1] - g)], [j(d.ra[n][1][0] - f), j(d.ra[n][1][1] - g)]])
            }
            b.blackout = c
        }
        V.f.note !== k && (b.note = ab());
        V.f.pen !== k && (b.paths = db());
        V.f.arrow !== k && (b.arrows = gb());
        d = 0;
        0 === b.highlight.length && (d = 1);
        c = new Da({na:{jd:function() {
        }, scope:this}, url:p.xb});
        V.Ga[V.Ga.length - 1].$b = c.Ra;
        a = {addInfo:k, email:a.wc, comment:a.tb};
        tb !== e && (a.addInfo = tb);
        if(V.Bb !== k) {
            try {
                V.Bb.apply(window, [a])
            }catch(y) {
            }
        }
        a.addInfo === k && (a.addInfo = e);
        R(c, "api", X);
        a.addInfo !== e && R(c, "additionalinfo", r.stringify(a.addInfo));
        a.email !== e && R(c, "senderemail", a.email);
        a.comment !== e && R(c, "sendercomment", a.comment);
        R(c, "maskid", d);
        R(c, "sessionid", V.lb);
        R(c, "imgdata", r.stringify(b));
        V.pb = h;
        S.push(c);
        Ha();
        V.eb = new U("progress", q.pd, function() {
            if(V.zb !== k) {
                try {
                    V.zb.apply(window, [V.lb])
                }catch(a) {
                }
            }
            V.pb = l;
            Ab("finished")
        });
        V.eb.show()
    }
    function Hb(a) {
        if(!V.pb) {
            rb("send");
            var b = [], b = [], b = [], b = [], d = l;
            V.f.highlight !== k && (b = Za(), 0 < b.length && (d = h));
            V.f.note !== k && (b = ab(), 0 < b.length && (d = h));
            V.f.pen !== k && (b = db(), 0 < b.length && (d = h));
            V.f.arrow !== k && (b = gb(), 0 < b.length && (d = h));
            b = k;
            a.tb !== e && "" !== a.tb && (d = h);
            d ? Gb(a) : (b = new U("alert", q.od), b.show())
        }
    }
    function Ib(a) {
        if(V.Gb !== k) {
            try {
                V.Gb.apply(window, [a])
            }catch(b) {
            }
        }
    }
    function Jb() {
        if(X === e || "" === X || "YOUR-API-KEY-HERE" === X.toUpperCase()) {
            return"button" === V.ua && window.open(p.sb + "?apikey=", "_blank"), Ib("Invalid API Key"), l
        }
        var a = window.location.href;
        return 0 <= a.indexOf("http://localhost") || 0 <= a.indexOf("https://localhost") ? ("button" === V.ua && window.open(p.sb + "?error=localdevelopment", "_blank"), Ib("Local Development not supported"), l) : h
    }
    function Kb() {
        if(Jb() && V.C === k) {
            if("button" === V.ua && V.Ab !== k) {
                try {
                    V.Ab.apply(window)
                }catch(a) {
                }
            }
            Aa();
            var b = V, d = new nb;
            d.$a = 0;
            d = {data:ob(d, document) + "<ustfc>" + d.$a + "</ustfc>", width:fa(), height:ka()};
            if(ba()) {
                var c = document.defaultView.getComputedStyle(document.documentElement, k), f = {};
                f.zoomLevel = window.detectZoom.zoom();
                f.contentWidth = parseInt(c.width.replace(/px/, ""), 10);
                f.contentHeight = parseInt(c.height.replace(/px/, ""), 10);
                f.orientation = 90 === window.orientation || -90 === window.orientation ? "L" : "P";
                f.deviceWidth = window.screen.width;
                f.deviceHeight = window.screen.height;
                f.retina = 1 < window.devicePixelRatio;
                d.Nc = f
            }
            b.U = d;
            "normal" === V.wa && (window.addEventListener && window.addEventListener("DOMMouseScroll", vb, l), window.onmousewheel = document.onmousewheel = vb, F(document, "keydown", wb), xb = H(), F(window, "scroll", yb), zb = document.onselectstart, document.onselectstart = function() {
                if(u()) {
                    if(!I(B(window.event), "us_report_note_text") && !I(B(window.event), "us_email_text_box") && !I(B(window.event), "us_email") && !I(B(window.event), "us_add_email") && !I(B(window.event), "us_add_comment")) {
                        return l
                    }
                }else {
                    return l
                }
            });
            V.Ga.push({Xb:k, $b:k});
            "normal" === V.wa ? V.C = new La({width:V.U.width, height:V.U.height, Ma:Hb, Y:Fb, fb:function(a) {
                V.Da.X || "close" === a ? V.Da.pa() : (rb("helpwindow"), V.Da.show())
            }, hb:pb, Gc:ub, Ua:V.I.Ua, cb:V.I.cb, Tb:V.I.Ub, xc:V.I.hd, Ta:V.I.Ta, bb:V.I.bb, vc:V.I.gd, f:V.I.f, ca:V.I.ca, oa:V.I.oa}) : "mobile" === V.wa && (V.C = new Qa({width:V.U.width, height:V.U.height, Ma:Hb, Y:Fb, Ua:V.I.Ua, cb:V.I.cb, Tb:V.I.Ub, Ta:V.I.Ta, bb:V.I.bb}));
            V.C.show();
            V.M = new Ua({width:V.U.width, height:V.U.height, rd:V.C.Wb("highlight") ? 0 : 1});
            b = V.M;
            b.la = document.createElement("div");
            b.la.setAttribute("id", "us_report_overlay");
            b.la.setAttribute("unselectable", "on");
            1 === b.Qc && D(b.la, "lighter");
            d = ca(b.t.width, b.t.height);
            b.t.width = d.width;
            b.t.height = d.height;
            C(b.la, "width: " + b.t.width + "px; height: " + b.t.height + "px;");
            document.body.appendChild(b.la);
            b.sa = h;
            Wa(b, []);
            for(var g in V.f) {
                V.C.Wb(g) && (V.f[g] = new pb[g]({M:V.M, width:V.U.width, height:V.U.height}))
            }
            g = V.M;
            g.sa && (g.ta = document.createElement("div"), g.ta.setAttribute("id", "us_report_cursor_layer"), g.ta.setAttribute("unselectable", "on"), C(g.ta, "width: " + g.t.width + "px; height: " + g.t.height + "px;"), document.body.appendChild(g.ta));
            V.C.start();
            K.jb({url:p.yb, Va:{api:X, url:window.location.href, jsVersion:p.vb}, na:function(a, b) {
                var c;
                if(b) {
                    if(a && 1 !== a.RC) {
                        c = a.message;
                        var d = a.RC;
                        O("Error " + d + " message: " + c);
                        Ab("error");
                        if("" !== c) {
                            V.n !== k && (V.n.Hb = h);
                            var f = "";
                            -1 === d ? f = c + '<br/><a target="_blank" href="' + p.sb + "?apikey=" + X + '">' + q.Rb + "</a>" : -2 === d && (f = c + '<br/><a target="_blank" href="' + p.sb + '?error=localdevelopment">' + q.Rb + "</a>");
                            Ib(c);
                            c = new U("info", f);
                            c.show();
                            return
                        }
                    }
                    V.Yb = a.permissions;
                    V.Yb.googleAnalytics === h && "undefined" !== typeof window._gaq && (V.gc = h);
                    rb("open");
                    V.C.uc(V.Yb);
                    V.C.enable();
                    "normal" === V.wa && (V.C.Vb(), V.Da = new Sa({f:T, bd:V.C.Vb()}), c = V.Da, c.X || "true" !== ma("usersnap_hide_help") && c.show());
                    V.lb = a.sessionkey;
                    c = new Da({na:{jd:function() {
                    }, scope:this}, url:p.wb});
                    V.Ga[V.Ga.length - 1].Xb = c.Ra;
                    R(c, "api", X);
                    R(c, "sessionid", V.lb);
                    R(c, "html", V.U.data);
                    "undefined" !== typeof V.U.Nc && R(c, "viewportData", r.stringify(V.U.Nc));
                    d = da();
                    R(c, "vpleft", na());
                    R(c, "vptop", oa());
                    R(c, "vpwidth", d.width);
                    R(c, "vpheight", d.height);
                    R(c, "location", window.location.href);
                    S.push(c);
                    Ha()
                }else {
                    O("Invalid API Key"), Ab("error"), c = q.ld, V.n !== k && (V.n.Hb = h), Ib("Invalid API Key"), c = new U("info", c), c.show()
                }
            }, scope:this})
        }
    }
    var Lb = {};
    Lb.jsVersion = p.vb;
    Lb.cssVersion = p.ub;
    p.Ja || (window.UserSnap.setLanguage = Mb, window.UserSnap.setEmailBox = Nb, window.UserSnap.toggleButton = Ob, window.UserSnap.forceReportOpen = Pb, window.UserSnap.openReportWindow = Eb, window.UserSnap.getInstance = Qb, window.UserSnap.version = Lb);
    function Mb(a, b) {
        var d = {en:"en", fr:"fr", es:"es", pl:"pl", fa:"fa", de:"de-formal", "de-formal":"de-formal", "de-informal":"de-informal", it:"it", jp:"jp", ko:"ko", hu:"hu", da:"da", sk:"sk", cz:"cz", no:"no", nl:"nl", fi:"fi", pt:"pt", tr:"tr", ru:"ru"};
        if("undefined" !== typeof d[a]) {
            V.C !== k && Ab("restart");
            V.n !== k && V.n.D();
            Cb !== k && E(document, "keyup", Cb);
            Db !== k && E(document, "keydown", Db);
            Bb !== k && "normal" === V.wa && E(window, "resize", Bb);
            var c = document.createElement("script");
            c.type = "text/javascript";
            c.async = h;
            c.src = p.gb + "js/" + p.vb + "/usersnap-" + p.vb + p.dd + "-" + d[a] + ".js";
            d = document.getElementsByTagName("head")[0];
            if("function" === typeof b) {
                var f = function() {
                    if(A() || v()) {
                        if("loaded" !== c.readyState) {
                            return
                        }
                        E(c, "readystatechange", f)
                    }else {
                        E(c, "load", f)
                    }
                    try {
                        b()
                    }catch(a) {
                        O("failed to execute callback fn")
                    }
                };
                A() || v() ? F(c, "readystatechange", f) : F(c, "load", f)
            }
            d.appendChild(c)
        }
    }
    function Nb(a) {
        V.C !== k ? V.C.Ec(a) : V.I.Ub = a
    }
    function Ob(a) {
        "button" === V.ua && (a === h ? V.n.show() : a === l ? V.n.pa() : V.n.X ? V.n.pa() : V.n.show())
    }
    function Pb() {
        Eb()
    }
    function Eb() {
        "none" === V.ua ? (Ca("Nothing configured yet"), Ib("Nothing configured yet")) : Kb()
    }
    if("undefined" !== typeof window._usersnapconfig) {
        var Z = window._usersnapconfig, $ = {Vc:Z.apiKey, ca:Z.valign, oa:Z.halign, lang:Z.lang, f:Z.tools, cd:Z.btnText, Ta:Z.commentRequired, bb:Z.commentBox, gd:Z.commentBoxPlaceholder, Ua:Z.emailRequired, cb:Z.emailBox, hd:Z.emailBoxPlaceholder, Ub:Z.emailBoxValue, tc:Z.beforeSend, sc:Z.beforeOpen, mc:Z.afterSend, Y:Z.cancelHandler, yc:Z.errorHandler, Cc:Z.loadHandler, mode:Z.mode, Fc:Z.shortcut, lc:Z.addinfo};
        "undefined" !== typeof Z.configurator && (p.yb = p.yb.replace(/\/report\//, "/configurator/"), p.cc = p.cc.replace(/\/report\//, "/configurator/"), p.wb = p.wb.replace(/\/report\//, "/configurator/"), p.xb = p.xb.replace(/\/report\//, "/configurator/"), p.bc = p.bc.replace(/\/report\//, "/configurator/"));
        a: {
            if("none" !== V.ua) {
                Ca("An instance was already initialized!")
            }else {
                "function" === typeof $.yc && (V.Gb = $.yc);
                "function" === typeof $.Y && (V.Cb = $.Y);
                "function" === typeof $.tc && (V.Bb = $.tc);
                "function" === typeof $.sc && (V.Ab = $.sc);
                "function" === typeof $.mc && (V.zb = $.mc);
                "function" === typeof $.Cc && (V.Lb = $.Cc);
                if(ba()) {
                    if(/iPhone|iPad|iPod/i.test(navigator.userAgent || navigator.vendor || window.opera)) {
                        V.wa = "mobile"
                    }else {
                        Ca("Sorry only iOS devices are currently supported!");
                        Ib("Only iOS as mobile browser supported!");
                        break a
                    }
                }
                (new Image).src = p.gb + "res/" + p.ub + "/images/ussprite.png";
                (new Image).src = p.gb + "res/" + p.ub + "/images/ussprite_v.png";
                X = $.Vc;
                $.lc !== e && (tb = $.lc);
                $.Fc !== e && (sb = $.Fc);
                if(!V.fc) {
                    var Rb = document.createElement("link");
                    Rb.setAttribute("rel", "stylesheet");
                    Rb.setAttribute("type", "text/css");
                    p.Ja ? Rb.setAttribute("href", p.gb + "css/usersnap.css?dc=" + (new Date).getTime()) : Rb.setAttribute("href", p.gb + "res/" + p.ub + "/css/usersnap.css");
                    document.getElementsByTagName("head")[0].appendChild(Rb);
                    V.fc = h
                }
                qb();
                F(document, "keyup", Cb);
                F(document, "keydown", Db);
                "normal" === V.wa && F(window, "resize", Bb);
                "report" === $.mode ? (V.ua = "report", V.I = $) : (V.ua = "button", V.I = $, V.n = new Ja({ca:$.ca, oa:$.oa, kd:Kb, text:$.cd, ya:$}));
                if(V.Lb !== k) {
                    try {
                        V.Lb.apply(window)
                    }catch(Sb) {
                    }
                }
            }
        }
    }
    function Qb() {
        return V
    }
    ;
})();

