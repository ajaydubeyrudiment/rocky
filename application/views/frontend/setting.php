<div class="col-md-9 f_r_user">
    <div class="setting">
     <h2>Setting</h2> 
     <hr />
     <div id="setting_res"></div>
     <form class="form-horizontal" id="userSetting" onsubmit="return false;">
        <div class="form-group">
          <h4 class="main_title">General</h4>
          <label for="inputEmail3" class="col-sm-3 control-label">Privacy Setting</label>
          <div class="col-sm-6 selectPrivacyMain">
           <select class="form-control selectPrivacy" id="mounth"  name="privacy">
              <option value="1" <?php if(!empty($user->privacy)&&$user->privacy==1){ 
                echo 'selected';  $privacyText = 'Public';} ?>>Public</option>
              <!-- <option value="3" <?php if(!empty($user->privacy)&&$user->privacy==3){ 
                echo 'selected'; $privacyText = 'Followers Only'; } ?>>Followers Only</option> -->
              <option value="2" <?php if(!empty($user->privacy)&&$user->privacy==2){ 
                echo 'selected'; $privacyText = 'Private'; } ?>>Private</option>             
           </select>
           <input type="hidden" id="privacyText" value="<?php if(!empty($privacyText)) echo $privacyText; ?>">
           <div class="text-danger" id="selectPrivacy_error"></div>
          </div>
        </div>
        <div class="form-group">
          <label for="inputPassword3" class="col-sm-3 control-label">Advanced Metrics Tracking</label>
          <div class="col-sm-6">
             <div class="checkbox checkbox-success ml-20">
                 <input id="checkbox312" name="advancedMetricsTracking" <?php if(!empty($user->advancedMetricsTracking)&&$user->advancedMetricsTracking==1){ echo 'checked';  } ?> value="1" class="advancedMetricsTracking" type="checkbox">
                 <label for="checkbox312"></label>
             </div>
             <div class="text-danger" id="advancedMetricsTracking_error"></div>
          </div>
        </div>
        <div class="form-group">
          <label for="inputPassword3" class="col-sm-3 control-label">Use Metrics System</label>
          <div class="col-sm-6">
            <div class="checkbox checkbox-success ml-20">
                 <input id="checkbox4" name="useMetricsSystem" <?php  if(!empty($user->useMetricsSystem)&&$user->useMetricsSystem==1){ 
                echo 'checked';  } ?> value="1" class="useMetricsSystem" type="checkbox">
                 <label for="checkbox4">  </label>
            </div>
            <div class="text-danger" id="useMetricsSystem_error"></div>
          </div>
        </div>       
        <div class="form-group">
          <h4 class="main_title">Contact Info</h4>
          <label for="inputEmail3" class="col-sm-3 control-label">Email</label>
          <div class="col-sm-6">
            <input type="text"   name="contactEmail" value="<?php if(!empty($user->contactEmail)){ echo $user->contactEmail;}  ?>" class="form-control" id="contactEmail" placeholder="Enter Your Email">
            <div class="text-danger" id="contactEmail_error"></div>
          </div>
        </div>
          <div class="form-group">
          <label for="inputEmail3" class="col-sm-3 control-label">Mobile Number</label>
          <div class="col-sm-6">
            <input type="text"  name="contactMobile" value="<?php  if(!empty($user->contactMobile)){ echo $user->contactMobile;}   ?>" class="form-control" id="contactMobile" placeholder="Enter Your Mobile Number">
            <div class="text-danger" id="contactMobile_error"></div>
          </div>
        </div>
        <div class="form-group">
          <h4 class="main_title">Password Change</h4>
          <label for="inputEmail3" class="col-sm-3 control-label">Current Password</label>
          <div class="col-sm-6">
            <input type="password" class="form-control" name="old_password" id="old_password" placeholder="Enter Your Currnet Password">
            <div class="text-danger" id="old_password_error"></div>
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail3" class="col-sm-3 control-label">New Password</label>
          <div class="col-sm-6">
            <input type="password" class="form-control" name="new_password" id="new_password" placeholder="Enter Your New Password" onchange="checkPasswords('new_password');">
            <div class="text-danger" id="new_password_error"></div>
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail3" class="col-sm-3 control-label">Re-Enter New Password</label>
          <div class="col-sm-6">
            <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Enter Your Re-enter Password">
            <div class="text-danger" id="confirm_password_error"></div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button onclick="updatePassword();"  type="submit" class="btn btn-default update_password pull-right">Update</button>
          </div>
        </div>
        <div class="form-group">
          <h4 class="main_title">Payment Method</h4>
          <label for="inputEmail3" class="col-sm-3 control-label">Paypal Email</label>
          <div class="col-sm-6">
            <input type="Enter Your Paypal email" name="paypalEmail" class="form-control" id="paypalEmail" placeholder="Email" value="<?php  if(!empty($user->paypalEmail)){ echo $user->paypalEmail;}   ?>">
             <div class="text-danger" id="paypalEmail_error"></div>
          </div>
        </div>
        <div class="form-group">
          <h4 class="main_title">Subscriptions</h4>
          <label for="inputEmail3" class="col-sm-3 control-label">Modify Plan</label>
          <div class="col-sm-6 selectPlanMain">
             <select class="form-control selectPlan selectPlanVals" id="mounth" name="plan" onchange="selectPlans();">
                <?php 
                $pan_texth = 'Basic';
                $plans     = getPlans();
                if(!empty($plans)){
                  foreach($plans as $plan){
                    if(!empty($user->plan)&&$user->plan==$plan->id){ 
                      $pan_text  = $plan->plan_title.' $'.number_format($plan->amount, 1);
                      $pan_texth = $plan->plan_title;
                      echo '<option selected value="'.$plan->id.'">'.$plan->plan_title.' $'.number_format($plan->amount, 1).'</option>'; 
                    }else{
                      echo '<option value="'.$plan->id.'">'.$plan->plan_title.' $'.number_format($plan->amount, 1).'</option>'; 
                    }
                  } 
                }?>                                   
           </select>
           <input type="hidden" id="userPlanText" value="<?php if(!empty($pan_text)) echo $pan_text; ?>">
           <div class="text-danger" id="selectPlan_error"></div>
          </div>
        </div>
        <div class="form-group">          
          <label for="inputEmail3" class="col-sm-3 control-label">Plan Benifits</label>
          <div class="col-sm-6 plan_b">
             <p id="plan_message">
              <?php             
                if($pan_texth=='Basic'){
                  echo 'Access to all cardio based workouts only.';
                }else if($pan_texth=='Standard'){ 
                  echo 'Access to all workouts.';
                }else if($pan_texth=='Premiuim'){
                  echo 'Access to all muscle gain workouts and content.';
                }
              ?>
             </p> 
          </div>
        </div>
        <input type="hidden" name="page_name" value="setting"/>
        <div class="form-group inline_b">
          <button class="btn btn-info" onclick="saveSetting();">Save Changes</button>
          <button class="btn btn-danger" onclick="cancelSetting();">Cancel</button>
        </div>
      </form>
    </div>
</div>
