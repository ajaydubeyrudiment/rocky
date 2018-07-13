<!-- Content Wrapper. Contains page content -->
<div id="dash_board_custom">
  <!-- Content Header (Page header) -->
  <section class="content-header title_containt">
    Hello <span class="user_names_s"><?php echo superadmin_name();?></span>, Welcome to the <span class="site_name"><?php echo site_info('site_title'); ?></span> Admin............. <i class="fa fa-smile-o" aria-hidden="true"></i>      
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">      
      <?php 
        $segment1   =   $this->uri->segment(2);
        $segment2   =   $this->uri->segment(3);
        if(!empty($segment2)){
          $urlSegment = strtolower($segment1)."/".strtolower($segment2);
        }else{
          $urlSegment = strtolower($segment1);
        }
        $main_modil_match  = str_replace('/index', '', $urlSegment);
        $main_modil_matchs = explode('/', $main_modil_match);
        $main_module_param = end($main_modil_matchs);
        $all_module        = get_admin_all_modules();     
        if(in_array('addsubadmin', $all_module)||in_array('subadmin', $all_module)){?>
          <div class="col-md-4">
            <div class="box margin_bottom_20">
              <div class="box-header with-border box_heads"> 
                <h3 class="box-title">
                  <i class="fa fa-users" aria-hidden="true"></i>
                  Subadmins    
                </h3>
              </div> 
              <div class="box-body">
                <ul class="secound">
                  <?php if(in_array('addsubadmin', $all_module)){?>  
                    <li>
                      <a href="<?php echo base_url('superadmin/subadmin'); ?>">
                        Subadmin List <span class="label label-success pull-right lable_counter-dash"><?php echo get_all_count('admin_users', array('user_role'=>'3')); ?></span>
                      </a>                 
                    </li>
                  <?php } if(in_array('subadmin', $all_module)){?>
                    <li>
                      <a href="<?php echo base_url('superadmin/subadmin/addSubadmin'); ?>">
                        Add Subadmin 
                      </a>
                    </li>
                  <?php }?>
                </ul>
              </div>
            </div>
          </div>
        <?php }?>
        <?php if(in_array('customer', $all_module)||in_array('influencers', $all_module)){?>
          <div class="col-md-4">
            <div class="box margin_bottom_20">
              <div class="box-header with-border box_heads"> 
                <h3 class="box-title">
                  <i class="fa fa-users" aria-hidden="true"></i>
                  Users    
                </h3>
              </div>
              <div class="box-body">
                <ul class="secound"> 
                  <?php if(in_array('customer', $all_module)){?> 
                    <li>
                      <a href="<?php echo base_url('superadmin/customer'); ?>">
                        Users <span class="label label-success pull-right lable_counter-dash"><?php echo get_all_count('users', array('status !='=>'3')); ?></span>
                      </a>                 
                    </li>
                  <?php
                  }
                  if(in_array('influencers', $all_module)){?>
                    <li>
                      <a href="<?php echo base_url('superadmin/customer/influencers'); ?>">
                        Influencers 
                      </a>
                    </li>
                  <?php }?>
                </ul>
              </div>
            </div>
          </div>
        <?php }?>
        <?php if(in_array('subscription', $all_module)||in_array('addsubscription', $all_module)){?>
        <div class="col-md-4">
          <div class="box margin_bottom_20">
            <div class="box-header with-border box_heads"> 
              <h3 class="box-title">
                <i class="fa fa-th" aria-hidden="true"></i>
                  Subscriptions   
              </h3>
            </div>
            <div class="box-body">
              <ul class="secound">
                <?php if(in_array('subscription', $all_module)){?>
                <li>
                  <a href="<?php echo base_url('superadmin/subscription'); ?>">
                      Subscription  List <span class="label label-success pull-right lable_counter-dash"><?php echo get_all_count('plans', array('status !='=>'3')); ?></span>
                  </a>
                </li>
                <?php }?>
                <?php if(in_array('addsubscription', $all_module)){?>
                <li>
                  <a href="<?php echo base_url('superadmin/subscription/addSubscription'); ?>">
                      Add Subscription 
                  </a>
                </li>
                <?php }?>
              </ul>
            </div>
          </div>       
        </div>  
        <?php }?>
        <?php if(in_array('plancategory', $all_module)||in_array('addplancategory', $all_module)){?>
        <div class="col-md-4">       
          <div class="box margin_bottom_20">
            <div class="box-header with-border box_heads"> 
              <h3 class="box-title">
                <i class="fa fa-th" aria-hidden="true"></i>
                  Categorys  
              </h3>
            </div>          
            <div class="box-body">
              <ul class="secound">
                <?php if(in_array('plancategory', $all_module)){?>
                <li>
                  <a href="<?php echo base_url('superadmin/servicePlan/planCategory'); ?>">
                    Category List <span class="label label-success pull-right lable_counter-dash"><?php echo get_all_count('service_plan_category', array('status !='=>'3')); ?></span>
                  </a>
                </li>
                <?php }?>
                <?php if(in_array('addplancategory', $all_module)){?>
                <li>
                  <a href="<?php echo base_url('superadmin/servicePlan/addPlanCategory'); ?>">
                    Add Category
                  </a>
                </li>
                <?php }?>
              </ul>
            </div>
          </div>        
        </div>  
        <?php }?>
        <?php if(in_array('addplansubcategory', $all_module)||in_array('plansubcategory', $all_module)){?>
        <div class="col-md-4">       
          <div class="box margin_bottom_20">
            <div class="box-header with-border box_heads"> 
              <h3 class="box-title">
                <i class="fa fa-th" aria-hidden="true"></i>
                  Subcategorys  
              </h3>
            </div>          
            <div class="box-body">
              <ul class="secound">
                <?php if(in_array('plansubcategory', $all_module)){?>
                <li>
                  <a href="<?php echo base_url('superadmin/servicePlan/planSubCategory'); ?>">
                    Subcategory List <span class="label label-success pull-right lable_counter-dash"><?php echo get_all_count('service_plan_item', array('status !='=>'3')); ?></span>
                  </a>
                </li>
                <?php }?>
                <?php if(in_array('addplansubcategory', $all_module)){?>
                  <li>
                    <a href="<?php echo base_url('superadmin/servicePlan/addPlanSubCategory'); ?>">
                      Add Subcategory
                    </a>
                  </li>
                <?php }?>
              </ul>
            </div>
          </div>        
        </div>     
        <?php }?>
        <?php if(in_array('add_workout_exercise', $all_module)||in_array('workout_exercise', $all_module)){?>   
        <div class="col-md-4">       
          <div class="box margin_bottom_20">
            <div class="box-header with-border box_heads"> 
              <h3 class="box-title">
                <img src="<?php echo base_url('assets/admin/img/workout_plan.png');?>" width="30">
                Workout Exercises
              </h3>
            </div>          
            <div class="box-body">
                <ul class="secound">
                  <?php if(in_array('workout_exercise', $all_module)){?>
                    <li>
                      <a href="<?php echo base_url('superadmin/servicePlan/workout_exercise'); ?>">
                          Workout Exercise List <span class="label label-success pull-right lable_counter-dash"><?php echo get_all_count('service_plan_user_exercise', array('status !='=>'3')); ?></span>
                      </a>
                    </li>
                  <?php }?>
                  <?php if(in_array('add_workout_exercise', $all_module)){?>
                    <li>
                      <a href="<?php echo base_url('superadmin/servicePlan/add_workout_exercise'); ?>">
                          Add Workout Exercise
                      </a>
                    </li>
                  <?php }?>
                </ul>
            </div>
          </div>        
        </div>
        <?php }?>
        <?php if(in_array('diet_plan_food', $all_module)||in_array('add_diet_plan_food', $all_module)){?>
        <div class="col-md-4">       
          <div class="box margin_bottom_20">
            <div class="box-header with-border box_heads"> 
              <h3 class="box-title">
                <img src="<?php echo base_url('assets/admin/img/diet_plan.png');?>" width="30">
                Diet Items
              </h3>
            </div>          
            <div class="box-body">
                <ul class="secound">
                  <?php 
                  if(in_array('diet_plan_food', $all_module)){?>
                    <li>
                      <a href="<?php echo base_url('superadmin/servicePlan/diet_plan_food'); ?>">
                        Diet Item List <span class="label label-success pull-right lable_counter-dash"><?php echo get_all_count('service_plan_diet_items', array('status !='=>'3')); ?></span>
                      </a>
                    </li>
                  <?php }?>
                  <?php if(in_array('add_diet_plan_food', $all_module)){?>
                    <li>
                      <a href="<?php echo base_url('superadmin/servicePlan/add_diet_plan_food'); ?>">
                          Add Diet Item
                      </a>
                    </li>
                  <?php }?>
                </ul>
            </div>
          </div>        
        </div> 
        <?php }?>
        <?php if(in_array('post?type=image', $all_module)||in_array('post?type=blog', $all_module)||in_array('post?type=recipe', $all_module)){?>
          <div class="col-md-4">
            <div class="box margin_bottom_20">
              <div class="box-header with-border box_heads"> 
                <h3 class="box-title">
                  <i class="fa fa-th" aria-hidden="true"></i>
                  Posted Content
                </h3>
              </div>
              <div class="box-body">
                <ul class="secound">
                  <?php if(in_array('post?type=blog', $all_module)){?>  
                  <li>
                    <a href="<?php echo base_url('superadmin/post?type=blog'); ?>">
                      Blog List <span class="label label-success pull-right lable_counter-dash"><?php echo get_all_count('recipe_blog_image', array('status !='=>'3', 'type'=>'blog')); ?></span>
                    </a>
                  </li>
                  <?php }?>
                  <?php if(in_array('post?type=image', $all_module)){?>
                  <li>
                    <a href="<?php echo base_url('superadmin/post?type=image'); ?>">
                      Images List <span class="label label-success pull-right lable_counter-dash"><?php echo get_all_count('recipe_blog_image', array('status !='=>'3' , 'type'=>'image')); ?></span>
                    </a>
                  </li>
                  <?php }?>
                  <?php if(in_array('post?type=recipe', $all_module)){?>
                  <li>
                    <a href="<?php echo base_url('superadmin/post?type=recipe'); ?>">
                      Recipe List <span class="label label-success pull-right lable_counter-dash"><?php echo get_all_count('recipe_blog_image', array('status !='=>'3' , 'type'=>'recipe')); ?></span>
                    </a>
                  </li>  
                  <?php }?>                
                </ul>
              </div>
            </div>
          </div>
        <?php }?>
        <div class="col-md-4">
          <div class="box margin_bottom_20">
            <div class="box-header with-border box_heads">
              <h3 class="box-title">
                <i class="fa fa-bookmark" aria-hidden="true"></i>
                Other
              </h3>
            </div>
            <div class="box-body">
              <ul class="secound">  
                <li>
                  <a href="<?php echo base_url(ADMIN_DIR.'superadmin/profile'); ?>">
                    Profile
                  </a>
                </li>
                <li>
                  <a href="<?php echo base_url(ADMIN_DIR.'superadmin/change_password'); ?>">
                    Change Password 
                  </a>
                </li>
                <li>
                  <a href="<?php echo base_url(ADMIN_DIR.'superadmin/logout'); ?>">
                    Signout
                  </a>
                </li>
              </ul> 
            </div>
          </div>
        </div>
    </div>
  </section>
</div>
<!-- left column -->
<!-- <div class="col-md-4">
  <div class="box margin_bottom_20">
    <div class="box-header with-border box_heads"> 
      <h3 class="box-title">
        <i class="fa fa-th" aria-hidden="true"></i>
          Height  
      </h3>
    </div>
    <div class="box-body">
        <ul class="secound">
          <li>
            <a href="<?php echo base_url('superadmin/height'); ?>">
                Height List <span class="label label-success pull-right lable_counter-dash"><?php echo get_all_count('height', array('status !='=>'3')); ?></span>
            </a>
          </li>
          <li>
            <a href="<?php echo base_url('superadmin/height/addHeight'); ?>">
                Add Height
            </a>
          </li>
        </ul>
    </div>
  </div>
</div>  --> 

<!-- <div class="col-md-4">       
  <div class="box margin_bottom_20">
    <div class="box-header with-border box_heads"> 
      <h3 class="box-title">
        <i class="fa fa-th" aria-hidden="true"></i>
          Service Plans  
      </h3>
    </div>          
    <div class="box-body">
        <ul class="secound">
          <li>
            <a href="<?php echo base_url('superadmin/servicePlan'); ?>">
                Service Plan List <span class="label label-success pull-right lable_counter-dash"><?php echo get_all_count('service_plan', array('status !='=>'3')); ?></span>
            </a>
          </li>
          <li>
            <a href="<?php echo base_url('superadmin/servicePlan/addServicePlant'); ?>">
                Add Service Plan
            </a>
          </li>
        </ul>
    </div>
  </div>        
</div>   -->