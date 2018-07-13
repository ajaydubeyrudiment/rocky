<?php 
	$user   = user_info();
?>
<style type="text/css">
	.mainContr ul {height: 160px; overflow-y: scroll;}
	.mainContr {
	    min-height: 200px;
	}
	.reltype{position: relative;}
	.mainContr table th{ padding: 12px 0;}
</style>
<div class="mainContr">
	<form method="post" id="addWorkOutExDetails" onsubmit="return false;">
		<div id="addWorkOutExDetails_res"></div>	
		<table style="width: 100%;">
			<tr>
				<th>Exercise</th>
				<th colspan="2">Minuts/Set/Reps/Weight</th>				
			</tr>
			<?php 
			$exercisIDs = array();
			if(!empty($exercises)){
				foreach($exercises  as $exercis){
	    			$exercisRow = $this->common_model->get_row('service_plan_user_exercise', array('id'=>$exercis));
	    			if($exercisRow->measureUnit==1){
					  	$exMinuts    = !empty($exercisRow->minuts)?$exercisRow->minuts:"30";
					}else if($exercisRow->measureUnit==1){
					  	$exMinuts    = !empty($exercisRow->minuts)?$exercisRow->minuts:"5";
					}else{
					  	$exMinuts    = !empty($exercisRow->minuts)?$exercisRow->minuts:"5";
					}
					$exSets      = "5";
					$exReps      = "5";  
					$exercisIDs[]   = $exercisRow->id;
					?>
					<tr>
						<td><?php echo ucwords($exercisRow->exercise_title); ?></td>
						<?php
						if(!empty($exercisRow->category_id)&&$exercisRow->category_id==2){?>
						<th>Sets</th>
						<td class="reltype">
							<a href="#" class="dropdown-toggle profile_pic_a" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						  		<span id="mealTypeText_set_<?php echo $exercisRow->id;?>">
						    		<?php echo !empty($exSets)?$exSets:''; ?>
						  		</span> 
						  		<i class="fa fa-caret-down"></i>
							</a>
							<ul class="dropdown-menu minus_manus">
								<?php 
								for($m=1;$m<=10;$m++){?>           
								  	<li><a href="javascript:void(0);" onclick="mealType('<?php echo $m; ?>','_set_<?php echo $exercisRow->id;?>');"><?php echo $m; ?></a></li>         
								<?php } 
							echo  '</ul>';?>          
							<input type="hidden" name="sets_<?php echo $exercisRow->id;?>" id="mealType_set_<?php echo $exercisRow->id;?>" value="<?php echo !empty($exSets)?$exSets:''; ?>">
						<th>Reps</th>
						<td class="reltype">
							<a href="#" class="dropdown-toggle profile_pic_a" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span id="mealTypeText_reps_<?php echo $exercisRow->id;?>"><?php echo !empty($exReps)?$exReps:''; ?></span> <i class="fa fa-caret-down"></i></a>
							<ul class="dropdown-menu minus_manus">
							<?php for($m=1;$m<=20;$m++){?>           
							   <li>
								   	<a href="javascript:void(0);" onclick="mealType('<?php echo $m; ?>','_reps_<?php echo $exercisRow->id;?>');">
								   		<?php echo $m; ?>						   		
								   	</a>
							   </li>         
							<?php } 
							echo  '</ul>';
							?>          
							<input type="hidden" name="reps_<?php echo $exercisRow->id;?>" id="mealType_reps_<?php echo $exercisRow->id;?>" value="<?php echo !empty($exReps)?$exReps:''; ?>">
						</td>
					<?php }else{ ?>
						<th colspan="3"><?php if($exercisRow->measureUnit==1){echo 'Minutes';}else if($exercisRow->measureUnit==2){echo 'Sets';}else if($exercisRow->measureUnit==3){echo 'Reps';}else{echo 'Weight';} ?></th>
				      	<th class="reltype">
				        <?php 
				       	if($exercisRow->measureUnit==1){?>
				          	<a href="#" class="dropdown-toggle profile_pic_a" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
				          		<span id="mealTypeText<?php echo $exercisRow->id;?>"><?php echo !empty($exMinuts)?$exMinuts:''; ?></span> <i class="fa fa-caret-down"></i>
				          	</a>
				            <ul class="dropdown-menu minus_manus">
				            <?php for($m=5;$m<=120;$m=$m+5){?>           
				              	<li><a href="javascript:void(0);" onclick="mealType('<?php echo $m; ?>','<?php echo $exercisRow->id;?>');"><?php echo $m; ?></a></li> 
				              	<?php }?>        
				            </ul>          
			          		<input type="hidden" name="minuts_<?php echo $exercisRow->id;?>" id="mealType<?php echo $exercisRow->id;?>" value="<?php echo !empty($exMinuts)?$exMinuts:''; ?>">
				        <?php } else if($exercisRow->measureUnit==2){?>
				         	<a href="#" class="dropdown-toggle profile_pic_a" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
				         		<span id="mealTypeText<?php echo $exercisRow->id;?>"><?php echo !empty($exMinuts)?$exMinuts:''; ?></span> <i class="fa fa-caret-down"></i>
				         	</a>
				            <ul class="dropdown-menu minus_manus">
				            <?php for($m=1;$m<=10;$m++){?>           
				              	<li>
				              		<a href="javascript:void(0);" onclick="mealType('<?php echo $m; ?>','<?php echo $exercisRow->id;?>');"><?php echo $m; ?></a>
				              	</li> 
				              	<?php }?>        
				            </ul>         
				          	<input type="hidden" name="minuts_<?php echo $exercisRow->id;?>" id="mealType<?php echo $exercisRow->id;?>" value="<?php echo !empty($exMinuts)?$exMinuts:''; ?>">
				        <?php }else if($exercisRow->measureUnit==3){?>
				          	<a href="#" class="dropdown-toggle profile_pic_a" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
				          		<span id="mealTypeText<?php echo $exercisRow->id;?>"><?php echo !empty($exMinuts)?$exMinuts:''; ?></span> <i class="fa fa-caret-down"></i>
				          	</a>
				            <ul class="dropdown-menu minus_manus">
				            <?php for($m=1;$m<=20;$m++){?>           
				              	<li>
				              		<a href="javascript:void(0);" onclick="mealType('<?php echo $m; ?>','<?php echo $exercisRow->id;?>');"><?php echo $m; ?></a>
				              	</li>  
				              	<?php }?>       
				            </ul>        
				          	<input type="hidden" name="minuts_<?php echo $exercisRow->id;?>" id="mealType<?php echo $exercisRow->id;?>" value="<?php echo !empty($exMinuts)?$exMinuts:''; ?>">
				        <?php 
				        }else if($exercisRow->measureUnit==4){?>
				          	<input type="text" maxlength="3" name="minuts_<?php echo $exercisRow->id;?>" id="mealType<?php echo $exercisRow->id;?>" placeholder="<?php if(!empty($user->useMetricsSystem) && $user->useMetricsSystem==1){echo 'weight in kg';}else{echo 'wieght in  lbs';} ?>" value="<?php echo !empty($exMinuts)?$exMinuts:''; ?>" style="width: 100px; border: 1px solid #1BBDB0; height: 32px;padding-left: 11px;  color: #1BBDB0;  font-size: 14px;}">
				        <?php } 
				        ?>
				      	</th>
				      	<?php }?>
				  	</tr>
			<?php 
				}
			}?>
			<tr>
				<th colspan="3" class="text-center">
					<input type="hidden" name="exercises" value="<?php echo implode(',', $exercisIDs);?>">
					<input type="hidden" name="workout_id" value="<?php echo $workout_id;?>">
					<input type="hidden" name="day" value="<?php echo $day;?>">
					<input type="hidden" name="date" value="<?php echo $date;?>">
					<input type="submit" name="submit" onclick="addNewWorkOutEx();" class="btn btn-lg btn-success" value="Update">
				</th>
			</tr>
		</table>
	</form>
</div>