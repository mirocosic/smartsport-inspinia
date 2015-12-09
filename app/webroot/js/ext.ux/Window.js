
Ext.define('Ext.ux.desktop.Window', {
    extend: 'Ext.window.Window',
    
    /**
     * @cfg desktop
     * The Ext.ux.desktop.Desktop instance to add the window to.
     * REQUIRED
     */
        
    isWindow: true,
    
    constrainHeader: true,
    
    minimizable: true,
    
    maximizable: true,
        
    constructor: function (config) {
        var me = this;
        
        me.addEvents(
            'beforeload',
            'load'
        );
        
        //clone config
        config = Ext.apply({}, config);
        
        if(!config.desktop) {
            Ext.Error.raise('config.desktop is required.');
        }
        if(!(config.desktop instanceof Ext.ux.desktop.Desktop)) {
            Ext.Error.raise('config.desktop must be an instance of Ext.ux.desktop.Desktop.');
        }
        
        me.desktop = config.desktop;
        
        delete config.desktop;
        
        //internal flag from Desktop system
        if(config.doNotProcess !== true) {
            me.desktop.attachWindow(me);
        }
        
        delete config.doNotProcess;
        
        me.callParent(arguments);
        
        me.loadTracker = new Ext.ux.LoadTracker();
        
        me.loadTracker.on('beforeload',function(){
            me.fireEvent('beforeload', me);
        });
        me.loadTracker.on('load',function(){
            me.fireEvent('load', me);
        });
        
//        me.on('beforeload',function(){
//            Ext.suspendLayouts(true);
//            document.body.style.cursor = 'progress';
//            console.log('window before');
//        });
//        
//        me.on('load',function(){
//            Ext.resumeLayouts(true);
//            document.body.style.cursor = 'auto';
//            console.log('window load'); 
//        });
    },
    
    getDesktop: function() {
        return this.desktop;
    },
    
    getModule: function() {
        return this.module;
    },
    
    trackStore:function(store){
        this.loadTracker.addStore(store);
    }
});
