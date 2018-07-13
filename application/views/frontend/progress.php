 <style type="text/css">
    body .wrapper .progress_checkbox ul li {
      width: 100%;
      display: inline-block;
  }
body .wrapper .progress_checkbox {padding: 0;margin: 0;border:none;}
body .wrapper .progress_checkbox ul li .checkbox {padding-right: 0px;padding: 0;margin: 3px 0;}
body .wrapper .progress_checkbox ul li .checkbox input[type="checkbox"] {display: none;opacity: 0;}
body .wrapper .progress_checkbox ul li .checkbox label::before {display: none;}
body .wrapper .progress_checkbox ul li .checkbox label::after {display: none;}
#progrss_chart{

}
.tab-link {
    background-color: #1bbdb0;
    padding: 8px 80px;
    margin: 0 0 2em;
    display: inline-block;
    color: #fff;
    text-decoration: none!important;
}
.tab-link:hover {color: #000;}
    
.tab-link.active {background-color: #000;color: #fff;}

 </style>
 <div class="col-md-9">
    <div class="feed_right">
       <div class="goal_setter" id="progrss_chart">
          <h2>Progress</h2>
          <hr />
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
          <a href="javascript:void();" id="daily" class="tab-link" onclick="changeChart('weekly');">Weekly</a> 
          <a href="javascript:void();" id="monthly" class="tab-link" onclick="changeChart('monthly');">Monthly</a> 
          <a href="javascript:void();" id="yearly" class="tab-link" onclick="changeChart('yearly');">Yearly</a> 
          <a href="javascript:void();" id="all" class="tab-link" onclick="changeChart('all');">all</a>                     
        </div>
        <div class="col-md-10">
          <div id="chart_div"></div>
        </div>
        <div class="col-md-2">
          <div class="row">
          <form id="progressChartFrm" onsubmit="return false;">
            <input type="hidden" name="tab_types" id="tab_types" value="all"/>  
            <input type="hidden" name="user_id" value="<?php if($this->input->get('user_id')){echo $this->input->get('user_id');} ?>"/>  
            <div class="progress_checkbox">             
              <ul class="list-inline">
                <li>
                    <div class="checkbox checkbox-success">
                       <input id="checkbox350" type="checkbox" checked="checked"  name="height" class="useMetricsSystem" value="1" onclick="changeChartLine('height');">
                       <label for="checkbox350">Height</label>
                   </div>
                </li>
                <li>
                  <div class="checkbox checkbox-success">
                     <input id="checkbox351" type="checkbox"  name="weight" class="useMetricsSystem" value="1" onclick="changeChartLine('weight');">
                     <label for="checkbox351">Weight</label>
                 </div>
                </li>
                <li>
                  <div class="checkbox checkbox-success">
                    <input id="checkbox352" type="checkbox" name="caloriesConsumed" class="useMetricsSystem" value="1" onclick="changeChartLine('caloriesConsumed');">
                    <label for="checkbox352">Calories Consumed</label>
                  </div>
                </li>
                <li>
                  <div class="checkbox checkbox-success">
                    <input id="checkbox353" type="checkbox" name="caloriesBurned" class="useMetricsSystem" value="1" onclick="changeChartLine('caloriesBurned');">
                    <label for="checkbox353">Calories Burned</label>
                  </div>
                </li> 
              <!--  <li>
                  <div class="checkbox checkbox-success">
                    <input id="checkbox354" type="checkbox"  name="bodyShots" class="useMetricsSystem" value="1" onclick="changeChartLine('bodyShots');">
                    <label for="checkbox354">Body Shots</label>
                  </div>
                </li> -->
                <?php
                if($this->input->get('user_id')){
                  $userID = $this->input->get('user_id');
                  $user = user_info($this->input->get('user_id'));
                }else{
                  $user = user_info();
                }
                if(!empty($user->advancedMetricsTracking)&&$user->advancedMetricsTracking==1){?>
                  <li>
                      <div class="checkbox checkbox-success">
                         <input id="checkbox355" type="checkbox" name="chest" class="useMetricsSystem" value="1" onclick="changeChartLine('chest');">
                         <label for="checkbox355">Chest</label>
                     </div>
                  </li>
                  <li>
                      <div class="checkbox checkbox-success">
                         <input id="checkbox358" type="checkbox" name="waist" class="useMetricsSystem" value="1" onclick="changeChartLine('waist');">
                         <label for="checkbox358">Waist</label>
                     </div>
                  </li>
                 <!--  <li>
                      <div class="checkbox checkbox-success">
                         <input id="checkbox362" type="checkbox"  name="bodyFat" class="useMetricsSystem" value="1" onclick="changeChartLine('bodyFat');">
                         <label for="checkbox362">Body Fat %</label>
                     </div>
                  </li> -->
                  <li>
                    <div class="checkbox checkbox-success">
                      <input id="checkbox359" type="checkbox" name="hips" class="useMetricsSystem" value="1" onclick="changeChartLine('hips');">
                      <label for="checkbox359">Hips</label>
                   </div>
                  </li>                             
                  <li>
                    <div class="checkbox checkbox-success">
                      <input id="checkbox356" type="checkbox" name="arms" class="useMetricsSystem" value="1" onclick="changeChartLine('arms');">
                      <label for="checkbox356">Arms</label>
                   </div>
                  </li>
                  <li>
                    <div class="checkbox checkbox-success">
                      <input id="checkbox360" type="checkbox" name="legs" class="useMetricsSystem" value="1" onclick="changeChartLine('legs');">
                      <label for="checkbox360">Legs</label>
                    </div>
                  </li>                                            
                  <li>
                    <div class="checkbox checkbox-success">
                      <input id="checkbox357" type="checkbox" name="forearms" class="useMetricsSystem" value="1" onclick="changeChartLine('forearms');">
                      <label for="checkbox357">Forearms</label>
                   </div>
                  </li>               
                  <li>
                    <div class="checkbox checkbox-success">
                      <input id="checkbox361" type="checkbox" name="calves" class="useMetricsSystem" value="1" onclick="changeChartLine('calves');">
                      <label for="checkbox361">Calves</label>
                   </div>
                  </li> 
                 <!--  <li>
                    <div class="checkbox checkbox-success">
                      <input id="checkbox364" type="checkbox" name="dietPlan" class="useMetricsSystem" value="1" onclick="changeChartLine('dietPlan');">
                      <label for="checkbox364">Diet Plan</label>
                   </div>
                  </li>  
                  <li>
                    <div class="checkbox checkbox-success">
                      <input id="checkbox363" type="checkbox" name="workoutPlan" class="useMetricsSystem" value="1" onclick="changeChartLine('workoutPlan');">
                      <label for="checkbox363">Workout Plan</label>
                    </div>
                  </li>  -->
                <?php }?>  
              </ul>
            </div>
          </form>
        </div>
        </div>
      </div>
    <div class="clearfix"></div>  
    </div>
 </div> 
 <!--==script progress-bar end==-->