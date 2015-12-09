Ext.define('Ext.ux.Video', {
    extend: 'Ext.Component',
    alias: 'widget.video',

    autoEl: 'video',

    //baseCls: Ext.baseCSSPrefix + 'video',

    /**
     * @cfg {String} src
     * The image src.
     */
    src: '',
   
   //DOM video element
    video:null,

    ariaRole: 'video',
    
    
    
    autoplay:true,
    
    

    initComponent: function() {        
        this.callParent();
    },
    
    
    listeners: {
        afterrender: {
            fn: function(){                        
                this.video = this.videoEl.dom;                
                //this.clear();
            }
        }        
    },

    getElConfig: function() {
        var me = this,
            autoEl = me.autoEl,
            config = me.callParent(),    
            video;

        // It is sometimes helpful (like in a panel header icon) to have the video wrapped
        // by a div. If our autoEl is not 'video' then we just add an video child to the el.
        if (autoEl === 'video' || (Ext.isObject(autoEl) && autoEl.tag === 'video')) {
            video = config;
        } else  {
            config.cn = [video = {
                tag: 'video',
                role: me.ariaRole,
                id: me.id + '-video'
            }];
        }

        if (video) {
            video.src = me.src || null;
        }

/*
        if (me.alt) {
            (video || config).alt = me.alt;
        }*/
        if (me.autoplay) {
            (video || config).autoplay = "autoplay";
        }

        return config;
    },

    onRender: function () {
        var me = this,
            autoEl = me.autoEl,
            el;

        me.callParent(arguments);

        el = me.el;
        
        if (autoEl === 'video' || (Ext.isObject(autoEl) && autoEl.tag === 'video')) {
            me.videoEl = el;
        }
        else {
            me.videoEl = el.getById(me.id + '-video');
        }
    },

    onDestroy: function () {
        Ext.destroy(this.videoEl);
        this.videoEl = null;
        this.callParent();
    },
    
    
    setSrc: function(src) {
        var me = this,
            videoEl = me.videoEl;

        me.src = src;

        if (videoEl) {
            videoEl.dom.src = src || null;
        }
    },

});