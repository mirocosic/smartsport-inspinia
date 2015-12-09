/*!
 * Ext JS Library 4.0
 * Copyright(c) 2006-2011 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */

Ext.define('Ext.ux.desktop.Module', {
    mixins: {
        observable: 'Ext.util.Observable'
    },    
    
    /*
     * @cfg stopOnLastWindowClose
     * true to stop the module when the last window belonging to module is closed.
     */
    stopOnLastWindowClose: true,

    constructor: function (config) {
        var me = this;
        this.mixins.observable.constructor.call(me, config);
        
        Ext.apply(me, config);
        
        me.windows = new Ext.util.MixedCollection();
        
        me.init();        
    },
    
    init: Ext.emptyFn,

    //info: return false to cancel execution
    run: function(app, params) {
        if(!app || !(app instanceof Ext.ux.desktop.App)) {
            Ext.Error.raise('app parameter must be an instance of Ext.ux.desktop.App.');
        } else if(this.app && (app !== this.app)) {
            Ext.Error.raise('Module run on different app than was initialized.');
        }
        
        this.app = app;
    },
    
    stop: function() {
        var me = this;
        
        me.windows.each(function(win) {
            win.close();
        });
    },    
    
    createWindow: function(config, cls) {
        config = Ext.apply({module: this}, config);
        return this.app.getDesktop().createWindow(config, cls);
    }    
});
