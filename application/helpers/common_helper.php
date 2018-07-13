<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*	clear cache
*/
if ( ! function_exists('clear_cache')) {
	function clear_cache(){
		$CI =& get_instance();
		$CI->output->set_header('Expires: Wed, 11 Jan 1984 05:00:00 GMT' );
		$CI->output->set_header('Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . 'GMT');
		$CI->output->set_header("Cache-Control: no-cache, no-store, must-revalidate");
		$CI->output->set_header("Pragma: no-cache");
	}
}
if ( ! function_exists('superadmin_logged_in')) {
	function superadmin_logged_in(){
		$CI =& get_instance();
		$superadmin_info = $CI->session->userdata('superadmin_info');
		if($superadmin_info['logged_in']===TRUE)
			return TRUE;
		else
			return FALSE;
	}
}
if ( ! function_exists('superadmin_name')) { 
	function superadmin_name(){
		$CI =& get_instance();
		$superadmin_info = $CI->session->userdata('superadmin_info');
		if($superadmin_info['logged_in']===TRUE )
		 	return ucfirst($superadmin_info['first_name'])." ".$superadmin_info['last_name'];
		else
			return FALSE;
	}
}
if ( ! function_exists('superadmin_id')) {
	function superadmin_id(){
		$CI =& get_instance();
		$superadmin_info = $CI->session->userdata('superadmin_info');
		return $superadmin_info['id'];		
	}
}
if ( ! function_exists('get_admin_info')) {
    function get_admin_info($id=''){
        $CI =& get_instance();
        $user_info = $CI->common_model->get_row('admin_users',array('id'=>$id));        
        return $user_info; 
    }
} 

/*check parent or teacher user login*/

if ( ! function_exists('user_logged_in')) {

    function user_logged_in(){

        $CI =& get_instance();

        $user_info = $CI->session->userdata('user_info');

        if($user_info['logged_in']===TRUE)

            return TRUE;

        else

            return FALSE;

    }

}

/*get parent or teacher id*/

if ( ! function_exists('user_id')) {

    function user_id(){

        $CI =& get_instance();

        $user_info = $CI->session->userdata('user_info');       

            return $user_info['id'];        

    }

}

/*get customer info*/

if ( ! function_exists('user_info')) {
    function user_info($userID=''){
        $CI =& get_instance();
        if(!empty($userID)){
        	$result = $CI->developer_model->getUserDetails($userID); 
        }else{
        	$result = $CI->developer_model->getUserDetails($userID); 
        } 
        if(!empty($result)){
            return $result;
        }else{
            return false;
        } 
    }
}

if ( ! function_exists('home_pagination')) {

	function home_pagination(){

		$CI =& get_instance();

		$data = array();

		$data['full_tag_open'] = '<ul class="pagination">';	

		$data['full_tag_close'] = '</ul>';

		$data['first_tag_open'] = '<li class="first-l">';

		$data['first_tag_close'] = '</li>';

		$data['num_tag_open'] = '<li >';

		$data['num_tag_close'] = '</li>';

		$data['last_tag_open'] = '<li class="last-l">';		

		$data['last_tag_close'] = '</li>';

		$data['next_tag_open'] = '<li class="next-l">';

		$data['next_tag_close'] = '</li>';

		$data['prev_tag_open'] = '<li class="pre-l">';

		$data['prev_tag_close'] = '</li>';

		$data['cur_tag_open'] = '<li class="active"><a href="#">';

		$data['cur_tag_close'] = '</a></li>';

		$data['next_link'] = $CI->lang->line('Next');

		$data['prev_link'] = $CI->lang->line('Previous');

		return $data;

	}					

}

/**

*	frontend pagination

*/

if ( ! function_exists('frontend_pagination')) {
	function frontend_pagination(){
		$data = array();
		$data['full_tag_open'] = '<ul class="pagination">';	
		$data['full_tag_close'] = '</ul>';
		$data['first_tag_open'] = '<li>';
		$data['first_tag_close'] = '</li>';
		$data['num_tag_open'] = '<li>';
		$data['num_tag_close'] = '</li>';
		$data['last_tag_open'] = '<li>';	
		$data['last_tag_close'] = '</li>';
		$data['next_tag_open'] = '<li>';
		$data['next_tag_close'] = '</li>';
		$data['prev_tag_open'] = '<li>';
		$data['prev_tag_close'] = '</li>';
		$data['cur_tag_open'] = '<li class="active"><a href="#">';
		$data['cur_tag_close'] = '</a></li>';
		$data['next_link'] 		= 'Next';
		$data['prev_link'] = 'Previous';
		return $data;
	}
}
if ( ! function_exists('admin_pagination')) {
	function admin_pagination(){
		$data = array();
		$data['full_tag_open']  = '<ul class="pagination">';	
		$data['full_tag_close'] = '</ul>';
		$data['first_tag_open'] = '<li>';
		$data['first_tag_close'] = '</li>';
		$data['num_tag_open'] = '<li>';
		$data['num_tag_close'] = '</li>';
		$data['last_tag_open'] = '<li>';	
		$data['last_tag_close'] = '</li>';
		$data['next_tag_open'] = '<li>';
		$data['next_tag_close'] = '</li>';
		$data['prev_tag_open'] = '<li>';
		$data['prev_tag_close'] = '</li>';
		$data['cur_tag_open'] = '<li class="active"><a href="#">';
		$data['cur_tag_close'] = '</a></li>';
		$data['next_link'] 		= 'Next';
		$data['prev_link'] 		= 'Previous';
		return $data;
	}	
}

/**

*	thisis  back end helper 

*/

if ( ! function_exists('msg_alert')) {

	function msg_alert(){

	$CI =& get_instance(); ?>

		<?php if($CI->session->flashdata('msg_success')): ?>	

			<div class="alert alert-success" id="actionMessageSuccess">

				 <button type="button" class="close" data-dismiss="alert">&times;</button> 

			    <strong><?php if($CI->lang->line('success')){ echo $CI->lang->line('success');}else{ echo 'Success';}?> :</strong> <br>  <?php echo urldecode($CI->session->flashdata('msg_success')); ?>

			</div>

		 <?php endif; ?>

		<?php if($CI->session->flashdata('msg_info')): ?>	

			<div class="alert alert-info" id="actionMessageInfo">

				 <button type="button" class="close" data-dismiss="alert">&times;</button> 

			    <strong>Info :</strong> <br> <?php echo urldecode($CI->session->flashdata('msg_info')); ?>

			</div>

		<?php endif; ?>

		<?php if($CI->session->flashdata('msg_warning')): ?>	

			<div class="alert alert-warning" id="actionMessageWarning">

				 <button type="button" class="close" data-dismiss="alert">&times;</button> 

			     <strong>Warning :</strong> <br> <?php echo urldecode($CI->session->flashdata('msg_warning')); ?>

			</div>

		<?php endif; ?>

		<?php if($CI->session->flashdata('msg_error')): ?>	

			<div class="alert alert-danger" id="actionMessageError">

				 <button type="button" class="close" data-dismiss="alert">&times;</button> 

			     <strong><?php if($CI->lang->line('Error')){echo $CI->lang->line('Error');}else{echo 'Error';}?> :</strong> <br>  <?php echo urldecode($CI->session->flashdata('msg_error')); ?>

			</div>

		<?php endif; ?>

	<?php }					

}

/**

*	Menu Information

*/

if ( ! function_exists('upload_file')) {

	function upload_file($param = null){

		$CI =& get_instance();	

		$config['upload_path'] = './assets/uploads/';

		$config['allowed_types']= 'gif|jpg|png|xls|xlsx|csv|jpeg|pdf|doc|docx';

		$config['max_size']	= 1024*90;

		$config['image_resize']= FALSE;

		$config['resize_width']= 126;

		$config['resize_height']= 126;		

		if ($param){

            $config = $param + $config;

        }

		$CI->load->library('upload', $config);

		if(!empty( $config['file_name']))

			$file_Status = $CI->upload->do_upload($config['file_name']);

		else

			$file_Status = $CI->upload->do_upload();

		if (!$file_Status){

			return array('STATUS'=>FALSE,'FILE_ERROR' => $CI->upload->display_errors());			

		}else{

			$uplaod_data=$CI->upload->data();

	

			$upload_file = explode('.', $uplaod_data['file_name']);

			

			if($config['image_resize'] && in_array($upload_file[1], array('gif','jpeg','jpg','png','bmp','jpe'))){

				$param2=array(

					'source_image' 	=>	$config['source_image'].$uplaod_data['file_name'],

					'new_image' 	=>	$config['new_image'].$uplaod_data['file_name'],

					'create_thumb' 	=>	FALSE,

					'maintain_ratio'=>	FALSE,

					'width' 		=>	$config['resize_width'],

					'height' 		=>	$config['resize_height'],

					);			

				image_resize($param2);

			}	

			return array('STATUS'=>TRUE,'UPLOAD_DATA' =>$uplaod_data );

		}

	}

}

/**

*	image resize

*/

if ( ! function_exists('image_resize')) {

	function image_resize($param = null){

		$CI =& get_instance();

		$config['image_library'] = 'gd2';

		$config['source_image']	 = './assets/uploads/';

		$config['new_image']	 = './assets/uploads/';		

		$config['create_thumb']  = FALSE;

		$config['maintain_ratio']= FALSE;

		$config['width']	     = 150;

		$config['height']	     = 150;		

		 if ($param) {

            $config = $param + $config;

        }

		$CI->load->library('image_lib', $config); 

		if ( ! $CI->image_lib->resize())

		{

		   //return array('STATUS'=>TRUE,'MESSAGE'=>$CI->image_lib->display_errors()); 

			die($CI->image_lib->display_errors());

		}else{

			 return array('STATUS'=>TRUE,'MESSAGE'=>'Image resized.'); 

		}

	}

}

/**

*	thumbnail image

*/

if ( ! function_exists('create_thumbnail')) {

	function create_thumbnail($config_img='',$img_fix='') {

		$CI =& get_instance();

		$config_image['image_library'] = 'gd2';

		$config_image['source_image']  = $config_img['source_path'].$config_img['file_name'];	

		//$config_image['create_thumb'] = TRUE;

		$config_image['new_image']	   = $config_img['destination_path'].$config_img['file_name'];

		$config_image['height']    	   = $config_img['height'];

		$config_image['width'] 		   = $config_img['width'];

		if($img_fix){

			$config_image['maintain_ratio'] = FALSE;

		}

		else{

			$config_image['maintain_ratio'] = TRUE;

			list($width, $height, $type, $attr) = getimagesize($config_img['source_path'].$config_img['file_name']);

	        if ($width < $height) {

	        	$cal=$width/$height;

	        	$config_image['width']=$config_img['width']*$cal;

	        }

			if ($height < $width)

			{

				$cal=$height/$width;

		    	$config_image['height']=$config_img['height']*$cal;

			}

		}		

		$CI->load->library('image_lib');

		$CI->image_lib->initialize($config_image);		

		if(!$CI->image_lib->resize()) 

			return array('status'=>FALSE,'error_msg'=>$CI->image_lib->display_errors());

		else

			return array('status'=>TRUE,'file_name'=>$config_img['file_name']);

	}

}

/*generate salts for password*/

if ( ! function_exists('salt')) {

	function salt(){

	        return substr(md5(uniqid(rand(), true)), 0, 10);

	}

}

/*generate passwrod*/

if ( ! function_exists('passwordGenrate')) {

	function passwordGenrate($password, $salt){      

	        return sha1($salt.sha1($salt.sha1($password)));

	}

}

if ( ! function_exists('get_all_count')) {
    function get_all_count($table='', $array=array(),$limit=''){
        $CI =& get_instance();        
        if($CI->db->table_exists($table)){
	        $CI->db->where($array);	
	        if(!empty($limit)){
	        	$CI->db->limit($limit);
	        } 
		    $query = $CI->db->get($table);
		    if($query->num_rows()>0){
		        return $query->num_rows();
		    }else{
		        return 0;
		    }   		
		}else{
			return false;	
		} 
    }
}

if ( ! function_exists('cal_times')) { 

	function cal_times($times){
		//echo $times.'  '.date('Y-m-d H:i:s').' '.time().' '. strtotime($times);
		$diffTime = time()-strtotime($times);
		//echo $diffTime; exit();
		if($diffTime>31104000){

			$time = ceil($diffTime/31104000);

			return $time.' year ago';

		}elseif($diffTime>2492000){

			$time = ceil($diffTime/2492000);

			return $time.' months ago';

		}elseif($diffTime>86400){

			$time = ceil($diffTime/86400);

			return $time.' days ago';

		}elseif($diffTime>3600){

			$time = ceil($diffTime/3600);

			return $time.' hours ago';

		}elseif($diffTime>60){

			$time = ceil($diffTime/60);

			return $time.' minutes ago';

		}else{

			return $diffTime.' second ago';

		}

	}					

}
/*get customer info*/

if ( ! function_exists('get_setting_data')) {
    function get_setting_data($meta_id=''){
        $CI =& get_instance();
        if(!empty($meta_id)){
        	$row = $CI->common_model->get_row('setting', array('meta_key'=>$meta_id, 'status'=>1)); 
	        if(!empty($row)){
	        	if($meta_id=='home_banner'){
	        		if(!empty($row->meta_value)&&file_exists('assets/uploads/slider/'.$row->meta_value)){
	        			echo base_url('assets/uploads/slider/'.$row->meta_value);
	        		}else{
	        			echo 'imgs/coverr.jpg';
	        		}
	        	}else{
	        		return $row->meta_value;
	        	}
	        }else{
	        	return '';
	        }
        }else{
        	return '';
        }         
    }
}
/*get infomation ecosystem*/
if ( ! function_exists('site_info')) {
    function site_info($id){
        $CI =& get_instance();
        $result  = $CI->common_model->get_row('web_info', array('meta_key'=>$id, 'status'=>1), array('meta_data'));        
        if(!empty($result->meta_data)){
        	return $result->meta_data;
        }else{
        	return '';
        }     
    }
} 
/*get infomation ecosystem*/
if ( ! function_exists('admin_theam_path')) {
    function admin_theam_path(){
        $CI =& get_instance();
        $result  = $CI->common_model->get_row('web_info', array('meta_key'=>'admin_theam_path'), array('meta_data'));        
        if(!empty($result->meta_data)){
        	return base_url().$result->meta_data;
        }else{
        	return '';
        }     
    }
} 
/*get Left Bar Module*/
if ( ! function_exists('getModuleMenus')) {
    function getModuleMenus($mtype='',$type='',$category='',$subcategory=''){
        $CI =& get_instance();
        $whereA = array('status'=>1, 'type'=>$type);
        if(!empty($mtype)&&$mtype=='left_bar'){
        	$whereA['left_bar_show'] = 1;
        } 
        if(!empty($mtype)&&$mtype=='dashboard'){
        	$whereA['dashboard_show'] = 1;
        }       
        if(!empty($category)){
        	$whereA['category_id'] = $category;
        }
        if(!empty($subcategory)){
        	$whereA['sub_category_id'] = $subcategory;
        }
        return $CI->common_model->get_result('ad_module_main', $whereA); 
    }
} 
/*get admin user type*/
if ( ! function_exists('admin_type')) {
    function admin_type(){
        $CI =& get_instance();
        $result = $CI->common_model->get_row('admin_users',array('id'=>superadmin_id()), array('user_role')); 
        if(!empty($result->user_role)){
            return $result->user_role;
        }else{
            return false;
        }
    }
} 
if ( ! function_exists('get_select_options')) {
    function get_select_options($category=''){
        $CI =& get_instance();
        if(!empty($category)){
        	return $CI->common_model->get_result('ad_module_selects',array('category'=>$category), array(), array('category','asc'));
        }else{
        	return false;
        }       
    }
}
if ( ! function_exists('btnOrder')) {
    function btnOrder($id=''){
        $CI =& get_instance();
        $btnClasses = array('1' => 'btn btn-success',
				          '2' => 'btn btn-danger',
				          '4' => 'btn btn-primary'
        			 );
       if(array_key_exists($id, $btnClasses)){
              return $btnClasses[$id];
        }else{
          return false;
        }      
    }
}