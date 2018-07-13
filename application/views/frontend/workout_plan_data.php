<style type="text/css"> 
  .followsss { margin: 15px 0px; border: 1px solid #1bbdb0;  padding: 10px;  text-decoration: none; cursor: pointer;}
</style>
<?php 
if(!empty($currentPlan)){?>
  <h2>Workout Plan - <?php echo !empty($currentPlan->plan_name)?ucfirst($currentPlan->plan_name):''; ?><span class="pull-right metrics_date">
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
    <a href="javascript:void(0);" onclick="show_workout_plan_data('<?php echo $preSdate; ?>','<?php echo  $preEdate; ?>','<?php echo $currentPlan->id; ?>','<?php echo $currentPlan->created_date; ?>', '<?php echo ($this->input->get('user_id'))?$this->input->get('user_id'):''; ?>','pre');">
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
    <a href="javascript:void(0);" onclick="show_workout_plan_data('<?php echo $nextSdate; ?>','<?php echo  $nextEdate; ?>','<?php echo $currentPlan->id; ?>','<?php echo $currentPlan->created_date; ?>', '<?php echo ($this->input->get('user_id'))?$this->input->get('user_id'):''; ?>','next');"><i class="fa fa-chevron-right"></i></a>
      </span>
    </h2>
    <hr/>                              
    <!--==accordian start==-->
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
      <?php 
        $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'); 
        if(!empty($days)){
          $i   = 1;
          $ips = 0;
          foreach($days as $day){
            $startCursZone = $startTimeZone+(86400*$ips); 
            $dateTimeZone  = strtotime(date('Y-m-d', $startCursZone));
            if(!empty($currentPlan->id)){
              $dayDetails = $this->developer_model->getWorkOutPlanDayNew($dateTimeZone, $currentPlan->id);
            }
           ?>
            <div class="panel panel-default title_low_carb">
              <h4 class="panel-title" id="heading<?php echo $day; ?>">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $day; ?>" aria-expanded="true" aria-controls="collapseOne">
                  <?php echo ucfirst($day);?>
                </a>
              </h4>
              <div id="collapse<?php echo $day; ?>" class="panel-collapse collapse <?php if($day==strtolower(date('l'))){echo 'in';} ?>" role="tabpanel" aria-labelledby="heading<?php echo $day; ?>">
                <div class="panel-body diet_plan_low">
                  <table class="table table-striped"> 
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Calories</th> 
                        <th>Minuts</th> 
                        <th>Set</th> 
                        <th>Reps</th> 
                        <th>Weight</th>  
                        <th class="text-center"></th>         
                      </tr>
                      </thead>
                      <tbody>
                        <?php 
                        $menuAd = 0;
                        if($this->input->get('user_id')==''&&$this->input->get('plan_id')==''){
                          $menuAd = 1;
                        }else if($this->input->get('plan_id')&&$this->input->get('user_id')&&$this->input->get('plan_id')==$currentPlan->id&&$this->input->get('user_id')==user_id()){
                          $menuAd = 1;
                        }else if($this->input->get('user_id')&&$this->input->get('user_id')==user_id()){
                          $menuAd = 1;
                        }
                        $totalCals = $totalWieghts  = $totalMinuts = $totalMinutsT= $totalSets = $totalRegs =  0;
                        if(!empty($dayDetails)){
                          foreach($dayDetails as $dayDetail){
                             $checkRows = $this->common_model->get_row('workout_exercise_done', array('workout_exercise_id'=>$dayDetail->id, 'exercise_date'=>$dateTimeZone)); 
                            ?>
                            <tr class="text-left <?php if(!empty($checkRows)){ echo 'activePlanRow';} ?>" id="row_<?php echo !empty($dayDetail->id)?$dayDetail->id:''; ?>_<?php echo !empty($day)?$day:''; ?>"> 
                              <td><?php echo !empty($dayDetail->item_title)?ucwords($dayDetail->item_title):''; ?>&nbsp;&nbsp;<i style="cursor: pointer;" data-toggle="modal" data-target="#myModalA" onclick="showExerciseDetails('<?php echo !empty($dayDetail->exercise_id)?$dayDetail->exercise_id:''; ?>','<?php echo !empty($dayDetail->item_title)?ucwords($dayDetail->item_title):''; ?>');" class="fa fa-info-circle" aria-hidden="true"></i></td>
                              <td>
                                <?php 
                                if(!empty($dayDetail->cacalories)&&$dayDetail->measureUnit==1){
                                  $calUnit   = ($dayDetail->cacalories*$dayDetail->minuts)/60; 
                                  echo round($calUnit, 1);
                                  if(!empty($checkRows)){
                                    $totalCals = $calUnit + $totalCals;                     
                                  }
                                }else{ echo '-';} ?>
                                </td>
                              <td>
                                <?php 
                                if(!empty($dayDetail->minuts)&&$dayDetail->measureUnit==1){
                                  echo $dayDetail->minuts;
                                  if(!empty($checkRows)){
                                    $totalMinuts       = $totalMinuts  + $dayDetail->minuts;
                                  }
                                }else{
                                  echo '-';
                                } 
                                ?>
                              </td>
                              <td>
                                <?php 
                                  if(!empty($dayDetail->sets)){
                                    echo $dayDetail->sets;
                                    if(!empty($checkRows)){
                                      $totalSets    = $totalSets + $dayDetail->sets;
                                    }
                                  }else if(!empty($dayDetail->minuts)&&$dayDetail->measureUnit==2){
                                    echo $dayDetail->minuts;
                                    if(!empty($checkRows)){
                                      $totalSets    = $totalSets + $dayDetail->minuts;
                                    }
                                  }else{
                                    echo '-';
                                  }
                                ?>
                              </td>
                              <td>
                                <?php 
                                  if(!empty($dayDetail->reps)){
                                    echo $dayDetail->reps;
                                    if(!empty($checkRows)){
                                      $totalRegs    = $totalRegs + $dayDetail->reps;
                                    }
                                  }else if(!empty($dayDetail->minuts)&&$dayDetail->measureUnit==3){
                                    echo $dayDetail->minuts;
                                    if(!empty($checkRows)){
                                      $totalRegs    = $totalRegs + $dayDetail->minuts;
                                    }
                                  }else{
                                    echo '-';
                                  }
                                ?>
                              </td>
                              <td>
                                <?php 
                                  if(!empty($dayDetail->minuts)&&$dayDetail->measureUnit==4){
                                    echo $dayDetail->minuts;
                                    if(!empty($user->useMetricsSystem) && $user->useMetricsSystem==1){echo ' kg';}else{echo ' lbs';}
                                    if(!empty($checkRows)){
                                      $totalWieghts    = $totalWieghts + $dayDetail->minuts;
                                    }
                                  }else{
                                    echo '-';
                                  }
                                ?>
                              </td>
                              <?php                                
                              //echo $this->input->get('plan_id').'plan_id, id'.$currentPlan->id.', user_id'.$this->input->get('user_id');
                              if(!empty($menuAd)){?>
                                <td class="text-center">
                                  <a href="javascript:void(0);" onclick="checkWorkOutEx('<?php echo $dayDetail->id; ?>','<?php echo !empty($day)?$day:''; ?>', '<?php echo $dateTimeZone; ?>', '<?php echo !empty($currentPlan->id)?$currentPlan->id:''; ?>');" class="btn btn-xs editt" id="btn_<?php echo !empty($dayDetail->id)?$dayDetail->id:''; ?>_<?php echo !empty($day)?$day:''; ?>" title="Set/Unset Exercise">
                                   <?php if(!empty($checkRows)){ echo '<i class="fa fa-times-circle"></i>'; }else{echo '<i class="fa fa-check-circle"></i>';}?> 
                                  </a>&nbsp;&nbsp;&nbsp;<a class="btn btn-xs editt" data-toggle="modal" data-target="#myModalA" href="javascript:void(0);" onclick="editWorkPlanEx('<?php echo $dayDetail->id; ?>');"  title="Edit exercise"><i class="fa fa-pencil"></i></a>
                                </td> 
                              <?php }?>
                            </tr> 
                          <?php                       
                          }
                        }
                        if(!empty($menuAd)){?>
                          <tr>
                            <td colspan="6"></td>
                            <td class="text-center"><a class="btn btn-sm btn-success"  href="<?php echo base_url('user/create_workout_plan?type=addEx&date='.$dateTimeZone.'&day='.$day);if(!empty($currentPlan->goal_id)){echo '&goal_id='.$currentPlan->goal_id; };if(!empty($currentPlan->id)){echo '&workout_id='.$currentPlan->id; }; ?>" title="Add more exercise"><i class="fa fa-plus-circle"></i></a></td>
                          </tr>
                          <?php
                          if($totalMinuts>60){
                            $totalMinuts = $totalMinuts;
                            $totalMinutsT = floor($totalMinuts/60). ' hour ';
                            if($totalMinutsT<10){
                              $totalMinutsT = '0'.$totalMinutsT;
                            }
                            if(($totalMinuts % 60)>0&&($totalMinuts % 60)>9){
                              $totalMinutsT .= ' '.($totalMinuts % 60).' minuts';
                            }elseif(($totalMinuts % 60)>0&&($totalMinuts % 60)<9){
                              $totalMinutsT .= ' 0'.($totalMinuts % 60).' minuts';
                            }
                          }else{
                            $totalMinutsT = $totalMinuts. ' minuts ';
                          }
                          if(!empty($user->useMetricsSystem) && $user->useMetricsSystem==1){$wim = ' kg';}else{$wim = ' lbs';}
                          $totalCals = round($totalCals);
                          echo '<tr>';
                          echo '<th>Total</th>';
                          echo !empty($totalCals)?'<th id="totalCals_'.$dateTimeZone.'">'.$totalCals.' cal</th>':'<td id="totalCals_'.$dateTimeZone.'">-</td>';
                          echo !empty($totalMinuts)?'<th id="totalMinuts_'.$dateTimeZone.'">'.$totalMinutsT.'</th>':'<td id="totalMinuts_'.$dateTimeZone.'">-</td>';               
                          echo !empty($totalSets)?'<th id="totalSets_'.$dateTimeZone.'">'.$totalSets.' sets</th>':'<td id="totalSets_'.$dateTimeZone.'>-</td>';
                          echo !empty($totalRegs)?'<th id="totalRegs_'.$dateTimeZone.'">'.$totalRegs.' reps</th>':'<td id="totalRegs_'.$dateTimeZone.'">-</td>';
                          echo !empty($totalWieghts)?'<th id="totalWieghts_'.$dateTimeZone.'">'.$totalWieghts.$wim.'</th>':'<td id="totalWieghts_'.$dateTimeZone.'">-</td>';
                          ?>
                          <td class="text-center">                          
                          </td>
                          <?php
                          echo '</tr>';
                        }
                        ?> 
                      </tbody>
                    </table>
                </div>
              </div>
            </div>
          <?php         
            $i++;
            $ips++;
          }
        }
      ?>                   
    </div>
  <?php 
  if($this->input->get('user_id')&&$this->input->get('user_id')!=user_id()){?> 
    <div class="followsss text-center">
      <a class="following-settingss" href="javascript:void();" onclick="copy_workout_plan('<?php if(!empty($currentPlan->id)){ echo $currentPlan->id;} ?>','<?php if(!empty($currentPlan->goal_id)){ echo $currentPlan->goal_id;} ?>','<?php if($this->input->get('user_id')){ echo $this->input->get('user_id');} ?>');">
        Copy
      </a>
    </div>
    <div id="copy_diet_plan_res"></div>  
<?php }
  }else{
    echo '<br/><br/><br/><h2 class="text-danger text-center">Workout plan records not found</h2>';
  }
?>