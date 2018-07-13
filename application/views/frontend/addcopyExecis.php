<?php
if(!empty($exercises)){
  foreach($exercises  as $exercis){
    $exercisRow = $this->common_model->get_row('service_plan_user_exercise', array('id'=>$exercis));
    if($this->input->post('id')){
      $exRow      = $this->common_model->get_row('service_plan_works', array('exercise_id'=>$exercis, 'user_id'=>user_id(), 'workout_id'=>$this->input->post('id')));
    }    
    $exercisRow->id = $exercisRow->id.'_'.$keyID;
    if($exercisRow->measureUnit==1){
      $exMinuts    = !empty($exRow->minuts)?$exRow->minuts:"30";
    }else if($exercisRow->measureUnit==2){
      $exMinuts    = !empty($exRow->minuts)?$exRow->minuts:"5";
    }else{
      $exMinuts    = !empty($exRow->minuts)?$exRow->minuts:"5";
    }
    $exSets      = !empty($exRow->sets)?$exRow->sets:"5";
    $exReps      = !empty($exRow->reps)?$exRow->reps:"5";    
    $mondayCh    = !empty($exRow->monday)?"checked":"";
    $tuesdayCh   = !empty($exRow->tuesday)?"checked":"";
    $wednesdayCh = !empty($exRow->wednesday)?"checked":"";
    $thursdayCh  = !empty($exRow->thursday)?"checked":"";
    $fridayCh    = !empty($exRow->friday)?"checked":"";
    $saturdayCh  = !empty($exRow->saturday)?"checked":"";
    $sundayCh    = !empty($exRow->sunday)?"checked":"";    
    echo '<div class="create_workout" id="'.$keyID.'">  
      <table class="table table-bordered">
        <tr>
        <th>Name</th>
        <td>'.ucwords($exercisRow->exercise_title).'</td>
           ';
        if(!empty($exercisRow->category_id)&&$exercisRow->category_id==2){?>
          <th>Sets</th>
          <td class="reltype">
            <a href="#" class="dropdown-toggle profile_pic_a" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
              <span id="mealTypeText_set_<?php echo $exercisRow->id;?>">
                <?php echo !empty($exSets)?$exSets:''; ?>
              </span> 
              <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu minus_manus">
            <?php for($m=1;$m<=10;$m++){?>           
              <li><a href="javascript:void(0);" onclick="mealType('<?php echo $m; ?>','_set_<?php echo $exercisRow->id;?>');"><?php echo $m; ?></a></li>         
            <?php } 
          echo  '</ul>';
          ?>          
          <input type="hidden" name="sets_<?php echo $exercisRow->id;?>" id="mealType_set_<?php echo $exercisRow->id;?>" value="<?php echo !empty($exSets)?$exSets:''; ?>">
          <th>Reps</th>
          <td class="reltype">
            <a href="#" class="dropdown-toggle profile_pic_a" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span id="mealTypeText_reps_<?php echo $exercisRow->id;?>"><?php echo !empty($exReps)?$exReps:''; ?></span> <i class="fa fa-caret-down"></i></a>
            <ul class="dropdown-menu minus_manus">
            <?php for($m=1;$m<=20;$m++){?>           
              <li><a href="javascript:void(0);" onclick="mealType('<?php echo $m; ?>','_reps_<?php echo $exercisRow->id;?>');"><?php echo $m; ?></a></li>         
            <?php } 
            echo  '</ul>';
            ?>          
            <input type="hidden" name="reps_<?php echo $exercisRow->id;?>" id="mealType<?php echo $exercisRow->id;?>" value="<?php echo !empty($exReps)?$exReps:''; ?>">
          </td>
        <?php }else{?>
          <th colspan="3"><?php if($exercisRow->measureUnit==1){echo 'Minutes';}else if($exercisRow->measureUnit==2){echo 'Sets';}else if($exercisRow->measureUnit==3){echo 'Reps';}else{echo 'Weight';} ?></th>
          <td class="reltype">
            <?php 
           if($exercisRow->measureUnit==1){?>
              <a href="#" class="dropdown-toggle profile_pic_a" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span id="mealTypeText<?php echo $exercisRow->id;?>"><?php echo !empty($exMinuts)?$exMinuts:''; ?></span> <i class="fa fa-caret-down"></i></a>
                <ul class="dropdown-menu minus_manus">
                <?php for($m=5;$m<=120;$m=$m+5){?>           
                  <li><a href="javascript:void(0);" onclick="mealType('<?php echo $m; ?>','<?php echo $exercisRow->id;?>');"><?php echo $m; ?></a></li>         
                <?php } 
              echo  '</ul>';
              ?>          
              <input type="hidden" name="minuts_<?php echo $exercisRow->id;?>" id="mealType<?php echo $exercisRow->id;?>" value="<?php echo !empty($exMinuts)?$exMinuts:''; ?>">
            <?php } else if($exercisRow->measureUnit==2){?>
              <a href="#" class="dropdown-toggle profile_pic_a" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span id="mealTypeText<?php echo $exercisRow->id;?>"><?php echo !empty($exMinuts)?$exMinuts:''; ?></span> <i class="fa fa-caret-down"></i></a>
                <ul class="dropdown-menu minus_manus">
                <?php for($m=1;$m<=10;$m++){?>           
                  <li><a href="javascript:void(0);" onclick="mealType('<?php echo $m; ?>','<?php echo $exercisRow->id;?>');"><?php echo $m; ?></a></li>         
                <?php } 
              echo  '</ul>';
              ?>          
              <input type="hidden" name="minuts_<?php echo $exercisRow->id;?>" id="mealType<?php echo $exercisRow->id;?>" value="<?php echo !empty($exMinuts)?$exMinuts:''; ?>">

            <?php }else if($exercisRow->measureUnit==3){?>
              <a href="#" class="dropdown-toggle profile_pic_a" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span id="mealTypeText<?php echo $exercisRow->id;?>"><?php echo !empty($exMinuts)?$exMinuts:''; ?></span> <i class="fa fa-caret-down"></i></a>
                <ul class="dropdown-menu minus_manus">
                <?php for($m=1;$m<=20;$m++){?>           
                  <li><a href="javascript:void(0);" onclick="mealType('<?php echo $m; ?>','<?php echo $exercisRow->id;?>');"><?php echo $m; ?></a></li>         
                <?php } 
              echo  '</ul>';
              ?>          
              <input type="hidden" name="minuts_<?php echo $exercisRow->id;?>" id="mealType<?php echo $exercisRow->id;?>" value="<?php echo !empty($exMinuts)?$exMinuts:''; ?>">
            <?php 
            }else if($exercisRow->measureUnit==4){?>
              <input type="text" name="minuts_<?php echo $exercisRow->id;?>" id="mealType<?php echo $exercisRow->id;?>" value="<?php echo !empty($exMinuts)?$exMinuts:''; ?>" placeholder="<?php if(!empty($user->useMetricsSystem) && $user->useMetricsSystem==1){echo 'wieght in kg';}else{echo 'wieght in  lbs';} ?>" style="width: 50%; border: 1px solid #1BBDB0; height: 32px;padding-left: 11px;  color: #1BBDB0;  font-size: 14px;}" maxlength="3">
            <?php } 
            ?>
          </td>
        <?php }?>
      <?php 
      $wday           = date('w')-1;
      $startTimeZone  = strtotime('-'.$wday.' days');
      $startTimeZone  = strtotime(date('Y-m-d', $startTimeZone));
      $monday_date    = $startTimeZone;
      $tuesday_date   = $startTimeZone+(86400*1);
      $wednesday_date = $startTimeZone+(86400*2);
      $thursday_date  = $startTimeZone+(86400*3);
      $friday_date    = $startTimeZone+(86400*4);
      $saturday_date  = $startTimeZone+(86400*5);
      $sunday_date    = $startTimeZone+(86400*6);  
      ?>
      <td>
        <a class="btn btn-sm btn-primary" href="javascript:void(0);" onclick="addCopyExecis('<?php echo $exercisRow->id; ?>','<?php echo $keyID; ?>');" title="copy exercise"><i class="fa fa-clone"></i></a>
        <a class="btn btn-sm btn-danger" href="javascript:void(0);" onclick="addDeleteExecis('<?php echo $exercisRow->id; ?>','<?php echo $keyID; ?>');" title="Delete Exercise"><i class="fa fa-times"></i></a>
      </td>
      <?php       
      echo '
          </tr>
        </tbody>
      </table>         
      <ul class="list-inline text-left listssD ">
          <li>
              <div class="checkbox checkbox-success">
                <input id="checkbox_monday_'.$exercisRow->id.'" value="1" name="'.$exercisRow->id.'_'.$monday_date.'" type="checkbox" '.$mondayCh.'>
                <label for="checkbox_monday_'.$exercisRow->id.'">Monday</label>
              </div>
          </li>
          <li>
              <div class="checkbox checkbox-success">
                <input id="checkbox_tuesday_'.$exercisRow->id.'" value="1" name="'.$exercisRow->id.'_'.$tuesday_date.'"  type="checkbox" '.$tuesdayCh.'>
                <label for="checkbox_tuesday_'.$exercisRow->id.'"">Tuesday</label>
              </div>
          </li>
         <li>
              <div class="checkbox checkbox-success">
                <input id="checkbox_wednesday_'.$exercisRow->id.'" value="1" name="'.$exercisRow->id.'_'.$wednesday_date.'" type="checkbox" '.$wednesdayCh.'>
                <label for="checkbox_wednesday_'.$exercisRow->id.'">Wednesday</label>
              </div>
          </li>
          <li> 
              <div class="checkbox checkbox-success">
                <input id="checkbox_thursday_'.$exercisRow->id.'" value="1" name="'.$exercisRow->id.'_'.$thursday_date.'" type="checkbox" '.$thursdayCh.'>
                <label for="checkbox_thursday_'.$exercisRow->id.'">Thursday</label>
              </div>
          </li>
          <li>
              <div class="checkbox checkbox-success">
                <input id="checkbox_friday_'.$exercisRow->id.'" value="1" name="'.$exercisRow->id.'_'.$friday_date.'" type="checkbox" '.$fridayCh.'>
                <label for="checkbox_friday_'.$exercisRow->id.'">Friday</label>
              </div>
          </li>
          <li>
              <div class="checkbox checkbox-success">
                <input id="checkbox_saturday_'.$exercisRow->id.'" value="1" name="'.$exercisRow->id.'_'.$saturday_date.'" type="checkbox" '.$saturdayCh.'>
                <label for="checkbox_saturday_'.$exercisRow->id.'">Saturday</label>
              </div>
          </li>
          <li>
              <div class="checkbox checkbox-success">
                <input id="checkbox_sunday_'.$exercisRow->id.'" value="1" name="'.$exercisRow->id.'_'.$sunday_date.'" type="checkbox" '.$sundayCh.'>
                <label for="checkbox_sunday_'.$exercisRow->id.'">Sunday</label>
              </div>
          </li>
      </ul>
  </div>';
  }
}
?>