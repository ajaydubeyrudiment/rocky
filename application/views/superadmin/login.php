<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo site_info('admin_title'); ?> | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo  ADMIN_THEAM_PATH ;?>bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo  ADMIN_THEAM_PATH ;?>bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo  ADMIN_THEAM_PATH ;?>bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo  ADMIN_THEAM_PATH ;?>dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo  ADMIN_THEAM_PATH ;?>plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <style type="text/css">
    .login-page, .register-page {
      background: url(<?php echo base_url(); ?>assets/admin/img/login-frant.jpg);
  }
  </style>
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page" >
<div class="login-box">
  <div class="login-logo">
    <a href="<?php echo base_url(); ?>">
      <img src="<?php echo base_url('assets/front/img/login-logo.png'); ?>" width="200" />
    </a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>
    <form  method="post" id="login_form" onsubmit="return false;">
      <div id="login_error_res"></div>
      <input  id="loginProcess" name="loginProcess" type="hidden" value="getUserLoggedIn">
      <div id="login_main">
        <div class="form-group has-feedback">
          <input type="text" id="username" name="email" class="form-control" placeholder="Email">
          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          <div id="username_error" class="text-danger"></div>
        </div>
        <div class="form-group has-feedback">
          <input type="password" id="password" name="password" placeholder="Password" class="form-control" placeholder="Password">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          <div id="password_error" class="text-danger"></div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-xs-4 col-xs-offset-8">
            <a onclick="adminLogin()" id="login_btn" class="btn btn-primary btn-block btn-flat">Sign In</a>
          </div>
          <!-- /.col -->
        </div>
      </div>
      <div id="forgot_password_main" class="hidden">
        <div class="form-group has-feedback">
          <input type="text" id="forgotEmail" name="forgotEmail" class="form-control" placeholder="Email">
          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          <div id="forgot_email_error" class="text-danger"></div>
        </div>
      </div>
    </form>
    <!-- <a href="javascript:void(0)" onclick="forgot_manu();" id="forgot_menu_link">
      I forgot my password
    </a> -->
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script type="text/javascript">
  var superadmin_url = "<?php echo base_url().ADMIN_DIR; ?>"
</script>
<script src="<?php echo  ADMIN_THEAM_PATH ;?>bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo  ADMIN_THEAM_PATH ;?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?php echo  ADMIN_THEAM_PATH ;?>plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo  ADMIN_THEAM_PATH ;?>js/backend_js_validation.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
