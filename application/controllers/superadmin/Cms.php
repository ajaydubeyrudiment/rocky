<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*this controller for making manage cms in superadmin */
class Cms extends CI_Controller {
    public function __construct(){
        parent::__construct();  
        clear_cache();  
        $this->load->model('common_model');  
        if(!superadmin_logged_in()){
            redirect(base_url().ADMIN_DIR.'login');
        }
    }
    public function index(){
        $data                           = array();           
        $config                         = frontend_pagination();
        $config['enable_query_strings'] = TRUE;            
        if(!empty($_SERVER['QUERY_STRING'])){
          $config['suffix']       = "?".$_SERVER['QUERY_STRING'];
        }else{
          $config['suffix']       = '';
        }       
        $config['base_url']         = base_url().ADMIN_DIR.'cms/index/';
        $counts                     = $this->developer_model->cms_result(0, 0);
        $config['total_rows']       = $counts;
        $config['per_page']         = PER_PAGE;        
        $config['uri_segment']      = 4;           
        $config['use_page_numbers'] = TRUE;  
        $config['first_url']        = $config['base_url'].$config['suffix'];          
        $pageNo                     = $this->uri->segment(4);
        $offSet                     = 0;
        if($pageNo){
            $offSet                 = $config['per_page']*($pageNo-1);
        }
        $this->pagination->initialize($config);
        $data['offset']     = $offSet;
        $data['pagination'] = $this->pagination->create_links();
        $data['rows']       = $this->developer_model->cms_result($offSet, PER_PAGE);            
        $data['template']   = 'superadmin/cms/cms_list';
        $this->load->view('templates/superadmin_template',$data); 
    } 
   /* Edit Page Data*/    
    public function edit_containt($id=''){  
        if(!empty($id)){
            if($id=='about'){
                if($this->input->post('about_Description')){
                    $update = $this->common_model->update('web_info', array('meta_data'=>$this->input->post('about_Description')), array('meta_key'=>'about_us'));
                    $this->session->set_flashdata('msg_success', 'Content is updated successfull');
                } 
            } 
            if($id=='home_banner'){
               if (!empty($_FILES['user_img']['name'])){
                    $this->form_validation->set_rules('user_img','','callback_blog_image_check');
                    $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
                    if ($this->form_validation->run() == TRUE){
                    }
                }
                 if($this->input->post('title')){
                    $update = $this->common_model->update('web_info', array('meta_data'=>$this->input->post('title')), array('meta_key'=>'home_title'));
                    $this->session->set_flashdata('msg_success', 'Content is updated successfull');
                } 
                 if($this->input->post('desciption')){
                    $update = $this->common_model->update('web_info', array('meta_data'=>$this->input->post('desciption')), array('meta_key'=>'home_desciption'));
                    $this->session->set_flashdata('msg_success', 'Content is updated successfull');
                }  
            }  
            if($id=='middle_banner'){
               if (!empty($_FILES['user_img']['name'])){
                    $this->form_validation->set_rules('user_img','','callback_middile_image_check');
                    $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
                    if ($this->form_validation->run() == TRUE){
                    }
                }
                 if($this->input->post('title')){
                    $update = $this->common_model->update('web_info', array('meta_data'=>$this->input->post('title')), array('meta_key'=>'home_title'));
                    $this->session->set_flashdata('msg_success', 'Content is updated successfull');
                } 
                 if($this->input->post('desciption')){
                    $update = $this->common_model->update('web_info', array('meta_data'=>$this->input->post('desciption')), array('meta_key'=>'home_desciption'));
                    $this->session->set_flashdata('msg_success', 'Content is updated successfull');
                }  
            }  
            $data['title']      =  'Edit Content';
            $data['row']        = $this->common_model->get_result('web_info', array('status'=>1), array(), array('id','ASC'));      
            $data['template']   = 'superadmin/cms/edit_containt';
            $this->load->view('templates/superadmin_template',$data);
        } else{
            redirect('superadmin/superadmin/error_404');
        }       
    }  
    public function middile_image_check($str){
        $allowed = array("image/jpeg", "image/jpg", "image/png"); 
        $images  = explode('.', $_FILES["user_img"]['name']);
        $ext     = end($images); 
        $imageVideo = 'content image ';  
        if($ext=='mp4'){
            $imageVideo = 'content video ';
        }
        if(empty($_FILES['user_img']['name'])){
            $this->form_validation->set_message('middile_image_check', 'The '.$imageVideo.'  is required');
            return FALSE;
        }
        if(!in_array($_FILES['user_img']['type'], $allowed)) {
            $this->form_validation->set_message('middile_image_check', 'Only jpg, jpeg and png files are allowed');
            return FALSE;
        }
        if($ext!='mp4'){
            $image = getimagesize($_FILES['user_img']['tmp_name']);
            if ($image[0] < 1000 || $image[1] < 360) {
                $this->form_validation->set_message('middile_image_check', 'Oops! Your logo needs to be atleast 1000 x 360 pixels');
                return FALSE;
            }
            if ($image[0] > 2000 || $image[1] > 2000) {
                $this->form_validation->set_message('middile_image_check', 'Oops! Your logo needs to be maximum of 2000 x 2000 pixels');
                return FALSE;
            }
        }
        if(!empty($_FILES['user_img']['name'])):
            $config['encrypt_name']     = TRUE;
            $max_size                   = 1024*20;
            $new_name                   = 'image_'.substr(md5(rand()),0,7).$_FILES["user_img"]['name'];
            $config['file_name']        = $new_name;
            $config['upload_path']      = 'assets/uploads/middle/';
            $config['allowed_types']    = 'jpeg|jpg|png';
            $config['max_size']         = $max_size;
            $config['max_width']        = '2000';
            $config['max_height']       = '2000';
            $this->load->library('upload', $config);
            if ( ! $this->upload->do_upload('user_img')){
                $this->form_validation->set_message('blog_image_check', $this->upload->display_errors());
                return FALSE;
            }else{
                $data = $this->upload->data(); // upload image
                $config_img_p['source_path'] = 'assets/uploads/middle/';
                $config_img_p['destination_path'] = 'assets/uploads/middle/thumbnails/';
                $config_img_p['width']      = '360';
                $config_img_p['height']     = '360';
                $config_img_p['file_name']  = $data['file_name'];
                $status=create_thumbnail($config_img_p);                
                $update = $this->common_model->update('web_info', array('meta_data'=>$data['file_name']), array('meta_key'=>'moddile_banner'));                
                $this->session->set_flashdata('msg_success', 'Content is updated successfull');
                return TRUE;
            } 
        else:
            $this->form_validation->set_message('blog_image_check', 'The %s field required.');
            return FALSE;
            endif;
    }   
    public function blog_image_check($str){
        $allowed = array("image/jpeg", "image/jpg", "image/png"); 
        $images  = explode('.', $_FILES["user_img"]['name']);
        $ext     = end($images); 
        $imageVideo = 'content image ';  
        if($ext=='mp4'){
            $imageVideo = 'content video ';
        }
        if(empty($_FILES['user_img']['name'])){
            $this->form_validation->set_message('blog_image_check', 'The '.$imageVideo.'  is required');
            return FALSE;
        }
        if(!in_array($_FILES['user_img']['type'], $allowed)) {
            $this->form_validation->set_message('blog_image_check', 'Only jpg, jpeg and png files are allowed');
            return FALSE;
        }
        if($ext!='mp4'){
            $image = getimagesize($_FILES['user_img']['tmp_name']);
            if ($image[0] < 1000 || $image[1] < 360) {
                $this->form_validation->set_message('blog_image_check', 'Oops! Your logo needs to be atleast 1000 x 360 pixels');
                return FALSE;
            }
            if ($image[0] > 2000 || $image[1] > 2000) {
                $this->form_validation->set_message('blog_image_check', 'Oops! Your logo needs to be maximum of 2000 x 2000 pixels');
                return FALSE;
            }
        }
        if(!empty($_FILES['user_img']['name'])):
            $config['encrypt_name']     = TRUE;
            $max_size                   = 1024*20;
            $new_name                   = 'image_'.substr(md5(rand()),0,7).$_FILES["user_img"]['name'];
            $config['file_name']        = $new_name;
            $config['upload_path']      = 'assets/uploads/slider/';
            $config['allowed_types']    = 'jpeg|jpg|png';
            $config['max_size']         = $max_size;
            $config['max_width']        = '2000';
            $config['max_height']       = '2000';
            $this->load->library('upload', $config);
            if ( ! $this->upload->do_upload('user_img')){
                $this->form_validation->set_message('blog_image_check', $this->upload->display_errors());
                return FALSE;
            }else{
                $data = $this->upload->data(); // upload image
                $config_img_p['source_path'] = 'assets/uploads/slider/';
                $config_img_p['destination_path'] = 'assets/uploads/slider/thumbnails/';
                $config_img_p['width']      = '360';
                $config_img_p['height']     = '360';
                $config_img_p['file_name']  = $data['file_name'];
                $status=create_thumbnail($config_img_p);                
                $update = $this->common_model->update('web_info', array('meta_data'=>$data['file_name']), array('meta_key'=>'home_banner'));                
                $this->session->set_flashdata('msg_success', 'Content is updated successfull');
                return TRUE;
            } 
        else:
            $this->form_validation->set_message('blog_image_check', 'The %s field required.');
            return FALSE;
            endif;
    }  
}