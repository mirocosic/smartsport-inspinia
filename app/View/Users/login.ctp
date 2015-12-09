<!DOCTYPE html>
<html>
    <head>
        <title>Main page for SmartSport App</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!-- load ExtJS -->
        <script src="/js/ext/ext-all.js"></script>
        
        <!-- load Theme Triton -->
        <script src="/ext-themes/theme-triton/theme-triton.js"></script>
        <link rel="stylesheet" href="/ext-themes/theme-triton/resources/theme-triton-all.css"/>
        
        <!-- load fonts -->
        <link rel="stylesheet" href="/css/fonts.css"/>
        
        <style>
            html,body {
                height:100%;
                position: relative;
            }
            
            #content {
                position: absolute;
                margin: auto !important;
                width: 320px;
                height: 400px;
                left:0;
                right: 0;
                top:0;
                bottom:0;
            }
            
        </style>
    </head>
    <body>
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            <?php 
              $uid = AuthComponent::user('id');
              if ($uid){
                 //echo "ga('set', '&uid', 'UserID=$uid'); ";// Set the user ID using signed-in user_id.
                 echo "ga('create', 'UA-67040802-1', { 'userId': 'UserID=$uid' });";
                 echo "ga('send', 'pageview');";
              } else {
                  echo "ga('create', 'UA-67040802-1', 'auto');";
                  echo "ga('send', 'pageview');";
              }
            ?>

        </script>
        <div id="content">
            
        </div>   
      
       
    </body>
<script type='text/javascript'>
    
Ext.onReady(function() {
    var loginPanel = new Ext.form.Panel({
        title:"<?=__('Login');?>",
        width: 320,
        url: 'login',
        defaultType: 'textfield',
        pollForChanges: true,
        padding: 10,
        items: [{
                fieldLabel: "<?=__('Username');?>",
                name: 'data[User][username]',
                hasFocus: true,
                itemId: 'username_field',
                allowBlank: false,
                margin: '30 10 10 10'
            }, {
                fieldLabel: "<?=__('Password');?>",
                name: 'data[User][password]',
                inputType: 'password',
                allowBlank: false,
                margin: '10 10 30 10'
            }, {
                name: '_method',
                value: 'POST',
                hidden: true,
                allowBlank: false
            }
        ],
        buttons: [{
            text: "<?=__('Log In');?>",
            formBind: true,
            // Function that fires when user clicks the button 
            handler: function() {
                submitFormAndLogin();
            }
        }],
        listeners: {
            afterRender: function(thisForm, options){
                this.keyNav = Ext.create('Ext.util.KeyNav', this.el, {
                    enter: submitFormAndLogin,
                    scope: this
                });
                Ext.defer(function(){
                    thisForm.down('#username_field').getEl().query('input')[0].focus();
                }, 500);
            }
        }
    });
    
     function submitFormAndLogin() {
     
        loginPanel.getForm().submit({
            method: 'POST',
            waitTitle: 'Connecting',
            waitMsg: 'Authenticating...',
            success: function(form, action) {
                console.log(action);
                var obj = Ext.decode(action.response.responseText);
                Ext.Msg.alert('Status', 'Login Successful!', function(btn, text) {
                    if (btn == 'ok') {
                        var redirectUrl = obj.redirect;
                        console.log(redirectUrl);
                        window.location = redirectUrl;
                    }
                });
            },
            failure: function(form, action) {
                Ext.Msg.alert('Status', 'Login Failed!');
                /*
                if (action.failureType == 'server') {
                    var obj = Ext.decode(action.response.responseText);
                    Ext.Msg.alert('Login Failed!', obj.errors.reason);
                } else {
                    Ext.Msg.alert('Warning!', 'Authentication server is unreachable : ' + action.response.responseText);
                }
                loginPanel.getForm().reset();
                
                */
            }
        });
        
    }
    
    loginPanel.render(Ext.get('content'));
    
});

</script>
</html>