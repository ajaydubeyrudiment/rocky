<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*this controller for making manage all users in superadmin */
class ServicePlan extends CI_Controller {
    public function __construct() {
        parent::__construct();
        clear_cache();
        if(!superadmin_logged_in()){
           redirect(base_url().ADMIN_DIR.'login');
        }
    }
    /*user list with filters*/
    function index() {
        $data = array();
        $config = admin_pagination();
        $config['enable_query_strings'] = TRUE;
        if (!empty($_SERVER['QUERY_STRING'])) {
            $config['suffix'] = "?" . $_SERVER['QUERY_STRING'];
        } else {
            $config['suffix'] = '';
        }
        $config['base_url']         = base_url() . "superadmin/servicePlan/index/";
        $counts                     = $this->developer_model->getServicePlan(0, 0);
        $config['total_rows']       = $counts;
        $config['per_page']         = PER_PAGE;
        $config['uri_segment']      = 4;
        $config['use_page_numbers'] = TRUE;
        $config['first_url']        = $config['base_url'] . $config['suffix'];
        $pageNo = $this->uri->segment(4);        
        $this->pagination->initialize($config);        
        $offSet = 0;
        if ($pageNo) {
            $offSet = $config['per_page'] * ($pageNo - 1);
        }
        $data['pagination'] = $this->pagination->create_links();
        $data['rows']       = $this->developer_model->getServicePlan($offSet, PER_PAGE);
        $data['offset']     = $offSet;
        $data['template']   = 'superadmin/servicePlan/servicePlan';
        $this->load->view('templates/superadmin_template', $data);
    }
    /*superadmin dashboard*/
    public function addServicePlan($id = '') {
        if (isset($_POST['submit'])) {
            //print_r($_POST); exit();
            $this->form_validation->set_rules('title', 'title', 'trim|required');
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if ($this->form_validation->run() == TRUE) {
                $insertData = array();
                if ($this->input->post('title')) $insertData['title'] = $this->input->post('title');
                if ($this->input->post('id')) {
                    $this->common_model->update('service_plan', $insertData, array('id' => $this->input->post('id')));
                    $this->session->set_flashdata('msg_success', 'Service Plan is updated successfully');
                    redirect(ADMIN_URL.'ServicePlan');
                } else {
                    $this->common_model->insert('service_plan', $insertData);
                    $this->session->set_flashdata('msg_success', 'Service Plan is added successfully');
                    redirect(ADMIN_URL . 'servicePlan/addServicePlan');
                }
            }
        }
        $data['title'] = 'Add Service Plan';
        if (!empty($id)) {
            $data['title'] = 'Edit Service Plan';
            $data['row'] = $this->common_model->get_row('service_plan', array('id' => $id));
        }
        $data['template'] = 'superadmin/servicePlan/addServicePlan';
        $this->load->view('templates/superadmin_template', $data);
    }    
    /******************************     Plan Category    ****************/
    public function planCategory() {
        $data = array();
        $config = admin_pagination();
        $config['enable_query_strings'] = TRUE;
        if (!empty($_SERVER['QUERY_STRING'])) {
            $config['suffix'] = "?" . $_SERVER['QUERY_STRING'];
        } else {
            $config['suffix'] = '';
        }
        $config['base_url']         = base_url() . "superadmin/servicePlan/planCategory/";
        $counts                     = $this->developer_model->getPlanCategory(0, 0);
        $config['total_rows']       = $counts;
        $config['per_page']         = PER_PAGE;
        $config['uri_segment']      = 4;
        $config['use_page_numbers'] = TRUE;
        $config['first_url']        = $config['base_url'] . $config['suffix'];
        $pageNo = $this->uri->segment(4);        
        $this->pagination->initialize($config);        
        $offSet = 0;
        if ($pageNo) {
            $offSet = $config['per_page'] * ($pageNo - 1);
        }
        $data['pagination'] = $this->pagination->create_links();
        $data['rows']       = $this->developer_model->getPlanCategory($offSet, PER_PAGE);
        $data['offset']     = $offSet;
        $data['plans']      = $this->common_model->get_result('service_plan', array('status' => 1));
        $data['template']   = 'superadmin/servicePlan/planCategory';
        $this->load->view('templates/superadmin_template', $data);
    }  
    public function addPlanCategory($id = '') {
        if (isset($_POST['submit'])) {
            $this->form_validation->set_rules('plan_id', 'plan', 'trim|required');
            $this->form_validation->set_rules('title', 'category name', 'trim|required');
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if ($this->form_validation->run() == TRUE) {
                $insertData = array();
                if ($this->input->post('plan_id')) $insertData['plan_id'] = $this->input->post('plan_id');
                if ($this->input->post('title'))   $insertData['title']   = $this->input->post('title');
                if ($this->input->post('id')) {
                    $this->common_model->update('service_plan_category', $insertData, array('id' => $this->input->post('id')));
                    $this->session->set_flashdata('msg_success', 'Plan Category is updated successfully');
                    redirect(ADMIN_URL.'servicePlan/planCategory');
                } else {
                    $this->common_model->insert('service_plan_category', $insertData);
                    $this->session->set_flashdata('msg_success', 'Plan Category is added successfully');
                    redirect(ADMIN_URL . 'servicePlan/addPlanCategory');
                }
            }
        }
        $data['title'] = 'Add Plan Category';
        if (!empty($id)) {
            $data['title'] = 'Edit Plan Category';
            $data['row']   = $this->common_model->get_row('service_plan_category', array('id' => $id));
        }
        $data['plans']    = $this->common_model->get_result('service_plan', array('status' => 1));
        $data['template'] = 'superadmin/servicePlan/addPlanCategory';
        $this->load->view('templates/superadmin_template', $data);
    }
    /*user list with filters*/
    public function planSubCategory() {
        $data   = array();
        $config = admin_pagination();
        $config['enable_query_strings'] = TRUE;
        if (!empty($_SERVER['QUERY_STRING'])) {
            $config['suffix'] = "?" . $_SERVER['QUERY_STRING'];
        } else {
            $config['suffix'] = '';
        }
        $config['base_url']         = base_url() . "superadmin/servicePlan/planSubCategory/";
        $counts                     = $this->developer_model->get_plan_type(0, 0);
        $config['total_rows']       = $counts;
        $config['per_page']         = PER_PAGE;
        $config['uri_segment']      = 4;
        $config['use_page_numbers'] = TRUE;
        $config['first_url']        = $config['base_url'] . $config['suffix'];
        $pageNo = $this->uri->segment(4);        
        $this->pagination->initialize($config);        
        $offSet = 0;
        if ($pageNo) {
            $offSet = $config['per_page'] * ($pageNo - 1);
        }
        $data['pagination'] = $this->pagination->create_links();
        $data['rows']       = $this->developer_model->get_plan_type($offSet, PER_PAGE);
        $data['offset']     = $offSet;
        $data['plans']     = $this->common_model->get_result('service_plan', array('status' => 1));
        if($this->input->get('plan_id')){
            $data['categorys']  = $this->common_model->get_result('service_plan_category', array('status' => 1, 'plan_id'=>$this->input->get('plan_id')));
        }else{
            $data['categorys']  = $this->common_model->get_result('service_plan_category', array('status' => 1));
        }
        $data['template']   = 'superadmin/servicePlan/planSubCategory';
        $this->load->view('templates/superadmin_template', $data);
    }    
    /*superadmin dashboard*/
    public function addPlanSubCategory($id = '') {
        $type = " sub category"; 
        if (isset($_POST['submit'])) {
            $this->form_validation->set_rules('plan_id', 'plan', 'trim|required');
            $this->form_validation->set_rules('category_id', 'category', 'trim|required');
            $this->form_validation->set_rules('title', 'sub sategory', 'trim|required');
            if (!empty($_FILES['user_img']['name'])){
              $this->form_validation->set_rules('user_img','','callback_user_image_check');
            }
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if ($this->form_validation->run() == TRUE) {
                $insertData = array();
                if ($this->input->post('plan_id')) 
                    $insertData['plan_id'] = $this->input->post('plan_id');
                if ($this->input->post('category_id')) 
                    $insertData['category_id'] = $this->input->post('category_id');
                if ($this->input->post('title'))   
                    $insertData['title']   = $this->input->post('title');
                if($this->session->userdata('user_image_check')!=''){        
                    $user_image_check=$this->session->userdata('user_image_check');
                    $insertData['itemPic'] = $user_image_check['user_img'];    
                    $this->session->unset_userdata('user_image_check');       
                }
                if ($this->input->post('id')) {
                    $this->common_model->update('service_plan_item', $insertData, array('id' => $this->input->post('id')));
                    $this->session->set_flashdata('msg_success', $type.' is updated successfully');
                    redirect(ADMIN_URL.'servicePlan/planSubCategory');
                } else {
                    $this->common_model->insert('service_plan_item', $insertData);
                    $this->session->set_flashdata('msg_success', $type.' is added successfully');
                    redirect(ADMIN_URL . 'servicePlan/addPlanSubCategory');
                }
            }
        }
        $data['plans']      = $this->common_model->get_result('service_plan', array('status' => 1));
        $data['categorys']  = $this->common_model->get_result('service_plan_category', array('status' => 1));
        $data['title'] = 'Add '.$type;
        if (!empty($id)) {
            $data['title'] = 'Edit '.$type;
            $data['row']   = $this->common_model->get_row('service_plan_item', array('id' => $id));
            if(!empty($data['row']->plan_id)){
                $data['categorys']  = $this->common_model->get_result('service_plan_category', array('status' => 1,'plan_id'=>$data['row']->plan_id));
            }
        }
        
        $data['template']   = 'superadmin/servicePlan/addPlanSubCategory';
        $this->load->view('templates/superadmin_template', $data);
    }    
    public function getCategorys() {
        if($this->input->get('plan_id')){
            $categorys  = $this->common_model->get_result('service_plan_category', array('status' => 1, 'plan_id'=>$this->input->get('plan_id'))); 
            if(!empty($categorys)){
                echo '<option value="">All Category</option>';
                foreach($categorys as $category){
                    if($this->input->get('category_id')&&$this->input->get('category_id')==$category->id){
                        echo '<option selected value="'.$category->id.'">'.ucfirst($category->title).'</option>';
                    }else{
                        echo '<option value="'.$category->id.'">'.ucfirst($category->title).'</option>';
                    }
                }
            }
        }
    }
    public function getSubCategorys() {
        echo '<option value="">Select Sub Category</option>';
        if($this->input->get('category_id')){
            $subcategorys  = $this->common_model->get_result('service_plan_item', array('status' => 1, 'category_id'=>$this->input->get('category_id'))); 
            if(!empty($subcategorys)){
                foreach($subcategorys as $subcategory){
                    if($this->input->get('subcategory_id')&&$this->input->get('subcategory_id')==$subcategory->id){
                        echo '<option selected value="'.$subcategory->id.'">'.ucfirst($subcategory->title).'</option>';
                    }else{
                        echo '<option value="'.$subcategory->id.'">'.ucfirst($subcategory->title).'</option>';
                    }
                }
            }
        }
    }
    /*user list with filters*/
    public function workout_exercise() {
        $data = array();
        $config = admin_pagination();
        $config['enable_query_strings'] = TRUE;
        if (!empty($_SERVER['QUERY_STRING'])) {
            $config['suffix'] = "?" . $_SERVER['QUERY_STRING'];
        } else {
            $config['suffix'] = '';
        }
        $config['base_url']         = base_url() . "superadmin/servicePlan/workout_exercise/";
        $counts                     = $this->developer_model->get_service_plan_user_exercise(0, 0);
        $config['total_rows']       = $counts;
        $config['per_page']         = PER_PAGE;
        $config['uri_segment']      = 4;
        $config['use_page_numbers'] = TRUE;
        $config['first_url']        = $config['base_url'] . $config['suffix'];
        $pageNo = $this->uri->segment(4);        
        $this->pagination->initialize($config);        
        $offSet = 0;
        if ($pageNo) {
            $offSet = $config['per_page'] * ($pageNo - 1);
        }
        $data['pagination'] = $this->pagination->create_links();
        $data['rows']       = $this->developer_model->get_service_plan_user_exercise($offSet, PER_PAGE);
        $data['offset']     = $offSet;
        $data['categorys']  = $this->common_model->get_result('service_plan_category', array('status' => 1, 'plan_id'=>2));
        $data['template']   = 'superadmin/servicePlan/workout_exercise';
        $this->load->view('templates/superadmin_template', $data);
    }    
    /*superadmin dashboard*/
    public function add_workout_exercise($id = '') {
        $type = "workout exercise"; 
        if (isset($_POST['submit'])) {
            $this->form_validation->set_rules('category_id', 'category', 'trim|required');
            $this->form_validation->set_rules('sub_category_id', 'sub category', 'trim|required');
            $this->form_validation->set_rules('exercise_title', 'exercise title', 'trim|required');
            $this->form_validation->set_rules('cacalories', 'cacalories', 'trim|required|numeric');
            $this->form_validation->set_rules('exercise_details', 'exercise details', 'trim');
            $this->form_validation->set_rules('exercise_instruction', 'exercise instruction', 'trim');
            $this->form_validation->set_rules('measureUnit', 'measure unit', 'trim|required');
            if (!empty($_FILES['user_img']['name'])){
                $this->form_validation->set_rules('user_img','','callback_plansExercise_check');
            }
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if ($this->form_validation->run() == TRUE) {
                $insertData = array();
                if ($this->input->post('category_id')) 
                    $insertData['category_id'] = $this->input->post('category_id');
                if ($this->input->post('sub_category_id')) 
                    $insertData['sub_category_id'] = $this->input->post('sub_category_id');
                if ($this->input->post('exercise_title')) 
                    $insertData['exercise_title'] = $this->input->post('exercise_title');
                if ($this->input->post('cacalories')) 
                    $insertData['cacalories'] = $this->input->post('cacalories');
                if ($this->input->post('exercise_details'))   
                    $insertData['exercise_details']   = $this->input->post('exercise_details');
                if ($this->input->post('exercise_instruction'))   
                    $insertData['exercise_instruction']   = $this->input->post('exercise_instruction');
                if ($this->input->post('measureUnit'))   
                    $insertData['measureUnit']   = $this->input->post('measureUnit');
                if($this->session->userdata('plansExercise_check')!=''){        
                    $user_image_check=$this->session->userdata('plansExercise_check');
                    $insertData['exercise_pic'] = $user_image_check['user_img'];    
                    $this->session->unset_userdata('plansExercise_check');       
                }
                if ($this->input->post('id')) {
                    $this->common_model->update('service_plan_user_exercise', $insertData, array('id' => $this->input->post('id')));
                    $this->session->set_flashdata('msg_success', $type.' is updated successfully');
                    redirect(ADMIN_URL.'servicePlan/workout_exercise');
                } else {
                    $this->common_model->insert('service_plan_user_exercise', $insertData);
                    $this->session->set_flashdata('msg_success', $type.' is added successfully');
                    redirect(ADMIN_URL . 'servicePlan/add_workout_exercise');
                }
            }
        }
        $data['title'] = 'Add '.$type;
        if (!empty($id)) {
            $data['title']  = 'Edit '.$type;
            $data['row']    = $this->common_model->get_row('service_plan_user_exercise', array('id' => $id));
        }
        $data['categorys']  = $this->common_model->get_result('service_plan_category', array('status' => 1, 'plan_id'=>2));
        $data['template']   = 'superadmin/servicePlan/add_workout_exercise';
        $this->load->view('templates/superadmin_template', $data);
    }
    /*user list with filters*/
    public function diet_plan_food() {
        $data = array();
        $config = admin_pagination();
        $config['enable_query_strings'] = TRUE;
        if (!empty($_SERVER['QUERY_STRING'])) {
            $config['suffix'] = "?" . $_SERVER['QUERY_STRING'];
        } else {
            $config['suffix'] = '';
        }
        $config['base_url']         = base_url() . "servicePlan/diet_plan_food/";
        $counts                     = $this->developer_model->get_service_plan_diet_items(0, 0);
        $config['total_rows']       = $counts;
        $config['per_page']         = PER_PAGE;
        $config['uri_segment']      = 4;
        $config['use_page_numbers'] = TRUE;
        $config['first_url']        = $config['base_url'] . $config['suffix'];
        $pageNo = $this->uri->segment(4);        
        $this->pagination->initialize($config);        
        $offSet = 0;
        if ($pageNo) {
            $offSet = $config['per_page'] * ($pageNo - 1);
        }
        $data['pagination'] = $this->pagination->create_links();
        $data['rows']       = $this->developer_model->get_service_plan_diet_items($offSet, PER_PAGE);
        $data['offset']     = $offSet;
        $data['categorys']  = $this->common_model->get_result('service_plan_category', array('status' => 1, 'plan_id'=>1));
        $data['template']   = 'superadmin/servicePlan/diet_plan_food';
        $this->load->view('templates/superadmin_template', $data);
    }    
    /*superadmin dashboard*/
    public function add_diet_plan_food($id = '') {
        $type = "diet food"; 
        if (isset($_POST['submit'])) {
            $this->form_validation->set_rules('category_id', 'category', 'trim|required');
            $this->form_validation->set_rules('sub_category_id', 'sub category', 'trim|required');
            $this->form_validation->set_rules('item_title', 'item', 'trim|required');
            $this->form_validation->set_rules('cacalories', 'cacalories', 'trim|required|numeric');
            $this->form_validation->set_rules('protein', 'protein', 'trim|numeric');
            $this->form_validation->set_rules('fat', 'fat', 'trim|numeric');
            $this->form_validation->set_rules('carbohydrate', 'carbohydrate', 'trim|numeric');
            $this->form_validation->set_rules('suger', 'suger', 'trim|numeric');
            $this->form_validation->set_rules('fiber', 'fiber', 'trim');
            $this->form_validation->set_rules('description', 'description', 'trim');
            $this->form_validation->set_rules('preparation', 'preparation', 'trim');
            $this->form_validation->set_rules('healthiness', 'healthiness', 'trim');
            if (!empty($_FILES['user_img']['name'])){
                $this->form_validation->set_rules('user_img','','callback_user_image_check');
            }
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if ($this->form_validation->run() == TRUE) {
                $insertData = array();
                if ($this->input->post('category_id')) 
                    $insertData['category_id'] = $this->input->post('category_id');
                if ($this->input->post('sub_category_id'))   
                    $insertData['sub_category_id']   = $this->input->post('sub_category_id');
                if ($this->input->post('item_title'))   
                    $insertData['item_title']   = $this->input->post('item_title');
                if ($this->input->post('cacalories'))   
                    $insertData['cacalories']   = $this->input->post('cacalories');
                if ($this->input->post('protein'))   
                    $insertData['protein']   = $this->input->post('protein');
                if ($this->input->post('fat'))   
                    $insertData['fat']   = $this->input->post('fat');
                if ($this->input->post('carbohydrate'))   
                    $insertData['carbohydrate']   = $this->input->post('carbohydrate');
                if ($this->input->post('fiber'))   
                    $insertData['fiber']   = $this->input->post('fiber');
                if ($this->input->post('suger'))   
                    $insertData['suger']   = $this->input->post('suger');
                if ($this->input->post('description'))   
                    $insertData['description']   = $this->input->post('description');
                if ($this->input->post('preparation'))   
                    $insertData['preparation']   = $this->input->post('preparation');
                if ($this->input->post('healthiness'))   
                    $insertData['healthiness']   = $this->input->post('healthiness');
                if($this->session->userdata('user_image_check')!=''){        
                    $user_image_check=$this->session->userdata('user_image_check');
                    $insertData['exercise_pic'] = $user_image_check['user_img'];    
                    $this->session->unset_userdata('user_image_check');       
                }
                if ($this->input->post('id')) {
                    $this->common_model->update('service_plan_diet_items', $insertData, array('id' => $this->input->post('id')));
                    $this->session->set_flashdata('msg_success', 'Diet Item is updated successfully');
                    redirect(ADMIN_URL.'servicePlan/diet_plan_food');
                } else {
                    $this->common_model->insert('service_plan_diet_items', $insertData);
                    $this->session->set_flashdata('msg_success', 'Diet Item is added successfully');
                    redirect(ADMIN_URL . 'servicePlan/add_diet_plan_food');
                }
            }
        }        
        $data['title']      = 'Add '.$type;
        if (!empty($id)) {
            $data['title']  = 'Edit '.$type;
            $data['row']    = $this->common_model->get_row('service_plan_diet_items', array('id' => $id));
        }
        $data['categorys']    = $this->common_model->get_result('service_plan_category', array('status' => 1, 'plan_id'=>1));
        $data['template']   = 'superadmin/servicePlan/add_diet_plan_food';
        $this->load->view('templates/superadmin_template', $data);
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
        if ($image[0] < 30 || $image[1] < 30) {
            $this->form_validation->set_message('user_image_check', 'Oops! Your logo needs to be atleast 30 x 30 pixels');
            return FALSE;
        }
        if ($image[0] > 3000 || $image[1] > 3000) {
            $this->form_validation->set_message('user_image_check', 'Oops! Your logo needs to be maximum of 3000 x 3000 pixels');
            return FALSE;
        }
        if(!empty($_FILES['user_img']['name'])):
            $config['encrypt_name'] = TRUE;
              $new_name = 'image_'.substr(md5(rand()),0,7).$_FILES["user_img"]['name'];
            $config['file_name'] = $new_name;
            $config['upload_path'] = 'assets/uploads/planItem/';
            $config['allowed_types'] = 'jpeg|jpg|png';
            $config['max_size']  = '5024';
            $config['max_width']  = '3000';
            $config['max_height']  = '3000';
            $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('user_img')){
            $this->form_validation->set_message('user_image_check', $this->upload->display_errors());
            return FALSE;
        }
        else{
            $data = $this->upload->data(); // upload image
            $config_img_p['source_path'] = 'assets/uploads/planItem/';
            $config_img_p['destination_path'] = 'assets/uploads/planItem/thumbnails/';
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
    public function plansExercise_check($str){
        $allowed = array("image/jpeg", "image/jpg", "image/png");       
        if(empty($_FILES['user_img']['name'])){
            $this->form_validation->set_message('plansExercise_check', 'Choose logo');
            return FALSE;
         }
        if(!in_array($_FILES['user_img']['type'], $allowed)) {        
            $this->form_validation->set_message('plansExercise_check', 'Only jpg, jpeg, and png files are allowed');
            return FALSE;
        }
        $image = getimagesize($_FILES['user_img']['tmp_name']);
        if ($image[0] < 30 || $image[1] < 30) {
            $this->form_validation->set_message('plansExercise_check', 'Oops! Your logo needs to be atleast 30 x 30 pixels');
            return FALSE;
        }
        if ($image[0] > 3000 || $image[1] > 3000) {
            $this->form_validation->set_message('plansExercise_check', 'Oops! Your logo needs to be maximum of 3000 x 3000 pixels');
            return FALSE;
        }
        if(!empty($_FILES['user_img']['name'])):
            $config['encrypt_name'] = TRUE;
              $new_name = 'image_'.substr(md5(rand()),0,7).$_FILES["user_img"]['name'];
            $config['file_name'] = $new_name;
            $config['upload_path'] = 'assets/uploads/plansExercise/';
            $config['allowed_types'] = 'jpeg|jpg|png';
            $config['max_size']  = '5024';
            $config['max_width']  = '3000';
            $config['max_height']  = '3000';
            $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('user_img')){
            $this->form_validation->set_message('plansExercise_check', $this->upload->display_errors());
            return FALSE;
        }
        else{
            $data = $this->upload->data(); // upload image
            $config_img_p['source_path'] = 'assets/uploads/plansExercise/';
            $config_img_p['destination_path'] = 'assets/uploads/plansExercise/thumbnails/';
            $config_img_p['width']  = '30';
            $config_img_p['height']  = '30';
            $config_img_p['file_name'] =$data['file_name'];
            $status=create_thumbnail($config_img_p);
            $this->session->set_userdata('plansExercise_check',array('image_url'=>$config['upload_path'].$data['file_name'],
               'user_img'=>$data['file_name']));
            return TRUE;
        }else:
            $this->form_validation->set_message('plansExercise_check', 'The %s field required.');
            return FALSE;
        endif;
    }
}