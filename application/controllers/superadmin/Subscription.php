<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*this controller for making manage all users in superadmin */
class Subscription  extends CI_Controller {
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
        $config['base_url']         = base_url() . "subscription/index/";
        $counts                     = $this->developer_model->getPlan(0, 0);
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
        $data['rows']       = $this->developer_model->getPlan($offSet, PER_PAGE);
        $data['offset']     = $offSet;
        $data['template']   = 'superadmin/Plan/plan';
        $this->load->view('templates/superadmin_template', $data);
    }
    /*superadmin dashboard*/
    public function addSubscription($id = '') {
        if (isset($_POST['submit'])) {
            //print_r($_POST); exit();
            $this->form_validation->set_rules('plan_title', 'subscription title', 'trim|required');
            $this->form_validation->set_rules('amount', 'amount', 'trim|required|numeric');
            $this->form_validation->set_rules('days', 'days', 'trim|required|numeric');
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if ($this->form_validation->run() == TRUE) {
                $insertData = array();
                if ($this->input->post('amount'))     $insertData['amount']     = $this->input->post('amount');
                if ($this->input->post('plan_title')) $insertData['plan_title'] = $this->input->post('plan_title');
                if ($this->input->post('days'))       $insertData['days']       = $this->input->post('days');
                if ($this->input->post('id')) {
                    $this->common_model->update('plans', $insertData, array('id' => $this->input->post('id')));
                    $this->session->set_flashdata('msg_success', 'Subscription is updated successfully');
                    redirect(ADMIN_URL.'subscription');
                } else {
                    $this->common_model->insert('plans', $insertData);
                    $this->session->set_flashdata('msg_success', 'Subscription is added successfully');
                    redirect(ADMIN_URL . 'subscription/addSubscription');
                }
            }
        }
        $data['title'] = 'Add Subscription';
        if (!empty($id)) {
            $data['title'] = 'Edit Subscription';
            $data['row'] = $this->common_model->get_row('plans', array('id' => $id));
        }
        $data['template'] = 'superadmin/Plan/addPlan';
        $this->load->view('templates/superadmin_template', $data);
    }    
}