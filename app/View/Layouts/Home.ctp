<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | Main view</title>

    <!-- Ext Theme -->
    <link rel="stylesheet" href="/ext-themes/theme-triton/resources/theme-triton-all.css"/>

    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="/css/plugins/toastr/toastr.min.css" rel="stylesheet">

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
                            <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
                        </div>
                    </form>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                     <li>
                        <a href="<?=$this->Html->url(array('language'=>'hrv'));?>">
                            HR
                        </a>
                         
                    </li>
                     <li>
                        <a href="<?=$this->Html->url(array('language'=>'eng'));?>">
                             EN
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
                <strong>Copyright</strong> Example Company &copy; 2014-2015
            </div>
        </div>

    </div>
</div>


</body>

</html>

