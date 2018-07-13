<style type="text/css">
  .slider-arrow { left: 0; right: 0;}
  #moreDesc, #ingredients, #recipie_instruction, #next_ingredients, #next_instruction,#blog_content,#next_b_containt {display: none;}
</style>
<?php 
function getFilesType($imageName=''){ 
  $imgesEx    = explode('.', $imageName);
  $fileExts = strtolower(end($imgesEx));
  if(!empty($fileExts)&&($fileExts=='png'||$fileExts=='jpeg'||$fileExts=='jpg')){
      return 'image';
  }else if(!empty($fileExts)&&($fileExts=='mp4')){
      return 'video';
  }else if(!empty($fileExts)){
      return $fileExts;
  }else{
       return '';
  }
}
$post_img = $imageName = $oldFileName = '';
  if(!empty($row->image_name)&&file_exists('assets/uploads/recipeBlogImages/'.$row->image_name)){
    $post_img = base_url().'assets/uploads/recipeBlogImages/'.$row->image_name;
  }else if(!empty($row->coverImage)&&file_exists('assets/uploads/recipeBlogImages/'.$row->coverImage)){
    $post_img = base_url().'assets/uploads/recipeBlogImages/'.$row->coverImage;
  } 
  if(!empty($row->image_name)&&file_exists('assets/uploads/recipeBlogImages/'.$row->image_name)){
    $imageName   = base_url().'assets/uploads/recipeBlogImages/'.$row->image_name;
    $oldFileName = $row->image_name;
  }else if(!empty($row->coverImage)&&file_exists('assets/uploads/recipeBlogImages/'.$row->coverImage)){
    $imageName = base_url().'assets/uploads/recipeBlogImages/'.$row->coverImage;
    $oldFileName = $row->coverImage;
  }
  $imagesArray[] = array('file_type'=>getFilesType($imageName), 'file_path'=>$imageName);
  if(!empty($imgesArrs)){
    foreach($imgesArrs as $imgesArr){
      if(!empty($imgesArr->image_name)&&file_exists('assets/uploads/recipeBlogImages/'.$imgesArr->image_name)){
        if($oldFileName != $imgesArr->image_name){
          $imageName      = base_url().'assets/uploads/recipeBlogImages/'.$imgesArr->image_name;
          $imagesArray[]  = array('file_type'=>getFilesType($imageName), 'file_path'=>$imageName);
        }
      }                
    }
} 
if($row->type=='recipe'||$row->type=='blog'||$row->type=='profile_post'){?>
<div class="col-md-8 col-lg-8 col-sm-12 col-xs-12 left-l">
  <div class="row">
    <div class="images_user_view recipie_discription" id="res_imgs">	  	
      <div class="col-xs-12">
        <div class="row text-center">
          <h3 class="title_recipe"><?php if(!empty($row->title)) echo ucwords($row->title);  ?></h3>
          <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
              <?php 
                if(!empty($imagesArray)&&count($imagesArray)>1){
                  $ips = 0;
                  foreach($imagesArray as $imageArray){
                    if($ips==0){
                     echo '<li data-target="#myCarousel" data-slide-to="'.$ips.'" class="active"></li>';
                    }else{
                      echo '<li data-target="#myCarousel" data-slide-to="'.$ips.'"></li>';
                    }
                    $ips++;
                  }
                }
              ?>
            </ol>
            <!-- Wrapper for slides -->
            <div class="carousel-inner">
              <?php 
              if(!empty($imagesArray)){
                $ips = 1;
                foreach($imagesArray as $imageArray){ ?>
                  <div class="item <?php if($ips==1){ echo 'active';} ?>">
                     <?php 
                     if($imageArray['file_type']=='image'){
                      echo '<img alt="blog" width="100%" src="'.$imageArray['file_path'].'">';
                    }else if($imageArray['file_type']=='video'){
                      echo '<video class="video_file"   controls><source src="'.$imageArray['file_path'].'" type="video/mp4"></video>';
                    }
                    $ips++;                
                  ?>
                  </div>
                <?php 
                }
              }
                ?>        
            </div>           
          </div>   
          <?php 
          if(!empty($imagesArray)&&count($imagesArray)>1){ ?>
            <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
              <span class="glyphicon   glyphicon-chevron-left" aria-hidden="true"></span>
              <span class="sr-only"></span>
            </a>
            <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
              <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
              <span class="sr-only"></span>
            </a>
            <?php 
          }?>       
        </div>
      </div>
    </div>
  </div> 
  <div class="row ingredients images_user_view recipie_discription" id="ingredients">
    <h3 class="title_recipe"><?php if(!empty($row->title)) echo ucwords($row->title);  ?></h3>
    <h5 class="ins">Ingredients</h5>
   <p> <?php if(!empty($row->ingredients)) echo $row->ingredients;  ?></p>
  </div> 
  <div class="row images_user_view recipie_instruction recipie_discription" id="recipie_instruction">
    <h3 class="title_recipe"><?php if(!empty($row->title)) echo ucwords($row->title);  ?></h3>
    <h5 class="ins">Instruction</h5>
    <p><?php if(!empty($row->instructions)) echo $row->instructions;  ?></p>
  </div> 
  <div class="row images_user_view recipie_instruction recipie_discription" id="blog_content">
    <h3 class="title_recipe"><?php if(!empty($row->title)) echo ucwords($row->title);  ?></h3>
    <h5 class="ins">Content</h5>
    <?php if(!empty($row->content)) echo $row->content;  ?>
  </div>  
</div>
<?php }
if($row->type=='image'){
	if(!empty($row->image_name)&&file_exists('assets/uploads/recipeBlogImages/'.$row->image_name)){?>
	<div class="col-md-8 col-lg-8 col-sm-12 col-xs-12">
	  <div class="row images_user_view">            
          <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
             <?php 
                if(!empty($imagesArray)&&count($imagesArray)>1){
                  $ips = 0;
                  foreach($imagesArray as $imageArray){
                    if($ips==0){
                     echo '<li data-target="#myCarousel" data-slide-to="'.$ips.'" class="active"></li>';
                    }else{
                      echo '<li data-target="#myCarousel" data-slide-to="'.$ips.'"></li>';
                    }
                    $ips++;
                  }
                }
              ?>
            </ol>
              <!-- Wrapper for slides -->
              <div class="carousel-inner">
                <?php 
                if(!empty($imagesArray)){
                  $ips = 1;
                  foreach($imagesArray as $imageArray){?>
                    <div class="item <?php if($ips==1){ echo 'active';} ?>">
                       <?php 
                        if($imageArray['file_type']=='image'){
                          echo '<img alt="blog" width="100%" src="'.$imageArray['file_path'].'">';
                        }else if($imageArray['file_type']=='video'){
                          echo '<video class="video_file"   controls><source src="'.$imageArray['file_path'].'" type="video/mp4"></video>';                      
                       }
                       $ips++;
                       ?>
                    </div>
                  <?php 
                  }
                }
               ?> 
              </div>
          </div>
          <?php if(!empty($imagesArray)&&count($imagesArray)>1){ ?>
           <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
              <span class="glyphicon   glyphicon-chevron-left" aria-hidden="true"></span>
              <span class="sr-only"></span>
            </a>
            <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
              <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
              <span class="sr-only"></span>
            </a>
          <?php }?>   
        </div>
      </div>	  
<?php } 
}?>
<div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
	<div class="row images_user_view_content">
	 	<div class="username">
	 		<?php 
	 			if(!empty($row->profile_pic)&&file_exists('assets/uploads/users/thumbnails/'.$row->profile_pic)){
					$user_img = base_url().'assets/uploads/users/thumbnails/'.$row->profile_pic;
				}else{
					$pics = site_info('default_user_pic');
          if(!empty($pics)&&file_exists($pics)){
            $user_img = base_url().$pics;
          } 					
				}
				if(!empty($user_img)){
					echo '<img src="'.$user_img.'" class="img-rounded">';
				}
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
	      <div class="username_content">
	        <h4>
            <a href="<?php echo $userNameIDS; ?>">
              <?php if(!empty($row->user_name)) echo $row->user_name;  ?>
            </a>
          </h4>
          <?php if(!empty($row->location)) echo '<i class="fa fa-map-marker"></i> '.ucwords($row->location);  ?>
	      </div>
        <?php 
        if($row->user_id!=user_id()){?>
	        <div class="follow text-center">
             <?php 
            if(get_all_count('follow_request', array('sender_id'=>user_id(), 'receiver_id'=>$row->user_id, 'accepted_status'=>1))>0){?>
              <button class="btn btn-md" onclick="followRequest('<?php echo !empty($row->user_id)?$row->user_id:0; ?>', '<?php echo !empty($viewUser)?$viewUser:user_id(); ?>');" id="postedFollowed">Unfollow</button>
            <?php 
            }elseif(get_all_count('follow_request', array('sender_id'=>user_id(), 'receiver_id'=>$row->user_id, 'accepted_status'=>4))>0){?>
              <button class="btn btn-md" id="postedFollowed">Request Sent</button>
            <?php }else{?>
              <button class="btn btn-md" onclick="followRequest('<?php echo !empty($row->user_id)?$row->user_id:0; ?>', '<?php echo !empty($viewUser)?$viewUser:user_id(); ?>');" id="postedFollowed">Follow</button>
            <?php }
            ?>  
	        </div>
        <?php }?>
        <div class="clearfix"></div>
	      <div class="bookmark_comment clearfix">
	        <ul class="list-inline">
	          <li>
              <a href="javascript:void(0);" title="Like" onclick="likePost('<?php echo !empty($row->id)?$row->id:0; ?>');" id="likeText">
                <?php 
                  if(get_all_count('likes', array('recipe_blog_image_id'=>$row->id, 'user_id'=>user_id()))>0){
                    echo '<i class="fa fa-thumbs-up" aria-hidden="true"></i><span id="post_likes_counters">'.get_all_count('likes', array('recipe_blog_image_id'=>$row->id)).'</span>';
                  }else{
                    echo '<i class="fa fa-thumbs-o-up" aria-hidden="true"></i><span id="post_likes_counters">'.get_all_count('likes', array('recipe_blog_image_id'=>$row->id)).'</span>';
                  }
                ?>                
              </a>
            </li>
	          <li>
              <a href="javascript:void(0);" title="comments">
                <i class="fa fa-comments-o" aria-hidden="true"></i>
                <span id="commentsCounts"><?php echo get_all_count('comments', array('recipe_blog_image_id'=>$row->id))?></span>
              </a>
            </li>
	          <li>
              <a href="javascript:void(0);" onclick="bookMarkPost('<?php echo !empty($row->id)?$row->id:0; ?>');" id="bookMarkPostText" title="bookmark">
                 <?php 
                  if(get_all_count('bookmark', array('recipe_blog_image_id'=>$row->id, 'user_id'=>user_id()))>0){
                    echo '<i class="fa fa-bookmark" aria-hidden="true"></i>';
                  }else{
                    echo '<i class="fa fa-bookmark-o" aria-hidden="true"></i>';
                  }
                ?>
              </a>
            </li>
	        </ul>                          
        </div>
        <div class="comment-box-blog"> 
          <p>
            <?php 
              $description = '';
              if(!empty($row->description)){ $description = $row->description;}              
              if(!empty($row->caption)){ $description = $row->caption;}              
              if(!empty($description)&&(strlen($description)>100)){
                 echo '<div id="lessDes">'.substr($description, 0 , 100).'<a  href="javascript:void(0);" id="less_Desclink" onclick="show_more_des();"> more</a></div>';                
                 echo '<div id="moreDesc">'.$description.'<a id="more_Desclink" href="javascript:void(0);" onclick="show_less_des();" > less</a> </div>'; 
              }else{
                echo $description; 
              }
             ?>
          </p>
        </div>
        <div id="post_errors" class="text-danger"></div> 
	  </div>
    <div class="comments_tread" id="commentsbox">
    
      <?php include('postComments.php'); ?>
    </div>
    <div class="comment_box"> 
      <div id="comments_res"></div>
      <div class="model-from"> 
        <form action="" onsubmit="return false;" id="comments_box_form">  
          <input type="hidden" name="post_id" value="<?php if(!empty($row->id)){ echo $row->id;} ?>">
          <div class="form-group">        
            <div class="input-group">
                <textarea rows="2" class="form-control" name="comments" id="comments" placeholder="Enter comment"></textarea>
                <a href="javascript:void(0);" onclick="submitComment();" class="input-group-addon">
                  <i class="fa fa-paper-plane" aria-hidden="true"></i>
                </a>
            </div>
            <div id="comments_error" class="text-danger"></div>
          </div>  
        </form> 
      </div>
    </div>
  </div>
</div>
<?php
if($row->type=='recipe'){?>
  <div class="slider-arrow" id="next_containt">
    <?php
    if(!empty($nextPost)){ ?>
      <a class="left " href="javascript:void(0);" onclick="imageDetails('<?php echo !empty($nextPost)?$nextPost:''; ?>');" data-slide="prev"> 
          <span class="glyphicon   glyphicon-chevron-left"></span> 
          <span class="sr-only"></span>
        </a> 
      <?php }?>
      <a class="right " href="javascript:void(0);" onclick="show_ingredients();" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span> 
        <span class="sr-only"></span>
      </a>
  </div> 
  <div class="slider-arrow" id="next_ingredients">
    <a class="left " href="javascript:void(0);" onclick="next_c ontaint();" data-slide="prev">
        <span class="glyphicon   glyphicon-chevron-left"></span> 
        <span class="sr-only"></span>
      </a> 
      <a class="right " href="javascript:void(0);" onclick="show_instruction();" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span> 
        <span class="sr-only"></span>
      </a>
  </div>
  <div class="slider-arrow" id="next_instruction">
    <a class="left " href="javascript:void(0);" onclick="show_i ngredients();" data-slide="prev">
        <span class="glyphicon   glyphicon-chevron-left"></span> 
        <span class="sr-only"></span>
      </a> 
      <?php 
      if(!empty($previewPost)){ ?>
        <a class="right " href="javascript:void(0);" onclick="imageDetails('<?php echo !empty($previewPost)?$previewPost:''; ?>');" data-slide="next">
          <span class="glyphicon glyphicon-chevron-right"></span>
          <span class="sr-only"></span>
        </a>
    <?php }?>
  </div>  
<?php }else if($row->type=='blog'){?>
  <div class="slider-arrow" id="pre_containt">
    <?php
    if(!empty($nextPost)){ ?>
      <a class="left " href="javascript:void(0);" onclick="imageDetails('<?php echo !empty($nextPost)?$nextPost:''; ?>');" data-slide="prev"> 
          <span class="glyphicon glyphicon-chevron-left"></span> 
          <span class="sr-only"></span>
        </a> 
      <?php }?>
      <a class="right " href="javascript:void(0);" onclick="show_containt();" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span> 
        <span class="sr-only"></span>
      </a>
  </div>
  <div class="slider-arrow" id="next_b_containt">
    <a class="left " href="javascript:void(0);" onclick="pre_containt();" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span> 
      <span class="sr-only"></span>
    </a> 
    <?php 
    if(!empty($previewPost)){ ?>
      <a class="right " href="javascript:void(0);" onclick="imageDetails('<?php echo !empty($previewPost)?$previewPost:''; ?>');" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
        <span class="sr-only"></span>
      </a>
  <?php }?>
</div>  
<?php }else{?>
  <div class="slider-arrow">
    <?php
    if(!empty($nextPost)){ ?>
      <a class="left " href="javascript:void(0);" onclick="imageDetails('<?php echo !empty($nextPost)?$nextPost:''; ?>');" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
        <span class="sr-only"></span>
      </a>
    <?php }
    if(!empty($previewPost)){ ?>
      <a class="right " href="javascript:void(0);" onclick="imageDetails('<?php echo !empty($previewPost)?$previewPost:''; ?>');" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
        <span class="sr-only"></span>
      </a>
    <?php }?>
  </div>
<?php }?>