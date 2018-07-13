<div class="">
<ul class="breadcrumb">
    <li><a href="<?php echo base_url('superadmin/superadmin/dashboard');?>"> Dashboard  </a></li>
    <li> Setting</li>           
</ul>
</div>
<div class="portlet light bordered">
  <div class="portlet-title panel-heading">
    <div class="caption">
      <i class="fa fa-cog" aria-hidden="true"></i> Setting
    </div>
  </div>
</div>
<div class="row">
    <div class="col-lg-12">
      <section class="panel">          
  <form role="form" method="post" class="form-horizontal custom-admin-form" action="<?php echo current_url()?>" enctype="multipart/form-data">
    <div class="panel">   
      <div class="col-lg-6">
        <h4>General Setting</h4>
        <div class="form-group">
            <label class="control-label col-md-5 ">Email Account<small class="required">*</small></label>
              <div class="col-sm-7">
                <input type="text" placeholder="Email Account" class="form-control" name="admin_email" value="<?php if(set_value('admin_email')){echo set_value('admin_email');}else{ if(!empty($setting[0]->meta_data)){ echo $setting[0]->meta_data;}}   ?>">
               <?php echo form_error('admin_email'); ?>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-5 ">Newslettor Email<small class="required">*</small></label>
              <div class="col-sm-7">
                <input type="text" placeholder="Newslettor Email" name="newslettor_email" value="<?php if(set_value('newslettor_email')){echo set_value('newslettor_email');}else{ if(!empty($setting[8]->meta_data)){ echo $setting[8]->meta_data;}}   ?>" class="form-control">
               <?php echo form_error('newslettor_email'); ?>
            </div>
          </div>
        </div>
      <div class="col-lg-6">
        <h4>Socail Setting</h4>
        <div class="form-group">
            <label class="control-label col-md-5 ">Facebook Link<small class="required">*</small></label>
              <div class="col-sm-7">
                <input type="text" placeholder="Facebook Link" class="form-control" name="fb_link" value="<?php if(set_value('fb_link')){echo set_value('fb_link');}else{ if(!empty($setting[1]->meta_data)){ echo $setting[1]->meta_data;}}   ?>">
               <?php echo form_error('fb_link'); ?>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-5 ">Google Link<small class="required">*</small></label>
              <div class="col-sm-7">
                <input type="text" placeholder="Facebook Link" class="form-control" name="google_link" value="<?php if(set_value('google_link')){echo set_value('google_link');}else{ if(!empty($setting[2]->meta_data)){ echo $setting[2]->meta_data;}}   ?>">
               <?php echo form_error('google_link'); ?>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-5 ">Twittor Link<small class="required">*</small></label>
              <div class="col-sm-7">
                <input type="text" placeholder="Twittor Link" class="form-control" name="twittor_link" value="<?php if(set_value('twittor_link')){echo set_value('twittor_link');}else{ if(!empty($setting[3]->meta_data)){ echo $setting[3]->meta_data;}}   ?>">
               <?php echo form_error('twittor_link'); ?>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-5 ">Instagram Link<small class="required">*</small></label>
              <div class="col-sm-7">
                <input type="text" placeholder="Instagram Link" class="form-control" name="instagram_link" value="<?php if(set_value('instagram_link')){echo set_value('instagram_link');}else{ if(!empty($setting[4]->meta_data)){ echo $setting[4]->meta_data;}}   ?>">
               <?php echo form_error('instagram_link'); ?>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-5">Pinterest Link<small class="required">*</small></label>
              <div class="col-sm-7">
                <input type="text" placeholder="Pinterest Link" class="form-control" name="pinterest_link" value="<?php if(set_value('pinterest_link')){echo set_value('pinterest_link');}else{ if(!empty($setting[15]->meta_data)){ echo $setting[15]->meta_data;}}   ?>">
               <?php echo form_error('pinterest_link'); ?>
            </div>
          </div>
      </div>
      <div class="col-lg-6">
        <h4>Contact Setting</h4>
        <div class="form-group">
            <label class="control-label col-md-5 ">Contact Name<small class="required">*</small></label>
              <div class="col-sm-7">
                <input type="text" placeholder="Contact Name" class="form-control" name="contact_name" value="<?php if(set_value('contact_name')){echo set_value('contact_name');}else{ if(!empty($setting[5]->meta_data)){ echo $setting[5]->meta_data;}}   ?>">
               <?php echo form_error('contact_name'); ?>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-5 ">Contact Email<small class="required">*</small></label>
              <div class="col-sm-6">
                <input type="text" placeholder="Contact Email" class="form-control" name="contact_email" value="<?php if(set_value('contact_email')){echo set_value('contact_email');}else{ if(!empty($setting[6]->meta_data)){ echo $setting[6]->meta_data;}}   ?>">
               <?php echo form_error('contact_email'); ?>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-5">Contact Address<small class="required">*</small></label>
              <div class="col-sm-7">
                <input type="text" placeholder="Contact Address" class="form-control" name="contact_address" value="<?php if(set_value('contact_address')){echo set_value('contact_address');}else{ if(!empty($setting[7]->meta_data)){ echo $setting[7]->meta_data;}}   ?>">
               <?php echo form_error('contact_address'); ?>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-5 ">Contact Number<small class="required">*</small></label>
              <div class="col-sm-7">
                <input type="text" placeholder="Contact Number" class="form-control" name="contact_number" value="<?php if(set_value('contact_number')){echo set_value('instagram_link');}else{ if(!empty($setting[16]->meta_data)){ echo $setting[16]->meta_data;}}   ?>">
               <?php echo form_error('contact_number'); ?>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-5">Outside Contact Number<small class="required">*</small></label>
              <div class="col-sm-7">
                <input type="text" placeholder="Outside Contact Number" class="form-control" name="outside_contact_number" value="<?php if(set_value('outside_contact_number')){echo set_value('outside_contact_number');}else{ if(!empty($setting[17]->meta_data)){ echo $setting[17]->meta_data;}}   ?>">
               <?php echo form_error('outside_contact_number'); ?>
            </div>
          </div>
           <div class="form-group">
            <label class="control-label col-md-5 ">Outside Contact Country <small class="required">*</small></label>
              <div class="col-sm-7">
                <input type="text" placeholder="outside_contact_number_country" class="form-control" name="outside_contact_number_country" value="<?php if(set_value('outside_contact_number_country')){echo set_value('outside_contact_number_country');}else{ if(!empty($setting[18]->meta_data)){ echo $setting[18]->meta_data;}}   ?>">
               <?php echo form_error('outside_contact_number_country'); ?>
            </div>
          </div>
      </div>
      <div class="row">          
      <div class="col-sm-9 col-md-offset-3">
        <input type="hidden" name="submit" value="setting">
       <button class="btn btn-primary submit-btn" type="submit">Update Setting</button>
      </div> 
    </div> 
    </div>

  </form>
</div>
</section>

  </div>
  </div>
