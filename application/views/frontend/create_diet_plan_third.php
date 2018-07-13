  <style type="text/css"> 
    .reltype{ position: relative; }
    .create_diet .checkbox {
        padding-right: 50px;
    }
  </style>
  <?php if(empty($plan_id)){?>
    <div class="form-group">
      <input type="text" placeholder="Plan title" name="plan_name" id="plan_name" maxlength="20" class="form-control" value="<?php echo !empty($planRow->plan_name)?$planRow->plan_name:""; ?>" />
      <div id="plan_name_error" class="text-danger"></div>
    </div>
    <div class="form-group">
      <textarea name="plan_description" placeholder="Plan description" id="plan_description" rows="5" class="form-control" maxlength="200" ><?php echo !empty($planRow->plan_description)?$planRow->plan_description:""; ?></textarea>
      <div id="plan_description_error" class="text-danger"></div>
    </div>
  <?php }?>
  <div class="create_workout">  
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
      $thirdStep    = '';
      if(!empty($rows)){
        $ids=0;
        foreach($rows  as $row){
          $ids++;
          if($this->input->post('id')){
            $userItems = $this->common_model->get_row('diet_plan_works_new', array('item_id'=>$row->id, 
                                                                                   'plan_id'=>$this->input->post('id')
                                                                                   )
                                                                                  );
          }
          $keyID    = time().rand(1111,9999);
          $keys[]   = $keyID;      
          $row->id  = $row->id.'_'.$keyID;
        ?> 
          <div class="create_workout create_diet"  id="<?php echo $keyID;?>">
            <table class="table table-bordered">   
              <tr>
                <th>Meal</th>
                <td class="reltype">
                  <a href="#" class="dropdown-toggle profile_pic_a" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span id="mealTypeText<?php echo $row->id;?>"><?php echo !empty($userItems->meal)?ucwords($userItems->meal):'Breakfast'; ?></span><span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a href="javascript:void(0);" onclick="mealType('Breakfast','<?php echo $row->id;?>');">Breakfast</a></li>
                    <li><a href="javascript:void(0);" onclick="mealType('Lunch','<?php echo $row->id;?>');">Lunch</a></li>
                    <li><a href="javascript:void(0);" onclick="mealType('Dinner','<?php echo $row->id;?>');">Dinner</a></li>
                    <li><a href="javascript:void(0);" onclick="mealType('Snack','<?php echo $row->id;?>');">Snack</a></li>
                    <li><a href="javascript:void(0);" onclick="mealType('Other','<?php echo $row->id;?>');">Other</a></li>
                  </ul>
                  <input type="hidden" name="mealType<?php echo $row->id;?>" id="mealType<?php echo $row->id;?>" value="<?php echo !empty($userItems->meal)?ucwords($userItems->meal):'breakfast'; ?>">
                </td>
                <th>Food</th>
                <td><?php echo ucwords($row->item_title);?></td>
                <th>Serving</th>
                <td class="reltype">
                  <a href="#" class="dropdown-toggle profile_pic_a" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <span id="servingTypeText<?php echo $row->id;?>"><?php echo !empty($userItems->serving)?ucwords($userItems->serving):'1'; ?></span><span class="caret"></span>
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
                  <input type="hidden" name="servingType<?php echo $row->id;?>" id="servingType<?php echo $row->id;?>" value="<?php echo !empty($userItems->serving)?ucwords($userItems->serving):'1'; ?>">
                </td>
                <td>
                  <a class="btn btn-sm btn-primary" href="javascript:void(0);" onclick="addCopyFood('<?php echo $row->id; ?>','<?php echo $keyID; ?>');" title="Copy Food"><i class="fa fa-clone"></i></a>
                  <a class="btn btn-sm btn-danger" href="javascript:void(0);" onclick="addDeleteFood('<?php echo $row->id; ?>','<?php echo $keyID; ?>');" title="Delete Food"><i class="fa fa-times"></i></a>
                </td>
              </tr> 
              <tr>   
                <td colspan="7">                   
                  <ul class="list-inline text-left listssD">
                    <li>
                      <div class="checkbox checkbox-success">
                        <input id="checkbox_monday_<?php echo $row->id;?>" value="1" <?php  echo !empty($userItems->monday)?'checked':''; ?> name="<?php echo $row->id.'_'.$monday_date;?>" type="checkbox">
                        <label for="checkbox_monday_<?php echo $row->id;?>">Monday</label>
                      </div>
                    </li>
                    <li>
                      <div class="checkbox checkbox-success">
                        <input id="checkbox_tuesday_<?php echo $row->id;?>" value="1" <?php  echo !empty($userItems->tuesday)?'checked':''; ?> name="<?php echo $row->id.'_'.$tuesday_date;?>" class="hidden"  type="checkbox">
                        <label for="checkbox_tuesday_<?php echo $row->id;?>"">Tuesday</label>
                      </div>
                    </li>
                   <li>
                      <div class="checkbox checkbox-success">
                        <input id="checkbox_wednesday_<?php echo $row->id;?>" <?php  echo !empty($userItems->wednesday)?'checked':''; ?> value="1" name="<?php echo $row->id.'_'.$wednesday_date;?>" type="checkbox">
                        <label for="checkbox_wednesday_<?php echo $row->id;?>">Wednesday</label>
                      </div>
                    </li>
                    <li>
                      <div class="checkbox checkbox-success">
                        <input id="checkbox_thursday_<?php echo $row->id;?>" <?php  echo !empty($userItems->thursday)?'checked':''; ?> value="1" name="<?php echo $row->id.'_'.$thursday_date;?>" type="checkbox">
                        <label for="checkbox_thursday_<?php echo $row->id;?>">Thursday</label>
                      </div>
                    </li>
                    <li>
                      <div class="checkbox checkbox-success">
                        <input id="checkbox_friday_<?php echo $row->id;?>" <?php  echo !empty($userItems->friday)?'checked':''; ?> value="1" name="<?php echo $row->id.'_'.$friday_date;?>" type="checkbox">
                        <label for="checkbox_friday_<?php echo $row->id;?>">Friday</label>
                      </div>
                    </li>
                    <li>
                      <div class="checkbox checkbox-success">
                        <input id="checkbox_saturday_<?php echo $row->id;?>" <?php  echo !empty($userItems->saturday)?'checked':''; ?> value="1" name="<?php echo $row->id.'_'.$saturday_date;?>" type="checkbox">
                        <label for="checkbox_saturday_<?php echo $row->id;?>">Saturday</label>
                      </div>
                    </li>
                    <li>
                      <div class="checkbox checkbox-success">
                        <input id="checkbox_sunday_<?php echo $row->id;?>" <?php  echo !empty($userItems->sunday)?'checked':''; ?> value="1" name="<?php echo $row->id.'_'.$sunday_date;?>" type="checkbox">
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
<input type="hidden" name="row_keys" id="row_keys" value="<?php echo !empty($keys)?implode(',', $keys):''; ?>">