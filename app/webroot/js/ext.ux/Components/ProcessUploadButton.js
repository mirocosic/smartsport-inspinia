
Ext.define('Ext.ux.ProcessUploadButton', {
    extend: 'Ext.Button',
    alias: 'widget.processuploadbutton',
    text: 'Upload and Process File',
    windowName: 'processUploadPrompt',
    title: 'Upload and process',
    windowId: '',
    items: [],
    actionUrl: 'placeholder',
    params: {},
    baseParams: {},
    progressbarUrl:'warehouse/progressBarStatus',
    progressbarDestroyUrl:'warehouse/progressBarDestroy',
    
    windowWidth: 500,
    returnBaseParams: null, //to get base params
    initComponent: function() {
        var config = {
            scope: this,
            handler: function() {
                this.window=Ext.getCmp('processUploadWindow');
                if(!this.window){
                
                    this.form = new Ext.form.FormPanel({
                        frame: true,
                        //url: this.actionUrl,
                        baseParams: this.baseParams,
                        pollForChanges: true,
                        items: this.items,
                        defaults: {
                            anchor: '-10'
                        },
                        buttons: [
                           {
                                text:'Upload and Process',
                                scope:this,
                                handler:function(){
                                    var me=this;
                                    me.progressMessageBox=Ext.Msg.show({
                                        closable:false, //remove X from window
                                        title:'Upload Progress',
                                        msg:'Please Wait.<br>Uploading requested file.',
                                        progress:true,
                                        progressText:'Preparing for upload'
                                    }); 
                            /*
                                    me.progressMessageBox.progress(
                                        'Upload Progress',
                                        'Please Wait.<br>Uploading requested file.',
                                        'Preparing for upload'
                                    );*/
                                    var formParams=this.form.getForm().getValues();                                    
                                         
                                    
                                    var fileInput=this.filefield.extractFileInput();
                                    var file = fileInput.files[0];
                                    
                                                                                
                                    new jsUpload({
                                        filename:file.name,                                        
                                        file: file,
                                        formParams:Ext.encode(formParams),
                                        url: this.actionUrl,
                                        size: 1048576,
                                        logger: function(message){return;/*block debug output to console*/},
                                        progressHandler: function(percent, isFinal){                                
                                            me.progressMessageBox.updateProgress(percent,Math.round(percent*100)+'% complete')                             
                                            if(isFinal){
                                                me.progressMessageBox.updateText('Upload Complete.<br>Please wait.');
                                                me.progressMessageBox.updateProgress(1,"Server processing uploaded file...");                                                
                                                
                                                if(percent==0.99){//finished uploading informing server                                                    
                                                    me.progressMessageBox.progress('Processing Data','Processing uploaded File, Please wait.');
                                                    setTimeout(function(){ //settimeout 5000 because win.close closes form and dosent post    
                                                        me.handleProgressBar(2000)                                                                                                               
                                                        me.window.close(); 
                                                    },5000);
                                                }
                                            }
                                            
                                           
                                        }
                                    });
                                }   
                            },{
                                text:'Cancel',
                                scope:this,
                                handler:function(){
                                    this.window.close();
                                }
                            }
                        ]
                    });
                    
                    
                    
                    this.filefield= new Ext.form.field.File({
                        //xtype:'filefield',
                        //id:'fileUploadFieldImportBomList',
                        fieldLabel:"File",
                        //buttonOnly: true,
                        buttonText:"Select File",
                        allowBlank:false,
                        anchor:'-10',
                        name:'File'
                    });
                    
                    this.form.add(this.filefield);
                    
                    this.window = ApplicationInstance.getDesktop().createWindow({
                        title: this.title,
                        width: this.windowWidth,
                        //id: this.windowId,
                        layout: 'fit',
                        height: 300,
                        items: this.form
                    });
                    
                    
                    
                }
                this.window.show();
            }
        };//eo config

        Ext.apply(this, config);

        this.callParent(arguments);
    },
    
    //private
    handleProgressBar:function(timeout){
        var me=this;
        Ext.Ajax.request({
            url: this.progressbarUrl,
            scope:this,
            callback: function(o, success, resp){
                if(success){
                    var response={}
                    try{
                        response = Ext.decode(resp.responseText);
                    }catch(e){
                        console.error('Unable to decode progress JSON');
                        console.debug(e);
                        setTimeout(function(){me.handleProgressBar(timeout)},timeout);
                        return;
                    }
                     me.progressMessageBox.updateProgress(response.val,response.msg);                         
                    if(response.end){                        
                        me.progressMessageBox.close();//getdialog does not work any more
                        Ext.Msg.alert('Complete', 'Requested operation is finished with response:'+'<br>'+response.msg);
                        Ext.Ajax.request({url: this.progressbarDestroyUrl});
                        
                    }else{
                        setTimeout(function(){me.handleProgressBar(timeout)},timeout);
                    }
                }else{
                    Ext.MessageBox.alert('No response', 'Greška mreže ili poslužitelja, poslužitelj nedostupan'); 
                    me.progressMessageBox.close();
                }
            } 
        });
    }
    
});


