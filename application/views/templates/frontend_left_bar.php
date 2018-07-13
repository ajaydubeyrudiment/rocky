<style type="text/css">
  body .wrapper .feed .feed_left .username .follow .edittextLink{background-color: transparent; font-weight: 500;  text-align: center; margin: 9px 0 0px; width: 100%;  float: left;  padding: 5px 41%;}
  #postedFollowedLeftR{ display: none; }
</style>
<section class="feed">
   <div class="container">
      <div class="row">          
          <?php 
            $uriseg3        = $this->uri->segment(3);
            if(!empty($uriseg3)){              
              $userIds        = explode('-', $uriseg3);
              $userID         = end($userIds);           
              if(!is_numeric($userID)){ redirect('user/dashboard');}
            }
            if(!empty($userID)){
              $user = user_info($userID);
            }else if($this->input->get('user_id')){
              $userID = $this->input->get('user_id');
              $user = user_info($this->input->get('user_id'));
            }else{
              $user = user_info();
            }
            if(empty($user)){redirect('user/dashboard');}   
            //print_r($user); exit();      
          ?>
         <div class="col-md-3 ">            
            <div class="row feed_left">
            <div class="username">
              <?php 
                if(!empty($user->profile_pic)&&file_exists('assets/uploads/users/thumbnails/'.$user->profile_pic)){
                   $profile_pic = base_url().'assets/uploads/users/thumbnails/'.$user->profile_pic;
                }else{
                  $pics = site_info('default_user_pic');
                  if(!empty($pics)&&file_exists($pics)){
                    $profile_pic = base_url().$pics;
                  } 
                }
              ?>
                <img src="<?php echo $profile_pic; ?>" class="profile_icons" id="left_bar_profile_pic" <?php if(empty($userID) || $userID==user_id()){?>onclick="click_imgs('profile_pic');" <?php }?>>               
                <div class="username_content">
                  <h4 id="left_bar_user_name"><?php if(!empty($user->user_name)) echo strtolower($user->user_name); ?></h4>
                  <h6 id="left_bar_about"><?php if(!empty($user->first_name)) echo strtolower($user->first_name);if(!empty($user->last_name)) echo ' '.strtolower($user->last_name); ?></h6>
                  <p id="left_bar_about">
                    <?php if(!empty($user->about)){ if(strlen($user->about)>100){ $about = ucfirst($user->about) ; echo substr($about,0,100).'...';}else{  echo ucfirst($user->about); } } ?>                 
                  </p>
                  <?php 
                  if(empty($userID) || $userID==user_id()){?>
                    <a  href="javascript:void(0);" title="Edit Profile" data-toggle="modal" data-target="#edit_profile_secound_model" class="username_content_img">
                      <img src="<?php echo base_url();?>assets/front/img/edite_icon.png"/>
                    </a>
                  <?php }?>
               </div>
               <div class="follow text-center">                  
                  <?php 
                  if(!empty($userID)){
                    if(get_all_count('follow_request', array('sender_id'=>user_id(), 'receiver_id'=>$userID, 'accepted_status'=>4))>0){
                      //echo $this->db->last_query();
                      ?>
                      <button class="btn btn-md editt" id="postedFollowedLeft">
                        <?php if(get_all_count('follow_request', array('sender_id'=>user_id(), 'receiver_id'=>$userID, 'accepted_status'=>1))>0){echo 'Unfollow';}elseif(get_all_count('follow_request', array('sender_id'=>user_id(), 'receiver_id'=>$userID, 'accepted_status'=>4))>0){echo 'Request Sent';}else{echo 'Follow';} ?>
                      </button>
                    <?php 
                    }else{?>
                      <button class="btn btn-md editt" onclick="followRequest('<?php echo !empty($userID)?$userID:0; ?>');" id="postedFollowedLeft">
                        <?php if(get_all_count('follow_request', array('sender_id'=>user_id(), 'receiver_id'=>$userID, 'accepted_status'=>1))>0){echo 'Unfollow';}elseif(get_all_count('follow_request', array('sender_id'=>user_id(), 'receiver_id'=>$userID, 'accepted_status'=>4))>0){echo 'Request Sent';}else{echo 'Follow';} ?>
                      </button>
                      <button class="btn btn-md editt" id="postedFollowedLeftR">
                        <?php echo 'Request Sent'; ?>
                      </button>
                    <?php }
                    ?>                    
                  <?php 
                  }else if($this->input->get('acntype')&&$this->input->get('acntype')=='edit_profile'){ 
                    echo '';?>              
                  <?php 
                  }else{echo ''; ?>
                  <?php }?>
               </div>
               <?php 
                if(!empty($userID)){                
                  $FollowingCount = get_all_count('follow_request', array('sender_id'=>$userID, 'accepted_status'=>1));
                  $FollowersCount = get_all_count('follow_request', array('receiver_id'=>$userID, 'accepted_status'=>1));
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
               ?>
              <div class="profile_status">
                <ul class="list-inline">
                  <li>
                    <a href="javascript:void(0);"  data-toggle="modal" data-target="#followingpop" onclick="getFollowingUser('<?php echo !empty($userID)?$userID:user_id(); ?>');">
                      <strong id="leftFollowingCount">
                        <?php echo !empty($FollowingCount)?$FollowingCount:0; ?>
                      </strong>
                      <span>Following</span>
                    </a>
                  </li>
                  <li>
                    <a href="javascript:void(0);" data-toggle="modal" data-target="#followerpop" onclick="getFollowerUser('<?php echo !empty($userID)?$userID:user_id(); ?>');">
                      <strong id="leftFollowersCount">
                        <?php echo !empty($FollowersCount)?$FollowersCount:0; ?>
                      </strong>
                      <span>Followers</span>
                    </a>
                  </li>
                  <?php 
                  if(!empty($userID)&&$userID!=user_id()){?>
  				          <li class="email_icon">
                      <a href="javascript:void(0);" style="">
                       <i class="fa fa-envelope"></i>
                      </a>
                    </li>
                  <?php }?>
                </ul>   
              </div>
              <input type="file" id="profile_pic" class="hidden">
              <input type="file" id="profile_pic1" class="hidden">
              <input type="hidden" id="file_type_name" name="file_type" value="profile_pic">
              <div id="profile_pic_error" class="text-danger"></div>
            </div>
            <hr />
            <div class="metrics">
               <h4>Metrics 
                <span class="metspan">
                  <?php 
                  if(empty($userID)||(!empty($userID)&&$userID==user_id())){?>
                    <a href="<?php echo base_url('user/goal_set'); ?>" title="Set Goal">
                      <img src="<?php echo base_url(); ?>assets/front/img/icon_1.png" />
                    </a>
                  <?php 
                  }
                  if(!empty($userID)){?>
                    <a href="<?php echo base_url('user/progress?user_id='.$userID); ?>" title="Progress Chart">
                      <img src="<?php echo base_url(); ?>assets/front/img/icon_2.png" />
                    </a>
                  <?php 
                  }else{?>
                    <a href="<?php echo base_url('user/progress'); ?>" title="Progress Chart">
                      <img src="<?php echo base_url(); ?>assets/front/img/icon_2.png" />
                    </a>
                  <?php }?>                  
                </span>			   
			         </h4>
               <p>Joined : <?php if(!empty($user->created_date)) echo date('m/d/Y',strtotime($user->created_date)); ?>
                <span class="pull-right">
                  Age : 
                    <?php 
                    if(!empty($user->date_of_birth)){ 
                      $dateOfBirthTime = time() - strtotime($user->date_of_birth); 
                      $oneYear         = 60*60*24*365;
                      echo floor($dateOfBirthTime/$oneYear).' Year'; 
                    } 
                    if(!empty($user->metrics_img1)&&file_exists('assets/uploads/users/thumbnails/'.$user->metrics_img1)){
                        $uploadFile = base_url().'assets/uploads/users/thumbnails/'.$user->metrics_img1;
                    }else{
                      if($user->gender=='female'){
                        $uploadFile = FRONT_THEAM_PATH.'img/femaleIcon.png';
                      }else{
                        $uploadFile = FRONT_THEAM_PATH.'img/maleIcon.png';
                      }
                    }
                    ?>
                  </span>
                </p>
               <div class="col-md-6 m1">
                  <div class="join">
                     <img src="<?php echo  $uploadFile ;?>" alt="join" class="profile_icons" onclick="click_imgs('metrics_img1');" id="metrics_img1">
                     <h4>Join Height :  
                      <?php 
                      if(!empty($user->useMetricsSystem) && $user->useMetricsSystem==1&&!empty($user->height)){
                        echo !empty($user->height)?number_format($user->height, 0).' cm':'';
                      }else{ 
                        $hieghtStrCmC = floatval($user->height); 
                        $hieghtStrCmD = $hieghtStrCmC / 2.54;
                        $inches      = floatval($hieghtStrCmD);
                        $inchesN     = intval($inches);
                        $feet        = intval($inches/12);
                        $feetI       = intval($feet*12);                       
                        $feetN       = ($inchesN==$feetI)?'':floatval($inches)-floatval($feetI);
                        $feetN       = !empty($feetN)?number_format($feetN, 0):'';
                        $feetInch    = $feet."'".$feetN;
                        echo  $feetInch;
                      }?>     
                     </h4>    
                     <h4>Join Weight :                       
                      <?php if(!empty($user->useMetricsSystem) && $user->useMetricsSystem==1 && !empty($user->weight)){ echo (strpos($user->weight,'kg')>0)?$user->weight:number_format($user->weight, 0).' kg'; }else if(!empty($user->weight)){echo (strpos($user->weight,'lbs')>0)?$user->weight:number_format($user->weight, 0).' lbs';}?>
                    </h4>
                     <div id="metrics_img1_error" class="text-danger"></div>
                  </div>
               </div>
               <div class="col-md-6 m2">
                  <div class="join">
                    <?php 
                    if(!empty($userID)){
                      $mrightImg = $this->common_model->get_result('metricTracker', array('user_id'=>$userID,'matrixDate <='=>strtotime(date('Y-m-d'))), array('bodyShot', 'weight', 'height','matrix_status'), array('matrixDate', 'desc'));
                    }else{
                      $mrightImg = $this->common_model->get_result('metricTracker', array('user_id'=>user_id(),'matrixDate <='=>strtotime(date('Y-m-d'))), array('bodyShot', 'weight', 'height','matrix_status'), array('matrixDate', 'desc'));
                    }
                    if(!empty($user->gender)&&$user->gender=='female'){
                      $mright = FRONT_THEAM_PATH.'img/femaleIcon.png';
                    }else{
                      $mright = FRONT_THEAM_PATH.'img/maleIcon.png';
                    }
                    $mrightH = "";
                    $mrightW = "";
                    if(!empty($mrightImg[0])){
                      if(!empty($mrightImg[0]->bodyShot)&&file_exists('assets/uploads/matrix/thumbnails/'.$mrightImg[0]->bodyShot)){
                        $mright = base_url('assets/uploads/matrix/thumbnails/'.$mrightImg[0]->bodyShot);
                      }
                      if(!empty($mrightImg[0]->weight)&&!empty($mrightImg[0]->height)){
                        if($user->useMetricsSystem==1){
                          $mrightH = !empty($mrightImg[0]->height)?number_format($mrightImg[0]->height, 0).' cm':'';
                        }else{
                          $hieghtStrCmC = floatval($mrightImg[0]->height); 
                          $hieghtStrCmD = $hieghtStrCmC / 2.54;
                          $inches      = floatval($hieghtStrCmD);
                          $inchesN     = intval($inches);
                          $feet        = intval($inches/12);
                          $feetI       = intval($feet*12);                       
                          $feetN       = ($inchesN==$feetI)?'':floatval($inches)-floatval($feetI);
                          $feetInch    = $feet."'".number_format($feetN, 0);
                          $mrightH     = $feetInch;
                        }
                      }
                      if($mrightImg[0]->matrix_status==$user->useMetricsSystem&&$user->useMetricsSystem==1){
                        $mrightW = !empty($mrightImg[0]->weight)?$mrightImg[0]->weight.' kg':'';
                      }else if($mrightImg[0]->matrix_status==$user->useMetricsSystem&&$user->useMetricsSystem==2){
                        $mrightW = !empty($mrightImg[0]->weight)?$mrightImg[0]->weight.' lbs':'';
                      }elseif($user->useMetricsSystem==1){
                        $mrightW = number_format($mrightImg[0]->weight / 2.2046, 0).' kg';
                      }elseif($user->useMetricsSystem==2){
                        $mrightW = number_format($mrightImg[0]->weight * 2.2046, 0).' lbs';
                      } 
                    }else if(!empty($mrightImg[1])){
                      if(!empty($mrightImg[1]->bodyShot)&&file_exists('assets/uploads/matrix/thumbnails/'.$mrightImg[1]->bodyShot)){
                        $mright = base_url('assets/uploads/matrix/thumbnails/'.$mrightImg[1]->bodyShot);
                      }
                      if(!empty($mrightImg[1]->weight)&&!empty($mrightImg[1]->height)){
                        if($user->useMetricsSystem==1){
                          $mrightH      = !empty($mrightImg[1]->height)?number_format($mrightImg[1]->height, 0).' cm':'';
                        }else{
                          $hieghtStrCmC = floatval($mrightImg[1]->height); 
                          $hieghtStrCmD = $hieghtStrCmC / 2.54;
                          $inches       = floatval($hieghtStrCmD);
                          $inchesN      = intval($inches);
                          $feet         = intval($inches/12);
                          $feetI        = intval($feet*12);                       
                          $feetN        = ($inchesN==$feetI)?'':floatval($inches)-floatval($feetI);
                          $feetInch     = $feet."'".number_format($feetN, 0);
                          $mrightH      = $feetInch;
                        }
                      }
                      if($mrightImg[1]->matrix_status==$user->useMetricsSystem&&$user->useMetricsSystem==1){
                        $mrightW = !empty($mrightImg[1]->weight)?$mrightImg[1]->weight.' kg':'';
                      }else if($mrightImg[1]->matrix_status==$user->useMetricsSystem&&$user->useMetricsSystem==2){
                        $mrightW = !empty($mrightImg[1]->weight)?$mrightImg[1]->weight.' lbs':'';
                      }elseif($user->useMetricsSystem==1){
                        $mrightW = number_format($mrightImg[1]->weight / 2.2046, 0).' kg';
                      }elseif($user->useMetricsSystem==2){
                        $mrightW = number_format($mrightImg[1]->weight * 2.2046, 0).' lbs';
                      } 
                    }                    
                    ?>
                    <img src="<?php echo  $mright ;?>" id="right_m_image"  alt="join">
                    <h4>Height : <span id="right_m_height"><?php echo $mrightH; ?></span></h4>
                    <h4>Weight : <span id="right_m_weight"><?php echo $mrightW; ?></span></h4>
                  </div>
               </div>
               <div class="metrics_tag">
                <?php 
                $thirdUri = ($this->uri->segment(3))?"/".$this->uri->segment(3):"";
                if(!empty($userID)){?>
                  <a href="<?php echo base_url('user/diet_plan'.$thirdUri.'?user_id='.$userID); ?>">Diet Plan</a>
                  <a href="<?php echo base_url('user/all_diet_plan'.$thirdUri.'?user_id='.$userID); ?>">All Diet Plans</a>
                  <a href="<?php echo base_url('user/workout_plan'.$thirdUri.'?user_id='.$userID); ?>">Workout Plan</a>
                  <a href="<?php echo base_url('user/all_workout_plan'.$thirdUri.'?user_id='.$userID); ?>">All Workout Plans</a>
                <?php }else{?>
                  <a href="<?php echo base_url('user/diet_plan'); ?>">Diet Plan</a>
                  <a href="<?php echo base_url('user/all_diet_plan'); ?>">All Diet Plans</a>
                  <a href="<?php echo base_url('user/workout_plan'); ?>">Workout Plan</a>
                  <a href="<?php echo base_url('user/all_workout_plan'); ?>">All Workout Plans</a>
               <?php } ?>        
               </div>
               <div class="clearfix"></div>
            </div>
          </div>
           <div class="row"> 
            <footer class="footer">
              <div class="">
                <div class="">
                  <ul class="list-inline pull-right">
                    <li><a href="javascript:void(0);">About</a></li>
                    <li><a href="javascript:void(0);">Help</a></li>
                    <li><a href="javascript:void(0);">Terms of Services</a></li>
                    <li><a href="javascript:void(0);">Privacy</a></li>
                    <li><a href="javascript:void(0);">Cookies</a></li>
                  </ul>
                </div>
                <div class="">
                  <p> @ copyright Loreme Impsum</p>
                </div>
              </div>
              <div class="clearfix"></div>
            </footer>
          </div>
         </div>
        <?php 
        if($this->input->get('acntype')&&$this->input->get('acntype')=='edit_profile'){?>
          <!--==modal start==-->
          <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="edit_profile_model">
           <div class="modal-dialog modal-md" role="document">
             <div class="modal-content">
                 <div class="modal-header mh_p">
                  <br/> <br/>
               </div>
                  <div class="modal-body mb_p">
                     <div class="progress_form">
                        <a href="javascript:void(0);"><img src="<?php echo  FRONT_THEAM_PATH ;?>img/logo.jpg"></a>
                        <h4 id="user_name_title">
                          Welcome to Roky <span style="color:#5ACFC6"><?php if(!empty($user->user_name)) echo $user->user_name; ?></span>
                        </h4>
                        <p id="user_name_about">
                            To begin, we need the following information so that we can better track your progress
                        </p>
                        <br />
                        <div id="editProfile_res"></div>
                        <form class="form-horizontal clearfix" id="editProfile" onsubmit="return false;">
                           <div class="form-group">
                              <label class="col-sm-4 control-label"><sup>*</sup>Birthdate</label>
                               <div class="col-sm-8" id="sandbox-container">
                                 <input type="text" readonly="readonly" placeholder="Birthdate"  class="form-control" name="dateofbirth" value="<?php if(!empty($user->date_of_birth)){ echo $user->date_of_birth;}else{date('01/01/'.(date('Y')-18));} ?>" id="dateofbirth">
                                 <div id="dateofbirth_error" class="text-danger"></div>
                              </div>
                              <i class="fa  fa-info-circle pull-right dateinfotext" data-toggle="tooltip" title="The user they must be atleast 18 years of age to register"  aria-hidden="true"></i>
                           </div>
                           <div class="form-group">
                              <label class="col-sm-4 control-label"><sup>*</sup>Sex</label>
                              <div class="col-sm-8 genderMain select_overlap">
                                 <select class="form-control gender" id="mounth" name="gender">
                                    <option value="male" <?php if(!empty($user->gender)&&$user->gender=='male'){ echo 'selected'; $gender_text = 'Male';}?>>Male</option>
                                    <option value="female" <?php if(!empty($user->gender)&&$user->gender=='female'){ echo 'selected'; $gender_text = 'Female';} ?>>Female</option>
                                    <option value="other" <?php if(!empty($user->gender)&&$user->gender=='other'){ echo 'selected'; $gender_text = 'Other';} ?>>Other</option>
                                  </select>
                                  <div id="gender_error" class="text-danger"></div>
                                  <input type="hidden" id="gender_text" value="<?php if(!empty($gender_text)) echo $gender_text; ?>">
                              </div>
                           </div>
                         <div class="form-group clearfix">
                             <div class="col-sm-4">
                                 <label>Metric System</label>
                             </div>
                             <div class="col-sm-6 checkbox checkbox-success">
                                 <input id="checkbox3" type="checkbox" <?php if(!empty($user->useMetricsSystem)&&$user->useMetricsSystem=='1') echo 'checked'; ?> name="useMetricsSystem" class="useMetricsSystem" value="1" onclick="checkMetrick();">
                                 <label for="checkbox3"></label>
                             </div>
                          </div>
                          <div id="withoutmetric">
                            <div class="form-group">
                              <label class="col-sm-4 control-label"><sup>*</sup>Joined Height</label>
                              <div class="col-sm-8 height_cm">
                                 <select class="form-control height_cms" id="mounth" name="height">
                                  <?php 
                                    $height_titleI ="";
                                    $hieghtStrCm = 91.44;
                                    for($hi=1;$hi<85;$hi++){
                                      $hieghtStrCm = $hieghtStrCm + 2.54; 
                                      $hieghtStrCm = number_format($hieghtStrCm, 2); 
                                      $inches      = intval($hieghtStrCm /2.54); 
                                      $feetInch    = "";
                                      $feetInch   .= floor($inches/12)."'";
                                      $feetInch   .= (floor($inches%12)>0)?floor($inches%12):'';
                                    if($hieghtStrCm=='172.72'){ 
                                        $height_titleI = $feetInch;
                                        echo '<option selected value="'.$hieghtStrCm.'">'.$feetInch.'</option>';
                                      }else{
                                        echo '<option value="'.$hieghtStrCm.'">'.$feetInch.'</option>'; 
                                      }                           
                                    }                                 
                                  ?>  
                                 </select>
                                 <input type="hidden" id="height_cm_title_text" value="<?php if(!empty($height_titleI)) echo $height_titleI; ?>">
                                 <div id="height_cms_error" class="text-danger"></div>
                              </div>
                            </div>                                                       
                          </div>                       
                          <div  id="withmetric" style="display:none;">                         
                           <div class="form-group">
                              <label class="col-sm-4 control-label"><sup>*</sup>Joined Height</label>
                              <div class="col-sm-8 height_main">
                                <select class="form-control height" id="mounth" name="height">
                                <?php 
      	                       		for($hi = 91.44;$hi<305;$hi++){
                                    if(number_format($hi,0)=='172'){ 
                                          $height_title = number_format($hi, 0).' cm';
                                          echo '<option selected value="'.$hi.'">'.number_format($hi, 0).' cm </option>'; 
                                    }else{
                                          echo '<option value="'.$hi.'">'.number_format($hi, 0).' cm </option>'; 
                                    }
                                  }                     					
	                               ?>   
                                 </select>
                                 <input type="hidden" id="height_title_text" value="<?php if(!empty($height_title)) echo $height_title; ?>">
                                 <div id="height_error" class="text-danger"></div>
                              </div>
                            </div>
                          </div>  
                          <div class="form-group"> 
                            <label class="col-sm-4 control-label"><sup>*</sup>Joined Weight</label>
                            <div class="col-sm-8">
                               <input type="text" value="<?php if(!empty($user->weight)){echo round($user->weight);} ?>" maxlength="3" id="weight_id_kg_lbs" placeholder="Enter your Weight in lbs" class="form-control weight" name="weight">
                               <div id="weight_error" class="text-danger"></div>
                            </div>
                          </div>                         
                          <div class="form-group">
                              <label class="col-sm-4 control-label">Profile Visibility</label>
                              <div class="col-sm-8 visibility">
                                 <select class="form-control" id="mounth" name="privacy">
                                    <option value="1" <?php if(!empty($user->privacy)&&$user->privacy=='1'){ echo 'checked'; $visibility_text = 'Public';} ?>>Public</option>
                                    <option value="2" <?php if(!empty($user->privacy)&&$user->privacy=='2') {echo 'checked'; $visibility_text = 'Private'; }?>>Private</option>
                                 </select>
                                 <input type="hidden" id="visibility_text" value="<?php if(!empty($visibility_text)){ echo $visibility_text;}?>">
                              </div>
                           </div>
                           <div class="form-group clearfix text-center sub_b">
                               <input type="hidden" name="profile_type" value="first_time">
                              <input type="submit" class="btn btn-md text-center" onclick="saveProfile('other_time');" value="save">
                           </div>
                        </form>
                     </div>
                  </div>
             </div>
           </div>
          </div>
          <!--==modal end==-->
        <?php } else{?>
          <!--==modal start==-->
          <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="edit_profile_secound_model">
           <div class="modal-dialog modal-md" role="document">
             <div class="modal-content">
                 <div class="modal-header mh_p">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> 
               </div>
                  <div class="modal-body mb_p">
                     <div class="progress_form">
                        <a href="javascript:void(0);"><img src="<?php echo  FRONT_THEAM_PATH ;?>img/logo.jpg"></a>                       
                        <h4 id="user_name_title">
                          Welcome to Roky <span style="color:#5ACFC6"><?php if(!empty($user->user_name)) echo $user->user_name; ?> </span>
                        </h4>
                        <p id="user_name_about1">
                          To begin, we need the following information so that we can better track your progress
                        </p>
                        <br />
                        <div id="editProfile_res"></div>
                        <form class="form-horizontal clearfix" id="editProfile" onsubmit="return false;">
                          <div class="form-group">
                              <label class="col-sm-4 control-label"><sup>*</sup>Full Name</label>
                               <div class="col-sm-8" id="sandbox-container">
                                 <input type="text" placeholder="Enter Name"  class="form-control" name="first_name" value="<?php if(!empty($user->first_name)) echo $user->first_name; ?>" id="first_name">
                                 <div id="first_name_error" class="text-danger"></div>
                              </div>
                           </div>
                            <div class="form-group">
                              <label class="col-sm-4 control-label"><sup>*</sup>About</label>
                               <div class="col-sm-8" id="sandbox-container">
                                 <textarea maxlength="100" placeholder="Enter About "  class="form-control" name="about"  id="user_about"><?php if(!empty($user->about)) echo $user->about; ?></textarea> 
                                 <div id="user_about_error" class="text-danger"></div>
                              </div>
                           </div>
                          <div class="form-group">
                              <label class="col-sm-4 control-label"><sup>*</sup>Birthdate</label>
                                <div class="col-sm-8" id="sandbox-container">
                                  <input type="text" readonly="readonly" placeholder="Birthdate"  class="form-control" name="dateofbirth" value="<?php if(!empty($user->date_of_birth)) echo $user->date_of_birth; ?>" id="dateofbirth">
                                  <i class="fa  fa-info-circle pull-right dateinfotext" data-toggle="tooltip" title="The user they must be atleast 18 years of age to register"  aria-hidden="true"></i>
                                 <div id="dateofbirth_error" class="text-danger"></div>
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="col-sm-4 control-label"><sup>*</sup>Sex</label>
                              <div class="col-sm-8 genderMain select_overlap">
                                 <select class="form-control gender" id="mounth" name="gender">
                                    <option value="male" <?php if(!empty($user->gender)&&$user->gender=='male'){ echo 'selected'; $gender_text = 'Male';}?>>Male</option>
                                    <option value="female" <?php if(!empty($user->gender)&&$user->gender=='female'){ echo 'selected'; $gender_text = 'Female';} ?>>Female</option>
                                    <option value="other" <?php if(!empty($user->gender)&&$user->gender=='other'){ echo 'selected'; $gender_text = 'Other';} ?>>Other</option>
                                  </select>
                                  <div id="gender_error" class="text-danger"></div>
                                  <input type="hidden" id="gender_text" value="<?php if(!empty($gender_text)) echo $gender_text; ?>">
                              </div>
                           </div>
                           <input  type="hidden" <?php if(!empty($user->useMetricsSystem)&&$user->useMetricsSystem=='1') echo 'checked'; ?>  class="useMetricsSystem" value="1">
                          <div id="withmetric" <?php if(empty($user->useMetricsSystem)||$user->useMetricsSystem==2) echo 'style="display:none;"'; ?>>                          	
                            <div class="form-group">
                              <label class="col-sm-4 control-label"><sup>*</sup>Joined Height</label>
                              <div class="col-sm-8 height_cm">
                                 <select class="form-control height_cms" id="mounth" name="height">
                                  <?php 
                                    for($hi = 91.44;$hi<305;$hi++){
                                      if(!empty($user->height)&&number_format($user->height,0)==number_format($hi,0)){ 
                                        $height_title = number_format($hi, 0).' cm';
                                        echo '<option selected value="'.$hi.'">'.number_format($hi, 0).' cm </option>'; 
                                      }else{
                                        echo '<option value="'.$hi.'">'.number_format($hi, 0).' cm </option>'; 
                                      }
                                     }?>  
                                 </select>
                                 <input type="hidden" id="height_cm_title_text" value="<?php if(!empty($height_title)) echo $height_title; ?>">
                                 <div id="height_cms_error" class="text-danger"></div>
                              </div>
                            </div>                    
                          </div>                          
                          <div id="withoutmetric" <?php if(!empty($user->useMetricsSystem)&&$user->useMetricsSystem==1) echo 'style="display:none;"'; ?>>
                            <div class="form-group">
                              <label class="col-sm-4 control-label"><sup>*</sup>Joined Height</label>
                              	<div class="col-sm-8 height_main">                              		
	                               <select class="form-control height" id="mounth" name="height">
		                               	<?php 
  		                                $height_titleI ="";
      			                       		$hieghtStrCm = 91.44;
                                      $inches = 0;
                                      $hieghtStrCmC = floatval($user->height); 
                                      $hieghtStrCmD = $hieghtStrCmC / 2.54;
                                      $inches      = floatval($hieghtStrCmD);
                                      $inchesN     = intval($inches);
                                      $feet        = intval($inches/12);
                                      $feetI       = intval($feet*12);                       
                                      $feetN       = ($inchesN==$feetI)?'':floatval($inches)-floatval($feetI);
                                      $height_titleIn    = !empty($feetN)?number_format($feetN, 0):'';
                                      $height_titleIn    = $feet."'".$feetN;
      			                       		for($hi=1;$hi<85;$hi++){
                                        $hieghtStrCm = $hieghtStrCm + 2.54; 
                                        $hieghtStrCmC = floatval($hieghtStrCm); 
                                        $hieghtStrCmD = $hieghtStrCm / 2.54;
                                        $inches      = floatval($hieghtStrCmD);
                                        $inchesN     = intval($inches);
                                        $feet        = intval($inches/12);
                                        $feetI       = intval($feet*12);                       
                                        $feetN       = ($inchesN==$feetI)?'':floatval($inches)-floatval($feetI);
                                        $feetN       = !empty($feetN)?number_format($feetN, 0):'';
                                        $feetInch    = $feet."'".$feetN;
      			                       			/*$hieghtStrCm = $hieghtStrCm + 2.54; 
      			                       			$inches      = intval(number_format($hieghtStrCm / 2.54, 2));
                                        $feetInch    = "";
                                        $feetInch   .= floor($inches/12)."'";   
      					                        $feetInch   .= (floor($inches%12)>0)?($inches%12):'';*/
      			                   				if(!empty($user->height) && floor($user->height) == floor($hieghtStrCmC)){ 
		                                    $height_titleI = $feetInch;
		                                    echo '<option selected value="'.$hieghtStrCm.'">'.$feetInch.' </option>';      
		                                  }else{
		                                    echo '<option value="'.$hieghtStrCm.'">'.$feetInch.' </option>'; 
		                                  }                      			
      			                       	}                       					
    			                          ?>   
	                                </select>
	                                <input type="hidden" id="height_title_text" value="<?php if(!empty($height_titleI)){ echo $height_titleI;}elseif(!empty($height_titleIn)){echo $height_titleIn;} ?>">
	                                <div id="height_error" class="text-danger"></div>
                              	</div>
                           </div>
                          </div>
                          <div class="form-group">
                            <label class="col-sm-4 control-label"><sup>*</sup>Joined Weight</label>
                            <div class="col-sm-8">
                               <input type="text" value="<?php if(!empty($user->weight)){echo round($user->weight);} ?>" placeholder="Enter your Weight in kg" class="form-control weight" maxlength="3" name="weight">
                               <div id="weight_error" class="text-danger"></div>
                            </div>
                           </div>   
                           <div class="form-group clearfix text-center sub_b">
                              <input type="hidden" id="visibility_text" value="<?php if(!empty($visibility_text)) echo $visibility_text; ?>">
                              <input type="hidden" name="privacy" value="<?php if(!empty($user->privacy)){ echo $user->privacy; }?>">
                              <input type="submit" class="btn btn-md text-center" onclick="saveProfile('first_time');" value="save">
                           </div>
                        </form>
                     </div>
                  </div>
             </div>
           </div>
          </div>
        <!--==modal end==-->
        <div id="followingpop" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">List of Following</h4>
              </div>
              <div class="modal-body" id="following_user_list"></div>
          </div>
          </div>
        </div>
        <div id="followerpop" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">List of Follower</h4>
              </div>
              <div class="modal-body" id="follower_user_list">
            </div>
          </div>
          </div>
        </div>
<?php }?>