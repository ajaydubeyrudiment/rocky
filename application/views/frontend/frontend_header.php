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
        $page_title              = 'Roky | User Dashboard';
        break; 
    case 'user/dashboard':
        $page_title              = 'Roky | User Dashboard';
        break; 
    case 'user/setting':
        $page_title              = 'Roky | Settings';
        break;
    case 'user/addRecipe':
        $page_title              = 'Roky | Add Recipe';
        break;
    case 'user/addBlog':
        $page_title              = 'Roky | Add Blog';
        break;
    case 'user/follow_request':
        $page_title              = 'Roky | Follow Requests(3)';
        break;
    case 'user/goal_setter':
        $page_title              = 'Roky | Goal Setter';
        break;
    case 'user/imagePost':
        $page_title              = 'Roky | Add Image or Video';
        break;
    case 'user/inbox':
        $page_title              = 'Roky | Messages(5)';
        break;
    case 'user/like':
        $page_title              = 'Roky | Likes(5)';
        break;
    case 'user/message':
        $page_title              = 'Roky | Conversation - John';
        break;
    case 'user/profile_follow_feed':
        $page_title              = 'Roky | Profile';
        break; 
    case 'user/profilePost':
        $page_title              = 'Roky | Add Image';
        break; 
    case 'user/setting':
        $page_title              = 'Roky | Settings';
        break;
    case 'user/user_profile':
        $page_title              = "Roky | John's Profile";
        break;
    case 'user/user_profile':
        $page_title              = "Roky | Goal Setter";
        break;
    case 'user/change_password':
        $page_title              = 'Change Password';
        break; 
    default :  
        $page_title              = 'Roky | Sign Up';
  }
?> 
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
      <title><?php echo $page_title ; ?></title>
      <!-- Bootstrap -->
      <link href="<?php echo  FRONT_THEAM_PATH ;?>css/bootstrap.min.css" rel="stylesheet">
      <link href="<?php echo  FRONT_THEAM_PATH ;?>css/style.css" rel="stylesheet">
      <link href="<?php echo  FRONT_THEAM_PATH ;?>css/animated.css" rel="stylesheet">
      <!--==font-family==-->
      <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
      <!-- datepicker css -->
      <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
      <!--==font-family==-->
   </head>
  <body>
  <?php 
  if(user_logged_in()){
    $user = user_info();
    if(!empty($user->profile_pic)&&file_exists('assets/uploads/users/thumbnails/'.$user->profile_pic)){
      $profile_pic = base_url().'assets/uploads/users/thumbnails/'.$user->profile_pic;
    }else{
      $pics = site_info('default_user_pic');
      if(!empty($pics)&&file_exists($pics)){
        $profile_pic = base_url().$pics;
      } 
    }
    ?>
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
                  <img width="50" height="50" class="img-circle" src="<?php echo  $profile_pic ;?>" id="header_profile_pic"> 
                  <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                  <li>
                    <a href="<?php echo base_url('user/message'); ?>">
                      Message <span class="text-right">(6)</span>
                    </a>
                  </li>
                  <li>
                    <a href="<?php echo base_url('user/like'); ?>">
                      Likes <span class="text-right">(34)</span>
                    </a>
                  </li>
                  <li>
                    <a href="<?php echo base_url('user/follow_request'); ?>">
                      Follow Request <span class="text-right">(4)</span>
                    </a>
                  </li>                  
                  <li>
                    <a href="<?php echo base_url('user/setting'); ?>">
                      Setting
                    </a>
                  </li>
                  <li>
                    <a href="<?php echo base_url('user/addRecipe'); ?>">
                      Add Recipe
                    </a>
                  </li>
                  <li>
                    <a href="<?php echo base_url('user/addBlog'); ?>">
                      Add Blog
                    </a>
                  </li>
                  <li>
                    <a href="<?php echo base_url('user/imagePost'); ?>">
                      Post Image
                    </a>
                  </li>
                  <li>
                    <a href="<?php echo base_url('user/profilePost'); ?>">
                      Post Profile
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
                 <li><a href="<?php echo base_url('user/dashboard'); ?>">Home</a></li>
                 <li  class="active"><a href="<?php echo base_url('user/profile_follow_feed'); ?>">Profile</a></li>
                 <li><a href="#">Plan</a></li>
                 <li><a href="#">Metrics</a></li>
                 <li><a href="<?php echo base_url('user/like'); ?>">Likes</a></li>
                 <li><a href="<?php echo base_url('user/inbox'); ?>">Inbox</a></li>
               </ul>              
               <ul class="nav navbar-nav navbar-right head2">
                <form class="navbar-form navbar-left" onsubmit="return false;">
                 <div class="form-group">
                   <input type="text" class="form-control"  id="search_bar" placeholder="Search">
                   <input type="text" class="form-control" onkeyup="search_result();" id="search_bar" placeholder="Search">
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
     div#result_loader_main {
        display: none;
        position: absolute;
        top: 0px;
        height: 500px;
        background-color: #fff;
        width: 100%;
        left: 0px;
    }
    #result_loader_main img {
      position: absolute;
      left: 55%;
      top: 210px;
      width: 120px;
    }
   </style>