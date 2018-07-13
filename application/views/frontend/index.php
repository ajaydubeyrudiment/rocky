<div class="loader_profile_left">
   <img src="<?php echo base_url('assets/front/img/loader_img.gif') ?>">

</div>
<div class="container">
   <div class="wrapper">
      <div class="head-logo only-for-m text-center">
         <a href="<?php echo base_url(); ?>"><img src="<?php echo  FRONT_THEAM_PATH ;?>img/logo.jpg" style="width: 250px;" alt="LOGO"></a>
         <a href="javascript:void(0);" id="bar" class="pull-right bars"><i class="fa fa-bars"></i></a>
         <p>SignUp to see and share workout media, workout plans, clients plans and progression information with friends.</p>
       </div>
      <div class="row">
         <div class="col-md-4 col-sm-4 col-xs-12 col-lg-4 login" id="login_main">
            <div class="head-logo text-center">
               <a class="mm" href="<?php echo base_url(); ?>"><img src="<?php echo  FRONT_THEAM_PATH ;?>img/logo.jpg" style="width: 250px;" alt="LOGO"></a>
            </div>
            <input type="hidden" id="enter_key_funcation" value="userSignup">
            <form onsubmit="return false;" action="" method="post" id="signup_form">
               <div class="form-group">
                  <!-- <button class="btn btn-block fb" onclick="fblogin_user();"><span class="fa fa-facebook"></span> Log In with Facebook</button>
                     <button class="btn btn-block google" onclick="googleLogin();"><span class="fa fa-google"></span> Log In with Google</button> -->
                  <button class="btn btn-block fb" ><span class="fa fa-facebook"></span> Log In with Facebook</button>
                  <button class="btn btn-block google"><span class="fa fa-google"></span> Log In with Google</button>
                  <p><strong>OR</strong></p>
               </div>
               <?php msg_alert(); ?>
               <div id="signup_success_res"></div>
               <div class="form-group">
                  <input type="hidden" name="email_verified" id="email_verified">
                  <input type="text" class="form-control" placeholder="Full Name" id="signup_name" name="name">
                  <div id="signup_name_error" class="text-danger"></div>
               </div>
               <div class="form-group js_main_validate">
                  <input type="text" class="form-control" placeholder="User Name" id="signup_username" name="user_name">
                  <icon id="signup_username_sucess_icon" class="fa fa-check available" aria-hidden="true">
                  </i>
               </div>
               <div id="signup_username_error" class="text-danger"></div>
               <div class="form-group js_main_validate">
                  <input type="text" class="form-control" placeholder="Email" id="signup_user_email" name="email">
                  <icon id="signup_email_sucess_icon" class="fa fa-check" aria-hidden="true">
                  </i>
               </div>
               <div id="signup_user_email_error" class="text-danger"></div>
               <div class="form-group">
                  <input type="password" class="form-control" placeholder="Password" id="signup_password" name="password">
                  <div id="signup_password_error" class="text-danger"></div>
               </div>
               <div id="signup_cpassword_error" class="text-danger"></div>
               <div class="form-group">
                  <button onclick="userSignup();" class="btn btn-block signup" > Sign up</button>
               </div>
               <div class="terms">
                  <p class="">By signing up, you agree to our<a href="javascript:void(0);"> Terms</a> & <a href="javascript:void(0);">Privacy Policy</a>.</p>
               </div>
            </form>
            <form onsubmit="return false;" action="" method="post" 
               id="display_login">
               <div class="form-group">
                  <!-- <button class="btn btn-block fb" onclick="fblogin_user();"><span class="fa fa-facebook"></span> Log In with Facebook</button>
                     <button class="btn btn-block google" onclick="googleLogin();"><span class="fa fa-google"></span> Log In with Google</button> -->
                  <button class="btn btn-block fb" ><span class="fa fa-facebook"></span> Log In with Facebook</button>
                  <button class="btn btn-block google"><span class="fa fa-google"></span> Log In with Google</button>
                  <p><strong>OR</strong></p>
               </div>
               <?php msg_alert(); ?>
               <div id="login_error_res"></div>
               <div class="form-group">
                  <input type="text" class="form-control" placeholder="Username or Email or Mobile Number" id="username">
                  <div id="username_error" class="text-danger"></div>
               </div>
               <div class="form-group">
                  <input type="password" class="form-control" placeholder="Password" id="password">
                  <div id="password_error" class="text-danger"></div>
               </div>
               <div class="form-group">
                  <button onclick="userLogin();" class="btn btn-block signup"> Log In</button>
               </div>
               <div class="terms">
                  <p class=""><a href="<?php echo base_url('home/forgot_password');?>"> Forgot Password ?</a></p>
               </div>
            </form>
            <div class="have_an_account text-center" id="have_account">
               <p>Have an account? <a href="javascript:void(0);" id="log_in">Log In</a></p>
            </div>
            <div class="have_an_account text-center" id="no_account">
               <p>Don't have an account? <a href="javascript:void(0);" id="sign_up">Sign up</a></p>
            </div>
            <div class="get_app text-center">
               <p>Get the app</p>
               <div class="get_app_download">
                  <a href="javascript:void(0);"><img src="<?php echo  FRONT_THEAM_PATH ;?>img/app_store.png" alt="App Store"></a>
                  <a href="javascript:void(0);"><img src="<?php echo  FRONT_THEAM_PATH ;?>img/google_play.png" alt="Google play"></a>
               </div>
            </div>
            <footer class="footer">
              <div class="">
                <div class="">
                  <ul class="list-inline pull-right">
                    <li><a href="javascript:void(0);">About</a></li>
                    <li><a href="javascript:void(0);">Help</a></li>
                    <li><a href="javascript:void(0);">Terms of Services</a></li>
                    <li><a href="javascript:void(0);">Privacy</a></li>
                    <li><a href="javascript:void(0);">Cookies</a></li>
                  </ul>
                </div>
                <div class="">
                  <p> @ copyright Loreme Impsum</p>
                </div>
              </div>
              <div class="clearfix"></div>
            </footer>
         </div>
         <div class="col-md-8 col-sm-8 col-xs-12 col-lg-8 right_portfolio pull-right">
            <div class="page-rightside-title-text">
               <p>SignUp to see and share workout media, workout plans, clients plans and progression information with friends.</p>
            </div>
            <div class="row blog_picture scollWind" id="result_list">
               <?php 
                  include('result_listing.php');
                  ?>
               <div id="load_datas"></div>
            </div>
            <div class="clearfix"></div>
            <form id="pagination_frm">
               <input type="hidden" id="lastCounter" name="lastCounter" value="<?php echo !empty($lastRecord)?$lastRecord:'';?>">
               <input type="hidden" id="currentCounter" name="currentPage" value="7">
            </form>
         </div>
      </div>
   </div>
</div>

<div class="row">
      <footer class="footer foot_mobile">
              <div class="col-md-12">
                <div class="col-sm-4 col-xs-12">
                  <p> @ copyright Loreme Impsum</p>
                </div>
                <div class="col-sm-8 col-xs-12">
                   <ul class="list-inline pull-right">
                      <li><a href="javascript:void(0);">About</a></li>
                      <li><a href="javascript:void(0);">Help</a></li>
                      <li><a href="javascript:void(0);">Terms of Services</a></li>
                      <li><a href="javascript:void(0);">Privacy</a></li>
                      <li><a href="javascript:void(0);">Cookies</a></li>
                  </ul>
                </div>
              </div>
              <div class="clearfix"></div>
            </footer>
</div>

    <!--     <footer class="footer">
              <div class="">
                <div class="">
                  <ul class="list-inline pull-right">
                    <li><a href="javascript:void(0);">About</a></li>
                    <li><a href="javascript:void(0);">Help</a></li>
                    <li><a href="javascript:void(0);">Terms of Services</a></li>
                    <li><a href="javascript:void(0);">Privacy</a></li>
                    <li><a href="javascript:void(0);">Cookies</a></li>
                  </ul>
                </div>
                <div class="">
                  <p> @ copyright Loreme Impsum</p>
                </div>
              </div>
              <div class="clearfix"></div>
            </footer> -->

