<style type="text/css"> 
  .followsss { margin: 15px 0px; border: 1px solid #1bbdb0;  padding: 10px;  text-decoration: none; cursor: pointer;}
</style>
<?php 
//echo '<pre>'; print_r($currentPlan); exit();
if(!empty($currentPlan)){?>
  <h2>Diet Plan - <?php echo !empty($currentPlan->plan_name)?ucfirst($currentPlan->plan_name):''; ?>
<?php
  $user   = user_info();
  if(!empty($startTimeZoneN)&&!empty($endTimeZoneN)){
    $startTimeZone = $startTimeZoneN;
    $endTimeZone   = $endTimeZoneN;
  }else{    
    $wday          = date('w')-1;
    $startTimeZone = strtotime('-'.$wday.' days');
    $endTimeZone   = strtotime('+'.(6-$wday).' days');
  }
  $preSdate  = $startTimeZone - (604800);
  $preEdate  = $endTimeZone - (604800);
  $nextSdate = $startTimeZone + (604800);
  $nextEdate  = $endTimeZone + (604800);
?>
<span class="pull-right metrics_date">
  <a href="javascript:void(0);" onclick="show_diet_plan_data('<?php echo $preSdate; ?>','<?php echo  $preEdate; ?>','<?php echo $currentPlan->id; ?>','<?php echo $currentPlan->created_date;?>', '<?php echo ($this->input->get('user_id'))?$this->input->get('user_id'):''; ?>','pre');">
    <i class="fa fa-chevron-left"></i>
  </a>
&nbsp;&nbsp;&nbsp;
<?php
 if(date('Y', $startTimeZone)!=date('Y', $endTimeZone)){
    $week_start = date('M d Y', $startTimeZone);
    $week_end   = date('M d Y', $endTimeZone);
  }else if(date('m', $startTimeZone)==date('m', $endTimeZone)){
    $week_start = date('M d', $startTimeZone);
    $week_end   = date('d, Y ', $endTimeZone);
  }else{          
    $week_start = date('M d', $startTimeZone);
    $week_end   = date('M d, Y ', $endTimeZone);
  }
  echo $week_start.' - '.$week_end;  
?>
&nbsp;&nbsp;&nbsp;
<a href="javascript:void(0);" onclick="show_diet_plan_data('<?php echo $nextSdate; ?>','<?php echo  $nextEdate; ?>','<?php echo $currentPlan->id; ?>','<?php echo $currentPlan->created_date; ?>', '<?php echo ($this->input->get('user_id'))?$this->input->get('user_id'):''; ?>','next');"><i class="fa fa-chevron-right"></i></a>
</span></h2>
<hr />                              
<!--==accordian start==-->
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
<?php 
  $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'); 
  if(!empty($days)){
    $i=1;
    $ips = 0;
    $menuAd = 0;
    if($this->input->get('user_id')==''&&$this->input->get('plan_id')==''){
      $menuAd = 1;
    }else if($this->input->get('plan_id')&&$this->input->get('user_id')&&$this->input->get('plan_id')==$currentPlan->id&&$this->input->get('user_id')==user_id()){
      $menuAd = 1;
    }else if($this->input->get('user_id')&&$this->input->get('user_id')==user_id()){
      $menuAd = 1;
    }
    foreach($days as $day){
      $startCursZone = $startTimeZone+(86400*$ips); 
      $dateTimeZone  = strtotime(date('Y-m-d', $startCursZone));
      if(!empty($currentPlan->id)){        
        $dayDetails = $this->developer_model->getDietPlanDayNew($dateTimeZone, $currentPlan->id);
      }
      //echo $this->db->last_query().'<br/>';
      ?>
      <div class="panel panel-default title_low_carb">
        <h4 class="panel-title" id="heading<?php echo $day; ?>">
          <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $day; ?>" aria-expanded="true" aria-controls="collapseOne">
            <?php echo ucfirst($day);?>
          </a>
        </h4>
        <div id="collapse<?php echo $day; ?>" class="panel-collapse collapse <?php if($day==strtolower(date('l'))){echo 'in';} ?>" role="tabpanel" aria-labelledby="heading<?php echo $day; ?>">
          <div class="panel-body diet_plan_low table-responsive">
            <table class="table table-striped"> 
              <thead>
                <tr>
                  <th>Meal</th>
                  <th>Name</th>
                  <th>Serving</th> 
                  <th>Protein</th> 
                  <th>Carb</th> 
                  <th>Calories</th>         
                  <th style="">                   
                  </th>     
                </tr>
                </thead>
                <tbody>
                  <?php 
                  $totalCacalories = $totalProtein = $totalCarbohydrate = $totalServing = 0;
                  if(!empty($dayDetails)){
                    foreach($dayDetails as $dayDetail){                      
                      $checkRows = $this->common_model->get_row('diet_plan_take_food', array('diet_food_id'=>$dayDetail->id, 'food_taken_date'=>$dateTimeZone)); 
                      ?>
                      <tr class="text-left <?php if(!empty($checkRows)){ echo 'activePlanRow';} ?>" id="row_<?php echo !empty($dayDetail->id)?$dayDetail->id:''; ?>_<?php echo !empty($day)?$day:''; ?>"> 
                        <td><?php echo !empty($dayDetail->meal)?ucwords($dayDetail->meal):''; ?></td> 
                        <td><?php echo !empty($dayDetail->item_title)?ucwords($dayDetail->item_title):''; ?>&nbsp;&nbsp;<i  title="Food Details" data-toggle="modal" style="cursor: pointer;" onclick="dietPlanDetails('<?php echo !empty($dayDetail->item_id)?ucwords($dayDetail->item_id):''; ?>','<?php echo !empty($dayDetail->item_title)?ucwords($dayDetail->item_title):''; ?>');" data-target="#myModalA" class="fa fa-info-circle" aria-hidden="true"></i>
                        </td>
                        <td><?php echo !empty($dayDetail->serving)?ucwords($dayDetail->serving):''; ?></td>
                        <td><?php echo !empty($dayDetail->protein)?ucwords($dayDetail->protein).'g':''; ?></td>
                        <td><?php echo !empty($dayDetail->carbohydrate)?ucwords($dayDetail->carbohydrate).'g':''; ?></td>
                        <td><?php 
                          $dayDetail->cacalories = $dayDetail->cacalories * $dayDetail->serving;
                          echo !empty($dayDetail->cacalories)?ucwords($dayDetail->cacalories):'cal'; ?></td>
                        <?php 
                        if(!empty($menuAd)){ ?>
                            <td width="80" class="text-center">                         
                              <a href="javascript:void(0);" onclick="checkDietPlanFood('<?php echo $dayDetail->id; ?>', '<?php echo !empty($day)?$day:''; ?>', '<?php echo $dateTimeZone; ?>', '<?php echo !empty($currentPlan->id)?$currentPlan->id:''; ?>');" class="btn btn-xs editt" id="btn_<?php echo !empty($dayDetail->id)?$dayDetail->id:''; ?>_<?php echo !empty($day)?$day:''; ?>" title="Set/Unset Food">
                               <?php if(!empty($checkRows)){ echo '<i class="fa fa-times-circle"></i>'; }else{echo '<i class="fa fa-check-circle"></i>';}?> 
                              </a>
                              <a class="btn btn-xs editt" data-toggle="modal" data-target="#myModalA" onclick="editDietPlanFood('<?php echo $dayDetail->id; ?>');" href="javascript:void(0);" title="Edit Food">
                                <i class="fa fa-pencil"></i>
                              </a>
                            </td> 
                        <?php }?>                                 
                      </tr> 
                    <?php 
                      if(!empty($checkRows)){
                        $totalCacalories    = $totalCacalories   + $dayDetail->cacalories;
                        $totalServing       = $totalServing      + $dayDetail->serving;
                        $totalProtein       = $totalProtein      + $dayDetail->protein;
                        $totalCarbohydrate  = $totalCarbohydrate + $dayDetail->carbohydrate;
                      }
                    }
                  }
                  if(!empty($menuAd)){ ?>
                    <tr>
                      <td colspan="6"></td>
                      <td class="text-center"><a class="btn btn-sm btn-success"  href="<?php echo base_url('user/create_diet_plan?type=addFood&date='.$dateTimeZone.'&day='.$day);if(!empty($currentPlan->goal_id)){echo '&goal_id='.$currentPlan->goal_id; };if(!empty($currentPlan->id)){echo '&diet_id='.$currentPlan->id; }; ?>" title="Add more food or drink"><i class="fa fa-plus-circle"></i></a></td>
                    </tr>
                    <?php
                  }
                  echo '<tr>';
                  echo '<th colspan="2">Total</th>';
                  echo !empty($totalServing)?'<th id="totalServing_'.$dateTimeZone.'">'.number_format($totalServing, 2).'</th>':'<td id="totalServing_'.$dateTimeZone.'">-</td>';
                  echo !empty($totalProtein)?'<th id="totalProtein_'.$dateTimeZone.'">'.$totalProtein.'g</th>':'<td id="totalProtein_'.$dateTimeZone.'">-</td>';
                  echo !empty($totalCarbohydrate)?'<th>'.$totalCarbohydrate.'g</th id="totalCarbohydrate_'.$dateTimeZone.'">':'<td id="totalCarbohydrate_'.$dateTimeZone.'">-</td>';               
                  echo !empty($totalCacalories)?'<th id="totalCacalories_'.$dateTimeZone.'">'.$totalCacalories.'cal</th>':'<td id="totalCacalories_'.$dateTimeZone.'">-</td>';
                  ?>
                  <th class="text-center">                  
                  </th>
                  <?php 
                  echo '</tr>';                  
                  ?>
                </tbody>
              </table>
          </div>
        </div>
      </div>
      <?php       
      $ips++;
      $i++;
    }
  }
if($this->input->get('user_id')&&$this->input->get('user_id')!=user_id()){?> 
  <div class="followsss text-center">
    <a class="following-settingss" href="javascript:void();" onclick="copy_diet_plan('<?php if(!empty($currentPlan->id)){ echo $currentPlan->id;} ?>','<?php if(!empty($currentPlan->goal_id)){ echo $currentPlan->goal_id;} ?>','<?php if($this->input->get('user_id')){ echo $this->input->get('user_id');} ?>');">
      Copy
    </a>
  </div>
  <div id="copy_diet_plan_res"></div>  
<?php }?>                  
</div>
<?php }else{
  echo '<br/><br/><br/><h2 class="text-danger text-center">Diet plan records not found</h2>';
}
?>
