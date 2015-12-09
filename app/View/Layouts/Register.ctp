<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | Register</title>

    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <link href="/css/animate.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">
    
     <div class="loginColumns animated fadeInDown">
        <div class="row">

            <div class="col-md-6">
                <h2 class="font-bold"><?=__('Welcome to SmartSport');?></h2>

                <p>
                    Perfectly designed and precisely prepared admin theme with over 50 pages with extra new web app views.
                </p>

                <p>
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                </p>

                <p>
                    When an unknown printer took a galley of type and scrambled it to make a type specimen book.
                </p>

                <p>
                    <small>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</small>
                </p>

            </div>
            <div class="col-md-6">
                <div class="ibox-content">
                     <?= $this->Form->create('User',array('id'=>'RegisterForm', 'action'=>'reset_password','class'=>'m-t'));?>
                <?= $this->Form->input('email',array('placeholder'=>__("Email"),'label'=>false, 'class'=>'form-control','div'=>'form-group'));?>
                <?= $this->Form->input('password',array('placeholder'=>__("Password"),'label'=>false, 'class'=>'form-control','div'=>'form-group'));?>
               
                <div class="form-group">
                    <div class="checkbox i-checks text-center"><label class=""> <input type="checkbox"><i></i> Agree the terms and policy </label></div>
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b"><?=__('Register');?></button>

                <p class="text-muted text-center"><small>Already have an account?</small></p>
                <a class="btn btn-sm btn-white btn-block" href="/users/login"><?=__('Log in');?></a>
            </form>
            
                    <p class="m-t">
                        
                    </p>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-6">
              <p class="m-t"> <small>Powered by <b>Sustainable IT</b> &copy; <?=date('Y');?></small> </p>
            </div>
            <div class="col-md-6 text-right">
                 <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <a href="<?=$this->Html->url(array('language'=>'hrv'));?>">HR</a>
                        </li>
                        <li>
                            <a href="<?=$this->Html->url(array('language'=>'eng'));?>">EN</a>
                        </li>
                        <li>
                            <a href="/home"><span class="fa fa-home"></span></a>
                        </li>
                 </ul>
            </div>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="/js/jquery-2.1.1.js"></script>
    <script src="/js/bootstrap.min.js"></script>
     <script src="/js/plugins/validate/jquery.validate.min.js"></script>
     <script src="/js/plugins/sweetalert/sweetalert.min.js"></script>
    <!-- iCheck  -->
    <script src="/js/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
    </script>
     
     <script>
         $("#RegisterForm").validate({
            rules: {
                'data[User][email]': { required : true, email:true },
                'data[User][password]': { required : true}
            },
            messages: {
                'data[User][email]': { required : '<?=__("Required field");?>',email:'<?=__("Email not valid");?>'},
                'data[User][password]': { required : '<?=__("Required field");?>'}
                
            },
            submitHandler: function(){
                $.ajax({
                    url: "/users/register",
                    data:{
                       email:$("#UserEmail").val(),
                        password:$("#UserPassword").val()
                      
                    },
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        if(data.success){
                            swal({
                                title:'<?=__("Great");?>!',
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
                    }
                });
        
            return false;
            }
            
         });
     </script>
   
</body>

</html>
