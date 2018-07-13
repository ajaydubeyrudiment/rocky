<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    User List
  </h1>
  <ol class="breadcrumb">
    <li>
      <a href="<?php echo ADMIN_URL.'superadmin/dashboard';?>">
        <i class="fa fa-dashboard"></i> 
        Dashboard
      </a>
    </li>
    <li class="active">
      User List
    </li>
  </ol>
</section>
<section class="content-header" id="filter_main">
  <form action="" method="get">
    <div class="filter_box" style="width:115px">
      <label>Name</label>
      <input type="text" name="name" value="<?php if($this->input->get('name')){echo $this->input->get('name');}?>" class="form-control"  placeholder="Search Name">
    </div> 
    <div class="filter_box" style="width:132px">
      <label>Username</label>
      <input type="text" name="user_name" value="<?php if($this->input->get('user_name')){echo $this->input->get('user_name');}?>" class="form-control"  placeholder="Search Username">
    </div>  
    <div class="filter_box" style="width:153px">
      <label>Email</label>
      <input type="text" name="email" value="<?php if($this->input->get('email')){echo $this->input->get('email');}?>" class="form-control"  placeholder="Search Email">
    </div>  
    <div class="filter_box" style="width:132px">
      <label>Mobile</label>
      <input type="text" name="mobile" value="<?php if($this->input->get('mobile')){echo $this->input->get('mobile');}?>" class="form-control"  placeholder="Search Mobile No.">
    </div>  
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
      <a href="<?php echo current_url();?>" class="btn btn-danger search_btn">
        <i class="fa fa-refresh" aria-hidden="true"></i>
      </a>
    </div>
  </form>
</section>
<?php $status_array = array('1'=>'Active','2'=>'Suspend','4'=>'Pending');?>
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box">            
        <!-- /.box-header -->
        <div class="box-body">
          <table class="table table-bordered">
            <tr>
              <head>
                <th class="text-center">S.No.</th> 
                <th class="text-center">Customer ID</th>     
                <th class="text-left">Name </th>     
                <th class="text-left">Username </th>     
                <th class="text-left">Email</th>  
                <th class="text-center">Followers</th>  
                <th class="text-center">followings</th>  
                <th class="text-center">Created Date &amp; Time</th>                
                <th class="text-center">Last Login</th>     
                <th class="text-center">Status</th> 
                <th class="text-center">Action</th>
              </tr>
            </head>  
            <tbody>
            <?php
               $i = $offset + 1;
                if(!empty($users)){
                  foreach ($users as $res_data){
                  ?>
                <tr>
                    <td class="text-center">
                      <?php echo $i; ?>
                    </td> 
                    <td class="text-center">
                    <?php if(!empty($res_data->id)){
                         echo '#'.$res_data->id;
                      }else{
                        echo '-';
                        }  ?>        
                    </td>    
                    <td class="text-left">      
                    <?php      
                     if(!empty($res_data->first_name)){
                        echo ucfirst($res_data->first_name);
                      }else{
                        echo '-';
                        }                
                       ?>        
                  </td>    
                    <td class="text-left">      
                    <?php      
                     if(!empty($res_data->user_name)){
                        echo ucfirst($res_data->user_name);
                      }else{
                        echo '-';
                        }                
                       ?>        
                  </td>    
                  <td class="text-left">
                    <?php       
                      if(!empty($res_data->email)) echo $res_data->email;     
                    ?>
                  </td> 
                  <td class="text-center">
                    <?php       
                      echo !empty($res_data->followingCount)?$res_data->followingCount:0;     
                    ?>
                  </td> 
                  <td class="text-center">
                    <?php       
                      echo !empty($res_data->followersCount)?$res_data->followersCount:0;     
                    ?>
                  </td>      
                  <td class="text-center">  
                    <?php if(!empty($res_data->created_date)){        
                         echo date('d M Y h:i A', strtotime($res_data->created_date)); 
                      }else{ echo '-';} ?>        
                  </td>
                  <td class="text-center">  
                    <?php if(!empty($res_data->last_login)&&$res_data->last_login!='0000-00-00 00:00:00'){
                             echo date('d M Y h:i A', strtotime($res_data->last_login));
                    }else{ echo '-';} ?>        
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
                         foreach($status_array as $k => $status_a)
                          {
                            if(!empty($res_data->status) && $k != $res_data->status&&$k != 4)
                            {
                        ?>
                        <li role="presentation">
                          <a role="menuitem" tabindex="-1" href="javascript:void(0);" onclick="changeUserStatus('<?php if(!empty($res_data->id)) echo $res_data->id; ?>','<?php echo $k; ?>','<?php if($k==1){ echo 'activate';}else if($k==3){ echo 'delete';}else{ echo 'suspend';}?>');">
                            <?php echo $status_a; ?>
                          </a>
                        </li>
                        <?php } 
                       } ?>          
                      </ul>
                      </div>
                  </td>  
                  <td class="text-center">      
                     <a class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" href="<?php echo base_url('superadmin/post?type=blog&userName='.$res_data->first_name.'&order=DESC');?>" title="Blogs">
                        <img src="<?php echo base_url('assets/admin/img/blogs.png'); ?>" width="15">
                     </a> 
                      <a class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" href="<?php echo base_url('superadmin/post?type=image&userName='.$res_data->first_name.'&order=DESC');?>" title="Images">
                        <img src="<?php echo base_url('assets/admin/img/image.png'); ?>" width="15">
                     </a>
                      <a class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" href="<?php echo base_url('superadmin/post?type=recipe&userName='.$res_data->first_name.'&order=DESC');?>" title="Recipes">
                        <img src="<?php echo base_url('assets/admin/img/Recipes.png'); ?>" width="15">
                     </a>   
                    <a title="Customer Details" data-toggle="modal" onclick="userInfo('<?php if(!empty($res_data->id)) echo $res_data->id; ?>','<?php if(!empty($res_data->first_name)) echo ucwords($res_data->first_name); if(!empty($res_data->last_name)) echo ' '.$res_data->last_name; ?>','workout')" data-target="#userDetails" class="btn btn-info btn-sm tooltips" >          
                        <img src="<?php echo base_url('assets/admin/img/workout_plan.png'); ?>" width="15">
                    </a> 
                    <a title="Customer Details" data-toggle="modal" onclick="userInfo('<?php if(!empty($res_data->id)) echo $res_data->id; ?>','<?php if(!empty($res_data->first_name)) echo ucwords($res_data->first_name); if(!empty($res_data->last_name)) echo ' '.$res_data->last_name; ?>','diet')" data-target="#userDetails" class="btn btn-info btn-sm tooltips" >          
                       <img src="<?php echo base_url('assets/admin/img/diet_plan.png'); ?>" width="15">
                    </a>   
                    <a title="Customer Details" data-toggle="modal" onclick="userInfo('<?php if(!empty($res_data->id)) echo $res_data->id; ?>','<?php if(!empty($res_data->first_name)) echo ucwords($res_data->first_name); if(!empty($res_data->last_name)) echo ' '.$res_data->last_name; ?>','user_info')" data-target="#userDetails" class="btn btn-info btn-sm tooltips" >          
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </a> 
                  </td>   
                </tr>
                <?php $i++;
                    } 
                  }else{?>
                      <tr >
                        <td colspan="8" class="error">
                          <span class="data-not-present">
                            <?php echo 'No user records found'; ?>                
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
<!-- Modal -->
<div class="modal fade" id="userDetails" role="dialog">
  <div class="modal-dialog" id="modal-box">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#3C8DBC;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          <div class="row">
            <div class="col-md-12"> 
              <h4 class="modal-title text-center">
                <b>
                  <span id="customerName" style="color: #fff"></span> 
                </b>
              </h4>
            </div>
          </div>
      </div>
      <div class="modal-body">
        <div class="row" id="customer_infos"></div>                      
      </div>
      <div class="modal-footer">          
      </div>
    </div>
  </div>
</div>
<!-- /.content -->
<script type="text/javascript">
  function userInfo(customerID='', customerName='',planType=''){       
    $("#modal-box").css('width','1200px');      
    if(customerName!=""){
       $("#customerName").html(customerName); 
       $(".userNameM").show(); 
       $(".customerName").html(customerName); 
    }  
    $.ajax({
      url:base_url+"superadmin/customer/customer_details",
      type:"get",    
      data:{'customer_id':customerID,'planType':planType},
      success: function(html){
        $('#customer_infos').html(html);  
      }
    });      
  }
</script>