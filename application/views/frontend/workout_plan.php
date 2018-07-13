<div class="col-md-9">
  <form id="workoutItems" method="post" onsubmit="return false;">
    <div class="feed_right">
      <div class="goal_setter">
        <div id="workout_plan_data">
          <?php include('workout_plan_data.php'); ?>
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
          <h4 class="modal-title" id="myModalLabel"></h4>
        </div>
        <div class="modal-body clearfix">
           <div class="col-md-12" id="workoutExerciseDetails"></div>         
        </div>
      </div>
    </div>
</div>
<!--==moreinformationfruits end==-->