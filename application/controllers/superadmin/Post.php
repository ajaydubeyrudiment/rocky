<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*this controller for making manage all users in superadmin */
class Post extends CI_Controller {
    public function __construct() {
        parent::__construct();
        clear_cache();
        if(!superadmin_logged_in()){
            redirect(base_url().ADMIN_DIR.'login');
        }
    }
    /*user list with filters*/
    function index() {
        $data   = array();
        $config = admin_pagination();
        $config['enable_query_strings'] = TRUE;
        if (!empty($_SERVER['QUERY_STRING'])) {
            $config['suffix'] = "?" . $_SERVER['QUERY_STRING'];
        } else {
            $config['suffix'] = '';
        }
        $config['base_url']         = base_url() . "superadmin/post/index/";
        $counts                     = $this->developer_model->getPost(0, 0);
        $config['total_rows']       = $counts;
        $config['per_page']         = PER_PAGE;
        $config['uri_segment']      = 4;
        $config['use_page_numbers'] = TRUE;
        $config['first_url']        = $config['base_url'] . $config['suffix'];
        $pageNo = $this->uri->segment(4);
        $offSet = 0;
        if ($pageNo) {
            $offSet = $config['per_page'] * ($pageNo - 1);
        }
        $this->pagination->initialize($config);
        $data['offset']     = $offSet;
        $data['pagination'] = $this->pagination->create_links();
        $data['rows']       = $this->developer_model->getPost($offSet, PER_PAGE);
        $data['template']   = 'superadmin/post/post';
        $this->load->view('templates/superadmin_template', $data);
    }
    public function postDetail(){
        $data['row']     = $this->developer_model->getPostDetails();
        $data['images']  = $this->common_model->get_result('images', array('meta_id'=>$this->input->get('post_id')));
        $this->load->view('superadmin/post/postDetail', $data);
    }
    public function postActivilty(){
        $data['rows'] = $this->developer_model->postActivilty();
        $this->load->view('superadmin/post/postActivilty', $data);
    }
}