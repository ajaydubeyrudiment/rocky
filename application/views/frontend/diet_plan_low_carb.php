<div class="col-md-9">
  <form id="workoutItems" method="post" onsubmit="return false;">
    <div class="feed_right">
      <div class="goal_setter">
        <h2>Diet Plan - Low Carb<span class="pull-right metrics_date">
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
        </span></h2>
        <hr />                              
        <!--==accordian start==-->
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
          <?php 
            $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'); 
            if(!empty($days)){
              $i=1;
              foreach($days as $day){
                $dayDetails = $this->developer_model->getDietPlanDay($day);                
                ?>
                <div class="panel panel-default title_low_carb">
                  <h4 class="panel-title" id="heading<?php echo $day; ?>">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $day; ?>" aria-expanded="true" aria-controls="collapseOne">
                      <?php echo ucfirst($day); ?>
                    </a>
                  </h4>
                  <div id="collapse<?php echo $day; ?>" class="panel-collapse collapse <?php if($i==1){echo 'in';} ?>" role="tabpanel" aria-labelledby="heading<?php echo $day; ?>">
                    <div class="panel-body diet_plan_low">
                      <table class="table table-striped"> 
                        <thead>
                          <tr>
                            <th>Meal</th>
                            <th>Name</th>
                            <th>Serving</th> 
                            <th>Protein</th> 
                            <th>Carb</th> 
                            <th>Fat</th>         
                          </tr>
                          </thead>
                          <tbody>
                            <?php 
                            if(!empty($dayDetails)){
                              foreach($dayDetails as $dayDetail){?>
                                <tr class="text-left" id="row_<?php echo !empty($dayDetail->id)?$dayDetail->id:''; ?>_<?php echo !empty($day)?$day:''; ?>"> 
                                  <td><?php echo !empty($dayDetail->meal)?ucwords($dayDetail->meal):''; ?></td> 
                                  <td><?php echo !empty($dayDetail->item_title)?ucwords($dayDetail->item_title):''; ?>&nbsp;&nbsp;<i data-toggle="modal" style="cursor: pointer;" onclick="dietPlanDetails('<?php echo !empty($dayDetail->item_id)?ucwords($dayDetail->item_id):''; ?>','<?php echo !empty($dayDetail->item_title)?ucwords($dayDetail->item_title):''; ?>');" data-target="#myModalA" class="fa fa-info-circle" aria-hidden="true"></i>
                                  </td>
                                  <td><?php echo !empty($dayDetail->serving)?ucwords($dayDetail->serving):''; ?></td>
                                  <td><?php echo !empty($dayDetail->protein)?ucwords($dayDetail->protein):''; ?></td>
                                  <td><?php echo !empty($dayDetail->carbohydrate)?ucwords($dayDetail->carbohydrate):''; ?></td>
                                  <td><?php echo !empty($dayDetail->fat)?ucwords($dayDetail->fat):''; ?></td>
                                  <!-- <td width="1%">
                                    <a class="btn btn-xs btn-danger" href="javascript:void(0);" onclick="removePlanDay('<?php echo !empty($dayDetail->id)?$dayDetail->id:''; ?>','<?php echo !empty($day)?$day:''; ?>');">
                                      <i class="fa fa-trash"></i>
                                    </a>
                                  </td> -->
                                  <td width="1%">
                                    <!-- <a class="btn btn-xs editt" href="javascript:void(0);" onclick="editPlanDay('<?php echo !empty($dayDetail->category_id)?$dayDetail->category_id:''; ?>','<?php echo !empty($day)?$day:''; ?>');">
                                      <i class="fa fa-pencil"></i>
                                    </a> -->
                                    <a class="btn btn-xs editt" href="javascript:void(0);">
                                      <i class="fa fa-pencil"></i>
                                    </a>
                                  </td>                                  
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
        <h4 class="modal-title" id="myModalLabel">Apple</h4>
      </div>
      <div class="modal-body clearfix">
         <div class="col-md-12">
            <div class="row" id="dietPlanDetails"></div>
         </div>         
      </div>
    </div>
  </div>
</div>
<!--==moreinformationfruits end==-->