<div class="container two-col-holder no-padding">

<!-- <div class="col-md-12 col-lg-12  login-page-warp">
<h4>Page does not seem to exist!</h4>
<br>

<h5>Click here to return to our homepage, or you can contact us for assistance here.</h5>
<h5>
The resource you are looking for might have been removed, had its name changed, or is temporarily unavailable.</h5>
</div> -->

<!--error page warp start here-->
<style type="text/css">
	.row {
	    margin: 50px 140px;
	}
	.bordIcon{color: #FFAD72;}
	.error-img{
		height:100px;
		width:100px;
	}
img.logImg {
    width: 150px;
}
</style>
<div class="header row">
	 		<a href="<?php echo base_url();?>" style="cursor:pointer; text-decoration:none;" >
	 		<img src="<?php echo base_url('assets/admin/img/redlogo.png') ?>" class="logImg">
	 		</a>
</div>
<hr>
<div class="row">
<div class="error-warp">
 <div class="error-content-warp">
<br>

	<h3 class="text-center"><font>Oops! Looks Like Something Went Wrong</font></h3>

	<br>
	<div class="col-md-4 col-lg-4">
		
		<img src="<?php echo base_url(); ?>assets/uploads/404img.jpg" alt="" class="error-img">
	</div>  


	<div class="col-md-8 col-lg-8 error-pagemenu-warp">
	
	<div class="">
	<div class="error-content">

	<h3 class="">Here's some resons why it happens :</h3>
	 <div class="col-md-10 col-lg-10 ">
		 <div class="col-md-12 col-lg-12 pull -right">
			<ul>
			<li>The page is temporarily unavailable or no longer exist.</li>
				<li>May be its name has been changed.</li>
				<li>The page stopped working correctly.</li>
				<li>Your session may have timed out.</li>
				<li>The specific product or store may no longer be available and has been removed.</li>
			</ul>
		 </div>
		</div>
		<div class="clearfix"></div>
	</div>
	</div>
<br>
	<div class="">
 		<a href="<?php echo base_url();?>" class="go-back" ><img src="<?php echo base_url(); ?>assets/uploads/backImg.png" alt="" width="45"><!--  -->  &nbsp;&nbsp;<b>Go to Login &nbsp;<i class="fa fa-home"></i></b></a>
	</div>
	<br>
	</div>

	<div class="clearfix"></div>
  </div>
</div>
</div>
<hr>
<!--error page warp end here-->

</div>
