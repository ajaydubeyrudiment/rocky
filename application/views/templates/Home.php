<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*this controller for making front end */
class Home extends CI_Controller {
	public function __construct(){
    	parent:: __construct(); 
    }
  /*home page */  
	public function index(){
		if(user_logged_in()){ 
            redirect(base_url('user/dashboard'));
        }		
        $data['rows'] 		= $this->developer_model->get_recipe_blog_images();	
		$data['template'] 	= 'frontend/index';
	    $this->load->view('templates/frontend_template', $data);
	} 
	public function login(){
		$loginResponce = array();	
		$this->form_validation->set_rules('email', 'user name', 'required');
		$this->form_validation->set_rules('password', 'password', 'required');
		if ($this->form_validation->run() == TRUE){
			$email    = $this->input->post('email');
			$password = $this->input->post('password');
			$response = $this->common_model->front_user_login($email, $password);
			if($response==1){
				if(user_logged_in()){
					$user = user_info();
					if(!empty($user->profile_view_status)&&$user->profile_view_status==1){
						$loginResponce = array('status' => 'true',
								'message'   => 'Your Login successfully.',
								'redirect_url' =>'user/dashboard'
								);
					}else{
						$loginResponce = array('status' => 'true',
								'message'   => 'Your Login successfully.',
								'redirect_url' =>'user/dashboard?acntype=edit_profile'
								);
					}					
				}else{
					$loginResponce = array('status'   => 'false',
						 'message' => 'Your account has been banned from using the RokApe platform. If you believe this has been in error, then please contact support@roksor.com'
								);
				}
			}else if($response==2){
				$loginResponce = array('status'   => 'false',
										'message' => 'Your account has been banned from using the RokApe platform. If you believe this has been in error, then please contact support@roksor.com'
									 );
			}else if($response==3){
				$loginResponce = array('status'   => 'false',
										'message' => 'Your account is not verified'
									 );
			}else if($response==4){
				$loginResponce = array('status'   => 'false',
										'message' => 'The username or password is invalid.'
									 );
			}else{
				$loginResponce = array('status'   => 'false',
								'message' => 'The username or password is invalid.'
								);
			}
		}else{
			$loginResponce = array('status'   => 'false',
							'message' => validation_errors()
						  );
		}
		echo json_encode($loginResponce);
	}	
	public function signup(){
		if(user_logged_in()){ 
			redirect('user/profile');
		}
		$data['template'] 		= 'frontend/signup';
	    $this->load->view('templates/frontend_template', $data);
	}
	public function signup_res(){	
		$loginResponce 	= array();
		$this->form_validation->set_rules('name', 'name', 'required');
		$this->form_validation->set_rules('user_name', 'user name', 'required|callback_userNameChecked');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_emailChecked');
		$this->form_validation->set_rules('password', 'password', 'required');
		if ($this->form_validation->run() == TRUE){	
			$activationcode            	= rand().time();
			$newSalt            	   	= salt();
			$password  				   	= passwordGenrate($this->input->post('password'), $newSalt);
			$signup 			 	   	= array();
			if($this->input->post('name'))
				$signup['first_name']	= $this->input->post('name');
			if($this->input->post('user_name'))
				$signup['user_name']	= $this->input->post('user_name');
			$signup['email']	 	   	= $this->input->post('email');
			$signup['activation_code'] 	= $activationcode ;	 
			$signup['created_date']     = date('Y-m-d H:i:s');
			$signup['password'] 	   	= $password; 
			$signup['salt']				= $newSalt;
			$insert = $this->common_model->insert('users', $signup);
			if(!empty($insert)){ 		
				$response = $this->common_model->front_user_login($this->input->post('email'), $this->input->post('password'));				
				$loginResponce = array('status' 		=> 'true',
										'message'   	=> "<br/>Your Registration is successful.",
										'redirect_url' 	=>'user/dashboard?acntype=edit_profile'
									  );
				/*------------------activation mail code-------------------------------------*/
				/*$site_title  	= site_info('site_title');
				$loginResponce = array('status' 	=> 'true',
										'message'   => "<br/>Your Registration is successful.<br/> welcome to <b>".ucwords($site_title)."</b>  <b> Email address verification needed</b><br/>Before you can login, please check your email to activate your user account. if you don't receive an email within a few secounds, please check your spam."
									  );
				$email_template = $this->cimail_email->get_email_template(6);
				$activate_link  = base_url('home/activateAccount/'.$activationcode);				
				$mail_from_name	= site_info('mail_from_name');
				$mail_from_email= site_info('mail_from_email');
				$param = array(
							'template'  	=> array(
							'temp'  		=> $email_template->template_body,
							'var_name' 		=> array(
													'username'  	=> $this->input->post('name'),
													'site_name' 	=>  $site_title,	
													'activate_link' =>  $activate_link,	
													), 
							),      

					'email' =>  array(
									'to'        =>  $this->input->post('email'),
									'from'      =>  $mail_from_email,
									'from_name' =>  $mail_from_name,
									'subject'   =>  $email_template->template_subject,
									)
				);  				
				$status  = $this->cimail_email->send_mail($param);*/	
			}else{
				$loginResponce = array('status' 	=> 'true',
										'message'   => 'Registration failed, try again'
									   );

			}
		}else{
			$loginResponce = array('status'   => 'false',
									'message' => validation_errors()
						  		  );
		}
		echo json_encode($loginResponce);
	}
	public function checkUserName(){	
		$loginResponce = array();		
		$this->form_validation->set_rules('user_name', 'user name', 'required|is_unique[users.user_name]');
		if ($this->form_validation->run() == TRUE){	
			$loginResponce = array('status'   => 'true',
									'message' => 'available'
						  		  );
		}else{
			$loginResponce = array('status'   => 'false',
									'message' => validation_errors()
						  		  );
		}
		echo json_encode($loginResponce);
	}
	public function checkUserEmail(){	
		$loginResponce = array();		
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_emailChecked');
		if ($this->form_validation->run() == TRUE){	
			$loginResponce = array('status'   => 'true',
									'message' => 'available'
						  		  );
		}else{
			$loginResponce = array('status'   => 'false',
									'message' => validation_errors()
						  		  );
		}
		echo json_encode($loginResponce);
	}
	public function profile_pic(){          
        $array = array('statuss'=>'false', 'message'=>'') ;    
        $this->form_validation->set_rules('user_img','','callback_user_image_check'); 
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>'); 
        if ($this->form_validation->run() == TRUE){ 
            $userdata                   = array();
            if($this->session->userdata('user_image_check')!=''){
                $user_image_check=$this->session->userdata('user_image_check');
                $userdata['file'] = $user_image_check['user_img'];
                $this->session->unset_userdata('user_image_check');  
                $array = array('statuss'=>'true','message'=>'profile pic changed','file_name'=>$user_image_check['user_img'],'full_path'=>base_url().'assets/uploads/users/thumbnails/'.$user_image_check['user_img']) ;     
            }
        }else{
            $array = array('statuss'=>'false','message'=>form_error('user_img')) ;    
        }
        echo json_encode($array);
    } 
    public function user_image_check($str){
        $allowed = array("image/jpeg", "image/jpg", "image/png"); 
          if(empty($_FILES['user_img']['name'])){
              $this->form_validation->set_message('user_image_check', 'Choose logo');
              return FALSE;
           }
          if(!in_array($_FILES['user_img']['type'], $allowed)) {
            $this->form_validation->set_message('user_image_check', 'Only jpg, jpeg, and png files are allowed');
              return FALSE;
        }
           $image = getimagesize($_FILES['user_img']['tmp_name']);
           if ($image[0] < 250 || $image[1] < 250) {
               $this->form_validation->set_message('user_image_check', 'Oops! Your logo needs to be atleast 250 x 250 pixels');
               return FALSE;
           }
           if ($image[0] > 2000 || $image[1] > 2000) {
               $this->form_validation->set_message('user_image_check', 'Oops! Your logo needs to be maximum of 2000 x 2000 pixels');
               return FALSE;
           }
        if(!empty($_FILES['user_img']['name'])):
            $config['encrypt_name'] 	= TRUE;
            $new_name 				    = 'image_'.substr(md5(rand()),0,7).$_FILES["user_img"]['name'];
            $config['file_name'] 		= $new_name;
            $config['upload_path'] 		= 'assets/uploads/users/';
            $config['allowed_types'] 	= 'jpeg|jpg|png';
            $config['max_size']  		= '7024';
            $config['max_width']  		= '2000';
            $config['max_height']   	= '2000';
            $this->load->library('upload', $config);
            if ( ! $this->upload->do_upload('user_img')){
                $this->form_validation->set_message('user_image_check', $this->upload->display_errors());
                return FALSE;
            }else{
                $data = $this->upload->data(); // upload image
                $config_img_p['source_path'] = 'assets/uploads/users/';
                $config_img_p['destination_path'] = 'assets/uploads/users/thumbnails/';
                $config_img_p['width']      = '250';
                $config_img_p['height']     = '250';
                $config_img_p['file_name']  = $data['file_name'];
                $status=create_thumbnail($config_img_p);
                $this->session->set_userdata('user_image_check',array('image_url'=>$config['upload_path'].$data['file_name'],
                     'user_img'=>$data['file_name']));
                unlink('assets/uploads/users/'.$data['file_name']);
                return TRUE;
            } 
        else:
            $this->form_validation->set_message('user_image_check', 'The %s field required.');
            return FALSE;
            endif;
    } 

  	public function update_password($key=""){
  		if(empty($key)){
  			redirect(base_url('home/error_404'));
  		}
  		if(!empty($key)){
  			$status = $this->common_model->get_row('users', array('activation_code'=>$key));
  			if(empty($status)){
  				redirect(base_url('home/error_404'));
  			}  			
  		}
  		if(isset($_POST['submit'])){
	        $this->form_validation->set_rules('newpassword','New password','required');
	        $this->form_validation->set_rules('confpassword','Confirm password','required|matches[newpassword]');
	        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
	        if($this->form_validation->run()==true){
				$newSalt      = salt();  
				$password     = passwordGenrate($this->input->post('newpassword'), $newSalt);
				$where        = array('activation_code'=>$key);
				$data         = array('password'=>$password, 'salt'=>$newSalt);
				$success      = $this->common_model->update('users', $data, $where);
				if($success){
					$this->session->set_flashdata('msg_success', 'Password is updated successfully');
					redirect(base_url('?acntype=login'));	
				}else{
					$this->session->set_flashdata('msg_error', "Password couldn't update, try again.");
					redirect(base_url('home/update_password/'.$userId));
				}
	        }
	    }  
	    $data['template'] 		= 'frontend/newPassword';
	    $this->load->view('templates/frontend_template', $data);  
	}    
	public	 function activateAccount($id=''){	
		$row = $this->common_model->get_row('users', array('activation_code'=>$id,'is_email_verify'=>0));
		if($row){	
			$update = $this->common_model->update('users', array('is_email_verify'=>1), array('id'=>$row->id));
			if($update){
				$this->session->set_flashdata('msg_success', 'Your account is activated successfuly');
				redirect(base_url('?acntype=login'));			    
			}
		}else{
			redirect(base_url('?acntype=login'));	
		}
	}	
  	public function forgot_password(){	
	    if(isset($_POST['submit'])){  
	        $this->form_validation->set_rules('email','Email','required|callback_emailCheckedforgot');
	        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
	      	if($this->form_validation->run()==true){
	      		$email          = $this->input->post('email');
	      		$where 			= "`email` = '".$email."' OR `user_name` = '".$email."'";
	      		$row 			= $this->common_model->get_row('users', array(), array(), array(), $where);
		        $where          = array('email' => $row->email);
		        $activationcode = uniqid();
		        $data           = array('activation_code'=>$activationcode);
		        $success        = $this->common_model->update('users', $data, $where);
		        if($success){
					$url      		= base_url()."home/update_password/".$activationcode;	
					$email_template = $this->cimail_email->get_email_template(2);
					$activate_link  = base_url('home/update_password/'.$activationcode);
					$site_title 	= site_info('site_title');
					$mail_from_name = site_info('mail_from_name');
					$mail_from_email= site_info('mail_from_email');
					$param=array(
								'template'  	=> array(
								'temp'  		=> $email_template->template_body,
								'var_name' 		=> array(
														'name'  	     => $row->first_name.' '.$row->last_name,
														'site_name' 	 => $site_title,	
														'activation_key' => $activate_link,		
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
					if($status['EMAIL_STATUS']==1){
						$this->session->set_flashdata('msg_success', 'Reset password link sent to your email account ');
					}else{
						$this->session->set_flashdata('msg_error', 'Mail sending failed, try again');
					}					
					redirect(base_url('home/forgot_password'));
		        }
	        }
	    }  
	    $data['template'] 		= 'frontend/forgot_password';
	    $this->load->view('templates/frontend_template', $data);
	}	
    public function sendForgotPasswordMail(){
    	$mailResponse = array('status' =>'false', 'message'=>'The email is invalid');
    	$this->form_validation->set_rules('forgotEmail', 'Email', 'trim|required|valid_email');
    	if ($this->form_validation->run() == TRUE){
        	$forgotEmail = $this->input->post('forgotEmail'); 
        	$user 	= $this->common_model->get_row('users', array('email' =>$forgotEmail),array('id','email','first_name')); 
           if($user){

           	  $activationCode = substr(time().$user->id,-6);

           	  if($this->common_model->update('users',array('activation_code'=>$activationCode), array('id'=>$user->id))){

           	  		$this->load->library('Cimail_email');

			        $subject = $this->common_model->get_row('email_templates',array('id'=>3));

			        $link= base_url().'home/reset_password/'.$activationCode; 

			        $email_template=$this->cimail_email->get_email_template(2);

			        $site_url = "<a href=".base_url()." target='_blank'>".SITE_NAME."</a>";

			        $param=array(

			        'template'  =>  array(

			                        'temp'  =>  $email_template->template_body,

			                        'var_name'  =>  array(
			                        'name'  => $user->first_name,
			                        'activation_key'  =>  $link,
			                        'site_url' =>  $site_url,  
			                        ), 
			        ),          

			        'email' =>  array(
			                'to'        =>   $user->email,
			                'from'      =>   NO_REPLY,
			                'from_name' =>   NO_REPLY_EMAIL_FROM_NAME,
			                'subject'   =>   $email_template->template_subject,
			            )
			        );  
			        $status=$this->cimail_email->send_mail($param);
	           	  	$mailResponse = array('status' =>'true',
	           	  				   'message'=>'A password reset link will be sent to your email.'
	           	  				 );	
           	  	}  
           }else{
           	 	$mailResponse = array('status' =>'false',
           	  				 'message'=>'Email does not  exist.'
           	  				 );
           }
       }              		
        echo json_encode($mailResponse);
    } 	

    public function reset_password($activation_key=''){   
      $data['title']='Reset password';  
      if(empty($activation_key)){ redirect(base_url('?acntype=login')); }
      if(!empty($activation_key)){
      	$user = $this->common_model->get_row('users',array('activation_code'=>trim($activation_key)));
      	if($user==FALSE){
          $this->session->set_flashdata('msg_error','Your activation key expired.');
          redirect(base_url('?acntype=login'));
       	} 
      }
      $this->form_validation->set_rules('password', 'New Password', 'trim|required|min_length[6]|matches[confpassword]');
      $this->form_validation->set_rules('confpassword','Confirm Password', 'trim|required');
      $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
      	if ($this->form_validation->run() == TRUE){
          	$user = $this->common_model->get_row('users',array('activation_code'=>trim($activation_key)));
            if($user==FALSE){
              $this->session->set_flashdata('msg_error','Your activation key expired.');
              redirect(base_url('?acntype=login'));
           } 
            $salt = salt();
		    $user_data  = array('salt'=>$salt,
								'password' => sha1($salt.sha1($salt.sha1($this->input->post('password')))),
								'activation_code'=>''
								);
            if($this->common_model->update('users', $user_data, array('id'=>$user->id))){ 
                $this->session->set_flashdata('msg_success','Your Password has reset successfully <br/> now you can Login.');
                redirect(base_url('?acntype=login'));
            }
      }
      $data['template'] 		= 'frontend/newPassword';
	  $this->load->view('templates/frontend_template', $data);
    }     
    /*validation function*/
    public function emailChecked($email){  
		$confirm    = $this->common_model->get_row('users', array('email'=>$email));
		if($confirm){
			$this->form_validation->set_message('emailChecked','The entered email is already registered.');
			return false;
		}else{ 
			return true;
		}
    }  
    public function userNameChecked($email){  
		$confirm    = $this->common_model->get_row('users', array('user_name'=>$email));
		if($confirm){
			$this->form_validation->set_message('userNameChecked','The selected username is already taken.');
			return false;
		}else{ 
			return true;
		}
    }
	public function emailCheckedforgot($email){ 
		$where = "`email` = '".$email."' OR `user_name` = '".$email."'";
		$confirm    = $this->common_model->get_row('users', array(), array(), array(), $where);		
		if($confirm){ 
			return TRUE;
		}else{    
			$this->form_validation->set_message('emailCheckedforgot', 'The email or user name is not registered');
			return FALSE;
		}
	}
	public function mobileChecked($mobile){  
		$confirm    = $this->common_model->get_row('users', array('mobile'=>$mobile));
		if($confirm){ 
			$this->form_validation->set_message('mobileChecked','The %s  is  already exist.');
			return false;
		}else{  
			return true;
		}
	}	
	public function newsletter(){
		$array 	  = array('status'=>'false', 'message'=>'');
		if($this->input->post('email')){
			$email = $this->input->post('email');
	        if($this->common_model->get_row('newsletters', array('email'=>$email))){
	        	$array 	  = array('status'=>'true', 'message'=>'Thank you for your subscription.');
	        }else{
	        	$this->common_model->insert('newsletters', array('email'=>$email, 'created_date'=>date('Y-m-d H:i:s')));
	        	$array 	  = array('status'=>'true', 'message'=>'Thank you for your subscription.');
	        }
		}
        echo json_encode($array);
	}
	public function contact_us(){
		$this->form_validation->set_rules('name','name','trim|required');
        $this->form_validation->set_rules('email','email','trim|required|valid_email');
        $this->form_validation->set_rules('phone','phone','trim|required|numeric');
        $this->form_validation->set_rules('message','message','trim|required'); 
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run() == TRUE){ 
            $userdata                   = array();
            if($this->input->post('name'))
                $userdata['name']     = $this->input->post('name');
            if($this->input->post('email'))
                $userdata['email']      = $this->input->post('email'); 
            if($this->input->post('phone'))
                $userdata['mobile']      = $this->input->post('phone'); 
            if($this->input->post('message'))
                $userdata['message']       = $this->input->post('message');
            $this->common_model->insert('contact_us', $userdata);
               $this->session->set_flashdata('msg_success', 'You are  contact is successfull');
            redirect('home/contact_us');
        } 
		$data['title']        = 'contact';
		$data['template']='frontend/contact';
	    $this->load->view('templates/frontend_template',$data);
	}
	public function error_404(){	
	    $data['template']='frontend/error_404';
	    $this->load->view('templates/frontend_template',$data);
	} 
	public	 function search(){			
		$data['rows']  = $this->developer_model->get_recipe_blog_images();
		$this->load->view('frontend/result_listing', $data);
	}	
}