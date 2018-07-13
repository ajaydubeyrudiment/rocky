<div class="bread_parent">
<div class="col-md-12">
  <ul class="breadcrumb">
      <li><a href="<?php echo base_url('/superadmin/dashboard');?>"><i class="icon-home"></i> Dashboard  </a></li>  
       <li><a href="<?php echo base_url('/email_templates/');?>"><b>Email Templates</b></a></li>
       <li><b>Email Template Add</b></li>
  </ul>
</div>
<div class="clearfix"></div>
</div> <br>
<div class="panel-body ">
<div class="tab-pane row-fluid fade in active" id="tab-1">
<form role="form" class="form-horizontal tasi-form" action="<?php echo current_url()?>" enctype="multipart/form-data" method="post" id="form_valid">
  <div class="form-body">
    <div class="form-group">
      <label class="col-md-3 control-label">Title <span class="mandatory">*</span></label>
      <div class="col-md-6">
        <input type="text" placeholder="Title" class="form-control" name="template_name" value="<?php echo set_value('template_name');?>" data-bvalidator="required" data-bvalidator-msg="Title required"><?php echo form_error('template_name'); ?>
      </div>
    </div>

     <div class="form-group">
      <label class="col-md-3 control-label">Subject <span class="mandatory">*</span></label>
      <div class="col-md-6">
        <input type="text" placeholder="Subject" class="form-control" name="template_subject" value="<?php echo set_value('template_subject');?>" data-bvalidator="required" data-bvalidator-msg="Subject required"><?php echo form_error('template_subject'); ?>
      </div>
    </div>  

    <div class="form-group">
      <label class="col-md-3 control-label">Body<span class="mandatory">*</span></label>
      <div class="col-md-6">
        <textarea name="template_body" class="tinymce_edittor form-control" placeholder="Body" data-bvalidator="required" data-bvalidator-msg="Body required"><?php echo set_value('template_body');?></textarea>
        <?php echo form_error('template_body'); ?>
      </div>
    </div> 
    
    <div class="form-group">
      <label class="col-md-3 control-label">Admin Subject</label>
      <div class="col-md-6">
        <input type="text" name="template_subject_admin" class="form-control" value="<?php echo set_value('template_subject_admin');?>" placeholder="Admin Subject" >
        <?php echo form_error('template_subject_admin'); ?>
      </div>
    </div> 


     <div class="form-group">
      <label class="col-md-3 control-label">Admin Body</label>
      <div class="col-md-6">
        <textarea name="template_body_admin" class="tinymce_edittor form-control" placeholder="News Description" ><?php echo set_value('template_body_admin');?></textarea>
        <?php echo form_error('template_body_admin'); ?>
      </div>
    </div>    
  
   
  <div class="form-actions fluid">
    <div class="col-md-offset-2 col-md-10">
      <button  class="btn btn-primary tooltips" rel="tooltip" data-placement="top" data-original-title="Add Email Templates" type="submit"> <i class="icon-plus"></i> Add Email Template</button>
      <a class="btn btn-danger tooltips" rel="tooltip" data-placement="top" data-original-title="Back to News" href="<?php echo base_url('/email_templates/');?>"><i class="icon-remove"></i> Back</a>                              
      </div>
    </div>
  </form>
</div>                     
</div>

<script type="text/javascript" >
 $(document).ready(function(){
  setTimeout(function(){
  $(".flash").fadeOut("slow", function () {
  $(".flash").remove();
      }); }, 5000);
 });
</script>
