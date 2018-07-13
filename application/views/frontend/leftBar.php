<section class="feed">
   <div class="container">
      <div class="row">
         <?php $user = user_info();?>
         <div class="col-md-3 ">
            <div class="row feed_left">
            <div class="username">
               <?php 
                  if(!empty($user->profile_pic)&&file_exists('assets/uploads/users/thumbnails/'.$user->profile_pic)){
                     $profile_pic = 'assets/uploads/users/thumbnails/'.$user->profile_pic
                  }else{
                     $profile_pic = base_url().site_info('default_user_pic');
                  }
               ?>
               <img src="<?php echo $profile_pic; ?>" class="img-rounded">
               <div class="username_content">
                  <h4><?php if(!empty($user->user_name)) echo $user->first_name; ?></h4>
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod </p>
               </div>
               <div class="follow text-center">
                  <button class="btn btn-md">Follow</button>
               </div>
               <div class="profile_status">
                  <ul class="list-inline">
                     <li><a href="javascript:void(0);"><strong>3.5 k</strong><span>Following</span></a></li>
                     <li><a href="javascript:void(0);"><strong>3.45 k</strong><span>Followers</span></a></li>
                  </ul>   
               </div>
               <div class="follow text-center">
                  <button class="btn btn-md editt">Edit Profile</button>
               </div>
            </div>
            <hr />
            <div class="metrics">
               <h4>Metrics</h4>
               <p>Joined date : 22 oct 2017</p>
               <p>Age : 25</p>
               <div class="col-md-6 m1">
                  <div class="join">
                     <img src="img/img-7.jpg" alt="join">
                     <h4>Join Height : 5'9"</h4>
                     <h4>Join Weight : 170lbs</h4>
                  </div>
               </div>
               <div class="col-md-6 m2">
                  <div class="join">
                     <img src="img/img-7.jpg" alt="join">
                     <h4>Height : 5'9"</h4>
                     <h4>Weight : 170lbs</h4>
                  </div>
               </div>
               <div class="metrics_tag">
                  <a href="javascript:void(0);">Diet Plan</a>
                  <a href="javascript:void(0);">Workout Plan</a>
                  <a href="javascript:void(0);">Progress</a>
                  <a href="javascript:void(0);">Previous Workout Plan</a>
                  <a href="javascript:void(0);">Previous Diet Plan</a>
               </div>
               <div class="clearfix"></div>
            </div>
         </div>
      </div>