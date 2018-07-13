<!--==HEADER END==-->
<div class="container">
 <div class="row">
    <div class="container new_image i_p">
       <h1 class="text-center">New Image</h1>      
       <div class="col-md-12 col-xs-12">
          <div id="recipie_response"></div>
          <form class="form-horizontal" id="recipie_form" onsubmit="return false;">
            <input type="hidden" name="recipe_blog_image_type" id="recipe_blog_image_type" value="image">
            <div class="p_upload_imagee">
              <label>
              <?php 
                $imagesCounts = 0;
                $imagesIDs   = array(); 
                if(!empty($images)){
                  foreach ($images as $image) {
                    $funcs  = "deleteParnamentImageImg('".$image->id."');";
                    echo '<div class="recepemainImg" id="'.$image->id.'">
                            <img src="'.base_url().'assets/uploads/recipeBlogImages/thumbnails/'.$image->image_name.'" class="receipe_img"/>
                            <a onclick="'.$funcs.'" href="javascript:void(0);" class="deleteReceipeImg"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a>
                            </div>';
                    $imagesCounts++;
                    $imagesIDs[] = $image->id;
                  }
                }
                ?>
                <div id="RecipePics"></div>
                <input type="file"  id="imgUploadReceipe"  multiple="multiple">
                Drag and drop your picture in this area or click <strong>browse</strong> to select an item.              
              </label>
              <div id="file_type_error" class="text-danger"></div>
              <input type="hidden" name="recepeImgs" value="" id="recepeImgsName">
              <input type="hidden" name="id" value="<?php if($this->input->get('post_id')){echo $this->input->get('post_id');} ?>"/>
              <input type="hidden" name="postImagesIds" value="<?php if(!empty($imagesIDs)){echo implode(',', $imagesIDs);} ?>" id="postImagesIds"/>
              <input id="imageCount" class="imageCount" value="<?php echo !empty($imagesCounts)?$imagesCounts:0; ?>" /> 
            </div>
          </form>
       </div>
       <div class="col-md-12 col-xs-12 text-center">       
          <button class="btn btn-block post_image" onclick="addRecipe();">
            <?php if($this->input->get('post_id')){echo 'Next';}else{echo 'Upload';} ?>            
          </button>
       </div>
       <div class="clearfix"></div>
    </div>      
 </div>
</div>
      