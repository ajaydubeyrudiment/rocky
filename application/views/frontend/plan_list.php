<?php
      if(!empty($rows)){
        $ids = 1;
        foreach($rows as $row){?>
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" id="planIDs_<?php echo !empty($row->id)?$row->id:''; ?>">
              <div class="previous_diet">
                <a href="<?php if($row->planType==2){ echo base_url('user/workout_plan?plan_id='.$row->id.'&user_id='.$row->user_id);}else{ echo base_url('user/diet_plan?plan_id='.$row->id.'&user_id='.$row->user_id);} ?>" class="linksS">
                  <h4><?php echo !empty($row->plan_name)?ucwords($row->plan_name):''; ?></h4>
                  <?php if($ids==1){ echo '<p><strong>Active From</strong> '.date('d M Y H:i A', strtotime($row->created_date)).' <strong>Active To</strong> Now</p>';}else{
                    echo '<p><strong>Active From</strong> '.date('d M Y H:i A', strtotime($row->created_date)).' <strong>Active To</strong> '.date('d M Y H:i A', strtotime($lastDate)).'</p>';
                  }
                  $lastDate = $row->created_date;
                  ?>                
                  <p><strong>Description : </strong>
                    <?php echo !empty($row->plan_description)?ucfirst($row->plan_description):''; ?></p>
                </a>
                <?php 
                if($this->input->get('user_id')==''||$this->input->get('user_id')==user_id()){?>
                  <ul class="list-inline">
                    <li>
                      <label class="plans" for="planids_<?php echo !empty($row->id)?ucwords($row->id):''; ?>">
                        <?php 
                        if(!empty($row->activatePlan)){?>
                          <a href="javascript:void(0);" onclick="activeDeactivePlan('<?php echo !empty($row->id)?ucwords($row->id):''; ?>','1', 'deactivate');">Activate</a>
                        <?php }else{?>
                          <a href="javascript:void(0);" onclick="activeDeactivePlan('<?php echo !empty($row->id)?ucwords($row->id):''; ?>','2', 'activate');">Deactivate</a>
                       <?php }?>
                        </label></li>
                    <li><a href="javascript:void(0);" onclick="editPlanDay('<?php echo !empty($row->id)?ucwords($row->id):''; ?>','<?php echo !empty($row->planType)?$row->planType:''; ?>');"><i class="fa fa-pencil"></i></a></li>
                    <li><a href="javascript:void(0);" onclick="deleteUserPlan('<?php echo !empty($row->id)?ucwords($row->id):''; ?>');"><i class="fa fa-trash"></i></a></li>
                  </ul>
                <?php }?>
            </div>
          </div> 
       <?php $ids++; 
        }
      }else{
        echo '<br/><br/><br/><h2 class="text-danger text-center"> plan records not found</h2>';
      }
      ?>