<div class="col-md-9">
  <div class="feed_right">
     <div class="goal_setter">
        <h2>Follow Request</h2>
        <hr />
        <div class="col-md-12">
          <?php 
          if(!empty($rows)){
            foreach($rows as $row){              
              $userNameIDS = base_url().'user/profile';
              if(!empty($row->user_name)){
                $userNameIDS .= str_replace(' ', '-', $row->user_name);
              }
              if(!empty($row->user_id)){
                $userNameIDS .= '-'.$row->user_id;
              }
              if($row->user_id==user_id()){$userNameIDS = base_url().'user/dashboard';}
              ?>
              <div class="row follow_main" id="request_<?php echo !empty($row->id)?$row->id:'' ?>">
                <div class="col-md-8 follow_left">
                  <div class="follow_left_img">
                    <a href="<?php echo $userNameIDS; ?>">
                      <img src="<?php echo (!empty($row->profile_pic)&&file_exists('assets/uploads/users/thumbnails/'.$row->profile_pic))?base_url().'assets/uploads/users/thumbnails/'.$row->profile_pic:base_url().'assets/front/img/roky-logo.png'; ?>" class="img-rounded" alt="<?php echo !empty($row->user_name)?$row->user_name:'' ?>">
                    </a>
                  </div>
                  <p><a href="<?php echo $userNameIDS; ?>"><?php echo !empty($row->user_name)?$row->user_name:'' ?></a> Would like to follow you</p>
                </div>
                <div class="col-md-4 follow_right text-right">
                  <button onclick="changeStatusReq('<?php echo !empty($row->id)?$row->id:'' ?>','1');" class="btn btn-md approve">Approve</button>
                  <button  onclick="changeStatusReq('<?php echo !empty($row->id)?$row->id:'' ?>','2');" class="btn btn-md deny">Deny</button>
                </div>
              </div>
            <?php }
          } ?>          
        </div>
     </div>
  <div class="clearfix"></div>  
  </div>
</div>       