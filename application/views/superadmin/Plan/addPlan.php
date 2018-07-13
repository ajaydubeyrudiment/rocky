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
    <li><a href="<?php echo ADMIN_URL.'subscription'; ?>">Subscription  List</a></li>
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
                Subscription  Title
              </label>     
              <input type="text" placeholder="Subscription  Title" class="form-control" name="plan_title" value="<?php if(set_value('plan_title')){echo set_value('plan_title');}else{ if(!empty($row->plan_title)){ echo $row->plan_title;}}   ?>" maxlength="50">
                 <?php echo form_error('plan_title'); ?>
            </div> 
            <div class="form-group">
              <label for="exampleInputEmail1">
                Price
              </label>     
              <input type="text" placeholder="Subscription  Price" class="form-control" name="amount" value="<?php if(set_value('amount')){echo set_value('amount');}else{ if(!empty($row->amount)){ echo $row->amount;}}   ?>" maxlength="50">
                 <?php echo form_error('amount'); ?>
            </div>  
            <div class="form-group">
              <label for="exampleInputEmail1">
                Days
              </label>     
              <input type="text" placeholder="Subscription  Days" class="form-control" name="days" value="<?php if(set_value('days')){echo set_value('days');}else{ if(!empty($row->days)){ echo $row->days;}}   ?>" maxlength="50">
                 <?php echo form_error('days'); ?>
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