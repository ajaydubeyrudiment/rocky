<div class="bread_parent">
  
  <ul class="breadcrumb">
      <li><a href="<?php echo base_url('/superadmin/dashboard');?>"><i class="icon-home"></i> Dashboard  </a></li>  
       <li><b>Email Templates</b></li>
        <div class="btn-group pull-right" style="margin-top:-7px;">
      <a class="btn btn-primary btn-sm tooltips" href="<?php echo base_url('/email_templates/email_templates_add');?>" id="add" data-original-title="Click to add the Email"><i class="icon-plus"> Add Email Templates &nbsp;</i>
      </a>
    </div>
  </ul>

  
  </div>

   
  <div class="clearfix"></div> 
    <section class="panel">
     <div class="col-lg-14"> 
        <form action="<?php current_url() ?>" method="get" accept-charset="utf-8">
          <label class="col-md-2 no-padding-left">
            <input type="text" name="title" value="<?php echo $this->input->get('title'); ?>" class="form-control" placeholder="Search By Tiltle"></label>
         
            <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
          <a href="<?php echo current_url() ?>" class="btn btn-danger"><i class="fa fa-refresh" aria-hidden="true"></i></a>
          
        </form>
      </div>
      <header class="panel-heading"  ></header>
      <table id="datatable_example" class="table table-striped table-hover" >
        <thead class="thead_color">
          <tr>
            <th width="2%">#</th>
            <th width="3%">ID</th>
            <th width="30%">Title</th>
            <th width="30%">Subject</th>
            
            <th width="18%"><i class="fa fa-calendar"></i> Created</th>
            <th width="20%">Actions</th>
          </tr>
        </thead>
          <?php  
          if(!empty($news)):
            $j=$offset+1; 
          foreach($news as $row): 
          ?>
            <tbody>
            <tr>
                <td><?php echo $j ?></td>
                <td><?php echo $row->id ?></td>
                <td><?php echo $row->template_name ?></td>
                <td><?php echo $row->template_subject ?></td>
               
                <td class="to_hide_phone"> <?php echo date('d M Y,h:i  A',strtotime($row->template_created)); ?></td>
                <td class="ms">
                    <a href="<?php echo base_url().'/email_templates/email_templates_edit/'.$row->id ?>" class="btn btn-primary btn-xs tooltips" rel="tooltip"  data-placement="left" data-original-title="Edit" ><i class="fa fa-pencil-square-o"></i></a>
                    <a href="<?php echo base_url().'/email_templates/email_templates_delete/'.$row->id ?>" class="btn btn-danger btn-xs tooltips" rel="tooltip"  data-placement="top" data-original-title="Delete This Email Template" onclick="if(confirm('Are you sure want to delete?')){return true;} else {return false;}" ><i class="fa fa-trash-o"></i></a> 
                </td>
              </tr> 
            </tbody> 
          <?php $j++;  endforeach; ?>
        <?php else: ?>
          <tr>
           <th colspan="6" class="msg"> <center>No Template Found.</center></th>
          </tr>
        <?php endif; ?> 
    </table>
    </section>
  
  </div>
<?php echo $pagination;?>
<script type="text/javascript" >
 $(document).ready(function(){
  setTimeout(function(){
  $(".flash").fadeOut("slow", function () {
  $(".flash").remove();
      }); }, 5000);
 });
</script>