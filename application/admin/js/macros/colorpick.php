<% POINT::start('plugins_html') %>
<div style="display:none;" id="colorpicker">
    <canvas class="rule"></canvas>
    <canvas class="pane"></canvas>
    <div></div>
    <button>ok</button>
</div>
<% POINT::finish() %>

<style type="text/css">
    <% POINT::start('css_body') %>
    #colorpicker {
        position: absolute;
        top: 0;
        left: 0;
        z-index: 1060;
        width: 160px;
        height: 100px;
        overflow: hidden;
        background: lightgray;
    }

    #colorpicker .rule {
        float: left;
        width: 20px;
        height: 90px;
        margin: 5px 1px 5px 5px;
    }
    #colorpicker .pane {
        float: left;
        width: 90px;
        height: 90px;
        margin: 5px 1px 5px 5px;
    }
    <% POINT::finish() %>
</style>

<script type="javascript/text">
<% POINT::start('js_body') %>

function Color(a, type) {
    this.type = type;
    this.color = a;
}
Color.prototype.changeHue = function (Hue) {
    if (this.type != 'hsv'){
        this.color = this.rgb2hsv(this.color);
        this.type='hsv';
    }
    this.color[0] = Hue;
}
Color.prototype.changeBrightness= function (Br) {
    if (this.type != 'hsv'){
        this.color = this.rgb2hsv(this.color);
        this.type='hsv';
    }
    this.color[2] = Br;
}
Color.prototype.toHexStr = function () {
    function hex(c) {
        c = parseInt(c).toString(16);
        return c.length < 2 ? "0" + c : c;
    }

    var rgb = this.color;
    if (this.type == 'hsv')
        rgb = this.hsv2rgb(rgb);

    return ("#" + hex(rgb[0]) + hex(rgb[1]) + hex(rgb[2])).toUpperCase();
}
Color.prototype.toRgbStr =function(){
    var rgb = this.color;
    if (this.type == 'hsv')
        rgb = this.hsv2rgb(rgb);

    return "rgb(" + Math.ceil(rgb[0])+',' +Math.ceil(rgb[1])+','+Math.ceil(rgb[2])+')';
}
Color.prototype.getWebSafeColor = function (color) {
    var rMod = color.r % 51;
    var gMod = color.g % 51;
    var bMod = color.b % 51;

    if ((rMod == 0) && (gMod == 0) && (bMod == 0)) return color;

    var wsColor = {};

    wsColor.r = (rMod <= 25 ? Math.floor(color.r / 51) * 51 : Math.ceil(color.r / 51) * 51);
    wsColor.g = (gMod <= 25 ? Math.floor(color.g / 51) * 51 : Math.ceil(color.g / 51) * 51);
    wsColor.b = (bMod <= 25 ? Math.floor(color.b / 51) * 51 : Math.ceil(color.b / 51) * 51);

    return wsColor;
};

Color.prototype.rgb2hsv = function (rgb) {
    var r, g, b, h, s, min, max;
    r = rgb[0];
    g = rgb[1];
    b = rgb[2];

    min=Math.min(r,g,b);
    max=Math.max(r,g,b);

    if(max==min){
        h=0
    } else if ((max==r)&& (g>=b)){
        h=60*(g-b)/(max-min)
    } else if ((max==r)&& (g<b)){
        h=360+60*(g-b)/(max-min)
    } else if (max==g){
        h=120+60*(b-r)/(max-min)
    } else if (max==b){
        h=240+60*(r-g)/(max-min)
    }
    if(max==0){
        s=0
    } else {
        s=1-min/max
    }

    return [h,s*100,max*100/256];
};
Color.prototype.hsv2rgb = function (hsv) {
    var h = hsv[0],s = hsv[1],v = hsv[2];

    var hi=Math.round((h-h%60)/60), vmin=(100-s)*v/100,alpha=(v-vmin)*(h % 60)/60,
        vinc=vmin+alpha,vdec=v-alpha,res;
    if(hi==0 || hi==6)
        res= [v,vinc,vmin];
    else if (hi==1)
        res= [vdec,v,vmin];
    else if (hi==2)
        res= [vmin,v,vinc];
    else if (hi==3)
        res= [vmin,vdec,v];
    else if (hi==4)
        res= [vinc,vmin,v];
    else if (hi==5)
        res= [v,vmin,vdec];

    return [2.56* res[0], 2.56* res[1], 2.56* res[2]];
};

Color.prototype.parseColor = function (colorText) {
    var sType = typeof(colorText);
    if (sType == "string") {
        if (/^\#?[0-9A-F]{6}$/i.test(colorText)) {
            return {
                r:eval('0x' + colorText.substr(colorText.length == 6 ? 0 : 1, 2)),
                g:eval('0x' + colorText.substr(colorText.length == 6 ? 2 : 3, 2)),
                b:eval('0x' + colorText.substr(colorText.length == 6 ? 4 : 5, 2)),
                a:255
            };
        }
    } else if (sType == "object") {
        if (colorText.hasOwnProperty("r") &&
            colorText.hasOwnProperty("g") &&
            colorText.hasOwnProperty("b")) {
            return colorText;
        }
    }
    return null;

};

/**
 * колор пикер - окошко, которое появляется под-над элементом при клике на него, прижато к правому краю элемента.
 */
$.fn.colorPicker = function (o) {
    var testcanvas = $("<canvas></canvas>");
    if (!testcanvas[0].getContext) {
        this.addClass('disabled');
        return this;
    }
    delete testcanvas;

    this.click(
        function (event) {
            ColorPicker.initialize({
                parent:this,
                inSpeed:'slow',
                outSpeed:'slow'
            }, event.pageX, event.pageY)
        }
    );
};

var ColorPicker = {
    initialize:function (options, X, Y) {
        //if this is a color us it if not try to make a color out of it.
        var _menu = $('#colorpicker')[0];
// Show the menu
        if (!_menu.show_menu) {
            this.HueBar = $('.rule', _menu)[0];
            this.SVBox = $('.pane', _menu)[0];
            this.drawHueBar();
            //Draw the SVBox
            this.drawSVBox();

            menu(_menu, {
                show:function () {
                    if (options.show)options.show.call();
                    options._displayed = true;

                    $(this).fadeIn(options.inSpeed)
                },
                hide:function () {
                    if (options.hide)options.hide.call();
                    options._displayed = false;

                    if (options._xmenu) {
                        $(options._xmenu).remove();
                        options._xmenu = false;
                    }
                    $(this).fadeOut(options.outSpeed)
                }
            });
            /*    $('A',_menu).mouseover( function() {
        $(_menu).find('LI.hover').removeClass('hover');
        $(this).parents('LI').addClass('hover');
    }).click(function(event){
            _menu.hide_menu() ;
            if( options._mode=='select'){
                if (options.set_text){
                    options.set_text.call(tgtElement,$(this).attr('href').substr(1))
                }
            } else if( options.action ) {
                options.action.call(tgtElement,
                    $(this).attr('href').substr(1),
                    event
                )
            }
            return false;
        });    */
        }
        $(_menu).css({ top:Y, left:X });
        _menu.show_menu();

    },
    CurColor:[0,99,99],
    //Iterates through all 360 hues and creates a 1px by 30px div for each hue.
    drawHueBar:function () {
        //get the multiplyer for the hue range based on the height of the hue bar.
        var hSteps = 360 / this.HueBar.height;
        var hColor = new Color([255, 0, 0], 'rgb');
        var myCTX = this.HueBar.getContext('2d');
        for (var hi = this.HueBar.height; hi > 0; hi--) {
            hColor.changeHue(hi * hSteps);
            myCTX.fillStyle = hColor.toRgbStr();
            myCTX.fillRect(0, this.HueBar.height - hi, this.HueBar.width, 1);
        }
    },
    //Draw a SV box that is as tall as the HUE Bar.
    drawSVBox:function (objsvDiv) {
        //Get the value multiplyer for the number of steps over the height of the SVBox
        var width=this.SVBox.width, height=this.SVBox.height;
        //Create a new color for calculating each rows color ranges.
        var svColor = new Color([this.CurColor[0], 99, 99], "hsv");
        //Get a 2d context to the SVBox canvas element.
        var myCTX = this.SVBox.getContext('2d');
        //Iterate over the hieght of the SVBox.
        for (var vi = height; vi > 0; vi--) {
            //Set the brightness for this row
            svColor.changeBrightness(vi * 100/height);
            //Get the hex string for the current brightness.
            //Create a new linear Gradient from the canvas context that goes
            //from the left to the right.
            var myLinearGrad = myCTX.createLinearGradient(0, 0, width, 1);
            //Add a color stop to the gradient that is the current
            //bright ness no need to to the HSV conversion here
            var x =Math.ceil(vi * 255/height);
            myLinearGrad.addColorStop(0, "rgb(" + x + "," + x + "," + x + ")");
            //Add a color stop that is based on the current Hue
            myLinearGrad.addColorStop(1, svColor.toRgbStr());
            //Set the canvas context's fill style to our current gradient.
            myCTX.fillStyle = myLinearGrad;
            //Fill the row at 1px high.
            myCTX.fillRect(0, height - vi, width, 1);
        }
    },
    //Update the pixels in the SVBox when the hue is clicked.
    UpdateSVBox:function () {
        if (this.supportsCanvas) {
            vSteps = 255 / this.SVBox.height;
            svSteps = 100 / this.SVBox.height;
            svColor = new Color([this.CurColor.hsv[0], 100, 100], "hsv");
            myCTX = this.SVBox.getContext('2d');
            strsvHex = "";
            for (vi = this.SVBox.height; vi > 0; vi--) {
                svColor.changeBrightness(vi * svSteps);
                strsvHex = svColor.rgbToHex();
                myLinearGrad = myCTX.createLinearGradient(0, 0, this.SVBox.width, 0);
                myLinearGrad.addColorStop(0, "rgb(" + vi + "," + vi + "," + vi + ")");
                myLinearGrad.addColorStop(1, strsvHex);
                myCTX.fillStyle = myLinearGrad;
                myCTX.fillRect(0, this.SVBox.height - vi, this.SVBox.height, 1);
            }
        } else {
            //Get the child rows in the SVBox
            arSVRows = this.SVBox.getChildren();
            //set the svSize to the number of rows.
            svSize = arSVRows.length;
            //Get the multiple required to go from 0 to 100 is svSize steps.
            svStep = 100 / svSize;
            //Create a color for calculating the HSV of each pixel.
            svColor = new Color([this.CurColor[0], this.CurColor[1], this.CurColor[2]]);

            //Loop over all of the rows.
            for (vi = svSize - 1; vi >= 0; vi -= 1) {
                //set the brightness for this row.
                svColor.changeBrightness(vi * svStep);
                //Get the children of the row.
                siChildren = arSVRows[svSize - vi - 1].getChildren();
                //Iterate the children of this row.
                for (si = 0; si < siChildren.length; si++) {
                    //Change the saturation for each pixe.
                    svColor.changeSaturation(si * svStep);
                    siChildren[si].setStyle("background-color", svColor);
                }
            }
        }

    },
    setCurrentHue:function (e) {
        //IE uses srcElement instead of target to specify the
        //element that was clicked.
        if (!e.target)
            e.target = e.srcElement;

        if (this.supportsCanvas) {
            //Get a 2d context to the SVBox canvas element.
            myCTX = this.HueBar.getContext('2d');
            //Get the coordinates for our hue bar.
            hBoxCoords = this.HueBar.getCoordinates();
            //subtract the left and top of the hue bar from the event.clentX and y then add the window.scrollX and Y
            // to get the click position in the Hue bar and pass those in to the contexts getImageData function.
            myImageData = myCTX.getImageData(e.clientX - hBoxCoords.left + window.scrollX, e.clientY - hBoxCoords.top + window.scrollY, 1, 1);
            //Create a hue color based of the ImageData returned by the getImageData function.
            CurHueColor = new Color([myImageData.data[0], myImageData.data[1], myImageData.data[2]]);
        } else {
            //Create a color object from the background of the target so we can
            //get its Hue.
            CurHueColor = new Color(e.target.getStyle("background-color"));
        }
        //Set the Hue of the current color.
        this.CurColor.changeHue(CurHueColor.hsv[0]);
        //Tell the SVBox to update.
        this.UpdateSVBox();
        //Set the selected color to the current color.
        this.SelectedColor.setStyle("background-color", this.CurColor);
        //Update the hsv and rgb text boxes.
        this.hInput.value = this.CurColor.hsv[0];
        this.sInput.value = this.CurColor.hsv[1];
        this.vInput.value = this.CurColor.hsv[2];
        this.rInput.value = this.CurColor[0];
        this.gInput.value = this.CurColor[1];
        this.bInput.value = this.CurColor[2];
    },
    setPreviewColor:function (e) {
        //IE uses srcElement instead of target to specify the
        //element that was clicked.
        if (!e.target)
            e.target = e.srcElement;
        if (this.supportsCanvas) {
            myCTX = this.SVBox.getContext('2d');
            SVBoxCoords = this.SVBox.getCoordinates();

            myImageData = myCTX.getImageData(e.clientX - SVBoxCoords.left + window.scrollX, e.clientY - SVBoxCoords.top + window.scrollY, 1, 1);
            nColor = new Color([myImageData.data[0], myImageData.data[1], myImageData.data[2]]);
            this.PreviewColor.setStyle("background-color", nColor);
        } else {
            nColor = new Color(e.target.getStyle("background-color"));
            this.PreviewColor.setStyle("background-color", nColor);
        }
    },

    setSelectedColor:function (e) {
        //IE uses srcElement instead of target to specify the
        //element that was clicked.
        if (!e.target)
            e.target = e.srcElement;
        if (this.supportsCanvas) {
            myCTX = this.SVBox.getContext('2d');
            SVBoxCoords = this.SVBox.getCoordinates();

            myImageData = myCTX.getImageData(e.clientX - SVBoxCoords.left + window.scrollX, e.clientY - SVBoxCoords.top + window.scrollY, 1, 1);
            nColor = new Color([myImageData.data[0], myImageData.data[1], myImageData.data[2]]);
        } else {
            nColor = new Color(e.target.getStyle("background-color"));
        }
        //nColor = new Color(e.target.getStyle("background-color"));
        this.CurColor = nColor;
        this.SelectedColor.setStyle("background-color", nColor);
        this.hInput.value = nColor.hsv[0];
        this.sInput.value = nColor.hsv[1];
        this.vInput.value = nColor.hsv[2];
        this.rInput.value = nColor[0];
        this.gInput.value = nColor[1];
        this.bInput.value = nColor[2];

    },
    setRGB:function () {
        nc = new Color([this.rInput.value, this.gInput.value, this.bInput.value]);
        this.CurColor = nc;
        this.hInput.value = nc.hsv[0];
        this.sInput.value = nc.hsv[1];
        this.vInput.value = nc.hsv[2];
        this.SelectedColor.setStyle("background-color", nc);
        this.PreviewColor.setStyle("background-color", nc);
        this.UpdateSVBox();
    },
    setHSL:function () {
        nc = new Color([this.hInput.value, this.sInput.value, this.vInput.value], 'hsv');
        this.CurColor = nc;
        this.rInput.value = nc[0];
        this.gInput.value = nc[1];
        this.bInput.value = nc[2];
        this.SelectedColor.setStyle("background-color", nc);
        this.PreviewColor.setStyle("background-color", nc);
        this.UpdateSVBox();
    },
    //When the ok button is clicked call this.OKCallBack();
    handleOK:function () {
        if (this.OKCallback != null)
            this.OKCallback();
    },
    //When the ok button is clicked call this.CancelCallback();
    handleCancel:function () {
        if (this.CancelCallback != null)
            this.CancelCallback();
    }

};
<% POINT::finish() %>

</script>