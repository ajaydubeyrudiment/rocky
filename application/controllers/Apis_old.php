<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';  

class Apis extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('api_model');
        $this->load->model('common_model');
        $_POST=$this->post();
        $this->load->library('form_validation');

    }  
   
    //Login Services
    public function create_login_user_post(){    
        $responseCode = 500;
        $message        = "Invalid Request.";
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|exact_length[10]');
        $this->form_validation->set_rules('device_id', 'Device ID', 'trim|required');
        $this->form_validation->set_rules('device_type', 'Device Type', 'trim|required');
        $this->form_validation->set_rules('os_version', 'OS Version', 'trim|required');
        $this->form_validation->set_rules('app_version', 'App Version', 'trim|required');
        $this->form_validation->set_rules('push_token', 'push token', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email'); 
        $this->form_validation->set_rules('first_name', 'First name', 'trim|max_length[50]');
         $this->form_validation->set_rules('last_name', 'Last name', 'trim|max_length[50]');
        if($this->form_validation->run() == TRUE){
            $mobile         = $this->input->post('mobile')!=''?$this->input->post('mobile'):'';
            $device_id      = $this->input->post('device_id')!=''?$this->input->post('device_id'):'';
            $device_type    = $this->input->post('device_type')!=''?$this->input->post('device_type'):'';
            $os_version     = $this->input->post('os_version')!=''?$this->input->post('os_version'):'';
            $app_version    = $this->input->post('app_version')!=''?$this->input->post('app_version'):'';
            $push_token     = $this->input->post('push_token')!=''?$this->input->post('push_token'):'';
            $first_name     = $this->input->post('first_name')!=''?$this->input->post('first_name'):'';
            $last_name      = $this->input->post('last_name')!=''?$this->input->post('last_name'):'';
            $email          = $this->input->post('email')!=''?$this->input->post('email'):'';
            if($mobile!='' && $device_id!='' && $device_type!= '' && $os_version!='' && $app_version!='' && $push_token!=''){
                $detail= array(
                        'device_id'     => $device_id,
                        'device_type'   => $device_type,
                        'os_version'    => $os_version, 
                        'app_version'   => $app_version, 
                        'push_token'    => $push_token,
                        'updated'       => date('Y-m-d h:i:s'),
                    );
                if($user_info = $this->api_model->get_row('users',array('mobile'=>$mobile))){  
                    $this->api_model->update('users',$detail,array('mobile'=>$mobile));   
                    $userdata = array (
                            'user_id' => $user_info->id,
                            'first_name' => $user_info->first_name,
                            'last_name' => $user_info->last_name,
                            'email'  => $user_info->email,
                            'mobile' => $user_info->mobile,
                            'push_token' => $push_token,
                            'os_version' => $os_version,
                            'app_version' => $app_version,
                            'device_id'  => $device_id,
                            'device_type' => $device_type

                        );  
                    $responseCode = 200;
                    $message      = 'You have been login successfully and your detail update successfully';
                   

                }else{ 
                    $detail['mobile']= $mobile;
                    $detail['first_name']= $first_name;
                    $detail['last_name']= $last_name;
                    $detail['email']= $email;
                    unset( $detail['updated']);
                    $detail['created']= date('Y-m-d h:i:s');
                    if($this->api_model->insert('users',$detail)) {
                        $user_info = $this->api_model->get_row('users',array('mobile'=>$mobile)); 
                        $userdata = array(
                              'user_id'    => $user_info->id,
                              'first_name' => $user_info->first_name,
                              'last_name'  => $user_info->last_name,
                              'email'      => $user_info->email,
                              'mobile'     => $user_info->mobile,
                              'push_token' => $user_info->push_token,
                              'os_version' => $user_info->os_version,
                              'app_version' => $user_info->app_version,
                              'device_id'  => $user_info->device_id,
                              'device_type' => $user_info->device_type                            
                        );  
                        $responseCode = 200;
                        $message      = 'Your account has been created and login successfully with this account';    

                    }else{
                        $message      = 'Your account creation failed, please provide correct details.'; 
                    }
                }
            }else{
                $message = "Mobile Number, Device id , Device type, os version, App version and Push token is reqiured";
            }
        }
        $data = array( 
            'ResponseCode' => $responseCode,
            'message'   => $message);

        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;

        if($responseCode == 200){
            $data['Data']   = $userdata;
        }
        $this->response($data);  
    }
    // get the list of boards
    public function test_data_post() {
        $user_id    = '';
        $responseCode   = 500;
        $message    = 'Invalid Request';
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        $this->form_validation->set_rules('ecoadmin_id', 'Ecoadmin Id', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            if($this->post('user_id')!='' && $this->post('ecoadmin_id')>0){
                $data['ResponseCode'] = 200;
                $user_id    = $this->post('user_id');
                $ecoadmin_id    = $this->post('ecoadmin_id');
                $boarddata  = $this->api_model->getBoardDetails($user_id,$ecoadmin_id);

                if($boarddata){
                    $data['Data'] = $boarddata;
                    $data['Message'] = "Boards details fetch successfully";
                }else{
                    $data['ResponseCode'] = 500;
                    $data['Message'] = "Boards record not found";
                }
                $this->response($data);
            }else{
                 $data = array( 
                'ResponseCode' => $responseCode,
                'message'   => $message);
            }
        }
        $data = array( 
            'ResponseCode' => $responseCode,
            'message'   => $message);
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;
        $this->response($data); // OK (200) being the HTTP response code        
    }    
    // get the list of boards
    public function boards_list_post() {
        $user_id    = '';
        $responseCode   = 500;
        $message    = 'Invalid Request';
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        $this->form_validation->set_rules('ecoadmin_id', 'Ecoadmin Id', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            if($this->post('user_id')!='' && $this->post('ecoadmin_id')>0){
                $data['ResponseCode'] = 200;
                $user_id    = $this->post('user_id');
                $ecoadmin_id    = $this->post('ecoadmin_id');
                $boarddata  = $this->api_model->getBoardDetails($user_id,$ecoadmin_id);

                if($boarddata){
                    $data['Data'] = $boarddata;
                    $data['Message'] = "Boards details fetch successfully";
                }else{
                    $data['ResponseCode'] = 500;
                    $data['Message'] = "Boards record not found";
                }
                $this->response($data);
            }else{
                 $data = array( 
                'ResponseCode' => $responseCode,
                'message'   => $message);
            }
        }
        $data = array( 
            'ResponseCode' => $responseCode,
            'message'   => $message);
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;
        $this->response($data); // OK (200) being the HTTP response code        
    } 
    // create baord
    public function board_create_post() { 

        
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        $this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('description', 'Description', 'trim|required|max_length[300]');
        if($this->form_validation->run() == TRUE){ 
            $user_id = $this->post('user_id'); 
            $title = $this->post('title'); 
            if($user_id && $title) {

                $detail= array( 
                    'title'    => $title,
                    'user_owner_id' => $user_id,
                    'admins'   => '#'.$user_id.'#',
                    'boards_users' => '#'.$user_id.'#',
                    'created_date' => date('Y-m-d h:i:s'),
                    'status' => 1,
                    'description' => $this->post('description'),
                    
                );                
                if($this->post('board_icon')) {

                   $detail['board_icon'] = 'assets/boardIcons/'.$this->post('board_icon');
                }else{
                     $detail['board_icon'] = 'assets/boardIcons/Icon_1.png';
                }

                if($board_id = $this->api_model->insert('boards',$detail)) { 
                    $boardCode = getBoardCode($board_id); 
                    $this->api_model->update('boards', array('board_code' =>$boardCode),array('id' =>$board_id));

                   $boardData = $this->api_model->get_row('boards',array("id" => $board_id,'status'=>1),array('id,title,description,board_code,board_icon'));
                    if(!empty($boardData)){
               
                        $result = array(
                                    'board_id'=> $boardData->id,
                                    'title' => $boardData->title,
                                    'description'=> $boardData->description,
                                    'board_code'=> $boardData->board_code,
                                    'board_icon'=> base_url().$boardData->board_icon,
                                );
                        $data = array( 
                        'ResponseCode' => 200,
                        'Data'=> $result,
                        'message'=>"Board has been created successfully", 
                        );  
                    }else{
                             $data = array( 
                            'ResponseCode' => 500,
                            'message'=>"Board status  check, Please try again.", 
                        );
                    } 
                                             
                
                } else {

                    $data = array( 
                        'ResponseCode' => 500,
                        'message'=>"For creation of board, Please try again.", 
                    );
                }  

            } else { 

               $data = array( 
                    'ResponseCode' => 500,
                    'message'=>"Invalid request.", 
                ); 
            } 
        }else{
            $data = array( 
                    'ResponseCode' => 500,
                    'message'=>"Invalid request.", 
                ); 
        }      
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;
        $this->response($data);   
    }

    //Boardicon list
    function board_icon_list_get(){
        $boarddata  = $this->api_model->get_result('board_icon',array('status'=>1),array('id,boardPath'));
        if($boarddata){
            $result = array();
            if(!empty($boarddata))
            {
                foreach($boarddata as $res)
                {
                    $result[] = array(
                                    'board_icon_id'=> $res->id,
                                    'board_icon_path' => base_url().$res->boardPath,
                                );
                } 
            }
            $data['ResponseCode'] = 200;
            $data['Data'] = $result;
            $data['Message'] = "Boards details fetch successfully";
        }else{
            $data['ResponseCode'] = 500;
            $data['Message'] = "Boards record not found";
        }
        $this->response($data);
            
    }
    //Board info fetch
    public function fetch_board_info_post() {  
        $board_id = $this->post('board_id'); 
        $this->form_validation->set_rules('board_id', 'Board Id', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            if($board_id) {  
                if($info = $this->api_model->board_info($board_id)) {

                    $data = array( 
                        'ResponseCode' => 200,
                        'Data'=>$info, 
                    );            
                }else {
                    $data = array( 
                        'ResponseCode' => 500,
                        'Message'=>"Broad id details not exists and also check status, Please try again.", 
                    );
                }
            } else {
                $data = array( 
                    'ResponseCode' => 500,
                    'message'=>"Invalid request.", 
                );
            } 
        }else{
             $data = array( 
                    'ResponseCode' => 500,
                    'message'=>"Invalid request.", 
                );
        } 
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;      
        $this->response($data); 
    }
    //share boardcode
    public function share_boardcode_post(){
        $user_id = '';
        $boardcode = '';
        $data['ResponseCode'] = 500;
        $message      = "Invalid request.";  
        $this->form_validation->set_rules('board_id', 'Board Id', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            $boardId  = $this->post('board_id');
            $boardData = $this->api_model->get_row('boards',array("id" => $boardId,'status'=>1),array('id,title,description,board_code,board_icon'));
            if(!empty($boardData)){
               
                $result = array(
                                    'board_id'=> $boardData->id,
                                    'title' => $boardData->title,
                                    'description'=> $boardData->description,
                                    'board_code'=> $boardData->board_code,
                                    'board_icon'=> base_url().$boardData->board_icon,
                                );
                   
                $data['ResponseCode'] = 200;
                $data['Data'] = $result;
                $data['message']  = "Board details share successfully.";
            }else{
                $data['message']  = "Invalid Board id and also check status, please try again to share board.";
            }
           
        }    

        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ; 
        $this->response($data);
    }
    //join board
    public function join_board_post(){
        $user_id = '';
        $boardcode = '';
        $responseCode = 500;
        $message      = "Invalid request.";  
       
        
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        $this->form_validation->set_rules('board_code', 'Board code', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
           
            $boardcode   = $this->post('board_code');
            $user_id    = '#'.$this->post('user_id').'#';
            $boardData = $this->api_model->get_row('boards',array("board_code" => $boardcode, 'status'=>1),array('boards_users,id,owner_id'));
            if(!empty($boardData)){
                $CheckuserData = $this->api_model->get_row('users',array("id" => $this->post('user_id')),array('id'));
                if(!empty($CheckuserData))
                {
                    $BoardusersArr = explode(',', ($boardData->boards_users));
                    if(!in_array($user_id, $BoardusersArr)){
                        $BoardusersArr[count($BoardusersArr)] = $user_id;
                        $listeners = implode(',',$BoardusersArr);
                        if(!empty($boardData->owner_id))
                        {
                            $check_resul = $this->api_model->checkuserEcoadmin($this->post('user_id'),$boardData->owner_id);
                            //echo $this->db->last_query();
                            //print_r($check_resul);
                            if(!empty($check_resul))
                            {
                                $this->api_model->update('boards',array('boards_users'=>$listeners),array('id'=>$boardData->id));
                                $message        = "User successfully joined in this board.";

                            }else{
                                $message        = "You can not join this user becauser user not belong to the ecoadmin";
                            }   
                        }else{
                            $this->api_model->update('boards',array('boards_users'=>$listeners),array('id'=>$boardData->id));
                            $responseCode   = 200;
                            $message        = "User successfully joined in board.";
                        }   
                            //echo $this->db->last_query(); 
                    }
                    else{

                        $message        = "User exists in this board.";
                    }
                }else{
                    $message        = "User Id not exits";
                }   
            }else{
                $message        = "Invalid Board Code and status check, please try again to join in board.";
            }
           
        }    
        $data = array( 
            'ResponseCode' => $responseCode,
            'message'=>  $message, 
        );
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ; 
        $this->response($data);
    }
    //make admin in boards 2 add admin, 1 remove admin
    function make_remove_admin_post(){
        $user_id = '';
        $board_id = '';
        $data['ResponseCode'] = 500;
        $message      = "Invalid request.";  
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        $this->form_validation->set_rules('board_id', 'Board Id', 'trim|required');
        $this->form_validation->set_rules('member_id', 'Member Id', 'trim|required');
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            $userId  = $this->post('user_id');
            $boardId  = $this->post('board_id');
            $member_id  = $this->post('member_id');
            $type  = $this->post('type');
            $boardInfo = $this->api_model->get_row('boards',array('id' =>$boardId, 'user_owner_id' =>$userId));
            //echo $this->db->last_query();
            $admins ='';
            if(!empty($boardInfo))
            {
                if($type =='2'){
                    if(!empty($member_id)){
                        if($boardInfo->admins){
                            $users    = explode(",", $boardInfo->admins);
                            $member_id   = array("#".$member_id."#");
                            $usersNews  = array_diff($users, $member_id);
                            if(!empty($usersNews)){
                                $admins = implode(',', $usersNews);
                            }
                        }
                    }
                 }elseif($type =='1'){
                    if(!empty($member_id)){
                        $admins = "#".$member_id."#";
                        if($boardInfo->admins){
                            $users  = explode(",", $boardInfo->admins);
                            if(!in_array("#".$member_id."#", $users)){
                                $users[] = "#".$member_id."#";
                            }
                            $admins = implode(',', $users);
                        }
                    }                
                }
                $update  = $this->api_model->update('boards',array('admins' =>$admins),array('id'=>$boardId));  
                if($type==1){
                    $data['ResponseCode'] = 200;
                    $data['message']  = "Member added as admin successfully";
                }elseif($type =='2'){
                    $data['ResponseCode'] = 200;
                    $data['message']  = "Member removed from admin role successfully";  
                }else{
                    $data['message']  = "Member not update";
                }
            }else{
                $data['message']  = "Please check board id and user id";
            }    
           
        }    
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ; 
        $this->response($data);
    }
    //remove members form boards
    function remove_member_post(){
        $user_id = '';
        $board_id = '';
        $data['ResponseCode'] = 500;
        $message      = "Invalid request.";  
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        $this->form_validation->set_rules('board_id', 'Board Id', 'trim|required');
        $this->form_validation->set_rules('member_id', 'Member Id', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            $userId  = $this->post('user_id');
            $boardId  = $this->post('board_id');
            $member_id  = $this->post('member_id');
            $boardInfo = $this->api_model->get_row('boards',array('id' =>$boardId, 'user_owner_id' =>$userId));
            $members ='';
            if(!empty($boardInfo))
            {
                
                if(!empty($member_id)){
                    if($boardInfo->boards_users){
                        $users    = explode(",", $boardInfo->boards_users);
                        $member_id   = array("#".$member_id."#");
                        $usersNews  = array_diff($users, $member_id);
                        if(!empty($usersNews)){
                            $members = implode(',', $usersNews);
                        }
                    }
                }
                $update  = $this->api_model->update('boards',array('boards_users' =>$members),array('id'=>$boardId));  
                $data['ResponseCode'] = 200;
                $data['message']  = "Member removed successfully";
               
            }else{
                $data['message']  = "Please check board id and user id";
            }    
           
        }    
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ; 
        $this->response($data);
    }
    //Boardcast boardlist
    public function boardcaster_boardlist_post(){
        $user_id = '';
        $boardcode = '';
        $data['ResponseCode'] = 500;
        $message      = "Invalid request.";  
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            $userId  = $this->post('user_id');
            $boardList = $this->api_model->boardcaster_boardlist($userId);
            //echo $this->db->last_query();
            if(!empty($boardList)){
                $data['ResponseCode'] = 200;
                $data['Data'] = $boardList;
                $data['message']  = "Boardcaster board list get successfully";
            }else{
                $data['message']  = "Boardcaster board list not found";
            }
           
        }    
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ; 
        $this->response($data);
    }
    //user information
    public function user_information_post(){
        $user_id = '';
        $boardcode = '';
        $data['ResponseCode'] = 500;
        $message      = "Invalid request.";  
        $this->form_validation->set_rules('user_id', 'User id', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            $user_id  = $this->post('user_id');
            $userData = $this->api_model->get_row('users',array("id" => $user_id));
            if(!empty($userData)){
                $data['ResponseCode'] = 200;
                $data['Data'] = $userData;
                $data['message']  = "User details fetch successfully.";
            }else{
                $data['message']  = "User is not exists";
            }
           
        }    

        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ; 
        $this->response($data);
    }
    //user profile update
    public function user_update_post() {  
        $user_id     = ''; 
        $responseCode   = 500;
        $message        = "Invalid request";
        $detail= array();
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        $this->form_validation->set_rules('first_name', 'First name', 'trim|max_length[50]');
        $this->form_validation->set_rules('last_name', 'Last name', 'trim|max_length[50]');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email'); 
        //$this->form_validation->set_rules('gender', 'Gender', 'trim|required'); 
        if($this->form_validation->run() == TRUE){ 
            if($this->post('first_name')!=''){
                $detail['first_name']   = $this->post('first_name');             
            }
            if($this->post('last_name')!=''){
                $detail['last_name']   = $this->post('last_name');
            }
            if($this->post('email')!=''){
                $detail['email']   = $this->post('email');
            }
            if($this->post('gender')!=''){
                $detail['gender']   = $this->post('gender');
            }
            if($this->post('user_id')!=''){
                $user_id= $this->post('user_id'); 
            }
            if($user_id!=''&& (count($detail)>0)) { 
                if($this->api_model->update('users',$detail,array('id'=>$user_id))) {
                    $responseCode   = 200; 
                    $message    = "Profile update successfully";  
                }else{
                    $message    = "Profile is not update, please try again";
                }        
            } 
        }    
        $data   = array( 
                'ResponseCode'  => $responseCode,
                'message'       => $message, 
            ); 
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;
        $this->response($data);
    }
    // post a boardcast  
    public function post_boardcast_post() {
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('board_id[]', 'Board Id', 'trim|required');

        if($this->form_validation->run() == TRUE){ 
            $board_id = array();
            $boardcast_media_id = array();
            $board_id = $this->post('board_id');
            $markImportant = $this->post('mark_important');
            $user_id = $this->post('user_id');  
            $description = $this->post('description'); 
            $title = $this->post('title');   
            $boardcast_media_id = $this->post('boardcast_media_id');
            $scheduled_date = $this->post('scheduled_date');

            if($board_id && $user_id && $description) {
                $detail= array( 
                    'description'=> $description,
                    'boardcast_user_id'=> $user_id,
                    'created_date' => date('Y-m-d h:i:s'),
                    'title' => $title,
                    'type'=>1,

                );
                if(!empty($markImportant) && $markImportant ==1)
                {
                	 $detail['mark_important'] = $markImportant;
                }
                if(!empty($scheduled_date))
                {
                    $detail['scheduled_date'] = $scheduled_date;
                }
                //if(!empty($boardcast_media_id))
                //{
                    //$detail['boardcast_media_id'] = implode(',',$boardcast_media_id); 
                //}  
                $boardcaster_id ='';
                $detail['board_id'] = implode(',',$board_id);
                $boardcaster_id =$this->api_model->insert('boardcast',$detail);
                if(!empty($boardcast_media_id))
                {

                    foreach($boardcast_media_id as $boardcast_media)
                    {
                        
                            $this->api_model->update('boardcast_media',array('boardcast_id'=>$boardcaster_id),array('media_id'=>$boardcast_media,'boardcast_user_id'=>$user_id));

                        
                    }
                }
                if($boardcaster_id) {  
                    $data = array( 
                        'ResponseCode' => 200,
                        'data' => $boardcaster_id,
                        'message'=>"Post a Boardcast has been created successfully", 
                    ); 
                } else {
                    $data = array( 
                        'ResponseCode' => 500,
                        'message'=>"For post a boardcast, Please try again.", 
                    );
                }

            } else {
                $data = array( 
                    'ResponseCode' => 500,
                    'message'=>"Invalid request.", 
                );
            } 
        }else{
            $data = array( 
                    'ResponseCode' => 500,
                    'message'=>"Invalid request.", 
                ); 
        }      
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;     

        $this->response($data); 
    }
    //edit_boardcast
    public function edit_scheduled_boardcast_post() {
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('board_id[]', 'Board Id', 'trim|required');
        $this->form_validation->set_rules('boardcast_id', 'Boardcast Id', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            $board_id = array();
            $boardcast_media_id = array();
            $board_id = $this->post('board_id');
            $markImportant = $this->post('mark_important');
            $boardcaster_id = $this->post('boardcast_id');  
            $description = $this->post('description'); 
            $title = $this->post('title');   
            $boardcast_media_id = $this->post('boardcast_media_id');
            $scheduled_date = $this->post('scheduled_date');
            if($board_id  && $description) {
                $detail= array( 
                    'description'=> $description,
                    'title' => $title,
                );
                if(!empty($markImportant) && $markImportant ==1)
                {
                     $detail['mark_important'] = $markImportant;
                }
                if(!empty($scheduled_date))
                {
                    $detail['scheduled_date'] = $scheduled_date;
                }
                //if(!empty($boardcast_media_id))
                //{
                    //$detail['boardcast_media_id'] = implode(',',$boardcast_media_id); 
                //}  
                $detail['board_id'] = implode(',',$board_id);
                $this->api_model->update('boardcast',$detail,array('boardcast_id'=>$boardcaster_id));
                if(!empty($boardcast_media_id))
                {

                    foreach($boardcast_media_id as $boardcast_media)
                    {
                        
                            $this->api_model->update('boardcast_media',array('boardcast_id'=>$boardcaster_id),array('media_id'=>$boardcast_media,'boardcast_user_id'=>$user_id));

                        
                    }
                }
                if($boardcaster_id) {  
                    $data = array( 
                        'ResponseCode' => 200,
                        'data' => $boardcaster_id,
                        'message'=>"Boardcast edited successfully", 
                    ); 
                } else {
                    $data = array( 
                        'ResponseCode' => 500,
                        'message'=>"Boardcast not edited, Please try again.", 
                    );
                }

            } else {
                $data = array( 
                    'ResponseCode' => 500,
                    'message'=>"Invalid request.", 
                );
            } 
        }else{
            $data = array( 
                    'ResponseCode' => 500,
                    'message'=>"Invalid request.", 
                ); 
        }      
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;     

        $this->response($data); 
    }
    function unlink_boardcast_media_post(){
        $user_id    = '';
        $responseCode   = 500;
        $message    = 'Invalid Request';
        $this->form_validation->set_rules('boardcast_media_id', 'Boardcast media Id', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
           
            $boardcast_media_id    = $this->post('boardcast_media_id');
            $checkData = $this->api_model->get_row('boardcast_media',array("media_id" => $boardcast_media_id));
            if(!empty($checkData))
            {
                $imageUrl = $checkData->file_path;
                $imageUrlth = 'assets/uploads/app_users/thumbnails/'.$checkData->file_name;
                if(file_exists($imageUrl))
                {
                    unlink($imageUrl);
                }
                if(file_exists($imageUrlth))
                {
                   
                    unlink($imageUrlth);
                }    
                $this->api_model->delete('boardcast_media',array('media_id'=>$boardcast_media_id));
                $data['ResponseCode'] = 200;
                $data['Message'] = "Boardcast media id deleted successfully";
            }else{
                 $data['ResponseCode'] = 500;
                $data['Message'] = "Boardcast media id not exist";

            }          
            $this->response($data);
           
        }
        $data = array( 
            'ResponseCode' => $responseCode,
            'message'   => $message);
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;
        $this->response($data); // OK (200) being the HTTP respo 
    }
    // create activity post
    public function create_activity_post() { 

        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('board_id[]', 'Board Id', 'trim|required');

        if($this->form_validation->run() == TRUE){ 
            $board_id = array();
            $boardcast_media_id = array();
            $board_id = $this->post('board_id');
            $user_id = $this->post('user_id');  
            $description = $this->post('description'); 
            $title = $this->post('title');   
            $boardcast_media_id = $this->post('boardcast_media_id');
            $markImportant = $this->post('mark_important');
            $scheduled_date = $this->post('scheduled_date');
            $option = $this->post('option');
            if($board_id && $user_id && $description) {
                $detail= array( 
                    'description'=> $description,
                    'boardcast_user_id'=> $user_id,
                    'created_date' => date('Y-m-d h:i:s'),
                    'title' => $title,
                    'type'=>2, 
                );
                //if(!empty($boardcast_media_id))
                //{
                    //$detail['boardcast_media_id'] = implode(',',$boardcast_media_id); 
                //}
                if(!empty($markImportant) && $markImportant ==1)
                {
                     $detail['mark_important'] = $markImportant;
                }
                if(!empty($scheduled_date))
                {
                    $detail['scheduled_date'] = $scheduled_date;
                }  
                $boardcaster_id ='';
               
                $detail['board_id'] = implode(',',$board_id);
                $boardcaster_id =$this->api_model->insert('boardcast',$detail);
                if(!empty($boardcast_media_id))
                {

                    foreach($boardcast_media_id as $boardcast_media)
                    {
                        
                            $this->api_model->update('boardcast_media',array('boardcast_id'=>$boardcaster_id),array('media_id'=>$boardcast_media,'boardcast_user_id'=>$user_id));

                        
                    }
                }

                if(!empty($option))
                {

                    foreach($option as $opt)
                    {
                            $option_detail = array('option'=>$opt,'boardcast_id'=>$boardcaster_id);
                            $activityOptionData = $this->api_model->get_row('activity_option',$option_detail); 
                            if(empty($activityOptionData)){
                                $this->api_model->insert('activity_option',$option_detail);
                            }    
                    }
                }
                if($boardcaster_id) {  
                    $data = array( 
                        'ResponseCode' => 200,
                        'data' => $boardcaster_id,
                        'message'=>"Activity has been created successfully", 
                    ); 
                } else {
                    $data = array( 
                        'ResponseCode' => 500,
                        'message'=>"For create an activity, Please try again.", 
                    );
                }

            } else {
                $data = array( 
                    'ResponseCode' => 500,
                    'message'=>"Invalid request.", 
                );
            } 
        }else{
            $data = array( 
                    'ResponseCode' => 500,
                    'message'=>"Invalid request.", 
                ); 
        }      
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;     

        $this->response($data); 
       
    }
    // Edit activity post
    public function edit_scheduled_activity_post() { 

        $this->form_validation->set_rules('boardcast_id', 'Boardcast Id', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('board_id[]', 'Board Id', 'trim|required');

        if($this->form_validation->run() == TRUE){ 
            $board_id = array();
            $boardcast_media_id = array();
            $board_id = $this->post('board_id');
            $boardcaster_id = $this->post('boardcast_id');  
            $description = $this->post('description'); 
            $title = $this->post('title');   
            $boardcast_media_id = $this->post('boardcast_media_id');
            $markImportant = $this->post('mark_important');
            $scheduled_date = $this->post('scheduled_date');
            $option = $this->post('option');
            if($board_id &&  $description) {
                $detail= array( 
                    'description'=> $description,
                    'title' => $title,
                    
                );
                //if(!empty($boardcast_media_id))
                //{
                    //$detail['boardcast_media_id'] = implode(',',$boardcast_media_id); 
                //}
                if(!empty($markImportant) && $markImportant ==1)
                {
                     $detail['mark_important'] = $markImportant;
                }else{
                    $detail['mark_important'] = 0;

                }
                if(!empty($scheduled_date))
                {
                    $detail['scheduled_date'] = $scheduled_date;
                }  
                $detail['board_id'] = implode(',',$board_id);
                $this->api_model->update('boardcast',$detail,array('boardcast_id'=>$boardcaster_id));
                if(!empty($boardcast_media_id))
                {

                    foreach($boardcast_media_id as $boardcast_media)
                    {
                        
                        $this->api_model->update('boardcast_media',array('boardcast_id'=>$boardcaster_id),array('media_id'=>$boardcast_media,'boardcast_user_id'=>$user_id)); 
                    }
                }

                if(!empty($option))
                {
                    $activityOptioncheck = $this->api_model->get_row('activity_option',array('boardcast_id'=>$boardcaster_id)); 
                    if($activityOptioncheck)
                      $this->api_model->delete('activity_option',array('boardcast_id'=>$boardcaster_id));
                    foreach($option as $opt)
                    {
                            $option_detail = array('option'=>$opt,'boardcast_id'=>$boardcaster_id);
                            $activityOptionData = $this->api_model->get_row('activity_option',$option_detail); 
                            if(empty($activityOptionData)){
                                $this->api_model->insert('activity_option',$option_detail);
                            }    
                    }
                }
                if($boardcaster_id) {  
                    $data = array( 
                        'ResponseCode' => 200,
                        'data' => $boardcaster_id,
                        'message'=>"Activity has been update successfully", 
                    ); 
                } else {
                    $data = array( 
                        'ResponseCode' => 500,
                        'message'=>"For update an activity, Please try again.", 
                    );
                }

            } else {
                $data = array( 
                    'ResponseCode' => 500,
                    'message'=>"Invalid request.", 
                );
            } 
        }else{
            $data = array( 
                    'ResponseCode' => 500,
                    'message'=>"Invalid request.", 
                ); 
        }      
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;     

        $this->response($data); 
       
    }
    //select activity option
    public function activity_option_post() {  
        $user_id     = ''; 
        $responseCode   = 500;
        $message        = "Invalid request";
        $detail= array();
        $this->form_validation->set_rules('boardcast_id', 'Boardcast Id', 'trim|required');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        $this->form_validation->set_rules('option_ans_id', 'Activity Option Answer', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            $detail['option_id']   = $this->post('option_ans_id');
            $boardcast_id = $this->post('boardcast_id');
            $user_id = $this->post('user_id');
            if($boardcast_id !=''&& $boardcast_id>0 && $user_id>0) {
                $boardcastData = $this->api_model->get_row('boardcast',array("boardcast_id" => $boardcast_id,'isDeleted'=>0),array('type'));
                if(!empty($boardcastData) && $boardcastData->type == 2){
                    $activityData = $this->api_model->get_row('activity_answers',array("boardcast_id" => $boardcast_id,'user_id'=>$user_id)); 
                    if(!empty($activityData)){
                        $this->api_model->update('activity_answers',$detail,array('boardcast_id'=>$boardcast_id,'user_id'=>$user_id));
                        $responseCode   = 200; 
                        $message    = "Activity answered update successfully";  
                    }else{
                        $detail['user_id']   = $user_id;
                        $detail['boardcast_id'] =  $boardcast_id;
                        $responseCode   = 200; 
                        $this->api_model->insert('activity_answers',$detail);
                        $message    = "Activity answered inserted successfully";
                    } 
                }else{
                    $message    = "Boardcast id not belongs to activity, please try again";
                }           
            } 
        }    
        $data   = array( 
                'ResponseCode'  => $responseCode,
                'message'       => $message, 
            ); 
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;
        $this->response($data);
    } 
    //file upload  
    public function file_upload_post() { 
        $user_id = $this->post('user_id');
        //print_r($_FILES['file']);
        //exit;
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required'); 
        if($this->form_validation->run() == TRUE){   
            if(!empty($_FILES['file'])) { 
                $user_id = $this->post('user_id'); 
                $config['upload_path'] = './assets/uploads/app_users/';
                $config["allowed_types"] ="*";
                $new_name = explode('.', $_FILES['file']['name']);
                $config['file_name']  =   $new_name[0].'_'.date('YmdHis');
                //echo $new_name[1];
                //exit;
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('file')){
                        $error = array('error' => $this->upload->display_errors());
                        $data = array( 
                            'ResponseCode' => 500,
                            'Data'  =>    $error, 
                        );
                }else{
                        $info = array('upload_data' => $this->upload->data());
                        if(!empty($new_name[1]) && ($new_name[1] =='jpeg' || $new_name[1] =='jpg' || $new_name[1] =='gif' || $new_name[1] =='png')){ 
                            $config_img_p['source_path'] = './assets/uploads/app_users/';
                            $config_img_p['destination_path'] = './assets/uploads/app_users/thumbnails/';
                            $config_img_p['width']     = '256';
                            $config_img_p['height']    = '256';
                            $config_img_p['file_name'] = $info['upload_data']['file_name'];
                            create_thumbnail($config_img_p);
                        }
                        $imageUrl = 'assets/uploads/app_users/'.$info['upload_data']['file_name'];
                        $detail['file_name'] = $info['upload_data']['file_name'];
                        $detail['file_path'] = $imageUrl;
                        $detail['boardcast_user_id'] = $user_id;
                        $detail['extension'] = ltrim($info['upload_data']['file_ext'], '.');
                        if($last_id = $this->api_model->insert('boardcast_media',$detail)){
                            $info['boardcast_media_id'] = $last_id; 
                            $data = array( 
                                'ResponseCode' => 200,
                                'Data'  =>    $info, 
                            ); 
                        }else{
                            $data = array( 
                                'ResponseCode' => 500,
                                'Data'  =>    'Invalid request, Please try again.');
                        }
                }
            } else {
                $data = array( 
                    'ResponseCode' => 500,
                    'message'=>"Invalid request.", 
                );
            }

        }else{
                $data = array( 
                    'ResponseCode' => 500,
                    'message'=>"Invalid request.", 
                );
        } 
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;      
        $this->response($data); 
    }
        // get the list of boards
    public function boardcast_list_post() {
        $user_id    = '';
        $responseCode   = 500;
        $message    = 'Invalid Request';
        $this->form_validation->set_rules('board_id', 'Board Id', 'trim|required');
        //$this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            $data['ResponseCode'] = 200;
            $board_id    = $this->post('board_id');
            $user_id    = $this->post('user_id');
            $boarddata  = $this->api_model->getBoardcastDetails($board_id,$user_id);
            $boardcastcount  = $this->api_model->get_boardcast_count($board_id,$user_id);
            if($boarddata){
                $data['Data'] = $boarddata;
                if(!empty($boardcastcount))
                $data['scheduledBoardcasts'] = $boardcastcount;
                $data['Message'] = "Boardcast details fetch successfully";
            }else{
                $data['ResponseCode'] = 500;
                $data['Message'] = "Boardcast record not found";
            }
            $this->response($data);
           
        }
        $data = array( 
            'ResponseCode' => $responseCode,
            'message'   => $message);
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;
        $this->response($data); // OK (200) being the HTTP response code        
    }    

    public function fetch_boardcast_info_post() { 
        $this->form_validation->set_rules('boardcast_id', 'Boardcast Id', 'trim|required');
        if($this->form_validation->run() == TRUE){
            $boardcast_id = $this->post('boardcast_id');
            $user_id    = $this->post('user_id');  
            if(!empty($boardcast_id)) {
                if($boardcastinfo = $this->api_model->boardcast_info($boardcast_id,$user_id)) {
                   $data = array( 
                        'ResponseCode' => 200,
                        'Data'=>$boardcastinfo,
                        'Message'=>"Broadcast id detail fetch successfully.",  
                    );            
                } else {
                    $data = array( 
                        'ResponseCode' => 500,
                        'Message'=>"Broadcast id is not existing, Please try again.", 
                    );
                } 
            } else {
                $data = array( 
                    'ResponseCode' => 500,
                    'message'=>"Invalid request.", 
                );
            }
        }else{
                $data = array( 
                    'ResponseCode' => 500,
                    'message'=>"Invalid request.", 
                );
        }        
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ; 
        $this->response($data);
    }
  
    //delete boardcast
    public function delete_boardcast_post(){
        $user_id = '';
        $boardcast_id = '';
        $isImportant  = 0;
        $responseCode = 500;
        $message      = "Invalid request.";  
        $this->form_validation->set_rules('boardcast_id', 'Boardcast Id', 'trim|required');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
                $user_id    = $this->post('user_id');
                $boardcast_id   = $this->post('boardcast_id');
                $boardcastData = $this->api_model->get_row('boardcast',array('boardcast_id'=>$boardcast_id,'boardcast_user_id'=>$user_id,'isDeleted'=>0));
                //echo $this->db->last_query();
                //exit;
                if($boardcastData){
                    $this->api_model->update('boardcast',array('isDeleted'=>1),array('boardcast_id'=>$boardcast_id,'boardcast_user_id'=>$user_id));
                    $responseCode   = 200;
                    $message        = "Boardcast deleted successfully";
                }else{
                    $responseCode   = 200;
                    $message        = "Please check user_id and boardcast_id, it's not exists.";
                }

        }    
        $data = array( 
            'ResponseCode' => $responseCode,
            'message'=>  $message, 
        );
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ; 
        $this->response($data);
    }
    // get the list of scheduled boards
    public function scheduled_list_post() {
        $user_id    = '';
        $responseCode   = 500;
        $message    = 'Invalid Request';
        $this->form_validation->set_rules('board_id', 'Board Id', 'trim|required');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            $data['ResponseCode'] = 200;
            $board_id    = $this->post('board_id');
            $user_id    = $this->post('user_id');
            $boarddata  = $this->api_model->getScheduledBoardcastDetails($board_id,$user_id);
            if($boarddata){
                $data['Data'] = $boarddata;
                $data['Message'] = "Scheduled Boardcast details fetch successfully";
            }else{
                $data['ResponseCode'] = 500;
                $data['Message'] = "Scheduled Boardcast record not found";
            }
            $this->response($data);
           
        }
        $data = array( 
            'ResponseCode' => $responseCode,
            'message'   => $message);
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;
        $this->response($data); // OK (200) being the HTTP response code        
    }            
    // get the list of boardcast important
    public function important_boardcast_list_post() {
        $user_id    = '';
        $responseCode   = 500;
        $message    = 'Invalid Request';
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            $data['ResponseCode'] = 200;
            //$user_id    = $this->post('user_id');
            $user_id    = $this->post('user_id');
            $boarddata  = $this->api_model->getImportantboardcastDetails($user_id);
            if($boarddata){
                $data['Data'] = $boarddata;
                $data['Message'] = "Important Boardcast details fetch successfully";
            }else{
                $data['ResponseCode'] = 500;
                $data['Message'] = "Important Boardcast record not found";
            }
            $this->response($data);
           
        }
        $data = array( 
            'ResponseCode' => $responseCode,
            'message'   => $message);
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;
        $this->response($data); // OK (200) being the HTTP response code        
    }    
    // get board memberlist
    public function boards_member_list_post() {
        $board_id = $this->post('board_id'); 
        $this->form_validation->set_rules('board_id', 'Board Id', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            if($board_id) {  
                if($info = $this->api_model->board_member_list($board_id)) {

                    $data = array( 
                        'ResponseCode' => 200,
                        'Data'=>$info, 
                    );            
                }else {
                    $data = array( 
                        'ResponseCode' => 500,
                        'Message'=>"Broad id not exists and also check status, Please try again.", 
                    );
                }
            } else {
                $data = array( 
                    'ResponseCode' => 500,
                    'message'=>"Invalid request.", 
                );
            } 
        }else{
             $data = array( 
                    'ResponseCode' => 500,
                    'message'=>"Invalid request.", 
                );
        } 
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;      
        $this->response($data);       
    }    
    // get the list of boards according to ecoadmin
    public function ecoadmin_boards_list_post() {
        $user_id    = '';
        $responseCode   = 500;
        $message    = 'Invalid Request';
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            if($this->post('user_id')!=''){
                $data['ResponseCode'] = 200;
                $user_id    = $this->post('user_id');
                $userData   = $this->api_model->get_row('users',array('id'=>$user_id),array('ecoadmin_id,mobile,id'));
                $ParentRole = array();
                $TeacherRole = array();
                if(!empty($userData->ecoadmin_id))
                {
                   
                    $userEcoadminData = explode(',',$userData->ecoadmin_id);
                    foreach($userEcoadminData as $useEcoadmin)
                    {
                        if($this->api_model->FindParentRole($userData->mobile,$useEcoadmin))
                        {
                            $ParentRole[] = $useEcoadmin;
                        }
                    }
                    //echo '<pre>'; print_r($ParentRole);
                    foreach($userEcoadminData as $useEcoadmin)
                    {
                        if($this->api_model->FindTeacherRole($userData->id,$useEcoadmin))
                        {
                            $TeacherRole[] = $useEcoadmin;
                        }
                    }
                    //echo $this->db->last_query();
                    //echo '<pre>'; print_r($TeacherRole);
                }
                
                $result = array();
                $ecoadmin_details =  array();
                $other_boards_detail = array();
                $boards_detail = array();
                if($userData){
                    if($userData->ecoadmin_id)
                    {
                        $ecoadmin_id = explode(',',$userData->ecoadmin_id);
                        if(!empty($ecoadmin_id))
                        {
                            foreach($ecoadmin_id as $ecoadmin)
                            {
                                $new_ecoadmin = trim($ecoadmin,'#');
                                $ecoadminData   = $this->api_model->get_row('admin_users',array('id'=>$new_ecoadmin),array('id,ecosystem_title,first_name,last_name,image,splash_screen_img'));
                                if(!empty($ecoadminData))
                                {
                                    $splash_screen_path = '';
                                    if(!empty($ecoadminData->splash_screen_img))
                                    	$splash_screen_path = base_url().'assets/uploads/splash_screen/thumbnails/'.$ecoadminData->splash_screen_img;
                                    else
                                    	$splash_screen_path = base_url().'assets/admin/img/splash.jpg';
                                    $ParentRoleShow = 0;
                                    $TeacherRoleShow = 0;
                                    if(in_array($ecoadmin,$ParentRole))
                                    {
                                        $ParentRoleShow = 1;
                                    }
                                    if(in_array($ecoadmin,$TeacherRole))
                                    {
                                        $TeacherRoleShow = 1;
                                    }
                                    $ecoadmin = array(
                                        'ecoadmin_id'=> $ecoadminData->id,
                                        'full_name'=> ucfirst($ecoadminData->ecosystem_title),
                                        'logo' => base_url().'assets/uploads/users/thumbnails/'.$ecoadminData->image,
                                        'splash_screen_image'=> $splash_screen_path,
                                         'isParent' => $ParentRoleShow,
                                         'isClassAdmin'=> $TeacherRoleShow
                                            
                                            );
                                    
                                    $ecoadmin_details[] =$ecoadmin;
                                    //$result[] = array('ecoadmin_boards_detail' =>$result_board_ecoadmin);
                                     
                                }
                               
                            }
                          
                        }
                    }
                  	$result_board_other = $this->api_model->getEcoadminBoardDetails($user_id,'other');
                    if(!empty($result_board_other))
                       $other_boards_detail = $result_board_other; 
                    $result['ecoadmin_details']= $ecoadmin_details;
                    $result['other_boards_detail'] = $other_boards_detail;
                    $data['Data'] = $result;
                    $data['Message'] = "Boards details fetch successfully";
                }else{
                    $data['ResponseCode'] = 500;
                    $data['Message'] = "User id not exits.";
                }
                $this->response($data);
            }
        }
        $data = array( 
            'ResponseCode' => $responseCode,
            'message'   => $message);
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;
        $this->response($data); // OK (200) being the HTTP response code        
    }    
    //create option  
    public function create_post() { 
        $user_id = $this->post('user_id');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required'); 
        if($this->form_validation->run() == TRUE){   
            if($last_id = $this->api_model->insert('activity_option',$detail)){
                $info['boardcast_media_id'] = $last_id; 
                $data = array( 
                    'ResponseCode' => 200,
                    'Data'  =>    $info, 
                ); 
            }else{
                $data = array( 
                    'ResponseCode' => 500,
                    'Data'  =>    'Invalid request, Please try again.');
            }
                
          
        }else{
                $data = array( 
                    'ResponseCode' => 500,
                    'message'=>"Invalid request.", 
                );
        } 
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;      
        $this->response($data); 
    }
   
    // send message
    public function send_message_post(){
        $parent_chat_id = 0;
        $chat_message = '';
        $responseCode = 500;
        $receiver_id = array();
        $sender_id = '';
        $type = '';
        $chat_element_id = 0;
        $message      = "Invalid request.";  
        //$this->form_validation->set_rules('chat_id', 'Chat ID', 'trim|required');
        $this->form_validation->set_rules('sender_id', 'Sender ID', 'trim|required');
        $this->form_validation->set_rules('receiver_id[]', 'Reciver ID', 'trim|required');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $type    = $this->post('type');
        if($type == 1)
        {
            $this->form_validation->set_rules('chat_element_id', 'Ecoadmin id', 'trim|required');
        }
        if($this->form_validation->run() == TRUE){ 
        	if($this->post('parent_chat_id')){
            	$parent_chat_id    = $this->post('parent_chat_id');
        	}
            $sender_id    = $this->post('sender_id');
            $receiver_id    = $this->post('receiver_id');
            $chat_message    = $this->post('message');
            $type    = $this->post('type');
            $chat_element_id    = $this->post('chat_element_id');
            if(!empty($receiver_id) &&  $type>0 && $sender_id>0)
            {

                foreach($receiver_id as $receiver)
                {
                    $detail = array("parent_chat_id" => $parent_chat_id , "sender_id" => $sender_id,"recevier_id" => $receiver, "msg" => $chat_message,"type"=> $type,"chat_element_id"=>$chat_element_id);
                    if($this->api_model->insert('chat',$detail)){
                    	$responseCode = 200;
                		$message        = "Message sent successfully.";
            		}
            		
                }
            }
        }    
        $data = array( 
            'ResponseCode' => $responseCode,
            'message'=>  $message, 
        );
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ; 
        $this->response($data);
    }
     // chat list fetch according to user id
    public function fetch_chat_list_post(){
        $user_id = '';
        $type = '';
        $chat_element_id = '';
        $responseCode = 500;
        $message      = "Invalid request.";  
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $type    = $this->post('type');
        if($type == 1)
        {
            $this->form_validation->set_rules('chat_element_id', 'Chat element id', 'trim|required');
        }
        if($this->form_validation->run() == TRUE){ 
            $user_id    = $this->post('user_id');
            //$type    = $this->post('type');
            $chat_element_id    = $this->post('chat_element_id');
            if($type>0 && $user_id>0)
            {
	            if($chatData = $this->api_model->getChatList($user_id,$chat_element_id,$type)){
	                $responseCode = 200;
	                $data = array( 
	                    'ResponseCode' => 200,
	                    'Data'=>  $chatData, 
	                );
	            }else {
	                $message = "No record found";
	            } 
            }
            if($responseCode == 500){
                $data = array( 
                    'ResponseCode' => $responseCode,
                    'message'=>  $message, 
                );
            }
        }else{
            $data = array( 
                    'ResponseCode' => $responseCode,
                    'message'=>  $message, 
                );
        }    
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ; 
        $this->response($data);
    }    
    // chat list fetch according to chat id & pagination
    public function fetch_chat_thread_post(){
        $chat_id = '';
        $responseCode = 500;
        $message      = "Invalid request."; 
        $offset = 0; 
        $this->form_validation->set_rules('chat_id', 'Chat ID', 'trim|required');
        $this->form_validation->set_rules('per_page', 'Per Page', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
        	if($this->post('offset'))
            $offset    = $this->post('offset');
            $per_page    = $this->post('per_page');
            $chat_id    = $this->post('chat_id');
            if($chatData = $this->api_model->fetch_chat($chat_id,$offset, $per_page)){
                $responseCode = 200;
                $data = array( 
                    'ResponseCode' => 200,
                    'Data'=>  $chatData, 
                );
            }else {
                $message = "Invalid request,Please try again.";
            } 
            
            if($responseCode == 500){
                $data = array( 
                    'ResponseCode' => $responseCode,
                    'message'=>  $message, 
                );
            }
        }else{
            $data = array( 
                    'ResponseCode' => $responseCode,
                    'message'=>  $message, 
                );
        }       
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ; 
        $this->response($data);
    }
    function reply_user_setting_post(){
    	$chat_id = '';
        $responseCode = 500;
        $message      = "Invalid request."; 
        $this->form_validation->set_rules('chat_id', 'Chat ID', 'trim|required');
        $this->form_validation->set_rules('setting_value', 'Chat ID', 'trim|required');
        if($this->form_validation->run() == TRUE){
        	$chat_id    = $this->post('chat_id'); 
        	if($chat_id>0)
            {
	         
	            $setting_value    = $this->post('setting_value');
                $chatData   = $this->api_model->get_row('chat',array('chat_id'=>$chat_id),array('parent_chat_id'));
                if(!empty($chatData) && $chatData->parent_chat_id == 0)
                {
    	            $this->api_model->update('chat',array('reply_setting'=>$setting_value),array('parent_chat_id'=>0,'chat_id'=>$chat_id));
    	            $responseCode = 200;
                    $data = array( 
                        'ResponseCode' => $responseCode,
                         'message' => "Setting update successfully"
                       
                    );
                }else{
                    $responseCode = 200;
                    $data = array( 
                    'ResponseCode' => 500,
                    'message'=>  'This chat id is not parent of chat', 
                    ); 
                }    
	        }    
            
            if($responseCode == 500){
                $data = array( 
                    'ResponseCode' => $responseCode,
                    'message'=>  $message, 
                );
            }
        }else{
            $data = array( 
                    'ResponseCode' => $responseCode,
                    'message'=>  $message, 
                );
        }       
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ; 
        $this->response($data);
    }    
    //Get classes data
    function get_classes_post(){
  
        $responseCode = 500;
        $message      = "Invalid request.";  
        $this->form_validation->set_rules('ecoadmin_id', 'Ecoadmin ID', 'trim|required');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            $ecoadmin_id    = $this->post('ecoadmin_id');
            $user_id    = $this->post('user_id');
            if($ecoadmin_id >0 && $user_id>0)
            {
                if($classesData = $this->api_model->get_classes($ecoadmin_id,$user_id)){
                    $responseCode = 200;
                    $data = array( 
                        'ResponseCode' => 200,
                        'Data'=>  $classesData, 
                    );
                }else {
                    $message = "Ecoadmin id and user id not exists and check status, Please try again.";
                }
                //echo $this->db->last_query();
            }     
            if($responseCode == 500){
                $data = array( 
                    'ResponseCode' => $responseCode,
                    'message'=>  $message, 
                );
            }
        }else{
            $data = array( 
                    'ResponseCode' => $responseCode,
                    'message'=>  $message, 
                );
        }    
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ; 
        $this->response($data);
    } 
    //Get class student data
    function get_class_student_post(){

        $responseCode = 500;
        $message      = "Invalid request.";  
        $this->form_validation->set_rules('class_id', 'Class ID', 'trim|required');
        $this->form_validation->set_rules('date', 'Date', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            $class_id    = $this->post('class_id');
            $date    = $this->post('date');
            if($class_id>0)
            {
                if($classesstudentData = $this->api_model->get_class_student($class_id,$date)){
                    $responseCode = 200;
                    $data = array( 
                        'ResponseCode' => 200,
                        'Data'=>  $classesstudentData, 
                    );
                }else {
                    $message = "Class id not exists, Please try again.";
                } 
                //echo $this->db->last_query();
            }
            if($responseCode == 500){
                $data = array( 
                    'ResponseCode' => $responseCode,
                    'message'=>  $message, 
                );
            }
        }else{
            $data = array( 
                    'ResponseCode' => $responseCode,
                    'message'=>  $message, 
                );
        }    
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ; 
        $this->response($data);
    }
    function mark_attendance_post(){
    	$this->form_validation->set_rules('date', 'Date', 'trim|required');
        $this->form_validation->set_rules('class_id', 'Class id', 'trim|required');
        $this->form_validation->set_rules('student_id[]', 'Student id', 'trim|required');
        $this->form_validation->set_rules('status[]', 'Status', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            $date = $this->post('date');
            $class_id = $this->post('class_id');  
            $student = $this->post('student_id');
            $owner_id = '';
            $owner_id = $this->post('user_id'); 
            $status = $this->post('status');   
            $total_count = count($student);
            $update_info = '';
            $attendance_id = '';
            if(!empty($total_count))
            {
                for($i=0; $i<$total_count; $i++)
                {
                    
                    $con = array('attendanceDate'=>strtotime($date),'student_id'=>$student[$i],'class_id'=>$class_id);
                    $checkDateRow = '';
                    $checkDateRow   = $this->api_model->get_row('attendance',$con);
                    $detail= array('status' =>  $status[$i]);
                    if(!empty($checkDateRow))
                    {
                        $detail['updated_date'] = date('Y-m-d h:i:s');
                        if(!empty($owner_id))
                        {
                            $detail['owner_id'] = $owner_id;
                        }    
                        $update_info = $this->api_model->update('attendance',$detail,$con);
                    }else{
                        
                        $detail['attendanceDate'] = strtotime($date);
                        $detail['student_id'] = $student[$i];
                        $detail['class_id'] = $class_id;
                        $detail['owner_id'] = $owner_id;
                        $attendance_id = $this->api_model->insert('attendance',$detail);
                    }
                    //echo $this->db->last_query();
                    
                }
            }
            if($attendance_id) {  
                $data = array( 
                    'ResponseCode' => 200,
                    'message'=>"Student attendance inserted successfully.", 
                ); 
            }elseif($update_info){  
                $data = array( 
                    'ResponseCode' => 200,
                    'message'=>"Student attendance update successfully.", 
                ); 
            }else{
                $data = array( 
                    'ResponseCode' => 500,
                    'message'=>"For student attendance, Please try again.", 
                );
            }
        }else{
            $data = array( 
                    'ResponseCode' => 500,
                    'message'=>"Invalid request.", 
                ); 
        }      
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;     

        $this->response($data); 
    }
    // view student attendance
    function view_student_attendance_post(){
    	$responseCode = 500;
        $message      = "Invalid request.";  
        $this->form_validation->set_rules('student_id', 'Student ID', 'trim|required');
     	$this->form_validation->set_rules('class_id', 'Class ID', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            $student_id    = $this->post('student_id');
            $class_id    = $this->post('class_id');
            if($studentAttendanceData = $this->api_model->get_student_attendance_info($student_id,$class_id)){
                $responseCode = 200;
                $data = array( 
                    'ResponseCode' => 200,
                    'Data'=>  $studentAttendanceData, 
                );
            }else {
                $message = "Please check student id, class id and try again.";
            } 
            
            if($responseCode == 500){
                $data = array( 
                    'ResponseCode' => $responseCode,
                    'message'=>  $message, 
                );
            }
        }else{
            $data = array( 
                    'ResponseCode' => $responseCode,
                    'message'=>  $message, 
                );
        }    
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ; 
        $this->response($data);
    }
    // parents according kid list
    function parent_kids_list_post(){
    	$responseCode = 500;
        $message      = "Invalid request.";  
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        $this->form_validation->set_rules('ecoadmin_id', 'Ecoadmin ID', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            $user_id    = $this->post('user_id');
            $ecoadmin_id    = $this->post('ecoadmin_id');
            if($kidsData = $this->api_model->get_parent_kids($user_id,$ecoadmin_id)){

                $responseCode = 200;
                $data = array( 
                    'ResponseCode' => 200,
                    'Data'=>  $kidsData, 
                );
            }else {
                $message = "Please check user id and try again.";
            } 
            
            if($responseCode == 500){
                $data = array( 
                    'ResponseCode' => $responseCode,
                    'message'=>  $message, 
                );
            }
        }else{
            $data = array( 
                    'ResponseCode' => $responseCode,
                    'message'=>  $message, 
                );
        }    
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ; 
        $this->response($data);
    } 
    // classes according kid id
    function get_kids_classes_post(){
        $responseCode = 500;
        $message      = "Invalid request.";  
        $this->form_validation->set_rules('kid_id', 'Kid ID', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            $kid_id    = $this->post('kid_id');
            if($kid_id>0)
            {
                if($kidsData = $this->api_model->get_class_kids($kid_id)){
                    $responseCode = 200;
                    $data = array( 
                        'ResponseCode' => 200,
                        'Data'=>  $kidsData, 
                    );
                }else {
                    $message = "Please check user id and try again.";
                } 

            }
            if($responseCode == 500){
                $data = array( 
                    'ResponseCode' => $responseCode,
                    'message'=>  $message, 
                );
            }
        }else{
            $data = array( 
                    'ResponseCode' => $responseCode,
                    'message'=>  $message, 
                );
        }    
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ; 
        $this->response($data);
    } 
    //Transportation list
    public function transport_list_post(){
        $user_id = '';
        $boardcode = '';
        $data['ResponseCode'] = 500;
        $message      = "Invalid request.";  
        $this->form_validation->set_rules('ecoadmin_id', 'Ecoadmin Id', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            $ecoadmin_id  = $this->post('ecoadmin_id');
            $transportList = $this->api_model->get_result('transport',array('ecoadmin_id'=>$ecoadmin_id));
            //echo $this->db->last_query();
            if(!empty($transportList)){
                $data['ResponseCode'] = 200;
                $data['Data'] = $transportList;
                $data['message']  = "Tansport list get successfully";
            }else{
                $data['message']  = "Tansport list not found";
            }
           
        }    
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ; 
        $this->response($data);
    } 
     //Transportation route get
    public function transport_route_post(){
        $user_id = '';
        $boardcode = '';
        $data['ResponseCode'] = 500;
        $message      = "Invalid request.";  
        $this->form_validation->set_rules('bus_id', 'Bus Id', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            $bus_id  = $this->post('bus_id');
            $busList = $this->api_model->get_result('route',array('bus_id'=>$bus_id),array('*'),array('order','asc'));
            //echo $this->db->last_query();
            if(!empty($busList)){
                $data['ResponseCode'] = 200;
                $data['Data'] = $busList;
                $data['message']  = "Bus list get successfully";
            }else{
                $data['message']  = "Bus list not found";
            }
           
        }    
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ; 
        $this->response($data);
    }

    //user setting
    function save_user_setting_post(){
        $user_id     = ''; 
        $responseCode   = 500;
        $message        = "Invalid request";
        $detail= array();
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        $this->form_validation->set_rules('email_alert', 'Email Alert', 'trim|required');
        $this->form_validation->set_rules('mobile_alert', 'Mobile Alert', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            $detail['email_alert']   = $this->post('email_alert');             
            $detail['mobile_alert']   = $this->post('mobile_alert');
            $user_id   = $this->post('user_id');
            if($user_id!=''&& (count($detail)>0)) { 
                $checkDateRow   = $this->api_model->get_row('users',array('id'=>$user_id));
                if(!empty($checkDateRow))
                {
                    if($this->api_model->update('users',$detail,array('id'=>$user_id))) {
                        $responseCode   = 200; 
                        $message    = "Setting saved successfully";  
                    }else{
                        $message    = "Setting not saved, please try again";
                    }
                }else{
                    $message    = "User id not exist, please try again";
                }            
            } 
        }    
        $data   = array( 
                'ResponseCode'  => $responseCode,
                'message'       => $message, 
            ); 
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;
        $this->response($data);
    }




   //create otp using get
    public function otp_get()
    {  
        $mobile = $this->get('mobile'); 

        $previous = 0; 
        if($mobile) {

            $generator = pow(13,11);
            $modulus = pow(7,19); //int might be too small
            $possibleChars = "123456789011234657822467849";
            $previous = ($previous + $generator) % $modulus;
            $output='';
            $temp = $previous; 

            for($i = 0; $i < 6; $i++) {

                $output.= $possibleChars[rand(0, strlen($possibleChars) - 1)];
                $temp = $temp / 19;
            }   

            //echo $output; die;

            $data = array( 
                'ResponseCode' => 200,
                'Data' => $output, 
            ); 

            $this->response($data); // OK (200) being the HTTP response code 
         
        } else { 

            // Set the response and exit
            $this->response([
                'ResponseCode' => 500,
                'message' => 'Mobile number is not existing in the system',   // internal server error 
            ]); 
        } 
    }  
    
   //create otp using post
    public function otp_post() 
    {   
        
        $mobile = $this->post('mobile');  
        $previous = 0; 
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|exact_length[10]');
        if($this->form_validation->run() == TRUE){
            if($mobile) {

            	$generator = pow(13,11);
      			$modulus = pow(7,19); //int might be too small
      			$possibleChars = "123456789011234657822467849";
            	$previous = ($previous + $generator) % $modulus;
            	$output='';
            	$temp = $previous; 

      			for($i = 0; $i < 6; $i++) {

        			$output.= $possibleChars[rand(0, strlen($possibleChars) - 1)];
        			$temp = $temp / 19;
      			}   

                //echo $output; die;
      			$data = array( 
            		'ResponseCode' => 200,
            		'Data' => $output, 
                ); 

                $this->response($data); // OK (200) being the HTTP response code 
             
      		} 
        }

        $data = array( 
            'ResponseCode' => 500,
            'message'   => 'Please Try Again');

        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;
        $this->response($data);              
    }   

    //match otp 
    public function matchOtp_post() {

        $otp = $this->post('otp');
        $this->form_validation->set_rules('otp', 'OTP', 'trim|required');
        if($this->form_validation->run() == TRUE){  
            if($otp) {
            
               $data = array( 
                    'ResponseCode' => 200,
                    'message' => 'OTP code matched successfully', 
                ); 

                $this->response($data); // OK (200) being the HTTP response code 
            }
        } 
        // Set the response and exit
        $data = array( 
            'ResponseCode' => 500,
            'message'   => 'OTP code does not match, please provide correct detail');

        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;
        $this->response($data);                        

    }   

    //match otp 
    public function matchOtp_get() {

        $otp = $this->get('otp');  
        if($otp) {
        
           $data = array( 
                'ResponseCode' => 200,
                'message' => 'OTP code matched successfully', 
            ); 

            $this->response($data); // OK (200) being the HTTP response code 

        } else {
         
           // Set the response and exit
            $this->response([
                'ResponseCode' => 500,
                'message' => 'OTP code does not match, please provide correct detail',   // internal server error 
            ]);
        }          

    }    
   public function user_mobile_update_request_post() {  
        $user_id     = ''; 
        $detail= array();
        if($this->post('mobile')!=''){
            $detail['otp']   = $this->post('mobile');             
        }
        if($this->post('user_id')!=''){
            $user_id= $this->post('user_id'); 
        }
        $responseCode   = 500;
        $message        = "Invalid request";
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[8]|max_length[12]');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            if($user_id!=''&& (count($detail)>0)) { 
                $otp = mt_rand(100000,999999);
                $detail['otp']   = $detail['otp'].'_'.$otp;
                if($this->api_model->update('users',$detail,array('id'=>$user_id))) {
                    $responseCode   = 200;
                    $message        = "OTP code has been sent";
                } else {
                    $message        = "OTP code not sent, please try again.";
                }        
            }
        }    
        $data = array( 
            'ResponseCode'  => $responseCode,
            'message'       => $message, 
        ); 
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;
        $this->response($data);
    }  

    public function user_mobile_update_verify_post() {  
        $user_id    = ''; 
        $otp        = '';
        if($this->post('otp')!=''){
            $otp    = $this->post('otp');             
        }
        if($this->post('user_id')!=''){
            $user_id= $this->post('user_id'); 
        }
        $responseCode   = 500;
        $message        = "Invalid request";
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[8]|max_length[12]');
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            if($user_id!=''&& ($otp!='')) { 
                $userData   = $this->api_model->get_row('users',array('id'=>$user_id),array('otp'));
                if(!empty($userData)){
                    $mobile_number = '';
                    $mobileStr = $userData->otp;
                    $mobileArr  = explode('_', $mobileStr);
                    if(isset($mobileArr[1])&&!empty($mobileArr[1])){
                        if(trim($mobileArr[1]) == trim($otp)){
                            $mobile_number = $mobileArr[0];
                        }
                    }
                    if(!empty($mobile_number)){
                        if($this->api_model->update('users',array('otp'=>'','mobile'=>$mobile_number),array('id'=>$user_id))) {
                            $responseCode   = 200;
                            $message    = "Mobile Number update successfully";
                        }else {
                            $message    = "Mobile Number not update, Please try again.";
                        } 
                    }else{
                        $message    = "Invalid OTP code, please try again";
                    }
                }
                       
            }  
        }    
        $data = array( 
                'ResponseCode' => $responseCode,
                'message'=>$message, 
            );
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;

        $this->response($data);
    }  

    public function user_delete_post() {  

       $user_id = $this->post('user_id'); 
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            if($user_id) { 

                $detail= array( 

                    'status' => 0,
                ); 

                if($this->api_model->update('users',$detail,array('id'=>$user_id))) {

                  $data = array( 
                    'ResponseCode' => 200,
                    'message'=>"User deleted successfully.", 
                  );
                  $this->response($data);

                } else {
                    
                    $data = array( 
                        'ResponseCode' => 500,
                        'message'=>"For user deletion, Please try again.", 
                   );
                    $this->response($data);

                }        
            } else {
               
                $data = array( 
                    'ResponseCode' => 500,
                    'message'=>"Invalid request", 
                ); 
                $this->response($data);

            }  
        }    
        $data = array( 
                    'ResponseCode' => 500,
                    'message'=>"Invalid request", 
                ); 
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;
        $this->response($data);
    }   
    
   
    //VERIFY MOBILE NUMBER 
    public function verify_mobile_post() {
        $responseCode = 400;
        $message        = "Invalid Request.";
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|exact_length[10]');
        if ($this->form_validation->run() == TRUE){
            if($this->input->post('mobile')){
                if($user_info = $this->api_model->get_row('users',array('mobile'=>$this->input->post('mobile')))){   
                    $userdata = array( 
                        'user_id' => $user_info->id,
                        'first_name' => $user_info->first_name,
                        'last_name' => $user_info->last_name,
                        'email'  => $user_info->email,
                        'mobile' => $user_info->mobile,
                        'push_token' => $user_info->push_token,
                        'os_version' => $user_info->os_version,
                        'app_version' => $user_info->app_version,
                        'device_id'  => $user_info->device_id,
                        'device_type' => $user_info->device_type
                    ); 
                    $message = 'Your mobile number is verified.';
                    $responseCode = 200;
                } else {
                    $responseCode = 500;
                    $message = 'Mobile number is not verified, Please provide verified mobile number.';
                } 
            }
        }

        $data = array( 
            'ResponseCode' => $responseCode,
            'message'   => $message);
    
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;

        if($responseCode == 200){
            $data['Data']   = $userdata;
        }
        $this->response($data);
    }
    // Get user settings details
    function get_user_setting_post(){
        $user_id    = '';
        $responseCode   = 500;
        $message    = 'Invalid Request';
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        if($this->form_validation->run() == TRUE){ 
            if($this->post('user_id')!=''){
                $data['ResponseCode'] = 200;
                $user_id    = $this->post('user_id');
                $userdata  = $this->api_model->get_row('users',array('id'=>$user_id),array('email_alert','mobile_alert'));

                if($userdata){
                    $data['Data'] = $userdata;
                    $data['Message'] = "User details fetch successfully";
                }else{
                    $data['ResponseCode'] = 500;
                    $data['Message'] = "User record not found";
                }
                $this->response($data);
            }else{
                 $data = array( 
                'ResponseCode' => $responseCode,
                'message'   => $message);
            }
        }
        $data = array( 
            'ResponseCode' => $responseCode,
            'message'   => $message);
        if($this->form_validation->error_array())    
        $data['error'] = $this->form_validation->error_array() ;
        $this->response($data); // OK (200) b
    }
        
}