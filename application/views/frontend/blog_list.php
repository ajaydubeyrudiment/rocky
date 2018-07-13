<?php 
if(!empty($rows)){
  foreach($rows as $row){               
    if(!empty($row->coverImage)&&file_exists('assets/uploads/recipeBlogImages/'.$row->coverImage)){?>
        <div class="blog_picture" id="post_id_<?php echo !empty($row->id)?$row->id:''; ?>">                 
           <div class="col-md-12 m_right">
              <div class="media user_p post_list_data">
                <?php
                 if(!empty($row->user_id)&&$row->user_id==user_id()){?>
                    <a href="javascript:void(0);"  class="model_link edit-icon" onclick="editPost('<?php echo !empty($row->id)?$row->id:''; ?>', 'addBlog');">
                       <img  src="<?php echo base_url(); ?>assets/front/img/edite_icon.png">
                    </a>
                    <a href="javascript:void(0);" onclick="deletePost('<?php echo !empty($row->id)?$row->id:''; ?>');" class="model_link delete-icon">
                       <i class="fa fa-times"></i>
                    </a>
                 <?php }?>
                 <div class="col-md-3 media-left">
                    <a href="#" data-toggle="modal" data-target="#imageDetails" onclick="imageDetails('<?php echo !empty($row->id)?$row->id:''; ?>');">
                    	<img src="<?php echo  base_url().'assets/uploads/recipeBlogImages/'.$row->coverImage ;?>" alt="Image">
                    </a>
                 </div>
                 <div class="col-md-9">
                    <h4 class="media-heading">
                    	<a href="javascript:void(0);" data-toggle="modal" data-target="#imageDetails" onclick="imageDetails('<?php echo !empty($row->id)?$row->id:''; ?>');"> 
                    		<?php if(!empty($row->title)) echo ucwords($row->title);  ?>  
	                    </a>
	                </h4>
                    <p class="b_content">
                    	<?php echo  (!empty($row->description)&&strlen($row->description)>150)?substr($row->description, 0,150).'...':$row->description; ?>
                    </p>
                 </div>
              </div>
           </div>                
        </div>
<?php }else{?>
		<div class="blog_picture" id="post_id_<?php echo !empty($row->id)?$row->id:''; ?>">
           <div class="col-md-12 m_right">
              <div class="media user_p post_list_data">
                <?php
               if(!empty($row->user_id)&&$row->user_id==user_id()){?>
                  <a href="javascript:void(0);" class="model_link edit-icon" onclick="editPost('<?php echo !empty($row->id)?$row->id:''; ?>', 'addBlog');">
                     <img  src="<?php echo base_url(); ?>assets/front/img/edite_icon.png">
                  </a>
                  <a href="javascript:void(0);" onclick="deletePost('<?php echo !empty($row->id)?$row->id:''; ?>');" class="model_link delete-icon">
                     <i class="fa fa-times"></i>
                  </a>
               <?php }?>
                 <div class="">
                    <a href="javascript:void(0);" data-toggle="modal" data-target="#imageDetails" onclick="imageDetails('<?php echo !empty($row->id)?$row->id:''; ?>');" class="model_link"> 
                      <h4 class="media-heading">
                    		<?php if(!empty($row->title)) echo ucwords($row->title);  ?> 
                      </h4>
                      <p class="b_content">
                      	<?php echo  (!empty($row->description)&&strlen($row->description)>150)?substr($row->description, 0,150).'...':$row->description; ?>
                      </p>
                    </a>
                 </div>
              </div>
           </div>
        </div>
	<?php } 
    } 
}else{
  //echo '<div class="col-md-12 m_right text-center text-danger"><br/><br/><br/><br/><h2>No records found</h2></div>';
}?>