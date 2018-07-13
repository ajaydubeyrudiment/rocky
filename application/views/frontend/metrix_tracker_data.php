<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript">
  function heightTextChange(height_id='', height_text=''){
    console.log('height_id= '+height_id+', height_text = '+height_text);
    $('.max_height_'+height_id+' .select-styled').text(height_text);   
  }
</script>
<h2>Metrics Tracker 
<?php
  $user   = user_info();
  if(!empty($startTimeZoneN)&&!empty($endTimeZoneN)){
    $startTimeZone = $startTimeZoneN;
    $endTimeZone   = $endTimeZoneN;
  }else{    
    $wday          = date('w')-1;
    $startTimeZone = strtotime('-'.$wday.' days');
    $endTimeZone   = strtotime('+'.(6-$wday).' days');
  }
  $preSdate  = $startTimeZone - (604800);
  $preEdate  = $endTimeZone - (604800);
  $nextSdate = $startTimeZone + (604800);
  $nextEdate  = $endTimeZone + (604800);
?>
<span class="pull-right metrics_date">
<?php if($startTimeZone>=strtotime(date('Y-m-d', strtotime($user->created_date)))){?>
<a href="javascript:void(0);" onclick="show_week_metrix_data('<?php echo $preSdate; ?>','<?php echo  $preEdate; ?>');">
  <i class="fa fa-chevron-left"></i>
</a>
<?php 
 } ?>
&nbsp;&nbsp;&nbsp;
<?php
  if(date('Y', $startTimeZone)!=date('Y', $endTimeZone)){
    $week_start = date('M d Y', $startTimeZone);
    $week_end   = date('M d Y', $endTimeZone);
  }else if(date('m', $startTimeZone)==date('m', $endTimeZone)){
    $week_start = date('M d', $startTimeZone);
    $week_end   = date('d, Y ', $endTimeZone);
  }else{          
    $week_start = date('M d', $startTimeZone);
    $week_end   = date('M d, Y ', $endTimeZone);
  }
  echo $week_start.' - '.$week_end;   
  $weight   = $hightTitle = '';
  if(!empty($user->useMetricsSystem) && $user->useMetricsSystem==1){ 
    $hightTitle = !empty($user->height)?number_format($user->height, 0).' cm':'';
    $hightID    = $user->height;
    $weight     = $user->weight;
  }else if(!empty($user->height)){    
    $inches      = intval(number_format($user->height/2.54, 2)); 
    $feetInch    = "";
    $feetInch   .= floor($inches/12)."'";
    $feetInch   .= floor($inches%12);                     
    $hightTitle  =  $feetInch;
    $hightID     = $user->height;
    $weight      = $user->weight;  
  }
?>
&nbsp;&nbsp;&nbsp;
<a href="javascript:void(0);" onclick="show_week_metrix_data('<?php echo $nextSdate; ?>','<?php echo
$nextEdate; ?>');">
  <i class="fa fa-chevron-right"></i>
</a>
</span></h2>
<hr />      
<!--==accordian start==-->      
<?php 
$startCurZone = $startTimeZone;
$days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'); 
if(!empty($days)){
  $i=0;
  foreach($days as $day){          
    $startCursZone = $startCurZone+(86400*$i);
    $dateTimeZone  = strtotime(date('Y-m-d', $startCursZone));
    if($dateTimeZone>=strtotime(date('Y-m-d', strtotime($user->created_date)))){?>
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default title_low_carb">              
            <h4 class="panel-title" id="heading<?php echo $day; ?>">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $day; ?>" aria-expanded="true" aria-controls="collapse<?php echo $day; ?>">
              <?php echo ucfirst($day); ?>
            </a>
          </h4>
          <div id="collapse<?php echo $day; ?>" class="panel-collapse collapse <?php if($day==strtolower(date('l'))){ echo 'in';} ?>" role="tabpanel" aria-labelledby="heading<?php echo $day; ?>">                    
                <div class="form-group col-md-12 text-left">
                    <label for="inputEmail3" class="col-sm-2 text-left control-label">Date :</label>
                    <div class="col-sm-10">                              
                      <div class="row mtracker">
                        <label>
                        <?php                           
                          echo date('F d, Y', $startCursZone);                           
                          $row = $this->common_model->get_row('metricTracker', array('matrixDate'=>$dateTimeZone, 'user_id'=>user_id()));
                          //echo '<br/>'.$this->db->last_query();                          
                          $cal_consumed = get_cal_consumed($dateTimeZone, user_id());
                          // echo '<br/>'.$this->db->last_query(); //exit();
                          $cal_burned   = get_cal_burned($dateTimeZone, user_id());
                          if(!empty($user->useMetricsSystem) && !empty($row->height) && $user->useMetricsSystem==1){
                            $hightTitleA = !empty($row->height)?number_format($row->height, 0).' cm':'';
                            $hightID     = number_format($row->height, 0);
                          }else if(!empty($row->height)){
                            $inches      = intval(number_format($row->height/2.54, 2)); 
                            $feetInch    = "";
                            $feetInch   .= floor($inches/12)."'";
                            $feetInch   .= floor($inches%12);                     
                            $hightTitleA =  $feetInch;
                            $hightID     = number_format($row->height, 0);
                          }
                        ?>                                   
                        </label>
                      </div>
                    </div>
                  </div>                          
                  <form class="form-horizontal metrics_tracker clearfix" id="user_metricTracker_<?php echo $day; ?>" onsubmit="return false;">
                    <input type="hidden" name="currentDate" value="<?php echo $dateTimeZone; ?>"/>
                    <input type="hidden" name="currentDay" value="<?php echo $day; ?>"/>
                    <input type="hidden" name="metricStatus" value="<?php echo $user->useMetricsSystem;?>"/>
                    <div class="form-group col-md-6 text-left">
                      <label for="inputEmail3" class="col-sm-4 text-left control-label">Height :</label>
                      <div class="col-sm-8 max_height_<?php echo $dateTimeZone; ?>">
                        <?php 
                        if(!empty($user->useMetricsSystem) && $user->useMetricsSystem==1){ ?>
                          <select class="form-control height" id="mounth" name="<?php echo $day.'_height'; ?>">
                          <?php 
                            $hightTitle = "";
                            for($hi = 91.44;$hi<305;$hi++){
                              if(number_format($hi,0)==number_format($hightID, 0)){ 
                                $hightTitle = number_format($hi, 0).' cm';
                                echo '<option selected value="'.$hi.'">'.number_format($hi, 0).' cm </option>'; 
                              }else{
                                echo '<option value="'.$hi.'">'.number_format($hi, 0).' cm </option>'; 
                              }
                            }                               
                          ?>   
                          </select> 
                        <?php }else{?>                          
                        <select class="form-control" id="mounth" name="<?php echo $day.'_height'; ?>">
                          <?php 
                            $hightTitle ="";
                            $hieghtStrCm = 91.44;
                            for($hi=1;$hi<85;$hi++){
	                            $feetI        	= $feet = $inches = "";
	                            $hieghtStrCm  	= $hieghtStrCm + 2.54; 
	                            $hieghtStrCmC 	= floatval($hieghtStrCm); 
	                            $hieghtStrCmD 	= $hieghtStrCmC / 2.54;
	                            $inches      	  = floatval($hieghtStrCmD);
	                            $inchesN     	  = intval($inches);
	                            $feet        	  = intval($inches/12);
	                            $feetI       	  = intval($feet*12);                                                  
	                            $feetN       	  = ($inchesN==$feetI)?'':floatval($inches)-floatval($feetI);
	                            $feetInch    	  = $feet."'".$feetN;
	                            if(floor($hieghtStrCm) == floor($hightID)){ 
	                                $hightTitle = $feetInch;
	                                echo '<option selected value="'.$hieghtStrCmC.'">'.$feetInch.'</option>';
	                             }else{
	                                echo '<option value="'.$hieghtStrCmC.'">'.$feetInch.'</option>'; 
	                            }                           
                        	}                                
                          ?>  
                        </select>
                        <?php }
                        ?>
                        <script type="text/javascript">
                          setTimeout(function(){
                            heightTextChange("<?php echo $dateTimeZone; ?>","<?php if(!empty($hightTitle)){echo $hightTitle; }else if(!empty($hightTitleA)){echo $hightTitleA;} ?>");
                          }, 500);                            
                        </script>
                      </div>
                    </div>
                    <?php
                    if(!empty($row->weight)){                      
                      if($row->matrix_status==$user->useMetricsSystem&&$user->useMetricsSystem==1){
                        $weight = !empty($row->weight)?$row->weight:'';
                      }else if($row->matrix_status==$user->useMetricsSystem&&$user->useMetricsSystem==2){
                        $weight = !empty($row->weight)?$row->weight:'';
                      }elseif($user->useMetricsSystem==1){
                        $weight = number_format($row->weight / 2.2046, 0);
                      }elseif($user->useMetricsSystem==2){
                        $weight = number_format($row->weight * 2.2046, 0);
                      } 
                    }
                    ?>

                    <div class="form-group col-md-6 text-left">
                      <label for="inputEmail3" class="col-sm-4 text-left control-label">Weight :</label>
                      <div class="col-sm-8">
                         <input type="text" class="form-control" placeholder="Enter your weight in <?php if(!empty($user->useMetricsSystem) && $user->useMetricsSystem==1){ echo 'kg'; }else{echo 'lbs';}?>" name="<?php echo $day.'_weight'; ?>" value="<?php if(!empty($weight)){echo $weight; }?>"> 
                      </div>
                    </div>
                    <div class="form-group col-md-6 text-left">
                      <label for="inputEmail3" class="col-sm-4 text-left control-label">Cal Consumed :</label>
                      <div class="col-sm-8">
                         <input type="text" class="form-control" placeholder="Enter Cal Consumed" name="<?php echo $day.'_cal_consumed'; ?>" value="<?php if(!empty($row->calConsumed)){echo $row->calConsumed;}else if(!empty($cal_consumed)){echo $cal_consumed; }?>"> 
                      </div>
                    </div>
                    <div class="form-group col-md-6 text-left">
                      <label for="inputEmail3" class="col-sm-4 text-left control-label">Cal Burned :</label>
                      <div class="col-sm-8">
                         <input type="text" class="form-control" placeholder="Enter Cal Burned" name="<?php echo $day.'_cal_burned'; ?>" value="<?php if(!empty($row->calBurned)){echo $row->calBurned;}else if(!empty($cal_burned)){echo $cal_burned; }?>"> 
                      </div>
                    </div>
                    <div class="form-group col-md-12 text-center">
                      <?php
                      if(!empty($row->bodyShot)&&file_exists('assets/uploads/matrix/thumbnails/'.$row->bodyShot)){
                        echo '<img style="display: inline-block;;" src="'.base_url().'assets/uploads/matrix/thumbnails/'.$row->bodyShot.'" class="m_files" id="'.$day.'_maxtrix_img" />';
                      }else{
                        echo '<img src="" class="m_files" id="'.$day.'_maxtrix_img" />';
                      } ?>
                    <div class="form-group col-md-12 text-left">
                      <label for="inputEmail3" class="col-sm-4 text-left control-label">Body Shot :</label>
                      <div class="col-sm-8">
                        <div class="row mtracker upload-btn">
                          <input type="file" class="form-control"  onchange="uploadMFiles('<?php echo $day; ?>');" id="<?php echo $day.'_body_shot_file'; ?>"/>
                           UPLOAD IMAGE 
                        </div>
                        <input type="hidden" name="<?php echo $day.'_body_shot'; ?>" id="<?php echo $day.'_body_shot'; ?>">
                        <div id="<?php echo $day.'_body_shot'; ?>_error" class="text-danger"></div>
                      </div>
                    </div>
                  <?php
                  if(!empty($user->advancedMetricsTracking)&&$user->advancedMetricsTracking==1){?>
                    <div class="form-group col-md-6 text-left">
                      <label for="inputEmail3" class="col-sm-4 text-left control-label">Chest :</label>
                      <div class="col-sm-8">
                         <input type="text" class="form-control" placeholder="Enter Chest" name="<?php echo $day.'_chest'; ?>" value="<?php if(!empty($row->chest)){echo $row->chest;}?>"> 
                      </div>
                    </div>
                    <div class="form-group col-md-6 text-left">
                      <label for="inputEmail3" class="col-sm-4 text-left control-label">Waist :</label>
                      <div class="col-sm-8">
                         <input type="text" class="form-control" placeholder="Enter Waist" name="<?php echo $day.'_waist'; ?>" value="<?php if(!empty($row->waist)){echo $row->waist;}?>"> 
                      </div>
                    </div>
                    <div class="form-group col-md-6 text-left">
                      <label for="inputEmail3" class="col-sm-4 text-left control-label">Arms :</label>
                      <div class="col-sm-8">
                         <input type="text" class="form-control" placeholder="Enter Arms" name="<?php echo $day.'_arms'; ?>" value="<?php if(!empty($row->arms)){echo $row->arms;}?>"> 
                      </div>
                    </div>
                    <div class="form-group col-md-6 text-left">
                      <label for="inputEmail3" class="col-sm-4 text-left control-label">Forearms :</label>
                      <div class="col-sm-8">
                         <input type="text" class="form-control" placeholder="Enter Forearms" name="<?php echo $day.'_forearms'; ?>" value="<?php if(!empty($row->forearms)){echo $row->forearms;}?>"> 
                      </div>
                    </div>
                    <div class="form-group col-md-6 text-left">
                      <label for="inputEmail3" class="col-sm-4 text-left control-label">Legs :</label>
                      <div class="col-sm-8">
                         <input type="text" class="form-control" placeholder="Enter Legs" name="<?php echo $day.'_legs'; ?>" value="<?php if(!empty($row->legs)){echo $row->legs;}?>"> 
                      </div>
                    </div>
                    <div class="form-group col-md-6 text-left">
                      <label for="inputEmail3" class="col-sm-4 text-left control-label">Calves :</label>
                      <div class="col-sm-8">
                         <input type="text" class="form-control" placeholder="Enter Calves" name="<?php echo $day.'_calves'; ?>" value="<?php if(!empty($row->calves)){echo $row->calves;}?>"> 
                      </div>
                    </div>
                    <div class="form-group col-md-6 text-left">
                      <label for="inputEmail3" class="col-sm-4 text-left control-label">Hips :</label>
                      <div class="col-sm-8">
                         <input type="text" class="form-control" placeholder="Enter Hips" name="<?php echo $day.'_hips'; ?>" value="<?php if(!empty($row->hips)){echo $row->hips;}?>"> 
                      </div>
                    </div>
                    <div class="form-group col-md-6 text-left">
                      <label for="inputEmail3" class="col-sm-4 text-left control-label">Biceps BF % :</label>
                      <div class="col-sm-8">
                         <input type="text" class="form-control" placeholder="Enter Biceps BF%" name="<?php echo $day.'_bicepsBF'; ?>" value="<?php if(!empty($row->bicepsBF)){echo $row->bicepsBF;}?>"> 
                      </div>
                    </div>
                    <div class="form-group col-md-6 text-left">
                      <label for="inputEmail3" class="col-sm-4 text-left control-label">Abs BF % :</label>
                      <div class="col-sm-8"> 
                         <input type="text" class="form-control" placeholder="Enter Abs BF%" name="<?php echo $day.'_absBF'; ?>" value="<?php if(!empty($row->absBF)){echo $row->absBF;}?>"> 
                      </div>
                    </div>
                    <div class="form-group col-md-6 text-left">
                      <label for="inputEmail3" class="col-sm-4 text-left control-label">Thighs BF % :</label>
                      <div class="col-sm-8">
                         <input type="text" class="form-control" placeholder="Enter Thighs BF%" name="<?php echo $day.'_thighsBF'; ?>" value="<?php if(!empty($row->thighsBF)){echo $row->thighsBF;}?>"> 
                      </div>
                    </div>
                    <?php }?>
                  <div class="form-group col-md-12 text-center">
                    <a href="javascript:void(0);" onclick="saveMatrix('<?php echo $day; ?>');" class="btn bnt-lg btn-success">Save </a><br/><br/>
                    <div id="matrix_res_<?php echo $day; ?>"></div>
                  </div>
                </form>
              <div class="clearfix"></div>                   
          </div>
        </div>
    </div>
  <?php
    } 
    $i++;
  } 
}?>
