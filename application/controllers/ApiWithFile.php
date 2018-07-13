<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';  
class ApiWithFile extends REST_Controller {
    function __construct(){
        parent::__construct();
        $_POST = $this->post();       
    }
    /*******************!!!!!!!!!!!!!!!     Common  Project Ralated Services      !!!!!!!!!!! ******************/
    /********************   validation function  ***********************/ 
    public function user_image_check($str){
        if(!empty($_FILES['user_img']['name'])):
            $config['encrypt_name']     = TRUE;
            $new_name                   = 'image_'.substr(md5(rand()),0,7).$_FILES["user_img"]['name'];
            $config['file_name']        = $new_name;
            $config['upload_path']      = 'assets/uploads/users/thumbnails/';
            $config['allowed_types']    = '*';
            $config['max_size']         = '10000';
            $this->load->library('upload', $config);
            if ( ! $this->upload->do_upload('user_img')){
                $this->form_validation->set_message('user_image_check', $this->upload->display_errors());
                return FALSE;
            }else{
                $data = $this->upload->data(); // upload image
                /*$config_img_p['source_path'] = 'assets/uploads/users/';
                $config_img_p['destination_path'] = 'assets/uploads/users/thumbnails/';
                $config_img_p['width']      = '250';
                $config_img_p['height']     = '250';
                $config_img_p['file_name']  = $data['file_name'];
                $status=create_thumbnail($config_img_p);*/
                $this->session->set_userdata('user_image_check',array('user_img'=>$data['file_name']));
                //unlink('assets/uploads/users/'.$data['file_name']);
                return TRUE;
            } 
        else:
            $this->form_validation->set_message('user_image_check', 'The %s field required.');
            return FALSE;
            endif;
    }
    public function edit_profile_pic_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';
        $this->form_validation->set_rules('user_id', 'user id', 'xss_clean|required');
        $this->form_validation->set_rules('user_img','','trim|xss_clean|callback_user_image_check');
        $this->form_validation->set_error_delimiters('', '');   
        if ($this->form_validation->run() == TRUE){        
            $userdata         = array();        
            $user_image_check = $this->session->userdata('user_image_check');
            $userdata = array('profile_pic'=>$user_image_check['user_img']);
            $update   = $this->common_model->update('users', $userdata, array('id'=>$this->input->post('user_id')));
            $this->session->unset_userdata('user_image_check');  
            if($update){
                $data['responseCode']   = 200;
                $data['message']        = "Profile pic is changed successfully";
            }else{
                $data['responseCode'] = 500;
                $data['message']      = "Profile pic changes  failed";
            }             
            $this->response($data);
        }  
        $data = array(
                        'responseCode' => $responseCode,
                        'message'     => validation_errors()
                    );
        $this->response($data);      
    }     
    /*******************!!!!!!!!!!!!!!!        Project Ralated Services         !!!!!!!!!!! ******************/
    /********************   validation function  ***********************/ 
    public function uploadRecipeImage($str,$fileCount=''){
        $errors =  $recipeImg = array();
        if(!empty($fileCount)){
            for($countS =0; $countS<$fileCount;$countS++){
                $_FILES['user_img']['name']        = $_FILES['user_imgs']['name'][$countS];
                $_FILES['user_img']['type']        = $_FILES['user_imgs']['type'][$countS];
                $_FILES['user_img']['tmp_name']    = $_FILES['user_imgs']['tmp_name'][$countS];
                $_FILES['user_img']['error']       = $_FILES['user_imgs']['error'][$countS];
                $_FILES['user_img']['size']        = $_FILES['user_imgs']['size'][$countS];
                if(!empty($_FILES['user_img']['name'])){                    
                    $config['encrypt_name']     = TRUE;
                    $new_name                   = 'image_'.substr(md5(rand()),0,7).$_FILES["user_img"]['name'];
                    $config['file_name']        = $new_name;
                    $config['upload_path']      = 'assets/uploads/recipeBlogImages/';
                    $config['allowed_types']    = '*';
                    $config['max_size']         = '10000'; 
                    $config['overwrite']     = FALSE;  
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);  
                    if ( ! $this->upload->do_upload('user_img')){
                        $errors[] = "The ".$_FILES['user_img']['name'].$this->upload->display_errors();
                    }else{
                        $data = $this->upload->data(); // upload image
                        $fileExts = explode('.', $_FILES['user_img']['name']);
                        $fileExt  = end($fileExts);
                        if($fileExt=='jpeg'||$fileExt=='jpg'||$fileExt=='png'){
                            $config_img_p['source_path'] = 'assets/uploads/recipeBlogImages/';
                            $config_img_p['destination_path'] = 'assets/uploads/recipeBlogImages/thumbnails/';
                            $config_img_p['width']      = '250';
                            $config_img_p['height']     = '250';
                            $config_img_p['file_name']  = $data['file_name'];
                            $status                     = create_thumbnail($config_img_p);
                        }
                        $recipeImg[]                = $data['file_name'];
                    }
                }
            }
        }
        if(!empty($errors)){
            $this->form_validation->set_message('uploadRecipeImage', implode(',', $errors));
            return FALSE;
        }else{
            $this->session->set_userdata('userImgText',  implode(',', $recipeImg));
            return TRUE;
        }       
    }
    public function uploadBlog($str){
        if(!empty($_FILES['user_img']['name'])){                    
            $config['encrypt_name']     = TRUE;
            $new_name                   = 'image_'.substr(md5(rand()),0,7).$_FILES["user_img"]['name'];       
            $config['file_name']        = $new_name;
            $config['upload_path']      = 'assets/uploads/recipeBlogImages/';
            $config['allowed_types']    = '*';
            $config['max_size']         = '10000';
            $config['overwrite']     = FALSE;  
            $this->load->library('upload', $config);
            $this->upload->initialize($config);  
            if ( ! $this->upload->do_upload('user_img')){
                $errors[] = "The ".$_FILES['user_img']['name'].$this->upload->display_errors();
            }else{
                $data = $this->upload->data(); // upload image
                $fileExts = explode('.', $_FILES['user_img']['name']);
                $fileExt  = end($fileExts);
                if($fileExt=='jpeg'||$fileExt=='jpg'||$fileExt=='png'){
                    $config_img_p['source_path'] = 'assets/uploads/recipeBlogImages/';
                    $config_img_p['destination_path'] = 'assets/uploads/recipeBlogImages/thumbnails/';
                    $config_img_p['width']      = '250';
                    $config_img_p['height']     = '250';
                    $config_img_p['file_name']  = $data['file_name'];
                    $status                     = create_thumbnail($config_img_p);
                }                
                $this->session->set_userdata('userImgText',  $data['file_name']);
            }
        }
        if(!empty($errors)){
            $this->form_validation->set_message('uploadBlog', implode(',', $errors));
            return FALSE;
        }else{            
            return TRUE;
        }       
    }
    public function add_recipe_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';
        $this->form_validation->set_rules('title', 'title', 'trim|xss_clean|required');
        $this->form_validation->set_rules('description', 'description', 'trim|xss_clean|required');   
        $this->form_validation->set_rules('user_id', 'user id', 'trim|xss_clean|required');   
        if(!empty($_FILES['user_imgs']['name'])){
            $fileCount = count($_FILES['user_imgs']['name']);
            $this->form_validation->set_rules('user_imgs','','trim|xss_clean|callback_uploadRecipeImage['.$fileCount.']');   
        }else{
            $this->form_validation->set_rules('user_imgs','image','trim|xss_clean|required');   
        }
        $this->form_validation->set_error_delimiters('', '');
        if ($this->form_validation->run() == TRUE){
            $insertedData         = array();
            $insertedData['type'] = 'recipe';
            if($this->input->post('title')){
                $insertedData['title'] = $this->input->post('title', TRUE);
            }
            if($this->input->post('description')){
                $insertedData['description'] = $this->input->post('description', TRUE);
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
            $insertedData['user_id'] = $this->input->post('user_id', TRUE);
            $insertedData['created_date'] = date('Y-m-d H:i:s'); 
            if($this->input->post('recipe_id')){
                $this->common_model->update('recipe_blog_image', $insertedData, array('id'=>$this->input->post('recipe_id', TRUE)));
                $recipeID = $this->input->post('recipe_id');
                $data['message']        = "Recipe is updated successfully";
            }else{
                $recipeID = $this->common_model->insert('recipe_blog_image', $insertedData);
                $data['message']        = "Recipe is added successfully";
            }
            $userImgText  = $this->session->userdata('userImgText');
            $userImgs     = explode(',', $userImgText);
            if(!empty($userImgs)){
                foreach($userImgs as $userImg){
                    $i=1;
                    if($i==1){ $firstImg = $userImg;}
                    $this->common_model->insert('images', 
                                                    array(
                                                        'image_name'   => $userImg, 
                                                        'meta_id'      => $recipeID,
                                                        'created_date' => date('Y-m-d H:i:s')
                                                    )
                                                );
                    $i++;
                }
            }
            $this->session->unset_userdata('userImgText');
            if(!empty($firstImg)){
                $this->common_model->update('recipe_blog_image',  
                                            array('image_name' => $firstImg), 
                                            array('id'         => $recipeID)
                                        ); 
            }   
            if($recipeID){
                $data['responseCode']   = 200;               
            }else{
                $data['responseCode'] = 500;
                $data['message']      = "Recipe added  failed";
            }             
            $this->response($data);     
        }
       $data = array(
                        'responseCode' => $responseCode,
                        'message'     => validation_errors()
                    );
        $this->response($data);      
    } 
    public function add_image_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';
        $this->form_validation->set_rules('caption', 'caption', 'trim|xss_clean|required');
        $this->form_validation->set_rules('location', 'location', 'trim|xss_clean|required');
        $this->form_validation->set_rules('tag_users', 'description', 'trim|xss_clean');   
        $this->form_validation->set_rules('user_id', 'user id', 'trim|xss_clean|required');
        if(!empty($_FILES['user_imgs']['name'])){
            $fileCount = count($_FILES['user_imgs']['name']);
            $this->form_validation->set_rules('user_imgs','','trim|xss_clean|callback_uploadRecipeImage['.$fileCount.']'); 
        }else{
            $this->form_validation->set_rules('user_imgs','image','trim|xss_clean|required');   
        }
        $this->form_validation->set_error_delimiters('', '');   
        if ($this->form_validation->run() == TRUE){
            $insertedData         = array();
            $insertedData['type'] = 'image';
            if($this->input->post('caption')){
                $insertedData['caption'] = $this->input->post('caption', TRUE);
            } 
            if($this->input->post('location')){
                $insertedData['location'] = $this->input->post('location', TRUE);
            } 
            if($this->input->post('tag_users')){
                $insertedData['tag_users'] = $this->input->post('tag_users', TRUE);
            }
            $insertedData['created_date'] = date('Y-m-d H:i:s');
            $insertedData['user_id'] = $this->input->post('user_id', TRUE); 
            $userImgText  = $this->session->userdata('userImgText');
            $userImgs     = explode(',', $userImgText);
            if($this->input->post('id')){
                $this->common_model->update('recipe_blog_image', $insertedData, array('id'=>$this->input->post('id', TRUE)));
                $recipeID = $this->input->post('id');
                 $data['message']        = "Images is updated successfully";
            }else{
                if(!empty($userImgs[0])){
                    $insertedData['image_name'] = $userImgs[0];
                }
                $recipeID = $this->common_model->insert('recipe_blog_image', $insertedData);
                $data['message']        = "Images is posted successfully";
            }             
            if(!empty($userImgs)){
                foreach($userImgs as $userImg){
                    $i=1;    
                    if(!empty($userImg)){
                        $insertedData['image_name'] = $userImg;
                        $this->common_model->insert('images', 
                                                    array(
                                                        'image_name'   => $userImg, 
                                                        'meta_id'      => $recipeID,
                                                        'created_date' => date('Y-m-d H:i:s')
                                                    )
                                                );
                    }                    
                    $i++;
                }
            }  
            $this->session->unset_userdata('userImgText');
            if($recipeID){
                $data['responseCode']   = 200;               
            }else{
                $data['responseCode'] = 500;
                $data['message']      = "Images posted  failed";
            }             
            $this->response($data);  
        }
        $data = array(
                        'responseCode' => $responseCode,
                        'message'     => validation_errors()
                    );
        $this->response($data);      
    }
    public function add_blog_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';
        $this->form_validation->set_rules('title', 'title', 'trim|xss_clean|required');
        $this->form_validation->set_rules('description', 'description', 'trim|xss_clean|required');   
        $this->form_validation->set_rules('user_id', 'user id', 'trim|xss_clean|required');   
        $this->form_validation->set_rules('blog', 'blog', 'trim|xss_clean');   
        if(!empty($_FILES['user_img']['name'])){
            $this->form_validation->set_rules('user_img','','trim|xss_clean|callback_uploadBlog');   
        }
        $this->form_validation->set_error_delimiters('', '');  
        if ($this->form_validation->run() == TRUE){
            $insertedData         = array();
            $insertedData['type'] = 'blog';
            $insertedData['created_date'] = date('Y-m-d H:i:s');
            if($this->input->post('title')){
                $insertedData['title'] = $this->input->post('title', TRUE);
            } 
            if($this->input->post('description')){
                $insertedData['description'] = $this->input->post('description', TRUE);
            } 
            if($this->input->post('blog')){
                $insertedData['content'] = $this->input->post('blog', TRUE);
            } 
            
            $insertedData['user_id'] = $this->input->post('user_id', TRUE); 
            $userImgText             = $this->session->userdata('userImgText');
            if(!empty($userImgText)){
                $insertedData['coverImage']  = $userImgText;
                $this->session->unset_userdata('userImgText');
            }
            if($this->input->post('blog_id')){
                $this->common_model->update('recipe_blog_image', $insertedData, array('id'=>$this->input->post('blog_id', TRUE)));
                $recipeID = $this->input->post('blog_id');
                $data['message']        = "Blog is updated successfully";
            }else{                
                $recipeID = $this->common_model->insert('recipe_blog_image', $insertedData);
                $data['message']        = "Blog is posted successfully";
            }
            if($recipeID){
                $data['responseCode']   = 200;                
            }else{
                $data['responseCode'] = 500;
                $data['message']      = "Blog posted  failed";
            }             
            $this->response($data);     
        }
        $data = array(
                        'responseCode' => $responseCode,
                        'message'     => validation_errors()
                    );
        $this->response($data);      
    }
    public function recipe_blog_image_post(){ 
        $this->form_validation->set_rules('title', 'title', 'trim|xss_clean|required');
        $this->form_validation->set_rules('description', 'description', 'trim|xss_clean|required');   
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        if ($this->form_validation->run() == TRUE){
            $insertedData         = array();
            $insertedData['type'] = 'recipe';
            if($this->input->post('title')){
                $insertedData['title'] = $this->input->post('title', TRUE);
            }
            if($this->input->post('description')){
                $insertedData['description'] = $this->input->post('description', TRUE);
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
            if($this->input->post('location')){
                $insertedData['location'] = $this->input->post('location', TRUE);
            } 
            if($this->input->post('tag_users')){
                $insertedData['tag_users'] = $this->input->post('tag_users', TRUE);
            }
            $insertedData['created_date'] = date('Y-m-d H:i:s');
            $insertedData['user_id'] = user_id();   
            if($this->input->post('recipe_blog_image_type', TRUE)=='recipe'){
                $messageTag = 'Recipe';
            } else if($this->input->post('recipe_blog_image_type', TRUE)=='blog'){
                $messageTag = 'Blog';
            }  else if($this->input->post('recipe_blog_image_type', TRUE)=='profile_post'){
                $messageTag = 'Image';
            }  
            if($this->input->post('images_ids', TRUE)){
                $images_ids = $this->input->post('images_ids', TRUE);
                $imagesArr  = explode('-', $images_ids);
                if(!empty($imagesArr)){
                    foreach ($imagesArr as $image) {
                        $insertedData['saved_images'] = 1;
                        $this->common_model->update('recipe_blog_image', $insertedData, array('id'=>$image));
                    }
                }
                if($this->input->post('default_id', TRUE)){
                    $recipeID = $this->input->post('default_id', TRUE);                
                }
                $ajaxResponce = array('status'  => 'true',
                                      'type'    => 'edited',
                                      'message' => $messageTag.' is updated successfully.'
                                    );
            }else{                
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
            }
            if($this->input->post('recepeImgs')){
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
                                                    array('coverImage' => $this->input->post('recepeImgs', TRUE)), 
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
    public function save_matrix_post(){ 
        $responseCode   = 500;
        $message        = 'Invalid Request';
        $this->form_validation->set_rules('user_id', 'user id', 'trim|xss_clean|required');
        $this->form_validation->set_rules('matrixDate', 'date', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('height', 'height', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('weight', 'weight', 'trim|xss_clean|required');  
        $this->form_validation->set_rules('cal_consumed', 'cal consumed', 'trim|xss_clean');  
        $this->form_validation->set_rules('cal_burned', 'cal consumed', 'trim|xss_clean');  
        $this->form_validation->set_rules('chest', 'chest', 'trim|xss_clean');  
        $this->form_validation->set_rules('waist', 'waist', 'trim|xss_clean');  
        $this->form_validation->set_rules('arms', 'arms', 'trim|xss_clean');  
        $this->form_validation->set_rules('forearms', 'forearms', 'trim|xss_clean');  
        $this->form_validation->set_rules('legs', 'legs', 'trim|xss_clean');  
        $this->form_validation->set_rules('calves', 'calves', 'trim|xss_clean');  
        $this->form_validation->set_rules('hips', 'hips', 'trim|xss_clean');  
        $this->form_validation->set_rules('bicepsBF', 'biceps bf', 'trim|xss_clean');  
        $this->form_validation->set_rules('absBF', 'abs bf', 'trim|xss_clean');  
        $this->form_validation->set_rules('thighsBF', 'thighs bf', 'trim|xss_clean'); 
        if(!empty($_FILES['user_img']['name'])){
            $fileCount = count($_FILES['user_img']['name']);
            $this->form_validation->set_rules('user_img','','trim|xss_clean|callback_uploadMatrixImage'); 
        }else{
            $this->form_validation->set_rules('user_img','image','trim|xss_clean');   
        }
        $this->form_validation->set_error_delimiters('', '');   
        if ($this->form_validation->run() == TRUE){
            $insertedData                   = array();
            if($this->input->post('matrixDate'))   
                $insertedData['matrixDate'] = strtotime($this->input->post('matrixDate'));
            if($this->input->post('height'))        
                $insertedData['height']     = $this->input->post('height');
            if($this->input->post('weight'))        
                $insertedData['weight']     = $this->input->post('weight');
            if($this->input->post('cal_consumed'))   
                $insertedData['calConsumed']= $this->input->post('cal_consumed');
            if($this->input->post('cal_burned'))     
                $insertedData['calBurned']  = $this->input->post('cal_burned');
            if($this->input->post('bodyShot'))      
                $insertedData['bodyShot']   = $this->input->post('bodyShot');
            if($this->input->post('chest'))         
                $insertedData['chest']      = $this->input->post('chest');
            if($this->input->post('waist'))         
                $insertedData['waist']      = $this->input->post('waist');
            if($this->input->post('arms'))          
                $insertedData['arms']       = $this->input->post('arms');
            if($this->input->post('forearms'))      
                $insertedData['forearms']   = $this->input->post('forearms');
            if($this->input->post('legs'))          
                $insertedData['legs']       = $this->input->post('legs');
            if($this->input->post('calves'))        
                $insertedData['calves']     = $this->input->post('calves');
            if($this->input->post('hips'))          
                $insertedData['hips']       = $this->input->post('hips');
            if($this->input->post('bicepsBF'))      
                $insertedData['bicepsBF']   = $this->input->post('bicepsBF');
            if($this->input->post('absBF'))         
                $insertedData['absBF']      = $this->input->post('absBF');
            if($this->input->post('thighsBF'))      
                $insertedData['thighsBF']   = $this->input->post('thighsBF');
            $insertedData['user_id']        = $this->input->post('user_id', TRUE); 
            $userImgText                    = $this->session->userdata('userImgText');
            if(!empty($userImgText)){
                $insertedData['bodyShot']   = $userImgText;
            }            
            $row = $this->common_model->get_row('metricTracker', array('user_id'=>$this->input->post('user_id', TRUE), 'matrixDate'=>strtotime($this->input->post('matrixDate'))));
            if(!empty($row)){
                $this->common_model->update('metricTracker', $insertedData, array('id'=>$row->id));
                $data['message'] = "Matric tracker is updated  successfully";
                $daysID          = $row->id;
            }else{
                $daysID           = $this->common_model->insert('metricTracker', $insertedData);
                $data['message']  = "Matric tracker is added  successfully";
            }
            $this->session->unset_userdata('userImgText');
            if($daysID){
                $data['responseCode']   = 200;               
            }else{
                $data['responseCode'] = 500;
                $data['message']      = "Matric tracker failed";
            }             
            $this->response($data);  
        }
        $data = array(
                        'responseCode' => $responseCode,
                        'message'     => validation_errors()
                    );
        $this->response($data);      
    }
    public function uploadMatrixImage($str){
        $errors                            = $recipeImg = array();
        $_FILES['user_img']['name']        = $_FILES['user_img']['name'];
        $_FILES['user_img']['type']        = $_FILES['user_img']['type'];
        $_FILES['user_img']['tmp_name']    = $_FILES['user_img']['tmp_name'];
        $_FILES['user_img']['error']       = $_FILES['user_img']['error'];
        $_FILES['user_img']['size']        = $_FILES['user_img']['size'];
        if(!empty($_FILES['user_img']['name'])){                    
            $config['encrypt_name']     = TRUE;
            $new_name                   = 'image_'.substr(md5(rand()),0,7).$_FILES["user_img"]['name'];
            $config['file_name']        = $new_name;
            $config['upload_path']      = 'assets/uploads/matrix/';
            $config['allowed_types']    = '*';
            $config['max_size']         = '10000'; 
            $config['overwrite']     = FALSE;  
            $this->load->library('upload', $config);
            $this->upload->initialize($config);  
            if(!$this->upload->do_upload('user_img')){
                $errors[] = "The ".$_FILES['user_img']['name'].$this->upload->display_errors();
            }else{
                $data = $this->upload->data(); // upload image
                $fileExts = explode('.', $_FILES['user_img']['name']);
                $fileExt  = end($fileExts);
                if($fileExt=='jpeg'||$fileExt=='jpg'||$fileExt=='png'){
                    $config_img_p['source_path'] = 'assets/uploads/matrix/';
                    $config_img_p['destination_path'] = 'assets/uploads/matrix/thumbnails/';
                    $config_img_p['width']      = '250';
                    $config_img_p['height']     = '250';
                    $config_img_p['file_name']  = $data['file_name'];
                    $status                     = create_thumbnail($config_img_p);
                }
                $this->session->set_userdata('userImgText',  $data['file_name']);
                return TRUE;
            }
            if(!empty($errors)){
                $this->form_validation->set_message('uploadMatrixImage', implode(',', $errors));
                return FALSE;
            }  
        } else{
            $this->form_validation->set_message('uploadMatrixImage', 'Image is required');
            return FALSE;
        }
    }
}