<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*this controller for making manage all boards in superadmin */
class AdminPage extends CI_Controller {
	public function __construct(){
	    parent::__construct();  
	    clear_cache();
	    if(!superadmin_logged_in()){
        	redirect(base_url().ADMIN_DIR.'login');
        }         
	}
	/*admin module listing*/
	public function index(){
    $data                           = array();           
    $config                         = frontend_pagination();
    $config['enable_query_strings'] = TRUE;            
    if(!empty($_SERVER['QUERY_STRING'])){
      $config['suffix']       = "?".$_SERVER['QUERY_STRING'];
    }else{
      $config['suffix']       = '';
    }       
    $config['base_url']         = base_url().ADMIN_DIR."adminPage/index/";
    $counts                     = $this->developer_model->admin_result(0, 0);
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
    $data['rows']       = $this->developer_model->admin_result($offSet, PER_PAGE);
    $data['siteData']  	= $this->developer_model->site_data();
    if($this->input->get('module_id')){
	    $data['tableHeads']  	= $this->common_model->get_result('ad_module_table_head', array('module_id'=>$this->input->get('module_id')), array(), array('id','ASC'));
    	$data['filters']  	= $this->common_model->get_result('ad_module_filter', array('module_id'=>$this->input->get('module_id')), array(), array('id','ASC'));		      
	  }	    
    $data['template']   = 'superadmin/admin_result/containt_list';
    $this->load->view('templates/superadmin_template',$data); 
	} 
  /* add page  containt*/	 
  public function add_containt($id=""){
    if($this->input->get('module_id')){
      $data['frows']    = $this->common_model->get_result('ad_module_form', array('module_id'=>$this->input->get('module_id')), array(), array('id','ASC'));   
    } 
    if(isset($_POST['submit'])){   
      print_r($_POST); // exit();  
      if(!empty($data['frows'])){
        foreach ($data['frows'] as $validate) {
          if(!empty($validate->ci_validation)){
            $this->form_validation->set_rules($validate->fieled_name, $validate->title, $validate->ci_validation); 
            //echo $validate->fieled_name.' '.$validate->title.' '.$validate->ci_validation.'<br/>'; 
          }
        }
      }
      if (!empty($_POST['id'])){  
        if (!empty($_FILES['user_img']['name'])){
          $this->form_validation->set_rules('user_img','','callback_user_image_check');
        } 
      } else{
         $this->form_validation->set_rules('user_img','image','callback_user_image_check');
      }   
      $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
      if($this->form_validation->run()==true){
        echo 'success'; exit();
        $data                     = array();      
        $data['title']            = $this->input->post('title');  
        $data['description']      = $this->input->post('description');
        if($this->session->userdata('user_image_check')!=''){
          $user_image_check       = $this->session->userdata('user_image_check');
          $data['new_profile']    = $user_image_check['user_img'];
          $this->session->unset_userdata('user_image_check');       
        }
        if (!empty($_POST['id'])){                
          $this->common_model->update('news', $data, array('id'=>$_POST['id']));
          $this->session->set_flashdata('msg_success', 'News is updated successfully');      
          redirect(base_url().ADMIN_DIR."news");
        }else{
          $this->common_model->insert('news', $data);
          $this->session->set_flashdata('msg_success', 'News is added successfully');
          redirect(base_url().ADMIN_DIR."news/add_news");
        }
      }
    }
    $data['title']        = 'Add News'; 
    if($id){
      $data['title']      = 'Edit News';  
      $data['row']        = $this->common_model->get_row('news', array('id'=>$id));  
    } 
    $data['template']  = 'superadmin/admin_result/add_containt';
    $this->load->view('templates/superadmin_template',$data); 
  }  
  public function fileUpload($str){
    $allowed = array("image/jpeg", "image/jpg", "image/png"); 
    if(empty($_FILES['user_img']['name'])){
      $this->form_validation->set_message('user_image_check', 'Choose Image');
      return FALSE;
    }
    if(!in_array($_FILES['user_img']['type'], $allowed)) {
      $this->form_validation->set_message('user_image_check', 'Only jpg, jpeg, and png files are allowed');
      return FALSE;
    }
    $image = getimagesize($_FILES['user_img']['tmp_name']);
    if ($image[0] < 350 || $image[1] < 350) {
      $this->form_validation->set_message('user_image_check', 'Oops! Your Image needs to be atleast 350 x 350 pixels');
      return FALSE;
    }
    if ($image[0] > 2000 || $image[1] > 2000) {
      $this->form_validation->set_message('user_image_check', 'Oops! Your Image needs to be maximum of 2000 x 2000 pixels');
      return FALSE;
    }
    if(!empty($_FILES['user_img']['name'])):
      $config['encrypt_name']   = TRUE;
      $new_name                 = 'image_'.substr(md5(rand()),0,7).$_FILES["user_img"]['name'];
      $config['file_name']      = $new_name;
      $config['upload_path']    = 'assets/uploads/news/';
      $config['allowed_types']  = 'jpeg|jpg|png';
      $config['max_size']       = '7024';
      $config['max_width']      = '2000';
      $config['max_height']     = '2000';
      $this->load->library('upload', $config);
      if ( ! $this->upload->do_upload('user_img')){
          $this->form_validation->set_message('user_image_check', $this->upload->display_errors());
          return FALSE;
      }else{
        $data = $this->upload->data(); // upload image
        $config_img_p['source_path'] = 'assets/uploads/news/';
        $config_img_p['destination_path'] = 'assets/uploads/news/thumbnails/';
        $config_img_p['width']      = '350';
        $config_img_p['height']     = '350';
        $config_img_p['file_name']  = $data['file_name'];
        $status=create_thumbnail($config_img_p);
        $this->session->set_userdata('user_image_check',array('image_url'=>$config['upload_path'].$data['file_name'],
             'user_img'=>$data['file_name']));
        return TRUE;
      } 
    else:
      $this->form_validation->set_message('user_image_check', 'The %s field required.');
      return FALSE;
      endif;
  }
}