<!--==blog start==-->
<div class="container">
  <div class="col-md-12 blog">
     <h2 class="text-center"><?php echo ($this->input->get('post_id'))?'Edit Blog':'Blog Post'; ?></h2>
      <div id="recipie_response"></div>
      <form class="form-horizontal" id="recipie_form" onsubmit="return false;">
        <div class="form-group">
          <input type="hidden" name="recipe_blog_image_type" id="recipe_blog_image_type" value="blog">
          <label class="col-sm-2 control-label">Title<span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <input type="hidden" name="id" value="<?php if(!empty($row->id)) echo $row->id;?>">
              <input type="text" class="form-control" value="<?php if(!empty($row->title)) echo $row->title;?>" placeholder="Title" name="title" id="recipie_title" maxlength="70">
              <div id="recipie_title_error" class="text-danger"></div>
            </div>
          </div> 
          <div class="form-group">
            <label class="col-sm-2 control-label">Description<span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <textarea class="form-control" rows="5" placeholder="Description" name="description" id="recipie_description" maxlength="1000"><?php if(!empty($row->description)) echo $row->description;?></textarea>
              <div id="recipie_description_error" class="text-danger"></div>
            </div>
          </div>  
          <!-- <div class="form-group">
            <label class="col-sm-2 control-label">Tags<span class="text-danger">*</span></label>
            <div class="col-sm-10">
               <input type="text" class="form-control" placeholder="Allow # and @" value="<?php if(!empty($row->tags)) echo $row->tags;?>" name="tags" id="tagsUser">
                <div id="tags_error" class="text-danger"></div>
            </div>
          </div>  -->   
           <div class="form-group">
              <p class="col-sm-2 upload_text text-center">Image</p>
              <div class="col-sm-10 upload_picture">
                <label>
                  <div id="RecipePics">
                    <?php 
                      if(!empty($row->coverImage)&&file_exists('assets/uploads/recipeBlogImages/thumbnails/'.$row->coverImage)){
                        echo '<div class="recepemainImg" id="'.$row->id.'">
                                <img src="'.base_url().'assets/uploads/recipeBlogImages/thumbnails/'.$row->coverImage.'" class="receipe_img"/>                               
                              </div>';
                        $imageCount =1;
                      }
                    ?>
                  </div>
                  <input type="file"  id="imgUploadReceipe">
                  <input type="hidden"  id="imageCount" value="<?php !empty($imageCount)?$imageCount:'0'; ?>">
                  <input type="hidden"  id="pageType" value="addBlog">
                </label>
                <div id="file_type_error" class="text-danger"></div>
                <input type="hidden" name="recepeImgs" value="" id="recepeImgsName">
              </div>
              <input type="hidden" id="recepe_imgs" name="recepe_img">
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">Content <span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <textarea class="form-control" rows="5" placeholder="Content" name="content_edit" id="recipie_content"><?php if(!empty($row->content)) echo $row->content;?></textarea>
              <div id="content_error" class="text-danger"></div>
            </div>
          </div> 
          <div class="form-group b_button text-center">
            <input type="submit" class="btn btn-info btn-block" value="<?php echo ($this->input->get('post_id'))?'Update':'Post'; ?>" onclick="addRecipe();"/>
          </div>
     </form>
  </div>
</div>
<!--==blog end==-->