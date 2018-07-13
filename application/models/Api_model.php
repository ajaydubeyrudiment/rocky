<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Api_model extends CI_Model {
	public function front_user_login($email = '', $password = '', $directLogin = '') {
        $email = trim($email);
        $whereN = "(`email`='" . $email . "' OR `user_name`='" . $email . "')";
        $this->db->where($whereN);
        $query_get = $this->db->get('users');
        $count = $query_get->num_rows();
        $res = $query_get->row();
        if ($count >= 1) {
            if ($directLogin == 1) {
                $where = "`email`='" . $email . "'";
            } else {
                $salt = $res->salt;
                $newPassword = sha1($salt . sha1($salt . sha1($password)));
                $where = "((`email`='" . $email . "' AND `password`='" . $newPassword . "') OR (`user_name`='" . $email . "' AND `password`='" . $newPassword . "'))";
            }
            $this->db->select('*');
            $this->db->from('users');
            $this->db->where($where);
            $query = $this->db->get();
            $sql = $query;
            $check_count = $sql->num_rows();
            $result = $sql->row();
            if ($check_count == 1) {
                if ($result->is_email_verify == 1) {
                    if ($result->status == 1) {
                    	$iplinks = "http://www.geoplugin.net/php.gp?ip=" . $_SERVER['REMOTE_ADDR'];
                        $geoIP   = unserialize(file_get_contents($iplinks));                        
                        $current_location = '';
                        if (!empty($geoIP)) {
                            if (!empty($geoIP['geoplugin_city'])) $current_location.= $geoIP['geoplugin_city'];
                            if (!empty($geoIP['geoplugin_region'])) $current_location.= ', ' . $geoIP['geoplugin_region'];
                            if (!empty($geoIP['geoplugin_countryName'])) $current_location.= ', ' . $geoIP['geoplugin_countryName'];
                        }
                        $user_data = array('id' => $result->id, 'first_name' => $result->first_name, 'email' => $result->email, 'logged_in' => TRUE);
                        $this->session->set_userdata('user_info', $user_data);
                        $updated_address = array('last_ip' => $this->input->ip_address(), 'last_login' => date('Y-m-d h:i:s'));
                        $updated_address['last_location'] = $current_location;
                        $this->update('users', $updated_address, array('id' => $result->id));

                        return 1;
                    } else {
                        return 2;
                    }
                } else {
                    return 3;
                }
            } else {
                return 4;
            }
        } else {
            return 5;
        }
    }
    public function getUserSeting($user_id=''){        
        $this->db->select('users.privacy as profileVisibility, users.advancedMetricsTracking, users.useMetricsSystem, users.contactEmail, users.contactMobile, users.paypalEmail, plans.id as modify_plan_id');
		$this->db->from('users');			
		$this->db->join('plans', 'users.plan = plans.id','left');			
		if(!empty($user_id)){
			$this->db->where("users.id", $user_id);	
		}else{
			$this->db->where("users.id", user_id());	
		}        	
	 	$getdata = $this->db->get(); 			
		$num  = $getdata->num_rows();	
		if($num){
		   $data = $getdata->row();
		   return $data;		
		}else{
			return false;
		}
	}	
	public function get_recipe_blog_images_user_following($userId='', $type='', $not_post_id='', $orders=''){
		$followingUsers = $this->getFollowingUser($userId);
		$whereUser = "( recipe_blog_image.user_id = '".$userId."'";
		//print_r($followingUsers); exit();
		if(!empty($followingUsers)){
			foreach($followingUsers as $followingUser){
				if(!empty($followingUser->other_user_id)){
					$whereUser .= " OR recipe_blog_image.user_id = '".$followingUser->other_user_id."'";
				}
			}
		}
		$whereUser .= ")";
        $this->db->select('recipe_blog_image.image_name, recipe_blog_image.coverImage, recipe_blog_image.id, recipe_blog_image.type, recipe_blog_image.description, recipe_blog_image.title, users.privacy as user_account_type, recipe_blog_image.user_id');
		$this->db->from('recipe_blog_image');      
		$this->db->join('users', 'recipe_blog_image.user_id = users.id', 'left');      
		if(!empty($userId)){	
			$this->db->where($whereUser);		
		}	
		if(!empty($type)){	
			$this->db->where("recipe_blog_image.type", $type);	
		}
		if(!empty($not_post_id)){	
			$this->db->where("recipe_blog_image.id !=", $not_post_id);	
		}
		if(!empty($orders)){	
			$this->db->order_by("recipe_blog_image.id", 'random');		
		}else{
			$this->db->order_by("recipe_blog_image.id", 'DESC');	
		}
		$this->db->where("recipe_blog_image.status", 1);
		$getdata = $this->db->get();
		$num  	 = $getdata->num_rows();	
		//echo $num; exit();
		if($num){
		   $data = $getdata->result();
		   return $data;		
		}else{
			return array();
		}	
	}
	public function get_recipe_blog_images($userId='', $type='', $not_post_id='', $orders='',$minMax=''){
        $this->db->select('recipe_blog_image.image_name, recipe_blog_image.coverImage, recipe_blog_image.id, recipe_blog_image.type, recipe_blog_image.description, recipe_blog_image.title, users.privacy as user_account_type, recipe_blog_image.user_id');
		$this->db->from('recipe_blog_image');      
		$this->db->join('users', 'recipe_blog_image.user_id = users.id', 'left');      
		if(!empty($userId)){	
			$this->db->where("recipe_blog_image.user_id", $userId);		
		}	
		if(!empty($type)){	
			$this->db->where("recipe_blog_image.type", $type);	
		}
		if(!empty($not_post_id)){	
			$this->db->where("recipe_blog_image.id !=", $not_post_id);	
		}
		if(!empty($minMax)&&$minMax=='min'){
			$this->db->where("recipe_blog_image.id >", $not_post_id);
			$this->db->order_by("recipe_blog_image.id", 'ASC');	
		}
		if(!empty($minMax)&&$minMax=='max'){
			$this->db->where("recipe_blog_image.id <", $not_post_id);
			$this->db->order_by("recipe_blog_image.id", 'ASC');	
		}
		if(!empty($orders)&&empty($minMax)){	
			$this->db->order_by("recipe_blog_image.id", 'random');		
		}elseif(empty($minMax)){
			$this->db->order_by("recipe_blog_image.id", 'DESC');	
		}
		$this->db->where("recipe_blog_image.status", 1);
		$getdata = $this->db->get();
		$num  	 = $getdata->num_rows();	
		if($num){
		   $data = $getdata->result();
		   return $data;		
		}else{
			return array();
		}	
	}	
	public function getLikesBookMarkBlogImges($userId='',$type='',$notPostID='',$orders='',$minMax=''){
		if($type=='likes'){
			$this->db->select('recipe_blog_image.image_name, recipe_blog_image.coverImage, recipe_blog_image.id, recipe_blog_image.type, recipe_blog_image.description, recipe_blog_image.title');
			$this->db->from('likes');     
			$this->db->join('recipe_blog_image', 'likes.recipe_blog_image_id=recipe_blog_image.id', 'left');     
			if(!empty($userId)){	
				$this->db->where("likes.user_id", $userId);		
			}
			if(!empty($notPostID)){	
				$this->db->where("recipe_blog_image.id !=", $notPostID);		
			}
			if(!empty($minMax)&&$minMax=='min'){
				$this->db->where("likes.id >", $notPostID);
				$this->db->order_by("likes.id", 'ASC');
			}
			if(!empty($minMax)&&$minMax=='max'){
				$this->db->where("likes.id <", $notPostID);
				$this->db->order_by("likes.id", 'ASC');
			}
			if(!empty($orders)){	
				$this->db->order_by("recipe_blog_image.id", 'random');		
			}elseif(empty($minMax)){
				$this->db->order_by("likes.id", 'DESC');	
			} 
		}else if($type=='comments'){
			$this->db->select('recipe_blog_image.image_name, recipe_blog_image.coverImage, recipe_blog_image.id, recipe_blog_image.type, recipe_blog_image.description, recipe_blog_image.title, comments.comments');
			$this->db->from('comments');     
			$this->db->join('recipe_blog_image', 'comments.recipe_blog_image_id=recipe_blog_image.id', 'left');
			if(!empty($userId)){	
				$this->db->where("comments.user_id", $userId);		
			}
			if(!empty($notPostID)){	
				$this->db->where("recipe_blog_image.id !=", $notPostID);		
			}
			if(!empty($minMax)&&$minMax=='min'){
				$this->db->where("likes.id >", $notPostID);
				$this->db->order_by("comments.id", 'ASC');
			}
			if(!empty($minMax)&&$minMax=='max'){
				$this->db->where("likes.id <", $notPostID);
				$this->db->order_by("likes.id", 'ASC');
			}
			if(!empty($orders)){	
				$this->db->order_by("recipe_blog_image.id", 'random');		
			}elseif(empty($minMax)){
				$this->db->order_by("recipe_blog_image.id", 'DESC');	
			}
			$this->db->where("comments.comments !=", ''); 
		}else{
			$this->db->select('recipe_blog_image.*');
			$this->db->from('bookmark');     
			$this->db->join('recipe_blog_image', 'bookmark.recipe_blog_image_id=recipe_blog_image.id', 'left');  
			if(!empty($userId)){	
				$this->db->where("bookmark.user_id", $userId);		
			}
			if(!empty($notPostID)){	
				$this->db->where("recipe_blog_image.id !=", $notPostID);		
			}
			if(!empty($minMax)&&$minMax=='min'){
				$this->db->where("bookmark.id >", $notPostID);
				$this->db->order_by("bookmark.id", 'ASC');
			}
			if(!empty($minMax)&&$minMax=='max'){
				$this->db->where("bookmark.id <", $notPostID);
				$this->db->order_by("bookmark.id", 'ASC');
			}
			if(!empty($orders)){	
				$this->db->order_by("recipe_blog_image.id", 'random');		
			}elseif(empty($minMax)){
				$this->db->order_by("bookmark.id", 'DESC');	
			}
		}	
		$getdata = $this->db->get();
		$num  	 = $getdata->num_rows();	
		if($num){
		   $data = $getdata->result();
		   return $data;		
		}else{
			return array();
		}	
	}
	public function get_post_details($post_id=''){
        $this->db->select('recipe_blog_image.*, users.user_name, users.profile_pic ');
		$this->db->from('recipe_blog_image');     
		$this->db->join('users','recipe_blog_image.user_id=users.id', 'left');     
		$this->db->order_by('recipe_blog_image.id', 'DESC');   
		if(!empty($post_id)){	
			$this->db->where("recipe_blog_image.id", $post_id);		
		}
		$getdata = $this->db->get();
		//echo $this->db->last_query(); exit();
		$num  	 = $getdata->num_rows();	
		if($num){
		   $data = $getdata->row();
		   return $data;		
		}else{
			return '';
		}	
	}
	public function getPreviewNext($type='', $post_id='', $viewUser='', $postType=''){
        $this->db->select('recipe_blog_image.id');
		$this->db->from('recipe_blog_image');
		$this->db->where("recipe_blog_image.status", 1);	  
		if(!empty($post_id)&&$type=='prev'){	
			$this->db->where("recipe_blog_image.id <", $post_id);	
			$this->db->order_by('recipe_blog_image.id', 'DESC');   	
		}
		if(!empty($post_id)&&$type=='next'){	
			$this->db->where("recipe_blog_image.id >", $post_id);
			$this->db->order_by('recipe_blog_image.id', 'ASC');   		
		}
		if(!empty($viewUser)){
			$this->db->where("recipe_blog_image.user_id", $viewUser);
		}
		if(!empty($postType)&&$postType!='all'){	
			$this->db->where("recipe_blog_image.type", $postType);		
		}
		$this->db->limit(1); 
		$getdata = $this->db->get();	
		$num  	 = $getdata->num_rows();	
		if($num){
		   $data = $getdata->row()->id;
		   return $data;		
		}else{
			return '';
		}	
	}
	public function get_comments($post_id='', $limit=""){
        $this->db->select('comments.id as comment_id,  comments.user_id, users.user_name, users.profile_pic, comments.comments, comments.created_date as commented_date ');
		$this->db->from('comments');     
		$this->db->join('users','comments.user_id=users.id', 'left');     
		$this->db->order_by('comments.id', 'DESC');   
		if(!empty($post_id)){	
			$this->db->where("comments.recipe_blog_image_id", $post_id);		
		}
		if(!empty($limit)){
			$this->db->limit($limit);		
		}
		$getdata = $this->db->get();
		$num  	 = $getdata->num_rows();	
		if($num){
		   $dataArr = array();
		   $data 	= $getdata->result();
		   if(!empty($data)){
		   		foreach($data as $dataA){
		   			$dataArr[] = array(
		   							'comment_id'     => $dataA->comment_id,
		   							'user_id'        => $dataA->user_id,
		   							'user_name'      => $dataA->user_name,
		   							'comments'       => $dataA->comments,
		   							'commented_date' => $dataA->commented_date,
		   							'profile_pic'    => (!empty($dataA->profile_pic) && file_exists('assets/uploads/users/thumbnails/'.$dataA->profile_pic))?base_url().'assets/uploads/users/thumbnails/'.$dataA->profile_pic:''
		   							);
		   		}
		   }
		   return $dataArr;		
		}else{
			return array();
		}	
	}
	public function get_user_exercise($subcategory_id=''){
		$this->db->select('exercise_title, cacalories,measureUnit, exercise_tag, exercise_details, exercise_pic, exercise_instruction, id as exerciseID, category_id');
		$this->db->from('service_plan_user_exercise');     		    
		if(!empty($subcategory_id)){
			$where =  "service_plan_user_exercise.sub_category_id IN(".$subcategory_id.")";
			$this->db->where($where);
		}
		$this->db->order_by('service_plan_user_exercise.id', 'DESC');   	
		$getdata = $this->db->get();
		$num  	 = $getdata->num_rows();	
		if($num){
		   $data = $getdata->result();
		   return $data;		
		}else{
			return array();
		}
	}
	public function get_user_diet_item($subcategory_id=''){
		$this->db->select('service_plan_diet_items.*');
		$this->db->from('service_plan_diet_items');     		    
		if(!empty($subcategory_id)){
			$where =  "service_plan_diet_items.sub_category_id IN(".$subcategory_id.")";
			$this->db->where($where);
		}
		$this->db->order_by('service_plan_diet_items.id', 'DESC');   	
		$getdata = $this->db->get();
		$num  	 = $getdata->num_rows();	
		if($num){
		   $data = $getdata->result();
		   return $data;		
		}else{
			return array();
		}
	}
	public function getServicePlanItem($planID='', $servicePlanID=''){        
        $this->db->select('title,id');
		$this->db->from('service_plan_category');		
		$this->db->where("service_plan_category.status", 1);
		if(!empty($planID)){
			$this->db->where("service_plan_category.plan_id", $planID);	        	        	
		}
	 	$getdata = $this->db->get(); 			
		$num  = $getdata->num_rows();	
		$rows = array();
		if($num){
		   $data = $getdata->result();
		   if(!empty($data)){
		   		foreach($data as $dataRow){
		   			$items  = 	$this->getCategoryItem($dataRow->id, $servicePlanID);
		   			$rows[] = 	array(	'categoryID'    => $dataRow->id, 
		   								'categoryTitle' => $dataRow->title,
		   								'items'		    => $items
		   							);
		   		}
		   }		
		}
		return $rows;
	}
	public function getServiceItemStepOne($servicePlanID=''){  
		$rowArr = array();
		$rows   = $this->common_model->get_row('userPlans', array('id'=>$servicePlanID));
		if(!empty($rows->items)){
			$rowArr = explode(',', $rows->items);
		}
		return $rowArr;
	}
	public function getServiceItemStepSecond($servicePlanID=''){  
		$rowArr = array();
		$rows   = $this->common_model->get_row('userPlans', array('id'=>$servicePlanID));
		if(!empty($rows->items)){
			$rowArr = explode(',', $rows->exercise);
		}
		return $rowArr;
	}
	public function getCategoryItem($categortID='', $servicePlanID=''){
		$planIDS = $this->getServiceItemStepOne($servicePlanID);        
        $this->db->select('itemPic as serviceItemPic,title as serviceItemTitle, id as serviceItemID');
		$this->db->from('service_plan_item');		
		$this->db->where("service_plan_item.status", 1);	        	
		$this->db->where("service_plan_item.category_id", $categortID);	        	
	 	$getdata = $this->db->get(); 			
		$num     = $getdata->num_rows();	
		if($num){
		   $rows  = $getdata->result();	
		   $items = array();
		   if(!empty($rows)){
		   		foreach($rows as $row){
		   			$row->serviceItemPic =  (!empty($row->serviceItemPic)&&file_exists('assets/uploads/planItem/thumbnails/'.$row->serviceItemPic))?base_url().'assets/uploads/planItem/thumbnails/'.$row->serviceItemPic:'';
		   			if(!empty($planIDS)&&in_array($row->serviceItemID, $planIDS)){
		   				$row->isSelected = true;
		   			}else{
		   				$row->isSelected = false;
		   			}	   			
		   			$items[] = $row;
		   		}		   	
		   } 
		   return $items;	
		}else{
			return array();
		}
	}
	public function getFollowingUser($userId=''){
        $this->db->select("users.user_name, users.profile_pic, users.id as other_user_id");
		$this->db->from('follow_request');     
		$this->db->join('users', 'follow_request.receiver_id = users.id', 'left');
		$this->db->where("follow_request.accepted_status", 1);	   
		if(!empty($userId)){	
			$this->db->where("follow_request.sender_id", $userId);		
		}	
		$this->db->order_by('users.user_name', 'ASC');  
		$getdata = $this->db->get();
		$num  	 = $getdata->num_rows();	
		if($num){
		   $data = $getdata->result();
		   return $data;		
		}else{
			return array();
		}	
	}	
	public function getFollowerUser($userId=''){
        $this->db->select("users.user_name, users.profile_pic, users.id as other_user_id");
		$this->db->from('follow_request');     
		$this->db->join('users', 'follow_request.sender_id = users.id', 'left');     
		$this->db->where("follow_request.accepted_status", 1);		 
		if(!empty($userId)){	
			$this->db->where("follow_request.receiver_id", $userId);		
		}	
		$this->db->order_by('users.user_name', 'ASC');  
		$getdata = $this->db->get();		
		$num  	 = $getdata->num_rows();	
		if($num){
		   $data = $getdata->result();
		   return $data;		
		}else{
			return array();
		}
	}
	public function getFollowerUserReq($user_id=''){
        $this->db->select("follow_request.id as request_id, users.user_name, users.profile_pic, users.id as user_id");
		$this->db->from('follow_request');     
		$this->db->join('users', 'follow_request.sender_id = users.id', 'left');     
		$this->db->where("follow_request.accepted_status", 4);		 
		$this->db->where("follow_request.receiver_id", $user_id);
		$this->db->order_by('users.user_name', 'ASC');  
		$getdata = $this->db->get();		
		$num  	 = $getdata->num_rows();	
		if(!empty($num)){
			return $getdata->result();	
		}else{
			return array();
		}
	}
	public function getFollowerUserReqCounter($user_id=''){
        $this->db->select("follow_request.id as request_id, users.user_name, users.profile_pic, users.id as user_id");
		$this->db->from('follow_request');     
		$this->db->join('users', 'follow_request.sender_id = users.id', 'left');     
		$this->db->where("follow_request.accepted_status", 4);		 
		$this->db->where("follow_request.receiver_id", $user_id);
		$this->db->order_by('users.user_name', 'ASC');  
		$getdata = $this->db->get();		
		$num  	 = $getdata->num_rows();	
		if(!empty($num)){
			return $num;	
		}else{
			return 0;
		}
	}
	public function getUserLikes($userID='',$countr=''){
        $this->db->select("users.id as like_user_id, users.user_name, users.profile_pic, recipe_blog_image.type,  recipe_blog_image.title, recipe_blog_image.image_name");
		$this->db->from('likes');     
		$this->db->join('users', 'likes.user_id = users.id', 'left');     
		$this->db->join('recipe_blog_image', 'likes.recipe_blog_image_id = recipe_blog_image.id', 'left');     
		$this->db->where("recipe_blog_image.status", 1);		 
		$this->db->where("recipe_blog_image.user_id", $userID);
		$this->db->order_by('likes.id', 'ASC');  
		$getdata = $this->db->get();		
		$num  	 = $getdata->num_rows();	
		if($num){
			if(!empty($countr)){
		    	return $getdata->num_rows();
			}else{
				return $getdata->result();
			}		
		}else{
			return false;
		}
	}
	public function getUserComments($countr=''){
        $this->db->select("comments.*, recipe_blog_image.title, recipe_blog_image.type,  recipe_blog_image.image_name");
		$this->db->from('comments');        
		$this->db->join('recipe_blog_image', 'comments.recipe_blog_image_id = recipe_blog_image.id', 'left');     
		$this->db->where("comments.status", 1);		 
		$this->db->where("comments.user_id", user_id());
		$this->db->order_by('comments.id', 'ASC');  
		$getdata = $this->db->get();		
		$num  	 = $getdata->num_rows();	
		if($num){
			if(!empty($countr)){
		    	return $getdata->num_rows();
			}else{
				return $getdata->result();
			}		
		}else{
			return false;
		}
	}
	public function get_user_result($height='', $wieght='', $goal_type='', $loseWeight='', $loseDay='', $useMetricsSystem=''){
		//$hieght_id = $this->get_hieght_id();
        $this->db->select('goal_setter.id as goal_id, users.profile_pic, users.user_name, goal_setter.user_id as other_user_id');
		$this->db->from('goal_setter');            
		$this->db->join('users', 'goal_setter.user_id=users.id', 'left');   
		if(!empty($useMetricsSystem)==1&&!empty($height)){
        	$this->db->where("goal_setter.height >", ($height)-1);
            $this->db->where("goal_setter.height <", ($height)+1);
        }else if (!empty($height)){
        	$this->db->where("goal_setter.height >", ($height)-1);
            $this->db->where("goal_setter.height <", ($height)+1);
        }
        if(!empty($wieght)){
        	$this->db->where("goal_setter.wieght >", ($wieght)-1);
            $this->db->where("goal_setter.wieght <", ($wieght)+1);
        }
        if(!empty($loseWeight)){
        	$this->db->where("goal_setter.loseWeight >", ($loseWeight)-1);
            $this->db->where("goal_setter.loseWeight <", ($loseWeight)+1);
        }
        if(!empty($loseDay)){
        	$this->db->where("goal_setter.loseDay >", ($loseDay)-1);
            $this->db->where("goal_setter.loseDay <", ($loseDay)+1);
        }
		$this->db->where("goal_setter.status !=", 3);	
		$this->db->order_by("goal_setter.id", 'DESC');	
		$getdata = $this->db->get(); 
		$num  = $getdata->num_rows();		
		if($num){
		   $data = $getdata->result();
		   return $data;		
		}else{
			return array();
		}	
	}
	public function getCurrentPlanDetails($planType='', $userID='',$planID='',$goalID=''){
        $this->db->select("userPlans.*");
		$this->db->from('userPlans');   
		if(!empty($userID)){
			$this->db->where("userPlans.user_id", $userID);
		} 
		if(!empty($planType)){
			$this->db->where("userPlans.planType", $planType);
		} 
		if(!empty($planID)){
			$this->db->where("userPlans.id", $planID);
		} 
		if(!empty($goalID)){
			$this->db->where("userPlans.goal_id", $goalID);
		}
		$this->db->where("userPlans.activatePlan", '1');		
		$this->db->order_by('userPlans.id', 'DESC');  
		$getdata = $this->db->get();		
		$num  	 = $getdata->num_rows();	
		if($num){
			return $getdata->row();		
		}else{
			return false;
		}
	}
	public function getWorkOutPlanDay($day='', $currentPlan='', $user_id='', $weekID=''){
		$this->db->select('service_plan_works_new.*, service_plan_user_exercise.exercise_title as item_title, service_plan_user_exercise.cacalories, service_plan_user_exercise.exercise_pic, service_plan_user_exercise.measureUnit, userPlans.goal_id');
		$this->db->from('service_plan_works_new');     
		$this->db->join('service_plan_user_exercise', 'service_plan_works_new.exercise_id=service_plan_user_exercise.id', 'left');
		$this->db->join('userPlans', 'service_plan_works_new.plan_id=userPlans.id', 'left');     
		if(!empty($weekID)){
			$this->db->where("service_plan_works_new.week_id", $weekID);
		}	
		if(!empty($user_id)){			
			$this->db->where("userPlans.user_id", $user_id);	
		}	
		if(!empty($currentPlan)){
			$this->db->where("service_plan_works_new.plan_id", $currentPlan);	
		}
		if(!empty($day)){	
			$this->db->where("service_plan_works_new.".$day, '1');		
		}
		$this->db->order_by('service_plan_works_new.id', 'DESC');   	
		$getdata = $this->db->get();
		//echo $this->db->last_query(); exit();
		$num  	 = $getdata->num_rows();	
		if($num){
		   $data = $getdata->result();
		   return $data;		
		}else{
			return array();
		}
	}
	public function getDietPlanDay($day='', $currentPlan='', $user_id='', $weekID=''){
		//echo $currentPlan.' currentPlan'.$user_id.' user_id <br/>'; 
		$this->db->select('diet_plan_works_new.*, service_plan_diet_items.item_title, service_plan_diet_items.cacalories, service_plan_diet_items.protein, service_plan_diet_items.fat, service_plan_diet_items.carbohydrate, service_plan_diet_items.fiber, service_plan_diet_items.suger, service_plan_diet_items.exercise_pic, userPlans.goal_id');
		$this->db->from('diet_plan_works_new');     
		$this->db->join('service_plan_diet_items', 'diet_plan_works_new.item_id=service_plan_diet_items.id', 'left');
		$this->db->join('userPlans', 'diet_plan_works_new.plan_id=userPlans.id', 'left');     
		if(!empty($currentPlan)){
			$this->db->where("diet_plan_works_new.plan_id", $currentPlan);
		}
		if(!empty($weekID)){
			$this->db->where("diet_plan_works_new.week_id", $weekID);
		}	
		if(!empty($user_id)){			
			$this->db->where("userPlans.user_id", $user_id);	
		}
		if(!empty($day)){	
			$this->db->where("diet_plan_works_new.".$day, '1');		
		}
		$this->db->order_by('diet_plan_works_new.id', 'DESC');   	
		$getdata = $this->db->get();
		//echo $this->db->last_query(); exit();
		$num  	 = $getdata->num_rows();	
		if($num){
		   $data = $getdata->result();
		   return $data;		
		}else{
			return false;
		}
	}
	public function getWorkOutPlanDayNew($date='', $currentPlan='', $user_id=''){
        $this->db->select('service_plan_works_new.*, service_plan_user_exercise.exercise_title as item_title, service_plan_user_exercise.cacalories, service_plan_user_exercise.exercise_pic, service_plan_user_exercise.measureUnit, userPlans.goal_id');
        $this->db->from('service_plan_works_new');     
        $this->db->join('service_plan_user_exercise', 'service_plan_works_new.exercise_id=service_plan_user_exercise.id', 'left');
        $this->db->join('userPlans', 'service_plan_works_new.plan_id = userPlans.id', 'left');     
        if(!empty($weekID)){
            $this->db->where("service_plan_works_new.week_id", $weekID);
        }   
        if(!empty($user_id)){         
            $this->db->where("userPlans.user_id", $user_id);    
        }  
        if(!empty($currentPlan)){
            $this->db->where("service_plan_works_new.plan_id", $currentPlan);    
        }
        if(!empty($date)){   
            $this->db->where("service_plan_works_new.work_out_date", $date);     
        }
        $this->db->order_by('service_plan_works_new.id', 'DESC');       
        $getdata = $this->db->get();
        //echo $this->db->last_query(); //exit();
        $num     = $getdata->num_rows();    
        if($num){
           $data = $getdata->result();
           return $data;        
        }else{
            return false;
        }
    }
    public function getDietPlanDayNew($date='', $currentPlan='', $user_id=''){
        $this->db->select('diet_plan_works_new.*, service_plan_diet_items.item_title, service_plan_diet_items.cacalories, service_plan_diet_items.protein, service_plan_diet_items.fat, service_plan_diet_items.carbohydrate, service_plan_diet_items.fiber, service_plan_diet_items.suger, service_plan_diet_items.exercise_pic, userPlans.goal_id, service_plan_diet_items.description, service_plan_diet_items.preparation, service_plan_diet_items.healthiness, service_plan_diet_items.exercise_pic');
        $this->db->from('diet_plan_works_new');     
        $this->db->join('service_plan_diet_items', 'diet_plan_works_new.item_id=service_plan_diet_items.id', 'left');
        $this->db->join('userPlans', 'diet_plan_works_new.plan_id=userPlans.id', 'left');  
        if(!empty($currentPlan)){
            $this->db->where("diet_plan_works_new.plan_id", $currentPlan);
        }  
        if(!empty($user_id)){         
            $this->db->where("userPlans.user_id", $user_id);    
        }
        if(!empty($date)){   
            $this->db->where("diet_plan_works_new.diet_date", $date);     
        }
        $this->db->order_by('diet_plan_works_new.id', 'DESC');      
        $getdata = $this->db->get();
        //echo $this->db->last_query(); exit();
        $num     = $getdata->num_rows();    
        if($num){
           $data = $getdata->result();
           return $data;        
        }else{
            return false;
        }
    }
}