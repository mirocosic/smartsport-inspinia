<div id="PageContent" class="wrapper wrapper-content animated fadeInRight">
  
</div>

<?= $this->element('Ext');?>
<?=$this->element('Scripts');?>

<script>
Ext.onReady(function() {
   var usersStore = Ext.create('Ext.data.Store',{
        fields:[
            {name:'User.id',mapping:'id'},
            {name:'User.name',mapping:'name'},
            {name:'User.surname',mapping:'surname'},
            {name:'User.mail',mapping:'mail'},
            
            {name:'User.oib',mapping:'oib'}

        ],
        proxy: {
            type:'ajax',
            url:'/clubs/getMembers',
            extraParams: {
                club_id: <?=$this->Session->read('Auth.Club_id');?>
            }
        },
        reader: {
            type:'json'
        },
         autoLoad: true
    });
    
    
var usersGrid = new Ext.grid.GridPanel({
        title: '<?= __("Members");?>',
        glyph: 'xf0f0@FontAwesome',
        padding: '0',
        xtype:'grid',
        layout:'fit',
        renderTo:'PageContent',
        store:usersStore,
        columns: [
            {header:'Id',dataIndex:'User.id',width:50},
            {header:'<?=__("Name");?>',dataIndex:'User.name'},
            {header:'<?=__("Surname");?>',dataIndex:'User.surname'},
            {header:'<?=__("Mail");?>',dataIndex:'User.mail',width:150},
            {header:'<?=__("OIB");?>',dataIndex:'User.oib'},
           
            {

                align: 'center',
                stopSelection: true,
                xtype: 'widgetcolumn',
                width:50,
                widget: {
                    xtype: 'button',
                    height:30,
                    glyph:'xf040@FontAwesome',
                 //   _btnText: "<?= __('Edit');?>",
                    defaultBindProperty: null, //important
                    handler: function(widgetColumn) {
                        var record = widgetColumn.getWidgetRecord();
                        var userEditWindow = Ext.create('Ext.window.Window',{
                            title:'User id = '+record.data.id,
                            width: 300,
                            //height: 300,
                            items: [{
                                xtype:"form",
                                id:"userDataForm",
                                defaults: {
                                    xtype:'textfield',
                                    padding: "10 10 0 10",
                                    allowBlank: false,
                                    blankText:"Warning!"
                                },
                                items:[{
                                        xtype:"hidden",
                                        name:"User.id"
                                },{
                                    fieldLabel:"<?=__('Name');?>",
                                    name: 'User.name'    
                                },{
                                    name:"User.surname",
                                    fieldLabel:"<?=__('Surname');?>"
                                },{
                                    name:"User.mail",
                                    fieldLabel:"<?=__('Mail');?>"
                                },{
                                    name:'User.password',
                                    fieldLabel:"<?=__('Password');?>",
                                    allowBlank: true
                                },{
                                    name:"User.oib",
                                    fieldLabel:"<?=__('OIB');?>",
                                    allowBlank: true
                                },{
                                    name:"User.club_id",
                                    value:<?=$this->Session->read('Auth.Club_id');?>,
                                    allowBlank: true,
                                    hidden:true
                                }],
                                buttons:[{
                                    formBind: true,
                                    text:"<?=__('Save');?>",
                                    handler: function(){
                                        userEditWindow.items.get('userDataForm').getForm().submit({
                                            url: '/users/edit',
                                            success: function (form, action) {
                                                Ext.Msg.alert("<?=__('Saved');?>", action.result.message);
                                                usersStore.load();  
                                                userEditWindow.close();
                                            },
                                            failure: function (form, action) {
                                                 Ext.Msg.alert("<?=__('Ooops!');?>","<?=__('Something went wrong...');?>");
                                            }
                                        });
                                    }
                                },{
                                    text:"<?=__('Delete');?>",
                                      handler: function(){
                                        Ext.MessageBox.confirm("<?=__('Are you sure?');?>","<?=__('Delete user ');?>"+record.data.User.name+"?",function(){
                                            Ext.Ajax.request({
                                                url: '/users/delete',
                                                params: {user_id: record.data.User.id},
                                                success: function (response, opts) {
                                                    var obj = Ext.decode(response.responseText);
                                                    if (obj.success == true){
                                                        Ext.Msg.alert("<?=__('Deleted');?>",obj.message); 
                                                    } else {
                                                        Ext.Msg.alert("<?=__('Error');?>",obj.message);
                                                    }
                                                    userEditWindow.close();
                                                    usersStore.load();
                                                },
                                                failure: function (response, opts) {
                                                     Ext.Msg.alert("<?=__('Ooops!');?>","<?=__('Something went wrong...');?>");
                                                }
                                            });
                                        })
                                    }
                                }]

                            }]
                         })
                        userEditWindow.items.get('userDataForm').getForm().loadRecord(record);
                        userEditWindow.show();
                    },
                    listeners: {
                          beforerender: function(widgetColumn){
                              var record = widgetColumn.getWidgetRecord();
                              widgetColumn.setText( widgetColumn._btnText ); //can be mixed with the row data if needed
                          }
                      }
                }
            }

        ],
        tbar:[{
            xtype:'button',
            text:"<?=__('Create');?>",
            glyph:'xf067@FontAwesome',
            handler:function(){
                var userEditWindow = Ext.create('Ext.window.Window',{
                    title:"<?=__('Create new user');?>",
                    width: 300,
                    items: [{
                        xtype:"form",
                        id:"userDataForm",
                        defaults: {
                            xtype:'textfield',
                            padding: "10 10 0 10",
                            allowBlank: false
                        },
                        items:[{
                            fieldLabel:"<?=__('Name');?>",
                            name: 'User.name'    
                        },{
                            name:'User.surname',
                            fieldLabel:"<?=__('Surname');?>",
                        },{
                            name:"User.mail",
                            fieldLabel:"<?=__('Mail');?>"
                        },{
                            name:'User.password',
                            fieldLabel:"<?=_('Password');?>"

                        },{
                            name:"User.oib",
                            fieldLabel:"<?=__('OIB');?>",
                            allowBlank: true,
                            hidden:true
                        },{
                            name:"User.club_id",
                            value:<?=$this->Session->read('Auth.Club_id');?>,
                            allowBlank: true,
                            hidden:true
                        }],
                        buttons:[{
                            formBind: true,
                            text:"<?=__('Save');?>",
                            handler: function(){
                                userEditWindow.items.get('userDataForm').getForm().submit({
                                    url: '/users/edit',
                                    success: function (form, action) {
                                        Ext.Msg.alert("<?=__('Saved');?>", action.result.message);
                                        usersStore.load();  
                                        userEditWindow.close();
                                    },
                                    failure: function (form, action) {
                                        Ext.Msg.alert("<?=__('Ooops!');?>","<?=__('Something went wrong...');?>");
                                    }
                                });
                            }
                        },{
                            text:"<?=__('Delete');?>"
                        }]

                    }]
                 });
                userEditWindow.show();
            }
        }],
        dockedItems: [{
            xtype: 'pagingtoolbar',
            dock: 'bottom',
            //plugins: {ptype: 'pagesize', displayText: 'Donora po stranici'},
            //pageSize: 10,
            beforePageText: 'Stranica',
            afterPageText: 'od {0}',
            store: usersStore,
            displayInfo: true,
            displayMsg: '<?=__('Users {0} - {1} of {2}')?>',
            emptyMsg: "<?=__('No users')?>"
        }]
     
    });
});
</script>