<div class="col-md-9">
  <?php msg_alert(); ?>
  <form id="editWorkOutSave" method="post" onsubmit="return false;">
    <input type="hidden" name="id" value="<?php echo $this->input->get('id')?$this->input->get('id'):0;?>">
    <input type="hidden" name="add_new_execirces" id="add_new_execirces">
    <input type="hidden" name="add_delete_execirces" id="add_delete_execirces">
    <div class="feed_right">
      <div class="goal_setter" id="stepFirst">
        <h2>Edit Workout Plan</h2>
        <hr />  
        <div class="row">
          <div class="back_next">                         
            <div id="thirdStepData">             
              <style type="text/css"> .reltype{ position: relative; }</style>
          <?php
          $user             = user_info();
          $planTitle        = !empty($userPlan->plan_name)?$userPlan->plan_name:"";
          $plan_description = !empty($userPlan->plan_description)?$userPlan->plan_description:"";
          $exercises        = $this->common_model->get_result('service_plan_works', array('workout_id'=>$this->input->get('id'), 'status'=>1));
          //echo $this->db->last_query(); 
          //echo '<pre>';print_r($exercises); exit();
          if(!empty($exercises)){
            $rowIds=0;
            foreach($exercises  as $exercis){ $rowIds++;
              $exercisRow = $this->common_model->get_row('service_plan_user_exercise', array('id'=>$exercis->exercise_id));
              if($this->input->post('id')){
                $exRow      = $this->common_model->get_row('service_plan_works', array('exercise_id'=>$exercis, 'user_id'=>user_id(), 'workout_id'=>$this->input->post('id')));
              }
              if($exercisRow->measureUnit==1){
                $exMinuts    = !empty($exercis->minuts)?$exercis->minuts:"30";
              }else if($exercisRow->measureUnit==2){
                $exMinuts    = !empty($exercis->minuts)?$exercis->minuts:"5";
              }else{
                $exMinuts    = !empty($exercis->minuts)?$exercis->minuts:"5";
              }
              $exSets      = !empty($exercis->sets)?$exercis->sets:"5";
              $exReps      = !empty($exercis->reps)?$exercis->reps:"5";    
              $mondayCh    = !empty($exercis->monday)?"checked":"";
              $tuesdayCh   = !empty($exercis->tuesday)?"checked":"";
              $wednesdayCh = !empty($exercis->wednesday)?"checked":"";
              $thursdayCh  = !empty($exercis->thursday)?"checked":"";
              $fridayCh    = !empty($exercis->friday)?"checked":"";
              $saturdayCh  = !empty($exercis->saturday)?"checked":"";
              $sundayCh    = !empty($exercis->sunday)?"checked":"";
              $keyID       = time().rand(1111,9999);
              echo '<div class="create_workout" id="'.$keyID.'"> 
                <table class="table table-bordered">';
              echo '<tr>';
              echo '<th>Name</th>
                  <td>'.ucwords($exercisRow->exercise_title).'</td>
                     ';
                  if(!empty($exercisRow->category_id)&&$exercisRow->category_id==2){?>
                    <th>Sets</th>
                    <td class="reltype">
                      <a href="#" class="dropdown-toggle profile_pic_a" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span id="mealTypeText_set_<?php echo $exercis->id;?>">
                          <?php echo !empty($exSets)?$exSets:''; ?>
                        </span> 
                        <i class="fa fa-caret-down"></i>
                      </a>
                      <ul class="dropdown-menu minus_manus">
                      <?php for($m=1;$m<=10;$m++){?>           
                        <li><a href="javascript:void(0);" onclick="mealType('<?php echo $m; ?>','_set_<?php echo $exercis->id;?>');"><?php echo $m; ?></a></li>         
                      <?php } 
                    echo  '</ul>';
                    ?>          
                    <input type="hidden" name="sets_<?php echo $exercis->id;?>" id="mealType_set_<?php echo $exercis->id;?>" value="<?php echo !empty($exSets)?$exSets:''; ?>">
                    <th>Reps</th>
                    <td class="reltype">
                      <a href="#" class="dropdown-toggle profile_pic_a" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span id="mealTypeText_reps_<?php echo $exercis->id;?>"><?php echo !empty($exReps)?$exReps:''; ?></span> <i class="fa fa-caret-down"></i></a>
                      <ul class="dropdown-menu minus_manus">
                      <?php for($m=1;$m<=20;$m++){?>           
                        <li><a href="javascript:void(0);" onclick="mealType('<?php echo $m; ?>','_reps_<?php echo $exercis->id;?>');"><?php echo $m; ?></a></li>         
                      <?php } 
                      echo  '</ul>';
                      ?>          
                      <input type="hidden" name="reps_<?php echo $exercis->id;?>" id="mealType<?php echo $exercis->id;?>" value="<?php echo !empty($exReps)?$exReps:''; ?>">
                    </td>
                  <?php }else{?>
                    <th colspan="3"><?php if($exercisRow->measureUnit==1){echo 'Minutes';}else if($exercisRow->measureUnit==2){echo 'Sets';}else if($exercisRow->measureUnit==3){echo 'Reps';}else{echo 'Weight';} ?></th>
                    <td class="reltype">
                      <?php 
                     if($exercisRow->measureUnit==1){?>
                        <a href="#" class="dropdown-toggle profile_pic_a" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span id="mealTypeText<?php echo $exercis->id;?>"><?php echo !empty($exMinuts)?$exMinuts:''; ?></span> <i class="fa fa-caret-down"></i></a>
                          <ul class="dropdown-menu minus_manus">
                          <?php for($m=5;$m<=120;$m=$m+5){?>           
                            <li><a href="javascript:void(0);" onclick="mealType('<?php echo $m; ?>','<?php echo $exercis->id;?>');"><?php echo $m; ?></a></li>         
                          <?php } 
                        echo  '</ul>';
                        ?>          
                        <input type="hidden" name="minuts_<?php echo $exercis->id;?>" id="mealType<?php echo $exercis->id;?>" value="<?php echo !empty($exMinuts)?$exMinuts:''; ?>">
                      <?php } else if($exercisRow->measureUnit==2){?>
                        <a href="#" class="dropdown-toggle profile_pic_a" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span id="mealTypeText<?php echo $exercisRow->id;?>"><?php echo !empty($exMinuts)?$exMinuts:''; ?></span> <i class="fa fa-caret-down"></i></a>
                          <ul class="dropdown-menu minus_manus">
                          <?php for($m=1;$m<=10;$m++){?>           
                            <li><a href="javascript:void(0);" onclick="mealType('<?php echo $m; ?>','<?php echo $exercis->id;?>');"><?php echo $m; ?></a></li>         
                          <?php } 
                        echo  '</ul>';
                        ?>          
                        <input type="hidden" name="minuts_<?php echo $exercis->id;?>" id="mealType<?php echo $exercis->id;?>" value="<?php echo !empty($exMinuts)?$exMinuts:''; ?>">

                      <?php }else if($exercisRow->measureUnit==3){?>
                        <a href="#" class="dropdown-toggle profile_pic_a" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span id="mealTypeText<?php echo $exercis->id;?>"><?php echo !empty($exMinuts)?$exMinuts:''; ?></span> <i class="fa fa-caret-down"></i></a>
                          <ul class="dropdown-menu minus_manus">
                          <?php for($m=1;$m<=20;$m++){?>           
                            <li><a href="javascript:void(0);" onclick="mealType('<?php echo $m; ?>','<?php echo $exercis->id;?>');"><?php echo $m; ?></a></li>         
                          <?php } 
                        echo  '</ul>';
                        ?>          
                        <input type="hidden" name="minuts_<?php echo $exercis->id;?>" id="mealType<?php echo $exercis->id;?>" value="<?php echo !empty($exMinuts)?$exMinuts:''; ?>">
                      <?php 
                      }else if($exercisRow->measureUnit==4){?>
                        <input type="text" name="minuts_<?php echo $exercis->id;?>" id="mealType<?php echo $exercis->id;?>" value="<?php echo !empty($exMinuts)?$exMinuts:''; ?>" placeholder="<?php if(!empty($user->useMetricsSystem) && $user->useMetricsSystem==1){echo 'wieght in kg';}else{echo 'wieght in  lbs';} ?>" style="width: 50%; border: 1px solid #1BBDB0; height: 32px;padding-left: 11px;  color: #1BBDB0;  font-size: 14px;}" maxlength="3">
                      <?php } 
                      ?>
                    </td>
                  <?php }?>
                  <td>
                    <a class="btn btn-sm btn-primary" href="javascript:void(0);" onclick="copyExecis('<?php echo $exercis->id; ?>','<?php echo $keyID; ?>');" title="copy exercise"><i class="fa fa-clone"></i></a>
                    <a class="btn btn-sm btn-danger" href="javascript:void(0);" onclick="deleteExecis('<?php echo $exercis->id; ?>','<?php echo $keyID; ?>');" title="Delete Exercise"><i class="fa fa-times"></i></a>
                  </td>
                <?php 
                $wday           = date('w')-1;
                $startTimeZone  = strtotime('-'.$wday.' days');
                $startTimeZone  = strtotime(date('Y-m-d', $startTimeZone));
                $monday_date    = 'monday';
                $tuesday_date   = 'tuesday';
                $wednesday_date = 'wednesday';
                $thursday_date  = 'thursday';
                $friday_date    = 'friday';
                $saturday_date  = 'saturday';
                $sunday_date    = 'sunday';         
                echo '
                    </tr>
                  </tbody>
                </table>         
                <ul class="list-inline text-left listssD ">
                    <li>
                        <div class="checkbox checkbox-success">
                          <input id="checkbox_monday_'.$exercis->id.'" value="1" name="'.$exercis->id.'_'.$monday_date.'" type="checkbox" '.$mondayCh.'>
                          <label for="checkbox_monday_'.$exercis->id.'">Monday</label>
                        </div>
                    </li>
                    <li>
                        <div class="checkbox checkbox-success">
                          <input id="checkbox_tuesday_'.$exercis->id.'" value="1" name="'.$exercis->id.'_'.$tuesday_date.'"  type="checkbox" '.$tuesdayCh.'>
                          <label for="checkbox_tuesday_'.$exercis->id.'"">Tuesday</label>
                        </div>
                    </li>
                   <li>
                        <div class="checkbox checkbox-success">
                          <input id="checkbox_wednesday_'.$exercis->id.'" value="1" name="'.$exercis->id.'_'.$wednesday_date.'" type="checkbox" '.$wednesdayCh.'>
                          <label for="checkbox_wednesday_'.$exercis->id.'">Wednesday</label>
                        </div>
                    </li>
                    <li> 
                        <div class="checkbox checkbox-success">
                          <input id="checkbox_thursday_'.$exercis->id.'" value="1" name="'.$exercis->id.'_'.$thursday_date.'" type="checkbox" '.$thursdayCh.'>
                          <label for="checkbox_thursday_'.$exercis->id.'">Thursday</label>
                        </div>
                    </li>
                    <li>
                        <div class="checkbox checkbox-success">
                          <input id="checkbox_friday_'.$exercis->id.'" value="1" name="'.$exercis->id.'_'.$friday_date.'" type="checkbox" '.$fridayCh.'>
                          <label for="checkbox_friday_'.$exercis->id.'">Friday</label>
                        </div>
                    </li>
                    <li>
                        <div class="checkbox checkbox-success">
                          <input id="checkbox_saturday_'.$exercis->id.'" value="1" name="'.$exercis->id.'_'.$saturday_date.'" type="checkbox" '.$saturdayCh.'>
                          <label for="checkbox_saturday_'.$exercis->id.'">Saturday</label>
                        </div>
                    </li>
                    <li>
                        <div class="checkbox checkbox-success">
                          <input id="checkbox_sunday_'.$exercis->id.'" value="1" name="'.$exercis->id.'_'.$sunday_date.'" type="checkbox" '.$sundayCh.'>
                          <label for="checkbox_sunday_'.$exercis->id.'">Sunday</label>
                        </div>
                    </li>
                </ul>
            </div>';
            }
          }
          $goal_id = 0;
          if(!empty($userPlan->goal_id)){
            $urlLink = base_url('user/create_workout_plan?goal_id='.$userPlan->goal_id);
            if(!empty($userPlan->id)){
              $urlLink .= '&plan_id='.$userPlan->id;
            }
          }
          ?>
            </div>
            <div class="back_next">
              <a href="<?php echo !empty($urlLink)?$urlLink:'#';?>" class="btn btn-md back"><i class="fa fa-plus" aria-hidden="true"></i> Add </a>
              <a href="javascript:void(0);" onclick="editWorkOutSave();" class="btn btn-md next pull-right"><i class="fa fa-check" aria-hidden="true"></i> Save</a>
            </div>
          </div>
        </div> 
      </div>  
      <div class="clearfix"></div> 
      <div id="thirdStep_res"></div>
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