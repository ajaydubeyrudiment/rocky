<div class="wrapper">
   <div class="container">
      <div class="head-logo text-center">
         <a href="<?php echo base_url(); ?>">
            <img src="<?php echo  FRONT_THEAM_PATH ;?>img/logo.jpg" alt="LOGO">
         </a>
         <p>SignUp to see and share workout media, workout plans, clients plans and progression information with friends.</p>
      </div>
      <div class="row">
         <div class="container">
            <div class="col-md-offset-3 col-md-6 forgot_password">
               <h1>Reset Password</h1>
               <p>Enter your Roky userame or email address linked to your account to receive a reset link in your email.</p>
               <form action="" method="post">
                  <div class="form-group ">
                     <input type="password" class="form-control" placeholder="Enter New Password" name="newpassword">
                     <?php echo form_error('newpassword'); ?>
                  </div>
                  <div class="form-group ">
                     <input type="password" class="form-control" placeholder="Enter Confirm Password" name="confpassword">
                     <?php echo form_error('confpassword'); ?>
                  </div>
                  <input type="submit" class="btn btn-info btn-md" value="Reset Password" name="submit">
               </form>
            </div>
         </div>      
      </div>
   </div>
</div>
<!-- <script type='text/javascript'>
   var captchaContainer = null;
   var loadCaptcha = function() {
     captchaContainer = grecaptcha.render('captcha_container', {
       'sitekey' : '6Ld6xTcUAAAAAEasNXlKM4DNEjfVI3lhXBTb5hBc',
       'callback' : function(response) {
         alert(response);
       }
     });
   };
 </script>
<script src="https://www.google.com/recaptcha/api.js?onload=loadCaptcha&render=explicit" async defer></script> -->