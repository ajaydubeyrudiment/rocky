<style type="text/css"> 
  .followsss {
      margin: 15px 0px;
      border: 1px solid #1bbdb0;
      padding: 10px;
      text-decoration: none;
      cursor: pointer;
  }
</style>
<div class="col-md-9">
  <form id="workoutItems" method="post" onsubmit="return false;">
    <div class="feed_right">
      <div class="goal_setter">
        <h2>Workout Plan - <?php echo !empty($currentPlan->plan_name)?ucfirst($currentPlan->plan_name):''; ?> <span class="pull-right metrics_date">
          <?php
            $wday          = date('w');
            $startTimeZone = strtotime('-'.$wday.' days');
            $endTimeZone   = strtotime('+'.(6-$wday).' days');
            if(date('W')=='52'){
              $week_start = date('M d Y', $startTimeZone);
              $week_end   = date('M d', $endTimeZone).' '.(date('Y')+1);
            }else if(date('m', $startTimeZone)==date('m', $endTimeZone)){
              $week_start = date('M d', $startTimeZone);
              $week_end   = date('d, Y ', $endTimeZone);
            }else{          
              $week_start = date('M d', $startTimeZone);
              $week_end   = date('M d, Y ', $endTimeZone);
            }
            echo $week_start.' - '.$week_end; 
          ?>
          </span>
        </h2>
        <hr/>                              
        <!--==accordian start==-->
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
          <?php 
            $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'); 
            if(!empty($days)){
              $i=1;
              foreach($days as $day){
                if(!empty($currentPlan->id)){
                  $dayDetails = $this->developer_model->getWorkOutPlanDay($day, $currentPlan->id);                
                }
                ?>
                <div class="panel panel-default title_low_carb">
                  <h4 class="panel-title" id="heading<?php echo $day; ?>">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $day; ?>" aria-expanded="true" aria-controls="collapseOne">
                      <?php echo ucfirst($day); ?>
                    </a>
                  </h4>
                  <div id="collapse<?php echo $day; ?>" class="panel-collapse collapse <?php if($day==strtolower(date('l'))){echo 'in';} ?>" role="tabpanel" aria-labelledby="heading<?php echo $day; ?>">
                    <div class="panel-body diet_plan_low">
                      <table class="table table-striped"> 
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Minuts</th> 
                            <th>Set</th> 
                            <th>Reps</th> 
                            <th>Weight</th>         
                          </tr>
                          </thead>
                          <tbody>
                            <?php 
                            if(!empty($dayDetails)){
                              foreach($dayDetails as $dayDetail){?>
                                <tr class="text-left" id="row_<?php echo !empty($dayDetail->id)?$dayDetail->id:''; ?>_<?php echo !empty($day)?$day:''; ?>"> 
                                  <td><?php echo !empty($dayDetail->item_title)?ucwords($dayDetail->item_title):''; ?>&nbsp;&nbsp;<i style="cursor: pointer;" data-toggle="modal" data-target="#myModalA" onclick="showExerciseDetails('<?php echo !empty($dayDetail->exercise_id)?$dayDetail->exercise_id:''; ?>','<?php echo !empty($dayDetail->item_title)?ucwords($dayDetail->item_title):''; ?>');" class="fa fa-info-circle" aria-hidden="true"></i></td>
                                  <td><?php echo !empty($dayDetail->serving)?ucwords($dayDetail->serving):''; ?></td>
                                  <td><?php echo !empty($dayDetail->protein)?ucwords($dayDetail->protein):''; ?></td>
                                  <td><?php echo !empty($dayDetail->carbohydrate)?ucwords($dayDetail->carbohydrate):''; ?></td>
                                  <td><?php echo !empty($dayDetail->fat)?ucwords($dayDetail->fat):''; ?></td>
                                </tr> 
                              <?php }
                            } ?>
                          </tbody>
                        </table>
                    </div>
                  </div>
                </div>
              <?php $i++;
              }
            }
          ?>  
          <div class="followsss text-center">
            <a class="following-settingss" href="javascript:void();" onclick="copy_workout_plan('<?php if($this->input->get('plan_id')){ echo $this->input->get('plan_id');} ?>','<?php if($this->input->get('goal_id')){ echo $this->input->get('goal_id');} ?>','<?php if($this->input->get('user_id')){ echo $this->input->get('user_id');} ?>');">
              Copy
            </a>
            <div id="copy_workout_plan_res"></div>       
          </div>                  
        </div>
      </div>
    </div>
  </form>
</div>
<!--==moreinformation fruits start==-->
<div class="modal fade" id="myModalA" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"></h4>
        </div>
        <div class="modal-body clearfix">
           <div class="col-md-12" id="workoutExerciseDetails"></div>         
        </div>
      </div>
    </div>
</div>
<!--==moreinformationfruits end==-->