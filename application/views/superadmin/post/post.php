<!-- Content Header (Page header) -->
<section class="content-header tab_header">
  <h1><?php echo $this->input->get('type')?ucwords($this->input->get('type')):'Post'; ?> List</h1>
  <ol class="breadcrumb">
    <li>
      <a href="<?php echo ADMIN_URL.'superadmin/dashboard';?>">
        <i class="fa fa-dashboard"></i> Dashboard
      </a>
    </li>
    <li class="active"> 
      <?php echo $this->input->get('type')?ucwords($this->input->get('type')):'Post'; ?> List
    </li>
  </ol>
</section>
<section class="content-header" id="filter_main">
  <form action="" method="get">
    <div class="filter_box" style="width:115px">
      <input type="hidden" name="type" value="<?php echo $this->input->get('type')?$this->input->get('type'):'Post'; ?>">
      <label><?php echo $this->input->get('type')?ucwords($this->input->get('type')):'Post'; ?> ID</label>
      <input type="text" name="id" value="<?php if($this->input->get('id')){echo $this->input->get('id');}?>" class="form-control"  placeholder="Search <?php echo $this->input->post('type')?ucwords($this->input->post('type')):'Post'; ?> ID">
    </div> 
    <div class="filter_box" style="width:132px">
      <label><?php echo $this->input->get('type')?ucwords($this->input->get('type')):'Post'; ?> Name</label>
      <input type="text" name="post_title" value="<?php if($this->input->get('post_title')){echo $this->input->get('post_title');}?>" class="form-control"  placeholder="Search <?php echo $this->input->post('type')?post_title($this->input->post('type')):'Post'; ?> Name">
    </div>  
    <div class="filter_box" style="width:153px">
      <label>Customer ID</label>
      <input type="text" name="userID" value="<?php if($this->input->get('userID')){echo $this->input->get('userID');}?>" class="form-control"  placeholder="Search Customer ID">
    </div> 
    <!-- <div class="filter_box" style="width:153px">
      <label>User Name</label>
      <input type="text" name="userName" value="<?php if($this->input->get('userName')){echo $this->input->get('userName');}?>" class="form-control"  placeholder="Search User Name">
    </div>  -->
     <div class="filter_box" style="width:127px">
      <label>Start Date</label>
      <input type="text" name="start" value="<?php if($this->input->get('start')){echo $this->input->get('start');}?>" class="form-control datepicker" readonly  placeholder="Search Start Date">
    </div> 
     <div class="filter_box" style="width:125px">
      <label>End Date</label>
      <input type="text" name="end" value="<?php if($this->input->get('end')){echo $this->input->get('end');}?>" class="form-control datepicker" readonly  placeholder="Search End Date">
    </div> 
     <div class="filter_box" style="width:">
      <label>Order</label>      
      <select class="form-control" name="order">
        <option value="DESC" <?php if($this->input->get('order')&&$this->input->get('order')=='DESC'){echo 'selected';} ?>>New</option>
        <option value="ASC" <?php if($this->input->get('order')&&$this->input->get('order')=='ASC'){echo 'selected';} ?>>Old</option>
        <option value="NameAtoZ" <?php if($this->input->get('order')&&$this->input->get('order')=='NameAtoZ'){echo 'selected';} ?>>Name A-Z</option>
         <option value="NameZtoA" <?php if($this->input->get('order')&&$this->input->get('order')=='NameZtoA'){echo 'selected';} ?>>Name Z-A</option>
      </select>
    </div> 
    <div class="filter_box" style="width: 80px;">
      <button type="submit" class="btn btn-primary search_btn">
        <i class="fa fa-search" aria-hidden="true"></i>
      </button>
      <a href="<?php echo current_url(); echo $this->input->get('type')?'?type='.$this->input->get('type'):''; ?>" class="btn btn-danger search_btn">
        <i class="fa fa-refresh" aria-hidden="true"></i>
      </a>
    </div>
  </form>
</section>
<?php
$status_array = array('1'=>'Active','2'=>'Deactive','4'=>'Pending');?>
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box">            
        <!-- /.box-header -->
        <div class="box-body table-responsive">
          <table class="table table-bordered">
            <tr>
              <head>
                <th class="text-center">S.No.</th> 
                <th class="text-center">
                  <?php echo $this->input->get('type')?ucwords($this->input->get('type')):'Post'; ?> ID
                </th> 
                <th class="text-center">Customer ID</th> 
                <th class="text-center">User Name</th> 
                <th class="text-center">Image</th>
                <th class="text-left">
                  <?php echo $this->input->get('type')?ucwords($this->input->get('type')):'Post'; ?> Title
                </th>
                <th class="text-center">Views</th> 
                <th class="text-center">Likes</th> 
                <th class="text-center">Bookmark</th>
                <th class="text-center">Comments</th> 
                <th class="text-center">Posted Date</th> 
                <th class="text-center">Status</th>   
                <th class="text-center">Action</th> 
              </tr>
            </head>  
            <tbody>
            <?php
            $i = $offset + 1;
            if(!empty($rows)){
              foreach ($rows as $res_data){
                if(!empty($res_data->image_name)&&file_exists('assets/uploads/recipeBlogImages/thumbnails/'.$res_data->image_name)){
                  $file_names = base_url().'assets/uploads/recipeBlogImages/thumbnails/'.$res_data->image_name;
                }else if(!empty($res_data->coverImage)&&file_exists('assets/uploads/recipeBlogImages/thumbnails/'.$res_data->coverImage)){
                  $file_names = base_url().'assets/uploads/recipeBlogImages/thumbnails/'.$res_data->coverImage;
                }
                if(!empty($res_data->title)){
                  $title =  ucwords($res_data->title);
                }else if(!empty($res_data->caption)){
                  $title =  ucwords($res_data->caption);
                }else if(!empty($res_data->location)){
                  $title =  ucwords($res_data->location);
                }
                ?>
                <tr>
                  <td class="text-center"><?php echo $i; ?></td> 
                  <td class="text-center"><?php echo !empty($res_data->id)?'#'.$res_data->id:'-';?></td>
                  <td class="text-center"><?php echo !empty($res_data->user_id)?'#'.$res_data->user_id:'-';?></td>
                  <td class="text-left">
                    <?php  
                      echo !empty($res_data->first_name)? ucfirst($res_data->first_name):'-';
                      echo !empty($res_data->last_name)? ' '.ucfirst($res_data->last_name):' ';
                    ?>
                  </td>
                  <td class="text-center"><?php  echo !empty($file_names)?'<img src="'.$file_names.'" width="50">':'-';?></td>
                  <td class="text-left"><?php  echo !empty($title)?$title:'-';?></td>
                  <td class="text-center"> 
                    <?php 
                     if(!empty($res_data->views)){?>
                      <a  class="btn btn-info" data-toggle="modal" data-target="#myModal" href="javascript:void(0);" onclick="veiwpostActivilty('<?php if(!empty($res_data->id)) echo $res_data->id; ?>','<?php if(!empty($title)) echo ucwords($title); ?>','views','<?php echo $this->input->get('type')?ucwords($this->input->get('type')):'Post'; ?>');");"><?php echo $res_data->views; ?></a>
                     <?php }else{
                      echo '0';
                     }
                    ?>                      
                  </td>
                  <td class="text-center"> 
                    <?php 
                     if(!empty($res_data->likes)){?>
                      <a  class="btn btn-info" data-toggle="modal" data-target="#myModal" href="javascript:void(0);" onclick="veiwpostActivilty('<?php if(!empty($res_data->id)) echo $res_data->id; ?>','<?php if(!empty($title)) echo ucwords($title); ?>','likes','<?php echo $this->input->get('type')?ucwords($this->input->get('type')):'Post'; ?>');");"><?php echo $res_data->likes; ?></a>
                     <?php }else{
                      echo '0';
                     }
                    ?>                      
                  </td>
                  <td class="text-center">
                    <?php 
                     if(!empty($res_data->bookmarked)){?>
                      <a  class="btn btn-info" data-toggle="modal" data-target="#myModal" href="javascript:void(0);" onclick="veiwpostActivilty('<?php if(!empty($res_data->id)) echo $res_data->id; ?>','<?php if(!empty($title)) echo ucwords($title); ?>','bookmarked','<?php echo $this->input->get('type')?ucwords($this->input->get('type')):'Post'; ?>');");"><?php echo $res_data->bookmarked; ?></a>
                     <?php }else{
                      echo '0';
                     }?>
                  </td>
                  <td class="text-center">
                    <?php 
                     if(!empty($res_data->comments)){?>
                      <a  class="btn btn-info" data-toggle="modal" data-target="#myModal" href="javascript:void(0);" onclick="veiwpostActivilty('<?php if(!empty($res_data->id)) echo $res_data->id; ?>','<?php if(!empty($title)) echo ucwords($title); ?>','comments','<?php echo $this->input->get('type')?ucwords($this->input->get('type')):'Post'; ?>');");"><?php echo $res_data->comments; ?></a>
                     <?php }else{
                      echo '0';
                     }?>
                  </td>
                  <td class="text-center"><?php  echo !empty($res_data->created_date)?date('d M Y h:i A', strtotime($res_data->created_date)):'-';?></td>
                  <td class="text-center"> 
                    <div class="dropdown">
                      <button class="<?php echo btnOrder($res_data->status);?> btn-xs  dropdown-toggle_get_val" id="menu1" type="button" data-toggle="dropdown"><?php 
                      foreach($status_array as $k => $status_a)
                      {
                        if(!empty($res_data->status) && $k == $res_data->status) 
                          echo $status_a;
                      }
                       ?>
                      <span class="caret"></span></button>  
                      <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                        <?php
                         foreach($status_array as $k => $status_a){
                            if(!empty($res_data->status) && $k != $res_data->status&&$k != 4){?>
                        <li role="presentation">
                          <a role="menuitem" tabindex="-1" href="javascript:void(0);" onclick="changeStatus('recipe_blog_image','<?php echo $this->input->post('type')?ucwords($this->input->post('type')):'Post'; ?>','<?php if(!empty($res_data->user_id)) echo $res_data->user_id; ?>','<?php echo $k; ?>','<?php if($k==1){ echo 'activate';}else if($k==3){ echo 'delete';}else{ echo 'deactive';}?>','id','status');">
                            <?php echo $status_a; ?>
                          </a>
                        </li>
                        <?php } 
                       } ?>          
                      </ul>
                    </div>
                  </td>
                  <td class="text-center">  
                    <a class="btn btn-sm btn-info" data-toggle="modal" data-target="#myModal" href="javascript:void(0);" onclick="viewPostDetails('<?php if(!empty($res_data->id)) echo $res_data->id; ?>','<?php if(!empty($title)) echo ucwords($title); ?>');");">
                      <i class="fa fa-eye" aria-hidden="true"></i>
                    </a>                       
                    <a class="btn btn-sm btn-danger" href="javascript:void(0);" onclick="changeStatus('recipe_blog_image','<?php echo $this->input->post('type')?ucwords($this->input->get('type')):'Post'; ?>','<?php if(!empty($res_data->id)) echo $res_data->id; ?>','3','delete','id','status');");">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </a>                                   
                  </td>
                </tr>
                <?php $i++;
                    } 
                  }else{?>
                  <tr >
                    <td colspan="13" class="text-center text-danger">
                      <span class="data-not-present">
                        <?php echo 'No ';
                              echo $this->input->get('type')?ucwords($this->input->get('type')):'Post';
                              echo ' records found'; ?>                
                      </span>
                    </td>
                  </tr>              
              <?php } ?> 
             <tbody>         
          </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">         
          <div class="text-right">
            <?php if(!empty($pagination)) echo $pagination; ?>
          </div>
        </div>
      </div>          
    </div>        
  </div>
</section>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header modelheads">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title" id="modelTitle"></h2>
      </div>
      <div class="modal-body" id="modelData"></div>     
    </div>
  </div>
</div>