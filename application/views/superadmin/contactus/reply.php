<div class="bread_parent">
<div class="col-md-12">
  <ul class="breadcrumb">
      <li><a href="<?php echo base_url('superadmin/superadmin/dashboard');?>"><i class="icon-home"></i> Dashboard  </a></li>  
       <li><a href="<?php echo base_url('superadmin/contactus');?>">Contact Us</a> </li>  
       <li><b>Contact Us Detail</b></li>
  </ul>
</div>

<div class="clearfix"></div>
</div>

 <div class="row">
                  <div class="col-md-6">
                      <!--user info panel-body table start-->
                      <section>
                          <div class="">
                              <div class="task-thumb-details">                                
                                      <h1><a href="#">Contact Information</a></h1>  
                              </div>                              
                          </div>
                          <table class="table table-hover personal-task">
                              <tbody>
                                <tr>
                                    <td width="50%"><strong>Name:</strong></td>
                                     
                                    <td width="50%"><?php if(isset($contacts[0]->name)){ echo ucwords($contacts[0]->name);  }else{ echo '-';} ?></td>
                                </tr>
                              
                                <tr>                                    
                                    <td><strong>Email:</strong></td>
                                    <td><?php if(isset($contacts[0]->email)){ echo $contacts[0]->email;  }else{ echo '-';} ?></td>
                                </tr>
                                <tr>                                    
                                    <td><strong>Contact No.:</strong></td>
                                    <td><?php if(isset($contacts[0]->mobile)){ echo $contacts[0]->mobile;  }else{ echo '-';} ?></td>
                                </tr>

                                <tr>                                    
                                    <td><strong>Created Date & Time:</strong></td>
                                    <td> <?php if(isset($contacts[0]->created)){ echo date('d M Y,h:i  A',strtotime($contacts[0]->created ));  }else{ echo '-';} ?></td>
                                </tr>
                                 <tr>                                    
                                    <td style="vertical-align: top !important;" ><strong>Message:</strong></td>
                                    <td><?php if(isset($contacts[0]->message)){ echo $contacts[0]->message;  }else{ echo '-';} ?></td>
                                </tr>

                                 <tr>                                    
                                    <td style="vertical-align: top !important;"><strong>Reply Message:</strong></td>
                                    <td><?php if(isset($contacts[0]->reply)){ echo $contacts[0]->reply;  }else{ echo '-';} ?></td>
                                </tr>
                               

                              </tbody>
                          </table>
                      </section>
                      <!--user info table end-->
                  </div>
                  <div class="col-md-6">
                      <!--work progress start-->
                      <section>

                        <div class="">
                              <div class="task-thumb-details"> 
                                 <h1><a href="#">Reply</a></h1>                                  
                              </div>
                        </div>

                        <div class="tab-pane row-fluid fade in active">
                          <form role="form" class="form-horizontal tasi-form" action="<?php echo current_url()?>" enctype="multipart/form-data" method="post" id="form_valid">
                            <div class="form-body">

                              <div class="form-group">
                            <div class="row"><label class="col-md-3 text-left">Message <span class="mandatory">*</span></label>
                            <div class="col-md-9">
                            <textarea placeholder="Your Message" name="message"   rows="6" class="form-control" value=""></textarea>
                             <?php echo form_error('message'); ?>
                                </div></div>
                          </div>

                          

                            <div class="form-actions fluid">
                              <div class="row">
                                <button  class="btn btn-info tooltips pull-right" rel="tooltip" data-placement="top" data-original-title="Reply" type="submit"> <i class="fa fa-reply"></i> Reply</button>
                                </div>
                              </div>
                          </form>
                        </div> 
                          
                         
                      </section>
                      <!--work progress end-->
                  </div>
              </div>



