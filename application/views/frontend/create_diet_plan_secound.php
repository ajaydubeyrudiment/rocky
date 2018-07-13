<?php
$userItems = array();
if(!empty($dietPlanEx)){
   $userItems = explode(',', $dietPlanEx);
} 
if(!empty($rows)){
  foreach($rows as $row){?>
    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12 select_nutrition">
      <div class="media nutrition">      
          <div class="media-body">
            <i data-toggle="modal" onclick="dietPlanDetails('<?php echo !empty($row->id)?ucwords($row->id):''; ?>','<?php echo !empty($row->item_title)?ucwords($row->item_title):''; ?>');" data-target="#myModalA" class="fa fa-info-circle" aria-hidden="true"></i>
            <input class="planss" id="dietItem_<?php echo !empty($row->id)?ucwords($row->id):''; ?>" type="checkbox" name="item[]" value="<?php echo !empty($row->id)?ucwords($row->id):''; ?>" <?php if(in_array($row->id, $userItems)){echo 'checked';} ?>>
            <label class="plans" for="dietItem_<?php echo !empty($row->id)?ucwords($row->id):''; ?>"><?php echo !empty($row->item_title)?ucwords($row->item_title):''; ?>  
              <div class="foody">
                <?php 
                if(!empty($row->exercise_pic)&&file_exists('assets/uploads/plansExercise/thumbnails/'.$row->exercise_pic)){?>
                  <img class="img-rounded" src="<?php echo base_url().'assets/uploads/plansExercise/thumbnails/'.$row->exercise_pic ?>" alt="">  
                <?php }
                ?> 
              </div>
              <span><div style="width: 20%"><?php echo !empty($row->cacalories)?ucwords($row->cacalories):''; ?></div><strong>calories</strong> </span>
              <span><div style="width: 20%"><?php echo !empty($row->protein)?ucwords($row->protein):''; ?></div><strong>protein</strong> </span>
              <span><div style="width: 20%"><?php echo !empty($row->carbohydrate)?ucwords($row->carbohydrate):''; ?></div><strong>carbohydrate</strong> </span>
            </label>
          </div>
      </div>
    </div>
  <?php }
}else{
  echo '<h3 class="text-danger text-center">No records found</h3>';
}?>
