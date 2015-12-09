/*
 * ComboBoxRenderer
 * Component for rendering combobox display values in ext 4 grids
 **/
Ext.define('Ext.ux.CmpUtils', {
    
    alternateClassName: 'CmpUtils',
    statics: {
        displayFieldRenderer: function(value, metaData, record, rowIdex, colIndex, store, view) {
            var editor = metaData.column.getEditor(record);
            var selectedRecord;
            
            if(value === null || value === '') {
                //console.log('rendered empty value');
                return null;
            }
            
            if (editor) {

                if (editor instanceof Ext.grid.CellEditor) {
                    editor = editor.field;
                }
                                
                if(!record.DataCache){
                    record.DataCache = new Ext.util.MixedCollection();
                } 
                
                if(record.DataCache.containsKey(editor.displayField+"_ID") && record.DataCache.getByKey(editor.displayField+"_ID") == value){
                    //console.log('found cached value: ', record.DataCache.getByKey(editor.displayField+"_STRING"));
                    return record.DataCache.getByKey(editor.displayField+"_STRING");
                }

                if (editor instanceof Ext.form.field.ComboBox) {
                                        
                    var store = editor.getStore();
                    
                    if(store){
                        
                        selectedRecord = editor.findRecord(editor.valueField, value);
                        
                        if(!selectedRecord && !(store.getProxy() instanceof Ext.data.proxy.Memory)) {
                            
                            if (store.loading) {
                                //console.log('rendered empty value due to store loading in-process');
                                return null;
                            }
                            
                            if(editor.queryMode == 'remote') {
                                
                                var loadCfg = {
                                    params: {
                                        singleLoadId: value
                                    }
                                };
                                                                
                                store.load(loadCfg);
                                //console.log('rendered empty value after store load initiated');
                                return null;
                            }
                        }
                    }
                }
            }
            
            var result = selectedRecord ? selectedRecord.get(editor.displayField) : null;
            
            if(result !== null){
                record.DataCache.add(editor.displayField+"_ID",value);
                record.DataCache.add(editor.displayField+"_STRING",result);
                //console.log('saved: ',result);
            }

            return result;
        }
    }
});