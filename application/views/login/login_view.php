<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Welcome To | IMS </title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="<?php echo base_url();?>assets/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?php echo base_url();?>assets/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="<?php echo base_url();?>assets/plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Morris Chart Css-->
    <link href="<?php echo base_url();?>assets/plugins/morrisjs/morris.css" rel="stylesheet" />

    <!-- JQuery DataTable Css -->
    <link href="<?php echo base_url();?>assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

    <!-- Custom Css -->
    <link href="<?php echo base_url();?>assets/adminbsb/css/style.css" rel="stylesheet">

    <!-- adminbsb Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?php echo base_url();?>assets/adminbsb/css/themes/all-themes.css" rel="stylesheet" />

    <!-- Jquery Core Js -->
    <script src="<?php echo base_url();?>assets/plugins/jquery/jquery.min.js"></script>    
    <script src="<?php echo base_url();?>assets/custom/validate_master.js"></script>

    <!-- Alertify -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/alertify/alertify.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/alertify/themes/default.min.css">
</head>

<body class="login-page">
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);">Inventory <b>System</b></a>
            <small>Admin BootStrap Based - Material Design</small>
        </div>
        <div class="card">
            <div class="body">
                <form id="login-form" method="POST">
                    <div class="msg">Sign in to start your session</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" id="username" data-label="#err-username" class="form-control" name="username" placeholder="Username" autofocus>
                        </div>
                        <span class="cd-error-message font-bold col-pink" id="err-username"></span>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" id="password" data-label="#err-password" class="form-control" name="password" placeholder="Password">
                        </div>
                        <span class="cd-error-message font-bold col-pink" id="err-password"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-8 p-t-5">                            
                        </div>
                        <div class="col-xs-4">
                            <button class="btn btn-block bg-pink waves-effect" id="btn-login">SIGN IN</button>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>    

    <!-- Bootstrap Core Js -->
    <script src="<?php echo base_url();?>assets/plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="<?php echo base_url();?>assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="<?php echo base_url();?>assets/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo base_url();?>assets/plugins/node-waves/waves.js"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="<?php echo base_url();?>assets/plugins/jquery-countto/jquery.countTo.js"></script>

    <!-- Morris Plugin Js -->
    <script src="<?php echo base_url();?>assets/plugins/raphael/raphael.min.js"></script>
    <script src="<?php echo base_url();?>assets/plugins/morrisjs/morris.js"></script>

    <!-- ChartJs -->
    <script src="<?php echo base_url();?>assets/plugins/chartjs/Chart.bundle.js"></script>

    

    <!-- Sparkline Chart Plugin Js -->
    <script src="<?php echo base_url();?>assets/plugins/jquery-sparkline/jquery.sparkline.js"></script>

    <!-- Custom Js -->
    <!-- <script src="<?php echo base_url();?>assets/js/pages/tables/jquery-datatable.js"></script> -->

    <!-- Demo Js -->
    <script src="<?php echo base_url();?>assets/adminbsb/js/demo.js"></script>
    <!-- Alertify -->
    <script src="<?php echo base_url();?>assets/plugins/alertify/alertify.min.js"></script>

    <script>
        $(function() {
            var baseurl = "<?php echo site_url();?>/";            
            function validate() {
                var err = 0;

                if (!$('#username').validateRequired()) {
                    err++;
                }
                if (!$('#password').validateRequired()) {
                    err++;
                }

                if (err != 0) {
                    return false;
                } else {
                    return true;
                }
            }            

            var loginEvent = function(e) {
                if (validate()) {
                    var formData = new FormData();
                    formData.append("username", $("#username").val());
                    formData.append("password", $("#password").val());

                    $(this).saveData({
                        url: "<?php echo site_url('login/doLogin')?>",
                        data: formData,
                        locationHref: "<?php echo site_url('report')?>",
                        hrefDuration : 1000
                    });
                }
                e.preventDefault();
            };
            
            // SAVE DATA TO DB
            $('#btn-login').click(loginEvent);
            $("#login-form").on("submit", loginEvent);            
        });
    </script>
</body>

</html>