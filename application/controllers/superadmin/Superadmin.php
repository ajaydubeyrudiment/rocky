<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*this controller for making manage  superadmin */
class Superadmin extends CI_Controller {
	public function __construct(){
        parent::__construct();
        clear_cache();
        $this->_check_login();       
        $this->load->model('common_model');
    }   
	public function index(){		
		$this->dashboard();
	}
	/*check superadmin login */
	private function _check_login(){
		if(superadmin_logged_in()===FALSE)
			redirect(base_url().ADMIN_DIR.'login');
	}
	/*logout superadmin user*/
	public function logout(){
		$this->_check_login(); //check  login authentication
		$this->session->sess_destroy();
		redirect(base_url().ADMIN_DIR.'login');
	}
	/*show superadmin dashboard*/
	public function dashboard(){    	   
	    $data['template']='superadmin/dashboard';
	    $this->load->view('templates/superadmin_template',$data);
    }
    /*validation funcation for email */
    public function email_check($new){
        if ($this->common_model->get_row('admin_users',array('email'=>$new))){ 
            $this->form_validation->set_message('email_check','This email address already exists');
            return FALSE;
        }else {
            return TRUE; 
        } 
    } 
    /*check mobile no exists*/
    public function mobile_check($new){
        if ($this->common_model->get_row('users',array('mobile'=>$new))) 
        { 
            $this->form_validation->set_message('mobile_check','This mobile no. already exists');
            return FALSE;
        } else {
            return TRUE; 
        } 
    }    
	/*change user status*/
	public function changeUserStatus($table_name,$id="",$status="")	{
		$this->_check_login(); //check login authentication
		if($status==1) $status=1;
		else $status=2;
		$data=array('status'=>$status);
		if($this->common_model->update($table_name, $data,array('id'=>$id)))
		$this->session->set_flashdata('msg_success','Status updated successfully');
		redirect($_SERVER['HTTP_REFERER']);
	}	
	/*upload images*/
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
	    if ($image[0] < 100 || $image[1] < 100) {
	        $this->form_validation->set_message('user_image_check', 'Oops! Your logo needs to be atleast 100 x 100 pixels');
	        return FALSE;
	    }
	    if ($image[0] > 2000 || $image[1] > 2000) {
	        $this->form_validation->set_message('user_image_check', 'Oops! Your logo needs to be maximum of 2000 x 2000 pixels');
	        return FALSE;
	    }
	    if(!empty($_FILES['user_img']['name'])):
			$config['encrypt_name'] = TRUE;
			  $new_name = 'image_'.substr(md5(rand()),0,7).$_FILES["user_img"]['name'];
			$config['file_name'] = $new_name;
			$config['upload_path'] = 'assets/uploads/admin/images/';
			$config['allowed_types'] = 'jpeg|jpg|png';
			$config['max_size']  = '5024';
			$config['max_width']  = '2000';
			$config['max_height']  = '2000';
			$this->load->library('upload', $config);
	    if ( ! $this->upload->do_upload('user_img')){
	        $this->form_validation->set_message('user_image_check', $this->upload->display_errors());
	        return FALSE;
	    }
	    else{
			$data = $this->upload->data(); // upload image
			$config_img_p['source_path'] = 'assets/uploads/admin/images/';
			$config_img_p['destination_path'] = 'assets/uploads/admin/images/thumbnails/';
			$config_img_p['width']  = '100';
			$config_img_p['height']  = '100';
			$config_img_p['file_name'] =$data['file_name'];
			$status=create_thumbnail($config_img_p);
			$this->session->set_userdata('user_image_check',array('image_url'=>$config['upload_path'].$data['file_name'],
			   'user_img'=>$data['file_name']));
			return TRUE;
	    }else:
	        $this->form_validation->set_message('user_image_check', 'The %s field required.');
	        return FALSE;
	    endif;
    }
    /*superadmin dashboard*/
	public function profile(){		
		$data['title']='profile';
		$oldEmail = $this->input->post('oldEmail');
		$email    = $this->input->post('email');
		$this->form_validation->set_rules('first_name', 'first Name', 'required');
		$this->form_validation->set_rules('last_name', 'last Name', 'required');
		if($oldEmail == $email){
		   $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		}else{
		   $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_email_check');
		}
		if (!empty($_FILES['user_img']['name'])){
	      $this->form_validation->set_rules('user_img','','callback_user_image_check');
	    }
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run() == TRUE){
			$user_data  = array();
		    if($this->session->userdata('user_image_check')!=''){        
		        $user_image_check=$this->session->userdata('user_image_check');
		        $user_data['image'] = $user_image_check['user_img'];	
		        $this->session->unset_userdata('user_image_check');       
		    }
			$user_data['first_name'] =  $this->input->post('first_name');
			$user_data['last_name']	 =  $this->input->post('last_name');
			$user_data['email']		 =	$this->input->post('email');			
			if($this->common_model->update('admin_users', $user_data,array('id'=>superadmin_id()))){
				$this->session->set_flashdata('msg_success','Profile updated successfully');
				redirect(ADMIN_URL.'superadmin/profile');
			}else{
				$this->session->set_flashdata('msg_error','Update failed, Please try again');
				redirect(ADMIN_URL.'superadmin/profile');
			}
		}else{
			$data['user'] = $this->common_model->get_row('admin_users', array('id'=>superadmin_id()));
			$data['template']='superadmin/profile';
			$this->load->view('templates/superadmin_template',$data);
		}
	}
	public function setting(){
		if(isset($_POST['submit'])){
			if($this->input->post('admin_email')){
				$this->common_model->update('web_info', array('meta_data'=>$this->input->post('admin_email')), array('meta_key'=>'admin_email'));	
				$this->session->set_flashdata('msg_success','Setting is updated successfully');
			}
			if($this->input->post('newslettor_email')){
				$this->common_model->update('web_info', array('meta_data'=>$this->input->post('newslettor_email')), array('meta_key'=>'newslettor_email'));	
				$this->session->set_flashdata('msg_success','Setting is updated successfully');
			}
			if($this->input->post('fb_link')){
				$this->common_model->update('web_info', array('meta_data'=>$this->input->post('fb_link')), array('meta_key'=>'fb_link'));	
				$this->session->set_flashdata('msg_success','Setting is updated successfully');
			}
			if($this->input->post('google_link')){
				$this->common_model->update('web_info', array('meta_data'=>$this->input->post('google_link')), array('meta_key'=>'google_link'));	
				$this->session->set_flashdata('msg_success','Setting is updated successfully');
			}
			if($this->input->post('twittor_link')){
				$this->common_model->update('web_info', array('meta_data'=>$this->input->post('twittor_link')), array('meta_key'=>'twittor_link'));	
				$this->session->set_flashdata('msg_success','Setting is updated successfully');
			}
			if($this->input->post('instagram_link')){
				$this->common_model->update('web_info', array('meta_data'=>$this->input->post('instagram_link')), array('meta_key'=>'instagram_link'));	
				$this->session->set_flashdata('msg_success','Setting is updated successfully');
			}
			if($this->input->post('pinterest_link')){
				$this->common_model->update('web_info', array('meta_data'=>$this->input->post('pinterest_link')), array('meta_key'=>'pinterest_link'));	
				$this->session->set_flashdata('msg_success','Setting is updated successfully');
			}
			if($this->input->post('contact_name')){
				$this->common_model->update('web_info', array('meta_data'=>$this->input->post('contact_name')), array('meta_key'=>'contact_name'));	
				$this->session->set_flashdata('msg_success','Setting is updated successfully');
			}
			if($this->input->post('contact_email')){
				$this->common_model->update('web_info', array('meta_data'=>$this->input->post('contact_email')), array('meta_key'=>'contact_email'));	
				$this->session->set_flashdata('msg_success','Setting is updated successfully');
			}
			if($this->input->post('contact_address')){
				$this->common_model->update('web_info', array('meta_data'=>$this->input->post('contact_address')), array('meta_key'=>'contact_address'));	
				$this->session->set_flashdata('msg_success','Setting is updated successfully');
			}
			if($this->input->post('contact_number')){
				$this->common_model->update('web_info', array('meta_data'=>$this->input->post('contact_number')), array('meta_key'=>'contact_number'));	
				$this->session->set_flashdata('msg_success','Setting is updated successfully');
			}
			if($this->input->post('outside_contact_number')){
				$this->common_model->update('web_info', array('meta_data'=>$this->input->post('outside_contact_number')), array('meta_key'=>'outside_contact_number'));	
				$this->session->set_flashdata('msg_success','Setting is updated successfully');
			}
			if($this->input->post('outside_contact_number_country')){
				$this->common_model->update('web_info', array('meta_data'=>$this->input->post('outside_contact_number_country')), array('meta_key'=>'outside_contact_number_country'));	
				$this->session->set_flashdata('msg_success','Setting is updated successfully');
			}
			redirect('superadmin/superadmin/setting');
		}
		$data['setting'] = $this->common_model->get_result('web_info', array());
		$data['template']='superadmin/setting';
		$this->load->view('templates/superadmin_template', $data);
	}		
	/*change super admin password*/
	public function change_password(){
		$this->_check_login(); //check login authentication
		$data['title']='change_password';
		$this->form_validation->set_rules('oldpassword', 'old password', 'trim|required|callback_password_check');
		$this->form_validation->set_rules('newpassword', 'new password', 'trim|required|min_length[6]|matches[confpassword]');
		$this->form_validation->set_rules('confpassword','confirm password', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		if ($this->form_validation->run() == TRUE){
			$salt = salt();
			$user_data  = array('salt'=>$salt,'password' => sha1($salt.sha1($salt.sha1($this->input->post('newpassword')))));
			$id =superadmin_id();
			if($this->common_model->update('admin_users',$user_data,array('id'=>$id))){
				$this->session->set_flashdata('msg_success','Password updated successfully');
			}else{
				$this->session->set_flashdata('msg_error','Update failed, please try again.');			
			}
		}
		$data['template']='superadmin/change_password';
		$this->load->view('templates/superadmin_template',$data);
	}
	/*check superadmin password*/
	public function password_check($oldpassword){		
		$user_info = $this->common_model->get_row('admin_users',array('id'=>superadmin_id()));
		$salt = $user_info->salt;
		if($this->common_model->password_check(array('password'=>sha1($salt.sha1($salt.sha1($oldpassword)))),superadmin_id())){
			return TRUE;
		}else{
			$this->form_validation->set_message('password_check', 'The %s does not match.');
			return FALSE;
		}
	}	
	/*active and deactive */ 
  	public function changeStatus($table='', $title='', $id='', $status=''){
		if(preg_match('/^\d+$/', $id)){ 
		      $update = $this->common_model->update($table, array('status'=>$status), array('id'=> $id));            
		    if($update){             
		        if($status ==2){
		          	$this->session->set_flashdata('msg_success', ucfirst($title).' is deactivated successfully');
		        }elseif($status ==1){
		          	$this->session->set_flashdata('msg_success', ucfirst($title).' is activated successfully');
		        } elseif($status ==3){
		          	$this->session->set_flashdata('msg_success', ucfirst($title).' is deleted successfully');
		        }          
		    } 
		    redirect($_SERVER['HTTP_REFERER']);            
		}else{
		    redirect(ADMIN_DIR.'superadmin/error_404');
		}
  	} 
	/*if page and data is not available in superadmin section show this page*/
	function error_404(){     
      $data['template']='superadmin/404';  
      $this->load->view('templates/superadmin_template',$data); 
    }    
}