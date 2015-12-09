/*!
 * Ext JS Library 4.0
 * Copyright(c) 2006-2011 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */

/**
 * @class Ext.ux.desktop.Wallpaper
 * @extends Ext.Component
 * <p>This component renders an image that stretches to fill the component.</p>
 */
Ext.define('Ext.ux.desktop.Wallpaper', {
    extend: 'Ext.Component',

    alias: 'widget.wallpaper',

    cls: 'ux-wallpaper',
    html: '<img src="'+Ext.BLANK_IMAGE_URL+'">',

    layout: 'tiled',
    wallpaper: null,
    stateful  : true,
    stateId  : 'desk-wallpaper',

    afterRender: function () {
        var me = this;
        me.callParent();
        me.setWallpaper(me.wallpaper, me.layout);
    },

    applyState: function () {
        var me = this, old = me.wallpaper;
        me.callParent(arguments);
        if (old != me.wallpaper) {
            me.setWallpaper(me.wallpaper);
        }
    },

    getState: function () {
        return this.wallpaper && { wallpaper: this.wallpaper };
    },

    setWallpaper: function (wallpaper, layout) {
        var me = this, imgEl, bkgnd;
        
        me.layout = layout || 'tiled';
        me.wallpaper = wallpaper;

        if (me.rendered) {
            imgEl = me.el.dom.firstChild;

            if (!wallpaper || wallpaper == Ext.BLANK_IMAGE_URL) {
                Ext.fly(imgEl).hide();
            } else if (me.layout === 'fit') {
                imgEl.src = wallpaper;

                me.el.removeCls('ux-wallpaper-tiled');
                me.el.removeCls('ux-wallpaper-centered');
                Ext.fly(imgEl).setStyle({
                    'min-width': '100%',
                    'min-height': '100%'
                }).show();
            } else if (me.layout === 'center') {
                Ext.fly(imgEl).hide();

                bkgnd = 'url('+wallpaper+')';
                
                me.el.removeCls('ux-wallpaper-tiled');
                me.el.addCls('ux-wallpaper-centered');
            } else {
                Ext.fly(imgEl).hide();

                bkgnd = 'url('+wallpaper+')';
                me.el.removeCls('ux-wallpaper-centered');
                me.el.addCls('ux-wallpaper-tiled');
            }

            me.el.setStyle({
                backgroundImage: bkgnd || ''
            });
            if(me.stateful) {
                me.saveState();
            }
        }
        return me;
    }
});
