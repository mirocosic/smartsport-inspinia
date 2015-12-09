
Ext.define('Ext.ux.PromptButton', {
    extend: 'Ext.Button',
    alias: 'widget.promptbutton',
    text: 'ButtonText',
    windowName: 'popupPrompt',
    title: 'Window Title',
    windowId: '',
    items: [],
    formUrl: 'placeholder',
    baseParams: {},
    parentId: false, //must have getStore().load()
    parentObject: false, // component must have getStore().load()
    windowWidth: 500,    
    windowHeight:300,
    returnBaseParams: null, //to get base params
    initComponent: function() {
        var me = this;

        var config = {
            handler: function() {

                if(Ext.isFunction(me.onBeforePrompt)) {
                    me.onBeforePrompt(me);
                }

                var form = new Ext.form.Panel({
                    //frame: true,
                    url: me.formUrl,
                    baseParams: me.returnBaseParams ? me.returnBaseParams() : me.baseParams,
                    pollForChanges: true,
                    items: me.items,
                    layout:'anchor',
                    bodyPadding:5,
                    buttons: [
                        {
                            formBind: true,
                            text: 'Spremi',
                            handler: function() {
                                if(Ext.isFunction(me.onBeforeSubmit)) {
                                    me.onBeforeSubmit(me, window);
                                }

                                form.getForm().submit({
                                    waitMsg: 'Spremanje',
                                    success: function(form, action) {

                                        if(action.result && action.result.msg) {
                                            Ext.MessageBox.show({
                                                title:'Poruka',
                                                msg: action.result.msg,
                                                buttons: Ext.MessageBox.OK,
                                                fn: function() {
                                                    if (me.parentId) {
                                                        Ext.getCmp(me.parentId).getStore().load();
                                                    }
                                                    if (me.parentObject) {
                                                        me.parentObject.getStore().load();
                                                    }
                                                    if(Ext.isFunction(me.onSubmit)) {
                                                        me.onSubmit(me, window, action.result);
                                                    }
                                                    window.close();
                                                }
                                            });
                                        } else {
                                            if (me.parentId) {
                                                Ext.getCmp(me.parentId).getStore().load();
                                            }
                                            if (me.parentObject) {
                                                me.parentObject.getStore().load();
                                            }
                                            if(Ext.isFunction(me.onSubmit)) {
                                                me.onSubmit(me, window, action.result);
                                            }
                                            window.close();
                                        }
                                    },
                                    failure: function(form,action) {
                                        if(action.result && action.result.msg){
                                            Ext.Msg.alert('Greška', 'Greška prilikom spremanja:<br>'+action.result.msg);
                                        }else{
                                            Ext.Msg.alert('Greška', 'Greška prilikom spremanja');
                                        }
                                    }
                                });
                            }
                        },
                        {
                            text: 'Odustani',
                            handler: function() {
                                window.close();
                            }
                        }
                    ]
                });

                var window = ApplicationInstance.getDesktop().createWindow({
                    title: me.title,
                    width: me.windowWidth,
                    iconCls: me.winCls,
                    layout: 'fit',
                    minHeight: me.windowHeight,
                    items: form
                });

                window.show();
            },
            scope: me
        };

        Ext.apply(me, config);

        me.callParent(arguments);
    }
});


