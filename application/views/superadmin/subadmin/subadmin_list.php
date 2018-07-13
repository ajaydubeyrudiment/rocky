<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Subadmin List
  </h1>
  <ol class="breadcrumb">
    <li>
      <a href="<?php echo ADMIN_URL.'superadmin/dashboard';?>">
        <i class="fa fa-dashboard">
        </i> 
        Dashboard
      </a>
    </li>
    <li class="active">
      Subadmin List
    </li>
  </ol>
</section>
<section class="content-header" id="filter_main">
  <form action="" method="get">
    <div class="filter_box" style="width:">
      <label>Subadmin ID</label>
      <input type="text" name="id" value="<?php if($this->input->get('id')){echo $this->input->get('id');}?>" class="form-control"  placeholder="Search Subadmin ID">
    </div>
    <div class="filter_box" style="width:">
      <label>Subadmin Name</label>
      <input type="text" name="title" value="<?php if($this->input->get('title')){echo $this->input->get('title');}?>" class="form-control"  placeholder="Search Subadmin Name">
    </div>
    <div class="filter_box" style="width:">
      <label>Subadmin Email</label>
      <input type="text" name="email" value="<?php if($this->input->get('email')){echo $this->input->get('email');}?>" class="form-control"  placeholder="Search Subadmin Email">
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
$status_array = array('1'=>'Active','2'=>'Deactivate','4'=>'Pending');?>
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
                <th class="text-center">Subadmin ID</th>     
                <th class="text-left">Subadmin Name</th>     
                <th class="text-left">Email</th>  
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
              foreach ($users as $res_data){?>
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
                  echo !empty($res_data->last_name)?' '.ucwords($res_data->last_name):'';
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
                <?php if(!empty($res_data->created)){        
                        echo date('d M Y h:i A', strtotime($res_data->created)); 
                      }else{ echo '-';} ?>        
              </td>
              <td class="text-center">  
                <?php if(!empty($res_data->last_login)&&$res_data->last_login!='0000-00-00 00:00:00'){
                        echo date('d M Y h:i A', strtotime($res_data->last_login));
                      }else{ echo '-';} ?>        
              </td>    
              <td class="text-center"> 
                <div class="dropdown">
                  <button class="<?php echo btnOrder($res_data->status);?> btn-xs  dropdown-toggle_get_val" id="menu1" type="button" data-toggle="dropdown">
                    <?php 
                      foreach($status_array as $k => $status_a)
                      {
                        if(!empty($res_data->status) && $k == $res_data->status) 
                         echo $status_a;
                        }?>
                    <span class="caret"></span>
                  </button>  
                  <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                    <?php
                      foreach($status_array as $k => $status_a){
                      if(!empty($res_data->status) && $k != $res_data->status&&$k != 4){?>
                    <li role="presentation">
                      <a role="menuitem" tabindex="-1" href="javascript:void(0);" onclick="changeStatus('admin_users','Subadmin','<?php if(!empty($res_data->id)) echo $res_data->id; ?>','<?php echo $k; ?>','<?php if($k==1){ echo 'activate';}else if($k==3){ echo 'delete';}else{ echo 'suspend';}?>');">
                        <?php echo $status_a; ?>
                      </a>
                    </li>
                    <?php } 
                      } ?>          
                  </ul>
                </div>
              </td>  
              <td class="text-center">           
                <a class="btn btn-sm btn-primary" href="<?php echo ADMIN_URL.'subadmin/addSubadmin/';?><?php if(!empty($res_data->id)) echo $res_data->id;if($this->input->get('module_id')){ echo '?module_id='.$this->input->get('module_id').'&list_module='.$this->input->get('module_id');}if($this->input->get('main_module')){ echo '&main_module='.$this->input->get('main_module');}?>">
                  <i class="fa fa-pencil" aria-hidden="true"></i>
               </a> 
               <a class="btn btn-sm btn-danger" href="javascript:void(0);" onclick="changeStatus('admin_users','Subadmin','<?php if(!empty($res_data->id)) echo $res_data->id; ?>','3','delete');">
                      <i class="fa fa-trash" aria-hidden="true"></i>
                    </a> 
              </td>
            </tr>
            <?php $i++;
              } 
            }else{?>
            <tr >
              <td colspan="8" class="error">
                <span class="data-not-present">
                  <?php echo 'No subadmin records found'; ?>                
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
        <button type="button" class="close" data-dismiss="modal">&times;
        </button>
        <div class="row">
          <div class="col-md-12"> 
            <h4 class="modal-title text-center">
              <b>
                <span id="customerName">
                </span> 
              </b>
            </h4>
          </div>
        </div>
      </div>
      <div class="modal-body">
        <div class="row" id="customer_infos">
          <div class="col-md-8">
            <table class="table table-hover table-bordered">
              <tr class="userNameM">
                <th>Full Name</th>
                <td class="customerName">
                </td>
              </tr>           
              <tr class="emailM">
                <th>Email</th>
                <td class="email">
                </td>
              </tr>
               <tr class="mobileM">
                <th>Mobile
                </th>
                <td class="mobile">
                </td>
              </tr>
              <tr class="genderM">
                <th>Gender
                </th>
                <td class="gender">
                </td>
              </tr>
              <tr class="addressM">
                <th>Address
                </th>
                <td class="address">
                </td>
              </tr> 
              <tr class="last_loginM">
                <th>Last Login
                </th>
                <td class="last_login">
                </td>
              </tr>
              <tr class="last_ipM">
                <th>Last IP
                </th>
                <td class="last_ip">
                </td>
              </tr>
              <tr class="createdM">
                <th>Registered Date</th>
                <td class="created">
                </td>
              </tr>
            </table>
          </div>
          <div class="col-md-4">               
            <div class="fileM">
            </div>
          </div>
        </div>                      
      </div>
      <div class="modal-footer">          
      </div>
    </div>
  </div>
</div>
<!-- /.content -->
<script type="text/javascript">
  function userInfo(customerID='',customerName="", mobile='', email='', gender='', address='' ,last_login='', last_ip='', created='', file=''){
    $("#modal-box").css('width','800px');
    if(customerName!=""){
      $("#customerName").html(customerName);
      $(".userNameM").show();
      $(".customerName").html(customerName);
    }
    if(mobile!=""){
      $(".mobileM").show();
      $(".mobile").html(mobile);
    }else{
      $(".mobileM").hide();
    }
    if(email!=""){
      $(".emailM").show();
      $(".email").html(email);
    }
    if(gender!=""){
      $(".genderM").show();
      $(".gender").html(gender);
    }
    else{
      $(".genderM").hide();
    }
    if(address!=""){
      $(".addressM").show();
      $(".address").html(address);
    }
    else{
      $(".addressM").hide();
    }
    if(last_login!=""){
      $(".last_loginM").show();
      $(".last_login").html(last_login);
    }
    if(last_ip!=""){
      $(".last_ipM").show();
      $(".last_ip").html(last_ip);
    }
    if(created!=""){
      $(".createdM").show();
      $(".created").html(created);
    }
    if(file!=""){
      $(".fileM").show();
      $(".fileM").html('<img class="profilePics img-responsive" src="'+file+'">');
    }else{
      $(".fileM").hide();
    }
  }
</script>
