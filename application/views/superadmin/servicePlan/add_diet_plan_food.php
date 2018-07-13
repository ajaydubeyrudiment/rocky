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
    <li><a href="<?php echo ADMIN_URL.'servicePlan/diet_plan_food'; ?>">Diet Item List</a></li>
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
               Diet  Item
              </label>     
              <input type="text" placeholder="Diet Item" class="form-control" name="item_title" value="<?php if(set_value('item_title')){echo set_value('item_title');}elseif(!empty($row->item_title)){ echo $row->item_title;}   ?>" maxlength="50">
                 <?php echo form_error('item_title'); ?>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">
                Cacalories
              </label>     
              <input type="text" placeholder="Enter Cacalories" class="form-control" name="cacalories" value="<?php if(set_value('cacalories')){echo set_value('cacalories');}else{ if(!empty($row->cacalories)){ echo $row->cacalories;}}   ?>" maxlength="50">
                 <?php echo form_error('cacalories'); ?>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">
                Protein
              </label>     
              <input type="text" placeholder="Enter Protein" class="form-control" name="protein" value="<?php if(set_value('protein')){echo set_value('protein');}else{ if(!empty($row->protein)){ echo $row->protein;}}   ?>" maxlength="50">
                 <?php echo form_error('protein'); ?>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">
              Fat
              </label>     
              <input type="text" placeholder="Enter fat" class="form-control" name="fat" value="<?php if(set_value('protein')){echo set_value('fat');}else{ if(!empty($row->fat)){ echo $row->fat;}}   ?>" maxlength="50">
                 <?php echo form_error('fat'); ?>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">
              Carbohydrate
              </label>     
              <input type="text" placeholder="Enter carbohydrate" class="form-control" name="carbohydrate" value="<?php if(set_value('carbohydrate')){echo set_value('carbohydrate');}else{ if(!empty($row->carbohydrate)){ echo $row->carbohydrate;}}   ?>" maxlength="50">
                 <?php echo form_error('carbohydrate'); ?>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">
              fiber
              </label>     
              <input type="text" placeholder="Enter Fiber" class="form-control" name="fiber" value="<?php if(set_value('fiber')){echo set_value('fiber');}else{ if(!empty($row->fiber)){ echo $row->fiber;}}   ?>" maxlength="50">
                 <?php echo form_error('fiber'); ?>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">
              Suger
              </label>     
              <input type="text" placeholder="Enter suger" class="form-control" name="suger" value="<?php if(set_value('suger')){echo set_value('suger');}else{ if(!empty($row->suger)){ echo $row->suger;}}   ?>" maxlength="50">
                 <?php echo form_error('suger'); ?>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">
                Description
              </label> 
              <textarea placeholder="Food Item Description" class="form-control" name="description"><?php if(set_value('description')){echo set_value('description');}elseif(!empty($row->description)){ echo $row->description;}   ?></textarea>
              <?php echo form_error('description'); ?>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">
                Preparation
              </label> 
              <textarea placeholder="Food Item Preparation" class="form-control" name="preparation"><?php if(set_value('preparation')){echo set_value('preparation');}elseif(!empty($row->preparation)){ echo $row->preparation;}   ?></textarea>
              <?php echo form_error('preparation'); ?>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">
                Healthiness
              </label> 
              <textarea placeholder="Food Item Healthiness" class="form-control" name="healthiness"><?php if(set_value('healthiness')){echo set_value('healthiness');}elseif(!empty($row->healthiness)){ echo $row->healthiness;}   ?></textarea>
              <?php echo form_error('healthiness'); ?>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">
              Food Item Pic
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