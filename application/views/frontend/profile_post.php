<div class="container">
   <div class="row">
      <div class="container new_image">
          <h1 class="text-center">New Image</h1>
          <form class="form-horizontal" id="recipie_form" onsubmit="return false;">
            <input type="hidden" name="recipe_blog_image_type" id="recipe_blog_image_type" value="profile_post">
            <div class="col-md-4 col-xs-12">
              <div class="row">
                <div class="profile_form_image">
                   <div class="upload_image">
                      <label>
                          <input type="file"  id="imgUploadReceipe">
                         <p><i class="fa fa-camera" aria-hidden="true"></i></p>
                      </label>
                      <?php 
                      if(!empty($row->image_name)&&file_exists('assets/uploads/recipeBlogImages/'.$row->image_name)){                                           
                        $image_name = base_url().'assets/uploads/recipeBlogImages/'.$row->image_name;
                        if(!empty($image_name)){              
                          $files      = explode('.', $image_name);
                          $file_name  = strtolower(end($files));
                          if($file_name=='png'||$file_name=='jpeg'||$file_name=='jpg'){
                            echo '<img alt="blog" src="'.$image_name.'" id="profile_post_image" class="profile_post_img_file">';
                          }else if($file_name=='mp4'){
                            echo '<video class="video_file profile_post_img_file"><source src="'.$image_name.'" type="video/mp4"></video>';
                          }
                        }                         
                      }else{
                        $image_name = FRONT_THEAM_PATH.'img/img-7.jpg';
                        echo '<img src="'.$image_name.'" id="profile_post_image">';
                      }?>                      
                      <div id="file_type_error"></div>
                      <input type="hidden" name="recepeImgs" value="" id="recepeImgsName">
                      <input type="hidden" name="default_id" value="<?php if(!empty($imagesID)){echo $imagesID;} ?>">
                      <input type="hidden" name="images_ids" value="<?php if(!empty($imagesIDA)){echo $imagesIDA;} ?>">
                      <input type="hidden" name="id" value="<?php if($this->input->get('post_id')){echo $this->input->get('post_id');} ?>">
                   </div>
                </div>
              </div>
             </div>
             <div class="col-md-8 col-xs-12">
                <div class="profile_form_image_right">  
                  <div id="recipie_response"></div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Caption<span class="text-danger">*</span></label>
                      <div class="col-sm-9">
                         <textarea type="text" class="form-control" rows="3" placeholder="1000 chars, allow # and @ "  id="caption" name="caption"><?php if(!empty($post_details->caption)) echo $post_details->caption;?></textarea>
                         <div id="caption_error" class="text-danger"></div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Location<span class="text-danger">*</span></label>
                      <div class="col-sm-9">
                         <input type="text" class="form-control" id="location" placeholder="Auto suggest from GPS" value="<?php if(!empty($post_details->location)) echo $post_details->location;?>" name="location">
                         <div id="location_error" class="text-danger"></div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Tag People</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="Auto Suggest from letters entered" value="<?php if(!empty($post_details->tag_users)) echo $post_details->tag_users;?>" name="tag_users">
                        <div id="tag_users_error" class="text-danger"></div>
                      </div>
                    </div>
                    <div class="form-group post_f_p">
                      <button class="btn btn-block post_image" onclick="addRecipe();"> 
                        <?php if($this->input->get('post_id')){echo 'Update';}else{echo 'Post';} ?>          
                      </button>
                    </div>                   
                </div>
             </div>
          </form>
         <div class="clearfix"></div>
      </div>      
   </div>
</div>