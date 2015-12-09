<div id="PageContent" class="wrapper wrapper-content animated fadeInRight">
    
</div>

<script>
var clubsStore = Ext.create('Ext.data.Store',{
    fields: [
        {name:'Club.id',mapping:'Club.id'},
        {name:'Club.name',mapping:'Club.name'}
    ],
    proxy: {
        type:'ajax',
        url:'/clubs/index'
    },
    reader: {
        type:'json'
    },
    autoLoad: true
});

 
var membersStore = Ext.create('Ext.data.Store',{
    fields:[
        {name:'User.id',mapping:'User.id'},
        {name:'User.name',mapping:'User.name'},
        {name:'User.surname',mapping:'User.surname'},
        {name:'User.mail',mapping:'User.mail'},
        {name:'User.username',mapping:'User.username'}

    ],
    proxy: {
        type:'ajax',
        url:'/users/index'
    },
    reader: {
        type:'json'
    },
     autoLoad: true
});
    
   
    
var clubMembersStore = Ext.create('Ext.data.Store',{
    fields:[
        {name:'User.id',mapping:'id'},
        {name:'User.username',mapping:'username'},
        {name:'UsersClub.admin',mapping:'UsersClub.admin'}
    ],
    proxy:{
        type:"ajax",
        url:"/clubs/getMembers"
    },
    reader: {
        type:"json"
    }
})

var usersGrid = new Ext.grid.GridPanel({
        title:"<?=__('Clubs');?>",
        glyph:"xf1e7@FontAwesome",
        renderTo:'PageContent',
        xtype:'grid',
        store:clubsStore,
        columns:[
            {header:'ID',dataIndex:'Club.id',width:50},
            {header:"<?=__('Name');?>",dataIndex:'Club.name'},
            {   stopSelection: true,
                xtype: 'widgetcolumn',
                width:120,
                widget: {
                    xtype: 'button',
                    text: "<?= __('Edit');?>",
                    glyph:'xf040@FontAwesome',
                    defaultBindProperty: null, //important
                    handler: function(widgetColumn) {
                      var record = widgetColumn.getWidgetRecord();

                        var clubEditWindow = Ext.create('Ext.window.Window',{
                            title:'Club id = '+record.data.Club.id,
                            width: 300,
                            items: [{
                                xtype:"form",
                                id:"clubDataForm",
                                defaults: {
                                    xtype:'textfield',
                                    padding: "10 10 0 10",
                                    allowBlank: false
                                },
                                items:[{
                                    xtype:"hidden",
                                    name:"Club.id"
                                },{
                                    fieldLabel:"<?=__('Name');?>",
                                    name: 'Club.name'    
                                }],
                                buttons:[{
                                    formBind: true,
                                    text:"<?=__('Save');?>",
                                    handler: function(){
                                        clubEditWindow.items.get('clubDataForm').getForm().submit({
                                            url: '/clubs/edit',
                                            success: function (form, action) {
                                                Ext.Msg.alert("<?=__('Saved');?>", action.result.message);
                                                clubsStore.load();  
                                                clubEditWindow.close();
                                            },
                                            failure: function (form, action) {
                                                Ext.Msg.alert("<?=__('Error');?>", action.result.message);
                                            }
                                        });
                                    }
                                },{
                                    text:"<?=__('Delete');?>",
                                    handler: function(){
                                        Ext.MessageBox.confirm("<?=__('Are you sure?');?>","<?=__('Delete club ');?>"+record.data.Club.name+"?",function(){
                                            Ext.Ajax.request({
                                                url: '/clubs/delete',
                                                params: {id: record.data.Club.id},
                                                success: function (response, opts) {
                                                    var obj = Ext.decode(response.responseText);
                                                    if (obj.success){
                                                        Ext.Msg.alert("<?=__('Deleted');?>",obj.message); 
                                                    } else {
                                                        Ext.Msg.alert("<?=__('Error');?>",obj.message);
                                                    }
                                                    clubEditWindow.close();
                                                    clubsStore.load();
                                                },
                                                failure: function (response, opts) {
                                                    Ext.Msg.alert("<?=__('Error');?>",response.message);
                                                }
                                            });
                                        })
                                    }
                                }]

                            }]
                         });

                        clubEditWindow.items.get('clubDataForm').getForm().loadRecord(record);
                        clubEditWindow.show();

                    }
                }

            },{
                xtype:'widgetcolumn',
                width:120,
                widget:{
                    xtype: 'button',
                    text: "<?= __('Members');?>",
                    glyph: 'xf0c0@FontAwesome',
                    defaultBindProperty: null, //important
                    handler: function(widgetColumn) {
                        var clubRecord = widgetColumn.getWidgetRecord();
                        var clubMembersWindow = Ext.create('Ext.window.Window',{
                            title:"<?=__('Members');?>",
                            glyph: 'xf0c0@FontAwesome',
                            width: 400,
                            height:500,
                            padding:20,
                            items:[{
                                xtype:'container',
                                layout:'hbox',
                                items:[{
                                    xtype:'combobox',
                                    item_id:'addMemberCombo',
                                    hideTrigger:true,
                                    typeAhead: true,
                                    forceSeletion: true,
                                    queryMode:'local',
                                    minChars: 2,
                                    store:membersStore,
                                    displayField:"User.username",
                                    valueField:'User.id',
                                    name:'Club.members',
                                    fieldLabel:"<?=__('User');?>",
                                    listeners: {
                                        buffer: 50,
                                        beforerender:function(){
                                            var store = this.store;
                                            store.clearFilter();
                                        },
                                        change: function() {
                                          var store = this.store;
                                          store.clearFilter();
                                          //store.resumeEvents();
                                          store.filter({
                                              property: 'User.username',
                                              anyMatch: true,
                                              value   : this.getValue()
                                          });
                                        }
                                      }

                                },{
                                    xtype:"button",
                                    text:"<?=__('Add');?>",
                                    glyph:'xf067@FontAwesome',
                                    handler:function(){
                                        var user_id = clubMembersWindow.down('[item_id=addMemberCombo]').getValue();
                                        Ext.Ajax.request({
                                            url:"/clubs/addMember",
                                            params:{
                                                club_id:clubRecord.data.Club.id,
                                                user_id:user_id
                                            },
                                            success:function(response){
                                                var r = Ext.decode(response.responseText);
                                                if(r.success == true){
                                                   // Ext.Msg.alert('Da');
                                                    clubMembersStore.load({params:{club_id:clubRecord.data.Club.id}});
                                                } else {
                                                    Ext.Msg.alert("<?=__('Error');?>",r.message);
                                                }
                                            },
                                            failure:function(response){
                                                 Ext.Msg.alert('Ne');
                                            }
                                        });
                                    }
                                }]
                            },{
                                padding:'20 0 0 0',
                                xtype:'grid',
                                store:clubMembersStore,
                                columns:[
                                    {header:'Id',dataIndex:'User.id',width:50},
                                    {header:"<?=__('Username');?>",dataIndex:"User.username"},
                                    {header:"Admin",dataIndex:"UsersClub.admin",
                                        renderer:function(val){
                                           if (val == 1) {
                                               return "<?=__('Yes');?>"
                                           } else {
                                               return "<?=__('No');?>"
                                           }
                                        }
                                    },
                                    {   xtype:"widgetcolumn",
                                        widget:{
                                            xtype: 'button',
                                            text: "<?= __('Remove');?>",
                                            glyph: 'xf068@FontAwesome',
                                            defaultBindProperty: null,
                                            handler:function(widgetColumn){
                                                var memberRecord = widgetColumn.getWidgetRecord();
                                                var users_club_id = memberRecord.data.UsersClub.id;
                                                Ext.Ajax.request({
                                                    url:"/clubs/removeMember",
                                                    params:{
                                                        users_club_id:users_club_id
                                                    },
                                                    success:function(response){
                                                        var r = Ext.decode(response.responseText);
                                                        if(r.success == true){

                                                            clubMembersStore.load({params:{club_id:clubRecord.data.Club.id}});
                                                        } else {
                                                            Ext.Msg.alert("<?=__('Error');?>",r.message);
                                                        }
                                                    },
                                                    failure:function(response){
                                                         Ext.Msg.alert("<?=__('Error');?>");
                                                    }
                                                });
                                            }
                                        }
                                }
                                ]
                            }]
                        });
                        clubMembersStore.load({params:{club_id:clubRecord.data.Club.id}});
                        clubMembersWindow.show();
                    }
                }
            }

        ]
        ,
        tbar:[{
            xtype:'button',
            margin: '20 0 0 20',
            text:"<?=__('Create');?>",
            glyph:'xf067@FontAwesome',
            handler:function(){
                var clubEditWindow = Ext.create('Ext.window.Window',{
                    title:"<?=__('Create new club');?>",
                    width: 300,
                    items: [{
                        xtype:"form",
                        id:"clubDataForm",
                        defaults: {
                            xtype:'textfield',
                            padding: "10 10 0 10",
                            allowBlank: false
                        },
                        items:[{
                            fieldLabel:"<?=__('Name');?>",
                            name: 'Club.name'    
                        }],
                        buttons:[{
                            formBind: true,
                            text:"<?=__('Save');?>",
                            handler: function(){
                                clubEditWindow.items.get('clubDataForm').getForm().submit({
                                    url: '/clubs/edit',
                                    success: function (form, action) {
                                        Ext.Msg.alert("<?=__('Saved');?>", action.result.message);
                                        clubsStore.load();  
                                        clubEditWindow.close();
                                    },
                                    failure: function (form, action) {
                                        Ext.Msg.alert("<?=__('Error');?>", action.result.message);
                                    }
                                });
                            }
                        },{
                            text:"<?=__('Delete');?>"
                        }]

                    }]
                 });

               // clubEditWindow.items.get('clubDataForm').getForm().loadRecord(record);
                clubEditWindow.show();
            }
        }]
    });
 </script>   