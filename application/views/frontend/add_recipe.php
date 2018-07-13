<!--==blog start==-->
<div class="container">
    <div class="col-md-12 blog">
       <h2 class="text-center"><?php echo ($this->input->get('post_id'))?'Edit Recipe':'New Recipe'; ?></h2>
       <div id="recipie_response"></div>
       <form class="form-horizontal" id="recipie_form" onsubmit="return false;">
          <div class="form-group">
            <label class="col-sm-2 control-label">Title<span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <input type="hidden" name="recipe_blog_image_type" id="recipe_blog_image_type" value="recipe">
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
           <div class="form-group">
              <p class="col-sm-2 upload_text text-center">Upload Image for recipie<span class="text-danger">*</span></p>
              <div class="col-sm-10 upload_picture">
                <label>
                  <?php 
                    if(!empty($images)){
                      $imageCount = 0;
                      foreach ($images as $image) {
                        $imageCount++;
                        $funcs  = "deleteParnamentImg('".$image->id."','".$image->image_name."','".$image->meta_id."');";
                        echo '<div class="recepemainImg" id="'.$image->id.'">
                                <img src="'.base_url().'assets/uploads/recipeBlogImages/thumbnails/'.$image->image_name.'" class="receipe_img"/>
                                <a onclick="'.$funcs.'" href="javascript:void(0);" class="deleteReceipeImg"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>
                              </div>';
                      }
                    }
                  ?>
                  <div id="RecipePics"></div>
                    <input type="file"  id="imgUploadReceipe"  multiple="multiple"  maxlength="5">
                    <input type="hidden"  id="imageCount" value="<?php echo !empty($imageCount)?$imageCount:'0'; ?>">
                    <input type="hidden"  id="pageType" value="addRecipe">
                </label>
                <div id="file_type_error" class="text-danger"></div>
                <input type="hidden" name="recepeImgs" value="" id="recepeImgsName">
              </div>
              <input type="hidden" id="recepe_imgs" name="recepe_img">
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">Ingredients<span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <textarea class="form-control" rows="5" placeholder="Ingredients" name="ingredients" id="recipie_ingredients"><?php if(!empty($row->ingredients)) echo $row->ingredients;?></textarea>
              <div id="recipie_ingredients_error" class="text-danger"></div>
            </div>
          </div> 
          <div class="form-group">
            <label class="col-sm-2 control-label">Instructions<span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <textarea class="form-control" rows="5" placeholder="Instructions" name="instructions" id="recipie_instructions"><?php if(!empty($row->instructions)) echo $row->instructions;?></textarea>
              <div id="recipie_instructions_error" class="text-danger"></div>
            </div>
          </div>         
         <div class="form-group b_button text-center">
            <input type="submit" class="btn btn-info btn-block" value="<?php echo ($this->input->get('post_id'))?'Update':'Post'; ?>" onclick="addRecipe();">
          </div>
       </form>
    </div>
</div>        