<style type="text/css">
	.mainContr ul {height: 160px; overflow-y: scroll;}
	.mainContr {
	    min-height: 200px;
	}
	.reltype{position: relative;}
</style>
<div class="mainContr">
	<form method="post" id="editDietPlanFoodDetails" onsubmit="return false;">
		<div id="editDietPlanFoodDetails_res"></div>

    <!--== new table start ==-->
    <table style="width: 100%;">
      <tr>
        <th class="text-center">Food</th>
        <th class="text-center">Meal</th> 
        <th class="text-center">Serving</th>
        <th class="text-center">Action</th>
      </tr>
      <tr>
        <td class="text-center"><?php echo ucwords($row->item_title);?></td>
        <td class="reltype text-center">
          <a href="#" class="dropdown-toggle profile_pic_a" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span id="mealTypeText<?php echo $row->id;?>"><?php echo !empty($row->meal)?ucwords($row->meal):'Breakfast'; ?></span><span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="javascript:void(0);" onclick="mealType('Breakfast','<?php echo $row->id;?>');">Breakfast</a></li>
            <li><a href="javascript:void(0);" onclick="mealType('Lunch','<?php echo $row->id;?>');">Lunch</a></li>
            <li><a href="javascript:void(0);" onclick="mealType('Dinner','<?php echo $row->id;?>');">Dinner</a></li>
            <li><a href="javascript:void(0);" onclick="mealType('Snack','<?php echo $row->id;?>');">Snack</a></li>
            <li><a href="javascript:void(0);" onclick="mealType('Other','<?php echo $row->id;?>');">Other</a></li>
          </ul>
          <input type="hidden" class="form-control" name="mealType" id="mealType<?php echo $row->id;?>" value="<?php echo !empty($row->meal)?ucwords($row->meal):'breakfast'; ?>">
        </td>
        <td class="reltype text-center">
          <a href="#" class="dropdown-toggle profile_pic_a" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <span id="servingTypeText<?php echo $row->id;?>"><?php echo !empty($row->serving)?ucwords($row->serving):'0.25'; ?></span><span class="caret"></span>
            <i class="fa fa-caret-down"></i>
          </a>
          <ul class="dropdown-menu">
            <li><a href="javascript:void(0);" onclick="servingType('0.25','<?php echo $row->id;?>');">0.25</a></li>
            <li><a href="javascript:void(0);" onclick="servingType('0.50','<?php echo $row->id;?>');">0.50</a></li>
            <li><a href="javascript:void(0);" onclick="servingType('0.75','<?php echo $row->id;?>');">0.75</a></li>
            <li><a href="javascript:void(0);" onclick="servingType('1','<?php echo $row->id;?>');">1</a></li>
            <li><a href="javascript:void(0);" onclick="servingType('1.25','<?php echo $row->id;?>');">1.25</a></li>
            <li><a href="javascript:void(0);" onclick="servingType('1.50','<?php echo $row->id;?>');">1.50</a></li>
            <li><a href="javascript:void(0);" onclick="servingType('1.75','<?php echo $row->id;?>');">1.75</a></li>
            <li><a href="javascript:void(0);" onclick="servingType('2','<?php echo $row->id;?>');">2</a></li>
          </ul>
          <input type="hidden" name="servingType" id="servingType<?php echo $row->id;?>" value="<?php echo !empty($row->serving)?ucwords($row->serving):'0.25'; ?>">
        </td>
        <td class="text-center">
          <input type="submit" name="submit" onclick="updateDietPlanEx();" class="btn btn-sm" value="Update">
          <input type="hidden" name="foodID" value="<?php echo $foodID;?>">
        </td>
      </tr>
    </table>
    <!--== new table end ==-->




	<!-- 	<table style="width: 100%;">
			<tr>
        <th>Food</th>
        <td><?php echo ucwords($row->item_title);?></td>
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
          <input type="hidden" name="mealType" id="mealType<?php echo $row->id;?>" value="<?php echo !empty($row->meal)?ucwords($row->meal):'breakfast'; ?>">
        </td>
        <th>Serving</th>
        <td class="reltype">
          <a href="#" class="dropdown-toggle profile_pic_a" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <span id="servingTypeText<?php echo $row->id;?>"><?php echo !empty($row->serving)?ucwords($row->serving):'0.25'; ?></span><span class="caret"></span>
            <i class="fa fa-caret-down"></i>
          </a>
          <ul class="dropdown-menu">
            <li><a href="javascript:void(0);" onclick="servingType('0.25','<?php echo $row->id;?>');">0.25</a></li>
            <li><a href="javascript:void(0);" onclick="servingType('0.50','<?php echo $row->id;?>');">0.50</a></li>
            <li><a href="javascript:void(0);" onclick="servingType('0.75','<?php echo $row->id;?>');">0.75</a></li>
            <li><a href="javascript:void(0);" onclick="servingType('1','<?php echo $row->id;?>');">1</a></li>
            <li><a href="javascript:void(0);" onclick="servingType('1.25','<?php echo $row->id;?>');">1.25</a></li>
            <li><a href="javascript:void(0);" onclick="servingType('1.50','<?php echo $row->id;?>');">1.50</a></li>
            <li><a href="javascript:void(0);" onclick="servingType('1.75','<?php echo $row->id;?>');">1.75</a></li>
            <li><a href="javascript:void(0);" onclick="servingType('2','<?php echo $row->id;?>');">2</a></li>
          </ul>
          <input type="hidden" name="servingType" id="servingType<?php echo $row->id;?>" value="<?php echo !empty($row->serving)?ucwords($row->serving):'0.25'; ?>">
        </td>
      	<td>
      		<input type="submit" name="submit" onclick="updateDietPlanEx();" class="btn btn-sm btn-success" value="Update">
      		<input type="hidden" name="foodID" value="<?php echo $foodID;?>">
      	</td>
  	  </tr>
	  </table> -->
  </form>
</div>