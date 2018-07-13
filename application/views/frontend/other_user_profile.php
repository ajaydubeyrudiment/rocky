<div class="col-md-9 f_r_user">
  <form id="blog_recepe_filters">
     <input type="hidden" name="user_id" value="<?php echo $this->input->get('user_id')?$this->input->get('user_id'):0;?>">
     <input type="hidden" name="type" value="image" id="type_id">
  </form>
  <ul class="nav nav-justified feed_right_user_list" role="tablist">
    <li role="presentation" class=""><a href="#Images" onclick="tabMenu('Images');" aria-controls="Images" role="tab" data-toggle="tab">Images</a></li>
    <li role="presentation"><a href="#Recipes" onclick="tabMenu('Recipes');"  aria-controls="messages" role="tab" data-toggle="tab">Recipes</a></li>
    <li role="presentation"><a href="#Blogs" onclick="tabMenu('Blogs');"  aria-controls="Blogs" role="tab" data-toggle="tab">Blogs</a></li>
  </ul>
  <div class="tab-content user_profile_view_rok">
    <div class="profile_follow_feed tab_sec">
      <div class="feed_right">      
        <div class="blog_picture">
          <?php include('result_listing.php'); ?>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
    <div role="tabpanel" class="tab-pane tab_sec fade in active" style="display: none;" id="Images">
      <div id="user_added_images"></div>       
       <div class="clearfix"></div>
    </div>                
    <div role="tabpanel" class="tab-pane tab_sec fade" style="display: none;" id="Recipes">
       <div id="user_added_recipes"></div>
       <div class="clearfix"></div>
    </div>
    <div role="tabpanel" class="tab-pane tab_sec fade" style="display: none;" id="Blogs">
      <div class="feed_right">                         
        <div id="user_added_blogs"></div>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
</div>