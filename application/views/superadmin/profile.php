<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    My Profile        
  </h1>
  <ol class="breadcrumb">
    <li>
      <a href="<?php echo ADMIN_URL; ?>">
        <i class="fa fa-dashboard"></i> Dashboard
      </a>
    </li>
    <li class="active">My Profile</li>
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
                First Name
              </label>   
              <?php 
                if(!empty($user->email)){
                  echo '<input type="hidden" name="oldEmail" value="'.$user->email.'">';
                }  
              ?>                
              <input type="text" placeholder="First Name" class="form-control" name="first_name" value="<?php if(set_value('first_name')){echo set_value('first_name');}else{ if(!empty($user->first_name)){ echo $user->first_name;}}   ?>" maxlength="50">
                 <?php echo form_error('first_name'); ?>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">
                Last Name
              </label>                  
              <input type="text" placeholder="Last Name" class="form-control" name="  last_name" value="<?php if(set_value('last_name')){echo set_value('last_name');}else{ if(!empty($user->last_name)){ echo $user->last_name;}}   ?>" maxlength="50">
                <?php echo form_error('last_name'); ?>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">
                Email Address
              </label>                  
               <input type="email" placeholder="Email Address" class="form-control" name="email" value="<?php if(set_value('email')){echo set_value('email');}else{ if(!empty($user->email)){ echo $user->email;}}   ?>">
                <?php echo form_error('email'); ?>
            </div>  
            <div class="form-group">
              <?php 
                if(!empty($user->image)&&file_exists('assets/uploads/admin/images/'.$user->image)){
                  $profilePic = base_url('assets/uploads/admin/images/'.$user->image);
                }else{
                  $pics = site_info('default_user_pic');
                  if(!empty($pics)&&file_exists($pics)){
                    $profilePic = base_url().$pics;
                  } 
                }
              ?>
              <img src="<?php echo $profilePic; ?>" width="150">
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">
                Profile Image
              </label>     
              <input type="file" class="form-control" id="exampleInputFile" name="user_img"  > 
                <?php 
                if(! form_error('user_img')) { ?>
                  <div class="note-msg">
                    Profile Image need to be atleast <font>100 x 100</font> pixels and maximum <font>2000 x 2000</font> pixels
                  </div>  
                <?php }else{  echo form_error('user_img'); } ?>
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