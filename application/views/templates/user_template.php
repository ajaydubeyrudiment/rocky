<?php 
    $this->load->view('templates/frontend_header'); 
    $pageName    = $this->uri->segment(2);
    if(user_logged_in()){
    	$hideleftBar = array('addRecipe','addBlog','imagePost','profilePost');
    	if(!in_array($pageName, $hideleftBar)){
    		$this->load->view('templates/frontend_left_bar');
    	}		
	}
    if($pageName=='dashboard'||$pageName=='profile'){
        echo '';
    }
    if($pageName=='user_profile'){
        $this->load->view('templates/frontend_left_bar');
    }  
	$this->load->view($template); 	
	$this->load->view('templates/frontend_footer');
?>