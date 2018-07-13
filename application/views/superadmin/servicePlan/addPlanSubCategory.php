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
    <li><a href="<?php echo ADMIN_URL.'servicePlan/planSubCategory'; ?>">Subcategory List</a></li>
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
            <div class="form-group">
              <label for="exampleInputEmail1">
                Plan
              </label>   
              <select class="form-control" name="plan_id" id="selectPlan" onchange="selectPlans();">
                <option value="">Select Plan</option>
                <?php 
                if(!empty($plans)){
                  foreach ($plans as $plan) {?>
                    <option value="<?php echo !empty($plan->id)?$plan->id:''; ?>" <?php if(set_value('plan_id')&&set_value('plan_id')==$plan->id){echo 'selected';}else if(!empty($row->plan_id)&&$row->plan_id==$plan->id){ echo 'selected';} ?>>
                      <?php echo !empty($plan->title)?$plan->title:''; ?>
                    </option>
                  <?php }
                } ?>        
              </select>
              <?php echo form_error('plan_id'); ?>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">
                Category
              </label>   
              <select class="form-control" name="category_id" id="category_id">
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
              <input type="text" placeholder="Subcategory Name" class="form-control" name="title" value="<?php if(set_value('title')){echo set_value('title');}else{ if(!empty($row->title)){ echo $row->title;}}   ?>" maxlength="50">
                 <?php echo form_error('title'); ?>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">
              Subcategory Pic
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
<script type="text/javascript">
   function selectPlans() {
      var planID = $('#selectPlan').val(); 
      $.ajax({
        url:base_url+"superadmin/servicePlan/getCategorys",
        type:"get",    
        data:{'plan_id':planID},
        success: function(html){
          $('#category_id').html(html);  
        }
      });
   }
</script>