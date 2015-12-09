
Ext.define('Ext.ux.ExtUtils', {
    alternateClassName: 'ExtUtils',
    statics: {
        isSubclassOf: function(cls, superCls) {

            if (!Ext.isFunction(cls) || !Ext.isFunction(superCls)) {
                return false;
            }

            var p = cls;

            do {

                if (p.superclass === superCls.prototype) {
                    return true;
                }

                p = p.superclass;

            } while (p);

            return false;
        },
        disableLoader: function() {
            Ext.Loader.setConfig({
                enabled: false,
                disableCaching: true
            });
        },
        initSessionHandling: function(warning) {

            Ext.SESSION_WARNING_MS = 300000;//warning before timeout in miliseconds 300000=5 minutes            
            Ext.SESSION_TIMEOUT_MINUTES = 110;//session timeout timer TODO: sync with backend

            Ext.SESSION_EXPIRES_DATE = new Date();
            //Ext.SESSION_EXPIRES_DATE.setHours(Ext.SESSION_EXPIRES_DATE.getHours()+2);
            Ext.SESSION_EXPIRES_DATE.setMinutes(Ext.SESSION_EXPIRES_DATE.getMinutes() + Ext.SESSION_TIMEOUT_MINUTES);

            //** SESSION TIMEOUT handling *******////
            Ext.util.Observable.observeClass(Ext.data.Connection);
            Ext.data.Connection.on('requestcomplete', function(dataconn, response) {

                if (response.request!=null && response.request.options.url == 'messaging/getNewNotifications') {//dont reset session timer
                    var timeout = Ext.SESSION_EXPIRES_DATE.getTime() - Ext.Date.now();
                    if (timeout < (Ext.SESSION_WARNING_MS)) {//show session timeout warning

                        var time = new Date(timeout);

                        Ext.MessageBox.show({
                            title: 'Session Timeout',
                            msg: 'Dugo vremena ste neaktivni, uskoro ćete biti automatski odjavljeni iz Webshop Backenda',
                            progressText: 'Vaša prijava ističe za: ' + time.getMinutes() + ':' + time.getSeconds(),
                            width: 300,
                            progress: true,
                            closable: false,
                            buttons: Ext.Msg.OKCANCEL,
                            fn: function(btnClicked) {
                                if (btnClicked == 'ok') {
                                    Ext.Ajax.request({
                                        url: 'users/extendSession'
                                    });
                                } else {//not ok do logout
                                    document.location = "/users/logout";
                                }
                            }
                        });

                        var progressRefresher = function() {
                            var timeout = Ext.SESSION_EXPIRES_DATE.getTime() - Ext.Date.now();
                            var time = new Date(timeout);
                            if (timeout < 0) {
                                document.location = "/users/logout";
                            } else {
                                //fugly lpad:
                                if (time.getSeconds().toString().length == 1) {
                                    var secondsStr = '0' + time.getSeconds().toString();
                                } else {
                                    var secondsStr = time.getSeconds();
                                }
                                Ext.MessageBox.updateProgress(timeout / Ext.SESSION_WARNING_MS, 'Vaša prijava ističe za: ' + time.getMinutes() + ':' + secondsStr);
                                Ext.Function.defer(progressRefresher, 1000, this);
                            }
                        }
                        progressRefresher();
                    }

                } else {//reset timer "manual" AJAX call
                    Ext.SESSION_EXPIRES_DATE = new Date();
                    //Ext.SESSION_EXPIRES_DATE.setHours(Ext.SESSION_EXPIRES_DATE.getHours()+2);
                    Ext.SESSION_EXPIRES_DATE.setMinutes(Ext.SESSION_EXPIRES_DATE.getMinutes() + Ext.SESSION_TIMEOUT_MINUTES);

                }

                try { //error if loading NON-JSON via ajax (error can be ignored)
                    var res = Ext.decode(response.responseText, false);
                    if (res && res.session_timeout) {
                        alert('Vaša prijava je istekla, molimo prijavite se ponovo ako želite nastaviti korištenje sustava.');
                        document.location = "/";
                        return;
                    }
                } catch (e) {///if response is invalid json ext decode throws syntax error
                    if (e.name == 'SyntaxError') {
                        //alert("<?php echo __('Unknown server error!') ?>\r\n<?php echo __('Data Dump:') ?>\n\r\n\r"+response.responseText);
                    }
                }
            });
            //*****EO session tiemout handling ****//////

        },
        clearCookiesState: function() {
            var cp = new Ext.state.CookieProvider({
                expires: new Date(new Date().getTime() + (1000 * 60 * 60 * 24 * 60))//60 days
            });

            Ext.state.Manager.setProvider(cp);

            for (var item in cp.state) {
                Ext.state.Manager.clear(item);
            }
        },
        getBrowserName: function() {
            var browser = 'unknown';

            var check = function(regex) {
                return regex.test(Ext.userAgent);
            }

            if (check(/trident/) && check(/rv:11\./)) {
                Ext.isIE = true;
                Ext.isIE11 = true;
                Ext.ieVersion = 11;
            }

            if (Ext.ieVersion > 0) {
                if (Ext.isIE7) {
                    browser = "IE7";
                } else if (Ext.isIE8) {
                    browser = "IE8";
                } else if (Ext.isIE9) {
                    browser = "IE9";
                } else {
                    browser = 'IE' + Ext.ieVersion;
                }
            } else if (Ext.chromeVersion > 0) {
                browser = 'Chrome' + Ext.chromeVersion;
            } else if (Ext.firefoxVersion > 0) {
                browser = 'Firefox' + Ext.firefoxVersion;
            } else if (Ext.operaVersion > 0) {
                browser = 'Opera' + Ext.operaVersion;
            } else if (Ext.safariVersion > 0) {
                browser = 'Safari' + Ext.safariVersion;
            }

            return browser;
        },
        getPlatformName: function() {
            var platform = 'unknown';

            if (Ext.isWindows) {
                platform = 'Windows';
            } else if (Ext.isLinux) {
                platform = 'Linux';
            } else if (Ext.isMac) {
                platform = 'Mac';
            }

            return platform;
        },
        //added newline handling
        htmlEncode: function(string) {
            if (Ext.isString(string)) {
                string = Ext.String.htmlEncode(string).replace(/\r?\n/g, "<br/>");
            }

            return string;
        },
        removeHTMLTags: function(htmlString) {
            if (htmlString) {
                var mydiv = document.createElement("div");
                mydiv.innerHTML = htmlString;

                if (document.all) // IE Stuff
                {
                    return mydiv.innerText;
                }
                else // Mozilla does not work with innerText
                {
                    return mydiv.textContent;
                }
            }
            return '';
        },
        simplifyHtml: function(htmlString) {
            if(htmlString) {
                return htmlString.replace(/<(?!br\s*\/?)[^>]+>/g, '');
            }
            return '';
        }
    }
});
