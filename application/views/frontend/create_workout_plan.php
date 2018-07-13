<div class="col-md-9">
  <?php msg_alert(); ?>
  <form id="workoutItems" method="post" onsubmit="return false;">
    <div class="feed_right">
      <div class="goal_setter" id="stepFirst">
        <h2><?php echo $this->input->get('plan_id')?'Add exercise type in current workout plan':'Workout Plan - Select Exercise Type';?></h2>
        <hr />  
        <input type="hidden" name="goal_id" value="<?php echo $this->input->get('goal_id')?$this->input->get('goal_id'):0;?>">
        <input type="hidden" name="workout_id" value="<?php echo $this->input->get('workout_id')?$this->input->get('workout_id'):0;?>">
        <input type="hidden" name="id" value="<?php echo $this->input->get('id')?$this->input->get('id'):0;?>">
        <input type="hidden" name="day" value="<?php echo $this->input->get('day')?$this->input->get('day'):0;?>">
        <input type="hidden" name="date" value="<?php echo $this->input->get('date')?$this->input->get('date'):0;?>">
        <input type="hidden" name="user_exercise" value="<?php echo !empty($userPlan->exercise)?$userPlan->exercise:0;?>">
        <input type="hidden" name="plan_id" value="<?php echo $this->input->get('plan_id')?$this->input->get('plan_id'):0;?>">
        <div class="row">         
          <?php 
          $userItems = $userExs = array();
          if(!empty($userPlan->items)){
             $userItems = explode(',', $userPlan->items);
          }         
          if(!empty($userPlan->exercise)){
             $userExs = explode(',', $userPlan->exercise);
          }
          if(!empty($rows)){
            foreach($rows as $row){?>
              <div class="workout_set">
                <h4 class="c15"><?php echo !empty($row['categoryTitle'])?ucwords($row['categoryTitle']):''; ?></h4>
              </div>
              <div class="c150 clearfix" style="display: block;"> 
                <?php 
                if(!empty($row['items'])){
                    foreach($row['items'] as $item){?>
                      <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12 select_nutrition">
                        <div class="media nutrition">
                          <div class="media-body">                        
                            <input class="planss" id="lists<?php echo !empty($item->serviceItemID)?$item->serviceItemID:'' ?>" type="checkbox" name="workoutitems[]" value="<?php echo !empty($item->serviceItemID)?$item->serviceItemID:'' ?>" <?php if(in_array($item->serviceItemID, $userItems)){echo 'checked';} ?>>
                            <label class="plans" for="lists<?php echo !empty($item->serviceItemID)?$item->serviceItemID:'' ?>">
                             <?php 
                              if(!empty($item->serviceItemPic)){?>
                                      <div class="diat-pic">
                                 <img class="img-rounded" src="<?php echo $item->serviceItemPic; ?>" alt="">  
                                 </div>
                              <?php }
                              ?>  
                     		       <div class="diat-title">
                                  <?php 
                                    echo !empty($item->serviceItemTitle)?ucwords($item->serviceItemTitle):''; 
    							                ?>
                              </div>                                                
                            </label>
                          </div>
                        </div>
                    </div>
                    <?php }
                } ?> 
              </div>
             <?php
            }
          }
          ?> 
          <div class="back_next">
            <a href="<?php echo base_url('user/create_diet_plan?goal_id='.$this->input->get('goal_id')); ?>" class="btn btn-md back">Skip <i class="fa fa-chevron-right" aria-hidden="true"></i></a> 
            <a href="javascript:void(0);" onclick="workOutStepFirstRight();" class="btn btn-md next pull-right">Next <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
          </div>
        </div> 
      </div>     
      <div class="clearfix"></div> 
      <div id="nextWorkOut_res"></div> 
      <div class="goal_setter" id="stepSecound">
        <h2><?php echo $this->input->get('plan_id')?'Add exercise in current workout plan':'Workout Plan - Select Exercise';?></h2>
        <hr />
        <div class="col-lg-12 col-sm-12 col-lg-12 col-md-12">
            <div class="row" id="workout_execies"></div>  
        </div> 
        <div class="back_next">
          <a href="javascript:void(0);" onclick="workOutSecoundleft();" class="btn btn-md back"><i class="fa fa-chevron-left" aria-hidden="true"></i> Back </a>
          <?php 
          if($this->input->get('type')=='addEx'){?>
            <a href="javascript:void(0);" onclick="saveWorkOutSecond();" class="btn btn-md next pull-right">Save <i class="fa fa-check" aria-hidden="true"></i></a>
          <?php }else{?>
            <a href="javascript:void(0);" onclick="workOutSecoundRight();" class="btn btn-md next pull-right">Next <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
         <?php } ?>
        </div>
      </div>
      <div class="clearfix"></div> 
      <div id="secoundStep_res"></div>
      <div class="goal_setter" id="stepThird">
        <h2><?php echo $this->input->get('plan_id')?'Add exercise in current workout plan':'Create Workout plan';?></h2>
        <hr />                             
        <div id="thirdStepData"></div>
        <div class="back_next">
          <a href="javascript:void(0);" onclick="workOutThirdLeft();" class="btn btn-md back"><i class="fa fa-chevron-left" aria-hidden="true"></i> Back </a>
          <a href="javascript:void(0);" onclick="workOutSave();" class="btn btn-md next pull-right">Save <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
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