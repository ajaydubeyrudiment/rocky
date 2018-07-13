<!-- Content Header (Page header) -->
<style type="text/css">
  .list-inline {
    padding-left: 0;
    margin-left: -5px;
    list-style: none;
    border: 1px solid #dad7d7;
    padding: 10px 0px 10px 10px;
    border-radius: 5px;
  }
</style>
<section class="content-header">
  <h1>
   <?php echo !empty($type)?$type:''; ?>       
  </h1>
  <ol class="breadcrumb">
    <li>
      <a href="<?php echo ADMIN_URL; ?>">
        <i class="fa fa-dashboard"></i> Dashboard
      </a>
    </li>
    <li>
      <a href="<?php echo ADMIN_URL; ?>subadmin">
        <i class="fa fa-dashboard"></i> Subadmin List
      </a>
    </li>
    <li class="active"><?php echo !empty($type)?$type:''; ?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
  <div class="row">
  <form action="" method="post" enctype="multipart/form-data">
    <!-- left column -->
    <div class="col-md-6">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header with-border">              
        </div>
        <!-- /.box-header -->
        <!-- form start -->
          <div class="box-body">
            <div class="form-group">
              <label for="exampleInputEmail1">
                First Name
              </label>   
              <?php 
                if(!empty($user->email)){
                  echo '<input type="hidden" name="oldEmail" value="'.$user->email.'">';
                }  
                if(!empty($user->id)){
                  echo '<input type="hidden" name="id" value="'.$user->id.'">';
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
               <input type="text" placeholder="Email Address" class="form-control" name="email" value="<?php if(set_value('email')){echo set_value('email');}else{ if(!empty($user->email)){ echo $user->email;}}   ?>">
                <?php echo form_error('email'); ?>
            </div>  
            <?php 
            if(empty($user)){?>
              <div class="form-group">
              <label for="exampleInputEmail1">
                Password
              </label>                  
               <input type="password" placeholder="Password" class="form-control" name="password" >
                <?php echo form_error('password'); ?>
            </div> 
            <div class="form-group">
              <label for="exampleInputEmail1">
                Confirm Password
              </label>                  
               <input type="password" placeholder="Confirm Password" class="form-control" name="confirm_password">
                <?php echo form_error('confirm_password'); ?>
            </div> 
            <?php } ?>
            <div class="form-group">
              <?php 
                if(!empty($user->image)&&file_exists('assets/uploads/admin/images/'.$user->image)){
                  $profilePic = base_url('assets/uploads/admin/images/'.$user->image);
                  echo '<img src="'.$profilePic.'" width="150">';
                }
              ?>              
            </div>    
                           
            <input type="hidden" name="submit" value="submit">
          </div>
          <!-- /.box-body -->
          
      </div>
      <!-- /.box -->
    </div>
    <!--/.col (left) -->
    <?php 
    $userModule = array();
    if(!empty($user->modules)){
      $userModule = explode(',', $user->modules);
    }
    ?>
    <div class="col-md-6">
      <div class="form-group">
        <label style="display: block;" for="exampleInputEmail1">
         Accessibility 
        </label>
         <input type="hidden"  value="dashboard"  name="modules[]" checked="checked">
        <div class="col-md-4 "> 
          <ul class="main_module ulli list-inline">  
            <input type="checkbox" name="modules[]" id="upper_subadmin" onclick="upperClick('subadmin');" <?php if(in_array('main_subadmin', $userModule)){ echo 'checked';} ?> value="main_subadmin">                     
            Subadmin 
            <ul class="secound newchild_list">                              
              <li>
                <input type="checkbox" <?php if(in_array('addsubadmin', $userModule)){ echo 'checked';} ?> class="lower_subadmin" value="addsubadmin" onclick="lowerClick('subadmin');" name="modules[]">
                Add Subadmin
              </li>
              <li>
                <input type="checkbox" class="lower_subadmin" <?php if(in_array('subadmin', $userModule)){ echo 'checked';} ?> value="subadmin" onclick="lowerClick('subadmin');" name="modules[]">
                Subadmin List
              </li>
            </ul>
          </ul>
        </div>  
        <div class="col-md-4 "> 
          <ul class="main_module ulli list-inline">  
            <input type="checkbox" name="modules[]" id="upper_customer" <?php if(in_array('main_customer', $userModule)){ echo 'checked';} ?> onclick="upperClick('customer');" value="main_customer">                     
            Customers
            <ul class="secound newchild_list"> 
              <li>
                <input type="checkbox" class="lower_customer" <?php if(in_array('customer', $userModule)){ echo 'checked';} ?> value="customer" onclick="lowerClick('customer');" name="modules[]">
                Customers List
              </li>
              <li>
                <input type="checkbox" class="lower_customer" <?php if(in_array('influencers', $userModule)){ echo 'checked';} ?> value="influencers" onclick="lowerClick('customer');" name="modules[]">
                Influencers List
              </li>
            </ul>
          </ul>
        </div> 
        <div class="col-md-4 "> 
          <ul class="main_module ulli list-inline">  
            <input type="checkbox" name="modules[]" id="upper_subscription" onclick="upperClick('subscription');"  value="main_subscription" <?php if(in_array('main_subscription', $userModule)){ echo 'checked';} ?>>                     
            Subscriptions
            <ul class="secound newchild_list"> 
              <li>
                <input type="checkbox" class="lower_subscription" value="addsubscription" onclick="lowerClick('subscription');" name="modules[]" <?php if(in_array('addsubscription', $userModule)){ echo 'checked';} ?>>
                Add Subscription
              </li>
              <li>
                <input type="checkbox" <?php if(in_array('subscription', $userModule)){ echo 'checked';} ?> class="lower_subscription" value="subscription" onclick="lowerClick('subscription');" name="modules[]">
                Subscription List
              </li>
            </ul>
          </ul>
        </div> 
        <div class="col-md-4 "> 
          <ul class="main_module ulli list-inline">  
            <input type="checkbox" name="modules[]" <?php if(in_array('main_plancategory', $userModule)){ echo 'checked';} ?> id="upper_plancategory" onclick="upperClick('plancategory');"  value="main_plancategory">                     
            Plan Category
            <ul class="secound newchild_list"> 
              <li>
                <input type="checkbox" class="lower_plancategory" <?php if(in_array('main_plancategory', $userModule)){ echo 'checked';} ?> value="addplancategory" onclick="lowerClick('addplancategory');" name="modules[]">
                Add Plan Category
              </li>
              <li>
                <input type="checkbox" class="lower_plancategory" <?php if(in_array('main_plancategory', $userModule)){ echo 'checked';} ?> value="plancategory" onclick="lowerClick('plancategory');" name="modules[]">
                Subscription List
              </li>
            </ul>
          </ul>
        </div> 
        <div class="col-md-4 "> 
          <ul class="main_module ulli list-inline">  
            <input type="checkbox" name="modules[]" id="upper_plansubcategory" onclick="upperClick('plansubcategory');" <?php if(in_array('main_plansubcategory', $userModule)){ echo 'checked';} ?> value="main_plansubcategory">                     
            Plan Sub Category
            <ul class="secound newchild_list"> 
              <li>
                <input type="checkbox" class="lower_plansubcategory"  <?php if(in_array('addplansubcategory', $userModule)){ echo 'checked';} ?> value="addplansubcategory" onclick="lowerClick('plansubcategory');" name="modules[]">
                Add Sub Category
              </li>
              <li>
                <input type="checkbox" class="lower_plansubcategory" <?php if(in_array('plansubcategory', $userModule)){ echo 'checked';} ?> value="plansubcategory" onclick="lowerClick('plansubcategory');" name="modules[]">
                Sub Category List
              </li>
            </ul>
          </ul>
        </div>  
        <div class="col-md-4 "> 
          <ul class="main_module ulli list-inline">  
            <input type="checkbox" name="modules[]" id="upper_workout_exercise" onclick="upperClick('workout_exercise');" <?php if(in_array('main_workout_exercise', $userModule)){ echo 'checked';} ?> value="main_workout_exercise">                     
            Workout Exercise
            <ul class="secound newchild_list"> 
              <li>
                <input type="checkbox" class="lower_workout_exercise" value="add_workout_exercise" onclick="lowerClick('workout_exercise');" <?php if(in_array('add_workout_exercise', $userModule)){ echo 'checked';} ?> name="modules[]">
               Add workout exercise
              </li>
              <li>
                <input type="checkbox" class="lower_workout_exercise" value="workout_exercise" onclick="lowerClick('workout_exercise');" <?php if(in_array('workout_exercise', $userModule)){ echo 'checked';} ?> name="modules[]">
                Workout Exercise List
              </li>
            </ul>
          </ul>
        </div>  
        <div class="col-md-4 "> 
          <ul class="main_module ulli list-inline">  
            <input type="checkbox" name="modules[]" id="upper_diet_plan_food" onclick="upperClick('diet_plan_food');" <?php if(in_array('main_diet_plan_food', $userModule)){ echo 'checked';} ?>  value="main_diet_plan_food">                     
            Workout Exercise
            <ul class="secound newchild_list"> 
              <li>
                <input type="checkbox" class="lower_diet_plan_food" <?php if(in_array('add_diet_plan_food', $userModule)){ echo 'checked';} ?> value="add_diet_plan_food" onclick="lowerClick('diet_plan_food');" name="modules[]">
               Add Diet Food
              </li>
              <li>
                <input type="checkbox" class="lower_diet_plan_food" <?php if(in_array('diet_plan_food', $userModule)){ echo 'checked';} ?> value="diet_plan_food" onclick="lowerClick('diet_plan_food');" name="modules[]">
                Diet Food List
              </li>
            </ul>
          </ul>
        </div> 
        <div class="col-md-4"> 
          <ul class="main_module ulli list-inline">  
            <input type="checkbox" name="modules[]" id="upper_post" onclick="upperClick('post');"  value="main_post" <?php if(in_array('main_post', $userModule)){ echo 'checked';} ?>>                     
            Posted Content
            <ul class="secound newchild_list"> 
              <li>
                <input type="checkbox" class="lower_post"  <?php if(in_array('post?type=blog', $userModule)){ echo 'checked';} ?> value="post?type=blog" onclick="lowerClick('post');" name="modules[]">
               Blog List
              </li>
              <li>
                <input type="checkbox" class="lower_post"  <?php if(in_array('post?type=image', $userModule)){ echo 'checked';} ?> value="post?type=image" onclick="lowerClick('post');" name="modules[]">
                Image List
              </li>
              <li>
                <input type="checkbox" class="lower_post"  <?php if(in_array('post?type=recipe', $userModule)){ echo 'checked';} ?> value="post?type=recipe" onclick="lowerClick('post');" name="modules[]">
                Recipe List
              </li>
            </ul>
          </ul>
        </div>           
      </div>
    </div>
    <div class="clearfix"></div>
    <br/>
        <div class="box-footer">
            <button style="text-align: center;display: block;margin: 0em auto;padding: 9px 19px;" type="submit" class="btn btn-primary"><?php echo !empty($type)?$type:''; ?></button>
          </div>
    </form>

  </div>
  <!-- /.row -->
</section>   