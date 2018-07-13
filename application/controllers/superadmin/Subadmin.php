<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*this controller for making manage all Student in superadmin */
class Subadmin extends CI_Controller {
    public function __construct() {
        parent::__construct();
        clear_cache();
        $this->load->model('common_model');
        if (!superadmin_logged_in()) {
            redirect('superadmin/login');
        }
    }
    /*user list with filters*/
    function index() {
        $data                           = array();
        $config                         = admin_pagination();
        $config['enable_query_strings'] = TRUE;
        if (!empty($_SERVER['QUERY_STRING'])) {
            $config['suffix'] = "?" . $_SERVER['QUERY_STRING'];
        } else {
            $config['suffix'] = '';
        }
        $config['base_url'] = base_url() . "superadmin/subadmin/index/";
        $counts = $this->developer_model->getSubadmin(0, 0);
        //echo $counts  ;
        //echo $this->db->last_query();	 exit();
        $config['total_rows'] = $counts;
        $config['per_page'] = PER_PAGE;
        $config['uri_segment'] = 4;
        $config['use_page_numbers'] = TRUE;
        $config['first_url'] = $config['base_url'] . $config['suffix'];
        $pageNo = $this->uri->segment(4);
        $offSet = 0;
        if ($pageNo) {
            $offSet = $config['per_page'] * ($pageNo - 1);
        }
        $this->pagination->initialize($config);
        $data['offset'] = $offSet;
        $data['pagination'] = $this->pagination->create_links();
        $data['users'] = $this->developer_model->getSubadmin($offSet, PER_PAGE);
        //echo $this->db->last_query();	 exit();
        $data['template'] = 'superadmin/subadmin/subadmin_list';
        $this->load->view('templates/superadmin_template', $data);
    }
    /*superadmin dashboard*/
    public function addSubadmin($id=''){      
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
        if(empty($id)){
            $this->form_validation->set_rules('password', 'new password', 'trim|required|min_length[6]');
            $this->form_validation->set_rules('confirm_password','confirm password', 'trim|required|matches[password]');
        }
        if (!empty($_FILES['user_img']['name'])){
            $this->form_validation->set_rules('user_img','','callback_user_image_check');
        }
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run() == TRUE){
            $user_data  = array();
            if($this->session->userdata('user_image_check')!=''){        
                $user_image_check=$this->session->userdata('user_image_check');
                $user_data['image'] = $user_image_check['user_img'];    
                $this->session->unset_userdata('user_image_check');       
            }
            $user_data['first_name'] =  $this->input->post('first_name');
            $user_data['last_name']  =  $this->input->post('last_name');
            $user_data['email']      =  $this->input->post('email');   
            if($this->input->post('modules')){
                $user_data['modules']    =  implode(',', $this->input->post('modules'));   
            }
             $user_data['user_role'] = 3;       
            if(!empty($id)){
                if($this->common_model->update('admin_users', $user_data, array('id'=>$id))){
                    $this->session->set_flashdata('msg_success','Subadmin is  updated successfully');
                    redirect(ADMIN_URL.'subadmin?module_id=41&main_module=40');
                }else{
                    $this->session->set_flashdata('msg_error','Update failed, Please try again');
                    redirect($_SERVER['HTTP_REFERER']); 
                }
            }else{
                $salt = salt();
                $user_data['password']  = sha1($salt.sha1($salt.sha1($this->input->post('password'))));    
                $user_data['salt']      = $salt;         
                $user_data['user_role'] = 3;         
                if($this->common_model->insert('admin_users', $user_data)){
                    $this->session->set_flashdata('msg_success','Subadmin is  added successfully');
                    redirect($_SERVER['HTTP_REFERER']); 
                }else{
                    $this->session->set_flashdata('msg_error','Update failed, Please try again');
                    redirect($_SERVER['HTTP_REFERER']); 
                }

            }         
        } 
        $data['type']  = 'Add Subadmin';
        if(!empty($id)){
            $data['type']  = 'Edit Subadmin';
            $data['user'] = $this->common_model->get_row('admin_users', array('id'=>$id));
        }
        $data['template'] ='superadmin/subadmin/add_subadmin';       
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
}
