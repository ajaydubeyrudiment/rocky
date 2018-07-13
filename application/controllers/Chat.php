<?php
/*admin login link https://open.rocket.chat/home*/
/*api link https://rocket.chat/docs/developer-guides/rest-api/ */
defined('BASEPATH') OR exit('No direct script access allowed');
/*this controller for making front end */
class Chat extends CI_Controller {
	public function __construct(){
    	parent:: __construct(); 
    	$this->check_admin_login();
    }
  /*home page */  
	public function index(){
		
	}
	public function check_admin_login(){
		$arr = $this->session->userdata();
		if(!empty($arr['chat_admin_login'])&&$arr['chat_admin_login']==TRUE){
			return TRUE;
		}else{
			$this->admin_login();
		}
	} 
	public function admin_login(){
		$url 			= 'https://rokape.rocket.chat/api/v1/login';
	    $data 			= array("user" => "amrode.rudiment@gmail.com","password" => "123456789");
	    $data_string 	= json_encode($data);
	    $ch 			= curl_init($url);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $result = curl_exec($ch);
	    curl_close($ch);
	    $response = json_decode($result);
	    $jsonArr  = array(	'chat_admin_login'=>TRUE, 
	    					'admin_chat_user_id'=>$response->data->userId, 
	    					'admin_chat_authToken'=>$response->data->authToken
	    				);
	    $this->session->set_userdata($jsonArr);
	} 
	/*{"status":"success","data":{"userId":"T53RAcKMDvTzaajCN","authToken":"ypexoPE7N08jrxi2QbzAOOYesE6IILGGWKXhI7VJOvG"}}*/
	public function admin_details(){
		$url = 'https://rokape.rocket.chat/api/v1/me';
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'X-User-Id:'.get_admin_chat_user_id(),'X-Auth-Token:'.get_admin_chat_token()));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $result = curl_exec($ch);
	    curl_close($ch);
	    print_r($result);
	} 
	public function add_new_user(){
		$url = 'https://rokape.rocket.chat/api/v1/users.create';
	    $data = array("name" => "developer","email" => "arjunamrode@gmail.com","password"=> "pass@w0rd45", "username"=> "essxamples","active"=>TRUE);
	    $data_string = json_encode($data);
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'X-User-Id:'.get_admin_chat_user_id(),'X-Auth-Token:'.get_admin_chat_token()));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $result = curl_exec($ch);
	    curl_close($ch);
	    print_r($result);
	} 
	public function user_list(){
		$url = 'https://rokape.rocket.chat/api/v1/users.list';
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'X-User-Id:'.get_admin_chat_user_id(),'X-Auth-Token:'.get_admin_chat_token()));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $result = curl_exec($ch);
	    curl_close($ch);
	    print_r($result);
	} 
	public function create_token(){
		$url = 'https://rokape.rocket.chat/api/v1/users.createToken';
	    $data = array("userId" => "T53RAcKMDvTzaajCN");
	    $data_string = json_encode($data);
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'X-User-Id:'.get_admin_chat_user_id(),'X-Auth-Token:'.get_admin_chat_token()));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $result = curl_exec($ch);
	    curl_close($ch);
	    print_r($result);
	} 
	public function create_channel(){
		$url = 'https://rokape.rocket.chat/api/v1/channels.create';
	    $data = array("name" => "channelname45");
	    $data_string = json_encode($data);
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'X-User-Id:'.get_admin_chat_user_id(),'X-Auth-Token:'.get_admin_chat_token()));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $result = curl_exec($ch);
	    curl_close($ch);
	    print_r($result);
	}
	public function all_channel(){
		$url = 'https://rokape.rocket.chat/api/v1/channels.list';
	    $ch  = curl_init($url);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'X-User-Id:'.get_admin_chat_user_id(),'X-Auth-Token:'.get_admin_chat_token()));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $result = curl_exec($ch);
	    curl_close($ch);
	    print_r($result);
	} 
	public function get_channel(){
		$url = 'https://rokape.rocket.chat/api/v1/channels.info?roomId=mfMeBrLx4XjNuKQWB';
	    $data = array("name" => "channelname9879");
	    $data_string = json_encode($data);
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'X-User-Id:'.get_admin_chat_user_id(),'X-Auth-Token:'.get_admin_chat_token()));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $result = curl_exec($ch);
	    curl_close($ch);
	    print_r($result);
	}
	public function channels_notifications_get(){
		$url = 'https://rokape.rocket.chat/api/v1/channels.notifications?roomId=mfMeBrLx4XjNuKQWB';
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'X-User-Id:'.get_admin_chat_user_id(),'X-Auth-Token:'.get_admin_chat_token()));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $result = curl_exec($ch);
	    curl_close($ch);
	    print_r($result);
	}
	public function channels_notifications_post(){
		$url = 'https://rokape.rocket.chat/api/v1/channels.notifications';
		$data = array("roomId" => "mfMeBrLx4XjNuKQWB");
	    $data_string = json_encode($data);
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'X-User-Id:'.get_admin_chat_user_id(),'X-Auth-Token:'.get_admin_chat_token()));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $result = curl_exec($ch);
	    curl_close($ch);
	    print_r($result);
	}
	public function channels_list_history(){
		$url = 'https://rokape.rocket.chat/api/v1/channels.history?roomId=Y6mQYSJnZSowNTYMe';		
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'X-User-Id:'.get_admin_chat_user_id(),'X-Auth-Token:'.get_admin_chat_token()));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $result = curl_exec($ch);
	    curl_close($ch);
	    print_r($result);
	}
	public function channels_list_joined(){
		$url = 'https://rokape.rocket.chat/api/v1/channels.list.joined';
		$data = array("roomId" => "mfMeBrLx4XjNuKQWB");
	    $data_string = json_encode($data);
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'X-User-Id:'.get_admin_chat_user_id(),'X-Auth-Token:'.get_admin_chat_token()));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $result = curl_exec($ch);
	    curl_close($ch);
	    print_r($result);
	}
	public function sendmessage(){
		$url = 'https://rokape.rocket.chat/api/v1/chat.sendMessage';
	    $data = array("rid" => "T53RAcKMDvTzaajCN", "msg" =>"hello all user");
	    $data_string = json_encode($data);
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'X-User-Id:'.get_admin_chat_user_id(),'X-Auth-Token:'.get_admin_chat_token()));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $result = curl_exec($ch);
	    curl_close($ch);
	    print_r($result);
	}
	public function postmessage(){
		$url = 'https://rokape.rocket.chat/api/v1/chat.postMessage';
	    $data = array("roomId" => "T53RAcKMDvTzaajCN", "channel"=> "channelname","text" =>"hello all user 123");
	    $data_string = json_encode($data);
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'X-User-Id:'.get_admin_chat_user_id(),'X-Auth-Token:'.get_admin_chat_token()));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $result = curl_exec($ch);
	    curl_close($ch);
	    print_r($result);
	}
	public function get_message(){
		$url = 'https://rokape.rocket.chat/api/v1/chat.getMessage?msgId=GMv5ho57DavGKKSKy';
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'X-User-Id:'.get_admin_chat_user_id(),'X-Auth-Token:'.get_admin_chat_token()));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $result = curl_exec($ch);
	    curl_close($ch);
	    print_r($result);
	}
	public function read_receipts(){
		$url = 'https://rokape.rocket.chat/api/v1/chat.getMessageReadReceipts?messageId=GMv5ho57DavGKKSKy';
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'X-User-Id:'.get_admin_chat_user_id(),'X-Auth-Token:'.get_admin_chat_token()));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $result = curl_exec($ch);
	    curl_close($ch);
	    print_r($result);
	}
	public function emoji_custom(){
		$url = 'https://rokape.rocket.chat/api/v1/emoji-custom';
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'X-User-Id:'.get_admin_chat_user_id(),'X-Auth-Token:'.get_admin_chat_token()));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $result = curl_exec($ch);
	    curl_close($ch);
	    print_r($result);
	}
}