<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Influencer List
  </h1>
  <ol class="breadcrumb">
    <li>
      <a href="<?php echo ADMIN_URL.'superadmin/dashboard';?>">
        <i class="fa fa-dashboard"></i> 
        Dashboard
      </a>
    </li>
    <li class="active">
      Influencer List
    </li>
  </ol>
</section>
<section class="content-header" id="filter_main">
  <form action="" method="get">
    <div class="filter_box" style="width:115px">
      <label>Name</label>
      <input type="text" name="name" value="<?php if($this->input->get('name')){echo $this->input->get('name');}?>" class="form-control"  placeholder="Search name">
    </div> 
    <div class="filter_box" style="width:132px">
      <label>Username</label>
      <input type="text" name="user_name" value="<?php if($this->input->get('user_name')){echo $this->input->get('user_name');}?>" class="form-control"  placeholder="Search Username">
    </div>  
    <div class="filter_box" style="width:193px">
      <label>Minimum Followers </label>
      <input type="text" name="minimum_follow" value="<?php if($this->input->get('minimum_follow')){echo $this->input->get('minimum_follow');}?>" class="form-control"  placeholder="Search Minimum Followers ">
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
                <th class="text-center">Name</th>     
                <th class="text-left">Username</th>     
                <th class="text-left">Email</th>  
                <th class="text-center">Followers</th> 
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
                      echo !empty($res_data->followersCount)?$res_data->followersCount:0;     
                    ?>
                  </td> 
                </tr>
                <?php $i++;
                    } 
                  }else{?>
                      <tr >
                        <td colspan="8" class="error">
                          <span class="data-not-present">
                            <?php echo 'No influencers records found'; ?>                
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