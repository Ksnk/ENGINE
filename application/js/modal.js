/* нагло потырено из twitter bootstrap и безжалостно почикано
 * ====================== */

var Modal = function (element, options) {
    this.options = options;
    this.$element = $(element);
};

Modal.prototype = {

    constructor:Modal, show:function () {
        if (this.isShown) return;

        this.isShown = true;

        var that = this;

        this.backdrop(function () {

            if (!that.$element.parent().length) {
                that.$element.appendTo(document.body); //don't move modals dom position
            } else {
                that.no_remove=true;
            }
            if($.fn._4state)
                $('._states',that.$element)._4state();

            that.options.oninit && that.options.oninit.call(that.$element);
            that.$element.show();

            //           if (transition) {
            //               that.$element[0].offsetWidth ;// force reflow
            //           }

            that.$element.focus();

            //var that = this  ;
            $(document).on('focusin.modal', function (e) {
                if (that.$element[0] !== e.target && !that.$element.has(e.target).length) {
                    that.$element.focus()
                }
            });

        })
    }, hide:function (ok) {
        if (!this.isShown) return;

        this.isShown = false;

        this.$element.hide();

        this.backdrop();
        this.options.onclose && this.options.onclose.call(this.$element,ok);
        if(!this.no_remove)
            this.$element.remove();

    }, backdrop:function (callback) {

        if (this.isShown) {

            this.$backdrop = $('<div class="backdrop fade_in' + '" />')
                .appendTo(document.body);
            callback()

        } else if (!this.isShown && this.$backdrop) {
            this.$backdrop.remove();
            this.$backdrop = null
        } else if (callback) {
            callback();
        }
    }
};

