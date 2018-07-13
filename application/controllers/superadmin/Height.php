<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*this controller for making manage all users in superadmin */
class Height extends CI_Controller {
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
        $config['base_url']         = base_url() . "height/index/";
        $counts                     = $this->developer_model->getHeight(0, 0);
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
        $data['rows']       = $this->developer_model->getHeight($offSet, PER_PAGE);
        $data['offset']     = $offSet;
        $data['template']   = 'superadmin/height/height';
        $this->load->view('templates/superadmin_template', $data);
    }
    /*superadmin dashboard*/
    public function addHeight($id = '') {
        if (isset($_POST['submit'])) {
            //print_r($_POST); exit();
            $this->form_validation->set_rules('hieght_cm', 'hieght  cm', 'trim|required');
            $this->form_validation->set_rules('height_title', 'height feet & inch', 'trim|required');
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if ($this->form_validation->run() == TRUE) {
                $insertData = array();
                if ($this->input->post('hieght_cm')) $insertData['hieght_cm'] = $this->input->post('hieght_cm');
                if ($this->input->post('height_title')) $insertData['height_title'] = $this->input->post('height_title');
                if ($this->input->post('id')) {
                    $this->common_model->update('height', $insertData, array('id' => $this->input->post('id')));
                    $this->session->set_flashdata('msg_success', 'Height is updated successfully');
                    redirect(ADMIN_URL.'height');
                } else {
                    $this->common_model->insert('height', $insertData);
                    $this->session->set_flashdata('msg_success', 'Height is added successfully');
                    redirect(ADMIN_URL . 'height/addHeight');
                }
            }
        }
        $data['title'] = 'Add Height';
        if (!empty($id)) {
            $data['title'] = 'Edit Height';
            $data['row'] = $this->common_model->get_row('height', array('id' => $id));
        }
        $data['template'] = 'superadmin/height/addHeight';
        $this->load->view('templates/superadmin_template', $data);
    }    
}