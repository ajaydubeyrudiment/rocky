<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  <div class="row information_fruits">    
    <?php 
    if(!empty($row->exercise_pic)&&file_exists('assets/uploads/plansExercise/thumbnails/'.$row->exercise_pic)){?>
      <img class="img-rounded" src="<?php echo base_url().'assets/uploads/plansExercise/thumbnails/'.$row->exercise_pic ?>" alt="">  
    <?php }
    ?> 
  </div> 
</div>
<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
  <div class="information_fruits_content">
    <p><strong>Description : </strong> <?php echo !empty($row->description)?ucfirst($row->description):''; ?></p>
    <hr />
    <ul>
      <li><strong>Calories :</strong> <?php echo !empty($row->cacalories)?ucfirst($row->cacalories):''; ?></li>
      <li><strong>protein :</strong> <?php echo !empty($row->protein)?ucfirst($row->protein):''; ?></li>
      <li><strong>Fat :</strong> <?php echo !empty($row->fat)?ucfirst($row->fat):''; ?></li>
      <li><strong>Carbohydrate :</strong> <?php echo !empty($row->carbohydrate)?ucfirst($row->carbohydrate):''; ?></li>
      <li><strong>Fiber :</strong> <?php echo !empty($row->fiber)?ucfirst($row->fiber):''; ?></li>
      <li><strong>Suger :</strong> 1<?php echo !empty($row->description)?ucfirst($row->suger):''; ?></li>
    </ul>
    <p><strong>Preparation Difficulty : </strong><?php echo !empty($row->preparation)?ucfirst($row->preparation):''; ?> </p>
    <p><strong>Healthiness : </strong> <?php echo !empty($row->healthiness)?ucfirst($row->healthiness):''; ?></p>
  </div>
</div>