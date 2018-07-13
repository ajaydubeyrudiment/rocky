<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';  
class Api extends REST_Controller {
    function __construct(){
        parent::__construct();
        $_POST = json_decode(file_get_contents("php://input"), true);
    }
    /*******************!!!!!!!!!!!!!!!     Common  Project Ralated Services      !!!!!!!!!!! ******************/
    /********************   validation function  ***********************/ 
    public function password_check($oldpassword){
        if(!empty($oldpassword)){            
            $user_info  = $this->common_model->get_row('users',array('id'=>$this->input->post('user_id')));
            $salt       = $user_info->salt;
            if($this->common_model->get_row('users', array('password'=>sha1($salt.sha1($salt.sha1($oldpassword))),'id'=>$this->input->post('user_id')))){
                return TRUE;
            }else{
                $this->form_validation->set_message('password_check', 'The %s does not match');
                return FALSE;
            }
        }
    }
    public function userNameChecked($email){ 
        if($this->input->post('user_name')){            
            $user_name  = $this->input->post('user_name');
            $confirm    = $this->common_model->get_row('users', array('user_name'=>$user_name));
            if($confirm){
                $this->form_validation->set_message('userNameChecked','The selected username is already taken.');
                return false;
            }else{ 
                return true;
            }
        }
    }
    public function emailChecked($email){ 
        if($this->input->post('email')){
            $email  = $this->input->post('email'); 
            $confirm    = $this->common_model->get_row('users', array('email'=>$email));
            if($confirm){
                $this->form_validation->set_message('emailChecked','The entered email is already registered.');
                return false;
            }else{ 
                return true;
            }
        }
    } 
    /*****************  user login signup api *************************/
    public function login_post() {             
        $user_id    = '';
        $responseCode   = 500;
        $message    = 'Invalid Request';
        $this->form_validation->set_rules('email', 'User name', 'trim|required|xss_clean');        
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('', '');  
        if($this->form_validation->run() == TRUE){ 
            $email       = $this->input->post('email', TRUE);                
            $password    = $this->input->post('password', TRUE);
            $res         = $this->common_model->front_user_login($email, $password); 
            if($res==1){
                if(user_logged_in()){
                    $user                       = user_info();
                    $data['responseCode']       = 200;
                    $data['message']            = "Your Login successfully";
                    $data['user_id']            = $user->id;
                    $data['user_name']          = !empty($user->user_name)?$user->user_name:'';
                    $data['firstTimeLogin']     = (!empty($user->profile_view_status)&&$user->profile_view_status==1)?1:0;                    
                }else{
                    $data['responseCode'] = 500;
                    $data['message'] = "You are not login, try again";
                }
            }else if($res==2){
                $data['responseCode'] = 500;
                $data['message']      = 'Your account has been banned from using the Roky platform. If you believe this has been in error, then please contact support@roky.ca';
            }else if($res==3){
                $data['responseCode'] = 500;
                $data['message']      = 'Your account is not verified';
            }else if($res==4){
                $data['responseCode'] = 500;
                $data['message']      = 'The username or password is invalid.';
            }else{
                $data['responseCode'] = 500;
                $data['message']      = 'The username or password is invalid.';
                
            }
            $this->response($data);
        }
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data); 
    }
    public function signup_post() {
        $responseCode   = 500;
        $message        = 'Invalid Request';
        $this->form_validation->set_rules('name', 'name', 'required|xss_clean');
        $this->form_validation->set_rules('user_name', 'user name', 'required|alpha_numeric|xss_clean|callback_userNameChecked');
        $this->form_validation->set_rules('email', 'email', 'required|valid_email|xss_clean|callback_emailChecked');
        $this->form_validation->set_rules('password', 'password', 'required|xss_clean');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $activationcode             = rand().time();
            $newSalt                    = salt();
            $password                   = passwordGenrate($this->input->post('password', TRUE), $newSalt);
            $signup                     = array();
            if($this->input->post('name'))
                $signup['first_name']   = $this->input->post('name', TRUE);
            if($this->input->post('user_name'))
                $signup['user_name']    = $this->input->post('user_name', TRUE);
            $signup['email']            = $this->input->post('email', TRUE);
            $signup['activation_code']  = $activationcode ;  
            $signup['created_date']     = date('Y-m-d H:i:s');
            $signup['password']         = $password; 
            $signup['salt']             = $newSalt;
            $insert = $this->common_model->insert('users', $signup);
            if(!empty($insert)){        
                $response = $this->common_model->front_user_login($this->input->post('email', TRUE), $this->input->post('password', TRUE));
                if($response){
                    $data['responseCode']   = 200;
                    $data['message']        = "Your registration is successfully.";
                    $data['user_id']        = $insert;
                    $data['user_name']     = $this->input->post('user_name', TRUE);
                    $data['firstTimeLogin'] = 1;      
                }else{
                    $data['responseCode'] = 500;
                    $data['message'] = "Registration  is failed";
                }             
                $this->response($data);
            }
        }
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data); 
    }
    /*****************  forgot password *************************/
    public function forgot_password_post() {             
        $user_id    = '';
        $responseCode   = 500;
        $message    = 'Invalid Request';
        $this->form_validation->set_rules('email', 'User name', 'trim|required|xss_clean'); 
        $this->form_validation->set_error_delimiters('', '');  
        if($this->form_validation->run() == TRUE){ 
            $email       = $this->input->post('email', TRUE);  
            $where          = "`email` = '".$email."' OR `user_name` = '".$email."'";
            $row            = $this->common_model->get_row('users', array(), array(), array(), $where);
            $data['responseCode'] = 200;
            $data['message']      = 'If the provided email address/username is registered then a password reset code will be sent to your email.';
            if(!empty($row)){
                $where          = array('email' => $row->email);
                $activationcode = uniqid();
                $activationcode = substr($activationcode, 0, 6);
                $datasa         = array('activation_code'=>$activationcode);
                $success        = $this->common_model->update('users', $datasa, $where);
                if($success){
                    $url            = base_url()."home/update_password/".$activationcode;  
                    $this->load->library('Cimail_email'); 
                    $email_template = $this->cimail_email->get_email_template(20);
                    $site_title     = site_info('site_title');
                    $mail_from_name = site_info('mail_from_name');
                    $mail_from_email= site_info('mail_from_email');
                    $param=array(
                                'template'      => array(
                                'temp'          => $email_template->template_body,
                                'var_name'      => array(
                                                        'name'           => $row->user_name,
                                                        'site_name'      => $site_title,    
                                                        'activation_key' => $activationcode,     
                                                        'site_url'       => base_url()
                                                        ), 
                                ),      
                        'email' =>  array(

                                        'to'        =>  $row->email,
                                        'from'      =>  $mail_from_name,
                                        'from_name' =>  $mail_from_email,
                                        'subject'   =>  $email_template->template_subject,
                                        )
                    );  
                    $status = $this->cimail_email->send_mail($param);
                    if($status){
                        $data['message']      = 'If the provided email address/username is registered then a password reset code will be sent to your email.';
                    }else{
                        $data['message']      = 'If the provided email address/username is registered then a password reset code will be sent to your email.';
                    }
                    $data['responseCode'] = 200;
                }else{
                    $data['responseCode'] = 200;
                    $data['message']      = 'If the provided email address/username is registered then a password reset code will be sent to your email.';
                }
            }
            $this->response($data);
        }
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data); 
    }
    public function check_password_opt_post() {             
        $user_id    = '';
        $responseCode   = 500;
        $message    = 'Invalid Request';
        $this->form_validation->set_rules('email', 'User name', 'trim|required|xss_clean'); 
        $this->form_validation->set_rules('otp', 'OTP', 'trim|required|xss_clean'); 
        $this->form_validation->set_error_delimiters('', '');  
        if($this->form_validation->run() == TRUE){ 
            $email          = $this->input->post('email', TRUE);  
            $otp            = $this->input->post('otp', TRUE);  
            $where          = "((`email` = '".$email."' OR `user_name` = '".$email."') AND `activation_code` = '".$otp."')";
            $row            = $this->common_model->get_row('users', array(), array(), array(), $where);
            if(!empty($row)){                
                $data['responseCode'] = 200;
                $data['message']      = 'OTP is matched';            
            }else{
                $data['responseCode'] = 500;
                $data['message']      = 'OTP is wrong';  
            }
            $this->response($data);
        }
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data); 
    }
    public function create_new_password_post() {             
        $user_id    = '';
        $responseCode   = 500;
        $message    = 'Invalid Request';
        $this->form_validation->set_rules('email', 'User name', 'trim|required|xss_clean'); 
        $this->form_validation->set_rules('new_password', 'new password', 'trim|required|xss_clean|min_length[6]');
        $this->form_validation->set_error_delimiters('', '');  
        if($this->form_validation->run() == TRUE){ 
            $email          = $this->input->post('email', TRUE);  
            $otp            = $this->input->post('otp', TRUE);  
            $where          = "(`email` = '".$email."' OR `user_name` = '".$email."')";
            $row            = $this->common_model->get_row('users', array(), array(), array(), $where);
            if(!empty($row->id)){
                $salt       = salt();
                $user_data  = array('salt'=>$salt,
                                    'password' => sha1($salt.sha1($salt.sha1($this->input->post('new_password', TRUE)))),
                                    'activation_code'=>''
                                    );
                if($this->common_model->update('users', $user_data, array('id'=>$row->id))){                     
                    $data['responseCode'] = 200;
                    $data['message']      = 'Password is changed successfully';            
                }else{
                    $data['responseCode'] = 200;
                    $data['message']      = 'Password update failed, try again'; 
                }             
            }else{
                $data['responseCode'] = 500;
                $data['message']      = 'Email is wrong';  
            }
            $this->response($data);
        }
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data); 
    }
    /*****************  get user profile *************************/
    public function get_user_profile_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';
        $this->form_validation->set_rules('user_id', 'user id', 'xss_clean|required');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){   
            $user = $this->common_model->get_row('users', array('id'=>$this->input->post('user_id')));
            $userdata                 = array();    
            $userdata['fullName']     = !empty($user->first_name)?$user->first_name:'';   
            $userdata['about']        = !empty($user->about)?$user->about:'';   
            $userdata['dateOfBirth']  = !empty($user->date_of_birth)?$user->date_of_birth:''; 
            $userdata['gender']       = !empty($user->gender)?$user->gender:'';  
            if(!empty($user->useMetricsSystem)&&$user->useMetricsSystem==1){
                $userdata['heightId']           = $user->height;
                $userdata['heightText']         = !empty($user->height)?number_format($user->height, 0).' cm':'';
                $userdata['weight']             = $user->weight;
                $userdata['useMetricsSystem']   = 1;
            }else{
            	$inches      = $user->height/2.54; 
                $feetInch    = "";
                $feetInch   .= floor($inches/12)."'";
                $feetInch   .= floor($inches%12);
                $userdata['heightId']           = $user->height;
                $userdata['heightText']         = $feetInch;
                $userdata['weight']             = $user->weight;
                $userdata['useMetricsSystem']   = 0;
            } 
            $userdata['profile_visibility'] = !empty($user->privacy)?$user->privacy:1;                
            $data['responseCode']   = 200;
            $data['message']        = "User profile is feched successfully";           
            $data['userDetails']    = $userdata;
            $this->response($data);
        }  
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }
    /*****************  edit user profile *************************/
    public function edit_profile_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';
        $this->form_validation->set_rules('user_id', 'user id', 'xss_clean|required');
        $this->form_validation->set_rules('dateOfBirth', 'date of birth', 'xss_clean|required');
        $this->form_validation->set_rules('gender', 'gender', 'xss_clean|required');       
        $this->form_validation->set_rules('about', 'about', 'xss_clean');   
        $this->form_validation->set_rules('fullName', 'full name', 'xss_clean');   
        $this->form_validation->set_rules('heightId', 'height', 'xss_clean|required');     
        $this->form_validation->set_rules('weight', 'weight', 'xss_clean|required');   
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){        
            $userdata                   = array();        
            if($this->input->post('fullName'))
                $userdata['first_name']  = $this->input->post('fullName', TRUE);   
            if($this->input->post('about'))
                $userdata['about']  = $this->input->post('about', TRUE);   
            if($this->input->post('dateOfBirth'))
                $userdata['date_of_birth']  = $this->input->post('dateOfBirth', TRUE);  
            if($this->input->post('gender'))
                $userdata['gender']  = strtolower($this->input->post('gender', TRUE)); 
            if($this->input->post('heightId'))
                $userdata['height']      = $this->input->post('heightId', TRUE); 
            if($this->input->post('weight'))
                $userdata['weight']      = $this->input->post('weight', TRUE);
            $userdata['profile_view_status'] = 1;            
            if(!empty($userdata)){
                $update = $this->common_model->update('users', $userdata, array('id'=>$this->input->post('user_id')));
                //echo $this->db->last_query();
                if($update){
                    $data['responseCode']   = 200;
                    $data['message']        = "Profile is changed successfully";
                }else{
                    $data['responseCode'] = 500;
                    $data['message']      = "Profile changes  failed";
                }             
                $this->response($data);
            } 
        }  
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);      
    } 
    public function get_profile_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';       
        $this->form_validation->set_rules('user_id', 'user id', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $userID      = $this->input->post('user_id');
            $user        = user_info($userID);
            $userDetails = array();
            if(!empty($user->profile_pic)&&file_exists('assets/uploads/users/thumbnails/'.$user->profile_pic)){
                $userDetails['profile_pic'] = base_url().'assets/uploads/users/thumbnails/'.$user->profile_pic;
            }else{
                $pics = site_info('default_user_pic');
                if(!empty($pics)&&file_exists($pics)){
                    $userDetails['profile_pic'] = base_url().$pics;
                } 
            } 
            $FollowingCount = get_all_count('follow_request', array('sender_id'=>$userID, 'accepted_status'=>1));
            $FollowersCount = get_all_count('follow_request', array('receiver_id'=>$userID, 'accepted_status'=>1));
            if($FollowingCount>1000){
              $FollowingCount = number_format(($FollowingCount/1000), 1).' k';
            }
            if($FollowersCount>1000){
              $FollowersCount = number_format(($FollowersCount/1000), 1).' k';
            }
            $userName = '';
            $userName .= !empty($user->user_name)?$user->first_name:'';
            $userName .= !empty($user->last_name)?$user->last_name:'';
            $userDetails['user_name']       = !empty($user->user_name)?$user->user_name:'';
            $userDetails['fullName']        = $userName;
            $userDetails['about']           = !empty($user->about)?$user->about:'';
            $userDetails['FollowingCount']  = !empty($FollowingCount)?$FollowingCount:'';
            $userDetails['FollowersCount']  = !empty($FollowersCount)?$FollowersCount:'';
            $data['responseCode']           = 200;
            $data['message']                = 'user details & Item list is fetched';
            $data['userDetails']            = $userDetails;
            $items                          = array();
            $itemsA                          = $this->api_model->get_recipe_blog_images_user_following($userID);
            if(!empty($itemsA)){
                foreach($itemsA as $item){
                    $imageName = '';
                    if(!empty($item->image_name)&&file_exists('assets/uploads/recipeBlogImages/'.$item->image_name)){
                        $imageName = base_url().'assets/uploads/recipeBlogImages/'.$item->image_name;
                    }else if(!empty($item->coverImage)&&file_exists('assets/uploads/recipeBlogImages/'.$item->coverImage)){
                        $imageName = base_url().'assets/uploads/recipeBlogImages/'.$item->coverImage;
                    }
                    $items[] = array('itemID'       => !empty($item->id)?$item->id:'', 
                                     'type'         => !empty($item->type)?$item->type:'', 
                                     'title'        => !empty($item->title)?$item->title:'',
                                     'description'  => !empty($item->description)?$item->description:'',
                                     'image_name'   => !empty($imageName)?$imageName:'',
                                  );
                }
            }
            $data['items'] = $items;
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);      
    } 
    public function get_other_profile_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';       
        $this->form_validation->set_rules('user_id', 'user id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('other_user_id', 'other user id', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $userID         = $this->input->post('user_id');
            $other_user_id  = $this->input->post('other_user_id');
            $user           = user_info($other_user_id);
            $userDetails    = array();
            if(!empty($user->profile_pic)&&file_exists('assets/uploads/users/thumbnails/'.$user->profile_pic)){
                $userDetails['profile_pic'] = base_url().'assets/uploads/users/thumbnails/'.$user->profile_pic;
            }else{
                $pics = site_info('default_user_pic');
                if(!empty($pics)&&file_exists($pics)){
                    $userDetails['profile_pic'] = base_url().$pics;
                } 
            } 
            $FollowingCount = get_all_count('follow_request', array('sender_id'=>$other_user_id, 'accepted_status'=>1));
            $FollowersCount = get_all_count('follow_request', array('receiver_id'=>$other_user_id, 'accepted_status'=>1));
            if($FollowingCount>1000){
              $FollowingCount = number_format(($FollowingCount/1000), 1).' k';
            }
            if($FollowersCount>1000){
              $FollowersCount = number_format(($FollowersCount/1000), 1).' k';
            }
            $userName = '';
            $userName .= !empty($user->user_name)?$user->first_name:'';
            $userName .= !empty($user->last_name)?$user->last_name:'';
            $userDetails['user_name']       = !empty($user->user_name)?$user->user_name:'';
            $userDetails['fullName']        = $userName;
            $userDetails['about']           = !empty($user->about)?$user->about:'';
            $userDetails['FollowingCount']  = !empty($FollowingCount)?$FollowingCount:'';
            $userDetails['FollowersCount']  = !empty($FollowersCount)?$FollowersCount:'';
            $userDetails['followStatus']    = 'Follow';
            if(get_all_count('follow_request', array('sender_id'=>$userID, 'receiver_id'=>$other_user_id, 'accepted_status'=>1))){
                $userDetails['followStatus']    = 'Unfollow';
            }
            $data['responseCode']           = 200;
            $data['message']                = 'other user details & Item list is fetched';
            $data['userDetails']            = $userDetails;
            $items                          = array();
            $itemsA                          = $this->api_model->get_recipe_blog_images($userID);
            if(!empty($itemsA)){
                foreach($itemsA as $item){
                    $imageName = '';
                    if(!empty($item->image_name)&&file_exists('assets/uploads/recipeBlogImages/'.$item->image_name)){
                        $imageName = base_url().'assets/uploads/recipeBlogImages/'.$item->image_name;
                    }else if(!empty($item->coverImage)&&file_exists('assets/uploads/recipeBlogImages/'.$item->coverImage)){
                        $imageName = base_url().'assets/uploads/recipeBlogImages/'.$item->coverImage;
                    }
                    $items[] = array('itemID'       => !empty($item->id)?$item->id:'', 
                                     'type'         => !empty($item->type)?$item->type:'', 
                                     'title'        => !empty($item->title)?$item->title:'',
                                     'description'  => !empty($item->description)?$item->description:'',
                                     'image_name'   => !empty($imageName)?$imageName:'',
                                  );
                }
            }
            $data['items'] = $items;
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);      
    } 
    /*******************!!!!!!!!!!!!!!!        Project Ralated Services         !!!!!!!!!!! ******************/
    public function getHeight_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';
        $this->form_validation->set_rules('type', 'type', 'xss_clean|required');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){        
            $userData   = array();   
            if($this->input->post('type')=='height_cm'){
	            for($hi = 91.44;$hi<305;$hi++){                    
                    $userData[]   = array(
                                             'height_id'=>$hi, 
                                            'height_text'=>number_format($hi, 0).' cm'
                                         );
                 } 
	       	}else{
	       		$hieghtStrCm = 88.9;
	       		for($hi=1;$hi<86;$hi++){
	       			$hieghtStrCm = $hieghtStrCm + 2.54; 
	       			$inches      = $hieghtStrCm /2.54; 
	                $feetInch    = "";
	                $feetInch   .= floor($inches/12)."'";
	                $feetInch   .= floor($inches%12);	   				
	                $userData[]   = array(
                                             'height_id'=>$hieghtStrCm, 
                                            'height_text'=>$feetInch
                                         );                        			
	       		} 
	       	}
            $data['responseCode']   = 200;
            $data['resultList']     = $userData;
            $data['message']        = 'Height list is fetched';
            $this->response($data);
        }  
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);      
    }
    public function getPlans_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';
        $rows = $this->common_model->get_result('plans', array('status'=>1));
        $this->form_validation->set_error_delimiters('', ''); 
        if(!empty($rows)){
            foreach ($rows as $row) {
                $userData[]   = array(
                                    'plan_id'    => $row->id, 
                                    'plan_title' => ucwords($row->plan_title).' $'.number_format($row->amount,1),
                                    'message'    => $row->message
                                );
            }
        }
        $data['responseCode']   = 200;
        $data['resultList']     = $userData;
        $data['message']        = 'Plan list is fetched';
        $this->response($data);   
    }
    public function edit_setting_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';       
        $this->form_validation->set_rules('user_id', 'user id', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('profileVisibility', 'profile visibility', 'xss_clean');   
        $this->form_validation->set_rules('advancedMetricsTracking', 'advanced metrics tracking', 'xss_clean');   
        $this->form_validation->set_rules('useMetricsSystem', 'profile visibility', 'trim|xss_clean'); 
        $this->form_validation->set_rules('contactEmail', 'contact email', 'trim|xss_clean|valid_email');
        $this->form_validation->set_rules('contactMobile', 'contact mobile', 'trim|xss_clean|numeric');  
        $this->form_validation->set_rules('paypalEmail', 'paypal email', 'trim|xss_clean|valid_email');
        $this->form_validation->set_rules('modify_plan_id', 'modify plan ID', 'trim|xss_clean');
        if($this->input->post('newPassword')){
            $this->form_validation->set_rules('oldPassword', 'Old password', 'trim|required|xss_clean|callback_password_check');
            $this->form_validation->set_rules('newPassword', 'new password', 'trim|required|xss_clean|min_length[6]|matches[confirmPassword]');
            $this->form_validation->set_rules('confirmPassword','confirm password', 'trim|required');
        }
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){               
            $userdata                   = array();        
            if($this->input->post('profileVisibility'))
                $userdata['privacy']  = $this->input->post('profileVisibility', TRUE);   
            if($this->input->post('advancedMetricsTracking'))
                $userdata['advancedMetricsTracking']  = $this->input->post('advancedMetricsTracking', TRUE); 
            if($this->input->post('useMetricsSystem')&&$this->input->post('useMetricsSystem')==1){
                $userdata['useMetricsSystem']  = $this->input->post('useMetricsSystem', TRUE);
            }else{
                $userdata['useMetricsSystem']  = 2;
            }
            $user = user_info($this->input->post('user_id'));
            if($user->useMetricsSystem!=$userdata['useMetricsSystem']){                    
                if($this->input->post('useMetricsSystem')){
                    $userdata['weight']      = number_format($user->weight / 2.2046, 0);  
                }else{
                    $userdata['weight']      = number_format($user->weight * 2.2046, 0); ;  
                }
            }
            if($this->input->post('contactEmail'))
                $userdata['contactEmail']     = $this->input->post('contactEmail', TRUE); 
            if($this->input->post('contactMobile'))
                $userdata['contactMobile']     = $this->input->post('contactMobile', TRUE);  
            if($this->input->post('paypalEmail'))
                $userdata['paypalEmail']     = $this->input->post('paypalEmail', TRUE);   
            if($this->input->post('modify_plan_id'))
                $userdata['plan']     = $this->input->post('modify_plan_id', TRUE);   
            if($this->input->post('newPassword')){
                $salt                 = salt();
                $userdata['salt']     = $salt;
                $userdata['password'] = sha1($salt.sha1($salt.sha1($this->input->post('newPassword', TRUE))));
            }
            if(!empty($userdata)){
                $update = $this->common_model->update('users', $userdata, array('id'=>$this->input->post('user_id')));
                if($update){
                    $data['responseCode']   = 200;
                    $data['message']        = "Setting is changed successfully";
                }else{
                    $data['responseCode'] = 500;
                    $data['message']      = "Setting changes  failed";
                }             
                $this->response($data);
            } 
        }       
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);      
    } 
    public function get_setting_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';       
        $this->form_validation->set_rules('user_id', 'user id', 'trim|xss_clean|required');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $row = $this->api_model->getUserSeting($this->input->post('user_id'));
            if($row->useMetricsSystem==2) $row->useMetricsSystem=0;            
            $data['responseCode']   = 200;
            $data['userSetting']    = $row;
            $data['message']        = 'Setting  is fetched';
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);      
    }    
    public function get_user_metrics_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';
        $this->form_validation->set_rules('user_id', 'user id', 'xss_clean|required');
         $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){   
            $user = $this->common_model->get_row('users', array('id'=>$this->input->post('user_id')));
            if(!empty($user->date_of_birth)){ 
                $dateOfBirthTime = time() - strtotime($user->date_of_birth); 
                $oneYear         = 60*60*24*365;
                $ageDate         = floor($dateOfBirthTime/$oneYear).' Year';
            }
            $userdata                 = array();    
            $userdata['joinedDate']   = !empty($user->created_date)?date('d/m/Y', strtotime($user->created_date)):'';   
            $userdata['age']          = !empty($ageDate)?$ageDate:'';   
            if(!empty($user->metrics_img1)&&file_exists('assets/uploads/users/thumbnails/'.$user->metrics_img1)){
                $uploadFile = base_url().'assets/uploads/users/thumbnails/'.$user->metrics_img1;
            }else{
               $uploadFile = FRONT_THEAM_PATH.'img/img-7.jpg';
            }            
            $userdata['leftMetricsImage']   = !empty($uploadFile)?$uploadFile:''; 
            if(!empty($user->useMetricsSystem)&&$user->useMetricsSystem==1){
                $userdata['left_height']        = !empty($user->height)?number_format($user->height, 0).' cm':'';
                $userdata['left_weight']        = $user->weight.' kg';              
            }else{
            	$inches      = $user->height/2.54; 
                $feetInch    = "";
                $feetInch   .= floor($inches/12)."'";
                $feetInch   .= floor($inches%12);
                $userdata['left_height']         = $feetInch;
                $userdata['left_weight']         = $user->weight.' lbs';
            } 
            $mrightImg = $this->common_model->get_result('metricTracker', array('user_id'=>$this->input->post('user_id'), 'bodyShot !='=>'','matrixDate <='=>strtotime(date('Y-m-d'))), array('bodyShot', 'weight', 'height'), array('matrixDate', 'desc'));

            if(!empty($mrightImg[0]->bodyShot)&&file_exists('assets/uploads/matrix/thumbnails/'.$mrightImg[0]->bodyShot)){
                $userdata['rightMetricsImage']  = base_url('assets/uploads/matrix/thumbnails/'.$mrightImg[0]->bodyShot);
                $userdata['right_weight']       = $mrightImg[0]->weight;
                $userdata['right_height']       = $mrightImg[0]->height;
            }else if(!empty($mrightImg[1]->bodyShot)&&file_exists('assets/uploads/matrix/thumbnails/'.$mrightImg[1]->bodyShot)){
                $userdata['rightMetricsImage'] = base_url('assets/uploads/matrix/thumbnails/'.$mrightImg[1]->bodyShot);
                $userdata['right_weight']       = $mrightImg[1]->weight;
                $userdata['right_height']       = $mrightImg[1]->height;
            }else{
                if($user->gender=='female'){
                    $userdata['rightMetricsImage'] = FRONT_THEAM_PATH.'img/femaleIcon.png';
                }else{
                    $userdata['rightMetricsImage'] = FRONT_THEAM_PATH.'img/maleIcon.png';
                }
                $userdata['right_weight']       = " 5'9";
                $userdata['right_height']       = " 170 lbs";
            }             
            $data['responseCode']   = 200;
            $data['message']        = "User profile is feched successfully";           
            $data['userDetails']    = $userdata;
            $this->response($data);
        }  
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }
    public function goal_set_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';       
        $this->form_validation->set_rules('user_id', 'user id', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('height', 'height', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('weight', 'weight', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('goal_type', 'goal type', 'trim|xss_clean|required');   
        $this->form_validation->set_rules('loseWeight', 'lose weight', 'trim|xss_clean');  
        $this->form_validation->set_rules('loseDay', 'day', 'trim|xss_clean');  
        $this->form_validation->set_rules('client_c', 'client', 'trim|xss_clean');  
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){               
            $insertedData                   = array();        
            if($this->input->post('height')){
                $insertedData['height'] = $this->input->post('height', TRUE);
            }
            if($this->input->post('weight')){
                $insertedData['wieght'] = $this->input->post('weight', TRUE);
            }
            if($this->input->post('goal_type')){
                $insertedData['goal_type'] = $this->input->post('goal_type', TRUE);
            }
            if($this->input->post('loseWeight')){
                $insertedData['loseWeight'] = $this->input->post('loseWeight', TRUE);
            }
            if($this->input->post('loseDay')){
                $insertedData['loseDay'] = $this->input->post('loseDay', TRUE);
            }
            if($this->input->post('client_c')){
                $insertedData['client_c'] = $this->input->post('client_c', TRUE);
            }
            $insertedData['user_id'] = $this->input->post('user_id', TRUE);
            $data['responseCode']    = 200;
            if($this->input->post('id')){
                $this->common_model->update('goal_setter', $insertedData, array('id'=>$this->input->post('id')));
                $data['message']        = "Goal  is changed successfully";
                $data['goalID']   		= $this->input->post('id');
            }else{
                $goalID 		   		= $this->common_model->insert('goal_setter', $insertedData);
                $data['message']   		= "Goal is set successfully";
                $data['goalID']   		= $goalID;
            }
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);      
    } 
    public function create_workout_plan_post(){ 
        $data['responseCode']    = 200;
        $data['message']         = "Workout plan is feched successfully";  
        //$data['exercisesTypes']  = $this->api_model->getServicePlanItem('2');
        if($this->input->post('plan_id')){
            $data['exercisesTypes'] = $this->api_model->getServicePlanItem('2', $this->input->post('plan_id'));
        }else{
            $data['exercisesTypes'] = $this->api_model->getServicePlanItem('2');
        }
        $this->response($data);
    }
    public function get_workout_plan_exercises_post(){ 
        //print_r($_POST['serviceItemID']);//exit();
        $responseCode   = 500;
        $message        = 'Invalid Request';      
        $this->form_validation->set_rules('serviceItemID[]', 'service Item ID', 'trim|xss_clean|required'); 
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){
            $exercisesType_ids     = $this->input->post('serviceItemID');
            if(!empty($exercisesType_ids)){ 
                if($this->input->post('plan_id')){
                    $planIDS = $this->api_model->getServiceItemStepSecond($this->input->post('plan_id')); 
                }               
                $exercisesType_id      = implode(',', $exercisesType_ids);
                //echo $exercisesType_id; exit();
                $exercises             = $this->api_model->get_user_exercise($exercisesType_id);
                //print_r($exercises); exit();
                $exmainArr             = array();
                if(!empty($exercises)){
                    foreach($exercises as $exercise){
                        $exercisesNw = array();
                        $exercisesNw['exercise_id']          = !empty($exercise->exerciseID)?$exercise->exerciseID:'';
                        $exercisesNw['exercise_title']       = !empty($exercise->exercise_title)?$exercise->exercise_title:'';
                        $exercisesNw['cacalories']           = !empty($exercise->cacalories)?$exercise->cacalories:'';
                        $exercisesNw['exercise_details']     = !empty($exercise->exercise_details)?$exercise->exercise_details:'';
                        $exercisesNw['exercise_pic']         = (!empty($exercise->exercise_pic)&&file_exists('assets/uploads/plansExercise/thumbnails/'.$exercise->exercise_pic))?base_url().'assets/uploads/plansExercise/thumbnails/'.$exercise->exercise_pic:'';
                        $exercisesNw['exercise_instruction'] = !empty($exercise->exercise_instruction)?$exercise->exercise_instruction:'';
                        $exercisesNw['exercise_tag']         = !empty($exercise->exercise_tag)?$exercise->exercise_tag:'';
                        $exercisesNw['measureUnit']          = !empty($exercise->measureUnit)?$exercise->measureUnit:1;
                        $exercisesNw['setRepsStatus']        = (!empty($exercise->category_id)&&$exercise->category_id==2)?1:0;
                        $exercisesNw['isSelected']           = false;
                        if(!empty($planIDS)&&in_array($exercise->exerciseID, $planIDS)){
                            $exercisesNw['isSelected'] = true;
                        }
                        $exmainArr[] = $exercisesNw;
                    }
                }                
                $data['exercises']       = $exmainArr;
                $data['responseCode']    = 200;
                $data['message']         = 'The exercises Item list.';
            }else{
                $data['responseCode']    = 500;
                $data['message']       = 'The service Item ID field is required.';
            }
            $this->response($data);
        }
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);
    }
    public function edit_workout_plan_exercises_post(){
        $responseCode   = 500;
        $this->form_validation->set_rules('plan_id', 'plan id', 'trim|xss_clean|required'); 
        $this->form_validation->set_rules('exercise_id[]', 'exercise ID', 'trim|xss_clean|required'); 
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){
            $exercisesType_ids     = $this->input->post('serviceItemID');           
            if($this->input->post('plan_id')){
                $planIDS = $this->api_model->getServiceItemStepSecond($this->input->post('plan_id')); 
            } 
            $exercises = $this->input->post('exercise_id');
            $plan_row  = $this->common_model->get_row('userPlans', array('id'=>$this->input->post('plan_id'))); 
            $planArrdA = $planArrdN = array();
            if(!empty($exercises)){
                foreach($exercises as $exercise){
                    $userItems = $this->common_model->get_row('service_plan_works_new', array('exercise_id'=>$exercise, 'plan_id'=>$this->input->post('plan_id')));
                    $exerciseRow = $this->common_model->get_row('service_plan_user_exercise', array('id'=>$exercise));
                    $planArrd = array();
                    $planArrd['exerciseID']   = !empty($exercise)?$exercise:'';
                    $planArrd['Name']         = !empty($exerciseRow->exercise_title)?$exerciseRow->exercise_title:'';
                    $planArrd['minuts']       = !empty($userItems->minuts)?$userItems->minuts:'30';
                    $planArrd['measureUnit']  = !empty($userItems->measureUnit)?ucwords($userItems->measureUnit):1;
                    $planArrd['monSelected']  = !empty($userItems->monday)?true:false;
                    $planArrd['tuesSelected'] = !empty($userItems->tuesday)?true:false;
                    $planArrd['wedSelected']  = !empty($userItems->wednesday)?true:false;
                    $planArrd['thusSelected'] = !empty($userItems->thursday)?true:false;
                    $planArrd['friSelected']  = !empty($userItems->friday)?true:false;
                    $planArrd['satSelected']  = !empty($userItems->saturday)?true:false;
                    $planArrd['sunSelected']  = !empty($userItems->sunday)?true:false;
                    $planArrdA[]              = $planArrd;
                }
            }
            $data['responseCode']      = 200; 
            $data['message']           = 'The service Item list.';
            $data['plan_name']         = $plan_row->plan_name;
            $data['plan_description']  = $plan_row->plan_description;
            $data['goal_id']           = $plan_row->goal_id;
            $data['exercises']         = $planArrdA;
            $this->response($data);
        }
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);
    }
    public function save_workout_post(){
        $responseCode   = 500;
        $message        = 'Invalid Request';      
        $this->form_validation->set_rules('plan_name', 'plan name', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('plan_description', 'plan description', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('exercisesTypes[]', 'exercises types', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('exercises[]', 'exercises', 'trim|xss_clean|required'); 
        $this->form_validation->set_rules('goal_id', 'goal_id', 'trim|xss_clean|required'); 
        $this->form_validation->set_rules('user_id', 'user_id', 'trim|xss_clean|required'); 
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){
            $exercisIDS  = $insertedData = array(); 
            if($this->input->post('exercises')){
                $exAlls = $this->input->post('exercises');
                foreach($exAlls as $exAll){
                   $exercisIDS[] =  $exAll['exerciseID'];
                }
            }
            if($this->input->post('exercisesTypes')){
                $insertedData['items']     			= implode(',', $this->input->post('exercisesTypes'));
                $insertedData['exercise']   		= implode(',', $exercisIDS);
                $insertedData['plan_name']   		= ($this->input->post('plan_name'))?$this->input->post('plan_name'):'';
                $insertedData['plan_description']   = ($this->input->post('plan_description'))?$this->input->post('plan_description'):'';
                $insertedData['planType']   		= 2;
                $insertedData['goal_id']    		= $this->input->post('goal_id')?$this->input->post('goal_id'):0;
            }
            $insertedData['user_id'] = $this->input->post('user_id');
            if($this->input->post('plan_id')){
                $this->common_model->update('userPlans', $insertedData, array('id'=>$this->input->post('plan_id')));
                $plan_id     		    = $this->input->post('plan_id');
                $data['message']   		= "Workout plan  is updated successfully";
                $data['responseCode']   = "200";
                $data['goalID']   		= $this->input->post('goal_id')?$this->input->post('goal_id'):0;
            }else{
                $plan_id                = $this->common_model->insert('userPlans', $insertedData);
                $data['message']        = "Workout plan  is added successfully";
                $data['responseCode']   = "200";
                $data['goalID']   		= $this->input->post('goal_id')?$this->input->post('goal_id'):0;
            }
            $exercise = $this->input->post('exercises');
            //print_r($exercise); exit();
            if(!empty($exercise)){
                foreach($exercise as $exRow){
                    //print_r($exRow); exit();
                    $wday           = date('w')-1;
                    $startTimeZone  = strtotime('-'.$wday.' days');
                    $startTimeZone  = strtotime(date('Y-m-d', $startTimeZone));
                    $dayArr         = array('monSelected', 'thusSelected', 'wedSelected', 'thusSelected', 
                                            'friSelected', 'satSelected','sunSelected');
                    if(!empty($exRow['minuts'])||!empty($exRow['sets'])||!empty($exRow['reps'])){  
                        $insertArr                   = array();
                        $insertArr['workout_id']     = $plan_id;
                        $insertArr['exercise_id']    = $exRow['exerciseID'];
                        $insertArr['week_id']        = date('W');
                        $insertArr['user_id']        = $this->input->post('user_id');
                        $insertArr['minuts']         = (!empty($exRow['minuts']))?$exRow['minuts']:0;
                        $insertArr['sets']           = (!empty($exRow['sets']))?$exRow['sets']:0;
                        $insertArr['reps']           = (!empty($exRow['reps']))?$exRow['reps']:0;
                        $insertArr['monday']         = (!empty($exRow['monSelected']))?1:0;
                        $insertArr['tuesday']        = (!empty($exRow['tuesSelected']))?1:0;
                        $insertArr['wednesday']      = (!empty($exRow['wedSelected']))?1:0;
                        $insertArr['thursday']       = (!empty($exRow['thusSelected']))?1:0;
                        $insertArr['friday']         = (!empty($exRow['friSelected']))?1:0;
                        $insertArr['saturday']       = (!empty($exRow['satSelected']))?1:0;
                        $insertArr['sunday']         = (!empty($exRow['sunSelected']))?1:0;
                        $insertArr['status']         = 1;
                        $exeRow                      = $this->common_model->insert('service_plan_works', $insertArr);
                        //$data['sqls'][] = $this->db->last_query();
                    }
                    for($ti=0;$ti<7;$ti++){
                        $startCursZone      = $startTimeZone+(86400*$ti);               
                        if($exRow[$dayArr[$ti]]==TRUE){                            
                            $hoursData                = array();
                            $hoursData['plan_id']     = $plan_id;
                            $hoursData['exercise_id'] = $exRow['exerciseID'];     
                            $hoursData['week_id']     = date('W');       
                            $hoursData['user_id']     = $this->input->post('user_id');       
                            $hoursData['minuts']      = !empty($exRow['minuts'])?$exRow['minuts']:0;;
                            $hoursData['work_out_date']  = $startCursZone;                    
                            $hoursData['created_date']   = date('Y-m-d H:i:s');      
                            $whArr                       = array(  'exercise_id'    => $exRow['exerciseID'], 
                                                                    'user_id'       => $this->input->post('user_id'), 
                                                                    'plan_id'       => $plan_id, 
                                                                    'work_out_date' => $startCursZone
                                                                );                             
                            $exRowC      = $this->common_model->get_row('service_plan_works_new', $whArr);
                            if(!empty($exRowC->id)){
                                $this->common_model->update('service_plan_works_new', $hoursData, array('id'=>$exRowC->id));
                            }else{
                                $this->common_model->insert('service_plan_works_new', $hoursData);
                            }
                        }
                    }                    
                }
            } 
            $this->common_model->update('userPlans', array('activatePlan'=>0), array('user_id'=>$this->input->post('user_id'), 'planType'=>2));
            $this->common_model->update('userPlans', array('activatePlan'=>1), array('id'=>$plan_id));
            $this->response($data);
        }
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data); 
    }
    public function show_exercise_details_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';       
        $this->form_validation->set_rules('plan_id', 'plan id', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $servicePlan           = $this->common_model->get_row('service_plan_user_exercise', 
                                                                  array('id'=>$this->input->post('plan_id'))
                                                                );
            $servicePlan->exercise_pic =  (!empty($servicePlan->exercise_pic)&&file_exists('assets/uploads/plansExercise/thumbnails/'.$servicePlan->exercise_pic))?base_url().'assets/uploads/plansExercise/thumbnails/'.$servicePlan->exercise_pic:'';
            unset($servicePlan->status);
            unset($servicePlan->created_date);
            unset($servicePlan->exercise_tag);
            unset($servicePlan->plan_id);
            $data['responseCode']  = 200;
            $data['message']       = 'Workout plan details is fetched';
            $data['servicePlan']   = $servicePlan;
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }
    public function create_diet_plan_post(){         
        $data['responseCode']    = 200;
        $data['message']         = "Diet Plan plan is feched successfully";
        if($this->input->post('plan_id')){
            $data['foodTypes'] = $this->api_model->getServicePlanItem('1', $this->input->post('plan_id'));
        }else{
            $data['foodTypes'] = $this->api_model->getServicePlanItem('1');
        }
        $this->response($data);
    }
    public function get_diet_item_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';      
        $this->form_validation->set_rules('plan_id', 'plan id', 'trim|xss_clean'); 
        $this->form_validation->set_rules('food_type_id[]', 'food type id', 'trim|xss_clean|required'); 
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){
            if($this->input->post('food_type_id')){ 
                if($this->input->post('plan_id')){
                    $planIDS = $this->api_model->getServiceItemStepSecond($this->input->post('plan_id')); 
                }
                $exercisesType_idDS    = $this->input->post('food_type_id');
                $exercisesType_id      = implode(',', $exercisesType_idDS);
                $items                 = $this->api_model->get_user_diet_item($exercisesType_id);
                $exmainArr             = array();
                if(!empty($items)){
                    foreach($items as $exercise){
                        $exercisesNw = array();
                        $exercisesNw['itemID']       = !empty($exercise->id)?$exercise->id:'';
                        $exercisesNw['item_title']   = !empty($exercise->item_title)?$exercise->item_title:'';
                        $exercisesNw['cacalories']   = !empty($exercise->cacalories)?$exercise->cacalories:'';
                        $exercisesNw['protein']      = !empty($exercise->protein)?$exercise->protein:'';
                        $exercisesNw['fat']          = !empty($exercise->fat)?$exercise->fat:'';
                        $exercisesNw['carbohydrate'] = !empty($exercise->carbohydrate)?$exercise->carbohydrate:'';
                        $exercisesNw['fiber']        = !empty($exercise->fiber)?$exercise->fiber:'';
                        $exercisesNw['suger']        = !empty($exercise->suger)?$exercise->suger:'';
                        $exercisesNw['preparation']  = !empty($exercise->preparation)?$exercise->preparation:'';
                        $exercisesNw['healthiness']  = !empty($exercise->healthiness)?$exercise->healthiness:'';
                        if(!empty($exercise->exercise_pic)&&file_exists('assets/uploads/planItem/thumbnails/'.$exercise->exercise_pic)){
                            $exercisesNw['item_pic'] =  base_url().'assets/uploads/planItem/thumbnails/'.$exercise->exercise_pic;
                        }else if(!empty($exercise->exercise_pic)&&file_exists('assets/uploads/plansExercise/thumbnails/'.$exercise->exercise_pic)){
                            $exercisesNw['item_pic'] = base_url().'assets/uploads/planItem/thumbnails/'.$exercise->exercise_pic;
                        }else{
                            $exercisesNw['item_pic'] = '';
                        }
                        $exercisesNw['isSelected'] = false;
                        if(!empty($planIDS)&&in_array($exercise->id, $planIDS)){
                            $exercisesNw['isSelected'] = true;
                        }
                        $exmainArr[] = $exercisesNw;
                    }
                    $data['dietItems']              = $exmainArr;
                    $data['responseCode']           = 200;
                    $data['message']                = 'The food type is fatched';
                }                
            }else{
                $data['responseCode']           = 500;
                $data['message']                = "The food type id field is required";
            }
            $this->response($data);
        }
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);
    }
    public function edit_diet_plan_food_post(){
        $responseCode   = 500;
        $this->form_validation->set_rules('plan_id', 'plan id', 'trim|xss_clean|required'); 
        $this->form_validation->set_rules('itemID[]', 'item id', 'trim|xss_clean|required'); 
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){
            if($this->input->post('plan_id')){
                $planIDS = $this->api_model->getServiceItemStepSecond($this->input->post('plan_id')); 
            }
            $exercisesType_idDS    = $this->input->post('itemID');
            //$exercisesType_id      = implode(',', $exercisesType_idDS);
            //$items                 = $this->api_model->get_user_diet_item($exercisesType_id); 
            $plan_row  = $this->common_model->get_row('userPlans', array('id'=>$this->input->post('plan_id'))); 
            $planArrdA = $planArrdN = array();
            if(!empty($exercisesType_idDS)){
                foreach($exercisesType_idDS as $exercise){
                    $userItems = $this->common_model->get_row('diet_plan_works_new', array('item_id'=>$exercise, 
                                                                            'plan_id'=>$this->input->post('plan_id')));
                    $foodRow    = $this->common_model->get_row('diet_plan_works_new', array('item_id'=>$exercise));
                    $planArrd   = array();
                    $planArrd['foodID']       = !empty($foodRow->id)?$foodRow->id:'';
                    $planArrd['Name']         = !empty($foodRow->item_title)?$foodRow->item_title:'';
                    $planArrd['mealType']     = !empty($userItems->meal)?ucwords($userItems->meal):'breakfast';
                    $planArrd['serving']      = !empty($userItems->serving)?ucwords($userItems->serving):'1';
                    $planArrd['monSelected']  = !empty($userItems->monday)?true:false;
                    $planArrd['tuesSelected'] = !empty($userItems->tuesday)?true:false;
                    $planArrd['wedSelected']  = !empty($userItems->wednesday)?true:false;
                    $planArrd['thusSelected'] = !empty($userItems->thursday)?true:false;
                    $planArrd['friSelected']  = !empty($userItems->friday)?true:false;
                    $planArrd['satSelected']  = !empty($userItems->saturday)?true:false;
                    $planArrd['sunSelected']  = !empty($userItems->sunday)?true:false;
                    $planArrdA[]              = $planArrd;
                }
            }
            $data['responseCode']      = 200;  
            $data['message']           = 'The food item is fatched';     
            $data['plan_name']         = $plan_row->plan_name;
            $data['plan_description']  = $plan_row->plan_description;
            $data['goal_id']           = $plan_row->goal_id;            
            $data['fooditems']         = $planArrdA; 
            $this->response($data);
        }
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);
    }
    public function save_diet_post(){
        $responseCode   = 500;
        $message        = 'Invalid Request';      
        $this->form_validation->set_rules('plan_name', 'plan name', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('plan_description', 'plan description', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('foodTypes[]', 'food types', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('fooditems[]', 'food items', 'trim|xss_clean|required'); 
        $this->form_validation->set_rules('user_id', 'user id', 'trim|xss_clean|required'); 
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){  
            $foodIDS  = $insertedData = array(); 
            if($this->input->post('fooditems')){
                $foodAlls = $this->input->post('fooditems');
                foreach($foodAlls as $foodAll){
                   $foodIDS[] =  $foodAll['foodID'];
                }
            }         
            if($this->input->post('foodTypes')){
                $insertedData['items']              = implode(',', $this->input->post('foodTypes'));
                $insertedData['exercise']           = implode(',', $foodIDS);
                $insertedData['plan_name']          = ($this->input->post('plan_name'))?$this->input->post('plan_name'):'';
                $insertedData['plan_description']   = ($this->input->post('plan_description'))?$this->input->post('plan_description'):'';
                $insertedData['planType']           = 1;
                $insertedData['goal_id']            = $this->input->post('goal_id')?$this->input->post('goal_id'):0;
            }
            $insertedData['user_id'] = $this->input->post('user_id');
            if($this->input->post('plan_id')){
                $this->common_model->update('userPlans', $insertedData, array('id'=>$this->input->post('plan_id')));
                $plan_id                = $this->input->post('plan_id');
                $data['message']        = "Diet plan  is updated successfully";
                $data['responseCode']   = "200";
                $data['goalID']         = $this->input->post('goal_id')?$this->input->post('goal_id'):0;
            }else{
                $plan_id                = $this->common_model->insert('userPlans', $insertedData);
                $data['responseCode']   = "200";
                $data['message']        = "Diet plan  is added successfully";
                $data['goalID']         = $this->input->post('goal_id')?$this->input->post('goal_id'):0;
            }
            $exercise = $this->input->post('fooditems');
            if(!empty($exercise)){
                foreach($exercise as $exRow){
                    /*$hoursData = array();
                    $hoursData['category_id'] = $diet_id;
                    $hoursData['item_id']     = $exRow['foodID'];
                    $hoursData['meal']        = !empty($exRow['meal'])?$exRow['meal']:0;
                    $hoursData['serving']     = !empty($exRow['serving'])?$exRow['serving']:0;  
                    $hoursData['monday']      = ($exRow['monSelected']==TRUE)?1:0;
                    $hoursData['tuesday']     = ($exRow['thusSelected']==TRUE)?1:0;
                    $hoursData['wednesday']   = ($exRow['wedSelected']==TRUE)?1:0;
                    $hoursData['thursday']    = ($exRow['thusSelected']==TRUE)?1:0;
                    $hoursData['friday']      = ($exRow['friSelected']==TRUE)?1:0;
                    $hoursData['saturday']    = ($exRow['satSelected']==TRUE)?1:0;
                    $hoursData['sunday']      = ($exRow['sunSelected']==TRUE)?1:0;
                    $hoursData['week_id']     = date('W');
                    $exRowNW                  = $this->common_model->get_row('diet_plan_works', array('item_id' => $exRow['foodID'], 'category_id' => $diet_id));
                    if(!empty($exRowNW->id)){
                        $this->common_model->update('diet_plan_works', $hoursData, array('id'=>$exRowNW->id));
                    }else{
                        $this->common_model->insert('diet_plan_works', $hoursData);
                    }*/
                    $wday           = date('w')-1;
                    $startTimeZone  = strtotime('-'.$wday.' days');
                    $startTimeZone  = strtotime(date('Y-m-d', $startTimeZone));
                    $dayArr         = array('monSelected', 'thusSelected', 'wedSelected', 'thusSelected', 
                                            'friSelected', 'satSelected','sunSelected');
                    for($ti=0;$ti<7;$ti++){
                        $startCursZone      = $startTimeZone+(86400*$ti);                      
                        //$checkedDate = $this->input->post($exRow['monSelected']==TRUE);
                        if($exRow[$dayArr[$ti]]==TRUE){                            
                            $hoursData                   = array();
                            $hoursData['plan_id']        = $plan_id;
                            $hoursData['item_id']        = $exRow['foodID'];
                            $hoursData['meal']           = !empty($exRow['meal'])?$exRow['meal']:0;
                            $hoursData['serving']        = !empty($exRow['serving'])?$exRow['serving']:0;    
                            $hoursData['week_id']        = date('W');       
                            $hoursData['user_id']        = $this->input->post('user_id');       
                            $hoursData['diet_date']      = $startCursZone;                    
                            $hoursData['created_date']   = date('Y-m-d H:i:s');      
                            $whArr                       = array(  'item_id'        => $exRow['foodID'], 
                                                                    'user_id'       => $this->input->post('user_id'), 
                                                                    'plan_id'       => $plan_id, 
                                                                    'diet_date'     => $startCursZone
                                                                );                             
                            $exRowC      = $this->common_model->get_row('diet_plan_works_new', $whArr);
                            //echo $this->db->last_query(); exit();
                            if(!empty($exRowC->id)){
                                $this->common_model->update('diet_plan_works_new', $hoursData, array('id'=>$exRowC->id));
                            }else{
                                $this->common_model->insert('diet_plan_works_new', $hoursData);
                            }
                        }
                    }
                    //echo $this->db->last_query();
                }
            } 
            $this->common_model->update('userPlans', array('activatePlan'=>0), array('user_id'=>$this->input->post('user_id'), 'planType'=>1));
            $this->common_model->update('userPlans', array('activatePlan'=>1), array('id'=>$plan_id));
            $this->response($data);
        }
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data); 
    }
    public function get_item_category_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';       
        $this->form_validation->set_rules('user_id', 'user id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('type', 'type', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $userID  = $this->input->post('user_id');
            $type    = $this->input->post('type');
            $items   = array();            
            if($type=='likes'){
                $itemsA  = $this->api_model->getLikesBookMarkBlogImges($userID,'likes');
                //echo $this->db->last_query(); exit();
            }else if($type=='bookmark'){
                $itemsA  = $this->api_model->getLikesBookMarkBlogImges($userID,'bookmark');
            }else if($type=='comments'){
                $itemsA  = $this->api_model->getLikesBookMarkBlogImges($userID,'comments');
            }else{
                $itemsA  = $this->api_model->get_recipe_blog_images($userID, $type);
            }
            if(!empty($itemsA)){
                foreach($itemsA as $item){
                    $imageName = '';
                    if(!empty($item->image_name)&&file_exists('assets/uploads/recipeBlogImages/'.$item->image_name)){
                        $imageName = base_url().'assets/uploads/recipeBlogImages/'.$item->image_name;
                    }else if(!empty($item->coverImage)&&file_exists('assets/uploads/recipeBlogImages/'.$item->coverImage)){
                        $imageName = base_url().'assets/uploads/recipeBlogImages/'.$item->coverImage;
                    }
                    $items[] = array('itemID'       => !empty($item->id)?$item->id:'',
                                     'type'         => !empty($item->type)?$item->type:'',
                                     'title'        => !empty($item->title)?$item->title:'',
                                     'description'  => !empty($item->description)?$item->description:'',
                                     'image_name'   => !empty($imageName)?$imageName:'',
                                     'comments'     => !empty($item->comments)?$item->comments:'',

                                  );
                }
            }
            $data['responseCode']  = 200;
            $data['message']       = 'Item list is fetched';
            $data['type']          = $this->input->post('type');
            $data['items']         = $items;
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }
    public function getFilesType($imageName=''){ 
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
    public function post_details_data($post_id='', $user_id=''){         
        $row                 = $this->api_model->get_post_details($post_id);
        $comments            = $this->api_model->get_comments($post_id, 3);
        $imageName           = array();
        $imgesArrs           = $this->common_model->get_result('images', array('meta_id'=>$post_id));
        $imageName           =  $oldFileName = '';
        //print_r($row); exit();
        $imagesArray         = array();
        if(!empty($row->image_name)&&file_exists('assets/uploads/recipeBlogImages/'.$row->image_name)){
            $imageName   = base_url().'assets/uploads/recipeBlogImages/'.$row->image_name;
            $oldFileName = $row->image_name;
            $imagesArray[] = array('file_type'=>$this->getFilesType($imageName), 'file_path'=>$imageName);
        }else if(!empty($row->coverImage)&&file_exists('assets/uploads/recipeBlogImages/'.$row->coverImage)){
            $imageName = base_url().'assets/uploads/recipeBlogImages/'.$row->coverImage;
            $oldFileName = $row->coverImage;
            $imagesArray[] = array('file_type'=>$this->getFilesType($imageName), 'file_path'=>$imageName);
        }
        if(!empty($imgesArrs)){
            foreach($imgesArrs as $imgesArr){
                if(!empty($imgesArr->image_name)&&file_exists('assets/uploads/recipeBlogImages/'.$imgesArr->image_name)){
                    if($oldFileName != $imgesArr->image_name){
                        $imageName      = base_url().'assets/uploads/recipeBlogImages/'.$imgesArr->image_name;
                        $imagesArray[]  = array('file_type'=>$this->getFilesType($imageName), 'file_path'=>$imageName);
                    }
                }                
            }
        }
        $dataA['post_id']         = !empty($row->id)?$row->id:'';
        $dataA['title']           = !empty($row->title)?$row->title:'';
        $dataA['description']     = !empty($row->description)?$row->description:'';
        $dataA['ingredients']     = !empty($row->ingredients)?$row->ingredients:'';
        $dataA['instructions']    = !empty($row->instructions)?$row->instructions:'';
        $dataA['tags']            = !empty($row->tags)?$row->tags:'';
        $dataA['location']        = !empty($row->location)?$row->location:'';
        $dataA['tag_users']       = !empty($row->tag_users)?$row->tag_users:''; 
        $dataA['posted_user_id']  = !empty($row->user_id)?$row->user_id:'';
        $dataA['posted_userName'] = !empty($row->user_name)?$row->user_name:'';
        $dataA['posted_userPic']  = (!empty($row->profile_pic) && file_exists('assets/uploads/users/thumbnails/'.$row->profile_pic))?base_url().'assets/uploads/users/thumbnails/'.$row->profile_pic:base_url().site_info('default_user_pic');
        $dataA['likesCounter']       = get_all_count('likes', array('recipe_blog_image_id'=>$post_id));
        $dataA['commentsCounter']    = get_all_count('comments', array('recipe_blog_image_id'=>$post_id));
        $dataA['bookmarkCounter']    = get_all_count('bookmark', array('recipe_blog_image_id'=>$post_id));

        if(get_all_count('likes', array('recipe_blog_image_id'=>$post_id, 'user_id'=>$user_id))>0){
            $dataA['like_status'] = 'Like';
        }else{
            $dataA['like_status'] = 'Unlike';
        }
        if(get_all_count('bookmark', array('recipe_blog_image_id'=>$post_id, 'user_id'=>$user_id))>0){
            $dataA['bookmark_status'] = 'Bookmarked';
        }else{
            $dataA['bookmark_status'] = 'Bookmark';
        }        
        if($row->user_id != $user_id){  
            if(get_all_count('follow_request', array('sender_id'=>$user_id, 'receiver_id'=>$row->user_id, 'accepted_status'=>1))){
                $dataA['followStatus']   = 'Unfollow';
            }else if(get_all_count('follow_request', array('sender_id'=>$user_id, 'receiver_id'=>$row->user_id, 'accepted_status'=>4))){
                $dataA['followStatus']   = 'Request Sent';
            }else{
                $dataA['followStatus']   = 'Follow';
            }
        }else{
            $dataA['followStatus']   = '';
        }
        $dataA['comments']       = $comments;
        $dataA['images']         = $imagesArray;
        $dataA['posted']        = cal_times($row->created_date);
        return $dataA;
    }
    public function post_details_post(){ 
        $data           = array();
        $responseCode   = 500;
        $message        = 'Invalid Request';       
        $this->form_validation->set_rules('user_id', 'user id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('post_id', 'post id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('type', 'type', 'trim|xss_clean');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $post_id             = $this->input->post('post_id');
            $post_type           = $this->input->post('type');
            $user_id             = $this->input->post('user_id');
            $insertedData                         = array();        
            $insertedData['user_id']              = $user_id;      
            $insertedData['recipe_blog_image_id'] = $post_id;
            $insertedData['created_date']         = date('Y-m-d H:i:s');    
            $this->common_model->insert('post_views', $insertedData);   
            $mainListArray[]       = $this->post_details_data($post_id,  $user_id);
            if($post_type=='likes'){
                $itemsA  = $this->api_model->getLikesBookMarkBlogImges($user_id,'likes', $post_id, 'random','min');
                $itemsB  = $this->api_model->getLikesBookMarkBlogImges($user_id,'likes', $post_id, 'random','max');
            }else if($post_type=='bookmark'){
                $itemsA  = $this->api_model->getLikesBookMarkBlogImges($user_id,'bookmark', $post_id, 'random','min');
                $itemsB  = $this->api_model->getLikesBookMarkBlogImges($user_id,'bookmark', $post_id, 'random','max');
            }else if($post_type=='comments'){
                $itemsA  = $this->api_model->getLikesBookMarkBlogImges($user_id,'comments', $post_id, 'random','min');
                $itemsB  = $this->api_model->getLikesBookMarkBlogImges($user_id,'comments', $post_id, 'random','min');
            }elseif(!empty($post_type)&&$post_type=='all'){
                $itemsA  = $this->api_model->get_recipe_blog_images($user_id, NULL, $post_id, 'random', 'min');
                $itemsB  = $this->api_model->get_recipe_blog_images($user_id, NULL, $post_id, 'random', 'max');
            }else{
                $itemsA  = $this->api_model->get_recipe_blog_images($user_id,$post_type,$post_id,'random','min');
                $itemsB  = $this->api_model->get_recipe_blog_images($user_id,$post_type,$post_id,'random','max');
            }
            if(!empty($itemsA)){
                foreach($itemsA as $item){
                    $mainListArray[]       = $this->post_details_data($item->id,  $user_id);
                }
            }
            if(!empty($itemsB)){
                foreach($itemsB as $item){
                    $mainListArray[]       = $this->post_details_data($item->id,  $user_id);
                }
            }
            $data['responseCode']   = 200;
            $data['message']        = 'Post details is fetched';
            $data['resultList']     = $mainListArray;
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }
    public function post_all_comments_post(){ 
        $data           = array();
        $responseCode   = 500;
        $message        = 'Invalid Request';       
        $this->form_validation->set_rules('post_id', 'post id', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $data['responseCode']   = 200;
            $post_id                = $this->input->post('post_id');
            $data['comments']       = $this->api_model->get_comments($post_id);
            $data['message']        = 'Post details is fetched';
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }
    public function post_like_post(){ 
        $data           = array();
        $responseCode   = 500;
        $message        = 'Invalid Request';       
        $this->form_validation->set_rules('user_id', 'user id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('post_id', 'post id', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $post_id             = $this->input->post('post_id');
            $user_id             = $this->input->post('user_id');
            if($this->common_model->get_row('likes', array('recipe_blog_image_id'=>$post_id, 'user_id'=>$user_id))){
                $this->common_model->delete('likes', 
                                                    array('recipe_blog_image_id'=>$post_id, 
                                                          'user_id'=>$user_id
                                                        )
                                            ); 
                $likeStatus             = 'Unlike';
                $data['message']        = 'Post is unlike';
            }else{
                $insertedData                         = array();        
                $insertedData['user_id']              = $user_id;      
                $insertedData['recipe_blog_image_id'] = $post_id;        
                $insertedData['created_date']         = date('Y-m-d H:i:s');    
                $this->common_model->insert('likes', $insertedData); 
                $likeStatus = 'Like';
                $data['message']        = 'Post is like';
            }
            $data['responseCode']   = 200;
            $data['likeStatus']     = $likeStatus;
            $data['likes']          = get_all_count('likes', array('recipe_blog_image_id'=>$post_id));
           
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }
    public function post_bookmark_post(){ 
        $data           = array();
        $responseCode   = 500;
        $message        = 'Invalid Request';       
        $this->form_validation->set_rules('user_id', 'user id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('post_id', 'post id', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $post_id             = $this->input->post('post_id');
            $user_id             = $this->input->post('user_id');
            if($this->common_model->get_row('bookmark', array('recipe_blog_image_id'=>$post_id, 'user_id'=>$user_id))){
                $this->common_model->delete('bookmark', 
                                                    array('recipe_blog_image_id'=>$post_id, 
                                                          'user_id'=>$user_id
                                                        )
                                            ); 
                $likeStatus = 'Bookmark';
                $data['message']            = 'Post is Bookmark';
            }else{
                $insertedData                         = array();        
                $insertedData['user_id']              = $user_id;      
                $insertedData['recipe_blog_image_id'] = $post_id;        
                $insertedData['created_date']         = date('Y-m-d H:i:s');    
                $this->common_model->insert('bookmark', $insertedData); 
                $likeStatus = 'Bookmarked';
                $data['message']            = 'Post is Bookmarked';
            }
            $data['responseCode']       = 200;
            $data['bookmarkStatus']     = $likeStatus;
            $data['bookmarks']          = get_all_count('bookmark', array('recipe_blog_image_id'=>$post_id));
            
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }
    public function post_comment_post(){ 
        $data           = array();
        $responseCode   = 500;
        $message        = 'Invalid Request';       
        $this->form_validation->set_rules('user_id', 'user id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('post_id', 'post id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('comment', 'comment', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $post_id             = $this->input->post('post_id');
            $user_id             = $this->input->post('user_id');
            $insertedData['user_id']              = $user_id; 
            $insertedData['recipe_blog_image_id'] = !empty($post_id)?$post_id:'';     
            $insertedData['comments']             = $this->input->post('comment')?$this->input->post('comment'):'';
            $insertedData['created_date']         = date('Y-m-d H:i:s');    
            $this->common_model->insert('comments', $insertedData);
            $data['responseCode']       = 200;
            $data['commentsCounter']    = get_all_count('comments', array('recipe_blog_image_id'=>$post_id));
            $data['comments']           = $this->api_model->get_comments($post_id);;
            $data['message']            = 'comments is posted';
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }
    public function user_follow_post(){ 
        $data           = array();
        $responseCode   = 500;
        $message        = 'Invalid Request';       
        $this->form_validation->set_rules('user_id', 'user id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('other_user_id', 'user id', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $insertedData                         = array();         
            $insertedData['sender_id']            = $this->input->post('user_id');      
            $insertedData['receiver_id']          = $this->input->post('other_user_id'); 
           /* $rows = $this->common_model->get_row('follow_request', $insertedData);
            echo '<pre>';print_r($rows); exit();*/
            if($this->common_model->get_row('follow_request', $insertedData)){
                $this->common_model->delete('follow_request', $insertedData);
                $FollowingCount = get_all_count('follow_request', array('sender_id'=>$this->input->post('user_id'), 'accepted_status'=>1));
                $FollowersCount = get_all_count('follow_request', array('receiver_id'=>$this->input->post('other_user_id'), 'accepted_status'=>1));
                if($FollowingCount>1000){
                    $FollowingCount = number_format(($FollowingCount/1000), 1).' k';
                }
                if($FollowersCount>1000){
                    $FollowersCount = number_format(($FollowersCount/1000), 1).' k';
                }
                $FollowerStatus = 'Follow';
            }else{
                if($this->common_model->get_row('users', array('id'=>$this->input->post('other_user_id'), 'privacy'=>2))){
                    $insertedData['accepted_status']  = '4';
                    $FollowerStatus = 'Request Sent';
                }else{
                    $insertedData['accepted_status']  = '1';
                    $FollowerStatus = 'Unfollow';
                }
                $this->common_model->insert('follow_request', $insertedData);
                $FollowingCount = get_all_count('follow_request', array('sender_id'=>$this->input->post('user_id'), 'accepted_status'=>1));
                $FollowersCount = get_all_count('follow_request', array('receiver_id'=>$this->input->post('other_user_id'), 'accepted_status'=>1));
                if($FollowingCount>1000){
                    $FollowingCount = number_format(($FollowingCount/1000), 1).' k';
                }
                if($FollowersCount>1000){
                    $FollowersCount = number_format(($FollowersCount/1000), 1).' k';
                }               
            }
            $data['responseCode']       = 200;
            $data['FollowingCount']     = $FollowingCount;
            $data['FollowersCount']     = $FollowersCount;
            $data['FollowerStatus']     = $FollowerStatus;
            $data['message']            = 'Follow is updated';
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    } 
    public function get_follow_request_post(){ 
        $data           = array();
        $responseCode   = 500;
        $message        = 'Invalid Request';       
        $this->form_validation->set_rules('user_id', 'user id', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $requestsNew = array();
            $requests    = $this->api_model->getFollowerUserReq($this->input->post('user_id'));
            if(!empty($requests)){
                foreach($requests as $request){
                    $request->profile_pic = (!empty($request->profile_pic)&&file_exists('assets/uploads/users/thumbnails/'.$request->profile_pic))?base_url('assets/uploads/users/thumbnails/'.$request->profile_pic):base_url().site_info('default_user_pic');
                    $requestsNew[] =   $request;                 
                }                
            }
            $data['responseCode']       = 200;
            $data['message']            = 'Follow request list';
            $data['followRequests']     = $requestsNew;
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }
    public function change_follow_request_status_post(){ 
        $data           = array();
        $responseCode   = 500;
        $message        = 'Invalid Request';       
        $this->form_validation->set_rules('user_id', 'user id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('request_id', 'request id', 'trim|required|xss_clean');
        $this->form_validation->set_rules('status', 'status', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $this->common_model->update('follow_request', array('accepted_status'=>$this->input->post('status')), array('id'=>$this->input->post('request_id')));
            $data['message']            = 'Request status is changed';
            $data['responseCode']       = 200;
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }
    public function get_followers_post(){ 
        $data           = array();
        $responseCode   = 500;
        $message        = 'Invalid Request';       
        $this->form_validation->set_rules('user_id', 'user id', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $usersNew = array();
            $users    = $this->api_model->getFollowerUser($this->input->post('user_id'));
            if(!empty($users)){
                foreach($users as $user){
                    $user->profile_pic = (!empty($user->profile_pic)&&file_exists('assets/uploads/users/thumbnails/'.$user->profile_pic))?base_url('assets/uploads/users/thumbnails/'.$user->profile_pic):base_url().site_info('default_user_pic');
                    if($user->other_user_id==$this->input->post('user_id')){
                        $user->status = '';
                    }else if(get_all_count('follow_request', array('sender_id' => $this->input->post('user_id'), 'receiver_id' => $user->other_user_id, 'accepted_status'=>1))>0){
                       $user->status = 'Unfollow'; 
                    }else if(get_all_count('follow_request', array('sender_id' => $this->input->post('user_id'), 'receiver_id' => $user->other_user_id, 'accepted_status'=>4))>0){
                        $user->status = 'Request Sent'; 
                    }else{                        
                        $user->status = 'Follow'; 
                    }
                    $usersNew[]     = $user;                 
                }                
            }
            $data['message']       = 'Follower list';
            $data['responseCode']  = 200;
            $data['followers']     = $usersNew;
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }
    public function get_followings_post(){ 
        $data           = array();
        $responseCode   = 500;
        $message        = 'Invalid Request';       
        $this->form_validation->set_rules('user_id', 'user id', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $usersNew = array();
            $users    = $this->api_model->getFollowingUser($this->input->post('user_id'));
            if(!empty($users)){
                foreach($users as $user){
                    $user->profile_pic = (!empty($user->profile_pic)&&file_exists('assets/uploads/users/thumbnails/'.$user->profile_pic))?base_url('assets/uploads/users/thumbnails/'.$user->profile_pic):base_url().site_info('default_user_pic');
                    if($user->other_user_id==$this->input->post('user_id')){
                        $user->status = '';
                    }else if(get_all_count('follow_request', array('sender_id' => $this->input->post('user_id'), 'receiver_id' => $user->other_user_id, 'accepted_status'=>1))>0){
                       $user->status = 'Unfollow'; 
                    }else if(get_all_count('follow_request', array('sender_id' => $this->input->post('user_id'), 'receiver_id' => $user->other_user_id, 'accepted_status'=>4))>0){
                        $user->status = 'Request Sent'; 
                    }else{                        
                        $user->status = 'Follow'; 
                    }
                    $usersNew[] =   $user;  

                }                
            }
            $data['message']        = 'Following list';
            $data['responseCode']   = 200;
            $data['followings']     = $usersNew;
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }  
    public function get_likes_post(){ 
        $data           = array();
        $responseCode   = 500;
        $message        = 'Invalid Request';       
        $this->form_validation->set_rules('user_id', 'user id', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $usersNew = array();
            $users    = $this->api_model->getUserLikes($this->input->post('user_id'));
            if(!empty($users)){
                foreach($users as $user){
                    $user->profile_pic = (!empty($user->profile_pic)&&file_exists('assets/uploads/users/thumbnails/'.$user->profile_pic))?base_url('assets/uploads/users/thumbnails/'.$user->profile_pic):base_url().site_info('default_user_pic');
                    $user->image_name  = (!empty($user->image_name)&&file_exists('assets/uploads/recipeBlogImages/thumbnails/'.$user->image_name))?base_url('assets/uploads/recipeBlogImages/thumbnails/'.$user->image_name):base_url().site_info('default_user_pic');                    
                    $usersNew[] =   $user;  
                }                
            }
            $data['message']        = 'Likes list';
            $data['responseCode']   = 200;
            $data['likeList']     = $usersNew;
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }  
    public function get_counter_post(){ 
        $data           = array();
        $responseCode   = 500;
        $message        = 'Invalid Request';       
        $this->form_validation->set_rules('user_id', 'user id', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $usersNew                       = array();
            $data['likesCounter']           = $this->api_model->getUserLikes($this->input->post('user_id'), 'counter');
            $data['followRequestCounter']   = $this->api_model->getFollowerUserReqCounter($this->input->post('user_id'), 'counter');
            $data['message']                = 'Get Counter';
            $data['responseCode']           = 200;
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }  
    public function get_find_plan_with_user_list_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';       
        $this->form_validation->set_rules('user_id', 'user id', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('height', 'height', 'trim|xss_clean');  
        $this->form_validation->set_rules('weight', 'weight', 'trim|xss_clean');  
        $this->form_validation->set_rules('goal_type', 'goal type', 'trim|xss_clean|required');   
        $this->form_validation->set_rules('loseWeight', 'lose weight', 'trim|xss_clean');  
        $this->form_validation->set_rules('loseDay', 'day', 'trim|xss_clean');   
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){               
            $height = $wieght =  $goal_type = $loseWeight = $loseDay = $useMetricsSystem = "";        
            if($this->input->post('height')){
                $height = $this->input->post('height', TRUE);
            }
            if($this->input->post('weight')){
                $wieght = $this->input->post('weight', TRUE);
            }
            if($this->input->post('goal_type')){
                $goal_type = $this->input->post('goal_type', TRUE);
            }
            if($this->input->post('loseWeight')){
                $loseWeight = $this->input->post('loseWeight', TRUE);
            }
            if($this->input->post('loseDay')){
                $loseDay = $this->input->post('loseDay', TRUE);
            }
            $userRow   = $this->common_model->get_row('users', array('id'=>$this->input->post('user_id', TRUE)));
            if(!empty($userRow->useMetricsSystem)){
                $useMetricsSystem = 1;
            }
            $usersNew  = array();
            $userLists = $this->api_model->get_user_result($height, $wieght, $goal_type, $loseWeight, $loseDay, $useMetricsSystem);
            if(!empty($userLists)){
                foreach($userLists as $user){
                	$planRow = $this->common_model->get_row('userPlans', array('goal_id'=>$user->goal_id, 'status'=>1));
                	if(!empty($planRow)){                		
	                    $user->profile_pic = (!empty($user->profile_pic)&&file_exists('assets/uploads/users/thumbnails/'.$user->profile_pic))?base_url('assets/uploads/users/thumbnails/'.$user->profile_pic):base_url().site_info('default_user_pic');                    
	                    $usersNew[] =   $user; 
                	}
                }
            }
            $data['responseCode']    = 200;
            $data['userList']        = $usersNew;
            $data['message']         = "Plan User  List fetched successfully";            
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);      
    } 
    public function get_other_diet_workout_plan_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request'; 
        $this->form_validation->set_rules('goal_id', 'goal_id', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('other_user_id', 'goal_id', 'trim|xss_clean|required');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){     
            $user_id      = $this->input->post('other_user_id', TRUE);  
            $goal_id      = $this->input->post('goal_id', TRUE);  
            $user         = $this->common_model->get_row('users', array('id'=>$user_id));
            $goal         = $this->common_model->get_row('goal_setter', array('id'=>$goal_id));
            $dietPlan     = $this->common_model->get_row('userPlans', array('goal_id'=>$goal_id, 'planType'=>1,'status'=>1));
            $workOutPlan  = $this->common_model->get_row('userPlans', array('goal_id'=>$goal_id, 'planType'=>2,'status'=>1));
            if(!empty($workOutPlan)){            	
	            if($workOutPlan->plan_expired=='0000-00-00 00:00:00'){
	                $durationTimes = time() - strtotime($workOutPlan->created_date); 
	                $workStartDate = date('M d, Y', strtotime($workOutPlan->created_date));
	                $workEndDate   = date('M d, Y');
	            }else{
	                $durationTimes = strtotime($workOutPlan->plan_expired) - strtotime($workOutPlan->created_date); 
	                $workStartDate = date('M d, Y', strtotime($workOutPlan->created_date));
	                $workEndDate   = date('M d, Y', strtotime($workOutPlan->plan_expired));
	            }
	            if($durationTimes>12960000){ 
	                $workDur = round($durationTimes/12960000).' year';
	            }else if($durationTimes>12960000){ 
	                $workDur = round($durationTimes/12960000).' month';
	            }else if($durationTimes>216000){ 
	                $workDur = round($durationTimes/216000).' day';
	            }else if($durationTimes>3600){
	                $workDur = round($durationTimes/3600).' hour';
	            }else if($durationTimes>60){ 
	                $workDur = round($durationTimes/60).' min';
	            }else{ 
	                $workDur = $durationTimes.' sec';
	            }
            }
            if(!empty($dietPlan)){ 
	            if(!empty($dietPlan->plan_expired)&&$dietPlan->plan_expired=='0000-00-00 00:00:00'){
	                $durationTimesD = time() - strtotime($dietPlan->created_date); 
	                $dietStartDate  = date('M d, Y', strtotime($dietPlan->created_date));
	                $dietEndDate    = date('M d, Y');
	            }elseif(!empty($dietPlan->plan_expired)){
	                $durationTimesD = strtotime($dietPlan->plan_expired) - strtotime($dietPlan->created_date); 
	                $dietStartDate  = date('M d, Y', strtotime($dietPlan->created_date));
	                $dietEndDate    = date('M d, Y', strtotime($dietPlan->plan_expired));
	            }
	            if($durationTimesD>12960000){ 
	                $dietDur = round($durationTimesD/12960000).' year';
	            }else if($durationTimesD>12960000){ 
	                $dietDur = round($durationTimesD/12960000).' month';
	            }else if($durationTimesD>216000){ 
	                $dietDur = round($durationTimesD/216000).' day';
	            }else if($durationTimesD>3600){
	                $dietDur = round($durationTimesD/3600).' hour';
	            }else if($durationTimesD>60){ 
	                $dietDur = round($durationTimesD/60).' min';
	            }else{ 
	                $dietDur = $durationTimesD.' sec';
	            }
            }
            $userDetails  = $workOutPlanD = $dietPlanD = array();
            $userDetails['user_name']     = $user->user_name;
            $userDetails['user_pic']      = (!empty($user->profile_pic)&&file_exists('assets/uploads/users/thumbnails/'.$user->profile_pic))?base_url('assets/uploads/users/thumbnails/'.$user->profile_pic):base_url().site_info('default_user_pic');
            $userDetails['currentHeight'] = !empty($goal->height)?$goal->height:'';
            $userDetails['currentWieght'] = !empty($goal->wieght)?$goal->wieght:'';
            $userDetails['loseDay']       = !empty($goal->loseDay)?$goal->loseDay:'';
            $userDetails['loseWeight']    = !empty($goal->loseWeight)?$goal->loseWeight:'';
            $workOutPlanD['planID']            = !empty($workOutPlan->id)?$workOutPlan->id:'';
            $workOutPlanD['planName']          = !empty($workOutPlan->plan_name)?$workOutPlan->plan_name:'';
            $workOutPlanD['plan_description']  = !empty($workOutPlan->plan_description)?$workOutPlan->plan_description:'';
            $workOutPlanD['activateFrom']    = !empty($workStartDate)?$workStartDate:'';
            $workOutPlanD['activateTo']      = !empty($workEndDate)?$workEndDate:'';
            $workOutPlanD['duration']        = !empty($workDur)?$workDur:'';
            $workOutPlanD['weightLost ']     = 0;
            $userDetails['workOutPlan']      = !empty($workOutPlanD)?$workOutPlanD:'';   
            $dietPlanD['planID']             = !empty($dietPlan->id)?$dietPlan->id:'';
            $dietPlanD['planName']           = !empty($dietPlan->plan_name)?$dietPlan->plan_name:'';
            $dietPlanD['plan_description']   = !empty($dietPlan->plan_description)?$dietPlan->plan_description:'';
            $dietPlanD['activateFrom']       = !empty($dietStartDate)?$dietStartDate:'';
            $dietPlanD['activateTo']         = !empty($dietEndDate)?$dietEndDate:'';
            $dietPlanD['duration']           = !empty($dietDur)?$dietDur:'';
            $dietPlanD['weightLost ']        = 0;
            $userDetails['dietPlan']         = !empty($dietPlanD)?$dietPlanD:'';
            $data['responseCode']            = 200;
            $data['userList']                = $userDetails;
            $data['message']                 = "User diet and workout plan list fetched successfully";            
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'      => validation_errors()
                     );
        $this->response($data);      
    } 
    public function get_other_workout_plan_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request'; 
        $this->form_validation->set_rules('plan_id', 'plan id', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('goal_id', 'goal id', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('other_user_id', 'goal_id', 'trim|xss_clean|required');
        $this->form_validation->set_rules('startDate', 'start date', 'trim|xss_clean');
        $this->form_validation->set_rules('endDate', 'end date', 'trim|xss_clean');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){     
            $user_id      = $this->input->post('other_user_id', TRUE);  
            $goal_id      = $this->input->post('goal_id', TRUE);  
            $plan_id      = $this->input->post('plan_id', TRUE);  
            $startDate    = $this->input->post('startDate', TRUE);  
            $endDate      = $this->input->post('endDate', TRUE);
            if($this->input->post('startDate')){
                $startTimeZone = strtotime($this->input->post('startDate'));
                $endTimeZone   = strtotime($this->input->post('endDate'));
            }else{
                $wday          = date('w')-1;
                $startTimeZone = strtotime('-'.$wday.' days');
                $endTimeZone   = strtotime('+'.(6-$wday).' days');
            }
            //$currentPlan = $this->api_model->getCurrentPlanDetails(2, $user_id);
            $currentPlan = $this->api_model->getCurrentPlanDetails(2, $user_id,$plan_id);
           /* echo $this->db->last_query();
            echo '<pre>';print_r($currentPlan); exit();*/
            $planDetails = array();
            $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'); 
            if(!empty($days)){
                $i   = 1;
                $ips = 0;
                foreach($days as $day){
                    $exercises = array();
                    $totalCals = $totalWieghts  = $totalMinuts = $totalMinutsT = $totalSets = $totalRegs =  0;
                    if(!empty($currentPlan->id)){
                        $startCursZone = $startTimeZone+(86400*$ips); 
                        $dateTimeZone  = strtotime(date('Y-m-d', $startCursZone));
                        //echo date('Y-m-d H:i:s', $dateTimeZone).' = date time<br/><br/><br/>';
                        $dayDetails    = $this->api_model->getWorkOutPlanDayNew($dateTimeZone, $currentPlan->id, $user_id);
                        //echo $this->db->last_query().'<br/><br/><br/>'; 
                        //print_r($dayDetails); //exit();
                        if(!empty($dayDetails)){
                           foreach($dayDetails as $dayDetail){
                                $exercise  = array();
                                $minutsT   = $calUnitT = $wieght = $regT = $setsT = $minutsT = '';
                                $checkRows = $this->common_model->get_row('workout_exercise_done', array('workout_exercise_id'=>$dayDetail->id, 'day'=>$day)); 
                                $exercise['item_title'] = $dayDetail->item_title;

                                if(!empty($dayDetail->exercise_pic)&&file_exists('assets/uploads/plansExercise/'.$dayDetail->exercise_pic)){
                                    $fileName = base_url().'assets/uploads/plansExercise/'.$dayDetail->exercise_pic;
                                }else if(!empty($dayDetail->exercise_pic)&&file_exists('assets/uploads/plansExercise/thumbnails/'.$dayDetail->exercise_pic)){
                                    $fileName = base_url().'assets/uploads/plansExercise/thumbnails/'.$dayDetail->exercise_pic;
                                }
                                $exercise['calories']        = !empty($dayDetail->cacalories)?$dayDetail->cacalories:'';
                                $exercise['description']     = !empty($dayDetail->description)?$dayDetail->description:'';
                                $exercise['instruction']     = !empty($dayDetail->exercise_instruction)?$dayDetail->exercise_instruction:'';
                                $exercise['exercise_tag']    = !empty($dayDetail->exercise_tag)?$dayDetail->exercise_tag:'';           
                                $exercise['measureUnit']    = !empty($dayDetail->measureUnit)?$dayDetail->measureUnit:'';           
                                $exercise['exercise_pic']    = !empty($fileName)?$fileName:'';
                                if(!empty($dayDetail->cacalories)&&$dayDetail->measureUnit==1){
                                    $calUnit   = ($dayDetail->cacalories*$dayDetail->minuts)/60; 
                                    $calUnitT  = round($calUnit, 1);
                                    if(!empty($checkRows)){
                                        $totalCals = $calUnit+ $totalCals;                     
                                    }
                                }
                                $exercise['calories']   = $calUnitT;                                
                                if(!empty($dayDetail->minuts)&&$dayDetail->measureUnit==1){
                                    $minutsT = $dayDetail->minuts;
                                    if(!empty($checkRows)){
                                        $totalMinuts  = $totalMinuts  + $dayDetail->minuts;
                                    }
                                }
                                $exercise['minuts'] = $minutsT;
                                if(!empty($dayDetail->minuts)&&$dayDetail->measureUnit==2){
                                    $setsT = $dayDetail->minuts;
                                    if(!empty($checkRows)){
                                        $totalSets    = $totalSets + $dayDetail->minuts;
                                    }
                                }
                                $exercise['sets'] = $setsT;
                                if(!empty($dayDetail->minuts)&&$dayDetail->measureUnit==3){
                                    $regT = $dayDetail->minuts;
                                    if(!empty($checkRows)){
                                        $totalRegs    = $totalRegs + $dayDetail->minuts;
                                    }
                                }
                                $exercise['regs'] = $regT;
                                if(!empty($dayDetail->minuts)&&$dayDetail->measureUnit==4){
                                    $wieght = $dayDetail->minuts;
                                    if(!empty($user->useMetricsSystem) && $user->useMetricsSystem==1){
                                        $wieght .=  ' kg';
                                    }else{
                                        $wieght .= ' lbs';
                                    }
                                    if(!empty($checkRows)){
                                        $totalWieghts    = $totalWieghts + $dayDetail->minuts;
                                    }
                                }
                                $exercise['weight']         = $wieght;   
                                $exercise['exerciseStatus'] = 0;
                                if(!empty($checkRows)){
                                    $exercise['exerciseStatus'] = 1;
                                }
                                $exercises[]        = $exercise;                        
                            }
                        }
                    }
                    if($totalMinuts>60){
                        $totalMinuts = $totalMinuts;
                        $totalMinutsT = floor($totalMinuts/60). ' hour ';
                        if($totalMinutsT<10){
                            $totalMinutsT = '0'.$totalMinutsT;
                        }
                        if(($totalMinuts % 60)>0&&($totalMinuts % 60)>9){
                            $totalMinutsT .= ' '.($totalMinuts % 60).' minuts';
                        }elseif(($totalMinuts % 60)>0&&($totalMinuts % 60)<9){
                            $totalMinutsT .= ' 0'.($totalMinuts % 60).' minuts';
                        }
                    }
                    if(!empty($user->useMetricsSystem) && $user->useMetricsSystem==1){
                        $totalWieghts = $totalWieghts.' kg';
                    }else{
                        $totalWieghts = $totalWieghts.' lbs';
                    }
                    $totalCals = round($totalCals);
                    $planDetails[] = array(     'dayName'  =>$day,
                                                'exercises'=>$exercises, 
                                               'totalCals'=>$totalCals,
                                               'totalMinuts'=>$totalMinutsT,
                                               'totalSets'=>$totalSets,
                                               'totalRegs'=>$totalRegs,
                                               'totalWieghts'=>$totalWieghts
                                             );
                    $ips++;                        
                }
            }
            $data['responseCode']  = 200;
            $data['planDetails']   = $planDetails;
            $data['message']       = "User workout list fetched successfully";            
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'      => validation_errors()
                     );
        $this->response($data);      
    }   
    public function get_other_diet_plan_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request'; 
        $this->form_validation->set_rules('plan_id', 'plan id', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('goal_id', 'goal id', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('other_user_id', 'goal_id', 'trim|xss_clean|required');
        $this->form_validation->set_rules('startDate', 'start date', 'trim|xss_clean');
        $this->form_validation->set_rules('endDate', 'end date', 'trim|xss_clean');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){     
            $user_id      = $this->input->post('other_user_id', TRUE);  
            $goal_id      = $this->input->post('goal_id', TRUE);  
            $plan_id      = $this->input->post('plan_id', TRUE);  
            $startDate    = $this->input->post('startDate', TRUE);  
            $endDate      = $this->input->post('endDate', TRUE);
            if($this->input->post('startDate')){
                $startTimeZone = strtotime($this->input->post('startDate'));
                $endTimeZone   = strtotime($this->input->post('endDate'));
            }else{
                $wday          = date('w')-1;
                $startTimeZone = strtotime('-'.$wday.' days');
                $endTimeZone   = strtotime('+'.(6-$wday).' days');
            }
            $currentPlan = $this->api_model->getCurrentPlanDetails(1, $user_id);
            //$currentPlan = $this->api_model->getCurrentPlanDetails(1, $user_id, $plan_id);
           /* echo $this->db->last_query();
            echo '<pre>';print_r($currentPlan); exit();*/
            $planDetails = array();
            $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'); 
            if(!empty($days)){
                $i   = 1;
                $ips = 0;
                foreach($days as $day){
                    $foods = array();
                    $totalCacalories = $totalServing  = $totalProtein = $totalCarbohydrate =  0;
                    if(!empty($currentPlan->id)){
                        $startCursZone = $startTimeZone+(86400*$ips); 
                        $dateTimeZone  = strtotime(date('Y-m-d', $startCursZone));
                        //echo date('Y-m-d H:i:s', $dateTimeZone).' = date time<br/><br/><br/>';
                        $dayDetails    = $this->api_model->getDietPlanDayNew($dateTimeZone, $currentPlan->id, $user_id);
                        //echo $this->db->last_query().'<br/><br/><br/>';
                        //$dayDetails = $this->api_model->getDietPlanDay($day, $currentPlan->id, $user_id, date('W', $startTimeZone));
                        //print_r($dayDetails); //exit();
                        if(!empty($dayDetails)){
                           foreach($dayDetails as $dayDetail){
                                $food  = array();
                                $minutsT   = $calUnitT = $wieght = $regT = $setsT = $minutsT = '';
                                $checkRows = $this->common_model->get_row('diet_plan_take_food', array('diet_food_id'=>$dayDetail->id, 'day'=>$day)); 
                                $food['meal']           = $dayDetail->meal;
                                $food['item_title']     = $dayDetail->item_title;
                                $food['serving']        = $dayDetail->serving;
                                $food['protein']        = !empty($dayDetail->protein)?ucwords($dayDetail->protein).'g':'';
                                $food['carbohydrate']   = !empty($dayDetail->carbohydrate)?ucwords($dayDetail->carbohydrate).'g':'';
                                $food['calories']       = !empty($dayDetail->cacalories)?ucwords($dayDetail->cacalories):'cal';
                                $food['fat']          = !empty($dayDetail->fat)?ucwords($dayDetail->fat):'';
                                $food['suger']        = !empty($dayDetail->suger)?ucwords($dayDetail->suger):'';
                                $food['description']  = !empty($dayDetail->description)?ucwords($dayDetail->description):'';                                
                                $food['fiber']        = !empty($dayDetail->fiber)?ucwords($dayDetail->fiber):'';                                
                                $food['preparation']  = !empty($dayDetail->preparation)?ucwords($dayDetail->preparation):'';
                                $food['healthiness']  = !empty($dayDetail->healthiness)?ucwords($dayDetail->healthiness):'';                                
                                if(!empty($dayDetail->exercise_pic)&&file_exists('assets/uploads/plansExercise/'.$dayDetail->exercise_pic)){
                                    $fileName = base_url().'assets/uploads/plansExercise/'.$dayDetail->exercise_pic;
                                }else if(!empty($dayDetail->exercise_pic)&&file_exists('assets/uploads/plansExercise/thumbnails/'.$dayDetail->exercise_pic)){
                                    $fileName = base_url().'assets/uploads/plansExercise/thumbnails/'.$dayDetail->exercise_pic;
                                }
                                $food['foodPic']    = !empty($fileName)?$fileName:'';
                                $food['foodStatus'] = 0;
                                if(!empty($checkRows)){
                                    $totalCacalories    = $totalCacalories   + $dayDetail->cacalories;
                                    $totalServing       = $totalServing      + $dayDetail->serving;
                                    $totalProtein       = $totalProtein      + $dayDetail->protein;
                                    $totalCarbohydrate  = $totalCarbohydrate + $dayDetail->carbohydrate;
                                    $food['foodStatus'] = 0;
                                }
                                $foods[] = $food;                        
                            }
                        }
                    }
                    $planDetails[]     = array( 'dayName'  =>$day,
                                                'foods'=>$foods, 
                                               'totalServing'=>number_format($totalServing, 2),
                                               'totalProtein'=>number_format($totalProtein, 1).'g',
                                               'totalCarbohydrate'=>$totalCarbohydrate.'g',
                                               'totalCalories'=>$totalCacalories.'cal'
                                             ); 
                    $ips++;                       
                }
            }
            $data['responseCode']  = 200;
            $data['planDetails']   = $planDetails;
            $data['message']       = "User diet list fetched successfully";            
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'      => validation_errors()
                     );
        $this->response($data);      
    } 
    public function get_copy_diet_plan_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request'; 
        $this->form_validation->set_rules('plan_id', 'plan id', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('user_id', 'goal_id', 'trim|xss_clean|required');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $plan_id     = $this->input->post('plan_id', TRUE);    
            $user_id     = $this->input->post('user_id', TRUE);    
            $currentPlan = $this->common_model->get_row('userPlans', array('id'=>$plan_id), array(), array('id','desc')); 
            $alreadyPlaned = $this->common_model->get_row('userPlans', array('planType'=>'1', 'goal_id'=>$currentPlan->goal_id, 'user_id'=>$user_id), array(), array('id','desc')); 
            if(empty($alreadyPlaned)){                
                $insertedData                       = array();            
                $insertedData['items']              = !empty($currentPlan->items)?$currentPlan->items:'';
                $insertedData['exercise']           = !empty($currentPlan->exercise)?$currentPlan->exercise:'';
                $insertedData['plan_name']          = !empty($currentPlan->plan_name)?$currentPlan->plan_name:'';
                $insertedData['plan_description']   = !empty($currentPlan->plan_description)?$currentPlan->plan_description:'';
                $insertedData['planType']           = 1;
                $insertedData['goal_id']            = !empty($currentPlan->goal_id)?$currentPlan->goal_id:'';
                $insertedData['user_id']            = $user_id;
                $workout_id     = $this->common_model->insert('userPlans', $insertedData);
                $this->common_model->update('userPlans', array('activatePlan'=>0), array('user_id'=>$user_id, 'planType'=>1));
                $this->common_model->update('userPlans', array('activatePlan'=>1), array('id'=>$workout_id));
                $exercises = $this->common_model->get_result('diet_plan_works_new', array('plan_id'=>$workout_id));
                if(!empty($exercises)){
                    foreach($exercises as $exRow){
                        $hoursData                = array();
                        $hoursData['plan_id']     = $workout_id;
                        $hoursData['week_id']     = !empty($exRow->week_id)?$exRow->week_id:0;
                        $hoursData['diet_date']   = !empty($exRow->diet_date)?$exRow->diet_date:0;
                        $hoursData['item_id']     = !empty($exRow->item_id)?$exRow->item_id:"";
                        $hoursData['serving']     = !empty($exRow->serving)?$exRow->serving:"";
                        $hoursData['meal']        = !empty($exRow->meal)?$exRow->meal:"";  
                        $hoursData['user_id']     = user_id();  
                        $this->common_model->insert('diet_plan_works_new', $hoursData);

                    } 
                } 
            }
            $data['responseCode']  = 200;
            $data['message']       = "Diet Plan is copy successfully";            
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'      => validation_errors()
                     );
        $this->response($data);      
    } 
    public function get_copy_workout_plan_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request'; 
        $this->form_validation->set_rules('plan_id', 'plan id', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('user_id', 'user id ', 'trim|xss_clean|required');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $plan_id     = $this->input->post('plan_id', TRUE);    
            $user_id     = $this->input->post('user_id', TRUE);    
            $currentPlan = $this->common_model->get_row('userPlans', array('id'=>$plan_id), array(), array('id','desc')); 
            $alreadyPlaned = $this->common_model->get_row('userPlans', array('planType'=>'1', 'goal_id'=>$currentPlan->goal_id, 'user_id'=>$user_id), array(), array('id','desc')); 
             //echo $this->db->last_query();
            //print_r($alreadyPlaned ); //exit();
            if(empty($alreadyPlaned)){                
                $insertedData = array();            
                $insertedData['items']              = !empty($currentPlan->items)?$currentPlan->items:'';
                $insertedData['exercise']           = !empty($currentPlan->exercise)?$currentPlan->exercise:'';
                $insertedData['plan_name']          = !empty($currentPlan->plan_name)?$currentPlan->plan_name:'';
                $insertedData['plan_description']   = !empty($currentPlan->plan_description)?$currentPlan->plan_description:'';
                $insertedData['planType']           = 2;
                $insertedData['goal_id']            = !empty($currentPlan->goal_id)?$currentPlan->goal_id:'';
                $insertedData['user_id']            = $user_id;
                $workout_id     = $this->common_model->insert('userPlans', $insertedData);
                //echo $this->db->last_query();
                $this->common_model->update('userPlans', array('activatePlan'=>0), array('user_id'=>$user_id, 'planType'=>2));
                $this->common_model->update('userPlans', array('activatePlan'=>1), array('id'=>$workout_id));
                $exercises = $this->common_model->get_result('service_plan_works_new', array('plan_id'=>$plan_id));
                //print_r($exercises);
                if(!empty($exercises)){
                    foreach($exercises as $exRow){
                        $hoursData = array();
                        $hoursData['plan_id']        = $workout_id;
                        $hoursData['exercise_id']    = !empty($exRow->exercise_id)?$exRow->exercise_id:"";
                        $hoursData['minuts']         = !empty($exRow->minuts)?$exRow->minuts:0;
                        $hoursData['week_id']        = !empty($exRow->week_id)?$exRow->week_id:0;
                        $hoursData['work_out_date']  = !empty($exRow->work_out_date)?$exRow->work_out_date:0;
                        $hoursData['user_id']        = $user_id;
                        $this->common_model->insert('service_plan_works_new', $hoursData);
                    }
                }
            }
            $data['responseCode']  = 200;
            $data['message']       = "Workout Plan is copy successfully";            
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'      => validation_errors()
                     );
        $this->response($data);      
    } 
    public function get_current_workout_plan_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';   
        $this->form_validation->set_rules('user_id', 'goal_id', 'trim|xss_clean|required');
        $this->form_validation->set_rules('startDate', 'start date', 'trim|xss_clean');
        $this->form_validation->set_rules('endDate', 'end date', 'trim|xss_clean');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){     
            $user_id      = $this->input->post('user_id', TRUE); 
            $startDate    = $this->input->post('startDate', TRUE);  
            $endDate      = $this->input->post('endDate', TRUE);
            if($this->input->post('startDate')){
                $startTimeZone = strtotime($this->input->post('startDate'));
                $endTimeZone   = strtotime($this->input->post('endDate'));
            }else{
                $wday          = date('w')-1;
                $startTimeZone = strtotime('-'.$wday.' days');
                $endTimeZone   = strtotime('+'.(6-$wday).' days');
            }
            $currentPlan = $this->api_model->getCurrentPlanDetails(2, $user_id);
           /* echo $this->db->last_query();
            echo '<pre>';print_r($currentPlan); exit();*/ 
            //echo date('Y-m-d HLi:', $startTimeZone).'startTimeZone  endTimeZone'.date('Y-m-d HLi:', $endTimeZone);          
            if(!empty($currentPlan)){
                $data['responseCode']  = 200;
                $planDetails = array();
                $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'); 
                if(!empty($days)){
                    $i   = 1;
                    $ips = 0;
                    foreach($days as $day){
                        $startCursZone = $startTimeZone+(86400*$ips); 
                        $dateTimeZone  = strtotime(date('Y-m-d', $startCursZone));
                        $exercises     = array();
                        $totalCals     = $totalWieghts  = $totalMinuts = $totalMinutsT = $totalSets = $totalRegs =  0;
                        if(!empty($currentPlan->id)){
                            $dayDetails = $this->api_model->getWorkOutPlanDayNew($dateTimeZone, $currentPlan->id, $user_id);
                            //echo 'date '.date('Y-m-d H:i:s',$dateTimeZone).$this->db->last_query().' query ';
                            //print_r($dayDetails); //    exit();\                            
                            if(!empty($dayDetails)){
                               foreach($dayDetails as $dayDetail){
                                    $minutsT   = $calUnitT = $wieght = $regT = $setsT = $minutsT = '';
                                    $exercise  = array();                                   
                                    $checkRows = $this->common_model->get_row('workout_exercise_done', array('workout_exercise_id'=>$dayDetail->id, 'exercise_date'=>$dateTimeZone)); 
                                    //echo 'date '.date('Y-m-d H:i:s', $dateTimeZone).$this->db->last_query();
                                    $exercise['item_title']     = $dayDetail->item_title;
                                    if(!empty($dayDetail->exercise_pic)&&file_exists('assets/uploads/plansExercise/'.$dayDetail->exercise_pic)){
                                        $fileName = base_url().'assets/uploads/plansExercise/'.$dayDetail->exercise_pic;
                                    }else if(!empty($dayDetail->exercise_pic)&&file_exists('assets/uploads/plansExercise/thumbnails/'.$dayDetail->exercise_pic)){
                                        $fileName = base_url().'assets/uploads/plansExercise/thumbnails/'.$dayDetail->exercise_pic;
                                    }
                                    $exercise['exercise_id']     = $dayDetail->id;
                                    $exercise['day']             = $day;
                                    $exercise['dates']           = date('Y-m-d', $dateTimeZone);;
                                    $exercise['calories']        = !empty($dayDetail->cacalories)?$dayDetail->cacalories:'';
                                    $exercise['description']     = !empty($dayDetail->description)?$dayDetail->description:'';
                                    $exercise['instruction']     = !empty($dayDetail->exercise_instruction)?$dayDetail->exercise_instruction:'';
                                    $exercise['exercise_tag']    = !empty($dayDetail->exercise_tag)?$dayDetail->exercise_tag:'';           
                                    $exercise['measureUnit']    = !empty($dayDetail->measureUnit)?$dayDetail->measureUnit:'';           
                                    $exercise['exercise_pic']    = !empty($fileName)?$fileName:'';; 
                                    if(!empty($dayDetail->cacalories)&&$dayDetail->measureUnit==1){
                                        $calUnit   = ($dayDetail->cacalories*$dayDetail->minuts)/60; 
                                        $calUnitT  = round($calUnit, 1);
                                        if(!empty($checkRows)){
                                            $totalCals = $calUnit+ $totalCals;                     
                                        }
                                    }                                    
                                    $exercise['calories']   = $calUnitT;                                
                                    if(!empty($dayDetail->minuts)&&$dayDetail->measureUnit==1){
                                        $minutsT = $dayDetail->minuts;
                                        if(!empty($checkRows)){
                                            $totalMinuts  = $totalMinuts  + $dayDetail->minuts;
                                        }
                                    } 
                                    /*if($dateTimeZone=='1527791400'){
                                        echo ' ex id'.$dayDetail->id.',  totalMinuts = '.$totalMinuts.', minuts='.$dayDetail->minuts.' <br/>';
                                    }*/
                                    $exercise['minuts']   = $minutsT;                                   
                                    if(!empty($dayDetail->sets)){
                                        $setsT = $dayDetail->sets;
                                        if(!empty($checkRows)){
                                          $totalRegs    = $totalRegs + $dayDetail->sets;
                                        }
                                    }else if(!empty($dayDetail->minuts)&&$dayDetail->measureUnit==2){
                                        $setsT = $dayDetail->minuts;
                                        if(!empty($checkRows)){
                                            $totalSets    = $totalSets + $dayDetail->minuts;
                                        }
                                    }
                                    $exercise['sets'] = $setsT;
                                    if(!empty($dayDetail->reps)){
                                        $regT = $dayDetail->reps;
                                        if(!empty($checkRows)){
                                          $totalRegs    = $totalRegs + $dayDetail->reps;
                                        }
                                    }else if(!empty($dayDetail->minuts)&&$dayDetail->measureUnit==3){
                                        $regT = $dayDetail->minuts;                                        
                                        if(!empty($checkRows)){
                                            $totalRegs    = $totalRegs + $dayDetail->minuts;
                                        }
                                    }
                                    $exercise['reps'] = $regT;
                                    if(!empty($dayDetail->minuts)&&$dayDetail->measureUnit==4){
                                        $wieght = $dayDetail->minuts;
                                        if(!empty($user->useMetricsSystem) && $user->useMetricsSystem==1){
                                            $wieght .=  ' kg';
                                        }else{
                                            $wieght .= ' lbs';
                                        }
                                        if(!empty($checkRows)){
                                            $totalWieghts    = $totalWieghts + $dayDetail->minuts;
                                        }
                                    }
                                    $exercise['wieght']         = $wieght;   
                                    $exercise['exerciseStatus'] = 0;
                                    if(!empty($checkRows)){
                                        $exercise['exerciseStatus'] = 1;
                                    }
                                    $exercises[]        = $exercise;                        
                                }
                            }
                        }
                        /*if($dateTimeZone=='1527791400'){
                            echo '  totalMinuts = '.$totalMinuts.' <br/>';
                        }*/
                        if($totalMinuts>60){
                            $totalMinuts = $totalMinuts;
                            $totalMinutsT = floor($totalMinuts/60). ' hour ';
                            if($totalMinutsT<10){
                                $totalMinutsT = '0'.$totalMinutsT;
                            }
                            if(($totalMinuts % 60)>0&&($totalMinuts % 60)>9){
                                $totalMinutsT .= ' '.($totalMinuts % 60).' minuts';
                            }elseif(($totalMinuts % 60)>0&&($totalMinuts % 60)<9){
                                $totalMinutsT .= ' 0'.($totalMinuts % 60).' minuts';
                            }
                        }else{
                            $totalMinutsT = $totalMinuts.' minuts'; 
                        }
                        if(!empty($user->useMetricsSystem) && $user->useMetricsSystem==1){
                            $totalWieghts = $totalWieghts.' kg';
                        }else{
                            $totalWieghts = $totalWieghts.' lbs';
                        }
                        $totalCals = round($totalCals);
                        $planDetails[]     = array( 'dayName'    => $day,
                                                    'plan_id'    => !empty($currentPlan->id)?$currentPlan->id:'',
                                                    'date_id'       => !empty($dateTimeZone)?$dateTimeZone:'',
                                                    'exercises'  => $exercises, 
                                                    'totalCals'   => $totalCals,
                                                    'totalMinuts' => $totalMinutsT,
                                                    'totalSets'   => $totalSets,
                                                    'totalRegs'   => $totalRegs,
                                                    'totalWieghts'=> $totalWieghts
                                                );    
                        $ips++;                    
                    }
                }
                $data['plan_name']     = !empty($currentPlan->plan_name)?$currentPlan->plan_name:'';               
                $data['planDetails']   = $planDetails;
                $data['message']       = "User workout list fetched successfully";            
            }else{
                $data['responseCode']  = 500;
                $data['message']       = "Workout plan records not found"; 
            }
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'      => validation_errors()
                     );
        $this->response($data);      
    }   
    public function get_current_diet_plan_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';  
        $this->form_validation->set_rules('user_id', 'goal_id', 'trim|xss_clean|required');
        $this->form_validation->set_rules('startDate', 'start date', 'trim|xss_clean');
        $this->form_validation->set_rules('endDate', 'end date', 'trim|xss_clean');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){     
            $user_id      = $this->input->post('user_id', TRUE);   
            $startDate    = $this->input->post('startDate', TRUE);  
            $endDate      = $this->input->post('endDate', TRUE);
            if($this->input->post('startDate')){
                $startTimeZone = strtotime($this->input->post('startDate'));
                $endTimeZone   = strtotime($this->input->post('endDate'));
            }else{
                $wday          = date('w')-1;
                $startTimeZone = strtotime('-'.$wday.' days');
                $endTimeZone   = strtotime('+'.(6-$wday).' days');
            }
            $currentPlan = $this->api_model->getCurrentPlanDetails(1, $user_id);   
            if(!empty($currentPlan)){         
                $data['responseCode']  = 200;
                $planDetails = array();
                $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'); 
                if(!empty($days)){
                    $i   = 1;
                    $ips = 0;
                    foreach($days as $day){
                        $startCursZone = $startTimeZone+(86400*$ips); 
                        $dateTimeZone  = strtotime(date('Y-m-d', $startCursZone));
                        $foods = array();
                        $totalCacalories = $totalServing  = $totalProtein = $totalCarbohydrate =  0;
                        if(!empty($currentPlan->id)){
                            $dayDetails = $this->api_model->getDietPlanDayNew($dateTimeZone, $currentPlan->id, $user_id);
                            //print_r($dayDetails); exit();
                            if(!empty($dayDetails)){
                               foreach($dayDetails as $dayDetail){
                                    $food      = array();
                                    $minutsT   = $calUnitT = $wieght = $regT = $setsT = $minutsT = '';
                                    $checkRows = $this->common_model->get_row('diet_plan_take_food', array('diet_food_id'=>$dayDetail->id, 'food_taken_date'=>$dateTimeZone)); 
                                    $food['diet_id']        = $dayDetail->id;
                                    $food['day']            = $day;
                                    $food['dates']          = date('Y-m-d', $dateTimeZone);;
                                    $food['meal']           = $dayDetail->meal;
                                    $food['item_title']     = $dayDetail->item_title;
                                    $food['serving']        = $dayDetail->serving;
                                    $food['protein']        = !empty($dayDetail->protein)?ucwords($dayDetail->protein).'g':'';
                                    $food['carbohydrate']   = !empty($dayDetail->carbohydrate)?ucwords($dayDetail->carbohydrate).'g':'';
                                    $food['calories']       = !empty($dayDetail->cacalories)?ucwords($dayDetail->cacalories):'cal';
                                    $food['fat']          = !empty($dayDetail->fat)?ucwords($dayDetail->fat):'';
                                    $food['suger']        = !empty($dayDetail->suger)?ucwords($dayDetail->suger):'';
                                    $food['description']  = !empty($dayDetail->description)?ucwords($dayDetail->description):'';                                
                                    $food['fiber']        = !empty($dayDetail->fiber)?ucwords($dayDetail->fiber):'';                                
                                    $food['preparation']  = !empty($dayDetail->preparation)?ucwords($dayDetail->preparation):'';
                                    $food['healthiness']  = !empty($dayDetail->healthiness)?ucwords($dayDetail->healthiness):'';                                
                                    if(!empty($dayDetail->exercise_pic)&&file_exists('assets/uploads/plansExercise/'.$dayDetail->exercise_pic)){
                                        $fileName = base_url().'assets/uploads/plansExercise/'.$dayDetail->exercise_pic;
                                    }else if(!empty($dayDetail->exercise_pic)&&file_exists('assets/uploads/plansExercise/thumbnails/'.$dayDetail->exercise_pic)){
                                        $fileName = base_url().'assets/uploads/plansExercise/thumbnails/'.$dayDetail->exercise_pic;
                                    }
                                    $food['foodPic']    = !empty($fileName)?$fileName:'';
                                    $food['foodStatus'] = 0;
                                    if(!empty($checkRows)){
                                        $totalCacalories    = $totalCacalories   + $dayDetail->cacalories;
                                        $totalServing       = $totalServing      + $dayDetail->serving;
                                        $totalProtein       = $totalProtein      + $dayDetail->protein;
                                        $totalCarbohydrate  = $totalCarbohydrate + $dayDetail->carbohydrate;
                                        $food['foodStatus'] = 1;
                                    }
                                    $foods[] = $food;                        
                                }
                            }
                        }
                        $planDetails[]     = array( 'dayName'  => $day,
                                                    'date_id'       => !empty($dateTimeZone)?$dateTimeZone:'',
                                                    'plan_id'  => !empty($currentPlan->id)?$currentPlan->id:'', 
                                                    'foods'    => $foods, 
                                                   'totalServing'=>number_format($totalServing, 2),
                                                   'totalProtein'=>number_format($totalProtein, 1).'g',
                                                   'totalCarbohydrate'=>$totalCarbohydrate.'g',
                                                   'totalCalories'=>$totalCacalories.'cal'
                                                 );
                        $ips++;                        
                    }
                }
                $data['plan_name']     = !empty($currentPlan->plan_name)?$currentPlan->plan_name:'';          
                $data['message']       = "User diet list fetched successfully";            
                $data['planDetails']   = $planDetails;
            }else{
                $data['responseCode']  = 500;
                $data['message']       = "Diet plan records not found"; 
            }
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'      => validation_errors()
                     );
        $this->response($data);      
    }   
    public function get_all_workout_plan_post(){ 
        $data           = array();
        $responseCode   = 500;
        $message        = 'Invalid Request';       
        $this->form_validation->set_rules('user_id', 'user id', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $usersNew                       = array();
            $rows           = $this->common_model->get_result('userPlans', array('user_id'=>$this->input->post('user_id', TRUE), 'planType'=>2, 'status'=>1), array('plan_name', 'plan_description', 'activatePlan','id','created_date'), array('id','desc'));  
            if(!empty($rows)){
                $ids = 1;
                foreach($rows as $row){ 
                    if($ids==1){
                        $row->activeFrom = date('d M Y H:i A', strtotime($row->created_date));
                        $row->activeTo   = 'Now';
                        $lastDate = $row->created_date;
                    }else{
                        $row->activeFrom = date('d M Y H:i A', strtotime($row->created_date));
                        $row->activeTo   = date('d M Y H:i A', strtotime($lastDate));
                    }
                    $row->planID   =  $row->id;
                    unset($row->id);
                    unset($row->created_date);
                    $ids++;
                    $usersNew[] = $row;  
                } 
            }
            $data['message']                = 'Get All Workout Plan List';
            $data['plansList']              = $usersNew;
            $data['responseCode']           = 200;
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }    
    public function get_all_diet_plan_post(){ 
        $data           = array();
        $responseCode   = 500;
        $message        = 'Invalid Request';       
        $this->form_validation->set_rules('user_id', 'user id', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $usersNew                       = array();
            $rows   = $this->common_model->get_result('userPlans', array('user_id'=>$this->input->post('user_id', TRUE), 'planType'=>1, 'status'=>1), array('plan_name', 'plan_description', 'activatePlan','id','created_date'), array('id','desc'));  
            //echo $this->db->last_query(); exit();
            if(!empty($rows)){
                $ids = 1;
                foreach($rows as $row){ 
                    if($ids==1){
                        $row->activeFrom = date('d M Y H:i A', strtotime($row->created_date));
                        $row->activeTo   = 'Now';
                        $lastDate = $row->created_date;
                    }else{
                        $row->activeFrom = date('d M Y H:i A', strtotime($row->created_date));
                        $row->activeTo   = date('d M Y H:i A', strtotime($lastDate));
                    }
                    $row->planID   =  $row->id;
                    $ids++;
                    unset($row->created_date);
                    unset($row->id);
                    $usersNew[] = $row;  
                } 
            }
            $data['message']                = 'Get All Diet Plan List';
            $data['plansList']              = $usersNew;
            $data['responseCode']           = 200;
            $this->response($data);
        }     
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }
    public function get_user_height_weight_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';
        $this->form_validation->set_rules('user_id', 'user id', 'xss_clean|required');
         $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){   
            $user = $this->common_model->get_row('users', array('id'=>$this->input->post('user_id')));
            if(!empty($user->useMetricsSystem)&&$user->useMetricsSystem==1){
                $data['height']        = !empty($user->height)?number_format($user->height, 0).' cm':'';
                $data['weight']        = number_format($user->weight, 0).' kg';              
            }else{
            	$inches      = $user->height/2.54; 
                $feetInch    = "";
                $feetInch   .= floor($inches/12)."'";
                $feetInch   .= floor($inches%12);
                $data['height']         = $feetInch;
                $data['weight']         = number_format($user->weight, 0).' lbs';
            }         
            $data['responseCode']   = 200;
            $data['message']        = "User height & weight is feched successfully"; 
            $this->response($data);
        }  
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }
    public function get_matrix_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';   
        $this->form_validation->set_rules('user_id', 'user_id', 'trim|xss_clean|required');
        $this->form_validation->set_rules('startDate', 'start date', 'trim|xss_clean');
        $this->form_validation->set_rules('endDate', 'end date', 'trim|xss_clean');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){  
            $matrixDetails  = array(); 
            $user_id        = $this->input->post('user_id', TRUE);  
            $startDate      = $this->input->post('startDate', TRUE);  
            $endDate        = $this->input->post('endDate', TRUE);
            if($this->input->post('startDate')){
                $startTimeZone = strtotime($this->input->post('startDate'));
                $endTimeZone   = strtotime($this->input->post('endDate'));
            }else{
                $wday          = date('w')-1;
                $startTimeZone = strtotime('-'.$wday.' days');
                $endTimeZone   = strtotime('+'.(6-$wday).' days');
            }
            $user   = $this->common_model->get_row('users', array('id'=>$user_id));            
            $days           = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'); 
            if(!empty($days)){
                $i=0;
                foreach($days as $day){                    
                    $startCursZone  = $startTimeZone+(86400*$i);
                    $dateTimeZone   = strtotime(date('Y-m-d', $startCursZone));
                    $cal_consumed   = get_cal_consumed($dateTimeZone, $user_id);
                    $cal_burned     = get_cal_burned($dateTimeZone, $user_id);
                    $matrix_Data    = array();                    
                    $row            = $this->common_model->get_row('metricTracker', array('matrixDate'=>$dateTimeZone, 'user_id'=>$user->id));
                    if(!empty($user->useMetricsSystem)&&$user->useMetricsSystem==1){
                        $hightTitle = !empty($user->height)?number_format($user->height, 0).' cm':'';
                        $hightID    = $user->height; 
                        $weight     = $user->weight;  
                    }else{
                        $inches      = $user->height/2.54; 
                        $feetInch    = "";
                        $feetInch   .= floor($inches/12)."'";
                        $feetInch   .= floor($inches%12);
                        $hightTitle = $feetInch;
                        $hightID    = $user->height; 
                        $weight     = $user->weight;  
                    } 
                    if(!empty($row->height)){$hightTitle = $row->height;}
                    if(!empty($row->weight)){$weight = $row->weight;}
                    if(!empty($row->cal_consumed)){$cal_consumed = $row->weight;}
                    if(!empty($row->cal_burned)){$cal_burned   = $row->cal_burned;}
                    $matrix_Data['dayName']    = $day;
                    $matrix_Data['matrixDate'] = date('F d, Y', $startCursZone);
                    $matrix_Data['height']     = $hightTitle;
                    $matrix_Data['weight']     = $weight;
                    $matrix_Data['calConsumed']= !empty($cal_consumed)?$cal_consumed:'';
                    $matrix_Data['calBurned']  = !empty($cal_burned)?$cal_burned:'';
                    $matrix_Data['bodyShot']   = (!empty($row->bodyShot)&&file_exists('assets/uploads/matrix/thumbnails/'.$row->bodyShot))?basE_url().'assets/uploads/matrix/thumbnails/'.$row->bodyShot:'';                   
                    $matrix_Data['chest']      = !empty($row->chest)?$row->chest:'';
                    $matrix_Data['waist']      = !empty($row->waist)?$row->waist:'';
                    $matrix_Data['arms']       = !empty($row->arms)?$row->arms:'';
                    $matrix_Data['forearms']   = !empty($row->forearms)?$row->forearms:'';
                    $matrix_Data['legs']       = !empty($row->legs)?$row->legs:'';
                    $matrix_Data['calves']     = !empty($row->calves)?$row->calves:'';
                    $matrix_Data['hips']       = !empty($row->hips)?$row->hips:'';
                    $matrix_Data['bicepsBF']   = !empty($row->bicepsBF)?$row->bicepsBF:'';
                    $matrix_Data['absBF']      = !empty($row->absBF)?$row->absBF:'';
                    $matrix_Data['thighsBF']   = !empty($row->thighsBF)?$row->thighsBF:'';                  
                    $data[$day]           = $matrix_Data;                       
                    $i++;
                }
            }
            $data['responseCode']  = 200;
            $data['advancedMetricsTrackingStatus']      = (!empty($user->advancedMetricsTracking)&&$user->advancedMetricsTracking==1)?1:0;
            $data['message']       = "Matrix list fetched successfully";            
            //$data['matrixDetails'] = $matrixDetails;
            $this->response($data);
        }       
        $data = array('responseCode' => $responseCode,
                      'message'      => validation_errors()
                     );
        $this->response($data);      
    } 
    public function active_deactive_plan_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';
        $this->form_validation->set_rules('user_id', 'user id', 'xss_clean|required');
        $this->form_validation->set_rules('plan_id', 'plan id', 'xss_clean|required');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){   
            $planRow = $this->common_model->get_row('userPlans', array('id'=>$this->input->post('plan_id')));
            if(!empty($planRow)){
                if(!empty($planRow->activatePlan)&&$planRow->activatePlan==1){
                    $this->common_model->update('userPlans', array('activatePlan'=>0), array('id'=>$this->input->post('plan_id')));
                    $nextPlanID = $this->common_model->get_row('userPlans', array('user_id'=>$this->input->post('user_id'), 'planType'=>$planRow->planType, 'id !='=>$this->input->post('plan_id')), array(), array('id', 'desc'));
                    if(!empty($nextPlanID->id)){
                        $this->common_model->update('userPlans', array('activatePlan'=>1), array('id'=>$nextPlanID->id));            
                    }
                    $data['message']        = "Plan  is deactivated successfully"; 
                }else{
                    $this->common_model->update('userPlans', array('activatePlan'=>0), array('user_id'=>$this->input->post('user_id'), 'planType'=>$planRow->planType));
                    $this->common_model->update('userPlans', array('activatePlan'=>1), array('id'=>$this->input->post('plan_id'))); 
                    $data['message']        = "Plan  is activated successfully"; 
                }
                $data['responseCode']   = 200;            
            }else{
                $data['responseCode']   = 500;
                $data['message']        = "Plan id is invailed"; 
            } 
            $this->response($data);
        }  
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }
    public function delete_plan_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';
        $this->form_validation->set_rules('user_id', 'user id', 'xss_clean|required');
        $this->form_validation->set_rules('plan_id', 'plan id', 'xss_clean|required');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $planRow = $this->common_model->get_row('userPlans', array('id'=>$this->input->post('plan_id'), 'activatePlan'=>1));
            if(!empty($planRow)){
                $nextPlanID = $this->common_model->get_row('userPlans', array('user_id'=>$this->input->post('user_id'), 'planType'=> $planRow->planType, 'id !='=>$this->input->post('plan_id')), array(), array('id', 'desc'));
                if(!empty($nextPlanID->id)){
                    $this->common_model->update('userPlans', array('activatePlan'=>1), array('id'=>$nextPlanID->id));            
                }                
            }  
            $this->common_model->update('userPlans', array('activatePlan'=>0, 'status'=>3), array('id'=>$this->input->post('plan_id')));         
            $data['responseCode']   = 200;
            $data['message']        = "Plan  is deleted successfully"; 
            $this->response($data);
        }  
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }
    public function food_info_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';
        $this->form_validation->set_rules('food_id', 'food id', 'xss_clean|required');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){   
            $row = $this->common_model->get_row('service_plan_diet_items', array('id'=>$this->input->post('food_id')));
            if(!empty($row->exercise_pic)&&file_exists('assets/uploads/plansExercise/'.$row->exercise_pic)){
                $fileName = base_url().'assets/uploads/plansExercise/'.$row->exercise_pic;
            }else if(!empty($row->exercise_pic)&&file_exists('assets/uploads/plansExercise/thumbnails/'.$row->exercise_pic)){
                $fileName = base_url().'assets/uploads/plansExercise/thumbnails/'.$row->exercise_pic;
            }
            $data['responseCode']    = 200;
            $data['item_title']      = !empty($row->item_title)?$row->item_title:'';
            $data['calories']        = !empty($row->cacalories)?$row->cacalories:'';
            $data['protein']         = !empty($row->protein)?$row->protein:'';
            $data['fat']             = !empty($row->fat)?$row->fat:'';
            $data['carbohydrate']    = !empty($row->carbohydrate)?$row->carbohydrate:'';
            $data['fiber']           = !empty($row->fiber)?$row->fiber:'';
            $data['suger']           = !empty($row->suger)?$row->suger:'';
            $data['preparation']     = !empty($row->preparation)?$row->preparation:'';
            $data['healthiness']     = !empty($row->healthiness)?$row->healthiness:'';
            $data['food_pic']        = !empty($fileName)?$fileName:'';            
            $data['message']        = "Food information is fatched successfully"; 
            $this->response($data);
        }  
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }
    public function exercise_info_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';
        $this->form_validation->set_rules('exercise_id', 'exercise id', 'xss_clean|required');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){   
            $row = $this->common_model->get_row('service_plan_user_exercise', array('id'=>$this->input->post('exercise_id')));
            if(!empty($row->exercise_pic)&&file_exists('assets/uploads/plansExercise/'.$row->exercise_pic)){
                $fileName = base_url().'assets/uploads/plansExercise/'.$row->exercise_pic;
            }else if(!empty($row->exercise_pic)&&file_exists('assets/uploads/plansExercise/thumbnails/'.$row->exercise_pic)){
                $fileName = base_url().'assets/uploads/plansExercise/thumbnails/'.$row->exercise_pic;
            }
            $data['responseCode']    = 200;
            $data['exercise_title']  = !empty($row->exercise_title)?$row->exercise_title:'';
            $data['calories']        = !empty($row->cacalories)?$row->cacalories:'';
            $data['description']     = !empty($row->description)?$row->description:'';
            $data['instruction']     = !empty($row->exercise_instruction)?$row->exercise_instruction:'';
            $data['exercise_tag']    = !empty($row->exercise_tag)?$row->exercise_tag:'';           
            $data['exercise_pic']    = !empty($fileName)?$fileName:'';;            
            $data['message']         = "Exercise information is fatched successfully"; 
            $this->response($data);
        }  
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }
    public function check_uncheck_exercise_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';
        $this->form_validation->set_rules('exercise_id', 'exercise id', 'xss_clean|required');
        $this->form_validation->set_rules('user_id', 'user id', 'xss_clean|required');
        $this->form_validation->set_rules('day', 'day', 'xss_clean|required');
        $this->form_validation->set_rules('dates', 'dates', 'xss_clean|required');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){   
            $chRow          = $this->common_model->get_row('service_plan_works_new', array('id'=>$this->input->post('exercise_id')));
            if(!empty($chRow)){
                if($this->common_model->get_row('workout_exercise_done', array('workout_exercise_id'=>$this->input->post('exercise_id'), 'exercise_date'=> strtotime($this->input->post('dates'))))){
                    $this->common_model->delete('workout_exercise_done', array('workout_exercise_id'=>$this->input->post('exercise_id'), 'day'=>$this->input->post('day')));
                   $data['message']        = "Exercise status is unset successfully"; 
                }else{
                    $this->common_model->insert('workout_exercise_done', array('workout_exercise_id'=>$this->input->post('exercise_id'), 'day'=>$this->input->post('day'), 'exercise_date'=>strtotime($this->input->post('dates'))));
                    $data['message']        = "Exercise status is set successfully"; 
                }
            }     
            $data['responseCode']    = 200;      
            $this->response($data);
        }  
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }
    public function check_uncheck_food_taken_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';
        $this->form_validation->set_rules('diet_id', 'diet id', 'xss_clean|required');
        $this->form_validation->set_rules('user_id', 'user id', 'xss_clean|required');
        $this->form_validation->set_rules('day', 'day', 'xss_clean|required');
        $this->form_validation->set_rules('dates', 'dates', 'xss_clean|required');
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){   
            $chRow  = $this->common_model->get_row('diet_plan_works_new', array('id'=>$this->input->post('diet_id')));
            if(!empty($chRow)){
                if($this->common_model->get_row('diet_plan_take_food', array('diet_food_id'=>$this->input->post('diet_id'), 'food_taken_date'=>$this->input->post('dates')))){
                    $this->common_model->delete('diet_plan_take_food', array('diet_food_id'=>$this->input->post('diet_id'), 'day'=>$this->input->post('day')));
                    $data['message']        = "Diet status is unset successfully"; 
                }else{
                    $this->common_model->insert('diet_plan_take_food', array('diet_food_id'=>$this->input->post('diet_id'), 'day'=>$this->input->post('day'), 'food_taken_date'=>$this->input->post('dates')));
                    $data['message']        = "Diet status is set successfully"; 
                }
            }      
            $data['responseCode']    = 200;       
            $this->response($data);
        }  
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }
    public function add_exercise_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';
        $this->form_validation->set_rules('user_id', 'user id', 'xss_clean|required');
        $this->form_validation->set_rules('workoutitems[]', 'workout items', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('exercise[]', 'exercise', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('plan_id', 'plan id', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('day', 'day', 'trim|xss_clean|required'); 
        $this->form_validation->set_rules('date', 'date', 'trim|xss_clean|required'); 
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){   
            //echo '<pre>';print_r($_POST); exit();
            $exercises = $exercisesM = array();
            if($this->input->post('workoutitems')){
                $items      = $this->input->post('workoutitems');
            }
            if($this->input->post('exercise')){
                $exercisesA   = $this->input->post('exercise');     
                if(!empty($exercisesA)){
                    foreach($exercisesA as $exerciseA){
                        $exercises[]  = !empty($exerciseA['exerciseId'])?$exerciseA['exerciseId']:'';
                        $exercisesM[] = $exerciseA;
                    }
                }           
            }
            $row = $this->common_model->get_row('userPlans', array('id'=>$this->input->post('plan_id')));
            if(!empty($row)){
                $itemsAll    = explode(',', $row->items);
                $exerciseAll = explode(',', $row->exercise);
                foreach($items as $item){
                    array_push($itemsAll, $item);
                }
                foreach($exercises as $exercise){
                    array_push($exerciseAll, $exercise);
                }
                array_unique($itemsAll);
                array_unique($exerciseAll);                
                $insertedData['items']      = implode(',', $itemsAll);
                $insertedData['exercise']   = implode(',', $exerciseAll);
                $this->common_model->update('userPlans', $insertedData, array('id'=>$this->input->post('plan_id')));  
            }  
            //echo '<pre>'; print_r($exercisesM); //exit();
            if(!empty($row)){
                if(!empty($exercisesM)){
                    foreach($exercisesM as $exerciseM){
                        $hoursData = array();
                        $hoursData['plan_id']  = $this->input->post('plan_id');
                        $hoursData['exercise_id']   = !empty($exerciseM['exerciseId'])?$exerciseM['exerciseId']:'';
                        $hoursData['minuts']         = !empty($exerciseM['minute'])?$exerciseM['minute']:0;
                        $hoursData['week_id']        = date('W', $this->input->post('date')); 
                        $hoursData['work_out_date']  = $this->input->post('date');
                        $hoursData['created_date']   = date('Y-m-d H:i:s');
                        $hoursData['user_id']        = $this->input->post('user_id');
                        $this->common_model->insert('service_plan_works_new', $hoursData); 
                       // echo $this->db->last_query();
                    }
                }
            }  
            $data['responseCode']    = 200;       
            $data['message']         = "Exercise is added in wookout plan";       
            $this->response($data);
        }  
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }
    public function add_food_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';
        $this->form_validation->set_rules('user_id', 'user id', 'xss_clean|required');
        $this->form_validation->set_rules('dietitems[]', 'item', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('item[]', 'item', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('plan_id', 'plan id', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('day', 'day', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('date', 'date', 'trim|xss_clean|required');  
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $insertedData = $foods = $foodsM = array();         
            if($this->input->post('dietitems')){
                $dietitems  = $this->input->post('dietitems');
            }
            if($this->input->post('item')){
                $foodsA   = $this->input->post('item');     
                if(!empty($foodsA)){
                    foreach($foodsA as $foodA){
                        $foods[]  = $foodA['food_id'];
                        $foodsM[] = $foodA;
                    }
                }           
            }
            $row = $this->common_model->get_row('userPlans', array('id'=>$this->input->post('plan_id')));
            if(!empty($row)){
                $itemsAll    = explode(',', $row->items);
                $exerciseAll = explode(',', $row->exercise);
                foreach($foods as $food){
                    array_push($itemsAll, $food);
                }
                foreach($dietitems as $dietitem){
                    array_push($exerciseAll, $dietitem);
                }
                array_unique($itemsAll);
                array_unique($exerciseAll);                
                $insertedData['items']      = implode(',', $itemsAll);
                $insertedData['exercise']   = implode(',', $exerciseAll);
                $this->common_model->update('userPlans', $insertedData, array('id'=>$this->input->post('plan_id')));  
            }
            if(!empty($row)){
                if(!empty($foodsM)){
                    foreach($foodsM as $foodM){
                        $hoursData = array();
                        $hoursData['plan_id']  = $this->input->post('plan_id');
                        $hoursData['item_id']      = $foodM['food_id'];                   
                        $hoursData['meal']         = ($foodM['meal'])?$foodM['meal']:0;
                        $hoursData['serving']      = ($foodM['serving'])?$foodM['serving']:0;
                        $hoursData['week_id']      = date('W', $this->input->post('date'));
                        $hoursData['diet_date']    = $this->input->post('date');
                        $hoursData['user_id']      = $this->input->post('user_id');
                        $hoursData['created_date']= date('Y-m-d H:i:s');
                        $this->common_model->insert('diet_plan_works_new', $hoursData); 
                    }
                }
            } 
            $data['responseCode']    = 200;  
            $data['message']         = "Food is added in diet plan";            
            $this->response($data);
        }  
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }
    public function edit_food_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';
        $this->form_validation->set_rules('user_id', 'user id', 'xss_clean|required');
        $this->form_validation->set_rules('day', 'day', 'trim|xss_clean|required');   
        $this->form_validation->set_rules('foodID', 'food ID', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('mealType', 'mealType', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('servingType', 'servingType', 'trim|xss_clean|required');  
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $insertedData = array();   
            if($this->input->post('mealType')){
                $insertedData['meal'] = $this->input->post('mealType');
            } 
            if($this->input->post('servingType')){
                $insertedData['serving'] = $this->input->post('servingType');
            }     
            $this->common_model->update('diet_plan_works_new', $insertedData, array('id'=>$this->input->post('foodID')));
            $data['responseCode']    = 200;  
            $data['message']         = "Diet is update successfully";            
            $this->response($data);
        }  
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }
    public function edit_workout_exercise_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';
        $this->form_validation->set_rules('user_id', 'user id', 'xss_clean|required');
        $this->form_validation->set_rules('day', 'day', 'trim|xss_clean|required');   
        $this->form_validation->set_rules('exerciseID', 'exercise ID', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('minutes', 'minutes', 'trim|xss_clean|required');  
        $this->form_validation->set_error_delimiters('', ''); 
        if ($this->form_validation->run() == TRUE){ 
            $insertedData = array();   
            if($this->input->post('minutes')){
                $insertedData['minuts'] = $this->input->post('minutes');
            }    
            $this->common_model->update('service_plan_works_new', $insertedData, array('id'=>$this->input->post('exerciseID')));
            $data['responseCode']    = 200;  
            $data['message']         = "Exercise is update successfully";            
            $this->response($data);
        }  
        $data = array('responseCode' => $responseCode,
                      'message'     => validation_errors()
                     );
        $this->response($data);  
    }
    public function get_all_items_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';       
        $itemsA  = $this->api_model->get_recipe_blog_images();
        if(!empty($itemsA)){
            foreach($itemsA as $item){     
                $showPostShowStatus = 1;        
                if($item->user_account_type==2){
                    $showPostShowStatus = 0;
                    if($this->input->post('user_id')){
                        if(get_all_count('follow_request', array('receiver_id'=>$item->user_id, 'sender_id'=> $this->input->post('user_id'), 'accepted_status'=>1))==1){
                            $showPostShowStatus = 1;
                        }
                    }
                }
                if(!empty($showPostShowStatus)){                    
                    $imageName = '';
                    if(!empty($item->image_name)&&file_exists('assets/uploads/recipeBlogImages/'.$item->image_name)){
                        $imageName = base_url().'assets/uploads/recipeBlogImages/'.$item->image_name;
                    }else if(!empty($item->coverImage)&&file_exists('assets/uploads/recipeBlogImages/'.$item->coverImage)){
                        $imageName = base_url().'assets/uploads/recipeBlogImages/'.$item->coverImage;
                    }
                    $items[] = array('itemID'       => !empty($item->id)?$item->id:'',
                                     'type'         => !empty($item->type)?$item->type:'',
                                     'title'        => !empty($item->title)?$item->title:'',
                                     'description'  => !empty($item->description)?$item->description:'',
                                     'image_name'   => !empty($imageName)?$imageName:'',
                                     'comments'     => !empty($item->comments)?$item->comments:'',

                                  );
                }
            }
        }
        $data['responseCode']  = 200;
        $data['message']       = 'Item list is fetched';
        $data['type']          = 'all';
        $data['items']         = $items;
        $this->response($data);  
    }
}