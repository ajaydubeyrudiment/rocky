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
    <li><a href="<?php echo ADMIN_URL.'height'; ?>">Height List</a></li>
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
                Height cm
              </label>     
              <input type="text" placeholder="Height cm" class="form-control" name="hieght_cm" value="<?php if(set_value('Height')){echo set_value('hieght_cm');}else{ if(!empty($row->hieght_cm)){ echo $row->hieght_cm;}}   ?>" maxlength="50">
                 <?php echo form_error('hieght_cm'); ?>
            </div> 
            <div class="form-group">
              <label for="exampleInputEmail1">
                Height feet & inch
              </label>     
              <input type="text" placeholder="height feet & inch" class="form-control" name="height_title" value="<?php if(set_value('height_title')){echo set_value('height_title');}else{ if(!empty($row->height_title)){ echo $row->height_title;}}   ?>" maxlength="50">
                 <?php echo form_error('height_title'); ?>
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