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
    <li><a href="<?php echo ADMIN_URL.'plan'; ?>">Plan List</a></li>
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
                Plan Title
              </label>     
              <input type="text" placeholder="Plan Title" class="form-control" name="title" value="<?php if(set_value('title')){echo set_value('title');}else{ if(!empty($row->title)){ echo $row->title;}}   ?>" maxlength="50">
                 <?php echo form_error('title'); ?>
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