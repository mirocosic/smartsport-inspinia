/**
 * This is an extended Panel, it requires a json configuration object
 * which is best generated with the provided Cake helper
 * @author Florian Krause <florian.krause@googlemail.com>
 * 
 * @license Ext.ux.CakePanel and Ext.uxCakeForm are licensed under the terms of
 * the Open Source LGPL 3.0 license.
 * 
 * @class Ext.ux.CakePanel
 * @extends Ext.Panel
 */
Ext.define('Ext.ux.CakePanel', {
    extend: 'Ext.Panel',
    alias: 'widget.cakepanel',
    initComponent: function() {
        
        if(!this.app) {
            Ext.Error.raise('app config required for CakePanel.');
        }
        
        Ext.applyIf(this, {
            //default Panel config
            layout: 'fit',
            activate: true,
            closable: false,
            //these values are required. 
            /**
             * @args Array		 
             * Fields of the datastore to use
             */
            jsonReaderFields: [],
            /**
             * @args Array
             * The columnmodel for the grid
             */
            columnModelFields: [],
            /**
             * @args String
             * Controller name (eg users)
             */
            controllerConfig: '',
            /**
             * @args Array
             * Configure the elements of the add/edit formfield
             */
            formFields: [],
            //these values can be overridden by passing them in the config object
            //since we cannot use objects created at initialisation we overwrite
            //them later on. All of these are optional
            /**
             * @args Object
             * Configure JsonReader, if empty default one is used
             */
            jsonReader: '',
            /**
             * @args Object
             * Configure DataStore, if empty default one is used
             */
            dataStore: '',
            /**
             * columns 
             */
            columns: [],
            /**
             * allow override for actions 
             */
            action: '',
            /**
             * group store by
             */
            groupStoreField: false,
            /**
             * which direction to sort the store in
             * should be asc or desc
             */
            groupStoreSortDirection: '',
            /**
             * @args Object
             * use this store for the filter bar ... same as in comboBox
             */
            filterBarStore: '',
            /**
             * @args Object
             * show filter in top bar, filter this database field
             */
            filterBarField: ''
        }); //end config object
        
        if((typeof this.columnModelFields !== "undefined") && Ext.isArray(this.columnModelFields)) {
            this.columnModelFields = Ext.Array.clone(this.columnModelFields);            
        }
        
        //push action to columnmodel and append plugin to grid
        this.columnModelFields.push({
            xtype: 'actioncolumn',
            doAutoSize: true,
            text: 'Actions',
            items: [{
                    iconCls: 'icon-edit',
                    tooltip: "Edit",
                    handler: function(view, rowIndex, colIndex, item, e, record, row) {
                        var panelTitle = (record.data.id == 0) ? "Create" : "Edit";
                        var form = new Ext.ux.CakeForm({
                            controllerConfig: this.controllerConfig,
                            recordId: record.data.id,
                            items: this.formFields,
                            dataStore: this.dataStore
                        });

                        var win = this.app.getDesktop().createWindow({
                            title: panelTitle + ' entry',
                            items: [form],
                            width: 400,
                            constrain: true
                        });
                        win.show();
                    }
                }, {
                    iconCls: 'icon-delete',
                    tooltip: "Delete",
                    handler: function(view, rowIndex, colIndex, item, e, record, row) {
                        Ext.MessageBox.confirm('Confirm', 'Are you sure you want to delete this entry?', function(btn) {
                            if (btn == "yes") {
                                Ext.Ajax.request({
                                    scope: this,
                                    disableCaching: true,
                                    method: 'GET',
                                    url: 'http://' + Ext.HOST + Ext.WEBROOT_DIR + this.controllerConfig + '/delete/' + record.data.id,
                                    success: function() {
                                        //check for associated models and  reload them										
                                        for (var assocModel in this.dataStore.getProxy().getReader().rawData) {
                                            if (assocModel != this.controllerConfig) {
                                                var reload = Ext.StoreMgr.lookup(assocModel);
                                                if (reload)
                                                    reload.load();
                                            } else if (assocModel != "total") {
                                                //this is necessary because the ext id may differ from cake id
                                                this.dataStore.remove(this.dataStore.getAt(this.dataStore.findExact('id', record.data.id)));
                                            }
                                        }
                                    },
                                    failure: function() {
                                        Ext.MessageBox.alert('Item NOT deleted', 'Network error, could not delete');
                                    }
                                });
                            }
                        }, this);
                    }
                }]
        });
        
        this.grid = new Ext.grid.GridPanel({
            features: this.groupStoreField ? 
                [{
                    ftype: 'grouping',
                    groupHeaderTpl: '{columnName}: {name} ({[children.length]} {[children.length > 1 ? "Items" : "Item"]})'
                }]
                : [],
            store: new Ext.data.Store({
                fields: this.jsonReaderFields,
                proxy: {
                    type: 'ajax',
                    url: 'http://' + Ext.HOST + Ext.WEBROOT_DIR + this.controllerConfig + '/index',
                    reader: {
                        type: 'json',
                        id: 'id',
                        root: this.controllerConfig,
                        totalProperty: 'total'
                    }
                },
                groupField: this.groupStoreField,
                sorters: this.groupStoreField ? [{
                    property: this.groupStoreField,
                    direction: (this.groupStoreSortDirection) ? this.groupStoreSortDirection.toUpperCase() : 'ASC'
                }] : null,
                storeId: this.controllerConfig
            }),            
            selModel: {
                selType: 'rowmodel',
                mode: 'SINGLE'
            },
            columns: this.columnModelFields,
            forceFit: true,
            frame: true
        });

        //if additional config is passed, we overwrite the defaults created before
        this.jsonReader ? this.dataStore.reader = this.jsonReader : this.jsonReader = this.dataStore.reader;
        this.dataStore ? this.grid.store = this.dataStore : this.dataStore = this.grid.store;

        //create toolbar
        this.dockedItems = [{
            xtype: 'toolbar',
            dock: 'bottom',
            width: 'auto',
            items: [
                '->',
                new Ext.Button({
                    text: 'Add new item',
                    handler: function() {
                        var form = new Ext.ux.CakeForm({
                            controllerConfig: this.controllerConfig,
                            recordId: 0,
                            items: this.formFields,
                            dataStore: this.dataStore
                        });

                        var win = this.app.getDesktop().createWindow({
                            title: 'New entry',
                            items: [form],
                            width: 400,
                            autoScroll: true
                        });
                        win.show();
                        //win.center();
                    },
                    iconCls: 'icon-add',
                    scope: this
                })
            ]
        }];

        //create filter toolbar if filterBarStore is set
        if (this.filterBarStore) {
            this.filterCombo = new Ext.form.ComboBox({
                displayField: 'text',
                typeAhead: false,
                queryMode: 'local',
                triggerAction: 'all',
                emptyText: 'Filter',
                selectOnFocus: true,
                width: 135,
                forceSelection: true,
                valueField: 'value'
            });
            //add filter to top bar
            this.dockedItems = {
                xtype: 'toolbar',
                dock: 'top',
                items: ['->', this.filterCombo]
            };
        }
        
        //finally push the grid into the panel
        this.items = [this.grid];

        this.callParent(arguments);
    }, //end initComponent

    onRender: function() {
        this.callParent(arguments);

        //load data as soon as possible
        this.grid.getStore().on('exception', function(dataStore, type, action, options, response) {
            Ext.MessageBox.alert('Wrong data', 'Error loading data<br>' + response.status);
            if (response.status == 401) {
                Ext.MessageBox.alert('Not authorized', 'You are no longer authorized. Please login again');
            }
            if (response.status == 404) {
                Ext.MessageBox.alert('Not found, something\'s gone terribly wrong');
            }
        });

        this.grid.getStore().load();

        if (this.filterBarStore) {
            this.dataStore.on('load', function() {
                var filterStore = Ext.ux.CakeHelper.getComboValues(this.filterBarStore, this.dataStore);
                filterStore.insert(0, new Ext.data.Record({'value': '', 'text': "No filter"}));
                this.filterCombo.bindStore(filterStore);
            }, this);

            this.filterCombo.on('select', function(el) {
                //either pass a reference to a (existing!) store, then we use the passed key
                //to select the value of it (because in the ext db, director_id stores the name
                //because of the mapping, not the id
                //if we pass a object by hand, then that is used
                if (typeof (this.filterBarStore) == "string") { //reference							
                    var habtm = eval('this.dataStore.data.items[0].data.' + this.filterBarStore);
                    if (typeof (habtm) == "object") {//has many assoc, we need to search in sub-store
                        this.dataStore.filterBy(function(store_el) {
                            var habtm_raw = eval('store_el.data.' + this.filterBarStore);
                            var found = false;
                            for (i = 0; i < habtm_raw.length; i++) {
                                var temp = habtm_raw[i];
                                if (eval('temp.' + this.filterBarField) == el.value)
                                    found = true;
                            }
                            return found;
                        }, this)
                    } else {
                        var mapping = eval('this.dataStore.getProxy().getReader().rawData.' + this.filterBarStore);
                        this.dataStore.filter(this.filterBarField, mapping[el.value]);
                    }
                } else { //manually passed object
                    this.dataStore.filter(this.filterBarField, this.filterBarStore[el.value]);
                }

            }, this);
        }
    } //end onRender

});

/**
 * This creates a FormPanel, loads the data from the given controller,handles
 * submits and validation errors
 * @class Ext.ux.CakeForm
 * @extends Ext.FormPanel
 */
Ext.define('Ext.ux.CakeForm', {
    extend: 'Ext.FormPanel',
    alias: 'widget.cakeform',
    initComponent: function() {
        var config = {
            fieldDefaults: {
                labelWidth: 150
            },
            defaults: {
                width: 200
            },
            frame: true,
            defaultType: 'textfield',
            items: this.formFields,
            buttons: [
                {
                    text: 'Submit',
                    disabled: true,
                    scope: this,
                    handler: function() {
                        this.form.submit({
                            url: Ext.WEBROOT_DIR + this.controllerConfig + '/ext_item/' + this.recordId,
                            waitMsg: 'Saving Data...',
                            scope: this,
                            success: function(form, action) {
                                //we check the json reply for associated and the current model.
                                //since we registered their stores in StoreMgr (using the controller's
                                //name as storeId) we can reload them now.
                                //why? Because the store for an associated model might alredy have
                                //been loaded and we need it updated after a save
                                //TODO: perhaps find a more elegant way to do this. getting
                                //associated data in a separate store?
                                for (var assocModel in this.dataStore.getProxy().getReader().rawData) {
                                    if (assocModel != "total") {
                                        var reload = Ext.StoreMgr.lookup(assocModel);
                                        if (reload)
                                            reload.load();
                                    }
                                }
                            },
                            failure: function(form, action, test) {
                                Ext.ux.CakeHelper.loadCakeError(form, action.result.validationErrors);
                            }
                        });
                    }
                }]
        };
        Ext.apply(this, config);
        this.callParent(arguments);
    },
    onRender: function() {
        this.callParent(arguments);

        this.load({
            url: Ext.WEBROOT_DIR + this.controllerConfig + '/ext_item/' + this.recordId,
            method: 'GET',
            success: function(form, action) {
                Ext.ux.CakeHelper.loadCakeData(form, action.result.data);
            }
        });
        this.on({
            actioncomplete: function(form, action) {
                // Only enable the submit button if the load worked
                if (action.type == 'load') {
                    this.getDockedItems('toolbar[dock="bottom"]')[0].enable();
                }
                if (action.type == 'submit') {
                    this.ownerCt.close();
                }
            },
            actionfailed: function(form, action) {
                if (action.type == 'load') { // Handle the LOAD errors
                    if (action.failureType == "connect") {
                        Ext.MessageBox.alert('fs.actionfailed error', 'Form load failed. Could not connect to server.');
                    } else {
                        if (action.response.responseText != '') {
                            var result = Ext.decode(action.response.responseText);
                            if (result && result.msg) {
                                Ext.MessageBox.alert('fs.actionfailed error', 'Form load failed with error: ' + action.result.msg);
                            } else {
                                Ext.MessageBox.alert('fs.actionfailed error', 'Form load failed with unknown error (possibly missing the "success" field in the json). Action type=' + action.type + ', failure type=' + action.failureType);
                            }
                        } else {
                            Ext.MessageBox.alert('fs.actionfailed error', 'Form load returned an empty string instead of json');
                        }
                    }
                }
            }
        });
        //prepare combo and multiselect boxes so they understand the cake data
        this.items.each(function(el) {
            if (el.xtype == "combo") {
                if (el.comboStore) {
                    var comboDataStore = Ext.ux.CakeHelper.getComboValues(el.comboStore, this.dataStore);
                    el.bindStore(comboDataStore);
                }
            }
            if (el.xtype == "multiselect") {
                if (el.multiStore) {
                    var multiDataStore = Ext.ux.CakeHelper.getComboValues(el.multiStore, this.dataStore);
                    el.bindStore(multiDataStore);
                }
            }
        }, this);
    }
});

Ext.define('Ext.ux.CakeHelper', {
    statics: {
        //reads the data from the json call and sets the form fields accordingly
        loadCakeData: function(form, data) {
            if (!data || data == null)
                return;
            for (var i in data) {
                var values = data[i];
                var valueArray = new Array(); //group multiselect into array
                for (var j in values) {
                    if (isFinite(j)) {
                        var theName = 'data[' + i + '][' + i + ']';
                        valueArray.push(values[j].id);
                    } else {
                        var theName = 'data[' + i + '][' + j + ']';
                    }
                    var field = form.findField(theName);
                    if (field) {
                        if (field.xtype == "checkbox")
                        {
                            if (values[j] == 1 || values[j] == true) {
                                field.setValue(true);
                            }
                        } else if (field.xtype == 'combo') {
                            var storeId = field.getStore().findExact('value', values[j]);
                            var record = field.getStore().getAt(storeId);
                            if (record)
                                field.setValue(record.data.value);
                        } else if (field.xtype == "multiselect") {
                            field.setValue(valueArray);
                        } else {
                            field.setRawValue(values[j]);
                        }
                    }
                }
            }
        },
        loadCakeError: function(form, data) {
            if (!data || data == null) {
                Ext.MessageBox.alert('Greška', 'Something went wrong but the server didn\'t tell me what');
            }
            for (var i in data) {
                var values = data[i];
                for (var j in values) {
                    var theName = 'data[' + i + '][' + j + ']';
                    var field = form.findField(theName);
                    if (field)
                        field.markInvalid(values[j]);
                }
            }
        },
        flattenObject: function(object) {
            var isFunction = typeof object == "function" && typeof object.call == "function"
                    && object != object.constructor.prototype;

            if (typeof object != "object" && !isFunction)
                throw new TypeError("Object.toArray, Incompatible object: " + typeof object);

            var entrySet = new Array();

            for (var prop in object) {
                if (!object.hasOwnProperty(prop))
                    continue;

                // User must explicitly copy |constructor| over.
                if (prop == "constructor")
                    continue;

                entrySet.push([prop, object[prop]]);
            }
            return entrySet;
        },
        /**
         * @args String
         * Returns an SimpleStore from ajax call
         * used for setting the entries for select boxes
         */
        getComboValues: function(name, store) {
            if (typeof (name) == "string") {
                var comboData = eval('store.getProxy().getReader().rawData.' + name);
                comboData = Ext.ux.CakeHelper.flattenObject(comboData);
            } else {
                var comboData = Ext.ux.CakeHelper.flattenObject(name);
            }
            var comboDataStore = new Ext.data.SimpleStore({
                fields: ["value", "text"],
                data: comboData
            });
            return comboDataStore;
        },
        renderHasMany: function(values) {
            if (values.length > 0) {

                var output = '<ul>';
                for (var value in values) {
                    if (isFinite(value))
                        output += '<li>' + (this.remap ? eval('values[value].' + this.remap) : values[value].name) + '</li>';
                }
                output += '</ul>';
            }
            return output;
        },
        renderCheckbox: function(value) {
            return (value == 1) ? 'Yes' : 'No'
        }
    }
});
