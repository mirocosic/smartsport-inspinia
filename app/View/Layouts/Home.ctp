<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | Main view</title>

    <!-- Ext Theme -->
    <link rel="stylesheet" href="/ext-themes/theme-triton/resources/theme-triton-all.css"/>

    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <link href="/css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link href="/css/plugins/datepicker/datepicker3.css" rel="stylesheet">
    <link href="/css/plugins/clockpicker/clockpicker.css" rel="stylesheet">
    <link href="/css/plugins/chosen/chosen.css" rel="stylesheet">
    <link href="/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
    <link href="/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">


    <link href="/css/animate.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/custom.css" rel="stylesheet">
</head>

<body>

<div id="wrapper">

    <?= $this->element('Menu');?>

    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    <form role="search" class="navbar-form-custom" method="post" action="#">
                        <div class="form-group">
                            <input type="text" placeholder="<?=__("Search");?>..." class="form-control" name="top-search" id="top-search">
                        </div>
                    </form>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" aria-expanded="false">
                            <i class="fa fa-envelope"></i>  <span class="label label-warning">16</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" aria-expanded="false">
                            <i class="fa fa-bell"></i>  <span class="label label-primary">8</span>
                        </a>
                    </li>


                    <li>
                        <a href="/users/logout">
                            <i class="fa fa-sign-out"></i><?=__("Logout");?>
                        </a>
                    </li>
                </ul>

            </nav>
        </div>
        
        <?php echo $this->fetch('content'); ?>
        
        
        <div class="footer">
            <div class="pull-right">
                10GB of <strong>250GB</strong> Free.
            </div>
            <div>
                <strong>Copyright</strong> <a href="#">Sustainable IT</a> &copy; <?=date('Y');?>
            </div>
        </div>

    </div>
</div>


</body>

</html>

