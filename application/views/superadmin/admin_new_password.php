<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="">
    <link rel="shortcut icon" href="img/favicon.png">
    <title><?php echo SITE_NAME ?>| New Password</title>
     <link href='https://fonts.googleapis.com/css?family=Lato:400,100,100italic,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
    <!-- Bootstrap core CSS -->
    <link href="<?php echo _THEME_URL?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo _THEME_URL?>css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="<?php echo _THEME_URL?>plugin/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="<?php echo _THEME_URL?>css/style.css" rel="stylesheet">
    <link href="<?php echo _THEME_URL?>css/style-responsive.css" rel="stylesheet" />
</head>
<body class="lock-screen">
    <style type="text/css">
      .footer-menu li{
          float: left;
          padding-right: 5px;
          border-right: 1px solid white;
          margin-right: 10px;
        }
      .copyright-inner1{
          background-color: #1A1D22;
          padding: 8px;
          border-bottom: 3px solid #1e90ff;
          position: absolute;
          width: 100%;
          bottom: 0px;
      }
      .login_user_name {
          margin: 0px 0px 10px 0px;
          font-size: 16px;
          font-weight: bold;
      }
    </style>
<!-- container -->
<div class="container">
  <div class="row">
    <div class="loginlogo text-center">
      <a href="<?php echo base_url(); ?>">
        <img width="125" src="<?php echo base_url(); ?>assets/frontend/images/logo.png" alt="">
      </a>
      <h2>#board</h2>
      <p>Create New Password</p>
    </div>
    <div class="col-md-4 col-md-offset-4">
      <div class="panel panel-login">
        <div class="panel-body">
          <?php echo form_open(get_the_current_url(), array('class'=>'form-signin')); ?>
            <input type="hidden" name="user_type" id="user_type"  value="user">
            <div class="row">
              <div class="col-lg-12">
                <div id="user-form" >
                   <?php if($this->session->flashdata('msg_error')): ?>
                   <div class="text-center">                 
                    <span class="help-block btn btn-danger" style="width: 100%;">
                    <?php echo $this->session->flashdata('msg_error'); ?></span>                 
                  </div>
                  <?php endif; ?>
                  <div class="login-wrap"> 
                    <div class="login_user_name">
                     Create New Password
                    </div>
                    <input  name="password" type="password" class="form-control" placeholder="New Password">
                    <?php echo form_error('password')?>
                    <input  name="confpassword" type="password" class="form-control" placeholder="Confirm Password">
                    <?php echo form_error('confpassword')?>
                    <button class="btn btn-lock" type="submit">Update Password </button> 
                  </div>
              </div>
            </div>
          </form>             
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /container -->      
<!-- footer -->
<div class="clear-fix"></div>
    <section class="copyright-inner1">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
               <div class="footer-menu">
                  <ul>
                    <li><a href="<?php echo base_url('home'); ?>">Home</a></li> 
                    <li><a href="<?php echo base_url('privacy-policy'); ?>">Privacy Policy</a></li>
                    <li><a href="<?php echo base_url('contact-us'); ?>">Contact Us</a></li>
                    <li><a href="<?php echo base_url('faq'); ?>">FAQ</a></li>
                    <li><a href="<?php echo base_url('login'); ?>"  target="_blank"><span >Login</span></a></li>
                  </ul>
                </div>
                </div>
            </div>
        </div>
        <div class="scroll_top">
            <a href="#HOME"><i class="fa fa-angle-up"></i></a>
        </div>
    </section>  
<!-- /footer -->
<!-- js placed at the end of the document so the pages load faster -->
<script type="text/javascript">
    var base_url ='<?php echo base_url(); ?>'
</script>
<script src="<?php echo _THEME_URL?>js/jquery.js"></script>
<script src="<?php echo _THEME_URL?>js/bootstrap.min.js"></script>
<script src="<?php echo _THEME_URL?>js/front_validation.js"></script>
</body>
</html>
