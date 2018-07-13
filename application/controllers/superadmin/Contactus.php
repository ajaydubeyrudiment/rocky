<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*this controller for making manage conatact us in  superadmin */
class Contactus extends CI_Controller {
  	public function __construct(){
  	    parent::__construct();  
  	     clear_cache();         
        $this->load->model('common_model');        
  	}
  /*check login superadmin*/
   private function _check_login(){
		  if(superadmin_logged_in()===FALSE)
			redirect(base_url().ADMIN_DIR.'login');
	}
  /*show all contact us messages*/ 
  public function index($offset=0){
    $this->_check_login(); //check login authentication
    $data['title']='Contact Us';    
    $per_page = PER_PAGE;   
    $pageNo                = $this->uri->segment(4); 
    $config                = frontend_pagination();
    $config['base_url']    = base_url().'superadmin/contactus/index';
    $config['total_rows']  = $this->common_model->contactus_model(0,0);
    $config['per_page']    = $per_page;
    $config['uri_segment'] = 4;
    $config['use_page_numbers'] = TRUE;
    if(!empty($_SERVER['QUERY_STRING'])){
      $config['suffix']     = "?".$_SERVER['QUERY_STRING'];
    }else{
      $config['suffix']     ='';
    }
    $data['total_records']        = $config['total_rows'];
    if($config['total_rows'] < $offset){
       $this->session->set_flashdata('msg_warning','Something went wrong ..! Please check it ');    
       redirect('superadmin/contactus/index/0');
    }
    $config['first_url']  = $config['base_url'].$config['suffix'];
    $this->pagination->initialize($config);    
    if($pageNo){
            $offset   = $config['per_page']*($pageNo-1);
    }
    $data['offset']   = $offset;
    $data['contacts'] = $this->common_model->contactus_model($offset,$per_page);
    $data['pagination']   = $this->pagination->create_links(); 
    $data['template']     = 'superadmin/contactus/index';
    $this->load->view('templates/superadmin_template', $data);
 }
  /*reply contact messages*/  
  public function contactus_reply($id=''){  
      if($id == ''){ redirect('superadmin/contactus/index/0'); }
        if(preg_match('/^\d+$/', $id)){ 
          $data['title']    = 'Edit membership';
          $data['contacts'] = $this->common_model->get_result('contact_us',array('id' => $id));
          if(empty($data['contacts'])){
              redirect('superadmin/superadmin/error_404');
          }
          $this->common_model->update('contact_us',array('read_status'=>1),array('id' => $id));
          $this->form_validation->set_rules('message', 'Message', 'required');
         
          $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

          if ($this->form_validation->run() == TRUE)
          {   
            $data_insert['reply'] = $data['contacts'][0]->reply." ".$this->input->post('message');
            $data_insert['status'] = 1;       
            $this->common_model->update('contact_us',$data_insert,$array = array('id' => $id));
             /*  mail function start */
              /*$this->load->library('cimail_email');
              $email_template = $this->cimail_email->get_email_template(8);
              $param=array(
                'template'  =>  array(
                        'temp'  =>  $email_template->template_body,
                        'var_name'  =>  array(
                                'username'  => $data['contacts'][0]->name,
                                'site_name' =>  SITE_NAME,
                                'reply' => $this->input->post('message')
                                     
                                ), 
                ),      
                'email' =>  array(
                    'to'    =>   $data['contacts'][0]->email,
                    'from'    =>   NO_REPLY,
                    'from_name' =>   NO_REPLY_EMAIL_FROM_NAME,
                    'subject' =>   $email_template->template_subject,
                  )
                );  
                $status=$this->cimail_email->send_mail($param);*/
               /*  mail function end */ 
              $this->session->set_flashdata('msg_success','Reply successfully.');
              redirect('superadmin/contactus/contactus_reply/'.$id);
          }
          $data['template']='superadmin/contactus/reply';
          $this->load->view('templates/superadmin_template',$data);
      }else{
        redirect('superadmin/superadmin/error_404');
      }
    }
}