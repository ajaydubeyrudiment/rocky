<div id="myCarousel" class="carousel slide" data-ride="carousel">
  <!-- Wrapper for slides -->
  <div class="carousel-inner">
    <?php 
    $imageStatus = 0;
    if(!empty($row->image_name)&&file_exists('assets/uploads/recipeBlogImages/'.$row->image_name)){
      $file_names = base_url().'assets/uploads/recipeBlogImages/'.$row->image_name;
      echo '<div class="item active">
            <img src="'.base_url().'assets/uploads/recipeBlogImages/'.$row->image_name.'" alt="">
          </div>';
      $imageStatus = 1;
    }else if(!empty($row->coverImage)&&file_exists('assets/uploads/recipeBlogImages/'.$row->coverImage)){
      $file_names = base_url().'assets/uploads/recipeBlogImages/'.$row->coverImage;
      echo '<div class="item active">
              <img src="'.base_url().'assets/uploads/recipeBlogImages/'.$row->coverImage.'" alt="">
            </div>';
      $imageStatus = 1;
    }
    //echo '<pre>'; print_r($images); 
    if(!empty($images)){
      $im=1;
      foreach ($images as $image) {
        if(!empty($image->image_name)&&$row->image_name!=$image->image_name&&file_exists('assets/uploads/recipeBlogImages/'.$image->image_name)){
          if($im==1&&$imageStatus==0){
            echo  '<div class="item active">
                    <img src="'.base_url().'assets/uploads/recipeBlogImages/'.$image->image_name.'" alt="">
                  </div>';
          }else{
            echo  '<div class="item">
                    <img src="'.base_url().'assets/uploads/recipeBlogImages/'.$image->image_name.'" alt="">
                  </div>';
          }
          $im++;
        }
      }
    }  
    if(!empty($row->title)){
      $title =  ucwords($row->title);
    }else if(!empty($row->caption)){
      $title =  ucwords($row->caption);
    }else if(!empty($row->location)){
      $title =  ucwords($row->location);
    }
    ?>  
  </div>
  <?php if(!empty($im)&&$im>1){ ?>
    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a>
  <?php }?>
</div>
<h2><?php echo !empty($title)?ucwords($title):''; ?></h2>
<p><?php echo !empty($row->description)?ucfirst($row->description):''; ?></p>
<table class="table table-bordered">
  <tr>
   <th> Post User Name </th>
   <td>
    <?php 
      if(!empty($row->profile_pic)){
        echo  '<img src="'.base_url().'upload/user/'.$row->profile_pic.'" alt="">';
      }?>
    <?php echo !empty($row->first_name)?ucfirst($row->first_name):''; ?>
    <?php echo !empty($row->last_name)?' '.ucfirst($row->last_name):''; ?>
   </td> 
   <th>Posted Date</th>
   <td>
    <?php echo !empty($row->created_date)?date('d M Y h:i A', strtotime($row->created_date)):''; ?>
   </td>
  </tr>
  <tr> 
   <th>Type</th>
   <td>
      <?php 
      if(!empty($row->type)){
        echo ucwords($row->type);
      } ?>
   </td>
    <th>Ingredients</th>
    <td>
      <?php 
      if(!empty($row->ingredients)){
        echo ucfirst($row->ingredients);
      } ?>
   </td>
  </tr>
  <tr> 
   <th>Tags</th>
   <td>
      <?php 
      if(!empty($row->type)){
        echo ucwords($row->tags);
      } ?>
   </td>
    <th>Instructions</th>
    <td>
      <?php 
      if(!empty($row->instructions)){
        echo ucfirst($row->instructions);
      } ?>
   </td>
  </tr>
</table>

