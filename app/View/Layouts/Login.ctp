<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | Login</title>

    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/font-awesome/css/font-awesome.css" rel="stylesheet">

    
    <!-- CSS plugins -->
    
     <link href="/css/plugins/toastr/toastr.min.css" rel="stylesheet">
     <link href="/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
     
     
    <link href="/css/animate.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/custom.css" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="background-div"></div>

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div style="position: relative; padding: 20px;">
            <div id="LoginFormBgn" style="position: absolute;background-color: white; opacity:0.8; width: 100%; height:100%;"></div>
            <div>

                <h1 class="logo-name">SS</h1>

            </div>
            <h3><?=__("Welcome to SmartSport");?></h3>
       
            <?= $this->Form->create('User',array(
                'id'=>'LoginForm',
                'action'=>'login',
                'class'=>'m-t'));
            ?>
            <?= $this->Form->input('email',array(
                'placeholder'=>__("Email"),
                'label'=>false,
                'class'=>'form-control',
                'div'=>'form-group'));
            ?>
            <?= $this->Form->input('password',array(
                'placeholder'=>__("Password"),
                'label'=>false,
                'class'=>'form-control',
                'div'=>'form-group'));
            ?>
        <!--    <form class="m-t" role="form" action="/users/login"> 
                <div class="form-group">
                    <input type="email" class="form-control" placeholder="Email" required="">
                </div>
        
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" required="">
                </div>
        -->
                <button type="submit" class="btn btn-primary block full-width m-b"><?=__("Log in");?></button>
        

                <a href="/users/reset_password"><small><?=__("Forgot password");?>?</small></a>
                <p class="text-muted text-center hidden"><small><?=__("Do not have an account?");?></small></p>
                <br/><br/>
                <a class="btn btn-sm btn-white btn-block" href="/users/register"><?=__("Create an account");?></a>
            </form>
            <p class="m-t"> <small>Powered by <b>Sustainable IT</b> &copy; <?=date('Y');?></small> </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="/js/jquery-2.1.1.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    
    <!-- Plugins -->
    <script src="/js/plugins/validate/jquery.validate.min.js"></script>
    <script src="/js/plugins/toastr/toastr.min.js"></script>
    <script src="/js/plugins/sweetalert/sweetalert.min.js"></script>
    
    <script>
    $(document).ready(function(){
        
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "progressBar": true,
            "preventDuplicates": true,
            "positionClass": "toast-top-center",
            "onclick": null,
            "showDuration": "400",
            "hideDuration": "400",
            "timeOut": "1000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
          };
     
        
        $("#LoginForm").validate({
            rules: {
                'data[User][email]': { required : true, email:false },
                'data[User][password]': { required : true}
            },
            messages: {
                'data[User][email]': { required : '<?=__("Required field");?>',email:'<?=__("Email not valid");?>'},
                'data[User][password]': { required : '<?=__("Required field");?>'}
            },
            submitHandler: function(){
                $.ajax({
                    url: "/users/login",
                    data:{
                       username:$("#UserEmail").val(),
                       password: $("#UserPassword").val()
                    },
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        if(data.success){
                            swal({
                                title:'<?=__("Welcome");?> '+data.name+'!',
                                text:'<?=__("Redirecting");?>...',
                                type:'success',
                                timer:500,
                                showConfirmButton: false
                            }, function(){
                                 window.location.href = data.redirect;
                            });
                        } else {
                            toastr.error('<?=__("Wrong email or password!");?>');
                             
                        }
                      
                    },
                    error: function (xhr, status) {
                        swal({
                            title:"<?=__('We\'re sorry!');?>",
                            text:'Something went wrong. Please try again.',
                              type:'error',
                              showConfirmButton: true
                        });
                    }
                });
        
            return false;
            }
         });
     });
     
         
     </script>

</body>
</html>
