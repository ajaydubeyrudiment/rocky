<section class="content-header">
  <h1>
    Change Password        
  </h1>
  <ol class="breadcrumb">
    <li>
      <a href="<?php echo ADMIN_URL; ?>">
        <i class="fa fa-dashboard"></i> Dashboard
      </a>
    </li>
    <li class="active">Change Password</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
  <div class="row">
    <!-- left column -->
    <div class="col-md-6">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header with-border">              
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form action="" method="post" enctype="multipart/form-data">
          <div class="box-body">
            <div class="form-group">
              <label for="exampleInputEmail1">
                Old Password
              </label>                  
              <input type="password" placeholder="Old Password" class="form-control" name="oldpassword" value="">
                 <?php echo form_error('oldpassword'); ?>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">
               New Password
              </label>                  
              <input type="password" placeholder="New Password" class="form-control" name="newpassword" value="">
                <?php echo form_error('newpassword'); ?>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">
                Confirm Password
              </label>                  
              <input type="password" placeholder="Confirm Password" class="form-control" name="confpassword" value="">
                <?php echo form_error('confpassword'); ?>
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