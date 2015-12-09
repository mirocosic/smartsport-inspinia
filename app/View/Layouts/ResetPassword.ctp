<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | Forgot password</title>

    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

    <link href="/css/animate.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">
    <div class="passwordBox animated fadeInDown">
        <div class="row">
            <div class="col-md-12">
                <div class="ibox-content">
                    <h2 class="font-bold"><?=__("Forgot password");?> <i class="fa fa-frown-o"></i></h2>
                    <p>
                        <?=__("Enter your email address and your password will be reset and emailed to you");?>.
                    </p>

                    <div class="row">

                        <div class="col-lg-12">
                            <?= $this->Form->create('User',array('id'=>'PasswordForm', 'action'=>'reset_password','class'=>'m-t'));?>
                            <?= $this->Form->input('email',array('placeholder'=>__("Email address"),'label'=>false, 'class'=>'form-control','div'=>'form-group'));?>

                            <button type="submit" class="btn btn-primary block full-width m-b"><?=__("Send new password");?></button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-6">
                Powered by <b>Sustainable IT</b>
            </div>
            <div class="col-md-6 text-right">
               <small>© <?=date('Y');?></small>
            </div>
        </div>
    </div>
    <script src="/js/jquery-2.1.1.js"></script>
     <script src="/js/plugins/validate/jquery.validate.min.js"></script>
     <script src="/js/plugins/sweetalert/sweetalert.min.js"></script>
     
     <script>
         $("#PasswordForm").validate({
            rules: {
                'data[User][email]': { required : true, email:true }
                
            },
            messages: {
                'data[User][email]': { required : '<?=__("Required field");?>',email:'<?=__("Email not valid");?>'}
                
            },
            submitHandler: function(){
                $.ajax({
                    url: "/users/reset_password",
                    data:{
                       email:$("#UserEmail").val()
                      
                    },
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        if(data.success){
                            swal({
                                title:'<?=__("Sent");?>!',
                                text:data.message,
                                type:'success',
                                //timer:500,
                                showConfirmButton: true
                            }, function(){
                                 window.location.href = data.redirect;
                            });
                        } else {
                            swal({
                                title:"<?=__('We\'re sorry!');?>",
                                text:data.message,
                                type:'error',
                                showConfirmButton: true
                            });
                             
                        }
                      
                    },
                    error: function (xhr, status) {
                        swal({
                            title:"<?=__('We\'re sorry!');?>",
                            text:'Something went wrong. Please try again.',
                              type:'error',
                              showConfirmButton: true
                        });
                    },
                });
        
            return false;
            }
            
         });
     </script>
</body>

</html>
