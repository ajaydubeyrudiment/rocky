<?php 
if(!empty($comments)){
  foreach($comments as $comment){
    if(!empty($comment->profile_pic_file)&&file_exists('assets/uploads/users/thumbnails/'.$comment->profile_pic_file)){
      $userPics = base_url().'assets/uploads/users/thumbnails/'.$comment->profile_pic_file;   
    }else{
      $userPics = base_url().site_info('default_user_pic');  
    }
    $userNameIDS = base_url().'user/profile/';
    if(!empty($comment->user_name)){
      $userNameIDS .= str_replace(' ', '-', $comment->user_name);
    }
    if(!empty($comment->user_id)){
      $userNameIDS .= '-'.$comment->user_id.'?user_id='.$comment->user_id;
    }
    if($comment->user_id==user_id()){
      $userNameIDS =base_url().'user/dashboard';
    }      
    ?>
    <div class="media">
      <div class="media-left">
        <a href="<?php echo $userNameIDS; ?>">
          <img class="media-object" src="<?php echo $userPics;?>" alt="comment image">
        </a>
      </div>
      <div class="media-body">
        <a href="<?php echo $userNameIDS; ?>">
          <h4>
            <?php echo !empty($comment->user_name)? ucwords($comment->user_name):''; ?>
          </h4>
      </a>
        <p><?php echo !empty($comment->comments)? $comment->comments:''; ?></p>
        <span><?php echo !empty($comment->created_date)?cal_times($comment->created_date):''; ?></span>
      </div>
    </div>
<?php } 
  }
?>