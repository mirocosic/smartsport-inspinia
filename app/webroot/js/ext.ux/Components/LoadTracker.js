
Ext.define('Ext.ux.LoadTracker', {
    mixins: {
        observable: 'Ext.util.Observable'
    },
    constructor: function(config) {
        var me = this;

        var done = false;

        Ext.apply(me, config);

        this.mixins.observable.constructor.call(this, config);

        this.addEvents(
            'beforeload',
            'load'
        );

        me.stores = [];

        me.addStore = function(store) {

            if(Ext.isObject(store) && store instanceof Ext.data.Store && !Ext.Array.contains(me.stores, store)){
                me.stores.push(store);
                store.on('load', storeLoadHandler, me);
                store.on('beforeload', storeBeforeHandler, me);
                //console.log('added',store);
            }
        }

        me.removeStore = function(store) {
            var len = me.stores.length;
            Ext.Array.remove(me.stores, store);

            if(len == (me.stores.length+1)){//if array is shorter
                store.un('load', storeLoadHandler);
                store.un('beforeload', storeBeforeHandler);
            }
        }

        var storeLoadHandler = function(store, records, successful) {

            if(!done && !me.checkStoresLoading() && Ext.isFunction(me.loadComplete)) {
                me.loadComplete(me);
                me.fireEvent('load', me);
                done = true;
            }
        }

        var storeBeforeHandler = function(store, records, successful) {
            if(!done && !me.checkStoresLoading() && Ext.isFunction(me.loadStart)) {
                me.loadStart(me);
                me.fireEvent('beforeload', me);
            }
        }

        me.checkStoresLoading = function() {
            for(var i = 0, tot = me.stores.length; i < tot; i++) {
                if(me.stores[i].loading) {
                    return true;
                }
            }

            return false;
        }

        me.reset = function(){
            done = false;
        }

        me.clear = function() {

        }
    },
    loadComplete: Ext.emptyFn,
    loadStart: Ext.emptyFn
});