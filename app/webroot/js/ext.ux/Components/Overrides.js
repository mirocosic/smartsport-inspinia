
//add tooltip-functionality to all formfields
Ext.define(null, {
    override: 'Ext.form.Field',
    afterRender: function() {
        var me = this;
        me.callParent();
        if (me.qtip) {
            me.tip = Ext.create('Ext.tip.ToolTip', {
                target: me.getEl().getAttribute("id"),
                trackMouse: true,
                renderTo: document.body,
                html: me.qtip
            });
        }
    }
});

Ext.define(null, {
    override: 'Ext.data.Store',
    loadTracker: null,
    constructor: function(config) {
        var me = this;

        me.callParent(arguments);

        if (me.getCount() > 0) {
            me.firstLoad = true;
        } else {
            me.on('load', function() {
                me.firstLoad = true;
            }, me, {single: true});
        }
        if (me.loadTracker) {
            me.loadTracker.addStore(me);
        }
    },
    isDirty: function() {
        var me = this, isDirty = false;

        me.each(function(item) {
            if (item.dirty == true) {
                isDirty = true;
            }
        });
        if (!isDirty) {
            isDirty = (me.getRemovedRecords().length > 0);
        }
        if (!isDirty) {
            isDirty = (me.getNewRecords().length > 0);
        }
        return isDirty;
    },
    searchAllRecords: function(field, value) {
        var me = this;

        var rec = me.findExact(field, value);

        var result;

        if (!rec) {
            return [];
        } else {
            result = [rec];
        }

        var updates = Ext.Array.merge(me.getUpdatedRecords(), me.getNewRecords());

        var filtered = Ext.Array.filter(updates, function(item) {
            item.get('id') == rec.get('id');
        });

        return result.concat(filtered);
    },
    searchAllByIndex: function(index) {
        var me = this;

        var rec = me.getAt(index);

        var result;

        if (!rec) {
            return [];
        } else {
            result = [rec];
        }

        var updates = Ext.Array.merge(me.getUpdatedRecords(), me.getNewRecords());

        var filtered = Ext.Array.filter(updates, function(item) {
            item.get('id') == rec.get('id');
        });

        return result.concat(filtered);
    },
    disableFilters: function() {
        var me = this;

        me.filters.each(function(item) {
            item.disabled = true;
        });
    },
    loadRawData: function(args) {
        this.loading = true;
        this.callParent(arguments);
        this.loading = false;
        this.loadFirst = true;
        this.fireEvent('load', this);
    },
    syncWithCallback: function(syncConfObj) { //do call callback after sync if nothing to sync
        if (this.isDirty()) {
            return this.sync(syncConfObj);
        } else {
            //todo better:
            syncConfObj.callback(new Ext.data.Batch());
            return this;
        }
    }
});

Ext.define(null, {
    override: 'Ext.form.field.ComboBox',
    selectOnFocus: true,
    enableRegEx: true,
    msgTarget: 'side',
    initComponent: function() {
        var me = this;

        me.addEvents('changeassert');

        me.callParent(arguments);

        me.on('blur', function() {
            if (me.queryFilter) {
                me.queryFilter.disabled = true;
            }
        });

        me.on('change', function(me, nval, oval) {
            var val = me.getRawValue();
            if (((val === null) || (val === '') || (Ext.isArray(val) && val.length === 0)) && me.allowBlank) {
                me.clearValue();
                nval = me.getValue()
                this.fireEvent('changeassert', this, nval, oval);
                //console.log('combo reset upon value: ', val);
            }
        });
    },
    setValue: function(v) {
        var store = this.getStore();
        if (store && (!store.firstLoad || store.loading)) {
            //console.log('*custom set value scheduled event, value: ', v);
            store.on('load', Ext.bind(this.setValue, this, arguments), null, {single: true});
        } else {
            var lastValue = this.getValue();

            this.callParent(arguments);

            var newValue = this.getValue();
            //console.log('* value set: ', newValue);

            if (!this.isEqual(newValue, lastValue) && store.findExact(this.valueField, newValue) != -1) {
                this.fireEvent('changeassert', this, newValue, lastValue);
                //console.log('* changeassert called: ', v);
            } else {
                //console.log('* changeassert failed: ', newValue, ' <> ', lastValue, ' or store index not found: ' + store.findExact(this.valueField, newValue));
            }
        }
    },
    getSelectedIndex: function() {
        var v = this.getValue();
        var r = this.findRecord(this.valueField || this.displayField, v);
        return(this.store.indexOf(r));
    },
    selectAt: function(index) {
        var me = this;
        me.setValue(me.getStore().getAt(index).get(me.valueField));
    },
    expand: function() {
        var me = this;

        if (me.preventExpand) {
            return;
        }

        me.callParent(arguments);
    },
    doLocalQuery: function(queryPlan) {
        var me = this,
                queryString = queryPlan.query;

        // Create our filter when first needed
        if (!me.queryFilter) {
            // Create the filter that we will use during typing to filter the Store
            me.queryFilter = new Ext.util.Filter({
                id: me.id + '-query-filter',
                anyMatch: me.anyMatch,
                caseSensitive: me.caseSensitive,
                root: 'data',
                property: me.displayField
            });
            me.store.addFilter(me.queryFilter, false);
        }

        // Querying by a string...
        if (queryString || !queryPlan.forceAll) {
            me.queryFilter.disabled = false;
            me.queryFilter.setValue(me.enableRegEx ? new RegExp(Ext.util.Format.escapeRegex(queryString), 'i') : queryString);
        }

        // If forceAll being used, or no query string, disable the filter
        else {
            me.queryFilter.disabled = true;
        }

        // Filter the Store according to the updated filter
        me.store.filter();

        // Expand after adjusting the filter unless there are no matches
        if (me.store.getCount()) {
            me.expand();
        } else {
            me.collapse();
        }

        me.afterQuery(queryPlan);
    },
    emptyQuery: function(preventExpand) {
        var me = this;

        if (preventExpand !== true)
            me.preventExpand = true;

        me.doQuery('', true);

        if (preventExpand !== false)
            me.preventExpand = false;
    }
});

Ext.define(null, {
    override: 'Ext.button.Button',
    hrefTarget: '_self',
    afterRender: function() {
        var me = this;
        me.callParent();
        me.updateQTip(me.qtip);
    },
    updateQTip: function(html) {
        var me = this;
        if (!me.tip && html) {
            me.tip = Ext.create('Ext.tip.ToolTip', {
                target: me.getEl().getAttribute("id"),
                trackMouse: true,
                renderTo: document.body,
                html: me.qtip,
                mouseOffset: [-30, -50]
            });
        }

        if(me.tip) {
            if (html) {
                me.tip.update(html);
            } else {
                me.tip.close();
                delete me.tip;
            }
        }
    }
});

//group Select/Deselect All in one op
Ext.define(null, {
    override: 'Ext.selection.Model',
    selectAll: function(suppressEvent) {
        var me = this;

        me.fireEvent('beginselectall', me);
        me.batchSelectionRunning = true;
        me.callParent(arguments);
        me.fireEvent('selectall', me);
        me.batchSelectionRunning = false;
    },
    deselectAll: function(suppressEvent) {
        var me = this;

        me.fireEvent('begindeselectall', me);
        me.batchSelectionRunning = true;
        me.callParent(arguments);
        me.fireEvent('deselectall', me);
        me.batchSelectionRunning = false;
    }
});

//select by id function for row selectionmodel
Ext.define(null, {
    override: 'Ext.selection.RowModel',
    selectById: function(id, suppressEvents) {

        var sm = this,
            s = sm.getStore();

        if (s.getCount() === 0) {
            return null;
        }

        var record = null;

        if(s instanceof Ext.data.TreeStore) {
            record = s.getNodeById(id);
        } else {
            record = s.getById(id);
        }

        if (record) {
            sm.select(record, false, suppressEvents);
        }
    },
    selectBy: function(name, value, suppressEvents) {

        var sm = this,
            s = sm.getStore();

        if (s.getCount() === 0) {
            return null;
        }

        var record = null;

        if(s instanceof Ext.data.TreeStore) {
            record = s.findBy(name, value);
        } else {
            record = s.findRecord(name, value, 0, false, true, true);
        }

        if (record) {
            sm.select(record, false, suppressEvents);
        }
    },
    storeHasSelected: function(record) {
        var store = this.store,
            records,
            len, id, i;

        if (record.hasId() && store.getById(record.getId())) {
            return true;
        } else {
            records = store.data.items || [];
            len = records.length;
            id = record.internalId;

            for (i = 0; i < len; ++i) {
                if (id === records[i].internalId) {
                    return true;
                }
            }
        }
        return false;
    }
});

Ext.define(null, {
    override: 'Ext.selection.CheckboxModel',
    //larger select area to prevent easy misclick deselects
    checkSelector: '.' + Ext.baseCSSPrefix + 'grid-cell-row-checker'
});

//add reset event on form reset
Ext.define(null, {
    override: 'Ext.form.BasicForm',
    constructor: function(config) {
        this.addEvents('reset');

        this.callParent(arguments);
    },
    reset: function(resetRecord) {

        resetRecord = resetRecord || true;

        this.callParent([resetRecord]);

        this.fireEvent('reset', this);

        return this;
    }
});

//post all by default
Ext.define(null, {
    override: 'Ext.data.proxy.Ajax',
    actionMethods: {create: 'POST', read: 'POST', update: 'POST', destroy: 'POST'},
    simpleSortMode: true,
    setExtraParam: function(name, value) {
        this.extraParams = this.extraParams || {};
        this.extraParams[name] = value;
    }
});

Ext.define(null, {
    override: 'Ext.form.field.Time',
    enableRegEx: false,
    selectOnFocus: false
});

//added for utility (faster than AbstractMixedCollection.each())
Ext.define(null, {
    override: 'Ext.util.AbstractMixedCollection',
    forEach: function(fn, scope) {
        var items = [].concat(this.items); // each safe for removal
        Ext.Array.forEach(items, fn, scope);
    }
});

//drag and drop fails without animation
Ext.override(Ext.dd.DragZone, {
    animRepair: false
});

//SHOULD BE THE DEFAULT
Ext.define(null, {
    override: 'Ext.window.Window',
    constrainHeader: true,
    animCollapse: false
});

Ext.define(null, {
    override: 'Ext.view.Table',
    enableTextSelection: true,
    initComponent: function() {
        var me = this;
        me.callParent(arguments);
        me.on('refresh', function(view) {
            var columns = view.getHeaderCt().getGridColumns();
            Ext.each(columns, function(column) {
                if (column.doAutoSize === true && column.rendered)
                    column.autoSize();
            });
        });
    }
});

//ONLY WITH GRAY THEME!!
Ext.define(null, {
    override: 'Ext.panel.Panel',
    bodyStyle: 'background-color:#F0F0F0;'
});
Ext.define(null, {
    override: 'Ext.grid.Panel',
    bodyStyle: 'background-color:#FFFFFF;',
    initComponent: function() {
        var me = this;

        me.callParent(arguments);

        var view = me.getView();

        me.on('afterrender', function() {
            view.getEl().on('mouseover', function(e, t) {
                var cell = Ext.fly(t).down('div');
                var val = cell.getHTML();

                if (Ext.util.TextMetrics.measure(cell, val).width > cell.getWidth(true)) {
                    Ext.fly(t).set({
                        'data-qtip': val
                    });
                } else {
                    t.removeAttribute('data-qtip');
                }
            },
                    this,
                    {
                        delegate: '.x-grid-cell'
                    });
        }, me, {single: true});
    },
    getFeature: function(id) {
        return this.getView().getFeature(id);
    }
});

Ext.define(null, {
    override: 'Ext.tree.Panel',
    bodyStyle: 'background-color:#FFFFFF;'
});

Ext.define(null, {
    override: 'Ext.form.action.Action',
    submitEmptyText: false
});

//start week on monday
Ext.define(null, {
    override: 'Ext.picker.Date',
    startDay: 1
});
Ext.define(null, {
    override: 'Ext.form.field.Date',
    startDay: 1
});

Ext.define(null, {
    override: 'Ext.data.TreeStore',
    setRootNode: function(root, /* private */ preventLoad) {
        var me = this,
            model = me.model,
            idProperty = model.prototype.idProperty

        root = root || {};
        if (!root.isModel) {
            root = Ext.apply({}, root);
            // create a default rootNode and create internal data struct.
            Ext.applyIf(root, {
                id: me.defaultRootId,
                text: me.defaultRootText,
                allowDrag: false
            });
            if (root[idProperty] === undefined) {
                root[idProperty] = me.defaultRootId;
            }
            Ext.data.NodeInterface.decorate(model);
            root = Ext.ModelManager.create(root, model);
        } else if (root.isModel && !root.isNode) {
            Ext.data.NodeInterface.decorate(model);
        }


        // Because we have decorated the model with new fields,
        // we need to build new extactor functions on the reader.
        me.getProxy().getReader().buildExtractors(true);

        // When we add the root to the tree, it will automaticaly get the NodeInterface
        me.tree.setRootNode(root);

        // If the user has set expanded: true on the root, we want to call the expand function to kick off
        // an expand process, so clear the expanded status and call expand.
        // Upon receipt, the expansion process is the most efficient way of processing the
        // returned nodes and putting them into the NodeStore in one block.
        // Appending a node to an expanded node is expensive - the NodeStore and UI are updated.
        //
        // >>> change tree behaviour - autoload controlled, not node-vontrolled
        if (preventLoad !== true && !root.isLoaded() && (Ext.isDefined(me.autoLoad) && me.autoLoad !== false && root.isExpanded())) {
            root.data.expanded = false;
            root.expand();
        }

        return root;
    },
    onProxyLoad: function(operation) {
        var me = this,
            successful = operation.wasSuccessful(),
            records = operation.getRecords(),
            node = operation.node,
            scope = operation.scope || me,
            args = [records, operation, successful];

        me.loading = false;
        node.set('loading', false);

        if (successful) {
            if (!me.clearOnLoad) {
                records = me.cleanRecords(node, records);
            } else {
                me.getRootNode().removeAll();
            }
            records = me.fillNode(node, records);
        }

        // The load event has an extra node parameter
        // (differing from the load event described in AbstractStore)
        /**
         * @event load
         * Fires whenever the store reads data from a remote data source.
         * @param {Ext.data.TreeStore} this
         * @param {Ext.data.NodeInterface} node The node that was loaded.
         * @param {Ext.data.Model[]} records An array of records.
         * @param {Boolean} successful True if the operation was successful.
         */
        // deprecate read?
        Ext.callback(operation.internalCallback, scope, args);
        me.fireEvent('read', me, operation.node, records, successful);
        me.fireEvent('load', me, operation.node, records, successful);
        //this is a callback that would have been passed to the 'read' function and is optional
        Ext.callback(operation.callback, scope, args);
    },
    findBy: function(name, value) {
        var me = this;

        var found = me.getRootNode().findChildBy(function(node) {
            return (node.get(name) == value);
        });

        return found;
    },
    indexOf: function(record) {
        //tree store
        return -1;
    },
    getCount: function() {
        var me = this;

        var count = 0;

        me.getRootNode().cascade(function() {
            count++;
        });
        return count;
    },
    
     getDeepAllLeafNodes: function (node) {
        var me = this;
        var allNodes = new Array();
        if (!Ext.value(node, false)) {
            return [];
        }
        if (node.isLeaf()) {
            return node;
        } else {
            node.eachChild(
                function (Mynode) {
                    allNodes = allNodes.concat(me.getDeepAllLeafNodes(Mynode));
                }
            );
        }
        return allNodes;
    },

    filter: function (filterFn) {

        var me = this;

        var leafNodes = me.getDeepAllLeafNodes(me.getRootNode());
        Ext.Array.each(leafNodes, function (name, index, nodes) {
            var node = leafNodes[index];

            if (!filterFn(name)) {

                // if the parent has no more nodes
                // remove the parent
                // if not, just remove the child node
                // getChildAt needs to be 1, not 0, because the first node will be deleted
                if(node.parentNode.getChildAt(1) !== undefined){
                    node.remove();
                }else{
                    node.parentNode.remove();
                }
            }
        });
    },

    clearFilter: function () {
        this.load();
    }
});

Ext.define(null, {
    override: 'Ext.grid.plugin.CellEditing',
    onEditComplete: function(ed, value, startValue) {
        var me = this,
            activeColumn = me.getActiveColumn(),
            context = me.context,
            record;

        if (activeColumn) {
            record = context.record;

            me.setActiveEditor(null);
            me.setActiveColumn(null);
            me.setActiveRecord(null);

            context.value = value;
            if (!me.validateEdit()) {
                me.editing = false;
                return;
            }

            // Only update the record if the new value is different than the
            // startValue. When the view refreshes its el will gain focus
            if (!record.isEqual(value, startValue)) {
                record.set(activeColumn.dataIndex, value);
            }

            // Restore focus back to the view.
            // Use delay so that if we are completing due to tabbing, we can cancel the focus task
            //
            // -> REMOVED to prevent stealing focus after edit
            //context.view.focusRow(context.rowIdx, 100);

            me.fireEvent('edit', me, context);
            me.editing = false;
        }
    }
});

//FF NS_ERROR_LOOKUP fix FFS
Ext.define(null, {
	override: 'Ext.form.field.HtmlEditor',
	execCmd: function(cmd, value)  {
		var me = this,
		    doc = me.getDoc();
		try {
			doc.execCommand(cmd, false, (Ext.isDefined(value) ? value : null));
		}
		catch (e) { }
        me.syncValue();
    }
});

Ext.override(Ext.form.field.HtmlEditor, {

    initDefaultFont: function(){
        // It's not ideal to do this here since it's a write phase, but we need to know
        // what the font used in the textarea is so that we can setup the appropriate font
        // options in the select box. The select box will reflow once we populate it, so we want
        // to do so before we layout the first time.
        var me = this,
            selIdx = 0,
            fonts, font, select,
            option, i, len, lower;
        
        if (!me.defaultFont) {
            font = me.textareaEl.getStyle('font-family');
            font = Ext.String.capitalize(font.split(',')[0]);
            fonts = Ext.Array.clone(me.fontFamilies);
            Ext.Array.include(fonts, font);
            fonts.sort();
            me.defaultFont = font;

            // handle the select-box only if enableFont is true    <-----------
            if (me.enableFont){
                select = me.down('#fontSelect').selectEl.dom;
                for (i = 0, len = fonts.length; i < len; ++i) {
                    font = fonts[i];
                    lower = font.toLowerCase();
                    option = new Option(font, lower);
                    if (font == me.defaultFont) {
                        selIdx = i;
                    }
                    option.style.fontFamily = lower;
                    select.add(option);
                }
                // Old IE versions have a problem if we set the selected property
                // in the loop, so set it after.
                select.options[selIdx].selected = true;
            }
        }
    }
});



//Fix for itemDom undefined error -> addandreceive nema gumbe nakon kamere
Ext.define(null, {
	override: 'Ext.layout.Layout',
    isValidParent : function(item, target, position) {
        var itemDom = item.el ? item.el.dom : Ext.getDom(item),
            targetDom = (target && target.dom) || target,
            parentNode = itemDom ? itemDom.parentNode : null, //itemDom undefined
            className;

        // If it's resizable+wrapped, the position element is the wrapper.
        if (parentNode) {
            className = parentNode.className;
            if (className && className.indexOf(Ext.baseCSSPrefix + 'resizable-wrap') !== -1) {
                itemDom = itemDom.parentNode;
            }
        }

        // Test DOM nodes for equality using "===" : http://jsperf.com/dom-equality-test
        if (itemDom && targetDom) {
            if (typeof position == 'number') {
                position = this.getPositionOffset(position);
                return itemDom === targetDom.childNodes[position];
            }
            return itemDom.parentNode === targetDom;
        }

        return false;
    },

});



// fix hide submenu (in chrome 43)
Ext.override(Ext.menu.Menu, {
    onMouseLeave: function(e) {
    var me = this;


    // BEGIN FIX
    var visibleSubmenu = false;
    me.items.each(function(item) { 
        if(item.menu && item.menu.isVisible()) { 
            visibleSubmenu = true;
        }
    })
    if(visibleSubmenu) {
        //console.log('apply fix hide submenu');
        return;
    }
    // END FIX


    me.deactivateActiveItem();


    if (me.disabled) {
        return;
    }


    me.fireEvent('mouseleave', me, e);
    }
});