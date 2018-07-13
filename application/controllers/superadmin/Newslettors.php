<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*this controller for making manage conatact us in  superadmin */

class Newslettors extends CI_Controller {

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

    $config['base_url']    = base_url().'superadmin/newslettors/index';

    $config['total_rows']  = $this->common_model->newslettor_model(0,0);

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

       redirect('superadmin/newslettors/index/0');

    }

    $config['first_url']  = $config['base_url'].$config['suffix'];

    $this->pagination->initialize($config);    

    if($pageNo){

            $offset   = $config['per_page']*($pageNo-1);

    }

    $data['offset']   = $offset;

    $data['contacts'] = $this->common_model->newslettor_model($offset,$per_page);

    $data['pagination']   = $this->pagination->create_links(); 

    $data['template']     = 'superadmin/newslettors/index';

    $this->load->view('templates/superadmin_template', $data);

 }

}