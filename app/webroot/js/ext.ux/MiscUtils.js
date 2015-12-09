
Ext.define('Ext.ux.MiscUtils', {
    alternateClassName: 'MiscUtils',
    statics: {
        clone: function(o) {
            if (!o || 'object' !== typeof o) {
                return o;
            }
            if ('function' === typeof o.clone) {
                return o.clone();
            }
            var c = '[object Array]' === Object.prototype.toString.call(o) ? [] : {};
            var p, v;
            for (p in o) {
                if (o.hasOwnProperty(p)) {
                    v = o[p];
                    if (v && 'object' === typeof v) {
                        c[p] = Ext.ux.maidea.Util.clone(v);
                    }
                    else {
                        c[p] = v;
                    }
                }
            }
            return c;
        },
        exists: function (namespace) {
           var tokens = namespace.split('.');
           return tokens.reduce(function(prev, curr) {
              return (typeof prev == "undefined") ? prev : prev[curr];
           }, window);
        },
        randomInt : function(min, max){
            return Math.floor(Math.random() * (max - min + 1)) + min;
        },
        //open window with post data
        postWindowOpen: function(url, postdata) {
            
            var randomId = 'postWindow-' + MiscUtils.randomInt(1, 100000);

            var hiddenForm = new Ext.FormPanel({
                id: randomId,
                standardSubmit: true,
                url: url,
                hidden: true,
                renderTo: Ext.getBody()
            });
            
            Ext.each(postdata, function(item, index) {
                for (i in item) {
                    hiddenForm.add({
                        xtype: 'hidden',
                        name: i,
                        value: item[i]
                    });
                }
            });
            hiddenForm.submit({
                clientValidation: false,
                target: '_blank',
                success: function() {Ext.defer(hiddenForm.destroy, 10, hiddenForm);},
                failure: function() {Ext.defer(hiddenForm.destroy, 10, hiddenForm);}
            });
        },
        
        
        extractDate: function(date) {
            return new Date(date.getFullYear(), date.getMonth(), date.getDate());
        },
        
        
        
        dataURItoBlob: function (dataURI) {
            var byteString;
            var mimestring; 
            if(dataURI.split(',')[0].indexOf('base64') !== -1 ) {
                byteString = atob(dataURI.split(',')[1]);
            } else {
                byteString = decodeURI(dataURI.split(',')[1]);
            }

            mimestring = dataURI.split(',')[0].split(':')[1].split(';')[0];

            var content = new Array();
            for (var i = 0; i < byteString.length; i++) {
                content[i] = byteString.charCodeAt(i);
            }
            return new Blob([new Uint8Array(content)], {type: mimestring});
        }
    }
});
