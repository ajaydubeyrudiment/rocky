<div class="row">
  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
    <div class="row information_fruits">
      <img src="<?php if(!empty($row->exercise_pic)&&file_exists('assets/uploads/plansExercise/'.$row->exercise_pic)){echo base_url().'assets/uploads/plansExercise/'.$row->exercise_pic;}else if(!empty($row->exercise_pic)&&file_exists('assets/uploads/plansExercise/thumbnails/'.$row->exercise_pic)){echo base_url().'assets/uploads/plansExercise/thumbnails/'.$row->exercise_pic;} ?>" class="img-rounded" alt="<?php echo !empty($row->exercise_title)?$row->exercise_title:''; ?>">
   </div> 
  </div>
  <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
    <div class="information_fruits_content">
      <p><strong>Instruction : </strong><?php echo !empty($row->exercise_instruction)?$row->exercise_instruction:''; ?></p>
      <p><strong>Details : </strong><?php echo !empty($row->exercise_details)?$row->exercise_details:''; ?></p>
      <p><strong>Calories : </strong><?php echo !empty($row->cacalories)?$row->cacalories:''; ?></p>
      <p><strong>Measure Unit : </strong><?php if($row->measureUnit==1){echo 'Minutes';}else if($row->measureUnit==2){echo 'Sets';}else if($row->measureUnit==3){echo 'Reps';}else{echo 'Weight';} ?></p>
    </div>
  </div>
</div>