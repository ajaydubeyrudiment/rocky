<div class="col-md-9 f_r_user">
  <?php
  if(!empty($userID)){
    $user = user_info($userID);
    //echo get_all_count('follow_request', array('receiver_id'=>$userID, 'sender_id'=>user_id(), 'accepted_status'=>1));
    //echo $this->db->last_query(); 
    if($user->privacy==2 && get_all_count('follow_request', array('receiver_id'=>$userID, 'sender_id'=>user_id(), 'accepted_status'=>1))==0){
      $privateAccountNotAccess = 1;
    }
  }
  if(!empty($privateAccountNotAccess)){?>
    <div class="text-center"><br/><br/><br/>
      <h3>This Account is Private</h3><br/><br/>
      <p>Follow to see their photos, videos, blogs and Recipes.</p>
    </div>
  <?php  }else{?>
      <form id="blog_recepe_filters">
         <input type="hidden" name="user_id" value="<?php echo !empty($userID)?$userID:user_id();?>" id="profile_user_id">
         <input type="hidden" name="type" value="" id="type_id">
      </form>
      <ul class="nav nav-justified feed_right_user_list" role="tablist">
        <li role="presentation" class="active" id="Images_tab"><a href="#usersearch"  aria-controls="Images" role="tab" data-toggle="tab">Username</a></li>
        <li role="presentation" id="Recipes_tab"><a href="#hashtag"  aria-controls="messages" role="tab" data-toggle="tab">Hashtags</a></li>
        <li role="presentation" id="Blogs_tab"><a href="#content" aria-controls="Blogs" role="tab" data-toggle="tab">Content</a></li>   
      </ul>
      <div class="tab-content user_profile_view_rok">        
        <div role="tabpanel" class="tab-pane tab_sec fade in active feed" id="usersearch">
          <div id="user_added_images"></div>  
          <div class="feed_right">
            <div class="goal_setter">
              <hr />
              <?php 
              //echo '<pre> '; print_r($users);
              if(!empty($users)){
                foreach($users as $user){
                  if(!empty($user->profile_pic)&&file_exists('assets/uploads/users/thumbnails/'.$user->profile_pic)){
                     $profile_pic = base_url().'assets/uploads/users/thumbnails/'.$user->profile_pic;
                  }else{
                    $pics = site_info('default_user_pic');
                    if(!empty($pics)&&file_exists($pics)){
                      $profile_pic = base_url().$pics;
                    } 
                  }   
                  $userNameIDS = '';
                  if(!empty($user->user_name)){
                    $userNameIDS .= str_replace(' ', '-', $user->user_name);
                  }
                  if(!empty($user->id)){
                    $userNameIDS .= '-'.$user->id;
                  }               
                  ?>   
                  <a href="<?php echo base_url('user/profile/'.$userNameIDS); ?>">               
                    <div class="inbox_main clearfix">
                      <div class="inbox_main_img">
                        <img width="50" src="<?php echo  $profile_pic ;?>" alt="user image">
                      </div>
                      <h4><?php if(!empty($user->user_name)) echo strtolower($user->user_name); ?></h4>
                      <h6 id="left_bar_about"><?php if(!empty($user->first_name)) echo strtolower($user->first_name);if(!empty($user->last_name)) echo ' '.strtolower($user->last_name); ?></h6>
                    </div>
                  </a>
                <?php 
                }
              }else{
                echo '<div class="col-md-12 m_right text-center text-danger"><br/><br/><br/><br/><h2>No user records found</h2></div>';
              }
              ?>
            </div>
          </div>
           <div class="clearfix"></div>
        </div>                
        <div role="tabpanel" class="tab-pane tab_sec fade" id="hashtag">
           <div id="user_added_recipes"></div>
           <div class="blog_picture">
             <?php include('hash_result_listing.php'); ?>
           </div>
           <div class="clearfix"></div>
        </div>
        <div role="tabpanel" class="tab-pane tab_sec fade" id="content">
          <div class="feed_right">                         
            <div id="user_added_blogs"></div>
              <div class="blog_picture">
               <?php include('result_listing.php'); ?>
              </div>
            <div class="clearfix"></div>
          </div>
        </div>        
      </div>
    <?php }?>
</div>