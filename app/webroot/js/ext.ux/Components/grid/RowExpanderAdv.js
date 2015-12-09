/**
 * A plugin that augments the Ext.ux.RowExpander to support clicking the header to expand/collapse all rows.
 *
 * Notes:
 *
 * - Compatible with Ext 4.x
 *
 * Example usage:
        var grid = Ext.create('Ext.grid.Panel',{
            plugins: [{
                ptype: 'dvp_rowexpander',
                pluginId: 'xpander'
            }]
            ...
        });

        grid.getPlugin('xpander').collapseAll();

 *
 * @author $Author: pscrawford $
 * @revision $Rev: 13458 $
 * @date $Date: 2013-02-20 14:04:38 -0700 (Wed, 20 Feb 2013) $
 * @license Licensed under the terms of the Open Source [LGPL 3.0 license](http://www.gnu.org/licenses/lgpl.html).  Commercial use is permitted to the extent that the code/component(s) do NOT become part of another Open Source or Commercially licensed development library or toolkit without explicit permission.
 * @constructor
 * @param {Object} config
 */
Ext.define('Ext.ux.grid.plugin.RowExpander', {
    alias: 'plugin.dvp_rowexpander',
    extend: 'Ext.grid.plugin.RowExpander',


    //configurables
    /**
     * @cfg {String} collapseAllCls
     */
    collapseAllCls: 'rowexpand-collapse-all',
    /**
     * @cfg {String} expandAllCls
     */
    expandAllCls: 'rowexpand-expand-all',
    /**
     * @cfg {String} headerCls
     */
    headerCls: 'rowexpand-header',

    tooltip: 'Expand/collapse all visible rows',

    //properties

    //private
    constructor: function(){
        var me = this;

        me.callParent(arguments);

        /**
         * @property toggleAllState
         * @type {Boolean}
         * Signifies the state of all rows expanded/collapsed.
         * False is when all rows are collapsed.
         */
        me.toggleAllState = false;
    },//eof constructor

    /**
     * @private
     * @param {Ext.grid.Panel} grid
     */
    init: function(grid) {
        var me = this,
            col;

        me.callParent(arguments);

        col = grid.headerCt.getComponent(0); //assumes 1st column is the expander
        col.on('headerclick',me.onHeaderClick,me);
        col.on('render',me.onHeaderRender,me);
    }, // eof init

    /**
     * @private
     * @return {Object}
     */
    getHeaderConfig: function(){
        var me = this,
            config = me.callParent(arguments);

        Ext.apply(config,{
            cls: (config.cls || '') + ' ' + me.headerCls,
            tooltip: me.tooltip
        });
        return config;
    },

    /**
     * Collapse all rows.
     */
    collapseAll: function(){
        this.toggleAll(false);
    },

    /**
     * Expand all rows.
     */
    expandAll: function(){
        this.toggleAll(true);
    },

    /**
     * @private
     * @param {Ext.grid.header.Container} header
     * @param {Ext.grid.column.Column} column
     * @param {Ext.EventObject} e
     * @param {HTMLElement} t
     */
    onHeaderClick: function(ct,col){
        var me = this;

        if (me.toggleAllState){
            me.collapseAll();
        } else {
            me.expandAll();
        }
    }, //eof onHeaderClick

    /**
     * @private
     * @param {Ext.grid.column.Column} column
     */
    onHeaderRender: function(col){
        col.textEl.addCls(this.expandAllCls);
    },

    /**
     * @private
     * @param {Boolean} expand True to indicate that all rows should be expanded; false to collapse all.
     */
    toggleAll: function(expand){
        var me = this,
            ds = me.getCmp().getStore(),
            records = ds.getRange(),
            l = records.length,
            i,
            record;

        for (i = 0; i < l; i++){ //faster than store.each()
            record = records[i];
            if (me.recordsExpanded[record.internalId] !== expand){
                me.toggleRow(i,record);
            }
        }

        var el = me.grid.headerCt.getComponent(0).textEl;

        if (me.toggleAllState != expand){
            if(expand){
                el.replaceCls(me.expandAllCls,me.collapseAllCls);
            } else {
                el.replaceCls(me.collapseAllCls,me.expandAllCls);
            }
        }
        me.toggleAllState = !me.toggleAllState;
    },

    toggleRow: function(rowIdx, record) {
        var me = this,
            view = me.view;

        var rowNode = view.getNode(rowIdx);

        if(rowNode) {
            var row = Ext.fly(rowNode, '_rowExpander'),
                nextBd = row.down(me.rowBodyTrSelector, true),
                isCollapsed = row.hasCls(me.rowCollapsedCls),
                addOrRemoveCls = isCollapsed ? 'removeCls' : 'addCls',
                ownerLock, rowHeight, fireView;
        } else {
            return;
        }

        // Suspend layouts because of possible TWO views having their height change
        Ext.suspendLayouts();
        row[addOrRemoveCls](me.rowCollapsedCls);
        Ext.fly(nextBd)[addOrRemoveCls](me.rowBodyHiddenCls);
        me.recordsExpanded[record.internalId] = isCollapsed;
        view.refreshSize();

        // Sync the height and class of the row on the locked side
        if (me.grid.ownerLockable) {
            ownerLock = me.grid.ownerLockable;
            fireView = ownerLock.getView();
            view = ownerLock.lockedGrid.view;
            rowHeight = row.getHeight();
            // EXTJSIV-9848: in Firefox the offsetHeight of a row may not match
            // it's actual rendered height due to sub-pixel rounding errors. To ensure
            // the rows heights on both sides of the grid are the same, we have to set
            // them both.
            row.setHeight(isCollapsed ? rowHeight : '');
            row = Ext.fly(view.getNode(rowIdx), '_rowExpander');
            row.setHeight(isCollapsed ? rowHeight : '');
            row[addOrRemoveCls](me.rowCollapsedCls);
            view.refreshSize();
        } else {
            fireView = view;
        }
        fireView.fireEvent(isCollapsed ? 'expandbody' : 'collapsebody', row.dom, record, nextBd);
        // Coalesce laying out due to view size changes
        Ext.resumeLayouts(true);
    },

    isRowCollapsed: function(rowIdx) {
        var me = this,
            view = me.view;

        var rowNode = view.getNode(rowIdx);

        if(rowNode) {
            var row = Ext.fly(rowNode, '_rowExpander'),
                isCollapsed = row.hasCls(me.rowCollapsedCls);

            return isCollapsed;
        } else {
            return;
        }
    }
});