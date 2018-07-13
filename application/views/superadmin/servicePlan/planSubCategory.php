<!-- Content Header (Page header) -->
<section class="content-header tab_header">
  <h1>Sub Category List</h1>
  <ol class="breadcrumb">
    <li>
      <a href="<?php echo ADMIN_URL.'superadmin/dashboard';?>">
        <i class="fa fa-dashboard"></i>Sub Dashboard
      </a>
    </li>
    <li class="active">Sub Category List</li>
  </ol>
</section>
<section class="content-header" id="filter_main">
  <form action="" method="get">
    <div class="filter_box" style="width:">
      <label>Subcategory ID</label>
      <input type="text" name="type_id" value="<?php if($this->input->get('type_id')){echo $this->input->get('type_id');}?>" class="form-control"  placeholder="Search Subcategory ID">
    </div>
    <div class="filter_box" style="width:">
      <label>Plan</label>
      <select class="form-control" name="plan_id" id="selectPlan" onchange="selectPlans();">
         <option value="">All Plan</option>
        <?php 
        if(!empty($plans)){
          foreach ($plans as $plan) {?>
            <option value="<?php echo !empty($plan->id)?$plan->id:''; ?>" <?php if($this->input->get('plan_id')&&$this->input->get('plan_id')==$plan->id){echo 'selected';} ?>>
              <?php echo !empty($plan->title)?$plan->title:''; ?>
            </option>
          <?php }
        } ?>        
      </select>
    </div> 
    <div class="filter_box" style="width:">
      <label>Category</label>
      <select class="form-control" name="category_id" id="category_id" onchange="selectsubcategory();">
         <option value="">All Category</option>
        <?php 
        if(!empty($categorys)){
          foreach ($categorys as $category) {?>
            <option value="<?php echo !empty($category->id)?$category->id:''; ?>" <?php if($this->input->get('category_id')&&$this->input->get('category_id')==$category->id){echo 'selected';} ?>>
              <?php echo !empty($category->title)?$category->title:''; ?>
            </option>
          <?php }
        } ?>        
      </select>
    </div> 
    <div class="filter_box" style="width:">
      <label>Subcategory</label>
      <input type="text" name="plan_type" value="<?php if($this->input->get('plan_type')){echo $this->input->get('plan_type');}?>" class="form-control"  placeholder="Search Subcategory">
    </div>
    <div class="filter_box" style="width:">
      <label>Order</label>
      <select class="form-control" name="order">
        <option value="DESC" <?php if($this->input->get('order')&&$this->input->get('order')=='DESC'){echo 'selected';} ?>>New</option>
        <option value="ASC" <?php if($this->input->get('order')&&$this->input->get('order')=='ASC'){echo 'selected';} ?>>Old</option>
      </select>
    </div> 
    <div class="filter_box" style="width: 80px;">
      <button type="submit" class="btn btn-primary search_btn">
        <i class="fa fa-search" aria-hidden="true"></i>
      </button>
      <a href="<?php echo current_url();?>" class="btn btn-danger search_btn">
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
                <th class="text-left">Pic</th>
                <th class="text-center">Subcategory ID</th> 
                <th class="text-left">Plan Name</th>
                <th class="text-left">Category</th> 
                <th class="text-left">Subcategory</th>
                <th class="text-center">Status</th>   
                <th class="text-center">Action</th> 
              </tr>
            </head>  
            <tbody>
            <?php
            $i = $offset + 1;
            if(!empty($rows)){
              foreach ($rows as $res_data){?>
                <tr>
                  <td class="text-center"><?php echo $i; ?></td> 
                  <td class="text-left">
                    <?php  
                    if(!empty($res_data->itemPic)&&file_exists('assets/uploads/planItem/thumbnails/'.$res_data->itemPic)){
                      echo '<img src="'.base_url().'assets/uploads/planItem/thumbnails/'.$res_data->itemPic.'" width="50"/>';
                    }else if(!empty($res_data->itemPic)&&file_exists('assets/uploads/planItem/'.$res_data->itemPic)){
                      echo '<img src="'.base_url().'assets/uploads/planItem/'.$res_data->itemPic.'" width="50"/>';
                    }else{
                      echo '-';
                    }
                  ?>
                  </td>
                  <td class="text-center"><?php  echo !empty($res_data->id)?'#'.$res_data->id:'-';?></td> 
                  <td class="text-left">
                    <?php  echo !empty($res_data->plan_name)? ucfirst($res_data->plan_name):'-';?>
                  </td>
                  <td class="text-left">
                    <?php  echo !empty($res_data->category_name)? ucfirst($res_data->category_name):'-';?>
                  </td>
                  <td class="text-left">
                    <?php  echo !empty($res_data->title)? ucfirst($res_data->title):'-';?>
                  </td>                  
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
                            if(!empty($res_data->status) && $k != $res_data->status&&$k != 4){
                        ?>
                        <li role="presentation">
                          <a role="menuitem" tabindex="-1" href="javascript:void(0);" onclick="changeStatus('service_plan_item','Subcategory','<?php if(!empty($res_data->id)) echo $res_data->id; ?>','<?php echo $k; ?>','<?php if($k==1){ echo 'activate';}else if($k==3){ echo 'delete';}else{ echo 'deactive';}?>','id','status');">
                            <?php echo $status_a; ?>
                          </a>
                        </li>
                        <?php } 
                       } ?>          
                      </ul>
                    </div>
                  </td>
                  <td class="text-center"> 
                    <a class="btn btn-sm btn-primary" href="<?php echo ADMIN_URL.'servicePlan/addPlanSubCategory/';?><?php if(!empty($res_data->id)) echo $res_data->id;?>">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                     </a>   
                     <a class="btn btn-sm btn-danger" href="javascript:void(0);" onclick="changeStatus('service_plan_item','Subcategory','<?php if(!empty($res_data->id)) echo $res_data->id; ?>','3','delete','id','status');");">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                     </a>                                        
                  </td>
                </tr>
                <?php $i++;
                    } 
                  }else{?>
                  <tr >
                    <td colspan="7" class="text-center text-danger">
                      <span class="data-not-present">
                        <?php echo 'No subcategory records found'; ?>                
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
<script type="text/javascript">
   function selectPlans() {
      var planID = $('#selectPlan').val(); 
      $.ajax({
        url:base_url+"superadmin/servicePlan/getCategorys",
        type:"get",    
        data:{'plan_id':planID},
        success: function(html){
          $('#category_id').html(html);  
        }
      });
   }
</script>