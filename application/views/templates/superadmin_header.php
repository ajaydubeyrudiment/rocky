<?php 
$segment1   =   $this->uri->segment(2);
$segment2   =   $this->uri->segment(3);
if(!empty($segment2)){
  $urlSegment = strtolower($segment1)."/".strtolower($segment2);
}else{
  $urlSegment = strtolower($segment1);
}
$site_title = site_info('site_title');
$admin_type = admin_type();
$admin_text = 'Sub admin';
if($admin_type==1) $admin_text = 'Developer admin';
if($admin_type==2) $admin_text = 'Admin';
$main_modil_match  = str_replace('/index', '', $urlSegment);
$main_modil_matchs = explode('/', $main_modil_match);
$main_module_param = end($main_modil_matchs);
$all_module        = get_admin_all_modules();
if($main_module_param=='post'){
  //echo $main_module_param.'type='.$this->input->get('type');
  if(!in_array($main_module_param.'?type='.$this->input->get('type'), $all_module)){
    redirect(base_url('superadmin/superadmin/dashboard'));
  }
}else if(!in_array($main_module_param, $all_module)){
    redirect(base_url('superadmin/superadmin/dashboard'));  
}

//echo $urlSegment; exit();
switch($urlSegment){      
  case 'superadmin/profile':
    $dashboardAct       = 'active';         
    $title              = 'Profile  | '.$site_title;
    break; 
  case 'superadmin/change_password':
    $dashboardAct       = 'active';         
    $title              = 'Change Password  | '.$site_title;
    break;  
  case 'height':
    $heightsMainAct     = $heightlist = 'active';  
    $title              = 'Height List  | '.$site_title;
    break;
  case 'height/index':
    $heightsMainAct     = $heightlist  = 'active';  
    $title              = 'Height List  | '.$site_title;
    break;
  case 'height/addheight':
    $heightsMainAct     = $addHeight = 'active';
    $title              = 'Add Height | '.$site_title;
    break; 
  case 'subscription':
    $plansMainAct     = $planlist = 'active';
    $title            = 'Subscription  List  | '.$site_title;
    break;
  case 'subscription/index':
    $plansMainAct     = $planlist = 'active'; 
    $title            = 'Subscription  List  | '.$site_title;
    break;
  case 'subscription/addsubscription':
    $plansMainAct     = $addPlan = 'active'; 
    $title            = 'Add Subscription  | '.$site_title;
    break; 
  case 'serviceplan':
    $servicePlanMainAct = $servicePlanlist    = 'active';
    $title              = 'Plan List  | '.$site_title;
    break;
  case 'serviceplan/index':
    $servicePlanMainAct = $servicePlanlist = 'active';    
    $title              = 'Plan List  | '.$site_title;
    break;
  case 'serviceplan/addserviceplan':
    $servicePlanMainAct = $addServicePlan = 'active';                
    $title              = 'Add Plan | '.$site_title;
    break; 
  case 'customer':        
    $usersMainAct         = 'active';         
    $title                = 'Users List  | '.$site_title;
    break;
  case 'customer/index':        
    $usersMainAct         = 'active';         
    $title                = 'Users List  | '.$site_title;
    break;
  case 'post':        
    $postMainAct          = 'active';         
    $title                = 'Blog, Image, Recipe List  | '.$site_title;
    break;
  case 'serviceplan/addplancategory':
    $servicePlanCatMainAct =  $addPlanCategory = 'active';        
    $title                 = 'Add Category | '.$site_title;
    break; 
  case 'serviceplan/plancategory':
    $servicePlanCatMainAct =  $plancategory = 'active';        
    $title                 = 'Category List | '.$site_title;
    break;  
  case 'serviceplan/plansubcategory':
    $servicePlanSubcatMainAct =  $planSubCategory    = "active";
    $title                    = 'Sub Category List | '.$site_title;
    break; 
  case 'serviceplan/addplansubcategory':
    $servicePlanSubcatMainAct =  $addPlanSubCategory = "active";
    $title                    = 'Add Sub Category | '.$site_title;
    break;
  case 'serviceplan/workout_exercise':
    $servicePlanWorkOutMainAct =  $workout_exercise    = "active";
    $title                     = 'Workout Exercise List | '.$site_title;
    break; 
  case 'serviceplan/add_workout_exercise':
    $servicePlanWorkOutMainAct =  $add_workout_exercise = "active";
    $title                     = 'Add Workout Exercise | '.$site_title;
    break; 
  case 'serviceplan/diet_plan_food':
    $diet_plan_foodAct =  $diet_plan_food = "active";
    $title             = 'Diet Item  List | '.$site_title;
    break;
  case 'serviceplan/diet_plan_food':
    $diet_plan_foodAct =  $diet_plan_food    = "active";
    $title             = 'Diet Item  List | '.$site_title;
    break; 
  case 'serviceplan/add_diet_plan_food':
    $diet_plan_foodAct =  $add_diet_plan_food = "active";
    $title             = 'Add Diet Item | '.$site_title;
    break;  
  case 'subadmin/addsubadmin':
    $subadminAct =  $addSubadmin = "active";
    $title             = 'Add Diet Item | '.$site_title;
    break; 
  case 'subadmin':
    $subadminAct =  $subadminlist = "active";
    $title             = 'Add Diet Item | '.$site_title;
    break; 
  default : 
    $dashboardAct       = 'active';
    $title              = $admin_text.' | Dashboard';
} 
$adminInfo = get_admin_info(superadmin_id());
?> 
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $title; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo  ADMIN_THEAM_PATH ;?>bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo  ADMIN_THEAM_PATH ;?>bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo  ADMIN_THEAM_PATH ;?>bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo  ADMIN_THEAM_PATH ;?>dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins -->
  <link rel="stylesheet" href="<?php echo  ADMIN_THEAM_PATH ;?>dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="<?php echo  ADMIN_THEAM_PATH ;?>css/custom.css">
  <link rel="stylesheet" href="<?php echo  ADMIN_THEAM_PATH ;?>css/alertify.core.css"> 
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <!-- datepicker css -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body class="hold-transition skin-blue  sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="<?php echo ADMIN_URL.'superadmin/dashboard';?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>ROKY</b></span>
      <!-- logo for regular state and mobile devices -->
      <!-- <span class="logo-lg"><b>Admin</b>LTE</span> -->
      <?php
        $logo_path = site_info('logo_url');
        if(!empty($logo_path)&&file_exists($logo_path)) {
          echo '<img src="'.base_url().$logo_path.'" class="admin-logo">';
        }else{
          echo '<span class="logo-mini">'.$site_title.'</span>';
          echo '<span class="logo-lg">'.$site_title.'</span>';
        }
      ?>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">                   
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <?php 
              if(!empty($adminInfo->image)&&file_exists('assets/uploads/admin/images/'.$adminInfo->image)){
                $profilePic = base_url('assets/uploads/admin/images/'.$adminInfo->image);
              }else{
                $pics = site_info('default_user_pic');
                if(!empty($pics)&&file_exists($pics)){
                  $profilePic = base_url().$pics;
                } 
              }
              if(!empty($profilePic)){
                echo '<img src="'.$profilePic.'" class="user-image" alt="User Image">';
              } ?>              
              <span class="hidden-xs">
                <?php  
                  if(!empty($adminInfo->first_name)) 
                    echo ucwords($adminInfo->first_name);  
                  if(!empty($adminInfo->last_name)) 
                    echo ' '.ucwords($adminInfo->last_name);
                ?>
              </span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-footer">
                <div class="">
                  <ul class="header-right_menu">
                    <li>
                      <a href="<?php echo ADMIN_URL.'superadmin/profile'; ?>">
                       <i class="fa fa-user" aria-hidden="true"></i> 
                        Profile
                      </a> 
                    </li>
                    <li>               
                      <a href="<?php echo ADMIN_URL.'superadmin/change_password'; ?>">
                       <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Change Password
                      </a> 
                    </li>
                    <li>               
                      <a href="<?php echo ADMIN_URL.'superadmin/logout'; ?>">
                       <i class="fa fa-sign-out" aria-hidden="true"></i> Sign out
                      </a>
                    </li>
                </div>
              </li>
            </ul>
          </li>
          <?php if($admin_type==1){ ?>
            <!-- Control Sidebar Toggle Button -->
            <li>
              <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
            </li>
          <?php }?>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <?php 
            if(!empty($profilePic)){?>
              <img src="<?php echo  $profilePic ;?>" class="img-circle" alt="User Image">
            <?php }?>
        </div>
        <div class="pull-left info">
          <p>
            <?php  
              if(!empty($adminInfo->first_name)) echo ucwords($adminInfo->first_name);  
              if(!empty($adminInfo->last_name)) echo ' '.ucwords($adminInfo->last_name);
            ?>
          </p>
          <a href="javascript:void(0);">
            <i class="fa fa-circle text-success"></i> 
            Online
          </a>
        </div>
      </div>      
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">    
        <li class="<?php echo !empty($dashboardAct)?$dashboardAct:''; ?>">
          <a href="<?php echo ADMIN_URL.'superadmin/dashboard';?>">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>            
          </a>
        </li> 
        <?php 
        if(in_array('addsubadmin', $all_module)||in_array('subadmin', $all_module)){?> 
          <li class="treeview <?php echo !empty($subadminAct)?$subadminAct:''; ?>">
            <a href="javascript:void(0);">
              <i class="fa fa-th" aria-hidden="true"></i> <span>Subadmin</span>
              <span class="pull-right-container">            
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu"> 
              <?php if(in_array('addsubadmin', $all_module)){?>
              <li class="treeview <?php echo !empty($addSubadmin)?$addSubadmin:''; ?>">
                <a href="javascript:void(0);" onclick="go_link('<?php echo base_url('superadmin/subadmin/addSubadmin'); ?>');">
                  <i class="fa fa-plus" aria-hidden="true"></i>
                  Add Subadmin
                </a>
              </li>
              <?php } if(in_array('subadmin', $all_module)){?>
              <li class="treeview <?php echo !empty($subadminlist)?$subadminlist:''; ?>">
                <a href="javascript:void(0);" onclick="go_link('<?php echo base_url('superadmin/subadmin'); ?>');">
                  <i class="fa fa-th" aria-hidden="true"></i> 
                  Subadmin List
                </a>
              </li>
              <?php }?>
            </ul>
          </li>
        <?php }?>
        <?php 
        if(in_array('customer', $all_module)){?>
          <li class="treeview <?php echo !empty($usersMainAct)?$usersMainAct:''; ?>">
            <a href="javascript:void(0);" onclick="go_link('<?php echo base_url('superadmin/customer'); ?>');">
              <i class="fa fa-users" aria-hidden="true"></i> <span>Users</span>
            </a>
          </li>  
        <?php }?>
         <?php 
        if(in_array('addsubscription', $all_module)||in_array('subscription', $all_module)){?> 
          <li class="treeview <?php echo !empty($plansMainAct)?$plansMainAct:''; ?>">
            <a href="javascript:void(0);">
              <i class="fa fa-th" aria-hidden="true"></i> <span>Subscriptions</span>
              <span class="pull-right-container">            
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <?php 
              if(in_array('addsubscription', $all_module)){?> 
                <li class="treeview <?php echo !empty($addPlan)?$addPlan:''; ?>">
                  <a href="javascript:void(0);" onclick="go_link('<?php echo base_url('superadmin/subscription/addSubscription'); ?>');">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    Add Subscription 
                  </a>
                </li>
              <?php }?>
              <?php 
              if(in_array('subscription', $all_module)){?> 
                <li class="treeview <?php echo !empty($planlist)?$planlist:''; ?>">
                  <a href="javascript:void(0);" onclick="go_link('<?php echo base_url('superadmin/subscription'); ?>');">
                    <i class="fa fa-th" aria-hidden="true"></i> 
                    Subscription  List
                  </a>
                </li>
               <?php }?>
            </ul>
          </li>
        <?php }?>
        <?php 
        if(in_array('addplancategory', $all_module)||in_array('plancategory', $all_module)){?> 
          <li class="treeview <?php echo !empty($servicePlanCatMainAct)?$servicePlanCatMainAct:''; ?>">
            <a href="javascript:void(0);">
              <i class="fa fa-th" aria-hidden="true"></i> <span>Plan Categorys</span>
              <span class="pull-right-container">            
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <?php 
              if(in_array('addplancategory', $all_module)){?> 
                <li class="treeview <?php echo !empty($addPlanCategory)?$addPlanCategory:''; ?>">
                  <a href="javascript:void(0);" onclick="go_link('<?php echo base_url('superadmin/servicePlan/addPlanCategory'); ?>');">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    Add Category
                  </a>
                </li>
              <?php }?>
              <?php 
              if(in_array('plancategory', $all_module)){?> 
                <li class="treeview <?php echo !empty($plancategory)?$plancategory:''; ?>">
                  <a href="javascript:void(0);" onclick="go_link('<?php echo base_url('superadmin/servicePlan/planCategory'); ?>');">
                    <i class="fa fa-th" aria-hidden="true"></i> 
                    Category List
                  </a>
                </li>
              <?php }?>
            </ul>
          </li>
        <?php }?>
         <?php 
        if(in_array('addplansubcategory', $all_module)||in_array('plansubcategory', $all_module)){?> 
        <li class="treeview <?php echo !empty($servicePlanSubcatMainAct)?$servicePlanSubcatMainAct:''; ?>">
          <a href="javascript:void(0);">
            <i class="fa fa-th" aria-hidden="true"></i> <span>Plan Sub Categorys</span>
            <span class="pull-right-container">            
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php 
            if(in_array('addplansubcategory', $all_module)){?> 
              <li class="treeview <?php echo !empty($addPlanSubCategory)?$addPlanSubCategory:''; ?>">
                <a href="javascript:void(0);" onclick="go_link('<?php echo base_url('superadmin/servicePlan/addPlanSubCategory'); ?>');">
                  <i class="fa fa-plus" aria-hidden="true"></i>
                  Add Sub Category
                </a>
              </li>
            <?php }?>
            <?php 
            if(in_array('plansubcategory', $all_module)){?> 
              <li class="treeview <?php echo !empty($planSubCategory)?$planSubCategory:''; ?>">
                <a href="javascript:void(0);" onclick="go_link('<?php echo base_url('superadmin/servicePlan/planSubCategory'); ?>');">
                  <i class="fa fa-th" aria-hidden="true"></i> 
                  Sub Category List
                </a>
              </li>
            <?php }?>   
          </ul>
        </li>   
        <?php }?>   
        <?php 
        if(in_array('add_workout_exercise', $all_module)||in_array('workout_exercise', $all_module)){?>   
          <li class="treeview <?php echo !empty($servicePlanWorkOutMainAct)?$servicePlanWorkOutMainAct:''; ?>">
            <a href="javascript:void(0);">
              <img src="<?php echo base_url('assets/admin/img/workout_plan.png');?>" style="width: 23px; margin-left: -2px;"> <span>Workout Exercises </span>
              <span class="pull-right-container">            
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">   
          <?php 
            if(in_array('add_workout_exercise', $all_module)){?>          
              <li class="treeview <?php echo !empty($add_workout_exercise)?$add_workout_exercise:''; ?>">
                <a href="javascript:void(0);" onclick="go_link('<?php echo base_url('superadmin/servicePlan/add_workout_exercise'); ?>');">
                  <i class="fa fa-plus" aria-hidden="true"></i>
                  Add Workout Exercise
                </a>
              </li>
            <?php }?>
            <?php 
            if(in_array('workout_exercise', $all_module)){?> 
              <li class="treeview <?php echo !empty($workout_exercise)?$workout_exercise:''; ?>">
                <a href="javascript:void(0);" onclick="go_link('<?php echo base_url('superadmin/servicePlan/workout_exercise'); ?>');">
                  <i class="fa fa-th" aria-hidden="true"></i> 
                  Workout Exercise List
                </a>
              </li>
            <?php }?>
          </ul>
        </li> 
        <?php }?>
        <?php 
        if(in_array('add_diet_plan_food', $all_module)||in_array('diet_plan_food', $all_module)){?> 
        <li class="treeview <?php echo !empty($diet_plan_foodAct)?$diet_plan_foodAct:''; ?>">
          <a href="javascript:void(0);">
            <img src="<?php echo base_url('assets/admin/img/diet_plan.png');?>" style="width: 23px; margin-left: -2px;"> <span>Diet Items </span>
            <span class="pull-right-container">            
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php 
            if(in_array('add_diet_plan_food', $all_module)){?> 
            <li class="treeview <?php echo !empty($add_diet_plan_food)?$add_diet_plan_food:''; ?>">
              <a href="javascript:void(0);" onclick="go_link('<?php echo base_url('superadmin/servicePlan/add_diet_plan_food'); ?>');">
                <i class="fa fa-plus" aria-hidden="true"></i>
                Add Diet Item
              </a>
            </li>
            <?php }?>
            <?php 
          if(in_array('diet_plan_food', $all_module)){?> 
            <li class="treeview <?php echo !empty($diet_plan_food)?$diet_plan_food:''; ?>">
              <a href="javascript:void(0);" onclick="go_link('<?php echo base_url('superadmin/servicePlan/diet_plan_food'); ?>');">
                <i class="fa fa-th" aria-hidden="true"></i> 
                Diet Item List
              </a>
            </li>
            <?php }?>
          </ul>
        </li>
        <?php }?>
        <?php 
        if(in_array('post?type=blog', $all_module)||in_array('post?type=recipe', $all_module)||in_array('post?type=image', $all_module)){?> 
        <li class="treeview <?php echo !empty($postMainAct)?$postMainAct:''; ?>">
          <a href="javascript:void(0);">
            <i class="fa fa-th" aria-hidden="true"></i> <span>Posted Content</span>
            <span class="pull-right-container">            
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php 
            if(in_array('post?type=blog', $all_module)){?> 
              <li class="treeview <?php echo ($this->input->get('type')&&$this->input->get('type')=='blog')?'active':''; ?>">
                <a href="javascript:void(0);" onclick="go_link('<?php echo base_url('superadmin/post?type=blog'); ?>');">
                  <img src="<?php echo base_url('assets/admin/img/blogs.png'); ?>" width="15">
                  Blog List
                </a>
              </li>
            <?php }?>
            <?php 
            if(in_array('post?type=image', $all_module)){?> 
              <li class="treeview <?php echo ($this->input->get('type')&&$this->input->get('type')=='image')?'active':''; ?>">
                <a href="javascript:void(0);" onclick="go_link('<?php echo base_url('superadmin/post?type=image'); ?>');">
                  <img src="<?php echo base_url('assets/admin/img/image.png'); ?>" width="15">
                  Image List
                </a>
              </li>
            <?php }?>
            <?php 
            if(in_array('post?type=recipe', $all_module)){?> 
              <li class="treeview <?php echo ($this->input->get('type')&&$this->input->get('type')=='recipe')?'active':''; ?>">
                <a href="javascript:void(0);" onclick="go_link('<?php echo base_url('superadmin/post?type=recipe'); ?>');">
                  <img src="<?php echo base_url('assets/admin/img/Recipes.png'); ?>" width="15">
                  Recipe List
                </a>
              </li>
            <?php }?>
          </ul>
        </li> 
        <?php }?>     
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
  <div class="content-wrapper">
    <section class="content-header">
      <div class="message_alert">
        <?php msg_alert(); ?>
      </div>
    </section>
    <div class="ajaxloader">
       <img src="<?php echo  base_url().'assets/admin/img/loading.gif'?>">
    </div>