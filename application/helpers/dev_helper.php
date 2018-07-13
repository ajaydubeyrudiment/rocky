<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('getPlans')) {
    function getPlans($plan=''){
        $CI =& get_instance();
        if(!empty($plan)){
        	return $CI->common_model->get_result('plans',array('id'=>$plan,'status'=>1), array(), array('amount','asc'));
        }else{
        	return $CI->common_model->get_result('plans',array('status'=>1), array(), array('amount','asc'));
        }       
    }
}
if ( ! function_exists('getHeight')) {
    function getHeight($id=''){
        $CI =& get_instance();
        if(!empty($plan)){
        	return $CI->common_model->get_result('height', array('id'=>$id, 'status'=>1), array(), array('id','asc'));
        }else{
        	return $CI->common_model->get_result('height', array('status'=>1), array(), array('id','asc'));
        }       
    }
}
if ( ! function_exists('getWorkOutPlansItems')) {
    function getWorkOutPlansItems($id=''){
        $CI = & get_instance();
        $itemIDs = explode(',', $id);
        $titles  = array();
        if(!empty($itemIDs)){
        	foreach($itemIDs as $itemID){
        		$row = $CI->common_model->get_row('service_plan_item', array('id'=>$itemID, 'status'=>1), array('title'), array('id','asc'));
        		$titles[] = $row->title;
        	}
        	return implode(', ', $titles);
        }
        return '-';
    }
}
if ( ! function_exists('getWorkOutEx')) {
    function getWorkOutEx($id=''){
        $CI = & get_instance();
        $itemIDs = explode(',', $id);
        $titles  = array();
        if(!empty($itemIDs)){
        	foreach($itemIDs as $itemID){
        		$row = $CI->common_model->get_row('service_plan_user_exercise', array('id'=>$itemID, 'status'=>1), array('exercise_title'), array('id','asc'));
        		$titles[] = $row->exercise_title;
        	}
        	return implode(', ', $titles);
        }
        return '-';
    }
}
if ( ! function_exists('getDietPlanF')) {
    function getDietPlanF($id=''){
        $CI = & get_instance();
        $itemIDs = explode(',', $id);
        $titles  = array();
        if(!empty($itemIDs)){
        	foreach($itemIDs as $itemID){
        		$row = $CI->common_model->get_row('service_plan_diet_items', array('id'=>$itemID, 'status'=>1), array('item_title'), array('id','asc'));
        		$titles[] = $row->item_title;
        	}
        	return implode(', ', $titles);
        }
        return '-';
    }
}
if ( ! function_exists('get_cal_consumed')) {
    function get_cal_consumed($date='',$user_id=''){
        $CI = & get_instance();
        return $CI->developer_model->get_cal_consumed($date, $user_id);       
    }
}
if ( ! function_exists('get_cal_burned')) {
    function get_cal_burned($date='', $user_id=''){
        $CI = & get_instance();
        return $CI->developer_model->get_cal_burned($date, $user_id);       
    }
}
if ( ! function_exists('get_admin_all_modules')) {
    function get_admin_all_modules(){
        $CI = & get_instance();
        $moduleRow = $CI->common_model->get_row('admin_users', array('id'=>superadmin_id()), array('modules'));
       // print_r($moduleRow); exit();
        $modules   = explode(',', $moduleRow->modules);
        return $modules;
    }
}
if ( ! function_exists('get_admin_chat_user_id')) {
    function get_admin_chat_user_id(){
        $CI = & get_instance();
        $arr = $CI->session->userdata();
        if(!empty($arr['chat_admin_login'])&&$arr['chat_admin_login']==TRUE){
            return $arr['admin_chat_user_id'];
        }else{
             return FALSE;
        }
    }
}
if ( ! function_exists('get_admin_chat_token')) {
    function get_admin_chat_token(){
        $CI = & get_instance();
        $arr = $CI->session->userdata();
        if(!empty($arr['chat_admin_login'])&&$arr['chat_admin_login']==TRUE){
            return $arr['admin_chat_authToken'];
        }else{
            return FALSE;
        }
    }
}

