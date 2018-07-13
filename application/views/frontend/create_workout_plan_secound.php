 <?php 
 $userExs = array();
 if(!empty($user_al_exercise)){
  $userExs = explode(',', $user_al_exercise);
  //echo $user_al_exercise.' user_al_exercise';
 }
//echo '<pre>'; print_r($exercises); 
if(!empty($exercises)){
  foreach($exercises as $exercise){?>
    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12 select_nutrition">
      <div class="media nutrition">                
        <div class="media-body">
          <i data-toggle="modal" data-target="#myModalA" onclick="showExerciseDetails('<?php echo !empty($exercise->id)?$exercise->id:''; ?>','<?php echo !empty($exercise->exercise_title)?ucwords($exercise->exercise_title):''; ?>');" class="fa fa-info-circle" aria-hidden="true"></i>
          <input class="planss" id="exercise<?php echo !empty($exercise->id)?$exercise->id:''; ?>" type="checkbox" name="exercise[]" value="<?php echo !empty($exercise->id)?$exercise->id:''; ?>" <?php if(in_array($exercise->id, $userExs)){echo 'checked';} ?>>
          <label class="plans" for="exercise<?php echo !empty($exercise->id)?$exercise->id:''; ?>">
            <?php 
            echo !empty($exercise->exercise_title)?$exercise->exercise_title:''; 
            if(!empty($exercise->exercise_pic)&&file_exists('assets/uploads/plansExercise/'.$exercise->exercise_pic)){
             echo '<img class="img-rounded" src="'.base_url().'assets/uploads/plansExercise/'.$exercise->exercise_pic.'" alt="" style="max-width:50px;">';
            }else if(!empty($exercise->exercise_pic)&&file_exists('assets/uploads/plansExercise/thumbnails/'.$exercise->exercise_pic)){
             echo '<img class="img-rounded" src="'.base_url().'assets/uploads/plansExercise/thumbnails/'.$exercise->exercise_pic.'" alt="" style="max-width:50px;">';
            }
            ?>  
            <span>
              <?php echo !empty($exercise->cacalories)?$exercise->cacalories:''; ?> 
              <strong>
                calories burned per hour
              </strong> 
            </span>
          </label>
        </div>
      </div>
    </div>
  <?php 
  } 
}?> 