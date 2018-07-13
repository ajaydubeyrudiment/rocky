<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*this controller for making user section*/
class User extends CI_Controller {
    public function __construct(){
        parent::__construct();
        clear_cache();
        if(!user_logged_in()){ 
            redirect(base_url());
        }
    } 
    /*************************     user  section **************************************/
    public function index(){ 
       redirect(base_url('user/dashboard'));
    } 
    /*show user dashboard  */  
    public function dashboard(){ 
        $data['gridCol']    = 3;
        $data['bgridCol']   = 6;
        $data['rows']       = $this->developer_model->get_recipe_blog_images();
        $data['template']   = 'frontend/home';
        $this->load->view('templates/user_template', $data);
    }
    /*show user profile  */    
    public function profile($userNameID=''){ 
        $data['gridCol']    = 3;
        $data['bgridCol']   = 6;
        if(!empty($userNameID)){
            $userIds        = explode('-', $userNameID);
            $userID         = end($userIds);
            $data['userID'] = $userID ;
            if(!is_numeric($userID)){ redirect('user/dashboard');}
        }else{
           $userID = user_id();
        }
        $data['rows']       = $this->developer_model->get_recipe_blog_images($userID);
        //echo $this->db->last_query(); exit();
        //echo '<pre>';print_r($data['rows']); exit();
        $data['comments']   = $this->developer_model->getUserComments();
        $data['template']   = 'frontend/profile';
        $this->load->view('templates/user_template', $data);
    } 
    /* inbox */
    public function user_profile(){ 
        if($this->input->get('user_id', TRUE)){            
            $data['rows']       = $this->developer_model->get_recipe_blog_images($this->input->get('user_id', TRUE));
            $data['template']   = 'frontend/other_user_profile';
            $this->load->view('templates/user_template', $data); 
        }else{
            redirect(base_url().'home/error_404');
        }
    }
    /* inbox */
    public function getFollowerUser(){ 
        $data['rows']    = $this->developer_model->getFollowerUser($this->input->get('user_id', TRUE));
        $this->load->view('frontend/getFollowerUser', $data); 
    }
     public function getFollowingUser(){ 
        $data['rows']    = $this->developer_model->getFollowingUser($this->input->get('user_id', TRUE));
        $this->load->view('frontend/getFollowingUser', $data); 
    }
    public function filterImagesRecipes(){
        if($this->input->get('user_id', TRUE)){           
            $data['rows']  = $this->developer_model->get_recipe_blog_images($this->input->get('user_id', TRUE),NULL,NULL,NULL,'filter');
            $this->load->view('frontend/images_list', $data); 
        }
    }
    public function filterBlogs(){
        if($this->input->get('user_id', TRUE)){           
            $data['rows']       = $this->developer_model->get_recipe_blog_images($this->input->get('user_id', TRUE),NULL,NULL,NULL,'filter');
            $this->load->view('frontend/blog_list', $data); 
        }
    }
    public function filterLikesBookMarkBlogImges(){
        if($this->input->get('user_id', TRUE)){  
            $data['gridCol']    = 3;
            $data['bgridCol']   = 6;         
            $data['rows']       = $this->developer_model->getLikesBookMarkBlogImges($this->input->get('user_id', TRUE));
            $this->load->view('frontend/result_listing', $data); 
        }
    } 
    /*show user profile  */ 
    public function email_check($new){
        if ($this->common_model->get_row('users',array('email'=>$new))){ 
            $this->form_validation->set_message('email_check','This email address already exists');
            return FALSE;
        } else {
            return TRUE; 
        } 
    }
    public function edit_profile_res(){ 
        //print_r($_POST);
        $ajaxResponce = array('status'=>'false','message'=>'');
        $this->form_validation->set_rules('dateofbirth', 'date of birth', 'xss_clean|required');
        $this->form_validation->set_rules('gender', 'gender', 'xss_clean|required');     
        $this->form_validation->set_rules('weight', 'weight', 'xss_clean|numeric');     
        if ($this->form_validation->run() == TRUE){ 
           //echo '<pre>'; print_r($_POST); exit();       
            $userdata                   = array();        
            if($this->input->post('first_name'))
                $userdata['first_name']  = $this->input->post('first_name', TRUE);   
            if($this->input->post('about'))
                $userdata['about']  = $this->input->post('about', TRUE);   
            if($this->input->post('dateofbirth'))
                $userdata['date_of_birth']  = $this->input->post('dateofbirth', TRUE);  
            if($this->input->post('gender'))
                $userdata['gender']  = $this->input->post('gender', TRUE);
            if($this->input->post('profile_type')&&$this->input->post('profile_type')=='first_time'){
                if($this->input->post('useMetricsSystem')){
                    $userdata['useMetricsSystem']  = $this->input->post('useMetricsSystem', TRUE);
                }else{
                    $userdata['useMetricsSystem']  = 2;
                }            
            }
            if($this->input->post('height'))
                $userdata['height']      = $this->input->post('height', TRUE); 
            if($this->input->post('weight'))
                $userdata['weight']      = $this->input->post('weight', TRUE);  
            if($this->input->post('privacy'))
                $userdata['privacy']     = $this->input->post('privacy', TRUE); 
            if($this->input->post('profile_pic_name'))
                $userdata['profile_pic'] = $this->input->post('profile_pic_name', TRUE);            
            $userdata['profile_view_status'] = 1;   
            if(!empty($userdata)){
                $update = $this->common_model->update('users',$userdata, array('id'=>user_id()));
                if($update){
                    $ajaxResponce = array('status'      => 'true',
                                          'message'     => 'Profile is changed successfully',
                                          'about'       => $this->input->post('about', TRUE),
                                          'first_name'  => $this->input->post('first_name', TRUE),
                                          'dateofbirth' => $this->input->post('dateofbirth', TRUE)
                                        );  
                }else{ 
                    $ajaxResponce = array('status'   => 'false',
                                    'message' => 'Profile changes  failed'
                                  );          
                } 
            } 
        }else{
            $ajaxResponce = array('status'   => 'false',
                                    'message' => validation_errors()
                                  );
        }
        echo json_encode($ajaxResponce);           
    }  
    public function profile_pic(){          
        $array = array('statuss'=>'false', 'message'=>'') ;    
        $this->form_validation->set_rules('user_img','','trim|xss_clean|callback_user_image_check'); 
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>'); 
        if ($this->form_validation->run() == TRUE){ 
            $userdata                   = array();
            if($this->session->userdata('user_image_check')!=''){
                $user_image_check = $this->session->userdata('user_image_check');
                $userdata['file'] = $user_image_check['user_img'];
                $this->session->unset_userdata('user_image_check');  
                if($this->input->post('file_type_name')=='metrics_img1'){
                    $updatedDate = array('metrics_img1'=>$user_image_check['user_img']);
                }else if($this->input->post('file_type_name')=='metrics_img2'){
                    $updatedDate = array('metrics_img2'=>$user_image_check['user_img']);
                }else{
                    $updatedDate = array('profile_pic'=>$user_image_check['user_img']);
                }
                $this->common_model->update('users', $updatedDate, array('id'=>user_id()));
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
           if ($image[0] < 100 || $image[1] < 100) {
               $this->form_validation->set_message('user_image_check', 'Oops! Your profile pic needs to be atleast 100 x 100 pixels');
               return FALSE;
           }
           if ($image[0] > 2000 || $image[1] > 2000) {
               $this->form_validation->set_message('user_image_check', 'Oops! your profile pic needs to be maximum of 2000 x 2000 pixels');
               return FALSE;
           }
        if(!empty($_FILES['user_img']['name'])):
            $config['encrypt_name']     = TRUE;
            $new_name                   = 'image_'.substr(md5(rand()),0,7).$_FILES["user_img"]['name'];
            $config['file_name']        = $new_name;
            $config['upload_path']      = 'assets/uploads/users/';
            $config['allowed_types']    = 'jpeg|jpg|png';
            $config['max_size']         = '7024';
            $config['max_width']        = '2000';
            $config['max_height']       = '2000';
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
    public function setting(){
        $data             = array();
        $data['user']     = $this->common_model->get_row('users', array('id' =>user_id()));      
        $data['template'] = 'frontend/setting';
        $this->load->view('templates/user_template', $data);
    } 
    /*save user setting  */ 
    public function saveSetting(){ 
        //print_r($_POST); exit();
        $ajaxResponce = array('status'=>'false','message'=>'');
        $this->form_validation->set_rules('contactEmail', 'contact email', 'xss_clean|valid_email');
        $this->form_validation->set_rules('paypalEmail', 'paypal email', 'xss_clean|valid_email');
        $this->form_validation->set_rules('contactMobile', 'contact mobile', 'xss_clean|numeric');
        if ($this->form_validation->run() == TRUE){        
            $userdata                   = array();        
            if($this->input->post('privacy'))
                $userdata['privacy']  = $this->input->post('privacy', TRUE);   
            if($this->input->post('advancedMetricsTracking')){                
                $userdata['advancedMetricsTracking']  = $this->input->post('advancedMetricsTracking', TRUE); 
            }else{
                $userdata['advancedMetricsTracking']  = 2; 
            }
            if($this->input->post('useMetricsSystem')){
                $userdata['useMetricsSystem']     = $this->input->post('useMetricsSystem', TRUE);
            }else{
                $userdata['useMetricsSystem']     = 2;
            }
            if($this->input->post('contactEmail'))
                $userdata['contactEmail']     = $this->input->post('contactEmail', TRUE); 
             if($this->input->post('contactMobile'))
                $userdata['contactMobile']     = $this->input->post('contactMobile', TRUE);  
            if($this->input->post('paypalEmail'))
                $userdata['paypalEmail']     = $this->input->post('paypalEmail', TRUE);   
            if($this->input->post('plan'))
                $userdata['plan']     = $this->input->post('plan', TRUE);   
            $user = user_info();
            if($user->useMetricsSystem!=$userdata['useMetricsSystem']){                    
                if($this->input->post('useMetricsSystem')){
                    $userdata['weight']      = number_format($user->weight / 2.2046, 0);  
                }else{
                    $userdata['weight']      = number_format($user->weight * 2.2046, 0); ;  
                }
            }
            if(!empty($userdata)){
                $update = $this->common_model->update('users',$userdata, array('id'=>user_id()));
                if($update){
                    $ajaxResponce = array('status'   => 'true',
                                    'message' => 'Setting is changed successfully'
                                  );  
                }else{ 
                    $ajaxResponce = array('status'   => 'false',
                                    'message' => 'Setting changes  failed'
                                  );          
                } 
            } 
        }else{
            $ajaxResponce = array('status'   => 'false',
                                    'message' => validation_errors()
                                  );
        }
        echo json_encode($ajaxResponce);       
    }
    /*change password*/
    public function change_password(){
        //print_r($_POST); exit();
        $ajaxResponce = array('status'   => 'false','message' => '');   
        $this->form_validation->set_rules('old_password', 'Old password', 'trim|required|xss_clean|callback_password_check');
        $this->form_validation->set_rules('new_password', 'new password', 'trim|required|xss_clean|min_length[6]|matches[confirm_password]');
        $this->form_validation->set_rules('confirm_password','confirm password', 'trim|required');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == TRUE){
            //echo 'yes'; exit();
            $salt = salt();
            $user_data  = array('salt'=>$salt,'password' => sha1($salt.sha1($salt.sha1($this->input->post('newpassword', TRUE)))));
            $id = user_id();
            if($this->common_model->update('users', $user_data,array('id'=>$id))){
                $ajaxResponce = array('status'   => 'true','message' => 'Password is updated successfully');   
            }else{               
                $ajaxResponce = array('status'   => 'true','message' => 'Password is not  update, try again');
            }
        }else{
             $ajaxResponce = array('status'   => 'false',
                                    'message' => validation_errors()
                                  );
        }
        echo json_encode($ajaxResponce);
    }
   /*check old  password */ 
    public function password_check($oldpassword){
        $user_info  = $this->common_model->get_row('users',array('id'=>user_id()));
        $salt       = $user_info->salt;
        if($this->common_model->get_row('users', array('password'=>sha1($salt.sha1($salt.sha1($oldpassword))),'id'=>user_id()))){
            return TRUE;
        }else{
            $this->form_validation->set_message('password_check', 'The %s does not match');
            return FALSE;
        }
    } 
    /*************************     reciepe  section **************************************/   
    /* add recipe */
    public function addRecipe($id=''){
        if($this->input->get('post_id')){
            $data['row']    = $this->common_model->get_row('recipe_blog_image', array('id'=>$this->input->get('post_id')));
            $data['images'] = $this->common_model->get_result('images', array('meta_id'=>$this->input->get('post_id'), 'status'=>1));
        }  
        $data['template']   = 'frontend/add_recipe';
        $this->load->view('templates/user_template', $data); 
    } 
    /* user blog */
    public function addBlog($id=''){  
        if($this->input->get('post_id')){
            $data['row']    = $this->common_model->get_row('recipe_blog_image', array('id'=>$this->input->get('post_id')));
        }
        $data['template']='frontend/addBlog';
        $this->load->view('templates/user_template', $data); 
    }
    /* image post */
    public function imagePost($id=''){ 
        if($this->input->get('post_id')){
            $data['images'] = $this->common_model->get_result('images', array('meta_id'=>$this->input->get('post_id'), 'status'=>1));
        }else{
            $data['images'] = $this->common_model->get_result('images', array('user_id'=>user_id(), 'status'=>1,'saved_images'=>0));
        }
        $data['template']='frontend/image_post';
        $this->load->view('templates/user_template', $data); 
    }
    public function profilePost($ids=''){
        $ida = explode('-', $ids);
        $id  = '';
        if(!empty($ida)){
            foreach($ida as $idaA){
                if(!empty($idaA)&&empty($id)) $id  = $idaA;
            }
        }        
        if(!empty($id)){
            $data['row']    = $this->common_model->get_row('images', array('id'=>$id));
            $data['images'] = $this->common_model->get_result('images', array('meta_id'=>$id, 'status'=>1));
        }  
        if($this->input->get('post_id')){
            $data['post_details']    = $this->common_model->get_row('recipe_blog_image', array('id'=>$this->input->get('post_id')));
        }
        $data['imagesIDA'] = $ids;
        $data['imagesID']  = $id;
        $data['template']  = 'frontend/profile_post';
        $this->load->view('templates/user_template', $data); 
    } 
    public function recipe_blog_image_res(){ 
        $ajaxResponce = array('status'   => 'false','message' => ''); 
        if($this->input->post('recipe_blog_image_type', TRUE)=='recipe'||$this->input->post('recipe_blog_image_type', TRUE)=='blog'){
            $this->form_validation->set_rules('title', 'title', 'trim|xss_clean|required');
            $this->form_validation->set_rules('description', 'description', 'trim|xss_clean|required');        
        }     
        $this->form_validation->set_rules('recipe_blog_image_type', 'type', 'trim|xss_clean|required');  
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');        
        //print_r($_POST);  exit();
        if ($this->form_validation->run() == TRUE){
            $insertedData = array();
            if($this->input->post('recipe_blog_image_type')&&$this->input->post('recipe_blog_image_type')=='profile_post'){
                $insertedData['type'] = 'image';
            }else if($this->input->post('recipe_blog_image_type', TRUE)){
                $insertedData['type'] = $this->input->post('recipe_blog_image_type', TRUE);
            }
            if($this->input->post('title')){
                $insertedData['title'] = $this->input->post('title', TRUE);
            }
            if($this->input->post('description')){
                $insertedData['description'] = $this->input->post('description', TRUE);
            } 
            if($this->input->post('content')){
                $insertedData['content'] = $this->input->post('content');
            }
            if($this->input->post('content_edit')){
                $insertedData['content'] = $this->input->post('content_edit');
            }
            if($this->input->post('ingredients')){
                $insertedData['ingredients'] = $this->input->post('ingredients', TRUE);
            }
            if($this->input->post('instructions')){
                $insertedData['instructions'] = $this->input->post('instructions', TRUE);
            } 
            if($this->input->post('tags')){
                $insertedData['tags'] = $this->input->post('tags', TRUE);
            } 
            if($this->input->post('caption')){
                $insertedData['caption'] = $this->input->post('caption', TRUE);
            }
            if($this->input->post('location')){
                $insertedData['location'] = $this->input->post('location', TRUE);
            } 
            if($this->input->post('tag_users')){
                $insertedData['tag_users'] = $this->input->post('tag_users', TRUE);
            }
            $insertedData['user_id'] = user_id();   
            if($this->input->post('recipe_blog_image_type', TRUE)=='recipe'){
                $messageTag = 'Recipe';
            } else if($this->input->post('recipe_blog_image_type', TRUE)=='blog'){
                $messageTag = 'Blog';
            }  else if($this->input->post('recipe_blog_image_type', TRUE)=='profile_post'){
                $messageTag = 'Image';
            }  
            $insertedData['created_date'] = date('Y-m-d H:i:s');
            if($this->input->post('id')){
                $this->common_model->update('recipe_blog_image', $insertedData, array('id'=>$this->input->post('id', TRUE)));
                $ajaxResponce = array('status'   => 'true',
                                      'type'    => 'edited',
                                      'message' => $messageTag.' is updated successfully.'
                                  );
                $recipeID = $this->input->post('id');
            }else{
                $recipeID = $this->common_model->insert('recipe_blog_image', $insertedData);
                $ajaxResponce = array('status'   => 'true',
                                      'type'    => 'added',
                                      'message' => $messageTag.' is added successfully.'
                                  );
            }
            if($this->input->post('images_ids', TRUE)){
                $images_ids = $this->input->post('images_ids', TRUE);
                $imagesArr  = explode('-', $images_ids);
                if(!empty($imagesArr)){
                    $i = 1;
                    foreach ($imagesArr as $image) {
                        if(!empty($image)){
                            if($i==1){ 
                                $rowID = $this->common_model->get_row('images', array('id'=>$image));
                                $this->common_model->update('recipe_blog_image', array('image_name'=>$rowID->image_name), array('id'=>$recipeID));
                            }
                            $insertedSData['meta_id']      = !empty($recipeID)?$recipeID:0;
                            $insertedSData['saved_images'] = 1;
                            $this->common_model->update('images', $insertedSData, array('id'=>$image));
                            $i++;
                        }
                    }
                }
                $ajaxResponce = array('status'  => 'true',
                                      'type'    => 'edited',
                                      'message' => $messageTag.' is updated successfully.'
                                    );
            }else if($this->input->post('recepeImgs')){
                if($this->input->post('recipe_blog_image_type')=='recipe'){
                    $receipeimgs = explode(',', $this->input->post('recepeImgs', TRUE));
                    $i=1;
                    foreach ($receipeimgs as $receipeimg) {
                        if(!empty($receipeimg)){
                            if($i==1){ $firstImg = $receipeimg;}
                            $this->common_model->insert('images', 
                                                            array(
                                                                'image_name'   => $receipeimg, 
                                                                'meta_id'      => $recipeID,
                                                                'user_id'      =>user_id(),
                                                                'created_date' => date('Y-m-d H:i:s')
                                                            )
                                                        );
                            $i++;
                        }
                    } 
                    if(!empty($firstImg)){
                        $this->common_model->update('recipe_blog_image',  
                                                    array('image_name' => $firstImg), 
                                                    array('id'         => $recipeID)
                                                ); 
                    }                    
                }else if($this->input->post('recipe_blog_image_type')=='blog'){
                    $this->common_model->update('recipe_blog_image',  
                                                    array('coverImage' => $this->input->post('recepeImgs',TRUE)),
                                                    array('id'         => $recipeID)
                                                );
                } else if($this->input->post('recipe_blog_image_type')=='profile_post'){
                    $this->common_model->update('recipe_blog_image',  
                                                    array('image_name' => $this->input->post('recepeImgs', TRUE)), 
                                                    array('id'         => $recipeID)
                                                );
                }          
            }         
        }else{
             $ajaxResponce = array('status'   => 'false',
                                    'message' => validation_errors()
                                  );
        }
        echo json_encode($ajaxResponce);
    } 
    public function images_upload_res(){
        $ajaxResponce = array('status'   => 'true','message' => '', 'type'=> 'updated');  
        $ids = array();
        if($this->input->post('postImagesIds')){
            $postImgs = explode(',', $this->input->post('postImagesIds'));
            if(!empty($postImgs)){
                foreach($postImgs as $postImg){
                    array_push($ids, $postImg);
                }
            }
        }
        if($this->input->post('recepeImgs')){
            if($this->input->post('recipe_blog_image_type')=='image'){
                $receipeimgs = explode(',', $this->input->post('recepeImgs', TRUE));
                $i=1;               
                foreach ($receipeimgs as $receipeimg) {
                    if(!empty($receipeimg)){                        
                        $id = $this->common_model->insert('images', 
                                                            array(
                                                                'image_name'   => $receipeimg, 
                                                                'saved_images' => 0,
                                                                'user_id'      =>user_id(),
                                                                'created_date' => date('Y-m-d H:i:s')
                                                            )
                                                    );
                        array_push($ids, $id);
                        $i++;
                    }
                }                                   
            }
        }
        $editUrls = '';
        if($this->input->post('id')){
            $editUrls = '?post_id='.$this->input->post('id');
        }
        $ajaxResponce = array('status'   => 'true',
                               'type'    => 'updated',
                               'message' => 'Images is uploaded successfully.',
                               'redirectUrl' => base_url().'user/profilePost/'.implode('-', $ids).$editUrls
                            );
        echo json_encode($ajaxResponce); 
    }
    public function uploadRecipeImgFun($str){  
        $filess   = explode('.', $_FILES['user_img']['name']);  
        $imagesEx = array('jpg','jpeg','png');
        $ext      = strtolower(end($filess));
        $allowed  = array("image/jpeg", "image/jpg", "image/png"); 
        if(empty($_FILES['user_img']['name'])){
              $this->form_validation->set_message('uploadRecipeImgFun', 'Choose image/video');
            return FALSE;
        }       
        if(in_array($ext, $imagesEx)){   
            if(!in_array($_FILES['user_img']['type'], $allowed)) {
                $this->form_validation->set_message('uploadRecipeImgFun', 'Only jpg, jpeg, and png files are allowed');
                return FALSE;
            }         
            $image = getimagesize($_FILES['user_img']['tmp_name']);
            if ($image[0] < 150 || $image[1] < 150) {
               $this->form_validation->set_message('uploadRecipeImgFun', 'Oops! '.$_FILES['user_img']['name'].' image needs to be atleast 150 x 150 pixels');
               return FALSE;
            }
            if ($image[0] > 2000 || $image[1] > 2000) {
               $this->form_validation->set_message('uploadRecipeImgFun', 'Oops! '.$_FILES['user_img']['name'].' image needs to be maximum of 2000 x 2000 pixels');
               return FALSE;
            }
            if(!empty($_FILES['user_img']['name'])):
                $config['encrypt_name'] = TRUE;
                $new_name               = 'image_'.substr(md5(rand()),0,7).$_FILES["user_img"]['name'];
                $config['file_name']     = $new_name;
                $config['upload_path']   = 'assets/uploads/recipeBlogImages/';
                $config['allowed_types'] = 'jpeg|jpg|png|wmv|mp4|avi|mov';
                $config['max_size']      = '7024';
                $config['max_width']     = '2000';
                $config['max_height']    = '2000';
                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload('user_img')){
                    $this->form_validation->set_message('user_imauploadRecipeImgFunge_check', $_FILES['user_img']['name'].' '.$this->upload->display_errors());
                    return FALSE;
                }else{
                    $data                               = $this->upload->data(); // upload image
                    $config_img_p['source_path']        = 'assets/uploads/recipeBlogImages/';
                    $config_img_p['destination_path']   = 'assets/uploads/recipeBlogImages/thumbnails/';
                    $config_img_p['width']              = '180';
                    $config_img_p['height']             = '180';
                    $config_img_p['file_name']          = $data['file_name'];
                    $status                             = create_thumbnail($config_img_p);
                    $this->session->set_userdata('uploadRecipeImgFun',array('image_url'=>$config['upload_path'].$data['file_name'],
                         'user_img'=>$data['file_name']));
                    return TRUE;
                } 
            else:
                $this->form_validation->set_message('uploadRecipeImgFun', 'The %s field required.');
                return FALSE;
                endif;
        }else{
            if(!empty($_FILES['user_img']['name'])):
                $config['encrypt_name']  = TRUE;
                $new_name                = 'image_'.substr(md5(rand()),0,7).$_FILES["user_img"]['name'];
                $config['file_name']     = $new_name;
                $config['upload_path']   = 'assets/uploads/recipeBlogImages/';
                $config['allowed_types'] = 'jpeg|jpg|png|wmv|mp4|avi|mov';
                $config['max_size']      = '35120';  //  20 MB       
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('user_img')){
                    $this->form_validation->set_message('user_imauploadRecipeImgFunge_check', $_FILES['user_img']['name'].' '.$this->upload->display_errors());
                    return FALSE;
                }else{
                    $data       = $this->upload->data(); // upload image
                    $this->session->set_userdata('uploadRecipeImgFun', array('user_img'=>$data['file_name']));
                    return TRUE;                    
                } 
            else:
                $this->form_validation->set_message('uploadRecipeImgFun', 'The %s field required.');
                return FALSE;
                endif;
        }
    }
    public function uploadRecipeImg(){  
        $array = array('statuss'=>'false', 'message'=>'') ;    
        $this->form_validation->set_rules('user_img','','xss_clean|callback_uploadRecipeImgFun'); 
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>'); 
        if ($this->form_validation->run() == TRUE){ 
            $userdata                   = array();
            if($this->session->userdata('uploadRecipeImgFun')!=''){
                $user_image_check=$this->session->userdata('uploadRecipeImgFun');
                $userdata['file'] = $user_image_check['user_img'];
                $this->session->unset_userdata('uploadRecipeImgFun'); 
                $imageKey = rand().time();                   ; 
                $filess   = explode('.', $user_image_check['user_img']);  
                $imagesEx = array('jpg','jpeg','png');
                $ext      = strtolower(end($filess));
                if(in_array($ext, $imagesEx)){
                    if($this->input->post('recipe_blog_image_type')=='blog'){                   
                        $strhtml  = '<div class="recepemainImg" id="'.$imageKey.'"><img src="'.base_url().'assets/uploads/recipeBlogImages/thumbnails/'.$user_image_check['user_img'].'" class="receipe_img"/></div>'; 
                    }else{
                        $funcs    = "deleteReciepeImgLocal('".$imageKey."','".$user_image_check['user_img']."');";
                        $strhtml  = '<div class="recepemainImg" id="'.$imageKey.'"><img src="'.base_url().'assets/uploads/recipeBlogImages/thumbnails/'.$user_image_check['user_img'].'" class="receipe_img"/><a onclick="'.$funcs.'" href="javascript:void(0);" class="deleteReceipeImg"><i class="fa fa-times-circle-o" aria-hidden="true"></i></div>'; 
                    }  
                    $array = array('statuss'    => 'true',                    
                                    'file_name' => $user_image_check['user_img'],
                                    'imagehtm'  => $strhtml,
                                    'full_path' => base_url().'assets/uploads/recipeBlogImages/thumbnails/'.$user_image_check['user_img']
                                  ) ;   
                }else{
                    if($this->input->post('recipe_blog_image_type')=='blog'){                   
                        $strhtml  = '<div class="recepemainImg" id="'.$imageKey.'"><img src="'.base_url().'assets/uploads/recipeBlogImages/thumbnails/'.$user_image_check['user_img'].'" class="receipe_img"/></div>'; 
                    }else{
                        $funcs    = "deleteReciepeImgLocal('".$imageKey."','".$user_image_check['user_img']."');";
                        $strhtml  = '<div class="recepemainImg" id="'.$imageKey.'"><video class="receipe_img" controls><source src="'.base_url().'assets/uploads/recipeBlogImages/'.$user_image_check['user_img'].'" type="video/mp4">/video><a onclick="'.$funcs.'" href="javascript:void(0);" class="deleteReceipeImg"><i class="fa fa-times-circle-o" aria-hidden="true"></i></div>'; 
                    }  
                    $array = array('statuss'    => 'true',                    
                                    'file_name' => $user_image_check['user_img'],
                                    'imagehtm'  => $strhtml,
                                    'full_path' => base_url().'assets/uploads/recipeBlogImages/thumbnails/'.$user_image_check['user_img']
                                  ) ;   
                } 
            }
        }else{
            $array = array('statuss'=>'false',
                            'message'=>form_error('user_img')
                        ) ;    
        }
        echo json_encode($array);
    } 
    /*delete recipe images*/
    public function deleteRecepeImage() { 
        $imageID    = $this->input->post('imageid');
        $image_name = $this->input->post('image_name');
        $recepeID   = $this->input->post('recepeID');
        if(!empty($imageID)){
            if($this->common_model->update('images', array('status'=>2), array('id'=>$imageID))){
                $recipe = $this->common_model->get_row('recipe_blog_image', array('image_name'=>$image_name,'id'=>$recepeID));
                if(!empty($recipe)){
                    $otherImage = $this->common_model->get_row('images', array('meta_id'=>$recepeID,'status'=>1), array('image_name'), array('id','asc'));
                    if(!empty($otherImage->image_name)){
                        $this->common_model->update('recipe_blog_image', array('image_name'=>$otherImage->image_name), array('id'=>$recepeID));
                    }
                }    
                echo 'Image is deleted successfully';   
            } 
        }                   
    }  
    /*delete recipe images*/
    public function deleteParnamentImageImg() { 
        $imageID = $this->input->post('imageid');
        if(!empty($imageID)){
            $rows = $this->common_model->get_row('images', array('id'=>$imageID));
            if(!empty($rows->image_name)){ 
                if(file_exists('assets/uploads/recipeBlogImages/'.$rows->image_name)){ unlink('assets/uploads/recipeBlogImages/'.$rows->image_name);}
                if(file_exists('assets/uploads/recipeBlogImages/thumbnails/'.$rows->image_name)){ unlink('assets/uploads/recipeBlogImages/thumbnails/'.$rows->image_name);}
            }
            if($this->common_model->delete('images', array('id'=>$imageID))){
                echo 'Image is deleted successfully';   
            } 
        }                   
    }  
    /*delete recipe images*/
    public function deletePost() { 
        $array = array('status'=>'false', 'message'=>'');
        $postID = $this->input->post('postID');
        if(!empty($postID)){
            if($this->common_model->update('recipe_blog_image', array('status'=>3), array('id'=>$postID))){
               $array = array('status'=>'true', 'message'=>'Post is deleted successfully');
            } 
        } 
        echo json_encode($array);                  
    }
   /*************************   section **************************************/     
    /* follow request */
    public function follow_request(){  
        $data['rows']     = $this->developer_model->getFollowerUserReq();
        $data['template'] = 'frontend/follow_request';
        $this->load->view('templates/user_template', $data); 
    }
    public function changeStatusReq($requestID='',$status=''){
        $array = array('message'  => '');        
        if($status==1){
            $this->common_model->update('follow_request', array('accepted_status'=>$status), array('id'=>$requestID));
            $array = array('message'  => 'Request is accepted');  
        }else{
            $this->common_model->delete('follow_request', array('id'=>$requestID));
            $array = array('message'  => 'Request is rajected');  
        }
        $reqCount = get_all_count('follow_request', array('receiver_id' => user_id(), 'accepted_status'=>4)) ;
        $followingUserID = user_id();
        if(!empty($followingUserID)){                
            $FollowingCount = get_all_count('follow_request', array('sender_id' => $followingUserID, 'accepted_status'=>1));
            $FollowersCount = get_all_count('follow_request', array('receiver_id' => $followingUserID, 'accepted_status'=>1));
        }else{
            $FollowingCount = get_all_count('follow_request', array('sender_id'=>user_id(), 'accepted_status'=>1));
            $FollowersCount = get_all_count('follow_request', array('receiver_id'=>user_id(), 'accepted_status'=>1));
        }
        if($FollowingCount>1000){
            $FollowingCount = number_format(($FollowingCount/1000), 1).' k';
        }
        if($FollowersCount>1000){
            $FollowersCount = number_format(($FollowersCount/1000), 1).' k';
        }
        $array['status']            = 'true';
        $array['reqCount']          = '('.$reqCount.')';
        $array['FollowingCount']    = $FollowingCount;
        $array['FollowersCount']    = $FollowersCount;
        echo json_encode($array);
    }
   /*************************  Plan Listing *********************************/
    public function copy_workout_plan(){ 
        $plan_id = $this->input->post('plan_id');
        if(!empty($plan_id)){            
            $currentPlan = $this->common_model->get_row('userPlans', array('id'=>$plan_id), array(), array('id','desc')); 
            $alreadyPlaned = $this->common_model->get_row('userPlans', array('planType'=>'2', 'goal_id'=>$currentPlan->goal_id, 'user_id'=>user_id()), array(), array('id','desc')); 
            /*print_r($currentPlan); print_r($alreadyPlaned); exit();*/
            if(empty($alreadyPlaned)){
                $insertedData = array();            
                $insertedData['items']              = !empty($currentPlan->items)?$currentPlan->items:'';
                $insertedData['exercise']           = !empty($currentPlan->exercise)?$currentPlan->exercise:'';
                $insertedData['plan_name']          = !empty($currentPlan->plan_name)?$currentPlan->plan_name:'';
                $insertedData['plan_description']   = !empty($currentPlan->plan_description)?$currentPlan->plan_description:'';
                $insertedData['planType']           = 2;
                $insertedData['goal_id']            = !empty($currentPlan->goal_id)?$currentPlan->goal_id:'';
                $insertedData['user_id']            = user_id();
                $workout_id     = $this->common_model->insert('userPlans', $insertedData);
                $this->common_model->update('userPlans', array('activatePlan'=>0), array('user_id'=>user_id(), 'planType'=>2));
                $this->common_model->update('userPlans', array('activatePlan'=>1), array('id'=>$workout_id));
                $exercises = $this->common_model->get_result('service_plan_works_new', array('workout_id'=>$currentPlan->id));
                if(!empty($exercises)){
                    foreach($exercises as $exRow){
                        $hoursData = array();
                        $hoursData['plan_id']        = $workout_id;
                        $hoursData['exercise_id']    = !empty($exRow->exercise_id)?$exRow->exercise_id:"";
                        $hoursData['minuts']         = !empty($exRow->minuts)?$exRow->minuts:0;
                        $hoursData['sets']           = !empty($exRow->sets)?$exRow->sets:0;
                        $hoursData['reps']           = !empty($exRow->reps)?$exRow->reps:0;
                        $hoursData['week_id']        = !empty($exRow->week_id)?$exRow->week_id:0;
                        $hoursData['week_id']        = !empty($exRow->week_id)?$exRow->week_id:0;
                        $hoursData['work_out_date']  = !empty($exRow->work_out_date)?$exRow->work_out_date:0;
                        $hoursData['user_id']        = user_id();
                        $this->common_model->insert('service_plan_works_new', $hoursData);
                    }
                }
            }
            $array = array('status'  => 'true', 
                        'message' => 'Workout plan is copy successfully'
                      ) ;
        }else{
            $array = array('status'  => 'false', 
                            'message' => 'plan id is required'
                        ) ;
        }
        echo json_encode($array);
    }
    public function user_workout_plan(){  
        $data['currentPlan'] = $this->common_model->get_row('userPlans', array('activatePlan'=>1, 'user_id'=>user_id(), 'planType'=>2, 'goal_id !='=>0), array(), array('id','desc')); 
        $data['template']    = 'frontend/user_workout_plan';
        $this->load->view('templates/user_template', $data); 
    }
     public function workout_plan(){  
        if($this->input->get('plan_id')){
            $data['currentPlan'] = $this->common_model->get_row('userPlans', array('id'=>$this->input->get('plan_id'))); 
        }else{
            $data['currentPlan'] = $this->developer_model->getCurrentPlanDetails(2);  
        }        
        //print_r($data['currentPlan']); exit();
        $data['template']    = 'frontend/workout_plan';
        $this->load->view('templates/user_template', $data); 
    }    
    public function all_workout_plan(){  
        if($this->input->get('user_id')){
            $data['rows']       = $this->common_model->get_result('userPlans', array('user_id'=>$this->input->get('user_id'), 'planType'=>2, 'status'=>1), array(), array('id','desc')); 
        }else{
            $data['rows']       = $this->common_model->get_result('userPlans', array('user_id'=>user_id(), 'planType'=>2, 'status'=>1), array(), array('id','desc'));
        }
        $data['template']   = 'frontend/all_workout_plan';
        $this->load->view('templates/user_template', $data); 
    }
    public function copy_diet_plan(){  
        $plan_id = $this->input->post('plan_id');
        if(!empty($plan_id)){
            $currentPlan = $this->common_model->get_row('userPlans', array('id'=>$plan_id), array(), array('id','desc')); 
            $alreadyPlaned = $this->common_model->get_row('userPlans', array('planType'=>'1', 'goal_id'=>$currentPlan->goal_id, 'user_id'=>user_id()), array(), array('id','desc')); 
            if(empty($alreadyPlaned)){                
                $insertedData                       = array();            
                $insertedData['items']              = !empty($currentPlan->items)?$currentPlan->items:'';
                $insertedData['exercise']           = !empty($currentPlan->exercise)?$currentPlan->exercise:'';
                $insertedData['plan_name']          = !empty($currentPlan->plan_name)?$currentPlan->plan_name:'';
                $insertedData['plan_description']   = !empty($currentPlan->plan_description)?$currentPlan->plan_description:'';
                $insertedData['planType']           = 1;
                $insertedData['goal_id']            = !empty($currentPlan->goal_id)?$currentPlan->goal_id:'';
                $insertedData['user_id']            = user_id();
                $workout_id     = $this->common_model->insert('userPlans', $insertedData);
                $this->common_model->update('userPlans', array('activatePlan'=>0), array('user_id'=>user_id(), 'planType'=>1));
                $this->common_model->update('userPlans', array('activatePlan'=>1), array('id'=>$workout_id));
                $exercises = $this->common_model->get_result('diet_plan_works_new', array('plan_id'=>$plan_id));
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
                $array = array('status'  => 'true', 
                            'message' => 'Diet plan is copy successfully.'
                          ) ;
            }else{
                $array = array('status'  => 'true', 
                            'message' => 'Diet plan is copy successfully.'
                          ) ;
            }
        }else{
            $array = array('status'  => 'false', 
                            'message' => 'Diet id is required.'
                          );
        }
        echo json_encode($array);
    }
    public function test_datas(){
        echo date('Y-m-d H:i:s', 1527100200); 
    }
    public function diet_plan(){ 
        if($this->input->get('plan_id')){
            $data['currentPlan'] = $this->common_model->get_row('userPlans', array('id'=>$this->input->get('plan_id'))); 
        }else{
            $data['currentPlan'] = $this->developer_model->getCurrentPlanDetails(1); 
        }
        $data['template']    = 'frontend/diet_plan';
        $this->load->view('templates/user_template', $data); 
    }
    public function getDietPlanData(){
        if($this->input->post('currentID')){
            $alCh = $this->common_model->get_row('diet_plan_works_new', array('plan_id'=>$this->input->post('currentID'), 'week_id'=>date('W', $this->input->post('startDate'))));

            $lastWeek   = $this->common_model->get_row('diet_plan_works_new', array('plan_id'=>$this->input->post('currentID')), array('week_id'), array('id', 'desc')); 

            //echo $this->db->last_query();exit();
            if(empty($alCh)){
                $dietPlanCs = $this->common_model->get_result('diet_plan_works_new', array('plan_id'=>$this->input->post('currentID') ,'week_id'=>$lastWeek->week_id));
                if(!empty($dietPlanCs)){
                    $ips = 0;
                    foreach($dietPlanCs as $dietPlanC){        
                        $weekDiff = date('W', $this->input->post('startDate')) - $dietPlanC->week_id;
                        if($this->input->post('type')=='next'){
                            $dateTimeZone = $dietPlanC->diet_date + (86400*7*$weekDiff);
                        }else{
                            $dateTimeZone = $dietPlanC->diet_date + (86400*7*$weekDiff);
                        }                        
                        $copyPlan                   = array(); 
                        $copyPlan['plan_id']        = $this->input->post('currentID');
                        $copyPlan['week_id']        = date('W', $this->input->post('startDate'));
                        $copyPlan['item_id']        = $dietPlanC->item_id;
                        $copyPlan['meal']           = $dietPlanC->meal;
                        $copyPlan['user_id']        = $dietPlanC->user_id;
                        $copyPlan['serving']        = $dietPlanC->serving;
                        $copyPlan['diet_date']      = $dateTimeZone;                        
                        $copyPlan['created_date']   = date('Y-m-d H:i:s', $dateTimeZone);
                        //echo '<pre>';print_r($copyPlan);
                       $this->common_model->insert('diet_plan_works_new', $copyPlan);
                        $ips++;
                    }
                }
            }           
        }
        if($this->input->post('startDate')&&$this->input->post('endDate')){
            $data['startTimeZoneN'] = $this->input->post('startDate');
            $data['endTimeZoneN']   = $this->input->post('endDate');
            $data['currentPlan']    = $this->developer_model->getCurrentPlanDetails(1); 
            $this->load->view('frontend/diet_plan_data', $data);  
        }
    }
    public function checkDietPlanFood(){
        $ajaxResponce   = array('status'   => 'false','message' => '');
        $chRow          = $this->common_model->get_row('diet_plan_works_new', array('id'=>$this->input->post('dietPlan_ids')));
        if(!empty($chRow)){
            if($this->common_model->get_row('diet_plan_take_food', array('diet_food_id'=>$this->input->post('dietPlan_ids'), 'day'=>$this->input->post('day_id')))){
                $this->common_model->delete('diet_plan_take_food', array('diet_food_id'=>$this->input->post('dietPlan_ids'), 'day'=>$this->input->post('day_id')));
                $ajaxResponce = array('status'   => 'true',
                                      'Dstatus'   => 'remove',
                                      'message'  => ''
                                  );
            }else{
                $this->common_model->insert('diet_plan_take_food', array('diet_food_id'=>$this->input->post('dietPlan_ids'), 'day'=>$this->input->post('day_id'), 'food_taken_date'=>$this->input->post('dates')));
                $ajaxResponce = array('status'   => 'true',
                                      'Dstatus'   => 'add',
                                      'message'  => ''
                                    );
            }
            $totalServing = $totalProtein = $totalCarb = $totalCalories = 0;
            $rows = $this->developer_model->getDietPlanTakenTotals($this->input->post('dates'), $this->input->post('plan_id'));
            // exit();
            if(!empty($rows)){
                foreach($rows as $rowIDS){
                    $totalServing   = $rowIDS->serving + $totalServing;
                    $totalProtein   = $rowIDS->protein + $totalProtein;
                    $totalCarb      = $rowIDS->carbohydrate + $totalCarb;
                    $totalCalories  = ($rowIDS->cacalories * $rowIDS->serving) + $totalCalories;
                }
            }
            $ajaxResponce['totalServing']   = number_format($totalServing, 2);
            $ajaxResponce['totalProtein']   = $totalProtein.'g';
            $ajaxResponce['totalCarb']      = $totalCarb.'g';
            $ajaxResponce['totalCalories']  = $totalCalories.'cal';
        }else{
            $ajaxResponce = array('status'   => 'false',
                                    'message' => ''
                                  );
        }
        echo json_encode($ajaxResponce);
    }
    public function checkWorkOutEx(){
        $ajaxResponce   = array('status'   => 'false','message' => '');
        $chRow          = $this->common_model->get_row('service_plan_works_new', array('id'=>$this->input->post('workout_exercise_id')));
        if(!empty($chRow)){
            if($this->common_model->get_row('workout_exercise_done', array('workout_exercise_id'=>$this->input->post('workout_exercise_id'), 'day'=>$this->input->post('day_id')))){
                $this->common_model->delete('workout_exercise_done', array('workout_exercise_id'=>$this->input->post('workout_exercise_id'), 'day'=>$this->input->post('day_id')));
                $ajaxResponce = array('status'   => 'true',
                                      'Dstatus'   => 'remove',
                                      'message'  => ''
                                  );
            }else{
                $this->common_model->insert('workout_exercise_done', array('workout_exercise_id'=>$this->input->post('workout_exercise_id'), 'day'=>$this->input->post('day_id'), 'exercise_date'=>$this->input->post('dates')));
                $ajaxResponce = array('status'   => 'true',
                                      'Dstatus'   => 'add',
                                      'message'  => ''
                                    );
            }
            $totalCals = $totalMinuts = $totalSets = $totalRegs = $totalCalories =  $totalWieghts =  0;
            $totalMinutsT = "";
            $rows = $this->developer_model->getWorkOutExTotals($this->input->post('dates'), $this->input->post('plan_id'));
            if(!empty($rows)){
                foreach($rows as $rowIDS){
                    if(!empty($rowIDS->cacalories)&&$rowIDS->measureUnit==1){
                        $calUnit   = ($rowIDS->cacalories*$rowIDS->minuts)/60;                         
                        $totalCals = $calUnit+ $totalCals;   
                        $totalMinuts = $totalMinuts + $rowIDS->minuts;  
                    }
                    if(!empty($rowIDS->sets)){
                        $totalSets = $totalSets + $rowIDS->sets;
                    }else if(!empty($rowIDS->minuts)&&$rowIDS->measureUnit==2){
                        $totalSets = $totalSets + $rowIDS->minuts;
                    }
                    if(!empty($rowIDS->reps)){
                        $totalRegs    = $totalRegs + $rowIDS->reps;
                    }else if(!empty($rowIDS->minuts)&&$rowIDS->measureUnit==3){
                        $totalRegs = $totalRegs + $rowIDS->minuts;
                    }
                    if(!empty($rowIDS->minuts)&&$rowIDS->measureUnit==4){
                        $totalWeight = $totalWeight + $rowIDS->minuts;
                    }
                }
            }
            if($totalMinuts>60){
                $totalMinuts = $totalMinuts;
                $totalMinutsT = floor($totalMinuts/60);
                if($totalMinutsT<10){
                    $totalMinutsT = '0'.$totalMinutsT;
                }
                $totalMinutsT = $totalMinutsT. ' hour ';
                if(($totalMinuts % 60)>0&&($totalMinuts % 60)>9){
                    $totalMinutsT .= ' '.($totalMinuts % 60).' minuts';
                }elseif(($totalMinuts % 60)>0&&($totalMinuts % 60)<9){
                    $totalMinutsT .= ' 0'.($totalMinuts % 60).' minuts';
                }
            }else{
                $totalMinutsT = $totalMinuts. ' minuts ';
            }
            $user                           = user_info();
            if(!empty($user->useMetricsSystem) && $user->useMetricsSystem==1){$wim = ' kg';}else{$wim = ' lbs';}
            $ajaxResponce['totalMinuts']    = $totalMinutsT;
            $ajaxResponce['totalSets']      = $totalSets;
            $ajaxResponce['totalRegs']      = $totalRegs;
            $ajaxResponce['totalWieghts']   = $totalWieghts.$wim;
            $ajaxResponce['totalCalories']  = round($totalCals).' cal';
        }else{
            $ajaxResponce = array('status'   => 'false',
                                    'message' => ''
                                  );
        }
        echo json_encode($ajaxResponce);
    }    
    public function checkss(){
        $rows = $this->developer_model->getWorkOutExTotals('1522972800', 107);
        print_r( $rows);
    }
    public function getWorkOutPlanData(){
        if($this->input->post('currentID')){
            $alCh = $this->common_model->get_row('service_plan_works_new', array('plan_id'=>$this->input->post('currentID'), 'week_id'=>date('W', $this->input->post('startDate'))));
            //echo $this->db->last_query();
            $lastWeek   = $this->common_model->get_row('service_plan_works_new', array('plan_id'=>$this->input->post('currentID')), array('week_id'), array('id', 'desc'));               
            if(empty($alCh)){                
                $dietPlanCs = $this->common_model->get_result('service_plan_works_new', 
                                                            array('plan_id'=>$this->input->post('currentID') ,
                                                                  'week_id'=>$lastWeek->week_id)
                                                                 );
                if(!empty($dietPlanCs)){
                    foreach($dietPlanCs as $dietPlanC){
                        $weekDiff = date('W', $this->input->post('startDate')) - $dietPlanC->week_id;                 
                        if($this->input->post('type')=='next'){
                            $dateTimeZone = $dietPlanC->work_out_date + (86400*7*$weekDiff);
                        }else{
                            $dateTimeZone = $dietPlanC->work_out_date + (86400*7*$weekDiff);
                        } 
                        $copyPlan                   = array(); 
                        $copyPlan['plan_id']        = $this->input->post('currentID');
                        $copyPlan['week_id']        = date('W', $this->input->post('startDate'));
                        $copyPlan['exercise_id']    = $dietPlanC->exercise_id;
                        $copyPlan['user_id']        = $dietPlanC->user_id;
                        $copyPlan['minuts']         = $dietPlanC->minuts;
                        $copyPlan['sets']           = $dietPlanC->sets;
                        $copyPlan['reps']           = $dietPlanC->reps;
                        $copyPlan['created_date']   = date('Y-m-d H:i:s', $dateTimeZone);
                        $copyPlan['work_out_date']  = $dateTimeZone;                   
                        $this->common_model->insert('service_plan_works_new', $copyPlan);
                    }
                }
            }            
        }
        if($this->input->post('startDate')&&$this->input->post('endDate')){
            $data['startTimeZoneN'] = $this->input->post('startDate');
            $data['endTimeZoneN']   = $this->input->post('endDate');
            $data['currentPlan']    = $this->developer_model->getCurrentPlanDetails(2); 
            $this->load->view('frontend/workout_plan_data', $data);  
        }
    }
    public function all_diet_plan(){ 
        if($this->input->get('user_id')){
            $data['rows']       = $this->common_model->get_result('userPlans', array('user_id'=>$this->input->get('user_id'), 'planType'=>1, 'status'=>1), array(), array('activatePlan','desc'));
        }else{
            $data['rows']       = $this->common_model->get_result('userPlans', array('user_id'=>user_id(), 'planType'=>1, 'status'=>1), array(), array('activatePlan','desc'));
        }

        $data['template']   = 'frontend/all_diet_plan';
        $this->load->view('templates/user_template', $data); 
    }
     public function activeDeactivePlan(){ 
        $ajaxResponce = array('status'   => 'false','message' => '');
        $this->form_validation->set_rules('planID', 'plan ID', 'trim|xss_clean|required'); 
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == TRUE){
            $planRow = $this->common_model->get_row('userPlans', array('id'=>$this->input->post('planID')));
            if(!empty($planRow)){
                if(!empty($planRow->activatePlan)&&$planRow->activatePlan==1){
                    $this->common_model->update('userPlans', array('activatePlan'=>0), array('id'=>$this->input->post('planID')));
                    $nextPlanID = $this->common_model->get_row('userPlans', array('user_id'=>user_id(), 'planType'=>$planRow->planType, 'id !='=>$this->input->post('planID'), 'status'=>1), array(), array('id', 'desc'));
                    //print_r($nextPlanID ); 
                    if(!empty($nextPlanID->id)){
                        $this->common_model->update('userPlans', array('activatePlan'=>1, 'created_date'=>date('Y-m-d H:i:s')), array('id'=>$nextPlanID->id));            
                    }
                    $message    = "Plan  is deactivated successfully"; 
                }else{
                    $this->common_model->update('userPlans', array('activatePlan'=>0), array('user_id'=>user_id(), 'planType'=>$planRow->planType));
                    $this->common_model->update('userPlans', array('activatePlan'=>1, 'created_date'=>date('Y-m-d H:i:s')), array('id'=>$this->input->post('planID'))); 
                    $message   = "Plan  is activated successfully"; 
                }
                $data['rows']   = $this->common_model->get_result('userPlans', array('user_id'=>user_id(), 'planType'=>$planRow->planType, 'status'=>1), array(), array('activatePlan','desc'));
                $planData       = $this->load->view('frontend/plan_list', $data, TRUE); 
                $ajaxResponce   = array('status'   => 'true',
                                        'message'  => $message,
                                        'planData' => $planData 
                                       );        
            }else{
                $ajaxResponce   = array('status'   => 'false',
                                        'message'  => 'Plan id is invailed'
                                       );
            }
            echo json_encode($ajaxResponce);
        }
    }

    /************************* set goal **************************************/
    public function goal_set(){          
        $data['template']='frontend/goal_set';
        $this->load->view('templates/user_template', $data); 
    } 
    public function show_user_plan(){          
        $user_id = $this->input->get('user_id');
        $goal_id = $this->input->get('goal_id');;
        if(empty($user_id)){ redirect($_SERVER['HTTP_REFERER']);}   
        if(!is_numeric($user_id)){ redirect($_SERVER['HTTP_REFERER']);} 
        if(empty($goal_id)){ redirect($_SERVER['HTTP_REFERER']);}   
        if(!is_numeric($goal_id)){ redirect($_SERVER['HTTP_REFERER']);}   
        $data['user']         = $this->common_model->get_row('users', array('id'=>$user_id));
        $data['goal']         = $this->common_model->get_row('goal_setter', array('id'=>$goal_id));
        $data['dietPlans']    = $this->common_model->get_row('userPlans', array('goal_id'=>$goal_id, 'planType'=>1));
        $data['workOutPlan']  = $this->common_model->get_row('userPlans', array('goal_id'=>$goal_id, 'planType'=>2));
        $data['template']     = 'frontend/show_user_plan';
        $this->load->view('templates/user_template', $data); 
    }
    public function user_result(){  
        $config = admin_pagination();
        $config['enable_query_strings'] = TRUE;
        if (!empty($_SERVER['QUERY_STRING'])) {
            $config['suffix'] = "?" . $_SERVER['QUERY_STRING'];
        } else {
            $config['suffix'] = '';
        }
        $config['base_url']         = base_url() . "user/user_result/";
        $counts                     = $this->developer_model->get_user_result(0, 0);
        $config['total_rows']       = $counts;
        $config['per_page']         = PER_PAGE;
        $config['uri_segment']      = 3;
        $config['use_page_numbers'] = TRUE;
        $config['first_url']        = $config['base_url'] . $config['suffix'];
        $pageNo = $this->uri->segment(3);        
        $this->pagination->initialize($config);        
        $offSet = 0;
        if ($pageNo) {
            $offSet = $config['per_page'] * ($pageNo - 1);
        }
        $data['pagination'] = $this->pagination->create_links();
        $data['rows']       = $this->developer_model->get_user_result($offSet, 8);
        $data['totalUsers'] = $counts;
        $data['template']   = 'frontend/user_result';
        $this->load->view('templates/user_template', $data); 
    } 
    public function goal_set_res(){ 
        $ajaxResponce = array('status'   => 'false','message' => '');
        $this->form_validation->set_rules('height', 'height', 'trim|xss_clean');  
        $this->form_validation->set_rules('wieght', 'wieght', 'trim|xss_clean');  
        $this->form_validation->set_rules('goal_type', 'weight', 'trim|xss_clean');   
        if($this->input->post('goal_type')==1){            
            $this->form_validation->set_rules('loseWeight', 'weight', 'trim|xss_clean|required');  
            $this->form_validation->set_rules('loseDay', 'day', 'trim|xss_clean|required');  
        }
        $this->form_validation->set_rules('client_c', 'client', 'trim|xss_clean');  
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == TRUE){
            $insertedData = array();            
            if($this->input->post('height')){
                $insertedData['height'] = $this->input->post('height', TRUE);
            }
            if($this->input->post('wieght')){
                $insertedData['wieght'] = $this->input->post('wieght', TRUE);
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
            $insertedData['user_id'] = user_id();
            if($this->input->post('id')){
                $this->common_model->update('goal_setter', $insertedData, array('id'=>$this->input->post('id')));
                $ajaxResponce = array('status'  => 'true',
                                      'message' => 'Goal is updated successfully',
                                      'goal_id' => $this->input->post('id')
                                     );
            }else{
                $goalID = $this->common_model->insert('goal_setter', $insertedData);
                $ajaxResponce = array('status'  => 'true',
                                      'message' => 'Goal is set successfully',
                                      'goal_id' => $goalID
                                     );
            }
        }else{
             $ajaxResponce = array('status'   => 'false',
                                    'message' => validation_errors()
                                  );
        }
        echo json_encode($ajaxResponce);
    }
    public function user_metric_tracker_res(){ 
        $days         = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'); 
        $currentDay   = strtolower(date('l'));          
        $ajaxResponce = array('status'   => 'false','message' => '');
        $this->form_validation->set_rules($currentDay.'_height', 'height', 'trim|xss_clean|required');  
        $this->form_validation->set_rules($currentDay.'_weight', 'wieght', 'trim|xss_clean|required');  
        $this->form_validation->set_rules($currentDay.'_cal_consumed', 'cal consumed ', 'trim|xss_clean|required');   
        $this->form_validation->set_rules($currentDay.'_cal_burned', 'cal burned', 'trim|xss_clean|required');   
        
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == TRUE){
            $insertedData = array();            
            if($this->input->post('height')){
                $insertedData['height'] = $this->input->post('height', TRUE);
            }
            if($this->input->post('wieght')){
                $insertedData['wieght'] = $this->input->post('wieght', TRUE);
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
            $insertedData['user_id'] = user_id();
            if($this->input->post('id')){
                $this->common_model->update('goal_setter', $insertedData, array('id'=>$this->input->post('id')));
                $ajaxResponce = array('status'  => 'true',
                                      'message' => 'Goal is updated successfully',
                                      'goal_id' => $this->input->post('id')
                                     );
            }else{
                $goalID = $this->common_model->insert('goal_setter', $insertedData);
                $ajaxResponce = array('status'  => 'true',
                                      'message' => 'Goal is set successfully',
                                      'goal_id' => $goalID
                                     );
            }
        }else{
             $ajaxResponce = array('status'   => 'false',
                                    'message' => validation_errors()
                                  );
        }
        echo json_encode($ajaxResponce);
    }
    /*************************** create workout Plan ******************/
    public function create_workout_plan(){  
        if($this->input->get('goal_id')==''&&$this->input->get('id')==''){
            redirect(base_url('home/error_404'));
        }
        if($this->input->get('goal_id')&&!is_numeric($this->input->get('goal_id'))){
            redirect(base_url('home/error_404'));
        }
        $data['rows']       = $this->developer_model->getServicePlanItem('2'); 
        $data['exercises']  = $this->developer_model->getServicePlanExercise('2'); 
        if($this->input->get('id')){
            $data['userPlan']   = $this->common_model->get_row('userPlans', array('id'=>$this->input->get('id')));
        }
        $data['template']   = 'frontend/create_workout_plan';
        $this->load->view('templates/user_template', $data); 
    }
     public function edit_workout_plan(){  
        if($this->input->get('goal_id')==''&&$this->input->get('id')==''){
            redirect(base_url('home/error_404'));
        }
        if($this->input->get('goal_id')&&!is_numeric($this->input->get('goal_id'))){
            redirect(base_url('home/error_404'));
        }
        $data['rows']       = $this->developer_model->getServicePlanItem('2'); 
        $data['exercises']  = $this->developer_model->getServicePlanExercise('2'); 
        if($this->input->get('id')){
            $data['userPlan']   = $this->common_model->get_row('userPlans', array('id'=>$this->input->get('id')));
        }
        $data['template']   = 'frontend/edit_workout_plan';
        $this->load->view('templates/user_template', $data); 
    } 
    public function workOutStepFirstValidation(){ 
        $ajaxResponce = array('status'   => 'false','message' => '');
        $this->form_validation->set_rules('workoutitems[]', 'exercise type', 'trim|xss_clean|required');  
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == TRUE){
            $data = array();
            if($this->input->post('workoutitems')){
                $workoutitems       = $this->input->post('workoutitems');
                $workoutitemsStr    = implode(',', $workoutitems);
                $data['exercises']  = $this->developer_model->get_user_exercise($workoutitemsStr);
            }
            $data['user_al_exercise'] = '';
            if($this->input->post('user_exercise')){
                $data['user_al_exercise'] = $this->input->post('user_exercise');
            }
            $secoundStep  = $this->load->view('frontend/create_workout_plan_secound', $data, TRUE); 
            $ajaxResponce = array( 'status'      => 'true',
                                   'message'     => 'Item is added successfully',
                                   'secoundStep' => $secoundStep
                                );
        }else{
             $ajaxResponce = array('status'   => 'false',
                                    'message' => validation_errors()
                                  );
        }
        echo json_encode($ajaxResponce);
    }
    public function workOutStepSecoundValidation(){ 
        //print_r($_POST); exit();
        $ajaxResponce = array('status'   => 'false','message' => '');
        $this->form_validation->set_rules('exercise[]', 'exercise', 'trim|xss_clean|required');  
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == TRUE){
            $exercises          = $this->input->post('exercise');
            $data['exercises']  = $exercises;
            if($this->input->post('plan_id')){
                $data['plan_id']  = $this->input->post('plan_id');
            }
            if($this->input->post('id')){
                $data['planRow']  = $this->common_model->get_row('userPlans', array('id'=>$this->input->post('id')));
            }
            $thirdStep          = $this->load->view('frontend/create_workout_plan_third', $data, TRUE); 
            $ajaxResponce       = array('status'    => 'true',
                                      'message'   => 'Item is added successfully',
                                      'thirdStep' => $thirdStep
                                     );
        }else{
             $ajaxResponce = array('status'   => 'false',
                                    'message' => validation_errors()
                                  );
        }
        echo json_encode($ajaxResponce);
    }
    public function test_plan_data(){
        echo date('Y-m-d H:i:s', 1526236200);
    }
    public function saveWorkOut(){
        $ajaxResponce   = array('status'   => 'false','message' => '');
        $plan_id        = $this->input->post('plan_id');
        if(empty($plan_id)){            
            $this->form_validation->set_rules('plan_name', 'plan name', 'trim|xss_clean|required');  
            $this->form_validation->set_rules('plan_description', 'plan description', 'trim|xss_clean|required');  
        }
        $this->form_validation->set_rules('workoutitems[]', 'item', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('exercise[]', 'item', 'trim|xss_clean|required');  
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == TRUE){
            //print_r($_POST); exit();
            $insertedData = array();            
            $exercise = array();
            if($this->input->post('workoutitems')){
                $items      = $this->input->post('workoutitems');
                $exercise   = $this->input->post('exercise');
                $insertedData['items']              = implode(',', $items);
                $insertedData['exercise']           = implode(',', $exercise);
                if($this->input->post('plan_name')){
                    $insertedData['plan_name']      = $this->input->post('plan_name');
                }
                if($this->input->post('plan_description')){
                    $insertedData['plan_description']      = $this->input->post('plan_description');
                }
                $insertedData['planType']           = 2;
                $insertedData['goal_id']            = $this->input->post('goal_id')?$this->input->post('goal_id'):0;
                $insertedData['created_date']       = date('Y-m-d H:i:s');
            }
            $insertedData['user_id'] = user_id();
            $wrkRow                  = $this->common_model->get_row('userPlans', array('goal_id'=> $this->input->post('goal_id'), 'planType'=>2));
            if($this->input->post('id')){
                $this->common_model->update('userPlans', $insertedData, array('id'=>$this->input->post('id')));
                $ajaxResponce = array('status'  => 'true',
                                      'message' => 'Workout plan is updated successfully'
                                     );
                $plan_id     = $this->input->post('id');
            }else if(!empty($wrkRow)){
                $this->common_model->update('userPlans', $insertedData, array('id'=>$wrkRow->id));
                $ajaxResponce = array('status'  => 'true',
                                      'message' => 'Workout plan is updated successfully'
                                     );
                $plan_id        = $wrkRow->id;
            }else{
                $plan_id        = $this->common_model->insert('userPlans', $insertedData);
                $ajaxResponce   = array('status'   => 'true',
                                        'message' => 'Workout plan is added successfully'
                                     );
                $this->common_model->update('userPlans', array('activatePlan'=>0), array('user_id'=>user_id(), 'planType'=>2));
                $this->common_model->update('userPlans', array('activatePlan'=>1), array('id'=>$plan_id));
            }
            //echo 'insertedData';print_r($insertedData); 
            if(!empty($exercise)){
                foreach($exercise as $exRow){                     
                    $wday           = date('w')-1;
                    $startTimeZone  = strtotime('-'.$wday.' days');
                    $startTimeZone  = strtotime(date('Y-m-d', $startTimeZone));
                    $keys           = explode(',', $this->input->post('row_keys'));
                    if(!empty($keys)){
                        foreach($keys as $key){ 
                            if($this->input->post('minuts_'.$exRow.'_'.$key)||$this->input->post('sets_'.$exRow.'_'.$key)||$this->input->post('reps_'.$exRow.'_'.$key)){  
                                $monday                     = $startTimeZone;
                                $tuesday                    = $startTimeZone+(86400*1);
                                $wednesday                  = $startTimeZone+(86400*2);
                                $thursday                   = $startTimeZone+(86400*3);
                                $friday                     = $startTimeZone+(86400*4);
                                $saturday                   = $startTimeZone+(86400*5);
                                $sunday                     = $startTimeZone+(86400*6);
                                $insertArr                  = array();
                                $insertArr['workout_id']    = $plan_id;
                                $insertArr['exercise_id']   = $exRow;
                                $insertArr['week_id']       = date('W');
                                $insertArr['user_id']       = user_id();
                                $insertArr['minuts']        = ($this->input->post('minuts_'.$exRow.'_'.$key))?$this->input->post('minuts_'.$exRow.'_'.$key):0;
                                $insertArr['sets']        = ($this->input->post('sets_'.$exRow.'_'.$key))?$this->input->post('sets_'.$exRow.'_'.$key):0;
                                $insertArr['reps']        = ($this->input->post('reps_'.$exRow.'_'.$key))?$this->input->post('reps_'.$exRow.'_'.$key):0;
                                $insertArr['monday']    = ($this->input->post($exRow.'_'.$key.'_'.$monday))?1:0;
                                $insertArr['tuesday']   = ($this->input->post($exRow.'_'.$key.'_'.$tuesday))?1:0;
                                $insertArr['wednesday'] = ($this->input->post($exRow.'_'.$key.'_'.$wednesday))?1:0;
                                $insertArr['thursday']  = ($this->input->post($exRow.'_'.$key.'_'.$thursday))?1:0;
                                $insertArr['friday']    = ($this->input->post($exRow.'_'.$key.'_'.$friday))?1:0;
                                $insertArr['saturday']  = ($this->input->post($exRow.'_'.$key.'_'.$saturday))?1:0;
                                $insertArr['sunday']    = ($this->input->post($exRow.'_'.$key.'_'.$sunday))?1:0;
                                $insertArr['status']        = 1;
                                $exeRow                     = $this->common_model->insert('service_plan_works', $insertArr);               
                            }
                            for($ti=0; $ti<7; $ti++){
                                $startCursZone      = $startTimeZone+(86400*$ti);
                                $checkedDate = $this->input->post($exRow.'_'.$key.'_'.$startCursZone);
                                if(!empty($checkedDate)){                            
                                    $hoursData                = array();
                                    $hoursData['plan_id']     = $plan_id;
                                    $hoursData['exercise_id'] = $exRow;     
                                    $hoursData['week_id']     = date('W');       
                                    $hoursData['user_id']     = user_id();       
                                    $hoursData['minuts']      = ($this->input->post('minuts_'.$exRow.'_'.$key))?$this->input->post('minuts_'.$exRow.'_'.$key):0;
                                    $hoursData['sets']      = ($this->input->post('sets_'.$exRow.'_'.$key))?$this->input->post('sets_'.$exRow.'_'.$key):0;
                                    $hoursData['reps']      = ($this->input->post('reps_'.$exRow.'_'.$key))?$this->input->post('reps_'.$exRow.'_'.$key):0;
                                    $hoursData['work_out_date']  = $startCursZone;                    
                                    $hoursData['created_date']   = date('Y-m-d H:i:s'); 
                                    /*echo 'hoursData'; print_r($hoursData); */ 
                                    $whArr   = array(  'exercise_id'    => $exRow, 
                                                        'user_id'       => user_id(), 
                                                        'plan_id'       => $plan_id, 
                                                        'work_out_date' => $startCursZone,
                                                        'created_date'  => date('Y-m-d H:i:s', $startCursZone)
                                                    );            
                                    $exRowC = $this->common_model->get_row('service_plan_works_new', $whArr);
                                    if(!empty($exRowC->id)){
                                        $this->common_model->update('service_plan_works_new', $hoursData, array('id'=>$exRowC->id));
                                    }else{
                                        $this->common_model->insert('service_plan_works_new', $hoursData);
                                    }
                                }
                            }
                        }
                    }
                }
            } 
            $ajaxResponce = array(  'status'   => 'true',
                                    'message'  => 'Workout plan is added successfully',
                                    'goal_id'  => $this->input->post('goal_id')?$this->input->post('goal_id'):0,
                                    'id'       => $this->input->post('id')?$this->input->post('id'):0
                                );
        }else{
             $ajaxResponce = array('status'   => 'false',
                                    'message' => validation_errors()
                                  );
        }
        echo json_encode($ajaxResponce);
    }
    public function saveWorkOutSecond(){
        //print_r($_POST); exit();
        $ajaxResponce = array('status'   => 'false','message' => ''); 
        $this->form_validation->set_rules('workoutitems[]', 'item', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('exercise[]', 'item', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('workout_id', 'workout id', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('day', 'day', 'trim|xss_clean|required');  
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == TRUE){
            $insertedData = array();            
            if($this->input->post('workoutitems')){
                $items      = $this->input->post('workoutitems');
            }
            if($this->input->post('exercise')){
                $exercises   = $this->input->post('exercise');
            }
            $row = $this->common_model->get_row('userPlans', array('id'=>$this->input->post('workout_id')));
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
                $this->common_model->update('userPlans', $insertedData, array('id'=>$this->input->post('workout_id')));   
                $data['exercises']  = $this->input->post('exercise');
                $data['workout_id'] = $this->input->post('workout_id');
                $data['day']        = $this->input->post('day');
                $data['date']       = $this->input->post('date');
                $rowHtm             = $this->load->view('frontend/addWorkPlanEx', $data, TRUE); 
                $ajaxResponce = array(  'status'   => 'true',
                                        'message'  => 'Exercise is added successfully',
                                        'rowHtm'   => $rowHtm
                                    );
            }else{
                $ajaxResponce = array(  'status'   => 'true',
                                        'message'  => 'Workout ID is not found, try again'
                                    );
            }            
        }else{
             $ajaxResponce = array('status'   => 'false',
                                    'message' => validation_errors()
                                  );
        }
        echo json_encode($ajaxResponce);
    }
    public function addNewWorkOutEx(){
        //print_r($_POST); exit();
        $ajaxResponce = array('status'   => 'false','message' => ''); 
        $this->form_validation->set_rules('exercises', 'item', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('workout_id', 'workout id', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('day', 'day', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('date', 'date', 'trim|xss_clean|required');  
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == TRUE){
            $insertedData = array();
            if($this->input->post('exercises')){
                $exercises   = explode(',', $this->input->post('exercises'));
            }
            $row = $this->common_model->get_row('userPlans', array('id'=>$this->input->post('workout_id')));
            if(!empty($row)){
                if(!empty($exercises)){
                    foreach($exercises as $exRow){
                        $hoursData                = array();
                        $hoursData['plan_id']     = $this->input->post('workout_id');
                        $hoursData['exercise_id'] = $exRow;     
                        $hoursData['week_id']     = date('W', $this->input->post('date'));       
                        $hoursData['user_id']     = user_id();       
                        $hoursData['work_out_date']  = $this->input->post('date'); 
                        if($this->input->post('minuts_'.$exRow)){
                            $hoursData['minuts']      = $this->input->post('minuts_'.$exRow);
                        }
                        if($this->input->post('sets_'.$exRow)){
                            $hoursData['sets']      = $this->input->post('sets_'.$exRow);
                        }
                        if($this->input->post('reps_'.$exRow)){
                            $hoursData['reps']      = $this->input->post('reps_'.$exRow);
                        }
                        $hoursData['created_date']   = date('Y-m-d H:i:s');      
                        $whArr                       = array(  'exercise_id'    => $exRow, 
                                                                'user_id'       => user_id(), 
                                                                'plan_id'       => $this->input->post('workout_id'), 
                                                                'work_out_date' => $this->input->post('date'),
                                                                'created_date'  => date('Y-m-d H:i:s', $this->input->post('date'))
                                                            );            
                        $exRowC      = $this->common_model->get_row('service_plan_works_new', $whArr);
                        if(!empty($exRowC->id)){
                            $this->common_model->update('service_plan_works_new', $hoursData, array('id'=>$exRowC->id));
                        }else{
                            $this->common_model->insert('service_plan_works_new', $hoursData);
                        }
                        $ajaxResponce = array(  'status'   => 'true',
                                        'message'  => 'Exercise is added successfully'
                                    );
                    }
                } 
                $ajaxResponce = array(  'status'   => 'true',
                                        'message'  => 'Exercise is added successfully'
                                    );
            }else{
                $ajaxResponce = array(  'status'   => 'true',
                                        'message'  => 'Workout ID is not found, try again'
                                    );
            }            
        }else{
             $ajaxResponce = array('status'   => 'false',
                                    'message' => validation_errors()
                                  );
        }
        echo json_encode($ajaxResponce);
    }    
    public function showExerciseDetails($id=''){  
        $data['row']       = $this->common_model->get_row('service_plan_user_exercise', array('id'=>$id)); 
        $this->load->view('frontend/workoutExerciseDetails', $data); 
    }
    public function planActivate(){  
        $ajaxResponce = array('status'   => 'false','message' => '');
        if($this->input->post('planID')){           
            $this->common_model->update('userPlans', array('user_id'=>user_id()), array('activatePlan'=>0));
            $this->common_model->update('userPlans', array('id'=>$this->input->post('planID')), array('activatePlan'=>0));
            $ajaxResponce = array('status'   => 'true','message' => 'Plan is activated successfully.');
        }
        echo $ajaxResponce;
    }
    public function editWorkPlanEx(){ 
        $ajaxResponce = array('status'   => 'false','message' => '');
        $this->form_validation->set_rules('exerciseID', 'exercise ID', 'trim|xss_clean|required');  
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == TRUE){
            $exerciseID         = $this->input->post('exerciseID');     
            $data['exercisRow'] = $this->developer_model->getWorkOutPlanDayDetails($exerciseID);
            $rowHtm             = $this->load->view('frontend/editWorkPlanEx', $data, TRUE); 
            $ajaxResponce       = array('status'  => 'true',
                                    'message' => 'Item  is updated from diet successfully',
                                    'rowHtm'  => $rowHtm
                                   );
        }else{
             $ajaxResponce = array('status'   => 'false',
                                    'message' => validation_errors()
                                  );
        }
        echo json_encode($ajaxResponce);
    }
    public function editDietPlanFood(){ 
        $ajaxResponce = array('status'   => 'false','message' => '');
        $this->form_validation->set_rules('foodID', 'food ID', 'trim|xss_clean|required');  
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == TRUE){
            $foodID             = $this->input->post('foodID');     
            $data['row']        = $this->developer_model->getDietPlanDayDetails($foodID);
            $data['foodID']     = $this->input->post('foodID');
            $rowHtm             = $this->load->view('frontend/editDietPlanFood', $data, TRUE); 
            $ajaxResponce       = array('status'  => 'true',
                                    'message' => 'Food  is updated from diet successfully',
                                    'rowHtm'  => $rowHtm
                                   );
        }else{
             $ajaxResponce = array('status'   => 'false',
                                    'message' => validation_errors()
                                  );
        }
        echo json_encode($ajaxResponce);
    }
    public function updateDietPlanEx(){ 
        $ajaxResponce = array('status'   => 'false','message' => '');
        $this->form_validation->set_rules('foodID', 'food ID', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('mealType', 'meal type', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('servingType', 'serving type', 'trim|xss_clean|required');  
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == TRUE){     
            $this->common_model->update('diet_plan_works_new', array('meal'=>$this->input->post('mealType'), 'serving'=>$this->input->post('servingType')), array('id'=>$this->input->post('foodID')));
            $ajaxResponce       = array('status'  => 'true',
                                        'message' => 'Food is updated successfully'
                                        );
        }else{
             $ajaxResponce = array('status'   => 'false',
                                    'message' => validation_errors()
                                  );
        }
        echo json_encode($ajaxResponce);
    }
    public function updateWorkPlanEx(){ 
        //print_r($_POST); exit();
        $ajaxResponce = array('status'   => 'false','message' => '');
        $this->form_validation->set_rules('exerciseID', 'exercise ID', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('minuts', 'measure unit', 'trim|xss_clean');  
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == TRUE){
            $exerciseID         = $this->input->post('exerciseID');
             $arra = array();    
            if($this->input->post('minuts')){
                $arra['minuts'] = $this->input->post('minuts');    
            }
            if($this->input->post('sets')){
                $arra['sets'] = $this->input->post('sets');    
            }
            if($this->input->post('reps')){
                $arra['reps'] = $this->input->post('reps');      
            }
            $this->common_model->update('service_plan_works_new', $arra, array('id'=>$exerciseID));
            $ajaxResponce       = array('status'  => 'true',
                                        'message' => 'Exercise is updated successfully'
                                        );
        }else{
             $ajaxResponce = array('status'   => 'false',
                                    'message' => validation_errors()
                                  );
        }
        echo json_encode($ajaxResponce);
    }
    /*************************** Create Diet Plan ******************/
    public function create_diet_plan(){  
        if($this->input->get('goal_id')==''&&$this->input->get('id')==''){
            redirect(base_url('user/goal_set'));
        }
        if($this->input->get('goal_id')&&!is_numeric($this->input->get('goal_id'))){
            redirect(base_url('user/goal_set'));
        }
        if($this->input->get('id')){
            $data['userPlan']   = $this->common_model->get_row('userPlans', array('id'=>$this->input->get('id')));
        }
        $data['rows']       = $this->developer_model->getServicePlanItem('1'); 
        $data['template']   = 'frontend/create_diet_plan';
        $this->load->view('templates/user_template', $data); 
    }
    public function deleteExecis(){  
        if($this->input->get('exerciseID')){
            $this->common_model->update('service_plan_works', array('status'=>3), array('id'=>$this->input->get('exerciseID')));
        }        
    }
    public function copyExecis(){
        $response   = array('status'=>'false', 'rowData'=>'', 'exeID'=>'');  
        if($this->input->post('exerciseID')){
            $exeRow = $this->common_model->get_row('service_plan_works', array('id'=>$this->input->post('exerciseID')));
            if(!empty($exeRow)){
                $insertArr                  = array();
                $insertArr['workout_id']    = $exeRow->workout_id;
                $insertArr['exercise_id']   = $exeRow->exercise_id;
                $insertArr['week_id']       = $exeRow->week_id;
                $insertArr['user_id']       = $exeRow->user_id;
                $insertArr['minuts']        = $exeRow->minuts;
                $insertArr['monday']        = $exeRow->monday;
                $insertArr['tuesday']       = $exeRow->tuesday;
                $insertArr['wednesday']     = $exeRow->wednesday;
                $insertArr['thursday']      = $exeRow->thursday;
                $insertArr['friday']        = $exeRow->friday;
                $insertArr['saturday']      = $exeRow->saturday;
                $insertArr['sunday']        = $exeRow->sunday;
                $insertArr['status']        = 2;
                $exeRow                     = $this->common_model->insert('service_plan_works', $insertArr);
                $data['exercises']          = $this->common_model->get_result('service_plan_works', array('id'=>$exeRow));
                $row        = $this->load->view('frontend/exercise_row', $data, TRUE); 
                $response   = array('status'=>'true', 'rowData'=>$row ,'exeID'=>$exeRow);
            }
        } 
        echo json_encode($response);       
    }
    public function addcopyExecis(){
        $response   = array('status'=>'false', 'rowData'=>'', 'exeID'=>'');  
        if($this->input->post('exerciseID')){
            $exeRow = $this->common_model->get_row('service_plan_works', array('id'=>$this->input->post('exerciseID')));
            if(!empty($exeRow)){
                $keyID              = time().rand(1111, 9999);
                $data['exercises']  = array($this->input->post('exerciseID'));
                $data['keyID']      = $keyID;
                $row                = $this->load->view('frontend/addcopyExecis', $data, TRUE); 
                $response           = array('status'=>'true', 'rowData'=>$row ,'keyID'=>$keyID);
            }
        } 
        echo json_encode($response);        
    }
    public function addDeleteExecis(){  
        $keys           = explode(',', $this->input->post('row_keys'));        
        $delkey         = array($this->input->post('keyID'));  
        $subTrackKey    = array_diff($keys, $delkey);    
        echo !empty($subTrackKey)?implode(',', $subTrackKey):'';  
    }    
    public function edit_diet_plan(){  
        if($this->input->get('goal_id')==''&&$this->input->get('id')==''){
            redirect(base_url('user/goal_set'));
        }
        if($this->input->get('goal_id')&&!is_numeric($this->input->get('goal_id'))){
            redirect(base_url('user/goal_set'));
        }
        if($this->input->get('id')){
            $data['rows']   = $this->developer_model->get_diat_plan_edit_data(NULL, 1);
        }
        if($this->input->get('id')){
            $data['userPlan']   = $this->common_model->get_row('userPlans', array('id'=>$this->input->get('id')));
        }
        //echo '<pre>'; print_r($data['rows']); exit();
        $data['template']   = 'frontend/edit_diet_plan';
        $this->load->view('templates/user_template', $data); 
    }

    public function deleteFood(){  
        if($this->input->get('exerciseID')){
            $this->common_model->update('service_plan_works', array('status'=>3), array('id'=>$this->input->get('exerciseID')));
        }        
    }
    public function copyFood(){
        $response   = array('status'=>'false', 'rowData'=>'', 'exeID'=>'');  
        if($this->input->post('exerciseID')){
            $exeRow = $this->common_model->get_row('diet_plan_works', array('id'=>$this->input->post('exerciseID')));
            if(!empty($exeRow)){
                $insertArr                  = array();
                $insertArr['category_id']   = $exeRow->category_id;
                $insertArr['item_id']       = $exeRow->item_id;
                $insertArr['week_id']       = $exeRow->week_id;
                $insertArr['meal']          = $exeRow->meal;
                $insertArr['serving']       = $exeRow->serving;
                $insertArr['monday']        = $exeRow->monday;
                $insertArr['tuesday']       = $exeRow->tuesday;
                $insertArr['wednesday']     = $exeRow->wednesday;
                $insertArr['thursday']      = $exeRow->thursday;
                $insertArr['friday']        = $exeRow->friday;
                $insertArr['saturday']      = $exeRow->saturday;
                $insertArr['sunday']        = $exeRow->sunday;
                $insertArr['status']        = 2;
                $exeRow                     = $this->common_model->insert('diet_plan_works', $insertArr);
                $data['rows']               = $this->developer_model->get_diat_plan_edit_data($exeRow, 2);
                $row        = $this->load->view('frontend/food_row', $data, TRUE); 
                $response   = array('status'=>'true', 'rowData'=>$row ,'exeID'=>$exeRow);
            }
        } 
        echo json_encode($response);       
    }
    public function addcopyFood(){
        $response   = array('status'=>'false', 'rowData'=>'', 'exeID'=>'');  
        if($this->input->post('exerciseID')){
            $foods          = explode('_', $this->input->post('exerciseID'));
            $foodID         = $foods[0];
            $keyID          = time().rand(1111, 9999);
            $data['rows']   = $this->developer_model->get_plan_list_items($foodID);
           /* echo ' foodID = '.$foodID;
            echo '<pre>';print_r($data['rows']); exit();*/
            $data['keyID']  = $keyID;
            $row            = $this->load->view('frontend/addcopyFood', $data, TRUE); 
            $response       = array('status'=>'true', 'rowData'=>$row ,'keyID'=>$keyID);
        }
        echo json_encode($response);        
    }
    public function addDeleteFood(){  
        $keys           = explode(',', $this->input->post('row_keys'));        
        $delkey         = array($this->input->post('keyID'));  
        $subTrackKey    = array_diff($keys, $delkey);    
        echo !empty($subTrackKey)?implode(',', $subTrackKey):'';  
    }
    public function dietPlanStepFirstValidation(){ 
        $ajaxResponce = array('status'   => 'false','message' => '');
        $this->form_validation->set_rules('dietitems[]', 'food or drink', 'trim|xss_clean|required');  
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == TRUE){
            $itemIDS            = $this->input->post('dietitems');       
            $data['dietPlanEx'] = $this->input->post('dietPlanEx');       
            $data['rows']       = $this->developer_model->get_plan_list_type(implode(',', $itemIDS));
            $itemData           = $this->load->view('frontend/create_diet_plan_secound', $data, TRUE); 
            $ajaxResponce       = array('status'  => 'true',
                                        'message' => 'Item is added successfully',
                                        'itemData'=> $itemData
                                        );
        }else{
             $ajaxResponce = array('status'   => 'false',
                                    'message' => validation_errors()
                                  );
        }
        echo json_encode($ajaxResponce);
    }
    public function skip_diet_plan(){
        if($this->input->get('goal_id')){
            if($this->common_model->get_row('userPlans', array('goal_id'=>$this->input->get('goal_id'),'planType'=>2))){
                redirect(base_url().'user/workout_plan');
            }else{
                $this->session->set_flashdata('msg_error','You must create at least a workout plan or diet plan');
                redirect(base_url().'user/create_workout_plan?goal_id='.$this->input->get('goal_id'));
            }
        }
    }
    public function dietPlanStepSecoundValidation(){ 
        $ajaxResponce = array('status'   => 'false','message' => '');
        $this->form_validation->set_rules('item[]', 'food or drink', 'trim|xss_clean|required');  
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == TRUE){
            $itemIDS        = $this->input->post('item');    
            if($this->input->post('id')){
                $data['planRow']  = $this->common_model->get_row('userPlans', array('id'=>$this->input->post('id')));
            } 
            if($this->input->post('plan_id')){
                $data['plan_id']  = $this->input->post('plan_id');
            }  
            $data['rows']   = $this->developer_model->get_plan_list_items(implode(',', $itemIDS));
            $itemData       = $this->load->view('frontend/create_diet_plan_third', $data, TRUE); 
            $ajaxResponce   = array('status'  => 'true',
                                    'message' => 'Item is added successfully',
                                    'itemData'=> $itemData
                                   );
        }else{
             $ajaxResponce = array('status'   => 'false',
                                    'message' => validation_errors()
                                  );
        }
        echo json_encode($ajaxResponce);
    }
    public function saveDietPlan(){
        $ajaxResponce = array('status'   => 'false','message' => '');        
        $plan_id      = $this->input->post('plan_id');
        if(empty($plan_id)){            
            $this->form_validation->set_rules('plan_name', 'plan name', 'trim|xss_clean|required');  
            $this->form_validation->set_rules('plan_description', 'plan description', 'trim|xss_clean|required');  
        }
        $this->form_validation->set_rules('dietitems[]', 'item', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('item[]', 'item', 'trim|xss_clean|required');  
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == TRUE){
            $insertedData = array();            
            if($this->input->post('dietitems')){
                $dietitems                  = $this->input->post('dietitems');
                $items                      = $this->input->post('item');
                if($this->input->post('plan_name')){
                    $insertedData['plan_name']  = $this->input->post('plan_name');
                }
                if($this->input->post('plan_description')){
                    $insertedData['plan_description']  = $this->input->post('plan_description');
                }
                $insertedData['items']      = implode(',', $dietitems);
                $insertedData['exercise']   = implode(',', $items);
                $insertedData['planType']   = 1;
                $insertedData['created_date'] = date('Y-m-d H:i:s');
                $insertedData['goal_id']      = $this->input->post('goal_id')?$this->input->post('goal_id'):0;
            }
            $insertedData['user_id'] = user_id();
            $wrkRow                  = $this->common_model->get_row('userPlans', array('goal_id'=> $this->input->post('goal_id'), 'planType'=>1));            
            if($this->input->post('id')){
                $this->common_model->update('userPlans', $insertedData, array('id'=>$this->input->post('id')));
                $ajaxResponce = array('status'  => 'true',
                                      'message' => 'Diet plan is updated successfully'
                                     );
                $plan_id     = $this->input->post('id');
            }else if(!empty($wrkRow)){
                $this->common_model->update('userPlans', $insertedData, array('id'=>$wrkRow->id));
                $ajaxResponce = array('status'  => 'true',
                                      'message' => 'Diet plan is updated successfully'
                                     );
                $plan_id     = $wrkRow->id;
            }else{
                $plan_id     = $this->common_model->insert('userPlans', $insertedData);
                $this->common_model->update('userPlans', array('activatePlan'=>0), array('user_id'=>user_id(), 'planType'=>1));
                $this->common_model->update('userPlans', array('activatePlan'=>1), array('id'=>$plan_id)); 
                $ajaxResponce   = array('status'   => 'true',
                                        'message' => 'Diet plan is added successfully'
                                     );
            }
            //print_r($_POST);
            //exit();
            if(!empty($items)){
                foreach($items as $exRow){
                    $wday           = date('w')-1;
                    $startTimeZone  = strtotime('-'.$wday.' days');
                    $startTimeZone  = strtotime(date('Y-m-d', $startTimeZone));
                    $keys           = explode(',', $this->input->post('row_keys'));
                    if(!empty($keys)){
                        foreach($keys as $key){
                            if($this->input->post('mealType'.$exRow.'_'.$key)){
                                $monday                     = $startTimeZone;
                                $tuesday                    = $startTimeZone+(86400*1);
                                $wednesday                  = $startTimeZone+(86400*2);
                                $thursday                   = $startTimeZone+(86400*3);
                                $friday                     = $startTimeZone+(86400*4);
                                $saturday                   = $startTimeZone+(86400*5);
                                $sunday                     = $startTimeZone+(86400*6);
                                $insertArr                  = array();
                                $insertArr['category_id']   = $plan_id;
                                $insertArr['item_id']       = $exRow;
                                $insertArr['week_id']       = date('W');
                                $insertArr['meal']          = ($this->input->post('mealType'.$exRow.'_'.$key))?$this->input->post('mealType'.$exRow.'_'.$key):0;
                                $insertArr['serving']       = ($this->input->post('servingType'.$exRow.'_'.$key))?$this->input->post('servingType'.$exRow.'_'.$key):0;
                                $insertArr['monday']   =($this->input->post($exRow.'_'.$key.'_'.$monday))?1:0;
                                $insertArr['tuesday']  =($this->input->post($exRow.'_'.$key.'_'.$tuesday))?1:0;
                                $insertArr['wednesday']=($this->input->post($exRow.'_'.$key.'_'.$wednesday))?1:0;
                                $insertArr['thursday'] =($this->input->post($exRow.'_'.$key.'_'.$thursday))?1:0;
                                $insertArr['friday']   =($this->input->post($exRow.'_'.$key.'_'.$friday))?1:0;
                                $insertArr['saturday'] =($this->input->post($exRow.'_'.$key.'_'.$saturday))?1:0;
                                $insertArr['sunday']   =($this->input->post($exRow.'_'.$key.'_'.$sunday))?1:0;
                                $insertArr['status']   =1;
                                $exeRow               =$this->common_model->insert('diet_plan_works',$insertArr);
                                //print_r($insertArr);
                            }
                            for($ti=0;$ti<7;$ti++){
                                $startCursZone      = $startTimeZone+(86400*$ti);                      
                                $checkedDate = $this->input->post($exRow.'_'.$startCursZone);
                                if(!empty($checkedDate)){                            
                                    $hoursData                = array();
                                    $hoursData['plan_id']     = $plan_id;
                                    $hoursData['item_id']     = $exRow;     
                                    $hoursData['week_id']     = date('W');       
                                    $hoursData['user_id']     = user_id();       
                                    $hoursData['meal']        = ($this->input->post('mealType'.$exRow))?$this->input->post('mealType'.$exRow):'';
                                    $hoursData['serving']     = ($this->input->post('servingType'.$exRow))?$this->input->post('servingType'.$exRow):'';
                                    $hoursData['diet_date']      = $startCursZone;                    
                                    $hoursData['created_date']   = date('Y-m-d H:i:s');      
                                    $whArr                       = array(  'item_id'=>$exRow, 
                                                                            'user_id'=>user_id(), 
                                                                            'plan_id'=>$plan_id, 
                                                                            'diet_date'=>$startCursZone,
                                                                            'created_date'  => date('Y-m-d H:i:s', $startCursZone)
                                                                        );            
                                    $exRowC      = $this->common_model->get_row('diet_plan_works_new', $whArr);
                                    //print_r($hoursData);
                                    if(!empty($exRowC->id)){
                                        $this->common_model->update('diet_plan_works_new', $hoursData, array('id'=>$exRowC->id));
                                    }else{
                                        $this->common_model->insert('diet_plan_works_new', $hoursData);
                                    }
                                }
                            }
                        }
                    }
                }
            } 
            $ajaxResponce = array(  'status'   => 'true',
                                    'message' => 'Diet is saved successfully'
                                );
        }else{
             $ajaxResponce = array('status'   => 'false',
                                    'message' => validation_errors()
                                  );
        }
        echo json_encode($ajaxResponce);
    }
    public function saveDietSecond(){
        //print_r($_POST); exit();
        $ajaxResponce = array('status'   => 'false','message' => ''); 
        $this->form_validation->set_rules('dietitems[]', 'item', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('item[]', 'item', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('diet_id', 'diet id', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('day', 'day', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('date', 'date', 'trim|xss_clean|required');  
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == TRUE){
            $insertedData = array();            
            if($this->input->post('dietitems')){
                $dietitems      = $this->input->post('dietitems');
            }
            if($this->input->post('item')){
                $items   = $this->input->post('item');
            }
            $row = $this->common_model->get_row('userPlans', array('id'=>$this->input->post('diet_id')));
            if(!empty($row)){
                $itemsAll    = explode(',', $row->items);
                $exerciseAll = explode(',', $row->exercise);
                foreach($dietitems as $dietitem){
                    array_push($itemsAll, $dietitem);
                }
                foreach($items as $item){
                    array_push($exerciseAll, $item);
                }
                array_unique($itemsAll);
                array_unique($exerciseAll);                
                $insertedData['items']      = implode(',', $itemsAll);
                $insertedData['exercise']   = implode(',', $exerciseAll);
                $this->common_model->update('userPlans', $insertedData, array('id'=>$this->input->post('diet_id')));   
                $itemIDS            = $this->input->post('item');
                $data['diet_id']    = $this->input->post('diet_id');
                $data['day']        = $this->input->post('day');
                $data['date']        = $this->input->post('date');
                $data['rows']       = $this->developer_model->get_plan_list_items(implode(',', $itemIDS));
                $rowHtm             = $this->load->view('frontend/addDietPlanFood', $data, TRUE); 
                $ajaxResponce = array(  'status'   => 'true',
                                        'message'  => 'Food is added successfully',
                                        'rowHtm'   => $rowHtm
                                    );
            }else{
                $ajaxResponce = array(  'status'   => 'true',
                                        'message'  => 'Food ID is not found, try again'
                                    );
            }            
        }else{
             $ajaxResponce = array('status'   => 'false',
                                    'message' => validation_errors()
                                  );
        }
        echo json_encode($ajaxResponce);
    }
    public function addNewDietFood(){
        //print_r($_POST); exit();
        $ajaxResponce = array('status'   => 'false','message' => '');  
        $this->form_validation->set_rules('item[]', 'item', 'trim|xss_clean|required');   
        $this->form_validation->set_rules('diet_id', 'diet id', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('day', 'day', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('date', 'date', 'trim|xss_clean|required');  
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == TRUE){
            $insertedData = array();
            if($this->input->post('item')){
                $items   = explode(',', $this->input->post('item'));
            }
            $row = $this->common_model->get_row('userPlans', array('id'=>$this->input->post('diet_id')));
            if(!empty($row)){
                if(!empty($items)){
                    foreach($items as $exRow){
                        $hoursData = array();
                        $hoursData['plan_id']     = $this->input->post('diet_id');
                        $hoursData['item_id']     = $exRow;                   
                        $hoursData['meal']        = ($this->input->post('mealType'.$exRow))?$this->input->post('mealType'.$exRow):0;
                        $hoursData['serving']     = ($this->input->post('servingType'.$exRow))?$this->input->post('servingType'.$exRow):0;                       
                        $hoursData['week_id']     = date('W', $this->input->post('date'));
                        $hoursData['diet_date']   = $this->input->post('date');
                        $hoursData['user_id']     = user_id();
                        $hoursData['created_date']= date('Y-m-d H:i:s', $this->input->post('date'));
                        //print_r($hoursData); 
                        $this->common_model->insert('diet_plan_works_new', $hoursData);
                        $ajaxResponce = array(  'status'   => 'true',
                                                'message'  => 'Food is added successfully'
                                            );
                    }
                } 
                $ajaxResponce = array(  'status'   => 'true',
                                        'message'  => 'Food is added successfully'
                                    );
            }else{
                $ajaxResponce = array(  'status'   => 'true',
                                        'message'  => 'Diet ID is not found, try again'
                                    );
            }            
        }else{
             $ajaxResponce = array('status'   => 'false',
                                    'message' => validation_errors()
                                  );
        }
        echo json_encode($ajaxResponce);
    }
    public function dietPlanItemDetails($id){  
        $data['row']       = $this->common_model->get_row('service_plan_diet_items', array('id'=>$id)); 
        $this->load->view('frontend/create_diet_plan_item_details', $data); 
    } 
    public function deleteUserPlan(){ 
        $ajaxResponce = array('status'   => 'false','message' => '');
        $this->form_validation->set_rules('planID', 'diet plan ID', 'trim|xss_clean|required'); 
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == TRUE){
            $planID      = $this->input->post('planID');  
            $planDetails = $this->common_model->get_row('userPlans', array('id'=>$planID));
            $newPlan     = 'no';
            if($planDetails->activatePlan==1){
                $planDetailsN = $this->common_model->get_result('userPlans', array('planType'=>$planDetails->planType, 'user_id'=>user_id(), 'status'=>1), array(), array('id', 'desc'));
                if(!empty($planDetailsN[1]->id)){
                    $this->common_model->update('userPlans', array('activatePlan'=>1), array('id'=>$planDetailsN[1]->id));
                    $newPlan = 'yes';
                }
            } 
            $this->common_model->update('userPlans', array('status'=>3), array('id'=>$planID));
            echo $newPlan;
        }
    }     
    /***************************  Metric Tracker ******************/
    public function metricTracker(){  
        $data['template']='frontend/metrics_tracker';
        $this->load->view('templates/user_template', $data); 
    } 
    public function getMatrixData(){
        if($this->input->post('startDate')&&$this->input->post('endDate')){
            $data['startTimeZoneN'] = $this->input->post('startDate');
            $data['endTimeZoneN']   = $this->input->post('endDate');
            $this->load->view('frontend/metrix_tracker_data', $data);  
        }
    }    
    public function getStartAndEndDate($week, $year){
        $time = strtotime("1 January $year", time());
        $day = date('w', $time);
        $time += ((7*$week)+1-$day)*24*3600;
        $return[0] = date('Y-m-d', $time);
        $time += 6*24*3600;
        $return[1] = date('Y-m-d', $time);
        return $return;
    }
    public function saveMatrix(){ 
        // print_r($_POST); //exit();
        $currentDay   = $this->input->post('currentDay');
        $ajaxResponce = array('status'   => 'false','message' => '');
        $this->form_validation->set_rules('currentDate', 'date', 'trim|xss_clean|required');  
        $this->form_validation->set_rules($currentDay.'_height', 'height', 'trim|xss_clean|required');  
        $this->form_validation->set_rules($currentDay.'_weight', 'weight', 'trim|xss_clean|required|numeric');  
        $this->form_validation->set_rules($currentDay.'_cal_consumed', 'cal consumed', 'trim|xss_clean');  
        $this->form_validation->set_rules($currentDay.'_chest', 'chest', 'trim|xss_clean');  
        $this->form_validation->set_rules($currentDay.'_waist', 'waist', 'trim|xss_clean');  
        $this->form_validation->set_rules($currentDay.'_arms', 'arms', 'trim|xss_clean');  
        $this->form_validation->set_rules($currentDay.'_forearms', 'forearms', 'trim|xss_clean');  
        $this->form_validation->set_rules($currentDay.'_legs', 'legs', 'trim|xss_clean');  
        $this->form_validation->set_rules($currentDay.'_calves', 'calves', 'trim|xss_clean');  
        $this->form_validation->set_rules($currentDay.'_hips', 'hips', 'trim|xss_clean');  
        $this->form_validation->set_rules($currentDay.'_bicepsBF', 'biceps bf', 'trim|xss_clean');  
        $this->form_validation->set_rules($currentDay.'_absBF', 'abs bf', 'trim|xss_clean');  
        $this->form_validation->set_rules($currentDay.'_thighsBF', 'thighs bf', 'trim|xss_clean');  
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == TRUE){
            $currentDate                            = $this->input->post('currentDate'); 
            $insertedData                           = array();
            $insertedData['user_id']                = user_id();
            if($this->input->post('metricStatus')){                
                $insertedData['matrix_status']  = $this->input->post('metricStatus'); 
            }else{
                $insertedData['matrix_status']  = 2; 
            }    
            if($this->input->post('currentDate'))   
                $insertedData['matrixDate'] = $this->input->post('currentDate');
            if($this->input->post($currentDay.'_height'))        
                $insertedData['height']     = $this->input->post($currentDay.'_height');            
            if($this->input->post($currentDay.'_weight'))        
                $insertedData['weight']     = $this->input->post($currentDay.'_weight');
            if($this->input->post($currentDay.'_cal_consumed'))   
                $insertedData['calConsumed']= $this->input->post($currentDay.'_cal_consumed');
            if($this->input->post($currentDay.'_cal_burned'))     
                $insertedData['calBurned']  = $this->input->post($currentDay.'_cal_burned');
            if($this->input->post($currentDay.'_bodyShot'))      
                $insertedData['bodyShot']   = $this->input->post($currentDay.'_bodyShot');
            if($this->input->post($currentDay.'_chest'))         
                $insertedData['chest']      = $this->input->post($currentDay.'_chest');
            if($this->input->post($currentDay.'_waist'))         
                $insertedData['waist']      = $this->input->post($currentDay.'_waist');
            if($this->input->post($currentDay.'_arms'))          
                $insertedData['arms']       = $this->input->post($currentDay.'_arms');
            if($this->input->post($currentDay.'_forearms'))      
                $insertedData['forearms']   = $this->input->post($currentDay.'_forearms');
            if($this->input->post($currentDay.'_legs'))          
                $insertedData['legs']       = $this->input->post($currentDay.'_legs');
            if($this->input->post($currentDay.'_calves'))        
                $insertedData['calves']     = $this->input->post($currentDay.'_calves');
            if($this->input->post($currentDay.'_hips'))          
                $insertedData['hips']       = $this->input->post($currentDay.'_hips');
            if($this->input->post($currentDay.'_bicepsBF'))      
                $insertedData['bicepsBF']   = $this->input->post($currentDay.'_bicepsBF');
            if($this->input->post($currentDay.'_absBF'))         
                $insertedData['absBF']      = $this->input->post($currentDay.'_absBF');
            if($this->input->post($currentDay.'_thighsBF'))      
                $insertedData['thighsBF']   = $this->input->post($currentDay.'_thighsBF');
            if($this->input->post($currentDay.'_body_shot'))      
                $insertedData['bodyShot']   = $this->input->post($currentDay.'_body_shot');
            $row = $this->common_model->get_row('metricTracker', array('user_id'=>user_id(), 'matrixDate'=>$currentDate));
            if(!empty($row)){
                $this->common_model->update('metricTracker', $insertedData, array('id'=>$row->id));
                $ajaxResponce   = array('status'   => 'true',
                                        'message'  => 'Metric tracker is updated  successfully'
                                       );
            }else{
                $this->common_model->insert('metricTracker', $insertedData);
                $ajaxResponce   = array('status'   => 'true',
                                        'message'  => 'Metric tracker is added  successfully'
                                       );
            }
            $user    = user_info();
            if(!empty($user->gender)&&$user->gender=='female'){
                $mright = FRONT_THEAM_PATH.'img/femaleIcon.png';
            }else{
                $mright = FRONT_THEAM_PATH.'img/maleIcon.png';
            }
            $mrightH = "";
            $mrightW = "";            
            $mrightImg = $this->common_model->get_result('metricTracker', array('user_id'=>user_id(), 'bodyShot !='=>'', 'matrixDate <='=>strtotime(date('Y-m-d'))), array('bodyShot'), array('matrixDate', 'desc')); 
            if(!empty($mrightImg[0]->bodyShot)&&file_exists('assets/uploads/matrix/thumbnails/'.$mrightImg[0]->bodyShot)){
                $mright = base_url('assets/uploads/matrix/thumbnails/'.$mrightImg[0]->bodyShot);
            }else if(!empty($mrightImg[1]->bodyShot)&&file_exists('assets/uploads/matrix/thumbnails/'.$mrightImg[1]->bodyShot)){
                $mright = base_url('assets/uploads/matrix/thumbnails/'.$mrightImg[0]->bodyShot);
            }
            $mrightImgHW = $this->common_model->get_result('metricTracker', array('user_id'=>user_id(), 'height !='=>'', 'weight !='=>'', 'matrixDate <='=>strtotime(date('Y-m-d'))), array('matrix_status', 'height', 'weight'), array('matrixDate', 'desc')); 
            //print_r($mrightImgHW); exit();
            if(!empty($mrightImgHW[0]->weight)&&!empty($mrightImgHW[0]->height)){
                if($user->useMetricsSystem==1){
                  $mrightH = !empty($mrightImgHW[0]->height)?number_format($mrightImgHW[0]->height, 0).' cm':'';
                }else{
                  $inches      = intval(number_format($mrightImgHW[0]->height/2.54, 2));
                  $feetInch    = "";
                  $feetInch   .= floor($inches/12)."'";
                  $feetInch   .= floor($inches%12);
                  $mrightH     = $feetInch;
                }   
                //echo $mrightImgHW[0]->matrix_status.' matrix_status '.$user->useMetricsSystem.'useMetricsSystem';        
                if($mrightImgHW[0]->matrix_status==$user->useMetricsSystem&&$user->useMetricsSystem==1){
                    $mrightW = !empty($mrightImgHW[0]->weight)?$mrightImgHW[0]->weight.' kg':'';
                }else if($mrightImgHW[0]->matrix_status==$user->useMetricsSystem&&$user->useMetricsSystem==2){
                    $mrightW = !empty($mrightImgHW[0]->weight)?$mrightImgHW[0]->weight.' lbs':'';
                }elseif($user->useMetricsSystem==1){
                    $mrightW = number_format($mrightImgHW[0]->weight / 2.2046, 0).' kg';
                }elseif($user->useMetricsSystem==2){
                    $mrightW = number_format($mrightImgHW[0]->weight * 2.2046, 0).' lbs';
                } 
                //echo $mrightW.' mrightW';
            }else if(!empty($mrightImgHW[1]->weight)&&!empty($mrightImgHW[1]->height)){
                if($user->useMetricsSystem==1){
                  $mrightH = !empty($mrightImgHW[1]->height)?number_format($mrightImgHW[1]->height, 0).' cm':'';
                }else{
                  $inches      = intval(number_format($mrightImgHW[0]->height/2.54, 2));
                  $feetInch    = "";
                  $feetInch   .= floor($inches/12)."'";
                  $feetInch   .= floor($inches%12);
                  $mrightH     = $feetInch;
                }            
                if(!empty($user->useMetricsSystem)&&$user->useMetricsSystem==1 && $mrightImgHW[1]->matrix_status == $user->useMetricsSystem){
                    $mrightW = !empty($mrightImgHW[1]->weight)?$mrightImgHW[1]->weight.' kg':'';
                }else if(!empty($mrightImgHW[1]->matrix_status)&& $mrightImgHW[1]->matrix_status == $user->useMetricsSystem&&$user->useMetricsSystem==2){
                    $mrightW = !empty($mrightImgHW[1]->weight)?$mrightImgHW[1]->weight.' lbs':'';
                }elseif(!empty($mrightImgHW[1]->matrix_status)&& $user->useMetricsSystem==1){
                    $mrightW = number_format($mrightImgHW[1]->weight / 2.2046, 0).' kg';
                }elseif(!empty($mrightImgHW[1]->matrix_status)&& $user->useMetricsSystem==2){
                    $mrightW = number_format($mrightImgHW[1]->weight * 2.2046, 0).' lbs';
                } 
            }
            $ajaxResponce['rightImg']  = $mright;
            $ajaxResponce['mrightW']   = $mrightW;
            $ajaxResponce['mrightH']   = $mrightH;
        }else{
            $ajaxResponce   =   array('status'   => 'false',
                                    'message' => validation_errors()
                                );
        }
        echo json_encode($ajaxResponce);
    }
    public function uploadMatrixFile(){         
        $array = array('statuss'=>'false', 'message'=>'') ;    
        $this->form_validation->set_rules('user_img','','trim|xss_clean|callback_uploadMatrixFileFo'); 
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>'); 
        if ($this->form_validation->run() == TRUE){ 
            //echo 'uploaed';
            if($this->session->userdata('uploadMatrixFileFo')!=''){
                $uploadMatrixFileFo = $this->session->userdata('uploadMatrixFileFo');
                $array = array( 'statuss'   => 'true',
                                'message'   => 'Matrix pic is uploaded',
                                'file_name' => $uploadMatrixFileFo['user_img'],
                                'full_path' => base_url().'assets/uploads/matrix/thumbnails/'.$uploadMatrixFileFo['user_img']
                              ) ;     
                $this->session->unset_userdata('uploadMatrixFileFo');  
            }
        }else{
            $array = array('statuss'=>'false','message'=>form_error('user_img')) ;    
        }
        echo json_encode($array);
    } 
    public function uploadMatrixFileFo($str){
        //print_r($_FILES); exit();   
        $allowed = array("image/jpeg", "image/jpg", "image/png"); 
        if(empty($_FILES['user_img']['name'])){
            $this->form_validation->set_message('uploadMatrixFileFo', 'Choose matrix file');
            return FALSE;
        }
        if(!in_array($_FILES['user_img']['type'], $allowed)) {
            $this->form_validation->set_message('uploadMatrixFileFo', 'Only jpg, jpeg, and png files are allowed');
            return FALSE;
        }
        $image = getimagesize($_FILES['user_img']['tmp_name']);
        if ($image[0] < 100 || $image[1] < 100) {
           $this->form_validation->set_message('uploadMatrixFileFo', 'Oops! Your profile pic needs to be atleast 100 x 100 pixels');
           return FALSE;
        }
        if ($image[0] > 2000 || $image[1] > 2000) {
           $this->form_validation->set_message('uploadMatrixFileFo', 'Oops! your profile pic needs to be maximum of 2000 x 2000 pixels');
           return FALSE;
        }
        if(!empty($_FILES['user_img']['name'])):
            $config['encrypt_name']     = TRUE;
            $new_name                   = 'image_'.substr(md5(rand()),0,7).$_FILES["user_img"]['name'];
            $config['file_name']        = $new_name;
            $config['upload_path']      = 'assets/uploads/matrix/';
            $config['allowed_types']    = 'jpeg|jpg|png';
            $config['max_size']         = '7024';
            $config['max_width']        = '3000';
            $config['max_height']       = '3000';
            $this->load->library('upload', $config);
            if ( ! $this->upload->do_upload('user_img')){
                $this->form_validation->set_message('uploadMatrixFileFo', $this->upload->display_errors());
                return FALSE;
            }else{
                $data                               = $this->upload->data(); 
                $config_img_p['source_path']        = 'assets/uploads/matrix/';
                $config_img_p['destination_path']   = 'assets/uploads/matrix/thumbnails/';
                $config_img_p['width']              = '250';
                $config_img_p['height']             = '250';
                $config_img_p['file_name']          = $data['file_name'];
                $status                             = create_thumbnail($config_img_p);
                $this->session->set_userdata('uploadMatrixFileFo', array('user_img'=>$data['file_name']));
                return TRUE;
            } 
        else:
            $this->form_validation->set_message('uploadMatrixFileFo', 'The %s field required.');
            return FALSE;
            endif;
    } 
    /***************************  Progress ******************/
    public function progress(){  
        $data['template']='frontend/progress';
        $this->load->view('templates/user_template', $data); 
    } 

    /* inbox */
    public function inbox(){  
        $data['template']='frontend/inbox';
        $this->load->view('templates/user_template', $data); 
    }    
    /* inbox */
    public function like(){  
        $data['rows']     = $this->developer_model->getUserLikes();
        $data['template'] = 'frontend/like';
        $this->load->view('templates/user_template', $data); 
    }
    /* inbox */
    public function message(){  
        $data['template']='frontend/message';
        $this->load->view('templates/user_template', $data); 
    }
    /* inbox */
    public function profile_follow_feed(){  
        $data['template']='frontend/profile_follow_feed';
        $this->load->view('templates/user_template', $data); 
    }    
   /* logout users*/ 
    public function logout(){
        $this->session->sess_destroy();
        redirect(base_url());
    } 
    public function editWorkOut(){
        //echo '<pre>';print_r($_POST); exit();
        $ajaxResponce = array('status'   => 'false','message' => '');
        $exercises        = $this->common_model->get_result('service_plan_works', array('workout_id'=>$this->input->post('id')));
        if(!empty($exercises)){
            foreach($exercises as $exercise){
                $hoursData                = array();   
                if($this->input->post('minuts_'.$exercise->id)){
                    $hoursData['minuts']  = $this->input->post('minuts_'.$exercise->id);
                } 
                if($this->input->post('sets_'.$exercise->id)){
                    $hoursData['sets']      = $this->input->post('sets_'.$exercise->id);
                }
                if($this->input->post('reps_'.$exercise->id)){
                    $hoursData['reps']      = $this->input->post('reps_'.$exercise->id);
                }
                $hoursData['monday']    = ($this->input->post($exercise->id.'_monday'))?$this->input->post($exercise->id.'_monday'):0;
                $hoursData['tuesday']   = ($this->input->post($exercise->id.'_tuesday'))?$this->input->post($exercise->id.'_tuesday'):0;
                $hoursData['wednesday'] = ($this->input->post($exercise->id.'_wednesday'))?$this->input->post($exercise->id.'_wednesday'):0;
                $hoursData['thursday']  = ($this->input->post($exercise->id.'_thursday'))?$this->input->post($exercise->id.'_thursday'):0;
                $hoursData['friday']  = ($this->input->post($exercise->id.'_friday'))?$this->input->post($exercise->id.'_friday'):0;
                $hoursData['saturday']  = ($this->input->post($exercise->id.'_saturday'))?$this->input->post($exercise->id.'_saturday'):0;
                $hoursData['sunday']  = ($this->input->post($exercise->id.'_sunday'))?$this->input->post($exercise->id.'_sunday'):0;          
                $this->common_model->update('service_plan_works', $hoursData, array('id'=>$exercise->id));
            }
        } 
        $add_delete_execirces = $this->input->post('add_delete_execirces');
        if(!empty($add_delete_execirces)){
            $execirces_rows = explode(',', $add_delete_execirces);
            if(!empty($execirces_rows)){
                foreach($execirces_rows as $execirces_row){
                    $this->common_model->update('service_plan_works', array('status'=>3), array('id'=>$execirces_row));
                }
            }
        }
        $add_new_execirces = $this->input->post('add_new_execirces');
        if(!empty($add_new_execirces)){
            $execirces_rows = explode(',', $add_new_execirces);
            if(!empty($execirces_rows)){
                foreach($execirces_rows as $execirces_row){
                    $this->common_model->update('service_plan_works', array('status'=>1), array('id'=>$execirces_row));
                }
            }
        }
        $exercises  = $this->common_model->get_result('service_plan_works', array('workout_id'=>$this->input->post('id'), 'status'=>1));
        $currentdate = strtotime(date('Y-m-d'));
        $this->common_model->delete('service_plan_works_new', array('plan_id'=>$this->input->post('id'), 'work_out_date >'=>$currentdate));
        $cuurentDaysExes = $this->common_model->get_result('service_plan_works_new', array('plan_id'=>$this->input->post('id'), 'work_out_date'=>$currentdate));
        if(!empty($cuurentDaysExes)){
            foreach($cuurentDaysExes as $cuurentDaysExe){
                $doneExe = $this->common_model->get_row('workout_exercise_done', array('workout_exercise_id'=>$cuurentDaysExe->id, 'exercise_date'=>$currentdate)); 
                if(empty($doneExe)){
                    $this->common_model->delete('service_plan_works_new', array('id'=>$cuurentDaysExe->id));
                }
            }
        }
        /*echo ' date '.date('Y-m-d ',$currentdate);
        echo $this->db->last_query(); exit();*/
        //print_r($exercises); exit();
        if(!empty($exercises)){
            foreach($exercises as $exercise){
                $startDay     = date('w');
                $endDay       = 15;
                //$currentdate  = strtotime('2018-06-15');
                $lastDay = $endDay - $startDay; 
                for($dayC=0; $dayC<$lastDay; $dayC++){
                    $dateTime   = $currentdate + $dayC*86400;
                    $dayId      = strtolower(date('l', $dateTime));
                    if(!empty($exercise->$dayId)){
                        $hoursData                   = array();
                        $hoursData['plan_id']        = $exercise->workout_id;
                        $hoursData['exercise_id']    = $exercise->exercise_id;     
                        $hoursData['week_id']        = date('W', $dateTime);       
                        $hoursData['user_id']        = user_id();       
                        $hoursData['work_out_date']  = $dateTime; 
                        $hoursData['minuts']         = $exercise->minuts;
                        $hoursData['sets']           = $exercise->sets;
                        $hoursData['reps']           = $exercise->reps;
                        $hoursData['created_date']   = date('Y-m-d H:i:s', $dateTime);
                        $this->common_model->insert('service_plan_works_new', $hoursData);
                    }
                }
            }
        }
        $ajaxResponce = array(  'status'   => 'true',
                                'message'  => 'Workout edit plan is added successfully'
                            );
        echo json_encode($ajaxResponce);
    }
    public function editDietPlan(){
        $exercises        = $this->common_model->get_result('diet_plan_works', array('category_id'=>$this->input->post('id')));
        if(!empty($exercises)){
            foreach($exercises as $exercise){
                $hoursData                = array();   
                if($this->input->post('mealType'.$exercise->id)){
                    $hoursData['meal']  = $this->input->post('mealType'.$exercise->id);
                } 
                if($this->input->post('servingType'.$exercise->id)){
                    $hoursData['serving']      = $this->input->post('servingType'.$exercise->id);
                }
                $hoursData['monday']    = ($this->input->post($exercise->id.'_monday'))?$this->input->post($exercise->id.'_monday'):0;
                $hoursData['tuesday']   = ($this->input->post($exercise->id.'_tuesday'))?$this->input->post($exercise->id.'_tuesday'):0;
                $hoursData['wednesday'] = ($this->input->post($exercise->id.'_wednesday'))?$this->input->post($exercise->id.'_wednesday'):0;
                $hoursData['thursday']  = ($this->input->post($exercise->id.'_thursday'))?$this->input->post($exercise->id.'_thursday'):0;
                $hoursData['friday']  = ($this->input->post($exercise->id.'_friday'))?$this->input->post($exercise->id.'_friday'):0;
                $hoursData['saturday']  = ($this->input->post($exercise->id.'_saturday'))?$this->input->post($exercise->id.'_saturday'):0;
                $hoursData['sunday']  = ($this->input->post($exercise->id.'_sunday'))?$this->input->post($exercise->id.'_sunday'):0;          
                $this->common_model->update('diet_plan_works', $hoursData, array('id'=>$exercise->id));
            }
        }
        $add_delete_execirces = $this->input->post('add_delete_execirces');
        if(!empty($add_delete_execirces)){
            $execirces_rows = explode(',', $add_delete_execirces);
            if(!empty($execirces_rows)){
                foreach($execirces_rows as $execirces_row){
                    $this->common_model->update('diet_plan_works', array('status'=>3), array('id'=>$execirces_row));
                }
            }
        }
        $add_new_execirces = $this->input->post('add_new_execirces');
        if(!empty($add_new_execirces)){
            $execirces_rows = explode(',', $add_new_execirces);
            if(!empty($execirces_rows)){
                foreach($execirces_rows as $execirces_row){
                    $this->common_model->update('diet_plan_works', array('status'=>1), array('id'=>$execirces_row));
                }
            }
        }
        $exercises  = $this->common_model->get_result('diet_plan_works', array('category_id'=>$this->input->post('id'), 'status'=>1));
        $currentdate = strtotime(date('Y-m-d'));
        $this->common_model->delete('diet_plan_works_new', array('plan_id'=>$this->input->post('id'), 'diet_date >'=>$currentdate));
        $cuurentDaysExes = $this->common_model->get_result('diet_plan_works_new', array('plan_id'=>$this->input->post('id'), 'diet_date'=>$currentdate));
        if(!empty($cuurentDaysExes)){
            foreach($cuurentDaysExes as $cuurentDaysExe){
                $doneExe = $this->common_model->get_row('diet_plan_take_food', array('diet_food_id'=>$cuurentDaysExe->id, 'food_taken_date'=>$currentdate)); 
                if(empty($doneExe)){
                    $this->common_model->delete('diet_plan_works_new', array('id'=>$cuurentDaysExe->id));
                }
            }
        }
        if(!empty($exercises)){
            foreach($exercises as $exercise){
                $startDay     = date('w');
                $endDay       = 15;
                $lastDay = $endDay - $startDay; 
                for($dayC=0; $dayC<$lastDay; $dayC++){
                    $dateTime   = $currentdate + $dayC*86400;
                    $dateTime   = $currentdate + $dayC*86400;
                    $dayId      = strtolower(date('l', $dateTime));
                    if(!empty($exercise->$dayId)){
                        $hoursData                   = array();
                        $hoursData['plan_id']        = $exercise->category_id;
                        $hoursData['item_id']        = $exercise->item_id;     
                        $hoursData['week_id']        = date('W', $dateTime);       
                        $hoursData['user_id']        = user_id();       
                        $hoursData['diet_date']     = $dateTime; 
                        $hoursData['meal']           = $exercise->meal;
                        $hoursData['serving']        = $exercise->serving;
                        $hoursData['created_date']   = date('Y-m-d H:i:s', $dateTime);
                        //echo '<pre>';print_r($hoursData);
                        $this->common_model->insert('diet_plan_works_new', $hoursData);
                    }
                }
            }
        }
        $ajaxResponce = array(  'status'   => 'true',
                                    'message' => 'Diet is saved successfully'
                                );
        echo json_encode($ajaxResponce);
    }
}
?>