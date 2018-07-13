<div id="following_users_res"></div>
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
		<div class="media" id="following_user_<?php echo !empty($row->user_id)?ucfirst($row->user_id):'' ?>">
		    <div class="media-left following-pic">
		    	<a href="<?php echo $userNameIDS; ?>">
		      		<img src="<?php echo (!empty($row->profile_pic)&&file_exists('assets/uploads/users/thumbnails/'.$row->profile_pic))?base_url().'assets/uploads/users/thumbnails/'.$row->profile_pic:base_url().'assets/front/img/roky-logo.png'; ?>" alt="<?php echo !empty($row->user_name)?$row->user_name:'' ?>">
		      	</a>
		    </div>
		    <div class="media-body">
		    	<?php
		    	if($row->user_id==user_id()){?>
		    		
		    	<?php 
		    	}else if(get_all_count('follow_request', array('sender_id' => user_id(), 'receiver_id' => $row->user_id, 'accepted_status'=>1))>0){?>
		      		<a href="javascript:void(0);" onclick="unFollowUserSet('<?php echo !empty($row->user_id)?ucfirst($row->user_id):'' ?>', '<?php if($this->input->get('user_id', TRUE)){echo $this->input->get('user_id', TRUE);} ?>');">
		      			<span class="following-setting" id="following_<?php echo !empty($row->user_id)?ucfirst($row->user_id):'' ?>">Unfollow</span>
		      		</a>
		    	<?php 
		    	}else if(get_all_count('follow_request', array('sender_id' => user_id(), 'receiver_id' => $row->user_id, 'accepted_status'=>4))>0){?>
		      		<a href="javascript:void(0);">
		      			<span class="following-setting" id="following_<?php echo !empty($row->user_id)?ucfirst($row->user_id):'' ?>">Request Sent</span>
		      		</a>
		    	<?php 
		    	}else{?>
		    		<a href="javascript:void(0);" onclick="unFollowUserSet('<?php echo !empty($row->user_id)?ucfirst($row->user_id):'' ?>','<?php if($this->input->get('user_id', TRUE)){echo $this->input->get('user_id', TRUE);} ?>');">
		      			<span class="following-setting" id="following_<?php echo !empty($row->user_id)?ucfirst($row->user_id):'' ?>">Follow</span>
		      		</a>
		    	<?php }?>		    		    	
		      	<h4><a href="<?php echo $userNameIDS; ?>"><?php echo !empty($row->user_name)?ucfirst($row->user_name):'' ?></a></h4>
		    </div>
		</div>
	<?php 
	}
}else{
	//echo '<h3 class="text-center text-danger"> No following records found</h3>';
}
?>