<div class="col-md-9">
  <div class="feed_right">
     <div class="goal_setter">
        <h2>Likes</h2>
        <hr />
        <div class="col-md-12">
          <?php 
          if(!empty($rows)){
            foreach($rows as $row){
              $userNameIDS = base_url().'user/profile/';
              if(!empty($row->user_name)){
                $userNameIDS .= str_replace(' ', '-', $row->user_name);
              }
              if(!empty($row->user_id)){
                $userNameIDS .= '-'.$row->user_id.'?user_id='.$row->user_id;
              }
              if($row->user_id==user_id()){
                $userNameIDS = base_url().'user/dashboard';
              }
              ?>
              <div class="row follow_main">
                <div class="col-md-8 col-xs-10 follow_left">
                  <div class="follow_left_img">
                    <a href="<?php echo $userNameIDS ?>">
                      <img src="<?php echo (!empty($row->profile_pic)&&file_exists('assets/uploads/users/thumbnails/'.$row->profile_pic))?base_url('assets/uploads/users/thumbnails/'.$row->profile_pic):base_url().site_info('default_user_pic'); ?>" class="img-rounded" alt="<?php echo (!empty($row->user_name))?$row->user_name:'';?>">
                    </a>
                  </div>
                  <p>
                    <a href="<?php echo $userNameIDS; ?>">
                      <?php echo (!empty($row->user_name))?ucwords($row->user_name):'';?>
                    </a> 
                    liked your <?php echo (!empty($row->type))?ucwords($row->type):'';?>
                  </p>
                </div>
                <div class="col-md-4 col-xs-2 follow_right text-right">
                  <a href="javascript:void(0);" data-toggle="modal" data-target="#imageDetails" onclick="imageDetails('<?php echo $row->recipe_blog_image_id; ?>');" class="model_link">
                    <?php 
                    if(!empty($row->image_name)&&file_exists('assets/uploads/recipeBlogImages/'.$row->image_name)){
                      $files      = explode('.',$row->image_name);
                      $file_name  = strtolower(end($files));
                      if($file_name=='png'||$file_name=='jpeg'||$file_name=='jpg'){
                         echo '<img alt="blog" width="30" src="'.base_url().'assets/uploads/recipeBlogImages/'.$row->image_name.'">';
                      }else if($file_name=='mp4'){
                         echo '<video class="video_file" width="30"><source src="'.base_url().'assets/uploads/recipeBlogImages/'.$row->image_name.'" type="video/mp4"></video>';
                      }
                    }else if(!empty($row->title)){
                      echo  character_limiter($row->title, 30);
                    }
                  ?>
                  </a>
                </div>
              </div>  
            <?php  
            }
          } ?>
        </div>
     </div>
  <div class="clearfix"></div>  
  </div>
</div>      