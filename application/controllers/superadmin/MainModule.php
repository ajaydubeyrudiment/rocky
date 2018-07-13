<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*this controller for making manage all users in superadmin */

class MainModule extends CI_Controller {

	public function __construct(){

	    parent::__construct();  

	    clear_cache();  	

		$this->load->model('common_model');	

	    /*if(!superadmin_logged_in()){

	     	redirect('login');

	    } */	          

	}	

	/*user list with filters*/	

	function index(){		

		$data                           = array();   		

        $config                         = frontend_pagination();

        $config['enable_query_strings'] = TRUE;            

        if(!empty($_SERVER['QUERY_STRING'])){

	     	$config['suffix'] = "?".$_SERVER['QUERY_STRING'];

	    }else{

	    	$config['suffix'] = '';

	    }       

        $config['base_url']         = base_url()."superadmin/customer/index/";

        $counts                     = $this->common_model->getCustomer(0, 0);

        $config['total_rows']       = $counts;

        $config['per_page']         = PER_PAGE;        

        $config['uri_segment']      = 4;           

        $config['use_page_numbers'] = TRUE;  

        $config['first_url'] 		= $config['base_url'].$config['suffix'];          

        $pageNo                     = $this->uri->segment(4);

        $offSet                     = 0;

        if($pageNo){

            $offSet   = $config['per_page']*($pageNo-1);

        }

        $this->pagination->initialize($config);

        $data['offset']     = $offSet;

        $data['pagination'] = $this->pagination->create_links();

       

        $data['users']      = $this->common_model->getCustomer($offSet, PER_PAGE);        

        $data['template']='superadmin/customer/customer';

	    $this->load->view('templates/superadmin_template',$data);		

	}	
	/*user list with filters*/
	public function add_module(){
        $data['template']   = 'superadmin/module/add_module';
	    $this->load->view('templates/superadmin_template',$data);
	}
	public function module_list(){
        $data['template']   = 'superadmin/module/module_list';
	    $this->load->view('templates/superadmin_template',$data);
	}
	public function changeStatus($id='', $status=''){

	    if(preg_match('/^\d+$/', $id)){ 

	      $update = $this->common_model->update('users', array('status'=>$status), array('id'=> $id));            

	      if($update){             

	        if($status ==2){

	          $this->session->set_flashdata('msg_success','Customer is suspended successfully');

	        }elseif($status ==1){

	          $this->session->set_flashdata('msg_success','Customer is activated successfully');

	        }

	    } 

	     	redirect($_SERVER['HTTP_REFERER']);            

	    }else{

	      	redirect('superadmin/superadmin/error_404');

	    }

  } 

	/*delete users*/

	public function deleteUser($id=''){

		if(preg_match('/^\d+$/', $id)){	 

            $update = $this->common_model->update('customer', array('isDeleted'=>1), array('id'=> $id));	           

            if($update){

            	$this->session->set_flashdata('msg_success', 'Customer is deleted successfully');

	         } 

	         redirect('superadmin/customer');         		

        }else{

           redirect('superadmin/superadmin/error_404');

        }

	}

	/*validations funcation check email id exists*/

    public function email_check($str=''){

	  if($this->common_model->get_row('sks_user',array('email'=>$str))):

		    $this->form_validation->set_message('email_check', 'The %s already exists');

		       return FALSE;

		else:

	       return TRUE;

	    endif;

	}

	/*check mobile no exists*/

	public function mobileNoCheck($new){

        if ($this->common_model->get_row('sks_user', array('mobile'=>$new))){ 

            $this->form_validation->set_message('mobileNoCheck','This mobile  no. already exists');

            return FALSE;

        } else {

            return TRUE; 

        } 

    }

	/*validation funcation end*/

	
}