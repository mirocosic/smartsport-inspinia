
Ext.define('Ext.ux.grid.MetaGridPanel', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.metagridpanel',
    storeUrl: '',
    permaColumns: [],
    constructor: function(config) {
        // call parent constructor

        this.callParent(arguments);

        this.getStore().on({
            metachange: {
                scope: this,
                fn: function(store, metadata) {
                    var fieldConfig = [];
                    var columnConfig = this.permaColumns.slice();//slice makes copy instead of reference
                    Ext.each(metadata.fields, function(field) {
                        var renderer = null;
                        if (field.header) {
                            if (!field.type) {
                                field.type = 'string';
                            }
                            switch (field.type) {
                                case 'date':
                                    if (field.showtime) {
                                        renderer = Ext.util.Format.dateRenderer('d.m.Y H:i');
                                    } else {
                                        renderer = Ext.util.Format.dateRenderer('d.m.Y');
                                    }

                                    break;
                                default:
                                    renderer = null;
                                    break;
                            }

                            var fieldCfg = {};

                            fieldConfig.push(Ext.apply(fieldCfg, field));

                            columnConfig.push({
                                header: field.header,
                                type: field.type,
                                dataIndex: field.name,
                                renderer: renderer,
                                sortable: field.sortable
                            });
                        }
                    });

                    Meta.dynamicModel.setFields(fieldConfig);
                    this.reconfigure(null, columnConfig);

                    //this.doLayout();
                }
            }
        });
    },
    initComponent: function() {

        this.columns = [];

        if(!MiscUtils.exists('Meta.dynamicModel')) {
            Ext.define('Meta.dynamicModel', {
                extend: 'Ext.data.Model',
                fields: []
            });
        }

        this.store = new Ext.data.Store({
            proxy: {
                type: 'ajax',
                simpleSortMode: 'true',
                url: this.storeUrl,
                reader: {
                    type: 'json',
                    totalProperty: 'totalCount',
                    root: 'data'
                }
            },
            storeId: 'metaStore',
            pageSize: 20,
            model: 'Meta.dynamicModel',
            remoteSort: true
        });

        var pagingtoolbarConfig = {
            xtype: 'pagingtoolbar',
            dock: 'bottom',
            plugins: { ptype: 'pagesize' },
            store: this.store,
            displayInfo: true
        };

        if(this.dockedItems) {
            if(Ext.isArray(this.dockedItems)) {
                this.dockedItems.push(pagingtoolbarConfig);
            } else {
                this.dockedItems = [this.dockedItems, pagingtoolbarConfig];
            }
        } else {
            this.dockedItems = [{
                xtype: 'pagingtoolbar',
                dock: 'bottom',
                plugins: { ptype: 'pagesize' },
                store: this.store,
                displayInfo: true
            }];
        }

        this.callParent(arguments);
    }
});