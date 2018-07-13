<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/frontend/images/fevicon.png">
    <title><?php echo SITE_NAME ?> | login</title>

     <link href='http://fonts.googleapis.com/css?family=Lato:400,100,100italic,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
    <!-- Bootstrap core CSS -->
    <link href="<?php echo _THEME_URL?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo _THEME_URL?>css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
   <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="<?php echo _THEME_URL?>css/style.css" rel="stylesheet">
    <link href="<?php echo _THEME_URL?>css/style-responsive.css" rel="stylesheet" />
</head>
<body class="lock-screen">
<style type="text/css">  
.footer-menu li{
    border-right: 1px solid rgba(154, 154, 154, 0.49);
    margin-right: 10px;
    display: inline-block;
    padding-right: 10px;
}
.copyright-inner1{
      background-color: #1A1D22;
    padding: 8px;
    border-bottom: 1px solid #1e90ff;
    position: absolute;
    width: 100%;
    bottom: 0px;
}
input#password_radio_button {
    float: left;
    width: 20px;
    margin-left: 30px;
    margin: 0px 5px 15px 0px !important;
    padding: 0px !important;
    height: 20px !important;
}
input#opt_radio_button {
    float: left;
    width: 20px;
    margin-left: 30px;
    margin: 0px 5px 15px 0px !important;
    padding: 0px !important;
    height: 20px !important;
}
.login_user_name {
    margin: 0px 0px 10px 0px;
    font-size: 16px;
    font-weight: bold;
}
a#userLogin, a#adminLogin, a.opt_password_link,a.opt_password_link:hover {
    color: #FFF !important;
}
.user-submit-btn {
    margin: 20px 0px 0px 0px;
}
.userReset {
  color: #FFF !important;
  background-color: red !important;
}
.user_password_main, .user_password_radio_btn, 
.user_otp_radio_btn, #userLogin,.custom_error,#admin-form,.userReset{ 
  display: none; 
} 

/*img.loginLoader {
    position: absolute;
    top: 270px;
    left: 580px;
    z-index: 99999;
    width: 330px;
    height: 240px;

}*/
/*.loginLoader*/
form .error {
    color: #e20f00;
    margin-top: -20px;
}
#admin-form{
  display: block;
}

.panel-login{
  position: relative;
}

.login_preloader_wrp {
  position: absolute;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: #EDEDEE;
  line-height: 100%;
  text-align: center;
  z-index: 99;
  display: none;
}

.login_preloader{
  width: 80px;
  position: absolute;
  top: 50%;
  margin-top: -40px;
  left: 50%;
  margin-left: -37.5px;
}

</style>
<?php
    $passwordMethod =''; 
    $passwordCheck  = 'checked';
    $otpCheck       = '';
    $passwordMethod = 'password';
    $user_type = 'user'; 
    $user_active = 'active';
  ?>
<!-- container -->

  <div class="container">
  <div class="row">    
    <div class="loginlogo text-center">
       <a href="<?php echo base_url(); ?>">
        <img width="125" src="<?php echo LOGO_URL ?>" alt="">
       </a>     
       <p>Kindly enter your login details</p>
    </div>
   <div class="col-md-4 col-md-offset-4">
    <?php msg_alert(); ?> 
    <div class="panel panel-login">
    
    <div class="login_preloader_wrp">
      <img src="<?php echo base_url('assets/admin/img/loginPreLoader.gif'); ?>" class="login_preloader">
    </div>  
    <div class="panel-body">
    <div class="message_box"></div>
    <form action="" method="post" id="login_form" class="form-signin">
      <input type="hidden" id="loginProcess" value="getAdminLogin">
      <input type="hidden" name="submit" value="submit">
      <input type="hidden" name="user_type" id="user_type"  value="admin">
      <div class="row">        
  <div id="admin-form" >
    <div class="login-wrap">     
        <label id="email1-label">            
        <input id="emaillable" type="text" class="form-control adminEmail" placeholder="Email" autofocus  name="email">
        </label>
         <div class="admin_email_error custom_error error"></div>
        <?php echo form_error('email')?>
        <label id="password-label">
        <input id="loginlabel"  name="password" type="password" class="form-control adminPassword"  placeholder="Password">
        </label>
        <div class="admin_password_error custom_error error"></div>
       <?php echo form_error('password')?>
        <a href="javascript:void(0)" id="adminLogin"  class="btn btn-lock">Sign in</a>             
       <div class="forgot-pass"><!-- <a href="#" id="forgot-form-link" >Forgot Password?</a> --> </div>
    </div>
  </div>
      </div>
    </form>
    <div id="forgotPasswordForm" >
   <?php if($this->session->flashdata('msg_info')): ?>
    <div class="text-center">
     <span class="help-block btn btn-info" style="width: 100%;">
        <?php echo $this->session->flashdata('msg_info'); ?>
     </span>    
    </div>
    <?php endif; ?>
  <?php if($this->session->flashdata('msg_error')): ?>
    <div class="text-center">
     <span class="help-block btn btn-danger" style="width: 100%;">
        <?php echo $this->session->flashdata('msg_error'); ?>
     </span>    
    </div>
    <?php endif; ?>
    <div class="login-wrap">
      <div  class="login_user_name">Enter your registered email, if you have forgot password </div>
      <label id="email1-label">
        <input type="text" id="forgotEmail" class="form-control" placeholder="Enter Registered Email" autofocus  name="forgotEmail">
      </label>
      <div class="forgot_email_error custom_error error"></div>

      <a href="javascript:void(0)" class="btn btn-lock forgot_mail">Submit</a>     
    </div>
  </div>
    </div>
</div>
</div>
</div>

  </div>
  <div class="clear-fix"></div>
      <!-- <section class="copyright-inner1">
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
    </section>  -->

    <!-- END FOOTER -->
<script type="text/javascript">
    var base_url ='<?php echo base_url(); ?>';
</script>
<script src="<?php echo _THEME_URL?>js/jquery.js"></script>
<script src="<?php echo _THEME_URL?>js/bootstrap.min.js"></script>
<script src="<?php echo _THEME_URL?>js/front_validation.js"></script>
</body>
</html>
