function adminLogin() {

  var username  = $('#username').val();

  var password  = $('#password').val();

  var error_res = '';

  if(username==''){

    $('#username_error').show().html('The email field is required.'); 

    error_res = true;       

  }else{

    if(isEmail(username)==false){

      $('#username_error').show().html('The email is invalid.'); 

      error_res = true;   

    }else{

      $('#username_error').hide();

    }  

  }

  if(password==''){

    $('#password_error').show().html('The password  field is required.'); 

    error_res = true;                        

  }else{

    $('#password_error').hide();

  }

  if(error_res==''){

    $.ajax({ 

      url:superadmin_url+"login/login_new",

      type:"POST",

      data:$("#login_form").serialize(), 

      success: function(html){

        console.log(html); 

        var response = $.parseJSON(html);

        if(response.status=='true'){ 

          window.location.href = response.redirect_url;

        }else{                 

          $('#login_error_res').show().html('<div class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Error : </strong> '+response.message+'</div>');                     

        }         

      }

    });

  } 

}

function forgotEmail() {

  var forgotEmail = $('#forgotEmail').val();

  $('.message_box').hide(); 

  if(forgotEmail==''){

    $('#forgot_email_error').show().html('The email field is required.');

    return false;

  }else{    

    if(isEmail(forgotEmail)==false){

      $('#forgot_email_error').show().html('The email is invalid.'); 

      return false;

    }else{

      $('#forgot_email_error').hide();

    } 

  }   

  $.ajax({

    url:superadmin_url+"login/sendForgotPasswordMail",

    type:"POST",

    data:{'forgotEmail':forgotEmail}, 

    success: function(html){      

      var response = $.parseJSON(html);                 

      if(response.status=='true'){   

        $('.message_box').show();

        $('.message_box').html('<div class="alert alert-success" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>success : </strong>'+response.message+'</div>');           

        $('#forgotEmail').val('');

        $('.login_preloader_wrp').hide(); 

      }else{ 

        $('.message_box').show();

        $('.message_box').html('<div class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Error : </strong> '+response.message+'</div>');

        $('.login_preloader_wrp').hide();  

      }        

    }

  });

}

function isEmail(email) {

  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

  return regex.test(email);

}

function forgot_manu(){

  $('#forgot_password_main').show();

  $('#login_main').hide();

}

function viewPostDetails(postID='',postName='') {  

  $('#modelTitle').html(postName+' Details');

  $('.modal-dialog').css({'width':'1000px'});

  $.ajax({

    url:base_url+"superadmin/post/postDetail",

    type:"get",    

    data:{'post_id':postID},

    success: function(html){

      $('#modelData').html(html);  

    }

  });

} 
function veiwpostActivilty(postID='',postName='',types='',type='') { 
  $('#modelTitle').html(postName+' Details');
  if(types=='booking'){
    $('.modal-dialog').css({'width':'1400px'});
  }else{
    $('.modal-dialog').css({'width':'1000px'});
  }
  $.ajax({
    url:base_url+"superadmin/post/postActivilty",
    type:"get",    
    data:{'post_id':postID,'types':types,'type':type},
    success: function(html){
      $('#modelData').html(html);  
    }
  });
}