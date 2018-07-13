  <?php if($this->input->get('planType')=='user_info'){?>
  <div class="col-md-8">
    <table class="table table-hover table-bordered">
        <tr class="userNameM">
          <th>Full Name</th>
          <td class="customerName">
            <?php 
              echo !empty($row->first_name)?ucfirst($row->first_name):''; 
              echo !empty($row->last_name)?' '.ucfirst($row->last_name):''; 
            ?>
          </td>
        </tr>   
        <tr class="userNameUM">
          <th>User Name</th>
          <td class="customerNameU">
            <?php echo !empty($row->user_name)?$row->user_name:''; ?>
          </td>
        </tr>            
        <tr class="emailM">
          <th>Email</th>
          <td class="email"><?php echo !empty($row->email)?$row->email:''; ?></td>
        </tr>
        <tr class="genderM">
          <th>Gender</th>
          <td class="gender"><?php echo !empty($row->gender)?ucfirst($row->gender):''; ?></td>
        </tr>
        <tr class="dobM">
          <th>Date Of Birth</th>
          <td class="dob">
            <?php echo !empty($row->date_of_birth)?date('d M Y', strtotime($row->date_of_birth)):''; ?>
          </td>
        </tr>
        <tr class="advancedMetricsTrackingM">
          <th>Advanced Metrics Tracking</th>
          <td class="advancedMetricsTracking"><?php echo !empty($row->advancedMetricsTracking)?'Yes':'No'; ?></td>
        </tr>
        <tr class="useMetricsSystemM">
          <th>Use Metric System</th>
          <td class="useMetricsSystem"><?php echo !empty($row->useMetricsSystem)?'Yes':'No'; ?></td>
        </tr>
         <tr class="useMetricsSystemM">
          <th>Privacy Setting</th>
          <td class="useMetricsSystem">
            <?php 
              if(!empty($row->privacy)&&$row->privacy==1){echo 'Public';} 
              if(!empty($row->privacy)&&$row->privacy==2){echo 'Private';} 
              if(!empty($row->privacy)&&$row->privacy==3){echo 'Followers Only';} 
            ?>
          </td>
        </tr>
         <tr class="useMetricsSystemM">
          <th> Failed login attempts</th>
          <td class="useMetricsSystem">0</td>
        </tr>
        <?php if(!empty($row->useMetricsSystem)&&$row->useMetricsSystem==1){
           $rowH   = $this->common_model->get_row('height', array('id'=>$row->hieght_cm));
         ?>
          <tr class="advancedMetricsTrackingM">
            <th>Height</th>
            <td class="advancedMetricsTracking">
              <?php echo !empty($rowH->hieght_cm)?$rowH->hieght_cm.' CM':'-'; ?>
            </td>
          </tr>
          <tr class="advancedMetricsTrackingM">
            <th>Weight</th>
            <td class="advancedMetricsTracking">
              <?php echo !empty($row->weight)?$row->weight.' KG':'-'; ?>
            </td>
          </tr>
        <?php }else{
          $rowH   = $this->common_model->get_row('height', array('id'=>$row->height));
         ?>
          <tr class="advancedMetricsTrackingM">
            <th>Height</th>
            <td class="advancedMetricsTracking">
              <?php echo !empty($rowH->height_title)?$rowH->height_title.'':'-'; ?>
            </td>
          </tr>
          <tr class="advancedMetricsTrackingM">
            <th>Weight</th>
            <td class="advancedMetricsTracking">
              <?php echo !empty($row->wieght_lbs)?$row->wieght_lbs.' lbs':'-'; ?>
            </td>
          </tr>
        <?php }?>
        <tr class="advancedMetricsTrackingM">
          <th>Following</th>
          <td class="advancedMetricsTracking">
            <?php echo get_all_count('follow_request', array('sender_id'=>$row->id)); ?>
          </td>
        </tr>
        <tr class="useMetricsSystemM">
          <th>Followers</th>
          <td class="useMetricsSystem">
            <?php echo get_all_count('follow_request', array('receiver_id'=>$row->id)); ?>
          </td>
        </tr>
        <tr class="useMetricsSystemM">
          <th>Subscription  Plan</th>
          <td class="useMetricsSystem"><?php echo !empty($row->plan_title)?ucwords($row->plan_title):'-'; ?></td>
        </tr>
        <tr class="useMetricsSystemM">
          <th>Last Posted Payment Date</th>
          <td class="useMetricsSystem">-</td>
        </tr>
        <tr class="useMetricsSystemM"> 
          <th>Last login location</th>
          <td class="useMetricsSystem">
            <?php echo !empty($row->last_location)?ucfirst($row->last_location):'-'; ?>
          </td>
        </tr> 
        <tr class="last_loginM">
          <th>Last Login</th>
          <td class="last_login">
            <?php echo !empty($row->last_login)?date('d M Y', strtotime($row->last_login)):''; ?>
          </td>
        </tr>
        <tr class="last_ipM">
          <th>Last IP</th>
          <td class="last_ip"><?php echo !empty($row->last_ip)?$row->last_ip:''; ?> </td>
        </tr>
        <tr class="createdM">
          <th>Registered Date</th>
          <td class="created">
            <?php echo !empty($row->created_date)?date('d M Y', strtotime($row->created_date)):''; ?>
          created_date</td>
        </tr>
    </table>
  </div>
  <div class="col-md-4">               
    <div class="fileM">
      <?php $userPic = (!empty($row->profile_pic)&&file_exists('assets/uploads/users/thumbnails/'.$row->profile_pic))?base_url()."assets/uploads/users/thumbnails/".$row->profile_pic:base_url()."assets/uploads/admin/images/profile_default.png"; ?>
      <img class="profilePics img-responsive" src="<?php echo $userPic; ?>">
    </div>
  </div>
  <?php 
  }
  $currentWorkOutPlan = array();
  $PrevusWorkOutPlans = array();
  $currentDietPlan    = array();
  $PrevusDietPlans    = array();
  if(!empty($userPlans)){
    foreach($userPlans as $userPlan){
      if($userPlan->planType==2&&$userPlan->activatePlan==1){
        $currentWorkOutPlan = $userPlan;
      }
      if($userPlan->planType==2&&$userPlan->activatePlan==0){
        $PrevusWorkOutPlans[] = $userPlan;
      }
      if($userPlan->planType==1&&$userPlan->activatePlan==1){
        $currentDietPlan = $userPlan;
      }
      if($userPlan->planType==1&&$userPlan->activatePlan==1){
        $PrevusDietPlans[] = $userPlan;
      }
    }
  }
  if($this->input->get('planType')=='workout'){
    if(!empty($currentWorkOutPlan)){
      $plan = $currentWorkOutPlan;
      echo '<div class="col-md-12"><h3>Current Workout Plan</h3>';
      echo '<table class="table table-hover table-bordered">
              <tr>
                <th>Plan Name</th>
                <th>Plan Description</th>
                <th>Exercise Type </th>
                <th>Exercise</th>
                <th>Goal Height
                <th>Goal wieght</th>
                <th>Lose Day</th>
                <th>Lose Weight</th>
                <th width="180">Plan Created Date</th>
              </tr>
              ';
      echo '<tr>';
        echo !empty($plan->plan_name)?'<td>'.ucwords($plan->plan_name).'</td>':'<td>-</td>';
        echo !empty($plan->plan_description)?'<td>'.ucwords($plan->plan_description).'</td>':'<td>-</td>';
        echo !empty($plan->items)?'<td>'.getWorkOutPlansItems($plan->items).'</td>':'<td>-</td>';
        echo !empty($plan->exercise)?'<td>'.getWorkOutEx($plan->exercise).'</td>':'<td>-</td>';
        echo !empty($plan->height)?'<td>'.ucwords($plan->height).'</td>':'<td>-</td>';
        echo !empty($plan->wieght)?'<td>'.ucwords($plan->wieght).'</td>':'<td>-</td>';
        echo !empty($plan->loseDay)?'<td>'.ucwords($plan->loseDay).'</td>':'<td>-</td>';
        echo !empty($plan->loseWeight)?'<td>'.ucwords($plan->loseWeight).'</td>':'<td>-</td>';
        echo !empty($plan->created_date)?'<td>'.date('d M Y h:i:s', strtotime($plan->created_date)).'</td>':'<td>-</td>';
      echo '</tr>';
      echo '</table>
      </div>';
    }
    if(!empty($PrevusWorkOutPlans)){
      echo '<div class="col-md-12"><h3>Previous work out plans</h3>';
      echo '<table class="table table-hover table-bordered">';
      echo'<tr>
            <th>Plan Name</th>
            <th>Plan Description</th>
            <th>Exercise Type </th>
            <th>Exercises</th>
            <th>Goal Height
            <th>Goal wieght</th>
            <th>Lose Day</th>
            <th>Lose Weight</th>
            <th width="180">Plan Created Date</th>
          </tr>
          ';
        foreach($PrevusWorkOutPlans as $plan){       
          echo '<tr>';
            echo !empty($plan->plan_name)?'<td>'.ucwords($plan->plan_name).'</td>':'<td>-</td>';
            echo !empty($plan->plan_description)?'<td>'.ucwords($plan->plan_description).'</td>':'<td>-</td>';
            echo !empty($plan->items)?'<td>'.getWorkOutPlansItems($plan->items).'</td>':'<td>-</td>';
            echo !empty($plan->exercise)?'<td>'.getWorkOutEx($plan->exercise).'</td>':'<td>-</td>';
            echo !empty($plan->height)?'<td>'.ucwords($plan->height).'</td>':'<td>-</td>';
            echo !empty($plan->wieght)?'<td>'.ucwords($plan->wieght).'</td>':'<td>-</td>';
            echo !empty($plan->loseDay)?'<td>'.ucwords($plan->loseDay).'</td>':'<td>-</td>';
            echo !empty($plan->loseWeight)?'<td>'.ucwords($plan->loseWeight).'</td>':'<td>-</td>';
            echo !empty($plan->created_date)?'<td>'.date('d M Y h:i:s', strtotime($plan->created_date)).'</td>':'<td>-</td>';
          echo '</tr>';     
        }
      echo '</table></div>';
    }
    if(empty($currentWorkOutPlan)&&empty($PrevusWorkOutPlans)){
      echo '<h3 class="text-danger text-center">No workout plan records found</h3>';
    }
  }
  if($this->input->get('planType')=='diet'){
    if(!empty($currentDietPlan)){
      $plan = $currentDietPlan;
      echo '<div class="col-md-12"><h3>Current Diet Plan</h3>';
      echo '<table class="table table-hover table-bordered">
              <tr>
                <th>Plan Name</th>
                <th>Plan Description</th>
                <th>Food or Drink Category </th>
                <th>Food or Drink </th>
                <th>Goal Height
                <th>Goal wieght</th>
                <th>Lose Day</th>
                <th>Lose Weight</th>
                <th width="180">Plan Created Date</th>
              </tr>
              ';
      echo '<tr>';
        echo !empty($plan->plan_name)?'<td>'.ucwords($plan->plan_name).'</td>':'<td>-</td>';
        echo !empty($plan->plan_description)?'<td>'.ucwords($plan->plan_description).'</td>':'<td>-</td>';
        echo !empty($plan->items)?'<td>'.getWorkOutPlansItems($plan->items).'</td>':'<td>-</td>';
        echo !empty($plan->exercise)?'<td>'.getDietPlanF($plan->exercise).'</td>':'<td>-</td>';
        echo !empty($plan->height)?'<td>'.ucwords($plan->height).'</td>':'<td>-</td>';
        echo !empty($plan->wieght)?'<td>'.ucwords($plan->wieght).'</td>':'<td>-</td>';
        echo !empty($plan->loseDay)?'<td>'.ucwords($plan->loseDay).'</td>':'<td>-</td>';
        echo !empty($plan->loseWeight)?'<td>'.ucwords($plan->loseWeight).'</td>':'<td>-</td>';
        echo !empty($plan->created_date)?'<td>'.date('d M Y h:i:s', strtotime($plan->created_date)).'</td>':'<td>-</td>';
      echo '</tr>';
      echo '</table></div>';
    }  
    if(!empty($PrevusDietPlans)){
      echo '<div class="col-md-12"><h3>Previous Diet plans</h3>';
      echo  '<table class="table table-hover table-bordered">
              <tr>
                <th>Plan Name</th>
                <th>Plan Description</th>
                <th>Food or Drink Category </th>
                <th>Food or Drink </th>
                <th>Goal Height
                <th>Goal Weight</th>
                <th>Lose Day</th>
                <th>Lose Weight</th>
                <th width="180">Plan Created Date</th>
              </tr>
            ';
        echo '<tr>';
        foreach($PrevusDietPlans as $plan){        
          echo !empty($plan->plan_name)?'<td>'.ucwords($plan->plan_name).'</td>':'<td>-</td>';
          echo !empty($plan->plan_description)?'<td>'.ucwords($plan->plan_description).'</td>':'<td>-</td>';
          echo !empty($plan->items)?'<td>'.getWorkOutPlansItems($plan->items).'</td>':'<td>-</td>';
          echo !empty($plan->exercise)?'<td>'.getDietPlanF($plan->exercise).'</td>':'<td>-</td>';
          echo !empty($plan->height)?'<td>'.ucwords($plan->height).'</td>':'<td>-</td>';
          echo !empty($plan->wieght)?'<td>'.ucwords($plan->wieght).'</td>':'<td>-</td>';
          echo !empty($plan->loseDay)?'<td>'.ucwords($plan->loseDay).'</td>':'<td>-</td>';
          echo !empty($plan->loseWeight)?'<td>'.ucwords($plan->loseWeight).'</td>':'<td>-</td>';
          echo !empty($plan->created_date)?'<td>'.date('d M Y h:i:s', strtotime($plan->created_date)).'</td>':'<td>-</td>';
        echo '</tr>';      
        }
        echo '</table></div>';
    }
    if(empty($currentDietPlan)&&empty($PrevusDietPlans)){
      echo '<h3 class="text-danger text-center">No diet plan records found</h3>';
    }
  }
  ?>
