<style type="text/css">.circlesa {bottom: 35px;}.video_file{width: 100%; height: 190px;}</style>
<?php 
if(!empty($rows)){
  	foreach($rows as $row){               
	    if(!empty($row->image_name)&&file_exists('assets/uploads/recipeBlogImages/'.$row->image_name)){
	    	$files    = explode('.', $row->image_name);
	        $fileExt  = strtolower(end($files));
	        if($fileExt=='png'||$fileExt=='jpeg'||$fileExt=='jpg'){
	          	$filePath = '<img  src="'.base_url().'assets/uploads/recipeBlogImages/'.$row->image_name.'">';
	        }else if($fileExt=='mp4'){
	          	$filePath = '<video class="video_file"><source src="'.base_url().'assets/uploads/recipeBlogImages/'.$row->image_name.'" type="video/mp4"></video>';
	        }
	        if($row->imgCounts>1){$filePath .= '<div class="circleUpper circlesa"><div class="circle"></div><div class="circle"></div><div class="circle"></div></div>';}
	    ?>
	    <div class="col-md-3 col-xs-3 col-sm-6 user_images" id="post_id_<?php echo !empty($row->id)?$row->id:''; ?>">
	    	<div class="post_list_data">   
		        <a href="javascript:void(0);" data-toggle="modal" data-target="#imageDetails" onclick="imageDetails('<?php echo !empty($row->id)?$row->id:''; ?>');" class="model_link">
		          	<?php echo !empty($filePath)?$filePath:'';?>
		        	<h4 class="text-center"><?php if(!empty($row->title)) echo ucwords($row->title);  ?></h4>
		        </a>
		        <?php
                if(!empty($row->user_id)&&$row->user_id==user_id()){?>
                  	<a href="javascript:void(0);"  class="model_link edit-icon" onclick="editPost('<?php echo !empty($row->id)?$row->id:''; ?>', '<?php echo (!empty($row->type)&&$row->type=='recipe')?'addRecipe':'imagePost'; ?>');">
                     	<img  src="<?php echo base_url(); ?>assets/front/img/edite_icon.png">
                  	</a>
                  	<a href="javascript:void(0);" onclick="deletePost('<?php echo !empty($row->id)?$row->id:''; ?>');" class="model_link delete-icon">
                     	<i class="fa fa-times"></i>
              		</a>
           		<?php }?>
            </div>
	    </div>        
	<?php } 
  	} 
}else{
	//echo '<div class="col-md-12 m_right text-center text-danger"><br/><br/><br/><br/><h2>No records found</h2></div>';
}?> 