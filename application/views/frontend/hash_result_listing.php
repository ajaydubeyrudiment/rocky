<style type="text/css">.video_file{width: 100%; height: 190px;}</style>
<?php 
//echo '<pre>';print_r($rows); exit();
if(!empty($hrows)){
   foreach($hrows as $row){               
      //if($row->type=='recipe'&&!empty($row->image_name)&&file_exists('assets/uploads/recipeBlogImages/'.$row->image_name)){
      if($row->type=='recipe'){?>
         <div class="col-md-<?php echo !empty($gridCol)?$gridCol:'4'; ?> blog_1" id="post_id_<?php echo !empty($row->id)?$row->id:''; ?>">
            <div class="post_list_data">
               <a href="javascript:void(0);" data-toggle="modal" data-target="#imageDetails" onclick="imageDetails('<?php echo !empty($row->id)?$row->id:''; ?>');" class="model_link">              
                  <?php 
                  if(!empty($row->image_name)&&file_exists('assets/uploads/recipeBlogImages/'.$row->image_name)){
                     $files      = explode('.',$row->image_name);
                     $file_name  = strtolower(end($files));
                     if($file_name=='png'||$file_name=='jpeg'||$file_name=='jpg'){
                        echo '<img alt="blog" src="'.base_url().'assets/uploads/recipeBlogImages/'.$row->image_name.'">';
                     }else if($file_name=='mp4'){
                        echo '<video class="video_file"><source src="'.base_url().'assets/uploads/recipeBlogImages/'.$row->image_name.'" type="video/mp4"></video>';
                     }
                  }
                  if($row->imgCounts>1){echo '<div class="circleUpper circlesa"><div class="circle"></div><div class="circle"></div><div class="circle"></div></div>';}
                  ?>     
                  	<h3 class="relative">
                     	<?php echo (!empty($row->title)&&strlen($row->title)>20)?substr($row->title, 0,20).'..':$row->title; ?>
                  	</h3>
               </a>
               <?php
                if($this->input->get('type')=='likes'){
               		if(!empty($row->user_id)&&$row->user_id==user_id()){?>
	                <a href="javascript:void(0);"  class="model_link edit-icon" onclick="editPost('<?php echo !empty($row->id)?$row->id:''; ?>','addRecipe');">
	                    <img  src="<?php echo base_url(); ?>assets/front/img/edite_icon.png">
	                </a>	                  
               <?php }?>
               		<a href="javascript:void(0);" onclick="deleteLike('<?php echo !empty($row->id)?$row->id:''; ?>');" class="model_link delete-icon">
	                    <i class="fa fa-times"></i>
	                </a>
               <?php 
           		}else if($this->input->get('type')=='bookmark'){
               		if(!empty($row->user_id)&&$row->user_id==user_id()){?>
	                <a href="javascript:void(0);"  class="model_link edit-icon" onclick="editPost('<?php echo !empty($row->id)?$row->id:''; ?>','addRecipe');">
	                    <img  src="<?php echo base_url(); ?>assets/front/img/edite_icon.png">
	                </a>	                  
               <?php }?>
               		<a href="javascript:void(0);" onclick="deleteBookMark('<?php echo !empty($row->id)?$row->id:''; ?>');" class="model_link delete-icon">
	                    <i class="fa fa-times"></i>
	                </a>
               <?php 
           		}else if(!empty($row->user_id)&&$row->user_id==user_id()){?>
	                <a href="javascript:void(0);"  class="model_link edit-icon" onclick="editPost('<?php echo !empty($row->id)?$row->id:''; ?>','addRecipe');">
	                    <img  src="<?php echo base_url(); ?>assets/front/img/edite_icon.png">
	                </a>
	                <a href="javascript:void(0);" onclick="deletePost('<?php echo !empty($row->id)?$row->id:''; ?>');" class="model_link delete-icon">
	                    <i class="fa fa-times"></i>
	                </a>
               <?php  
           		}?>
            </div>
         </div>
      <?php 
      //}elseif($row->type=='profile_post'&&!empty($row->image_name)&&file_exists('assets/uploads/recipeBlogImages/'.$row->image_name)){       
      }elseif($row->type=='profile_post'){?>       
         <div class="col-md-<?php echo !empty($gridCol)?$gridCol:'4'; ?> blog_1" id="post_id_<?php echo !empty($row->id)?$row->id:''; ?>">
            <div class="post_list_data">
               <a href="javascript:void(0);" data-toggle="modal" data-target="#imageDetails" onclick="imageDetails('<?php echo !empty($row->id)?$row->id:''; ?>');" class="model_link">
                   <?php 
                  if(!empty($row->image_name)&&file_exists('assets/uploads/recipeBlogImages/'.$row->image_name)){
                     $files      = explode('.',$row->image_name);
                     $file_name  = strtolower(end($files));
                     if($file_name=='png'||$file_name=='jpeg'||$file_name=='jpg'){
                        echo '<img alt="blog" src="'.base_url().'assets/uploads/recipeBlogImages/'.$row->image_name.'">';
                     }else if($file_name=='mp4'){
                        echo '<video class="video_file"><source src="'.base_url().'assets/uploads/recipeBlogImages/'.$row->image_name.'" type="video/mp4"></video>';
                     }
                     if($row->imgCounts>1){echo '<div class="circleUpper circlesa"><div class="circle"></div><div class="circle"></div><div class="circle"></div></div>';}
                  }?>
                  <!-- <img alt="blog" src="<?php echo  base_url('assets/uploads/recipeBlogImages/'.$row->image_name) ;?>">   -->
                  <h3 class="relative"><?php if(!empty($row->caption)) echo ucwords($row->caption); ?></h3>
               </a>
               <?php
                if($this->input->get('type')=='likes'){
               		if(!empty($row->user_id)&&$row->user_id==user_id()){?>
	                <a href="javascript:void(0);"  class="model_link edit-icon" onclick="editPost('<?php echo !empty($row->id)?$row->id:''; ?>','imagePost');">
	                    <img  src="<?php echo base_url(); ?>assets/front/img/edite_icon.png">
	                </a>	                  
               <?php }?>
               		<a href="javascript:void(0);" onclick="deleteLike('<?php echo !empty($row->id)?$row->id:''; ?>');" class="model_link delete-icon">
	                    <i class="fa fa-times"></i>
	                </a>
               <?php 
           		}else if($this->input->get('type')=='bookmark'){
               		if(!empty($row->user_id)&&$row->user_id==user_id()){?>
	                <a href="javascript:void(0);"  class="model_link edit-icon" onclick="editPost('<?php echo !empty($row->id)?$row->id:''; ?>','imagePost');">
	                    <img  src="<?php echo base_url(); ?>assets/front/img/edite_icon.png">
	                </a>	                  
               <?php }?>
               		<a href="javascript:void(0);" onclick="deleteBookMark('<?php echo !empty($row->id)?$row->id:''; ?>');" class="model_link delete-icon">
	                    <i class="fa fa-times"></i>
	                </a>
               <?php 
           		}else if(!empty($row->user_id)&&$row->user_id==user_id()){?>
	                <a href="javascript:void(0);"  class="model_link edit-icon" onclick="editPost('<?php echo !empty($row->id)?$row->id:''; ?>','imagePost');">
	                    <img  src="<?php echo base_url(); ?>assets/front/img/edite_icon.png">
	                </a>
	                <a href="javascript:void(0);" onclick="deletePost('<?php echo !empty($row->id)?$row->id:''; ?>');" class="model_link delete-icon">
	                    <i class="fa fa-times"></i>
	                </a>
               <?php  
           		}?>
            </div>
         </div>
      <?php 
      //}else if($row->type=='image'&&!empty($row->image_name)&&file_exists('assets/uploads/recipeBlogImages/'.$row->image_name)){
      }else if($row->type=='image'){?>
      <div class="col-md-<?php echo !empty($gridCol)?$gridCol:'4'; ?> blog_1 h1" id="post_id_<?php echo !empty($row->id)?$row->id:''; ?>">
         <div class="post_list_data">
            <a href="javascript:void(0);" data-toggle="modal" data-target="#imageDetails" onclick="imageDetails('<?php echo !empty($row->id)?$row->id:''; ?>','imagePost');" class="model_link">
               <?php 
                  if(!empty($row->image_name)&&file_exists('assets/uploads/recipeBlogImages/'.$row->image_name)){
                    // echo $row->image_name; exit();
                  $files      = explode('.', $row->image_name);
                  //print_r( $files);
                  $file_name  = strtolower(end($files));
                  if($file_name=='png'||$file_name=='jpeg'||$file_name=='jpg'){
                     echo '<img alt="blog" src="'.base_url().'assets/uploads/recipeBlogImages/'.$row->image_name.'">';
                  }else if($file_name=='mp4'){
                     echo '<video class="video_file"><source src="'.base_url().'assets/uploads/recipeBlogImages/'.$row->image_name.'" type="video/mp4"></video>';
                  }
                  if($row->imgCounts>1){echo '<div class="circleUpper circlesa"><div class="circle"></div><div class="circle"></div><div class="circle"></div></div>';}
               }?>
            </a> 
            <?php
                if($this->input->get('type')=='likes'){
               		if(!empty($row->user_id)&&$row->user_id==user_id()){?>
	                <a href="javascript:void(0);"  class="model_link edit-icon" onclick="editPost('<?php echo !empty($row->id)?$row->id:''; ?>','imagePost');">
	                    <img  src="<?php echo base_url(); ?>assets/front/img/edite_icon.png">
	                </a>	                  
               <?php }?>
               		<a href="javascript:void(0);" onclick="deleteLike('<?php echo !empty($row->id)?$row->id:''; ?>');" class="model_link delete-icon">
	                    <i class="fa fa-times"></i>
	                </a>
               <?php 
           		}else if($this->input->get('type')=='bookmark'){
               		if(!empty($row->user_id)&&$row->user_id==user_id()){?>
	                <a href="javascript:void(0);"  class="model_link edit-icon" onclick="editPost('<?php echo !empty($row->id)?$row->id:''; ?>','imagePost');">
	                    <img  src="<?php echo base_url(); ?>assets/front/img/edite_icon.png">
	                </a>	                  
               <?php }?>
               		<a href="javascript:void(0);" onclick="deleteBookMark('<?php echo !empty($row->id)?$row->id:''; ?>');" class="model_link delete-icon">
	                    <i class="fa fa-times"></i>
	                </a>
               <?php 
           		}else if(!empty($row->user_id)&&$row->user_id==user_id()){?>
	                <a href="javascript:void(0);"  class="model_link edit-icon" onclick="editPost('<?php echo !empty($row->id)?$row->id:''; ?>','imagePost');">
	                    <img  src="<?php echo base_url(); ?>assets/front/img/edite_icon.png">
	                </a>
	                <a href="javascript:void(0);" onclick="deletePost('<?php echo !empty($row->id)?$row->id:''; ?>');" class="model_link delete-icon">
	                    <i class="fa fa-times"></i>
	                </a>
               <?php  
           		}?> 
          </div>        
      </div>
      <?php 
      //}else if($row->type=='blog'&&!empty($row->coverImage)&&file_exists('assets/uploads/recipeBlogImages/'.$row->coverImage)){
      }else if($row->type=='blog'){?>
      <div class="col-md-<?php echo !empty($bgridCol)?$bgridCol:'8'; ?> m_right " id="post_id_<?php echo !empty($row->id)?$row->id:''; ?>">
         <div class="media post_list_data">
            <?php
            if($this->input->get('type')=='likes'){
           		if(!empty($row->user_id)&&$row->user_id==user_id()){?>
                <a href="javascript:void(0);"  class="model_link edit-icon" onclick="editPost('<?php echo !empty($row->id)?$row->id:''; ?>','addBlog');">
                    <img  src="<?php echo base_url(); ?>assets/front/img/edite_icon.png">
                </a>	                  
           <?php }?>
           		<a href="javascript:void(0);" onclick="deleteLike('<?php echo !empty($row->id)?$row->id:''; ?>');" class="model_link delete-icon">
                    <i class="fa fa-times"></i>
                </a>
           <?php 
       		}else if($this->input->get('type')=='bookmark'){
           		if(!empty($row->user_id)&&$row->user_id==user_id()){?>
                <a href="javascript:void(0);"  class="model_link edit-icon" onclick="editPost('<?php echo !empty($row->id)?$row->id:''; ?>','addBlog');">
                    <img  src="<?php echo base_url(); ?>assets/front/img/edite_icon.png">
                </a>	                  
           <?php }?>
           		<a href="javascript:void(0);" onclick="deleteBookMark('<?php echo !empty($row->id)?$row->id:''; ?>');" class="model_link delete-icon">
                    <i class="fa fa-times"></i>
                </a>
           <?php 
       		}else if(!empty($row->user_id)&&$row->user_id==user_id()){?>
                <a href="javascript:void(0);"  class="model_link edit-icon" onclick="editPost('<?php echo !empty($row->id)?$row->id:''; ?>','addBlog');">
                    <img  src="<?php echo base_url(); ?>assets/front/img/edite_icon.png">
                </a>
                <a href="javascript:void(0);" onclick="deletePost('<?php echo !empty($row->id)?$row->id:''; ?>');" class="model_link delete-icon">
                    <i class="fa fa-times"></i>
                </a>
           <?php  
       		}?>
            <!--  <img class="media-object" src="<?php echo  base_url('assets/uploads/recipeBlogImages/'.$row->coverImage) ;?>"> -->
            <div class="col-md-4 media-left">
               <a href="javascript:void(0);" data-toggle="modal" data-target="#imageDetails" onclick="imageDetails('<?php echo !empty($row->id)?$row->id:''; ?>');" class="model_link">
                  <?php
                  if(!empty($row->coverImage)&&file_exists('assets/uploads/recipeBlogImages/'.$row->coverImage)){
                     $files      = explode('.',$row->coverImage);
                     $file_name  = strtolower(end($files));
                     if($file_name=='png'||$file_name=='jpeg'||$file_name=='jpg'){
                        echo '<img alt="blog" src="'.base_url().'assets/uploads/recipeBlogImages/'.$row->coverImage.'">';
                     }else if($file_name=='mp4'){
                        echo '<video class="video_file"><source src="'.base_url().'assets/uploads/recipeBlogImages/'.$row->coverImage.'" type="video/mp4"></video>';
                     }
                  }?>
               </a>              
            </div>
            <div class="col-md-8">
               <a href="javascript:void(0);" data-toggle="modal" data-target="#imageDetails" onclick="imageDetails('<?php echo !empty($row->id)?$row->id:''; ?>');" class="model_link">
                  <h4 class="media-heading">
                     <?php echo (!empty($row->title)&&strlen($row->title)>20)?substr($row->title, 0,20).'..':$row->title; ?>
                  </h4>
                  <p class="b_content">
                     <?php echo  (!empty($row->description)&&strlen($row->description)>150)?substr($row->description, 0,150).'...':$row->description; ?>
                  </p>
               </a>
            </div>
         </div>
      </div>
   <?php
      } 
   } 
}else{
   //echo '<div class="col-md-12 m_right text-center text-danger"><br/><br/><br/><br/><h2>No records found</h2></div>';
}?>
      