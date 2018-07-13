<?php
/**
*
* Email Library 
*
*/
class Cimail_email{	
	private $config;
	private $param;	
	private $data_temp;
	private $param_email;
	private $result;
	function __construct()	{
		
		$CI =& get_instance();
		$CI->load->library(array('email','parser'));
		$CI->load->helper(array('email'));
		$config = Array(
						  'mailtype'  => 'html', 
						 'charset'   => 'iso-8859-1',
						// 'ssl'		=> 'true'
						);		
		
		$CI->email->initialize($config);
	}
    function send_mail($param=array()){
	    $CI =& get_instance();	
	    if(isset($param['template']) && !empty($param['template'])):
	    	if(!empty($param['template']['var_name']))

	    	$data_temp_body = $param['template']['var_name'];

	    	if(!empty($param['template']['temp']))

	    		$data_temp_body['email_message'] = $param['template']['temp']; else $data_temp_body['email_message'] = '';

	    

	        $email_body_template = $CI->parser->parse('templates/email/email_template', $data_temp_body, TRUE);
	        
	        	$param['email_body_template'] = $email_body_template;

 			endif;

       
         if(!empty($param['email'])) $param_email = $param['email']; else  $param_email=array();

      

        $CI->email->set_newline("\r\n"); 

        // from required

        if(isset($param_email['from']) && !empty($param_email['from'])):

        	if(!empty($param_email['from_name']))

         		$CI->email->from($param_email['from'],$param_email['from_name']);

         	else

         		$CI->email->from($param_email['from']);

        else:

        	die("Sorry, Please provide 'to' variable.");

        endif;
        if(isset($param_email['to']) && !empty($param_email['to'])):

         	$CI->email->to($param_email['to']); 

        else:

        	die("Sorry, Please provide 'to' variable.");

        endif;

        // cc optinal

         if(isset($param_email['cc']) && !empty($param_email['cc'])):

         	$CI->email->cc($param_email['cc']);         

        endif; 

        // bcc optinal 

        if(isset($param_email['bcc']) && !empty($param_email['bcc'])):

         	$CI->email->bcc($param_email['bcc']);        

        endif;
        // subject required
        if(isset($param_email['subject']) && !empty($param_email['subject'])):
        	$data_temp_subject['email_subject']=$param_email['subject'];
        
    		$new_subject_arr=array_merge($data_temp_subject,$param['template']['var_name']);   
        	$email_subject_template = $CI->parser->parse('templates/email/email_subject_template', $new_subject_arr, TRUE);
         	$CI->email->subject($email_subject_template);
        else:
        	die("Sorry, Please provide 'subject' variable."); 
         endif;
       
        // template required
       if(isset($param['email_body_template']) && !empty($param['email_body_template'])):
         	 $CI->email->message($param['email_body_template']);         	
        else:
        	die("Sorry, Please provide 'template' variable.");      
         endif;
        // attachment optinal 
        if(isset($param_email['attach']) && !empty($param_email['attach'])):
         	foreach ($param_email['attach'] as $row):
	         	$CI->email->attach($row['file']);
         	endforeach;
         endif;
	    if($result = $CI->email->send())  
	  	    return array('EMAIL_STATUS'=>$result,'EMAIL_MESSAGE'=>'Email Send Successfully.');
  	    else   	
  		    return array('EMAIL_STATUS'=>$result,'EMAIL_MESSAGE'=>$CI->email->print_debugger());						
						 
	}
	function get_email_template($template_id=''){
		$CI =& get_instance();			 
		$CI ->load->database();		
		$query=$CI->db->get_where('email_templates',array('id'=>$template_id));	
		if($query->num_rows()>0)
			return $query->row();
		else
			return FALSE;
	}
	function html(){
		return '<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>	
</head>
<body>
<div id="container">
	<h1>HELLO Mr/Miss {name},</h1>
	<div id="body">
	<p>You are {age} year old.</p>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
	</div>	
</div>
</body>
</html>';
	}
}