<div class="bread_parent">

  <div class="col-md-12">

    <ul class="breadcrumb">

        <li>

          <a href="<?php echo base_url('superadmin/superadmin/dashboard');?>"> Dashboard  </a>

        </li>

        <li class=""> Newslettors</li> 

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

     <form action="<?php echo base_url() ?>superadmin/newslettors/index" method="get" accept-charset="utf-8">

          <!-- <div class="box_wrap">

            <label>Newslettor ID</label>

            <input type="text" placeholder="Search by Newslettor ID" 

                class="form-control" name="name" value="<?php echo $this->input->get('name');?>"/>

          </div> -->

         <div class="box_wrap">

            <label>Email</label>

            <input type="text" name="email" value="<?php   echo $this->input->get('email');  ?>" class="form-control" placeholder="Search by Email">

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

        <a href="<?php echo base_url('superadmin/newslettors') ?>" class="btn btn-danger search-btn-new">

          <i class="fa fa-refresh"></i>

        </a>

    </form>

  </div>

  <div class="clearfix"></div>  

      <header class="panel-heading"  ></header>

      <table id="example1" class="table table-striped table-hover eco-admin-list-table" >

        <thead class="thead_color">

          <tr>

            <th width="20%" class="text-center">ID</th>

            <th width="35%">Email</th>          

            <th width="45%" class="text-center">Created Date & Time</th>             

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

                  <?php echo $row->email ?>                  

                </td>               

                <td class="to_hide_phone text-center">

                  <?php if(!empty($row->created_date)) echo date('d M Y,h:i  A',strtotime($row->created_date)); ?>

                </td>               

              </tr> 

            </tbody> 

          <?php $j++;

              }

            } else{?>

          <tr>

           <td colspan="3" class="msg"> <span class="data-not-present"><?php echo 'No newslettor records found'; ?></span></td>

          </tr>

        <?php } ?> 

    </table>

<br />

<div class="pagination-side">

  <?php echo $pagination;?>

</div>

</section>