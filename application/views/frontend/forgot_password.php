<div class="wrapper">
   <div class="container">
      <div class="head-logo text-center">
         <a href="<?php echo base_url(); ?>">
            <img src="<?php echo  FRONT_THEAM_PATH ;?>img/logo.jpg" alt="LOGO" style="width: 250px;">
         </a>
         <p>SignUp to see and share workout media, workout plans, clients plans and progression information with friends.</p>
      </div>
      <div class="row">
         <div class="container">
            <?php msg_alert(); ?>
            <div class="col-md-offset-3 col-md-6 forgot_password">
               <h1>Reset Password</h1>
               <p>Enter your Roky userame or email address linked to your account to receive a reset link in your email.</p>
               <form action="" method="post" onsubmit="return checkCapcha();">
                  <div class="form-group ">
                     <input type="text" class="form-control" id="email_id" value="<?php if(set_value('email')){echo set_value('email');}?>" placeholder="Email or Username" name="email">
                     <div id="email_error" class="text-danger"></div>
                     <?php echo form_error('email'); ?>
                     <!-- <div class="form-group"><br/>
                        <h3>Are you a robot?</h3><br/>
                        <div id="captcha_container"></div>
                        <input type="hidden" id="capcha_status" value="no">
                        <div id="capcha_error" class="text-danger"></div>
                     </div> -->
                     <input type="submit" class="btn btn-info btn-md" value="Reset Password" name="submit"><br/><br/>
                  </div>
               </form>
            </div>
         </div>      
      </div>
   </div>
</div>
 <script type='text/javascript'>
   var captchaContainer = null;
   var loadCaptcha = function() {
     captchaContainer = grecaptcha.render('captcha_container', {
       'sitekey' : '6Ld6xTcUAAAAAEasNXlKM4DNEjfVI3lhXBTb5hBc',
       'callback' : function(response) {
        // alert(response);
        $('#capcha_status').val('yes');
       }
     });
   };
  function checkCapcha(){
    var email_id      = $('#email_id').val();
    var capcha_status = $('#capcha_status').val();
    var error         = 'no';
    if(email_id==''){
      $('#email_error').show().html('The email or user name is required');
      error  = 'yes';    
    }else{
       $('#email_error').hide();
    }
    if(capcha_status=='no'){
      $('#capcha_error').show().html('The capcha is required');      
      error  = 'yes';
    }else{
      $('#capcha_error').hide();
     
    }
    if(error=='yes'){
       return false;
     }else{
       return true;
     }
  }
 </script>
<script src="https://www.google.com/recaptcha/api.js?onload=loadCaptcha&render=explicit" async defer></script> 