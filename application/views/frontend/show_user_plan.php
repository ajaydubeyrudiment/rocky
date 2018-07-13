<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript">
  function heightTextChange(height_id='', height_text=''){
    console.log('height_id= '+height_id+', height_text = '+height_text);
    $('.max_height_'+height_id+' .select-styled').text(height_text);   
  }
</script>
<?php 
$user   = user_info($goal->user_id);
if(!empty($user->useMetricsSystem) && !empty($user->height) && $user->useMetricsSystem==1){
  $hightTitleA = !empty($user->height)?number_format($user->height, 0).' cm':'';
  $hightID     = number_format($user->height, 0);
}else if(!empty($user->height)){
  $inches      = intval(number_format($user->height/2.54, 2)); 
  $feetInch    = "";
  $feetInch   .= floor($inches/12)."'";
  $feetInch   .= floor($inches%12);                     
  $hightTitleA =  $feetInch;
  $hightID     = number_format($user->height, 0);
  //echo $user->height.' height aaa ';
}
$userLink = base_url().'user/profile/';
if(!empty($user->user_name)){
  $userLink .= str_replace(' ', '-', $user->user_name);
}
if(!empty($user->user_id)){
  $userLink .= '-'.$user->id.'?user_id='.$user->id;
}
if($user->id==user_id()){
  $userLink = base_url().'user/dashboard';
}
?>
<div class="col-md-9">
  <div class="feed_right">
     <div class="goal_setter">
        <h2 style="text-align: center;">
          <a href="<?php echo $userLink; ?>">
             <?php echo !empty($user->user_name)?ucfirst($user->user_name):'';?>
          </a>
        </h2>
        <hr />
        <div class="col-md-6">
           <div class="form-group clearfix">
              <label for="inputEmail3" class="col-sm-5 control-label">Current Height</label>
              <!-- <div class="col-sm-7">
                <input type="text" class="form-control" id="inputEmail3" placeholder="Height in <?php if(!empty($goal->goal_type) && $goal->goal_type==1){ echo ' cm'; }else if(!empty($user->goal_type) && $goal->goal_type==2){ echo ' feet and inch'; } ?>" value="<?php echo !empty($goal->height)?$goal->height:""; ?>">
              </div> -->
              <div class="col-sm-7 max_height_goal">
              <?php 
              if(!empty($user->useMetricsSystem) && $user->useMetricsSystem==1){ ?>
                <select class="form-control height" id="mounth" name="height">
                <?php 
                  $hightTitle = "";
                  for($hi = 91.44;$hi<305;$hi++){
                    if(!empty($goal->height)&&number_format($hi,0)==number_format($goal->height, 0)){ 
                      $hightTitle = number_format($hi, 0).' cm';
                      echo '<option selected value="'.$hi.'">'.number_format($hi, 0).' cm </option>'; 
                    }else{
                      echo '<option value="'.$hi.'">'.number_format($hi, 0).' cm </option>'; 
                    }
                  }                               
                ?>   
                </select> 
              <?php }else{?>                          
              <select class="form-control" id="mounth" name="height">
                <?php 
                  $hightTitle ="";
                  $hieghtStrCm = 91.44;
                  for($hi=1;$hi<85;$hi++){
                    $feetI        = $feet = $inches = "";
                    $hieghtStrCm  = $hieghtStrCm + 2.54; 
                    $hieghtStrCmC = floatval($hieghtStrCm); 
                    $hieghtStrCmD = $hieghtStrCmC / 2.54;
                    $inches      = floatval($hieghtStrCmD);
                    $inchesN     = intval($inches);
                    $feet        = intval($inches/12);
                    $feetI       = intval($feet*12);                                                                      
                    $feetN       = ($inchesN==$feetI)?'':floatval($inches)-floatval($feetI);
                    $feetInch    = $feet."'".$feetN;
                   // echo 'hieghtStrCmC = '.$hieghtStrCmC;
                    //echo floor($hieghtStrCmC).' hieghtStrCm , hightID'.floor($hightID).'<br/>';
                  if(!empty($goal->height)&&floor($hieghtStrCmC) == floor($hightID)){ 
                      $hightTitle = $feetInch;
                      echo '<option selected value="'.$hieghtStrCmC.'">'.$feetInch.'</option>';
                   }else{
                      echo '<option value="'.$hieghtStrCmC.'">'.$feetInch.'</option>'; 
                  }                           
              }                                
                ?>  
              </select>
              <?php }
              ?>
              <script type="text/javascript">
                setTimeout(function(){
                  heightTextChange("goal", "<?php if(!empty($hightTitle)){echo $hightTitle;}else if(!empty($hightTitleA)){echo $hightTitleA;}?>");
                }, 500);                            
              </script>
            </div>
            </div>
             <div class="form-group clearfix">
              <label for="inputEmail3" class="col-sm-5 control-label">Current Weight</label>
              <div class="col-sm-7">
                <input type="text" class="form-control" id="inputEmail3" placeholder="Weight in <?php if(!empty($user->useMetricsSystem) && $user->useMetricsSystem==1){ echo ' kg'; }else if(!empty($user->useMetricsSystem) && $user->useMetricsSystem==2){ echo ' lbs'; } ?>" name="wieght" value="<?php echo !empty($goal->wieght)?$goal->wieght:""; ?>">
              </div>           
          </div>
       </div>
        <div class="col-md-6">
          <div class="form-group clearfix">
              <label for="inputEmail3" class="col-sm-5 control-label">Lose How Much ?</label>
              <div class="col-sm-7">
                <input type="text" class="form-control" id="inputEmail3" placeholder="20lbs" value="<?php echo !empty($goal->loseDay)?$goal->loseDay:""; ?>">
              </div>
          </div>
           <div class="form-group clearfix">
              <label for="inputEmail3" class="col-sm-5 control-label">In How Many Days ?</label>
              <div class="col-sm-7">
                <input type="text" class="form-control" id="inputEmail3" placeholder="60" value="<?php echo !empty($goal->loseWeight)?$goal->loseWeight:""; ?>">
              </div>
            </div>
        </div>
        <div class="clearfix"></div>
      <hr />
      <div class="row">
        <?php 
        $uriSegs = "";
        if($this->input->get('user_id')){              
          $user = user_info($this->input->get('user_id'));
          $uriSegs = '/'.$user->user_name.'-'.$user->id;
        }
        if(!empty($dietPlans)){ 
          if($dietPlans->plan_expired=='0000-00-00 00:00:00'){
            $durationTimes = time() - strtotime($dietPlans->created_date); 
            $startDate = date('d M Y H:i A', strtotime($dietPlans->created_date));
            $endDate   = date('d M Y H:i A');
          }else{
            $durationTimes = strtotime($dietPlans->plan_expired) - strtotime($dietPlans->created_date); 
            $startDate = date('d M Y H:i A', strtotime($dietPlans->created_date));
            $endDate   = date('d M Y H:i A', strtotime($dietPlans->plan_expired));
          }
          ?>
          <a href="<?php echo base_url('user/diet_plan'.$uriSegs.'?plan_id='.$dietPlans->id.'&user_id='.$this->input->get('user_id').'&goal_id='.$this->input->get('goal_id'));?>">
            <div class="col-md-6">
              <div class="auto-diet-box">
                <ul class="list-inline">
                  <li> <b>Diet Plan </b> <span class="pull-right">:</span></li>
                  <li><?php echo !empty($dietPlans->plan_name)?$dietPlans->plan_name:""; ?></li>
                  <li><b>Description </b> <span class="pull-right">:</span></li>
                  <li> <?php echo !empty($dietPlans->plan_description)?$dietPlans->plan_description:""; ?></li> 
                  <br>
                  <li><b>Active From </b> <span class="pull-right">:</span></li>
                  <li> <?php echo !empty($startDate)?$startDate:""; ?></li>
                  <li> <b>Active TO </b> <span class="pull-right">:</span> </li>
                  <li><?php echo !empty($endDate)?$endDate:""; ?></li>
                  <br>
                  <li> <b>Duration </b> <span class="pull-right">:</span></li>
                  <li><?php
                  if($durationTimes>12960000){ 
                    echo round($durationTimes/12960000).' year';
                  }else if($durationTimes>12960000){ 
                    echo round($durationTimes/12960000).' month';
                  }else if($durationTimes>216000){ 
                    echo round($durationTimes/216000).' day';
                  }else if($durationTimes>3600){
                    echo round($durationTimes/3600).' hour';
                  }else if($durationTimes>60){ 
                    echo round($durationTimes/60).' min';
                  }else{ 
                    echo $durationTimes.' sec';
                  } ?></li>
                  <li> <b>Weight Lost </b> <span class="pull-right">:</span></li>
                  <li> 0 lbs</li>
                 </ul>
              </div>
            </div>
          </a>
        <?php }else{
          echo '<div class="col-md-6">
                  <div class="auto-diet-box">
                    <h3 class="text-danger">Diet plan record not found</h3>
                  </div>
                </div>';
        } 
        if(!empty($workOutPlan)){
          if($workOutPlan->plan_expired=='0000-00-00 00:00:00'){
            $durationTimes = time() - strtotime($workOutPlan->created_date); 
            $startDate = date('d M Y H:i A', strtotime($workOutPlan->created_date));
            $endDate   = date('d M Y H:i A');
          }else{
            $durationTimes = strtotime($workOutPlan->plan_expired) - strtotime($workOutPlan->created_date); 
            $startDate = date('d M Y H:i A', strtotime($workOutPlan->created_date));
            $endDate   = date('d M Y H:i A', strtotime($workOutPlan->plan_expired));
          }?>
          <a href="<?php echo base_url('user/workout_plan?plan_id='.$workOutPlan->id.'&user_id='.$this->input->get('user_id').'&goal_id='.$this->input->get('goal_id'));?>">
            <div class="col-md-6">
              <div class="auto-diet-box">
                <ul class="list-inline">
                  <li> <b>Workout Plan </b> <span class="pull-right">:</span></li>
                  <li><?php echo !empty($workOutPlan->plan_name)?$workOutPlan->plan_name:""; ?></li>
                  <li><b>Description </b> <span class="pull-right">:</span></li>
                  <li> <?php echo !empty($workOutPlan->plan_description)?$workOutPlan->plan_description:""; ?></li> <br>
                  <li><b>Active From </b> <span class="pull-right">:</span></li>
                  <li> <?php echo !empty($startDate)?$startDate:""; ?></li>
                  <li> <b>Active TO </b> <span class="pull-right">:</span> </li>
                  <li><?php echo !empty($endDate)?$endDate:""; ?></li>
                  <br>
                  <li> <b>Duration </b> <span class="pull-right">:</span></li>
                  <li><?php
                  if($durationTimes>12960000){ 
                    echo round($durationTimes/12960000).' year';
                  }else if($durationTimes>12960000){ 
                    echo round($durationTimes/12960000).' month';
                  }else if($durationTimes>216000){ 
                    echo round($durationTimes/216000).' day';
                  }else if($durationTimes>3600){
                    echo round($durationTimes/3600).' hour';
                  }else if($durationTimes>60){ 
                    echo round($durationTimes/60).' min';
                  }else{ 
                    echo $durationTimes.' sec';
                  }
                   ?>
                     
                   </li>
                  <li> <b>Weight Lost </b> <span class="pull-right">:</span></li>
                  <li> 0 lbs</li>
                 </ul>
              </div>
            </div>
          </a>
        <?php }else{
          echo '<div class="col-md-6">
                  <div class="auto-diet-box">
                    <h3 class="text-danger">Workout plan record not found</h3>
                  </div>
                </div>';
        } ?>
      </div>      
    </div>
  </div>
</div>