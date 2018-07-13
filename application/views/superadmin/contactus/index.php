<div class="bread_parent">
  <div class="col-md-12">
    <ul class="breadcrumb">
        <li>
          <a href="<?php echo base_url('superadmin/superadmin/dashboard');?>"> Dashboard  </a>
        </li>
        <li class=""> Contact Us</li> 
    </ul>
  </div>
</div>
<?php
  $ASC= $DESC= $all = $read = $unread = $pending_message = $reply_message = "";  
  if($this->input->get('order')) {
    if($this->input->get('order')=="DESC"){
        $DESC = 'selected';
    }else{
        $ASC  = 'selected';
    }
  }
   if($this->input->get('status')) {
    if($this->input->get('status')=="read"){
        $read = 'selected';
    }else if($this->input->get('status')=="unread"){
        $unread = 'selected';
    }else if($this->input->get('status')=="pending_message"){
        $pending_message = 'selected';
    }else if($this->input->get('status')=="reply_message"){
        $reply_message = 'selected';
    }else{
        $all  = 'selected';
    }
  }
?>
<section class="panel">
 <!--   <div   id="demo" user="collapse" > -->
  <div class="col-lg-12 no-padding"> 
     <form action="<?php echo base_url() ?>superadmin/contactus/index" method="get" accept-charset="utf-8">
          <div class="box_wrap">
            <label>Contact Name</label>
            <input type="text" placeholder="Search by Contact Name" 
                class="form-control" name="name" value="<?php echo $this->input->get('name');?>"/>
          </div>
         <div class="box_wrap">
            <label>Contact No</label>
            <input type="text" name="Mobile" value="<?php   echo $this->input->get('Mobile');  ?>" class="form-control" placeholder="Search by Contact No.">
          </div>
          <div class="box_wrap">
            <label>Status</label>
              <select name="status" class="form-control">
                <option value="All" <?php  echo $all; ?>>All</option>
                <option value="pending_message" <?php  echo $pending_message; ?>>Pending Message</option>
                <option value="reply_message" <?php  echo $reply_message; ?>> Reply Message</option>
                <option value="read" <?php  echo $read; ?>>Read</option>
                <option value="unread" <?php  echo $unread; ?>>Unread</option>
              </select>
          </div>
         <div class="box_wrap">
            <label>Sort By</label>
             <select name="order" class="form-control">
                <option value="DESC" <?php  echo $DESC; ?>>New</option>
                <option value="ASC" <?php  echo $ASC; ?>>Old</option>
              </select>
          </div>
        <button type="submit" class="btn btn-primary search-btn-new">
          <i class="fa fa-search"></i>
        </button>
        <a href="<?php echo base_url('superadmin/contactus') ?>" class="btn btn-danger search-btn-new">
          <i class="fa fa-refresh"></i>
        </a>
    </form>
  </div>
  <div class="clearfix"></div>  
      <header class="panel-heading"  ></header>
      <table id="example1" class="table table-striped table-hover eco-admin-list-table" >
        <thead class="thead_color">
          <tr>
            <th width="5%" class="text-center">ID</th>
            <th width="15%">Contact Name</th>
            <th width="15%">Contact No.</th>
            <th>Message</th>  
            <th width="20%" class="text-center">Created Date & Time</th>
            <th width="7%" style="text-align:center">Reply</th>
            <th width="7%" style="text-align:center">Status</th>
            <th width="5%" style="text-align:center">Actions</th>
          </tr>
        </thead>
          <?php  
          if(!empty($contacts)){
            $j=$offset+1;           
            foreach($contacts as $row){?>
            <tbody>
            <tr>
                <td class="text-center">
                  <?php echo $j." ".NO_STYLE; ?>
                </td>
                <td>
                  <?php echo ucwords($row->name); ?>
                </td>
                <td>
                  <?php echo $row->mobile ?>                  
                </td>
                <td>
                  <?php 
                  if(strlen($row->message)>50){
                      echo substr($row->message, 0, 50)." ..";
                  }else{   echo $row->message ;}?>
                </td>
                <td class="to_hide_phone text-center">
                  <?php echo date('d M Y,h:i  A',strtotime($row->created)); ?>
                </td>
                <td class="ms2" style="text-align:center">
                    <?php if($row->status==0) { echo "<label class='label label-warning label-mini'> No </label>"; }else{ echo "<label class='label label-primary label-mini'> Yes </label>"; }   ?>
                </td>
                <td class="ms2" style="text-align:center">
                  <?php if($row->read_status==0) { echo "<label class='label label-danger label-mini'> Unread </label>"; }else{ echo "<label class='label label-success label-mini'> Read </label>"; }   ?>                    
                </td>                
                <td class="ms1 ms" style="text-align:center">
                  <a href="<?php echo base_url().'superadmin/contactus/contactus_reply/'.$row->id ?>" class="btn btn-primary btn-xs tooltips rpl" rel="tooltip"  data-placement="left" data-original-title="Reply" >
                    Reply
                  </a>
                </td>
              </tr> 
            </tbody> 
          <?php $j++;
              }
            } else{?>
          <tr>
           <td colspan="8" class="msg"> <span class="data-not-present"><?php echo 'No contact records found'; ?></span></td>
          </tr>
        <?php } ?> 
    </table>
<br />
<div class="pagination-side">
  <?php echo $pagination;?>
</div>
</section>