<?php 
$segment1   =   $this->uri->segment(1);
$segment2   =   $this->uri->segment(2);
if(!empty($segment2)){
    $urlSegment = strtolower($segment1)."/".$segment2;
}else{
    $urlSegment = $segment1;
}
//echo $urlSegment;exit();
 switch($urlSegment){
    case 'user/profile':
        $page_title              = 'ROKY | Profile';
        $profileAc               = 'active';
        break; 
    case 'user/dashboard':
        $page_title              = 'ROKY';
        $dashboardAc             = 'active';
        break; 
    case 'user/setting':
        $page_title              = 'ROKY | Settings';
        $dashboardAc             = 'active';
        break;
    case 'user/addRecipe':
        $page_title              = 'ROKY | Add Recipe';
        $profileAc               = 'active';
        break;
    case 'user/addBlog':
        $page_title              = 'ROKY | Add Blog';
        $profileAc               = 'active';
        break;
    case 'user/follow_request':
        $page_title              = 'ROKY | Follow Requests(3)';
        break;
    case 'user/imagePost':
        $page_title              = 'ROKY | Add Image or Video';
        $profileAc               = 'active';
        break;
    case 'user/inbox':  
        $page_title              = 'ROKY | Messages(5)';
        $inboxAc                 = 'active';
        break;
    case 'user/like':
        $page_title              = 'ROKY | Likes('.$this->developer_model->getUserLikes('counter').')';
        $likeAc                  = 'active';
        break;
    case 'user/message':
        $page_title              = 'ROKY | Conversation - John';
        break;
    case 'user/profile_follow_feed':
        $page_title              = 'ROKY | Profile';
        $profileAc               = 'active';
        break; 
    case 'user/profilePost':
        $page_title              = 'ROKY | Add Image';
        $profileAc               = 'active';
        break; 
    case 'user/setting':
        $page_title              = 'ROKY | Settings';
        break;
    case 'user/user_profile':
        $page_title              = "ROKY | John's Profile";
        $profileAc               = 'active';
        break;    
    case 'user/change_password':
        $page_title              = 'Change Password';
        $dashboardAc             = 'active';
        break; 
    case 'user/goal_set':
        $page_title              = 'ROKY | Set Goal';
        break;
    case 'user/diet_plan':
        $page_title              = 'ROKY | Diet Plan';
        $planAc                  = 'active';
        break;
    case 'user/all_diet_plan':
        $page_title              = 'ROKY | All Diet Plans';
        $planAc                  = 'active';
        break;
    case 'user/workout_plan':
        $page_title              = 'ROKY | Workout Plan';
        $planAc                  = 'active';
        break;
    case 'user/all_workout_plan':
        $page_title              = 'ROKY | All Workout Plans';
        $planAc                  = 'active';
        break;
    case 'user/create_workout_plan':
        $page_title              = 'ROKY | Create Workout Plan';
        $planAc                  = 'active';
        break;
     case 'user/create_diet_plan':
        $page_title              = 'ROKY | Create Diet Plan';
        $planAc                  = 'active';
        break;
    case 'user/metricTracker':
        $page_title              = 'ROKY | Metrics';
        $metricAc                = 'active';
        break; 
     case 'user/user_result':
        $page_title              = 'ROKY | Search Results';
        $metricAc                = 'active';
        break;        
    default :  
        $page_title              = 'ROKY';
  }
?> 
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
      <title id="page_title_bars"><?php echo $page_title ; ?></title>
      <!-- Bootstrap -->
      <link href="<?php echo  FRONT_THEAM_PATH ;?>css/bootstrap.min.css" rel="stylesheet">
      <link href="<?php echo  FRONT_THEAM_PATH ;?>css/style.css" rel="stylesheet">
      <link href="<?php echo  FRONT_THEAM_PATH ;?>css/animate.css" rel="stylesheet">
      <link rel="icon" href="<?php echo  FRONT_THEAM_PATH ;?>img/favicon.png" type="image/gif" sizes="16x16">
      <!--==font-family==-->
      <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" href="<?php echo  ADMIN_THEAM_PATH ;?>css/alertify.core.css"> 
      <!-- datepicker css -->
      <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
      <!--==font-family==-->
   </head>
  <body class="animated fadeIn">    
  <?php 
  if(user_logged_in()){
    $user = user_info();
    if(!empty($user->profile_pic)&&file_exists('assets/uploads/users/thumbnails/'.$user->profile_pic)){
      $profile_pic = base_url().'assets/uploads/users/thumbnails/'.$user->profile_pic;
    }else{
      $profile_pic = base_url('assets/front/img/roky-logo.png');
    }
    ?>
    <div class="loader_profile_left">
      <img src="<?php echo base_url('assets/front/img/loader_img.gif') ?>">
    </div>
    <div class="wrapper">
      <!--==HEADER START==-->
      <header class="header">
         <nav class="navbar navbar-default">
           <div class="container">
             <!-- Brand and toggle get grouped for better mobile display -->
             <div class="navbar-header">
               <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                 <span class="icon-bar"></span>
                 <span class="icon-bar"></span>
                 <span class="icon-bar"></span>
               </button>              
                <a href="#" class="dropdown-toggle profile_pic_a" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                 <!--  <i class="fa fa-cog profile_logo_gear fa-4x" aria-hidden="true"></i>
                  <span class="caret"></span> -->
                  <div class="profile-pic">
                    <img src="<?php echo $profile_pic;?>" id="top_header_img">
                  </div>

                </a>
                <ul class="dropdown-menu">
                  <li>
                    <a href="<?php echo base_url('user/message'); ?>">
                      Message <span class="text-right">(6)</span>
                    </a>
                  </li>
                  <li>
                    <a href="<?php echo base_url('user/like'); ?>">
                      Likes <span class="text-right">(<?php echo $this->developer_model->getUserLikes('counter');?>)</span>
                    </a>
                  </li>
                  <li>
                    <a href="<?php echo base_url('user/follow_request'); ?>">
                      Follow Request 
                      <span class="text-right" id="request_counter">
                        <?php 
                        $reqCount = get_all_count('follow_request', array('receiver_id' => user_id(), 'accepted_status'=>4)) ;
                        if($reqCount>0){ echo '('.$reqCount.')';} 
                        ?>
                      </span>
                    </a>
                  </li>                             
                  <li>
                    <a href="<?php echo base_url('user/setting'); ?>">
                      Setting
                    </a>
                  </li>              
                  <li>
                    <a href="<?php echo base_url('user/logout'); ?>">
                      Logout
                    </a>
                  </li>
                </ul>
              </div> 
             <!-- Collect the nav links, forms, and other content for toggling -->
             <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
               <ul class="nav navbar-nav head1">
                 <li class="<?php if(!empty($dashboardAc)){ echo  $dashboardAc;} ?>">
                    <a href="<?php echo base_url('user/dashboard'); ?>">
                      Home
                    </a>
                  </li>
                  <li class="<?php if(!empty($profileAc)){ echo  $profileAc;} ?>">
                    <a href="<?php echo base_url('user/profile'); ?>">
                      Profile
                    </a>
                  </li>
                 <li  class="<?php if(!empty($planAc)){ echo  $planAc;} ?>">
                  <a href="#" class="dropdown-toggle profile_pic_a" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    Plan
                  </a>
                  <ul class="dropdown-menu plans pull-left">
                     <li>
                      <a href="<?php echo base_url('user/diet_plan'); ?>">
                        Diet Plan 
                      </a>
                    </li>
                    <li>
                      <a href="<?php echo base_url('user/all_diet_plan'); ?>">
                       All Diet Plans
                      </a>
                    </li>
                    <li>
                      <a href="<?php echo base_url('user/workout_plan'); ?>">
                      Workout Plan
                      </a>
                    </li>
                    <li>
                      <a href="<?php echo base_url('user/all_workout_plan'); ?>">
                        All workout Plans
                      </a>
                    </li>
                  </ul>
                 </li>
                 <li class="<?php if(!empty($metricAc)){ echo  $metricAc;} ?>"><a href="<?php echo base_url('user/metricTracker'); ?>">Metrics</a></li>
                 <li class="<?php if(!empty($likeAc)){ echo  $likeAc;} ?>">
                    <a href="<?php echo base_url('user/like'); ?>">
                      Likes
                    </a>
                  </li>
                  <li class="<?php if(!empty($inboxAc)){ echo  $inboxAc;} ?>">
                      <a href="<?php echo base_url('user/inbox'); ?>">Inbox</a>
                  </li>
               </ul>              
               <ul class="nav navbar-nav navbar-right head2">
                <form class="navbar-form navbar-left" action="<?php echo base_url('home/search_result') ?>" method="get">
                 <div class="form-group">
                   <!-- <input type="text" class="form-control" onkeyup="search_result();" id="search_bar" placeholder="Search"> -->
                   <input type="text" class="form-control" name="title" placeholder="Search" value="<?php if($this->input->get('title')) echo $this->input->get('title'); ?>">
                 </div>
                 <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
               </form>
               </ul>
             </div><!-- /.navbar-collapse -->
           </div><!-- /.container-fluid -->
         </nav>  
      </header>
   <?php }?>
<style type="text/css">
  body .wrapper .feed .feed_right .goal_setter .previous_diet .linksS {
    background-color: #1bbdb0 !important;
    padding: 10px !important;
    color: #fff !important;
    margin: 15px 0px !important;
    min-height: 146px !important;
    overflow: auto;
    height: 146px;
    text-decoration: none;
  }  
  .followsss {margin: 15px 0px;border: 1px solid #1bbdb0;padding: 0px !important;text-decoration: none;cursor: pointer;}
  .followsss .following-settingss{ display: block; padding: 10px;text-decoration: none;}
  .feed{ display: block;}
</style>
  
 