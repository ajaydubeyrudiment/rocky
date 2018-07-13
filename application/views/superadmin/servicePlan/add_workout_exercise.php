<!-- Content Header (Page header) -->
<section class="content-header tab_header">
  <h1>
    <?php if(!empty($title)) echo $title; ?>
  </h1>
  <ol class="breadcrumb">
    <li>
      <a href="<?php echo ADMIN_URL; ?>superadmin/dashboard">
        <i class="fa fa-dashboard"></i> Dashboard
      </a>
    </li>
    <li><a href="<?php echo ADMIN_URL.'servicePlan/workout_exercise'; ?>">Workout Exercise List</a></li>
    <li class="active"><?php if(!empty($title)) echo $title; ?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
  <div class="row">
    <!-- left column -->
    <div class="col-md-10 col-md-offset-1">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header with-border">              
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form action="" method="post" enctype="multipart/form-data">
          <div class="box-body">
            <?php 
              if(!empty($row->id)){
                echo '<input type="hidden" name="id" value="'.$row->id.'">';
              }  
            ?>
            <input type="hidden" id="a_subcategory_id" value="<?php if(set_value('sub_category_id')){echo set_value('sub_category_id');}elseif(!empty($row->sub_category_id)){ echo $row->sub_category_id;}; ?>">
            <div class="form-group">
              <label for="exampleInputEmail1">
                Category
              </label>   
              <select class="form-control" name="category_id" id="category_id" onchange="selectsubcategory();">
                <option value="">Select Category</option>
                <?php 
                if(!empty($categorys)){
                  foreach ($categorys as $category) {?>
                    <option value="<?php echo !empty($category->id)?$category->id:''; ?>" <?php if(set_value('category_id')&&set_value('category_id')==$category->id){echo 'selected';}else if(!empty($row->category_id)&&$row->category_id==$category->id){ echo 'selected';} ?>>
                      <?php echo !empty($category->title)?$category->title:''; ?>
                    </option>
                  <?php }
                } ?>        
              </select>
              <?php echo form_error('category_id'); ?>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">
                Subcategory
              </label>     
              <select class="form-control" name="sub_category_id" id="sub_category_id">
                <option value="">Select Sub Category</option>
              </select>
              <?php echo form_error('sub_category_id'); ?>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">
                Exercise Title
              </label>     
              <input type="text" placeholder="Exercise Title" class="form-control" name="exercise_title" value="<?php if(set_value('exercise_title')){echo set_value('exercise_title');}elseif(!empty($row->exercise_title)){ echo $row->exercise_title;}   ?>" maxlength="50">
                 <?php echo form_error('exercise_title'); ?>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">
                Cacalories/hour
              </label>     
              <input type="text" placeholder="Enter Cacalories/hour" class="form-control" name="cacalories" value="<?php if(set_value('cacalories')){echo set_value('cacalories');}else{ if(!empty($row->cacalories)){ echo $row->cacalories;}}   ?>" maxlength="50">
                 <?php echo form_error('cacalories'); ?>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">
                Description
              </label> 
              <textarea placeholder="Exercise Description" class="form-control" name="exercise_details"><?php if(set_value('exercise_details')){echo set_value('exercise_details');}elseif(!empty($row->exercise_details)){ echo $row->exercise_details;}   ?></textarea>
              <?php echo form_error('exercise_details'); ?>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">
                Instruction
              </label> 
              <textarea placeholder="Exercise Instruction" class="form-control" name="exercise_instruction"><?php if(set_value('exercise_instruction')){echo set_value('exercise_instruction');}elseif(!empty($row->exercise_instruction)){ echo $row->exercise_instruction;}   ?></textarea>
              <?php echo form_error('exercise_instruction'); ?>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">
                Measure Unit
              </label>   
              <select class="form-control" name="measureUnit" id="measureUnit">
                <option value="">Select measure unit</option>
                <option value="1" <?php if(set_value('measureUnit')&&set_value('measureUnit')==1){echo 'selected';}else if(!empty($row->measureUnit)&&$row->measureUnit==1){ echo 'selected';} ?>>Minuts
                <option value="2" <?php if(set_value('measureUnit')&&set_value('measureUnit')==2){echo 'selected';}else if(!empty($row->measureUnit)&&$row->measureUnit==2){ echo 'selected';} ?>>Set
                <option value="3" <?php if(set_value('measureUnit')&&set_value('measureUnit')==3){echo 'selected';}else if(!empty($row->measureUnit)&&$row->measureUnit==4){ echo 'selected';} ?>>Reps
                <option value="4" <?php if(set_value('measureUnit')&&set_value('measureUnit')==4){echo 'selected';}else if(!empty($row->measureUnit)&&$row->measureUnit==4){ echo 'selected';} ?>>Weight
                </option>                       
              </select>
              <?php echo form_error('measureUnit'); ?>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">
              Exercise Pic
              </label>     
              <input type="file"  class="form-control" name="user_img" >
              <?php echo form_error('user_img'); ?>
            </div>     
            <input type="hidden" name="submit" value="submit">
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
      <!-- /.box -->
    </div>
    <!--/.col (left) -->
  </div>
  <!-- /.row -->
</section>  