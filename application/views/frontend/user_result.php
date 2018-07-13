<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript">
  function heightTextChange(height_id='', height_text=''){
    console.log('height_id= '+height_id+', height_text = '+height_text);
    $('.max_height_'+height_id+' .select-styled').text(height_text);   
  }
</script>
<div class="col-md-9">
  <div class="feed_right">
    <div class="goal_setter">
      <h2>User Search</h2>
      <hr />
      <!-- <form action="" onsubmit="return false;" method="post" id="createGoalSetter"> -->
        <?php 
          $user = user_info();
           if(!empty($user->useMetricsSystem) && $this->input->get('height') && $user->useMetricsSystem==1){
            $hightTitleA = ($this->input->get('height'))?number_format($this->input->get('height'), 0).' cm':'';
            $hightID     = number_format($this->input->get('height'), 0);
          }else if(!empty($user->height)){
            $inches      = intval(number_format($this->input->get('height')/2.54, 2)); 
            $feetInch    = "";
            $feetInch   .= floor($inches/12)."'";
            $feetInch   .= floor($inches%12);                     
            $hightTitleA =  $feetInch;
            $hightID     = number_format($this->input->get('height'), 0);
            //echo $user->height.' height aaa ';
          }
        ?>
        <form action="" method="get">
          <div class="col-md-12">
            <div id="createGoalSetter_res"></div>
              <div class="form-group clearfix">
                <label for="inputEmail3" class="col-sm-5 control-label">Current Height</label>
                <!-- <div class="col-sm-7">
                  <input type="text" class="form-control" id="inputEmail3" value="<?php if($this->input->get('height')){echo $this->input->get('height');} ?>" name="height" placeholder="Height in <?php if(!empty($user->useMetricsSystem) && $user->useMetricsSystem==1){ echo ' cm'; }else if(!empty($user->useMetricsSystem) && $user->useMetricsSystem==2){ echo ' feet and inch'; } ?>">
                </div> -->
              <div class="col-sm-7 max_height_goal">
              <?php 
              if(!empty($user->useMetricsSystem) && $user->useMetricsSystem==1){ ?>
                <select class="form-control height" id="mounth" name="height">
                <?php 
                  $hightTitle = "";
                  for($hi = 91.44;$hi<305;$hi++){
                    if(number_format($hi,0)==number_format($hightID, 0)){ 
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
                  if(floor($hieghtStrCmC) == floor($hightID)){ 
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
                <label for="inputEmail3weight" class="col-sm-5 control-label">Current Weight</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" id="inputEmail3weight" placeholder="Weight in <?php if(!empty($user->useMetricsSystem) && $user->useMetricsSystem==1){ echo ' kg'; }else if(!empty($user->useMetricsSystem) && $user->useMetricsSystem==2){ echo ' lbs'; } ?>" name="weight" value="<?php if($this->input->get('wieght')){echo $this->input->get('wieght');} ?>">
                </div>
              </div>
          </div>
          <div class="check_lose check_lose10 clearfix">   
            <div class="form-group clearfix">
              <label class="col-sm-5 control-label">Lose Weight</label>
              <div class="col-sm-6 checkbox checkbox-success">
                <input id="checkbox300"  class="lose_w loseWeightIn"  <?php if($this->input->get('goal_type')&&$this->input->get('goal_type')==1){echo 'checked';} ?> type="radio" onclick="checkGoalType('1');"  name="goal_type" value="1">            
              </div>
            </div>
            <div class="form-group clearfix">
              <label class="col-sm-5 control-label">Gain Muscle</label>
              <div class="col-sm-6 checkbox checkbox-success">
                <input id="checkbox4" type="radio" value="2" <?php if($this->input->get('goal_type')&&$this->input->get('goal_type')==2){echo 'checked';} ?> onclick="checkGoalType('2');"  name="goal_type">             
              </div>
            </div> 
            <div class="form-group clearfix">
              <label class="col-sm-5 control-label">Maintain</label>
              <div class="col-sm-6 checkbox checkbox-success">
                <input id="checkbox5" type="radio" value="3" <?php if($this->input->get('goal_type')&&$this->input->get('goal_type')==3){echo 'checked';} ?> onclick="checkGoalType('3');"  name="goal_type">            
              </div>
            </div>
          </div>
          <div class="col-md-12" id="" >
            <div id="gaol_type_in">
              <div class="form-group clearfix">
                <label for="inputEmail3" class="col-sm-5 control-label">Lose How Much ?</label>
                <div class="col-sm-7">
                	<input type="text" class="form-control" id="loseWeight" placeholder="ex 20 <?php if(!empty($user->useMetricsSystem) && $user->useMetricsSystem==1 && !empty($user->weight)){ echo ' kg'; }else if(!empty($user->wieght_lbs)){ echo ' lbs'; } ?>" value="<?php if($this->input->get('loseWeight')){echo $this->input->get('loseWeight');} ?>" name="loseWeight" maxlength="3">
                  <div id="loseWeight_error" class="error"></div>
                </div>
              </div>
              <div class="form-group clearfix">
                <label for="inputEmail3" class="col-sm-5 control-label">In How Many Days ?</label>
                <div class="col-sm-7">
                  <input type="text" class="form-control" value="<?php if($this->input->get('loseDay')){echo $this->input->get('loseDay');} ?>" id="loseDay" placeholder="60" name="loseDay"  maxlength="3">
                   <div id="loseDay_error" class="error"></div>
                </div>
              </div>             
            </div>
            <div class="form-group b_button text-center">
              <input type="submit" class="btn btn-info btn-block" value="Search">
            </div>
          </div>
        </form>
        <div class="user_search">  
          <h2 style="text-align: center;">Result : <?php echo !empty($totalUsers)?$totalUsers:'0';?> </h2> 
          <hr>
          <div class="row"> 
            <?php 
            if(!empty($rows)){
              foreach($rows as $user){
                if(!empty($user->profile_pic)&&file_exists('assets/uploads/users/thumbnails/'.$user->profile_pic)){
                  $profile_pic = base_url().'assets/uploads/users/thumbnails/'.$user->profile_pic;
                }else{
                  $profile_pic = base_url('assets/front/img/roky-logo.png');
                }
                $uriSeg = $user->user_name.'-'.$user->user_id;
                ?>
                <div class="col-md-3">
                  <a href="<?php echo base_url('user/show_user_plan/'.$uriSeg.'?goal_id='.$user->id.'&user_id='.$user->user_id); ?>" class="username_images_user row">
                    <img src="<?php echo $profile_pic; ?>" alt="<?php echo !empty($user->user_name)?$user->user_name:''; ?>" style="width: 150px;height:150px;">
                    <h4><?php echo !empty($user->user_name)?'<br/>'.ucfirst($user->user_name):''; ?></h4>
                  </a>
                </div>
              <?php }
            } ?>            
          </div>
          <div class="text-center"><?php if(!empty($pagination)) echo $pagination; ?></div>
          <div class="clearfix"></div>  
        </div>
        <br/>
        <br/>    
        </div>
      <!-- </form> -->
      <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>  
  </div>
</div>