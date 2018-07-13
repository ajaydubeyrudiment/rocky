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
        <li  role="presentation" class="" id="Images_tab"><a title="Images" href="#Images" onclick="tabMenu('Images');" aria-controls="Images" role="tab" data-toggle="tab"><span><img src="<?php echo base_url(); ?>assets/front/img/icon/profile_icon1.png"></span><br></a></li>
        <li  role="presentation" id="Recipes_tab"><a title="Recipes" href="#Recipes" onclick="tabMenu('Recipes');"  aria-controls="messages" role="tab" data-toggle="tab"><span><img src="<?php echo base_url(); ?>assets/front/img/icon/profile_icon2.png"></span><br></a></li>
        <li  role="presentation" id="Blogs_tab"><a title="Blogs" href="#Blogs" onclick="tabMenu('Blogs');"  aria-controls="Blogs" role="tab" data-toggle="tab"><span><img src="<?php echo base_url(); ?>assets/front/img/icon/profile_icon3.png"></span><br></a></li>
        <?php if(empty($userID)){?>
          <li role="presentation" id="Likes_tab"><a href="#Likes"  title="Likes" onclick="tabMenu('Likes');"  aria-controls="Likes" role="tab" data-toggle="tab"><span><img src="<?php echo base_url(); ?>assets/front/img/icon/profile_icon4.png"></span><br></a></li>
          <li role="presentation" id="Comments_tab"><a title="Comments" href="#Comments" onclick="tabMenu('Comments');"  aria-controls="Comments" role="tab" data-toggle="tab"><span><img src="<?php echo base_url(); ?>assets/front/img/icon/profile_icon5.png"></span><br></a></li>
          <li role="presentation" id="Bookmarks_tab"><a href="#Bookmarks" title="Bookmarks" onclick="tabMenu('Bookmarks');"  aria-controls="Bookmarks" role="tab" data-toggle="tab"><span><img src="<?php echo base_url(); ?>assets/front/img/icon/profile_icon6.png"></span><br></a></li>
        <?php }?>
      </ul>
      <div class="tab-content user_profile_view_rok">
        <div class="profile_follow_feed tab_sec">
          <div class="feed_right">      
            <div class="blog_picture" id="alldata_profiles">
              <?php include('result_listing.php'); ?>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
        <div role="tabpanel" class="tab-pane tab_sec fade in active" style="display: none;" id="Images">
          <div id="user_added_images"></div>
          <?php if(empty($userID)){?>
           <div class="add_recipie text-center col-md-12 clearfix">
              <a class="btn btn-block" href="<?php echo base_url('user/imagePost'); ?>">
                <i class="fa fa-plus"></i>
              </a>
           </div>
          <?php }?>
           <div class="clearfix"></div>
        </div>                
        <div role="tabpanel" class="tab-pane tab_sec fade" style="display: none;" id="Recipes">
           <div id="user_added_recipes"></div>
           <?php if(empty($userID)){?>
             <div class="add_recipie text-center col-md-12 clearfix">
                <a class="btn btn-block" href="<?php echo base_url('user/addRecipe'); ?>"> 
                  <i class="fa fa-plus"></i>
                </a>
             </div>
           <?php }?>
           <div class="clearfix"></div>
        </div>
        <div role="tabpanel" class="tab-pane tab_sec fade" style="display: none;" id="Blogs">
          <div class="feed_right">                         
            <div id="user_added_blogs"></div>
            <?php if(empty($userID)){?>
              <div class="add_recipie text-center col-md-12 clearfix">
                <a class="btn btn-block" href="<?php echo base_url('user/addBlog'); ?>"> 
                  <i class="fa fa-plus"></i>
                </a>
             </div>
           <?php }?>
            <div class="clearfix"></div>
          </div>
        </div>
        <div role="tabpanel" class="tab-pane tab_sec fade" id="Likes">
          <div class="feed_right">
            <div class="blog_picture" id="filterLikesBlogImges"></div>
            <div class="clearfix"></div>
          </div>   
        </div>
        <div role="tabpanel" class="tab-pane tab_sec fade" id="Comments"> 
          <div class="comments clearfix">
            <?php 
            if(!empty($comments)){
              foreach($comments as $row){?>
                <p>
                 <a href="javascript:void(0);" data-toggle="modal" data-target="#imageDetails" onclick="imageDetails('<?php echo $row->recipe_blog_image_id; ?>');" class="model_link">
                  <?php echo  character_limiter($row->comments, 250); ?>
                 <span class="pull-right">
                  <?php 
                  if(!empty($row->image_name)&&file_exists('assets/uploads/recipeBlogImages/'.$row->image_name)){
                    $files      = explode('.',$row->image_name);
                    $file_name  = strtolower(end($files));
                    if($file_name=='png'||$file_name=='jpeg'||$file_name=='jpg'){
                       echo '<img alt="blog" width="30" src="'.base_url().'assets/uploads/recipeBlogImages/'.$row->image_name.'">';
                    }else if($file_name=='mp4'){
                       echo '<video width="30"><source src="'.base_url().'assets/uploads/recipeBlogImages/'.$row->image_name.'" type="video/mp4"></video>';
                    }
                  }else if(!empty($row->title)){
                    echo  character_limiter($row->title, 30);
                  }
                ?>
                </span>
                </a>
              </p>
              <?php 
              }
            }
            ?>
          </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="Bookmarks"> 
          <div class="feed_right">
            <div class="blog_picture" id="filterBookMarkBlogImges"></div>
            <div class="clearfix"></div>
          </div>   
        </div>
      </div>
    <?php }?>
</div>