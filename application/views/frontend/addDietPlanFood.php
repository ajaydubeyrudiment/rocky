<style type="text/css">
  .mainContr ul {height: 160px; overflow-y: scroll;}
  .mainContr {
      min-height: 200px;
  }
  .reltype{position: relative;}
  .mainContr table th{ padding: 12px 0;}
</style>
<div class="mainContr">
	<form method="post" id="addDietFoodDetails" onsubmit="return false;">
		<div id="addDietFoodDetails_res"></div>
		<table style="width: 100%;">
      <?php
      $foodIDItems = array(); 
      if(!empty($rows)){
        foreach($rows as $row){
          $foodIDItems[] = $row->id;
          ?>
			    <tr>
            <th>Food</th> 
            <td>
              <?php echo !empty($row->item_title)?ucwords($row->item_title):''; ?>
            </td>
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
		  	</tr>
        <?php 
        } 
      }?>
      <tr>
       <th colspan="6" class="text-center">
          <input type="submit" name="submit" onclick="addNewDietFood();" class="btn btn-lg btn-success" value="Update">
          <input type="hidden" name="diet_id" value="<?php echo $diet_id;?>">
          <input type="hidden" name="day" value="<?php echo $day;?>">
          <input type="hidden" name="date" value="<?php echo $date;?>">
          <input type="hidden" name="item" value="<?php echo implode(',', $foodIDItems);?>">
        </th>
      </tr>
		</table>
	</form>
</div>