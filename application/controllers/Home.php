<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*this controller for making front end */
class Home extends CI_Controller {
	public function __construct(){
    	parent:: __construct(); 
    }
  /*home page */  
	public function index(){
		if(user_logged_in()){ 
            redirect(base_url('user/dashboard'));
        }		
        $data['lastRecord']    = ceil($this->developer_model->get_recipe_blog_images(NULL, 0, 0, 'counter')/3);	
        $data['rows'] 		   = $this->developer_model->get_recipe_blog_images(NULL,0,21);	
        $data['gridCol']	   = 3;
		$data['template'] 	   = 'frontend/index';
	    $this->load->view('templates/frontend_template', $data);
	}
	public function getPostAjx(){
		$start                 = $this->input->get('currentPage')*3;
        $array                 = array();	
        $array['status']       = 'true';
        $data['rows'] 		   = $this->developer_model->get_recipe_blog_images(NULL, $start, 3);
        if(!empty($data['rows'])){
        	$array['postDetails']  = $this->load->view('frontend/result_listing', $data, TRUE);
        }else{
        	$array['postDetails']  = '';
        }
        //$array['postDetails']  = $start.'<br/><br/>';
        echo json_encode($array);
	}
	public function chartData(){	
		$mainArr = $lineDays = array();	
		$user_id = user_id();
		if($this->input->post('user_id')){
			$user_id = $this->input->post('user_id');
		}
		if($this->input->post('tab_types')=='weekly'){	
		  	$allMonthNames = array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');
		  	$wday          = date('w')-1;
    		$startTimeZone = strtotime('-'.$wday.' days');
    		$startTimeZone = strtotime(date('Y-m-d', $startTimeZone));
			for($mi=0;$mi<date('7');$mi++){				
    			$dateTimes 	   = $startTimeZone + (86400*$mi);
    			$dayRecords    = $this->common_model->get_row('metricTracker', array('matrixDate'=>$dateTimes, 'user_id'=>$user_id));
    			/*echo 'date '.$startTimeZone.' date= '.date('Y-m-d ', $dateTimes).'<br/>';
    			print_r($dayRecords);*/ 
				$colData = array();
				$colData[] = $allMonthNames[$mi];
				if($this->input->post('height')){
					$heightNum = 0;
					if(!empty($dayRecords->height)){
						$heightNum = $dayRecords->height;
						if(strpos($dayRecords->height, "'")>0){
							$heightNum = str_replace("'", ".", $dayRecords->height);
						}
						if(strpos($heightNum, "cm")>0){
							$heightNum = str_replace("cm", "", $dayRecords->height);
						}
						if(strpos($heightNum, " ")>0){
							$heightNum = str_replace(" ", "", $dayRecords->height);
						}
						$heightNum = is_numeric($heightNum)?$heightNum:0;
					}
					$heightNum =(float)$heightNum;
					//echo 'heightNum = '.$heightNum.'<br/>';
					$colData[] = $heightNum;
				}
				if($this->input->post('weight')){
					$weightNum = 0;
					if(!empty($dayRecords->weight)){
						$weightNum = $dayRecords->weight;
						if(strpos($dayRecords->weight, "lbs")>0){
							$weightNum = str_replace("lbs", "", $dayRecords->weight);
						}
						if(strpos($weightNum, "kg")>0){
							$weightNum = str_replace("kg", "", $weightNum);
						}
						if(strpos($weightNum, " ")>0){
							$weightNum = str_replace(" ", "", $weightNum);
						}
						$weightNum = is_numeric($weightNum)?$weightNum:0;
					}
					$colData[] = (float)$weightNum;
				}
				if($this->input->post('caloriesBurned')){					
					$colData[] = (!empty($dayRecords->calBurned)&&is_numeric($dayRecords->calBurned))?(float)$dayRecords->calBurned:0;
				}
				if($this->input->post('caloriesConsumed')){
					$colData[] = (!empty($dayRecords->calConsumed)&&is_numeric($dayRecords->calConsumed))?(float)$dayRecords->calConsumed:0;
				}
				if($this->input->post('bodyShots')){
					$colData[] = (!empty($dayRecords->bodyShot)&&is_numeric($dayRecords->bodyShot))?(float)$dayRecords->bodyShot:0;
				}
				if($this->input->post('chest')){
					$colData[] = (!empty($dayRecords->chest)&&is_numeric($dayRecords->chest))?(float)$dayRecords->chest:0;
				}
				if($this->input->post('waist')){
					$colData[] = (!empty($dayRecords->waist)&&is_numeric($dayRecords->waist))?(float)$dayRecords->waist:0;
				}	
				if($this->input->post('arms')){
					$colData[] = (!empty($dayRecords->arms)&&is_numeric($dayRecords->arms))?(float)$dayRecords->arms:0;
				}				
				if($this->input->post('forearms')){
					$colData[] = (!empty($dayRecords->forearms)&&is_numeric($dayRecords->forearms))?(float)$dayRecords->forearms:0;
				}					
				if($this->input->post('legs')){
					$colData[] = (!empty($dayRecords->legs)&&is_numeric($dayRecords->legs))?(float)$dayRecords->legs:0;
				}
				if($this->input->post('calves')){
					$colData[] = (!empty($dayRecords->calves)&&is_numeric($dayRecords->calves))?(float)$dayRecords->calves:0;
				}				
				if($this->input->post('hips')){
					$colData[] = (!empty($dayRecords->hips)&&is_numeric($dayRecords->hips))?(float)$dayRecords->hips:0;
				}	
				if($this->input->post('bodyFat')){
					$colData[] = (!empty($dayRecords->bodyShot)&&is_numeric($dayRecords->bodyShot))?(float)$dayRecords->bodyShot:0;
				}			
				if($this->input->post('dietPlan')){
					$colData[] = (!empty($dayRecords->bodyShot)&&is_numeric($dayRecords->bodyShot))?(float)$dayRecords->bodyShot:0;
				}				
				if($this->input->post('workoutPlan')){
					$colData[] = (!empty($dayRecords->bodyShot)&&is_numeric($dayRecords->bodyShot))?(float)$dayRecords->bodyShot:0;
				}
				$mainArr[] = $colData;	
			}	
		}else if($this->input->post('tab_types')=='monthly'){
			$startTimeZone = strtotime(date('Y-m-01'));
			$miT           = 0;
			for($mi=1;$mi<=date('t');$mi++){
				$colData = array();
				$dateTimes 	   = $startTimeZone + (86400*$mi);
				$dateTimesN    = $startTimeZone + (86400*$miT);
    			$dayRecords    = $this->common_model->get_row('metricTracker', array('matrixDate'=>$dateTimes, 'user_id'=>$user_id)); 
				$colData = array();
				$colData[] = date('jS', $dateTimesN);
				if($this->input->post('height')){
					$heightNum = 0;
					if(!empty($dayRecords->height)){
						$heightNum = $dayRecords->height;
						if(strpos($dayRecords->height, "'")>0){
							$heightNum = str_replace("'", ".", $dayRecords->height);
						}
						if(strpos($heightNum, "cm")>0){
							$heightNum = str_replace("cm", "", $dayRecords->height);
						}
						if(strpos($heightNum, " ")>0){
							$heightNum = str_replace(" ", "", $dayRecords->height);
						}
						$heightNum = is_numeric($heightNum)?$heightNum:0;
					}
					$heightNum =(float)$heightNum;
					$colData[] = $heightNum;
				}
				if($this->input->post('weight')){
					$weightNum = 0;
					if(!empty($dayRecords->weight)){
						$weightNum = $dayRecords->weight;
						if(strpos($dayRecords->weight, "lbs")>0){
							$weightNum = str_replace("lbs", "", $dayRecords->weight);
						}
						if(strpos($weightNum, "kg")>0){
							$weightNum = str_replace("kg", "", $weightNum);
						}
						if(strpos($weightNum, " ")>0){
							$weightNum = str_replace(" ", "", $weightNum);
						}
						$weightNum = is_numeric($weightNum)?$weightNum:0;
					}
					$colData[] = (float)$weightNum;
				}
				if($this->input->post('caloriesBurned')){					
					$colData[] = (!empty($dayRecords->calBurned)&&is_numeric($dayRecords->calBurned))?(float)$dayRecords->calBurned:0;
				}
				if($this->input->post('caloriesConsumed')){
					$colData[] = (!empty($dayRecords->calConsumed)&&is_numeric($dayRecords->calConsumed))?(float)$dayRecords->calConsumed:0;
				}
				if($this->input->post('bodyShots')){
					$colData[] = (!empty($dayRecords->bodyShot)&&is_numeric($dayRecords->bodyShot))?(float)$dayRecords->bodyShot:0;
				}
				if($this->input->post('chest')){
					$colData[] = (!empty($dayRecords->chest)&&is_numeric($dayRecords->chest))?(float)$dayRecords->chest:0;
				}
				if($this->input->post('waist')){
					$colData[] = (!empty($dayRecords->waist)&&is_numeric($dayRecords->waist))?(float)$dayRecords->waist:0;
				}	
				if($this->input->post('arms')){
					$colData[] = (!empty($dayRecords->arms)&&is_numeric($dayRecords->arms))?(float)$dayRecords->arms:0;
				}				
				if($this->input->post('forearms')){
					$colData[] = (!empty($dayRecords->forearms)&&is_numeric($dayRecords->forearms))?(float)$dayRecords->forearms:0;
				}					
				if($this->input->post('legs')){
					$colData[] = (!empty($dayRecords->legs)&&is_numeric($dayRecords->legs))?(float)$dayRecords->legs:0;
				}
				if($this->input->post('calves')){
					$colData[] = (!empty($dayRecords->calves)&&is_numeric($dayRecords->calves))?(float)$dayRecords->calves:0;
				}				
				if($this->input->post('hips')){
					$colData[] = (!empty($dayRecords->hips)&&is_numeric($dayRecords->hips))?(float)$dayRecords->hips:0;
				}	
				if($this->input->post('bodyFat')){
					$colData[] = (!empty($dayRecords->bodyShot)&&is_numeric($dayRecords->bodyShot))?(float)$dayRecords->bodyShot:0;
				}			
				if($this->input->post('dietPlan')){
					$colData[] = (!empty($dayRecords->bodyShot)&&is_numeric($dayRecords->bodyShot))?(float)$dayRecords->bodyShot:0;
				}				
				if($this->input->post('workoutPlan')){
					$colData[] = (!empty($dayRecords->bodyShot)&&is_numeric($dayRecords->bodyShot))?(float)$dayRecords->bodyShot:0;
				}
				$mainArr[] = $colData;	
				$miT++;
			}		
		}else{
			$allMonthNames = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
			$monStr = 1;
			for($mi=0;$mi<date('12');$mi++){
				$colData 	   = array();
				$monStrT       = ($monStr<10)?'0'.$monStr:$monStr;
				$startDate     = strtotime(date('Y-'.$monStrT.'-01'));
				$endDate       = strtotime(date('Y-'.$monStrT.'-'.date('t', $startDate)));				
    			$dayRecords    = $this->developer_model->get_avrage_by_month($startDate, $endDate, $user_id);
    			/*echo 'date '.$startDate.' date= '.date('Y-m-d ', $startDate).'<br/>';
    			echo 'date '.$endDate.' date= '.date('Y-m-d ', $endDate).'<br/>';
    			print_r($dayRecords); */
				$colData = array();
				$colData[] = $allMonthNames[$mi];
				if($this->input->post('height')){
					$colData[] = (float)$dayRecords['height'];
				}
				if($this->input->post('weight')){
					$colData[] = (float)$dayRecords['weight'];
				}
				if($this->input->post('caloriesBurned')){					
					$colData[] = (!empty($dayRecords['calBurned'])&&is_numeric($dayRecords['calBurned']))?(float)$dayRecords['calBurned']:0;
				}
				if($this->input->post('caloriesConsumed')){
					$colData[] = (!empty($dayRecords['calConsumed'])&&is_numeric($dayRecords['calConsumed']))?(float)$dayRecords['calConsumed']:0;
				}
				if($this->input->post('bodyShots')){
					$colData[] = (!empty($dayRecords['bodyShot'])&&is_numeric($dayRecords['bodyShot']))?(float)$dayRecords->bodyShot:0;
				}
				if($this->input->post('chest')){
					$colData[] = (!empty($dayRecords['chest'])&&is_numeric($dayRecords['chest']))?(float)$dayRecords['chest']:0;
				}
				if($this->input->post('waist')){
					$colData[] = (!empty($dayRecords['waist'])&&is_numeric($dayRecords['waist']))?(float)$dayRecords['waist']:0;
				}	
				if($this->input->post('arms')){
					$colData[] = (!empty($dayRecords['arms'])&&is_numeric($dayRecords['arms']))?(float)$dayRecords['arms']:0;
				}				
				if($this->input->post('forearms')){
					$colData[] = (!empty($dayRecords['forearms'])&&is_numeric($dayRecords['forearms']))?(float)$dayRecords['forearms']:0;
				}					
				if($this->input->post('legs')){
					$colData[] = (!empty($dayRecords['legs'])&&is_numeric($dayRecords['legs']))?(float)$dayRecords['legs']:0;
				}
				if($this->input->post('calves')){
					$colData[] = (!empty($dayRecords['calves'])&&is_numeric($dayRecords['calves']))?(float)$dayRecords['calves']:0;
				}				
				if($this->input->post('hips')){
					$colData[] = (!empty($dayRecords['hips'])&&is_numeric($dayRecords['hips']))?(float)$dayRecords['hips']:0;
				}	
				if($this->input->post('bodyFat')){
					$colData[] = (!empty($dayRecords['bodyShot'])&&is_numeric($dayRecords['bodyShot']))?(float)$dayRecords['bodyShot']:0;
				}			
				if($this->input->post('dietPlan')){
					$colData[] = (!empty($dayRecords['bodyShot'])&&is_numeric($dayRecords['bodyShot']))?(float)$dayRecords['bodyShot']:0;
				}				
				if($this->input->post('workoutPlan')){
					$colData[] = (!empty($dayRecords['bodyShot'])&&is_numeric($dayRecords['bodyShot']))?(float)$dayRecords['bodyShot']:0;
				}
				$mainArr[] = $colData;	
				$monStr++;	
			}	
		}
		$lineDays = array();
		if($this->input->post('height')){
			$lineDays[] = 'Height';
		}
		if($this->input->post('bodyShots')){
			$lineDays[] = 'Body Shots';
		}
		if($this->input->post('waist')){
			$lineDays[] = 'Waist';
		}
		if($this->input->post('bodyFat')){
			$lineDays[] = 'Body Fat';
		}
		if($this->input->post('weight')){
			$lineDays[] = 'Weight';
		}
		if($this->input->post('chest')){
			$lineDays[] = 'Chest';
		}
		if($this->input->post('hips')){
			$lineDays[] = 'Hips';
		}
		if($this->input->post('workoutPlan')){
			$lineDays[] = 'Workout Plan';
		}
		if($this->input->post('caloriesConsumed')){
			$lineDays[] = 'Calories Consumed';
		}
		if($this->input->post('arms')){
			$lineDays[] = 'Arms';
		}
		if($this->input->post('legs')){
			$lineDays[] = 'Legs';
		}
		if($this->input->post('dietPlan')){
			$lineDays[] = 'Diet Plan';
		}
		if($this->input->post('caloriesBurned')){
			$lineDays[] = 'Calories Burned';
		}
		if($this->input->post('forearms')){
			$lineDays[] = 'Forearms';
		}
		if($this->input->post('calves')){
			$lineDays[] = 'Calves';
		}
		$user = user_info($user_id);
		$sufixText = '';
		$heightT   =  ($this->input->post('height'))?TRUE:FALSE;
		$weightT   =  ($this->input->post('weight'))?TRUE:FALSE;
		$this->input->post('weight');
		if(!empty($heightT)&&empty($weightT)){
			if(!empty($user->useMetricsSystem)&&$user->useMetricsSystem==1){
				$sufixText = 'cm';
			}else{
				$sufixText = 'Feet';
			}
		}else if(empty($heightT)&&!empty($weightT)){
			if(!empty($user->useMetricsSystem)&&$user->useMetricsSystem==1){
				$sufixText = 'kg';
			}else{
				$sufixText = 'lbs';
			}
		}
		$data = array('lineCols'=>$lineDays, 'rowD' => $mainArr, 'sufixText'=>$sufixText);
		echo json_encode($data);
	}
	public function testData(){
		//echo date('D');
		$this->load->view('templates/test_data');
	} 
	public function chat(){
		//echo date('D');
		echo 'Chat in out site';
	}
	public function chart(){
		//echo date('D');
		$this->load->view('frontend/chart');
	} 
	public function login(){
		$loginResponce = array();	
		$this->form_validation->set_rules('email', 'user name', 'required|xss_clean');
		$this->form_validation->set_rules('password', 'password', 'required|xss_clean');
		if($this->form_validation->run() == TRUE){
			$email    = $this->input->post('email', TRUE);
			$password = $this->input->post('password', TRUE);
			$response = $this->common_model->front_user_login($email, $password);
			if($response==1){
				if(user_logged_in()){
					$user = user_info();
					if(!empty($user->profile_view_status)&&$user->profile_view_status==1){
						$loginResponce = array('status' => 'true',
												'message'   => 'Your Login successfully.',
												'redirect_url' =>'user/dashboard'
											   );
					}else{
						$loginResponce = array( 'status' => 'true',
												'message'   => 'Your Login successfully.',
												'redirect_url' =>'user/dashboard?acntype=edit_profile'
											  );
					}					
				}else{
					$loginResponce = array( 'status'   => 'false',
						 					'message'  => 'Your account has been banned from using the Roky platform. If you believe this has been in error, then please contact support@roky.ca'
								);
				}
			}else if($response==2){
				$loginResponce = array('status'   => 'false',
									   'message' => 'Your account has been banned from using the Roky platform. If you believe this has been in error, then please contact support@roky.ca'
									 );
			}else if($response==3){
				$loginResponce = array('status'   => 'false',
									   'message'  => 'Your account is not verified'
									 );
			}else if($response==4){
				$loginResponce = array('status'   => 'false',
									   'message'  => 'The username or password is invalid.'
									 );
			}else{
				$loginResponce = array('status'   => 'false',
										'message' => 'The username or password is invalid.'
										);
			}
		}else{
			$loginResponce = array('status'   => 'false',
							'message' => validation_errors()
						  );
		}
		echo json_encode($loginResponce);
	}		
	public function signup(){
		if(user_logged_in()){ 
			redirect('user/profile');
		}
		$data['template'] 		= 'frontend/signup';
	    $this->load->view('templates/frontend_template', $data);
	}
	public function signup_res(){	
		$loginResponce 	= array();
		$this->form_validation->set_rules('name', 'name', 'required|xss_clean');
		$this->form_validation->set_rules('user_name', 'user name', 'required|alpha_numeric_spaces|xss_clean|callback_userNameChecked');
		$this->form_validation->set_rules('email', 'email', 'required|valid_email|xss_clean|callback_emailChecked');
		$this->form_validation->set_rules('password', 'password', 'required|xss_clean');
		if ($this->form_validation->run() == TRUE){	
			$activationcode            	= rand().time();
			$newSalt            	   	= salt();
			$password  				   	= passwordGenrate($this->input->post('password', TRUE), $newSalt);
			$signup 			 	   	= array();
			if($this->input->post('name'))
				$signup['first_name']	= $this->input->post('name', TRUE);
			if($this->input->post('user_name'))
				$signup['user_name']	= $this->input->post('user_name', TRUE);
			$signup['email']	 	   	= $this->input->post('email', TRUE);
			$signup['activation_code'] 	= $activationcode ;	 
			$signup['created_date']     = date('Y-m-d H:i:s');
			$signup['password'] 	   	= $password; 
			$signup['salt']				= $newSalt;
			$insert = $this->common_model->insert('users', $signup);
			if(!empty($insert)){ 		
				$response = $this->common_model->front_user_login($this->input->post('email', TRUE), $this->input->post('password', TRUE));				
				$loginResponce = array('status' 		=> 'true',
										'message'   	=> "Account created with success message",
										'redirect_url' 	=>'user/dashboard?acntype=edit_profile'
									  );
				/*------------------activation mail code-------------------------------------*/
				/*$site_title  	= site_info('site_title');
				$loginResponce = array('status' 	=> 'true',
										'message'   => "<br/>Your Registration is successful.<br/> welcome to <b>".ucwords($site_title)."</b>  <b> Email address verification needed</b><br/>Before you can login, please check your email to activate your user account. if you don't receive an email within a few secounds, please check your spam."
									  );
				$email_template = $this->cimail_email->get_email_template(6);
				$activate_link  = base_url('home/activateAccount/'.$activationcode);				
				$mail_from_name	= site_info('mail_from_name');
				$mail_from_email= site_info('mail_from_email');
				$param = array(
							'template'  	=> array(
							'temp'  		=> $email_template->template_body,
							'var_name' 		=> array(
													'username'  	=> $this->input->post('name'),
													'site_name' 	=>  $site_title,	
													'activate_link' =>  $activate_link,	
													), 
							),      

					'email' =>  array(
									'to'        =>  $this->input->post('email'),
									'from'      =>  $mail_from_email,
									'from_name' =>  $mail_from_name,
									'subject'   =>  $email_template->template_subject,
									)
				);  				
				$status  = $this->cimail_email->send_mail($param);*/	
			}else{
				$loginResponce = array('status' 	=> 'true',
										'message'   => 'Registration failed, try again'
									   );

			}
		}else{
			$loginResponce = array('status'   => 'false',
									'message' => validation_errors()
						  		  );
		}
		echo json_encode($loginResponce);
	}
	public function checkUserName(){	
		$loginResponce = array();		
		$this->form_validation->set_rules('user_name', 'user name', 'required|alpha_numeric|callback_userNameChecked');
		if ($this->form_validation->run() == TRUE){	
			$loginResponce = array('status'   => 'true',
									'message' => 'available'
						  		  );
		}else{
			$loginResponce = array('status'   => 'false',
									'message' => validation_errors()
						  		  );
		}
		echo json_encode($loginResponce);
	}
	public function checkUserEmail(){	
		$loginResponce = array();		
		$this->form_validation->set_rules('email', 'email', 'required|xss_clean|valid_email|callback_emailChecked');
		if ($this->form_validation->run() == TRUE){	
			$loginResponce = array('status'   => 'true',
									'message' => 'available'
						  		  );
		}else{
			$loginResponce = array('status'   => 'false',
									'message' => validation_errors()
						  		  );
		}
		echo json_encode($loginResponce);
	}
	public function profile_pic(){          
        $array = array('statuss'=>'false', 'message'=>'') ;    
        $this->form_validation->set_rules('user_img','','trim|xss_clean|callback_user_image_check'); 
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>'); 
        if ($this->form_validation->run() == TRUE){ 
            $userdata                   = array();
            if($this->session->userdata('user_image_check')!=''){
                $user_image_check=$this->session->userdata('user_image_check');
                $userdata['file'] = $user_image_check['user_img'];
                $this->session->unset_userdata('user_image_check');  
                $array = array('statuss'=>'true','message'=>'profile pic changed','file_name'=>$user_image_check['user_img'],'full_path'=>base_url().'assets/uploads/users/thumbnails/'.$user_image_check['user_img']) ;     
            }
        }else{
            $array = array('statuss'=>'false','message'=>form_error('user_img')) ;    
        }
        echo json_encode($array);
    } 
    public function user_image_check($str){
        $allowed = array("image/jpeg", "image/jpg", "image/png"); 
          if(empty($_FILES['user_img']['name'])){
              $this->form_validation->set_message('user_image_check', 'Choose logo');
              return FALSE;
           }
          if(!in_array($_FILES['user_img']['type'], $allowed)) {
            $this->form_validation->set_message('user_image_check', 'Only jpg, jpeg, and png files are allowed');
              return FALSE;
        }
           $image = getimagesize($_FILES['user_img']['tmp_name']);
           if ($image[0] < 100 || $image[1] < 100) {
               $this->form_validation->set_message('user_image_check', 'Oops! Your profile pic needs to be atleast 100 x 100 pixels');
               return FALSE;
           }
           if ($image[0] > 2000 || $image[1] > 2000) {
               $this->form_validation->set_message('user_image_check', 'Oops! your profile pic needs to be maximum of 2000 x 2000 pixels');
               return FALSE;
           }
        if(!empty($_FILES['user_img']['name'])):
            $config['encrypt_name'] 	= TRUE;
            $new_name 				    = 'image_'.substr(md5(rand()),0,7).$_FILES["user_img"]['name'];
            $config['file_name'] 		= $new_name;
            $config['upload_path'] 		= 'assets/uploads/users/';
            $config['allowed_types'] 	= 'jpeg|jpg|png';
            $config['max_size']  		= '7024';
            $config['max_width']  		= '2000';
            $config['max_height']   	= '2000';
            $this->load->library('upload', $config);
            if ( ! $this->upload->do_upload('user_img')){
                $this->form_validation->set_message('user_image_check', $this->upload->display_errors());
                return FALSE;
            }else{
                $data = $this->upload->data(); // upload image
                $config_img_p['source_path'] = 'assets/uploads/users/';
                $config_img_p['destination_path'] = 'assets/uploads/users/thumbnails/';
                $config_img_p['width']      = '250';
                $config_img_p['height']     = '250';
                $config_img_p['file_name']  = $data['file_name'];
                $status=create_thumbnail($config_img_p);
                $this->session->set_userdata('user_image_check',array('image_url'=>$config['upload_path'].$data['file_name'],
                     'user_img'=>$data['file_name']));
                unlink('assets/uploads/users/'.$data['file_name']);
                return TRUE;
            } 
        else:
            $this->form_validation->set_message('user_image_check', 'The %s field required.');
            return FALSE;
            endif;
    } 
  	public function update_password($key=""){
  		if(empty($key)){
  			redirect(base_url('home/error_404'));
  		}
  		if(!empty($key)){
  			$status = $this->common_model->get_row('users', array('activation_code'=>$key));
  			if(empty($status)){
  				redirect(base_url('home/error_404'));
  			}  			
  		}
  		if(isset($_POST['submit'])){
	        $this->form_validation->set_rules('newpassword','New password','required|xss_clean');
	        $this->form_validation->set_rules('confpassword','Confirm password','required|xss_clean|matches[newpassword]');
	        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
	        if($this->form_validation->run()==true){
				$newSalt      = salt();  
				$password     = passwordGenrate($this->input->post('newpassword', TRUE), $newSalt);
				$where        = array('activation_code'=>$key);
				$data         = array('password'=>$password, 'salt'=>$newSalt);
				$success      = $this->common_model->update('users', $data, $where);
				if($success){
					$this->session->set_flashdata('msg_success', 'Password is updated successfully');
					redirect(base_url('?acntype=login'));	
				}else{
					$this->session->set_flashdata('msg_error', "Password couldn't update, try again.");
					redirect(base_url('home/update_password/'.$userId));
				}
	        }
	    }  
	    $data['template'] 		= 'frontend/newPassword';
	    $this->load->view('templates/frontend_template', $data);  
	}    
	public	 function activateAccount($id=''){	
		$row = $this->common_model->get_row('users', array('activation_code'=>$id,'is_email_verify'=>0));
		if($row){	
			$update = $this->common_model->update('users', array('is_email_verify'=>1), array('id'=>$row->id));
			if($update){
				$this->session->set_flashdata('msg_success', 'Your account is activated successfuly');
				redirect(base_url('?acntype=login'));			    
			}
		}else{
			redirect(base_url('?acntype=login'));	
		}
	}	
  	public function forgot_password(){	
	    if(isset($_POST['submit'])){  
	        $this->form_validation->set_rules('email','Email','required|xss_clean');
	        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
	      	if($this->form_validation->run()==true){
	      		$email          = $this->input->post('email');
	      		$where 			= "`email` = '".$email."' OR `user_name` = '".$email."'";
	      		$row 			= $this->common_model->get_row('users', array(), array(), array(), $where);
	      		if(!empty($row)){
	      			$where          = array('email' => $row->email);
			        $activationcode = uniqid();
			        $data           = array('activation_code'=>$activationcode);
			        $success        = $this->common_model->update('users', $data, $where);
			        if($success){
						$url      		= base_url()."home/update_password/".$activationcode;	
						$email_template = $this->cimail_email->get_email_template(2);
						$activate_link  = base_url('home/update_password/'.$activationcode);
						$site_title 	= site_info('site_title');
						$mail_from_name = site_info('mail_from_name');
						$mail_from_email= site_info('mail_from_email');
						$param=array(
									'template'  	=> array(
									'temp'  		=> $email_template->template_body,
									'var_name' 		=> array(
															'name'  	     => $row->first_name.' '.$row->last_name,
															'site_name' 	 => $site_title,	
															'activation_key' => $activate_link,		
															'site_url'       => base_url()
															), 
									),      
							'email' =>  array(

											'to'        =>  $row->email,
											'from'      =>  $mail_from_name,
											'from_name' =>  $mail_from_email,
											'subject'   =>  $email_template->template_subject,
											)
						);  

						$status = $this->cimail_email->send_mail($param); 
						print_r($status); exit();
						if($status['EMAIL_STATUS']==1){
							$this->session->set_flashdata('msg_success', 'If the provided email address/username is registered then a password reset link will be sent to your email.');
						}else{
							$this->session->set_flashdata('msg_success', 'If the provided email address/username is registered then a password reset link will be sent to your email.');
						}					
						redirect(base_url('home/forgot_password'));
			        }
	      		}else{
      				$this->session->set_flashdata('msg_success', 'If the provided email address/username is registered then a password reset link will be sent to your email.');
      				redirect(base_url('home/forgot_password'));
				}		        
	        }
	    }  
	    $data['template'] 		= 'frontend/forgot_password';
	    $this->load->view('templates/frontend_template', $data);
	}	
    public function sendForgotPasswordMail(){
    	$mailResponse = array('status' =>'false', 'message'=>'The email is invalid');
    	$this->form_validation->set_rules('forgotEmail', 'Email', 'trim|xss_clean|required|valid_email');
    	if ($this->form_validation->run() == TRUE){
        	$forgotEmail = $this->input->post('forgotEmail', TRUE); 
        	$user 	= $this->common_model->get_row('users', array('email' =>$forgotEmail),array('id','email','first_name')); 
           if($user){

           	  $activationCode = substr(time().$user->id,-6);

           	  if($this->common_model->update('users',array('activation_code'=>$activationCode), array('id'=>$user->id))){

           	  		$this->load->library('Cimail_email');

			        $subject = $this->common_model->get_row('email_templates',array('id'=>3));

			        $link= base_url().'home/reset_password/'.$activationCode; 

			        $email_template=$this->cimail_email->get_email_template(2);

			        $site_url = "<a href=".base_url()." target='_blank'>".SITE_NAME."</a>";

			        $param=array(

			        'template'  =>  array(
					                        'temp'  =>  $email_template->template_body,
					                        'var_name'  =>  array(
					                        'name'  => $user->first_name,
					                        'activation_key'  =>  $link,
					                        'site_url' =>  $site_url,  
			                        	), 
			        ),          

			        'email' =>  array(
			                'to'        =>   $user->email,
			                'from'      =>   NO_REPLY,
			                'from_name' =>   NO_REPLY_EMAIL_FROM_NAME,
			                'subject'   =>   $email_template->template_subject,
			            )
			        );  
			        $status=$this->cimail_email->send_mail($param);
	           	  	$mailResponse = array('status' =>'true',
	           	  				   'message'=>'A password reset link will be sent to your email.'
	           	  				 );	
           	  	}  
           }else{
           	 	$mailResponse = array('status' =>'false',
           	  				 'message'=>'Email does not  exist.'
           	  				 );
           }
       }              		
        echo json_encode($mailResponse);
    } 	

    public function reset_password($activation_key=''){   
      $data['title']='Reset password';  
      if(empty($activation_key)){ redirect(base_url('?acntype=login')); }
      if(!empty($activation_key)){
      	$user = $this->common_model->get_row('users',array('activation_code'=>trim($activation_key)));
      	if($user==FALSE){
          $this->session->set_flashdata('msg_error','Your activation key expired.');
          redirect(base_url('?acntype=login'));
       	} 
      }
      $this->form_validation->set_rules('password', 'New Password', 'trim|required|xss_clean|min_length[6]|matches[confpassword]');
      $this->form_validation->set_rules('confpassword','Confirm Password', 'trim|xss_clean|required');
      $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
      	if ($this->form_validation->run() == TRUE){
          	$user = $this->common_model->get_row('users',array('activation_code'=>trim($activation_key)));
            if($user==FALSE){
              $this->session->set_flashdata('msg_error','Your activation key expired.');
              redirect(base_url('?acntype=login'));
           } 
            $salt = salt();
		    $user_data  = array('salt'=>$salt,
								'password' => sha1($salt.sha1($salt.sha1($this->input->post('password', TRUE)))),
								'activation_code'=>''
								);
            if($this->common_model->update('users', $user_data, array('id'=>$user->id))){ 
                $this->session->set_flashdata('msg_success','Your Password has reset successfully <br/> now you can Login.');
                redirect(base_url('?acntype=login'));
            }
      }
      $data['template'] 		= 'frontend/newPassword';
	  $this->load->view('templates/frontend_template', $data);
    }     
    /*validation function*/
    public function emailChecked($email){  
		$confirm    = $this->common_model->get_row('users', array('email'=>$email));
		if($confirm){
			$this->form_validation->set_message('emailChecked','The entered email is already registered.');
			return false;
		}else{ 
			return true;
		}
    }  
    public function userNameChecked($email){  
		$confirm    = $this->common_model->get_row('users', array('user_name'=>$email));
		if($confirm){
			$this->form_validation->set_message('userNameChecked','The selected username is already taken.');
			return false;
		}else{ 
			return true;
		}
    }
	public function emailCheckedforgot($email){ 
		$where = "`email` = '".$email."' OR `user_name` = '".$email."'";
		$confirm    = $this->common_model->get_row('users', array(), array(), array(), $where);		
		if($confirm){ 
			return TRUE;
		}else{    
			$this->form_validation->set_message('emailCheckedforgot', 'The email or user name is not registered');
			return FALSE;
		}
	}
	public function mobileChecked($mobile){  
		$confirm    = $this->common_model->get_row('users', array('mobile'=>$mobile));
		if($confirm){ 
			$this->form_validation->set_message('mobileChecked','The %s  is  already exist.');
			return false;
		}else{  
			return true;
		}
	}	
	public function newsletter(){
		$array 	  = array('status'=>'false', 'message'=>'');
		if($this->input->post('email')){
			$email = $this->input->post('email', TRUE);
	        if($this->common_model->get_row('newsletters', array('email'=>$email))){
	        	$array = array('status'=>'true', 'message'=>'Thank you for your subscription.');
	        }else{
	        	$this->common_model->insert('newsletters', array('email'=>$email, 'created_date'=>date('Y-m-d H:i:s')));
	        	$array = array('status'=>'true', 'message'=>'Thank you for your subscription.');
	        }
		}
        echo json_encode($array);
	}
	public function contact_us(){
		$this->form_validation->set_rules('name','name','trim|required');
        $this->form_validation->set_rules('email','email','trim|required|xss_clean|valid_email');
        $this->form_validation->set_rules('phone','phone','trim|required|xss_clean|numeric');
        $this->form_validation->set_rules('message','message','trim|xss_clean|required'); 
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run() == TRUE){ 
            $userdata                   = array();
            if($this->input->post('name'))
                $userdata['name']     = $this->input->post('name', TRUE);
            if($this->input->post('email'))
                $userdata['email']    = $this->input->post('email', TRUE); 
            if($this->input->post('phone'))
                $userdata['mobile']   = $this->input->post('phone', TRUE); 
            if($this->input->post('message'))
                $userdata['message']  = $this->input->post('message', TRUE);
            $this->common_model->insert('contact_us', $userdata);
               $this->session->set_flashdata('msg_success', 'You are  contact is successfull');
            redirect('home/contact_us');
        } 
		$data['title']     = 'contact';
		$data['template']  = 'frontend/contact';
	    $this->load->view('templates/frontend_template',$data);
	}
	public function error_404(){	
	    $data['template'] = 'frontend/error_404';
	    $this->load->view('templates/frontend_template',$data);
	} 
	public	 function search_result(){		
		$data['gridCol']	   = 3;
		$data['rows'] 	  = $this->developer_model->get_recipe_blog_images(NULL,0,21);	
		//echo $this->db->last_query().'<br/>'; exit();
		$data['hrows'] 	  = $this->developer_model->get_recipe_blog_images_hash(NULL,0,21);	
		//echo $this->db->last_query().'<br/>'; exit();
		$data['users'] 	  = $this->developer_model->get_users();	
		$data['template'] = 'frontend/search_result';
	    $this->load->view('templates/user_template', $data);
	}
	public	 function search(){			
		$data['rows']  = $this->developer_model->get_recipe_blog_images();
		$this->load->view('frontend/result_listing', $data);
	}
	public	 function post_details(){
		if(user_logged_in()){ 
			$insertedData 						  = array();		
			$insertedData['user_id'] 			  = user_id();		
			$insertedData['recipe_blog_image_id'] = ($this->input->get('post_id'))?$this->input->get('post_id'):'';
			$insertedData['created_date'] 		  = date('Y-m-d H:i:s');	
			$this->common_model->insert('post_views', $insertedData);	
		}			
		$data['row'] 	     = $this->developer_model->get_post_details();
		$data['previewPost'] = $this->developer_model->getPreviewNext('prev');
		$data['nextPost'] 	 = $this->developer_model->getPreviewNext('next');
		$data['viewUser']    = ($this->input->get('viewUser'))?$this->input->get('viewUser'):0;
		$data['comments']    = $this->developer_model->get_comments($this->input->get('post_id'));;
		//print_r($data['comments'] ); exit();
		$data['imgesArrs']   = $this->common_model->get_result('images', array('meta_id'=>$this->input->get('post_id'), 'status'=>1));
		$this->load->view('frontend/post_details', $data);
	}
	public	 function PostLike(){	
		$postID = $this->input->get('post_id');
		if(user_logged_in()){		
			if($this->common_model->get_row('likes', array('recipe_blog_image_id'=>$postID, 'user_id'=>user_id()))){
				$this->common_model->delete('likes', 
													array('recipe_blog_image_id'=>$postID, 
														  'user_id'=>user_id()
														)
											);	
				$jsonResponse = array('status' => 'true', 'message' => '<i class="fa fa-thumbs-o-up" aria-hidden="true"></i> <span id="post_likes_counters">'.get_all_count('likes', array('recipe_blog_image_id'=>$postID)).'</span>', 'likeCounter'=>get_all_count('likes', array('recipe_blog_image_id'=>$postID)));
			}else{
				$insertedData 						  = array();		
				$insertedData['user_id'] 			  = user_id();		
				$insertedData['recipe_blog_image_id'] = $postID;		
				$insertedData['created_date'] 		  = date('Y-m-d H:i:s');	
				$this->common_model->insert('likes', $insertedData);	
				$jsonResponse = array('status' => 'true', 'message' => '<i class="fa fa-thumbs-up" aria-hidden="true"></i> <span id="post_likes_counters">'.get_all_count('likes', array('recipe_blog_image_id'=>$postID)).'</span>',  'likeCounter'=>get_all_count('likes', array('recipe_blog_image_id'=>$postID)));
			}
		}else{
			$jsonResponse = array('status' => 'false', 'message' => 'Please <a href="'.base_url().'?acntype=login">login</a> or <a href="'.base_url().'">register</a> to like.');
		}
		echo json_encode($jsonResponse);
	}
	public	 function bookMarkPost(){	
		$postID = $this->input->get('post_id');
		if(user_logged_in()){		
			if($this->common_model->get_row('bookmark', array('recipe_blog_image_id'=>$postID))){
				$this->common_model->delete('bookmark', 
													array('recipe_blog_image_id'=>$postID, 
														  'user_id'=>user_id()
														)
											);	
				$jsonResponse = array('status' => 'true', 'message' => '<i class="fa fa-bookmark-o" aria-hidden="true"></i>');
			}else{
				$insertedData 						  = array();		
				$insertedData['user_id'] 			  = user_id();		
				$insertedData['recipe_blog_image_id'] = $postID;		
				$insertedData['created_date'] 		  = date('Y-m-d H:i:s');	
				$this->common_model->insert('bookmark', $insertedData);	
				$jsonResponse = array('status' => 'true', 'message' => '<i class="fa fa-bookmark" aria-hidden="true"></i>');
			}
		}else{
			$jsonResponse = array('status' => 'false', 'message' => 'Please <a href="'.base_url().'?acntype=login">login</a> or <a href="'.base_url().'">register</a> to bookmark.');
		}
		echo json_encode($jsonResponse);
	}
	public	 function postComment(){	
		$jsonResponse = array();
		$postID = $this->input->post('post_id');
		if(user_logged_in()){		
			$insertedData 						  = array();		
			$insertedData['user_id'] 			  = user_id();		
			$insertedData['recipe_blog_image_id'] = !empty($postID)?$postID:'';		
			$insertedData['comments'] 			  = $this->input->post('comments')?$this->input->post('comments'):'';		
			$insertedData['created_date'] 		  = date('Y-m-d H:i:s');	
			$this->common_model->insert('comments', $insertedData);
			$commentText ='';
			if($this->input->post('post_id')){
				$commentsChat['comments'] = $this->developer_model->get_comments($this->input->post('post_id'));
				//print_r($commentsChat['comments']); exit();
				$commentText = $this->load->view('frontend/postComments', $commentsChat, TRUE);
			}			
			$jsonResponse = array(	
								'status' => 'true', 								
								'message' => 'comments is posted',
								'commentCounter'=> get_all_count('comments', array('recipe_blog_image_id'=>$postID)), 
								'comments'=>$commentText
								);
		}else{
			$jsonResponse = array('status' => 'false', 'message' => 'Please <a href="'.base_url().'?acntype=login">login</a> or <a href="'.base_url().'">register</a> to comment.');
		}
		echo json_encode($jsonResponse);
	}
	public	 function followedUser(){	
		$jsonResponse = array();
		$user_id 		 = $this->input->post('user_id');
		$followingUserID = $this->input->post('followingUserID');
		if(user_logged_in()){
			$insertedData 						  = array();		
			$insertedData['sender_id'] 			  = user_id();		
			$insertedData['receiver_id'] 		  = !empty($user_id)?$user_id:'';			
			$reqStatus = $this->common_model->get_row('follow_request', $insertedData);
			//echo $this->db->last_query(); exit();
			if(!empty($reqStatus)){
				$this->common_model->delete('follow_request', $insertedData);
				if(!empty($followingUserID)){                
                  	$FollowingCount = get_all_count('follow_request', array('sender_id' => $followingUserID, 'accepted_status'=>1));
                  	$FollowersCount = get_all_count('follow_request', array('receiver_id' => $followingUserID, 'accepted_status'=>1));
                }else if($this->input->post('user_id')){
                  	$FollowingCount = get_all_count('follow_request', array('sender_id'=>$this->input->post('user_id'), 'accepted_status'=>1));
                  	$FollowersCount = get_all_count('follow_request', array('receiver_id'=>$this->input->post('user_id'), 'accepted_status'=>1));
                }else {
                  	$FollowingCount = get_all_count('follow_request', array('sender_id'=>user_id(), 'accepted_status'=>1));
                  	$FollowersCount = get_all_count('follow_request', array('receiver_id'=>user_id(), 'accepted_status'=>1));
                }
                if($FollowingCount>1000){
                  	$FollowingCount = number_format(($FollowingCount/1000), 1).' k';
                }
                if($FollowersCount>1000){
                  	$FollowersCount = number_format(($FollowersCount/1000), 1).' k';
                }
				$jsonResponse = array('status' 		   => 'true', 
									  'message' 	   => 'Follow',
									  'fstatus' 	   => (user_id()==$followingUserID)?1:0,
									  'FollowingCount' => $FollowingCount,
									  'FollowersCount' => $FollowersCount
									);
			}else{
				if($this->common_model->get_row('users', array('id'=>$user_id, 'privacy'=>2))){
					$insertedData['accepted_status']  = '4';
					$ststa = 'Request sent';
				}else{
					$insertedData['accepted_status']  = '1';
					$ststa = 'Unfollow';
				}
				$this->common_model->insert('follow_request', $insertedData);
				if(!empty($followingUserID)){                
                  $FollowingCount = get_all_count('follow_request', array('sender_id' => $followingUserID, 'accepted_status'=>1));
                  $FollowersCount = get_all_count('follow_request', array('receiver_id' => $followingUserID, 'accepted_status'=>1));
                }else if($this->input->post('user_id')){
                  	$FollowingCount = get_all_count('follow_request', array('sender_id'=>$this->input->post('user_id'), 'accepted_status'=>1));
                  	$FollowersCount = get_all_count('follow_request', array('receiver_id'=>$this->input->post('user_id'), 'accepted_status'=>1));
                }else{
                  $FollowingCount = get_all_count('follow_request', array('sender_id'=>user_id(), 'accepted_status'=>1));
                  $FollowersCount = get_all_count('follow_request', array('receiver_id'=>user_id(), 'accepted_status'=>1));
                }
                if($FollowingCount>1000){
                  $FollowingCount = number_format(($FollowingCount/1000), 1).' k';
                }
                if($FollowersCount>1000){
                  $FollowersCount = number_format(($FollowersCount/1000), 1).' k';
                }
				$jsonResponse = array('status' 		   => 'true', 
									  'message' 	   => $ststa,
									  'fstatus' 	   => 0,
									  'FollowingCount' => $FollowingCount,
									  'FollowersCount' => $FollowersCount
									);
			}
		}else{
			$jsonResponse = array('status' => 'false', 'message' => 'Please <a href="'.base_url().'?acntype=login">login</a> or <a href="'.base_url().'">register</a> to comment.');
		}
		echo json_encode($jsonResponse);
	}	
	/************** socail login **************/
	public function fb_login(){   
        if($this->input->post('name', TRUE)){
            $fbfullname = $this->input->post('name', TRUE);
        }
        if($this->input->post('email', TRUE)){
            $femail = $this->input->post('email', TRUE);
        }
        if($this->input->post('fb_id', TRUE)){
            $fbid = $this->input->post('fb_id', TRUE);
        }
        if($this->input->post('url', TRUE)){
            $picture = $this->input->post('url', TRUE);
        }
        $result = $this->common_model->fb_login($fbfullname,$femail,$fbid,$picture);
        echo $result;
    }   
    public function gmail_login(){  
        $fullname = $emai = $page_url = $picture = '';    
        if($this->input->post('name', TRUE)){
            $fullname = $this->input->post('name', TRUE);
        }
        if($this->input->post('email', TRUE)){
            $email = $this->input->post('email', TRUE);
        }
        if($this->input->post('page_url', TRUE)){
            $page_url = $this->input->post('page_url', TRUE);
        }
        if($this->input->post('url')){
            $picture = $this->input->post('url', TRUE);
        }
        $result = $this->common_model->gmail_login($fullname,$email,'1',$page_url,  $picture,'','');
        echo $result;
    }
    /************** socail login **************/
	public function test_mail_send_smtp(){        
        $this->load->library('email');
        $this->email->initialize(array(
          'protocol' => 'smtp',
          'smtp_host' => 'smtp.sendgrid.net',
          'smtp_user' => 'arjunamrode',
          'smtp_pass' => 'Arjun$#678',
          'smtp_port' => 587,
          'crlf' => "\r\n",
          'newline' => "\r\n"
        ));
        $this->email->from('amrode.rudiment@gmail.com', 'Your Name');
        $this->email->to('amrode.rudiment@gmail.com');
        $this->email->subject('Email Test');
        $this->email->message('Testing the email class.');
        $this->email->send();
        echo $this->email->print_debugger();
       /* $this->load->library('sendgrid_mail');
        $this->load->spark('sendgrid-mail/0.1.2');
        $this->sendgrid_mail->initialize(array('api_user'   => 'arjunamrode',
                                       'api_key'            => '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',
                                       'api_format'     => 'json'));

        // Send email
        $result = $this->sendgrid_mail->send('amrode.rudiment@gmail.com', 'Welcome to Oz!', 'You may see the wizard now.', NULL, 'amrode.rudiment@gmail.com');*/
    }    
    public function test_mail_send(){        
        $this->load->library('email');
        $this->email->from('info@risensys.com', 'Roky');
        $this->email->to('amrode.rudiment@gmail.com');
        $this->email->subject('Email Test');
        $this->email->message('Testing the email class.');
        if($this->email->send()){
        	echo 'success';
        }else{
        	echo 'not success';
        }
        echo $this->email->print_debugger();
    } 
}