<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <!--<link rel="icon" href="favicon.ico">-->

    <title>Dashboard Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="<?=base_url()?>build/bower/bootstrap-3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="<?=base_url()?>build/bower/bootstrap-3.3.7/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?=base_url()?>build/css/dashboard.css" rel="stylesheet">

    <?php Template::load_css();?>

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="<?=base_url()?>build/bower/bootstrap-3.3.7/assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="<?=base_url()?>build/bower/bootstrap-3.3.7/assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script>var BASE_URL = '<?=base_url();?>';</script>
    <?php Template::load_js('header');?>
</head>

<body>

<?php require(APPPATH . 'views/_common/main_navbar.php');?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <?php require(APPPATH . 'views/_common/main_sidebar.php');?>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <?=$content?>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="<?=base_url()?>build/jquery-3.2.1.js"></script>
<script src="<?=base_url()?>build/bower/bootstrap-3.3.7/dist/js/bootstrap.min.js"></script>
<!-- Just to make our placeholder images work. Don't actually copy the next line! -->
<script src="<?=base_url()?>build/bower/bootstrap-3.3.7/assets/js/vendor/holder.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="<?=base_url()?>build/bower/bootstrap-3.3.7/assets/js/ie10-viewport-bug-workaround.js"></script>

<?php Template::load_js('footer');?>
</body>
</html>
