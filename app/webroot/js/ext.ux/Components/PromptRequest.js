//<script>

Ext.define('Ext.ux.PromptRequest', {
    alternateClassName: 'PromptRequest',
    statics: {
        form: function(config) {

            var form = new Ext.form.Panel({
                frame: true,
                url: config.url,
                baseParams: Ext.isFunction(config.baseParams) ? config.baseParams() : config.baseParams,
                pollForChanges: true,
                items: config.items,
                buttons: [
                    {
                        formBind: true,
                        text: 'Save',
                        handler: function() {

                            if(Ext.isFunction(config.onBeforeSubmit)) {
                                config.onBeforeSubmit(form);
                            }

                            form.getForm().submit({
                                waitMsg: 'Saving',
                                success: function(form, action) {

                                    if(action.result && action.result.msg) {
                                        Ext.MessageBox.show({
                                            title:'Message',
                                            msg: action.result.msg,
                                            buttons: Ext.MessageBox.OK,
                                            fn: function() {
                                                if(Ext.isFunction(config.onSubmit)) {
                                                    config.onSubmit(form, action.result);
                                                }
                                                win.close();
                                            }
                                        });
                                    } else {
                                        if(Ext.isFunction(config.onSubmit)) {
                                            config.onSubmit(form, action.result);
                                        }
                                        win.close();
                                    }
                                },
                                failure: function() {
                                    Ext.Msg.alert('Greška', 'An error occured while saving');
                                }
                            });
                        }
                    },
                    {
                        text: 'Cancel',
                        handler: function() {
                            win.close();
                        }
                    }
                ]
            });

            var win = ApplicationInstance.getDesktop().createWindow({
                title: config.title || 'Prompt Form',
                width: config.windowWidth || 600,
                iconCls: config.winCls,
                layout: 'fit',
                height: config.windowHeight || 300,
                items: [form]
            });

            win.show();
        }
    }
});

//</script>