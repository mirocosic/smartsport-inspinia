Ext.define('Ext.ux.LoopTask', {
    
    fn: Ext.emptyFn,
    
    startDelay: 100,
    interval: 1000,
    
    constructor: function(config) {
        var me = this;
        
        Ext.apply(me, config);
        
        Ext.Function.defer(me.onTick, me.startDelay, me);
    },
    
    onDestroy: function () {
        var me = this;

        if (me.timer) {
            window.clearTimeout(me.timer);
            me.timer = null;
        }
    },

    onTick: function () {
        var me = this;
        me.fn();
        me.timer = Ext.Function.defer(me.onTick, me.interval, me);
    }
});