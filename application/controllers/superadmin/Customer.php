<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*this controller for making manage all users in superadmin */

class Customer extends CI_Controller {

	public function __construct(){
	    parent::__construct();  
	    clear_cache();  	
		$this->load->model('common_model');	
	    if(!superadmin_logged_in()){
	     	redirect(base_url().ADMIN_DIR.'login');
	    }
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

        $counts                     = $this->developer_model->getCustomer(0, 0);
        //echo $counts  ;
        //echo $this->db->last_query();	 exit(); 	

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

        $data['users']      = $this->developer_model->getCustomer($offSet, PER_PAGE);
        //echo $this->db->last_query();	 exit(); 
        if($this->input->get('module_id')){		    
	    	$data['filters']  	= $this->common_model->get_result('ad_module_filter', array('module_id'=>$this->input->get('module_id')), array(), array('order_priority','ASC'));		      
		  }	        

        $data['template']='superadmin/customer/customer';

	    $this->load->view('templates/superadmin_template',$data);		

	}
	function influencers(){		

		$data                           = array();   		

        $config                         = frontend_pagination();

        $config['enable_query_strings'] = TRUE;            

        if(!empty($_SERVER['QUERY_STRING'])){

	     	$config['suffix'] = "?".$_SERVER['QUERY_STRING'];

	    }else{

	    	$config['suffix'] = '';

	    }       

        $config['base_url']         = base_url()."superadmin/customer/influencers/";

        $counts                     = $this->developer_model->getInfluencers(0, 0);
        //echo $counts  ;
        //echo $this->db->last_query();	 exit(); 	

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

        $data['users']      = $this->developer_model->getInfluencers($offSet, PER_PAGE);
        //echo $this->db->last_query();	 exit(); 
        if($this->input->get('module_id')){		    
	    	$data['filters']  	= $this->common_model->get_result('ad_module_filter', array('module_id'=>$this->input->get('module_id')), array(), array('order_priority','ASC'));		      
		}	        

        $data['template']='superadmin/customer/influencers';

	    $this->load->view('templates/superadmin_template',$data);		

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
	/*user list with filters*/
	function customer_details(){
		$data  = array();
		if($this->input->get('customer_id')){
			$data['row'] 		=  $this->developer_model->getUserDetails($this->input->get('customer_id'));
			$data['userPlans']  =  $this->developer_model->getUserPlans($this->input->get('customer_id'));
		}       
	    $this->load->view('superadmin/customer/customerDetails', $data);
	}
}