<div class="col-md-9">
  <?php msg_alert(); ?>
  <!-- <form id="editDietSave" method="post" onsubmit="return false;"> -->
  <form id="editDietSave" method="post" action="<?php echo base_url('user/editDietPlan'); ?>">
    <input type="hidden" name="id" value="<?php echo $this->input->get('id')?$this->input->get('id'):0;?>">
    <input type="hidden" name="add_new_execirces" id="add_new_execirces">
    <input type="hidden" name="add_delete_execirces" id="add_delete_execirces">
    <div class="feed_right">
      <div class="goal_setter" id="stepFirst">
        <h2>Edit Diat Plan</h2>
        <hr />  
        <div class="row">
          <div class="back_next">                         
            <div id="thirdStepData">             
              <style type="text/css"> .reltype{ position: relative; }</style>
                  <div class="create_workout">  
                    <?php
                      $wday           = date('w')-1;
                      $startTimeZone  = strtotime('-'.$wday.' days');
                      $startTimeZone  = strtotime(date('Y-m-d', $startTimeZone));
                      /*$monday_date    = $startTimeZone;
                      $tuesday_date   = $startTimeZone+(86400*1);
                      $wednesday_date = $startTimeZone+(86400*2);
                      $thursday_date  = $startTimeZone+(86400*3);
                      $friday_date    = $startTimeZone+(86400*4);
                      $saturday_date  = $startTimeZone+(86400*5);
                      $sunday_date    = $startTimeZone+(86400*6);*/  
                      $monday_date    = 'monday';
                      $tuesday_date   = 'tuesday';
                      $wednesday_date = 'wednesday';
                      $thursday_date  = 'thursday';
                      $friday_date    = 'friday';
                      $saturday_date  = 'saturday';
                      $sunday_date    = 'sunday'; 
                      $thirdStep      = '';                      
                      if(!empty($rows)){
                        $ids=0;
                        foreach($rows  as $row){
                          $ids++;                          
                          $keyID    = time().rand(1111,9999);
                          $keys[]   = $keyID;      
                          //$row->id  = $row->id.'_'.$keyID;
                        ?> 
                          <div class="create_workout create_diet table-resposnsive"  id="<?php echo $keyID;?>">
                            <table class="table table-bordered">   
                              <tr>
                                <th>Meal</th>
                                <td class="reltype">
                                  <a href="#" class="dropdown-toggle profile_pic_a" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span id="mealTypeText<?php echo $row->id;?>"><?php echo !empty($row->meal)?ucwords($row->meal):'Breakfast'; ?></span><span class="caret"></span></a>
                                  <ul class="dropdown-menu">
                                    <li><a href="javascript:void(0);" onclick="mealType('Breakfast','<?php echo $row->id;?>');">Breakfast</a></li>
                                    <li><a href="javascript:void(0);" onclick="mealType('Lunch','<?php echo $row->id;?>');">Lunch</a></li>
                                    <li><a href="javascript:void(0);" onclick="mealType('Dinner','<?php echo $row->id;?>');">Dinner</a></li>
                                    <li><a href="javascript:void(0);" onclick="mealType('Snack','<?php echo $row->id;?>');">Snack</a></li>
                                    <li><a href="javascript:void(0);" onclick="mealType('Other','<?php echo $row->id;?>');">Other</a></li>
                                  </ul>
                                  <input type="hidden" name="mealType<?php echo $row->id;?>" id="mealType<?php echo $row->id;?>" value="<?php echo !empty($row->meal)?ucwords($row->meal):'breakfast'; ?>">
                                </td>
                                <th>Food</th>
                                <td><?php echo ucwords($row->item_title);?></td>
                                <th>Serving</th>
                                <td class="reltype">
                                  <a href="#" class="dropdown-toggle profile_pic_a" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <span id="servingTypeText<?php echo $row->id;?>"><?php echo !empty($row->serving)?ucwords($row->serving):'1'; ?></span><span class="caret"></span>
                                    <i class="fa fa-caret-down"></i>
                                  </a>
                                  <ul class="dropdown-menu">
                                    <li><a href="javascript:void(0);" onclick="servingType('0.25','<?php echo $row->id;?>');">0.25</a></li>
                                    <li><a href="javascript:void(0);" onclick="servingType('0.50','<?php echo $row->id;?>');">0.50</a></li>
                                    <li><a href="javascript:void(0);" onclick="servingType('0.75','<?php echo $row->id;?>');">0.75</a></li>
                                    <li><a href="javascript:void(0);" onclick="servingType('1','<?php echo $row->id;?>');">1</a></li>
                                    <li><a href="javascript:void(0);" onclick="servingType('1.25','<?php echo $row->id;?>');">1.25</a></li>
                                    <li><a href="javascript:void(0);" onclick="servingType('1.5','<?php echo $row->id;?>');">1.5</a></li>
                                    <li><a href="javascript:void(0);" onclick="servingType('1.75','<?php echo $row->id;?>');">1.75</a></li>
                                    <li><a href="javascript:void(0);" onclick="servingType('2','<?php echo $row->id;?>');">2</a></li>
                                  </ul>
                                  <input type="hidden" name="servingType<?php echo $row->id;?>" id="servingType<?php echo $row->id;?>" value="<?php echo !empty($row->serving)?ucwords($row->serving):'1'; ?>">
                                </td>
                                <td>
                                  <a class="btn btn-sm btn-default" href="javascript:void(0);" onclick="copyFood('<?php echo $row->id; ?>','<?php echo $keyID; ?>');" title="Copy Food"><i class="fa fa-clone"></i></a>
                                  <a class="btn btn-sm btn-danger" href="javascript:void(0);" onclick="deleteFood('<?php echo $row->id; ?>','<?php echo $keyID; ?>');" title="Delete Food"><i class="fa fa-times"></i></a>
                                </td>
                              </tr> 
                              <tr>   
                                <td colspan="7">                   
                                  <ul class="list-inline text-left listssD">
                                    <li>
                                      <div class="checkbox checkbox-success">
                                        <input id="checkbox_monday_<?php echo $row->id;?>" value="1" <?php  echo !empty($row->monday)?'checked':''; ?> name="<?php echo $row->id.'_'.$monday_date;?>" type="checkbox">
                                        <label for="checkbox_monday_<?php echo $row->id;?>">Monday</label>
                                      </div>
                                    </li>
                                    <li>
                                      <div class="checkbox checkbox-success">
                                        <input id="checkbox_tuesday_<?php echo $row->id;?>" value="1" <?php  echo !empty($row->tuesday)?'checked':''; ?> name="<?php echo $row->id.'_'.$tuesday_date;?>" class="hidden"  type="checkbox">
                                        <label for="checkbox_tuesday_<?php echo $row->id;?>"">Tuesday</label>
                                      </div>
                                    </li>
                                   <li>
                                      <div class="checkbox checkbox-success">
                                        <input id="checkbox_wednesday_<?php echo $row->id;?>" <?php  echo !empty($row->wednesday)?'checked':''; ?> value="1" name="<?php echo $row->id.'_'.$wednesday_date;?>" type="checkbox">
                                        <label for="checkbox_wednesday_<?php echo $row->id;?>">Wednesday</label>
                                      </div>
                                    </li>
                                    <li>
                                      <div class="checkbox checkbox-success">
                                        <input id="checkbox_thursday_<?php echo $row->id;?>" <?php  echo !empty($row->thursday)?'checked':''; ?> value="1" name="<?php echo $row->id.'_'.$thursday_date;?>" type="checkbox">
                                        <label for="checkbox_thursday_<?php echo $row->id;?>">Thursday</label>
                                      </div>
                                    </li>
                                    <li>
                                      <div class="checkbox checkbox-success">
                                        <input id="checkbox_friday_<?php echo $row->id;?>" <?php  echo !empty($row->friday)?'checked':''; ?> value="1" name="<?php echo $row->id.'_'.$friday_date;?>" type="checkbox">
                                        <label for="checkbox_friday_<?php echo $row->id;?>">Friday</label>
                                      </div>
                                    </li>
                                    <li>
                                      <div class="checkbox checkbox-success">
                                        <input id="checkbox_saturday_<?php echo $row->id;?>" <?php  echo !empty($row->saturday)?'checked':''; ?> value="1" name="<?php echo $row->id.'_'.$saturday_date;?>" type="checkbox">
                                        <label for="checkbox_saturday_<?php echo $row->id;?>">Saturday</label>
                                      </div>
                                    </li>
                                    <li>
                                      <div class="checkbox checkbox-success">
                                        <input id="checkbox_sunday_<?php echo $row->id;?>" <?php  echo !empty($row->sunday)?'checked':''; ?> value="1" name="<?php echo $row->id.'_'.$sunday_date;?>" type="checkbox">
                                        <label for="checkbox_sunday_<?php echo $row->id;?>">Sunday</label>
                                      </div>
                                    </li>
                                  </ul>
                                </td>
                              </tr>   
                            </table>   
                          </div>
                        <?php
                        }
                      }
                    ?>
                    </tbody>
                  </table>      
                </div>
            </div>
            <?php
            $goal_id = 0;
            if(!empty($userPlan->goal_id)){
              $urlLink = base_url('user/create_diet_plan?goal_id='.$userPlan->goal_id);
              if(!empty($userPlan->id)){
                $urlLink .= '&plan_id='.$userPlan->id;
              }
            }
            ?>
            <div class="back_next">
              <a href="<?php echo !empty($urlLink)?$urlLink:'#';?>" class="btn btn-md back"><i class="fa fa-plus" aria-hidden="true"></i> Add </a>
              <a href="javascript:void(0);" onclick="editDietSave();" class="btn btn-md next pull-right"><i class="fa fa-check" aria-hidden="true"></i> Save</a>
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