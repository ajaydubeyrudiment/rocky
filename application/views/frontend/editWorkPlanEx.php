<?php 
//print_r($exercisRow); exit();
$user   = user_info();
if($exercisRow->measureUnit==1){
  $exMinuts    = !empty($exercisRow->minuts)?$exercisRow->minuts:"30";
}else if($exercisRow->measureUnit==1){
  $exMinuts    = !empty($exercisRow->minuts)?$exercisRow->minuts:"5";
}else{
  $exMinuts    = !empty($exercisRow->minuts)?$exercisRow->minuts:"5";
}
$exSets      = !empty($exercisRow->sets)?$exercisRow->sets:"5";
$exReps      = !empty($exercisRow->reps)?$exercisRow->reps:"5";  
?>
<style type="text/css">
	.mainContr ul {height: 160px; overflow-y: scroll;}
	.mainContr {
	    min-height: 200px;
	}
	.reltype{position: relative;}
</style>
<div class="mainContr">
	<form method="post" id="editWorkOutExDetails" onsubmit="return false;">
		<div id="editWorkOutExDetails_res"></div>
		<table style="width: 100%;">
			<tr>
				<th>Exercise</th>
				<td><?php echo !empty($exercisRow->item_title)?ucwords($exercisRow->item_title):'';?></td>
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
						<input type="hidden" name="sets" id="mealType_set_<?php echo $exercisRow->id;?>" value="<?php echo !empty($exSets)?$exSets:''; ?>">
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
						<input type="hidden" name="reps" id="mealType_reps_<?php echo $exercisRow->id;?>" value="<?php echo !empty($exReps)?$exReps:''; ?>">
					</td>
				<?php }else{?>
					<th colspan="3">
						<?php if($exercisRow->measureUnit==1){echo 'Minutes';}else if($exercisRow->measureUnit==2){echo 'Sets';}else if($exercisRow->measureUnit==3){echo 'Reps';}else{echo 'Weight';} ?>
					</th>
			      	<td class="reltype">
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
			          		<input type="hidden" name="minuts" id="mealType<?php echo $exercisRow->id;?>" value="<?php echo !empty($exMinuts)?$exMinuts:''; ?>">
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
			          	<input type="hidden" name="minuts" id="mealType<?php echo $exercisRow->id;?>" value="<?php echo !empty($exMinuts)?$exMinuts:''; ?>">
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
			          	<input type="hidden" name="minuts" id="mealType<?php echo $exercisRow->id;?>" value="<?php echo !empty($exMinuts)?$exMinuts:''; ?>">
			        <?php 
			        }else if($exercisRow->measureUnit==4){?>
			          	<input type="text" name="minuts" placeholder="<?php if(!empty($user->useMetricsSystem) && $user->useMetricsSystem==1){echo 'weight in kg';}else{echo 'weight in  lbs';} ?>" maxlength="3" id="mealType<?php echo $exercisRow->id;?>" value="<?php echo !empty($exMinuts)?$exMinuts:''; ?>" style="width: 50%; border: 1px solid #1BBDB0; height: 32px;padding-left: 11px;  color: #1BBDB0;  font-size: 14px;}">
			        <?php } 
			        ?>
			      	</td>
		      	<?php }?>
		      	<td>
		      		<input type="submit" name="submit" onclick="updateWorkOutEx();" class="btn btn-sm btn-success" value="Update">
		      		<input type="hidden" name="exerciseID" value="<?php echo $exercisRow->id;?>">
		      	</td>
		  	</tr>
		</table>
	</form>
</div>