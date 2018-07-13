$(document).ready(function() { 
  $(document).on("keypress", '#comments', function (event) { 
    var key = event.which; if(key === 13){ 
      submitComment();
    } 
  });

    $(".only_number").keydown(function (e) {
      if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
          // Allow: Ctrl+A, Command+A
          (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
           // Allow: home, end, left, right, down, up
          (e.keyCode >= 35 && e.keyCode <= 40)) {
               // let it happen, don't do anything
               return;
      }
      // Ensure that it is a number and stop the keypress
      if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
          e.preventDefault();
      }
    });
    $('input').keypress(function (e) {    
      if (e.which == 13) {     
        var funcationName = $('#enter_key_funcation').val();
        if(funcationName=='userSignup'){
          userSignup();
        } 
        if(funcationName=='userLogin'){
          userLogin();
        } 
        if(funcationName=='checkForgotMail'){
          forgotEmail();
        }            
      }
    });
    $('#search_bar').keypress(function (e) {
        if (e.which == 13) { 
          var title = $('#search_bar').val();  
          window.location.href=base_url+"home/search_result?title="+title
        }
        var title = $('#search_bar').val();
        $('#result_loader_main').show();
        $.ajax({
          url:base_url+"home/search",
          type:"GET",
          data:{'title':title},
          success: function(html){      
            $('#result_loader_main').hide();
            $('#result_list').html(html);
          }
        });
    });
    $(".selectPlanMain  .select-options li").click(function(){  
      var plan  = $('.selectPlanVals').val();    
      if(plan=='1'){
          $('#plan_message').html('Access to all cardio based workouts only.');
       }
       if(plan=='2'){
          $('#plan_message').html('Access to all workouts.');
       }
       if(plan=='3'){
          $('#plan_message').html('Access to all muscle gain workouts and content');
       }      
    });
    /*check user name when signup*/
    $("#signup_username").change(function(){     
      var user_name = $('#signup_username').val();
      $.ajax({
        url:base_url+"home/checkUserName",
        type:"POST",
        data:{user_name:user_name},
        success: function(html){ 
          var response = $.parseJSON(html);       
          if(response.status=='true'){ 
            $('#signup_username_error').hide();
            $('#signup_username_sucess_icon').show().removeClass('fa-times unavailable').addClass('fa-check available');
          }else{ 
            $('#signup_username_error').show().html(response.message); 
            $('#signup_username_sucess_icon').show().addClass('fa-times unavailable').removeClass('fa-check available');
          }      
        }
      });
    });
   /*check email when signup*/
   $("#signup_user_email").change(function(){      
      var signup_user_email = $('#signup_user_email').val();
      if(isEmail(signup_user_email)==false){        
         $('#signup_user_email_error').show().html('The email is invalid.'); 
         $('#signup_email_sucess_icon').show().addClass('fa-times unavailable').removeClass('fa-check available');
         error    = 'yes';    
      }else{
         $('#signup_user_email_error').hide();
      } 
      $.ajax({
         url:base_url+"home/checkUserEmail",
         type:"POST",
         data:{email:signup_user_email},
         success: function(html){ 
            var response = $.parseJSON(html);       
            if(response.status=='true'){ 
               $('#signup_user_email_error').hide();
               $('#signup_email_sucess_icon').show().removeClass('fa-times unavailable').addClass('fa-check available');
            }else{ 
               $('#signup_user_email_error').show().html(response.message); 
               $('#signup_email_sucess_icon').show().addClass('fa-times unavailable').removeClass('fa-check available');
            }      
         }
      });
   });
  /*check email when signup*/
   $("#signup_cpassword").change(function(){      
      var signup_password  = $('#signup_password').val();
      var signup_cpassword = $('#signup_cpassword').val();
      if(signup_password==''){
        $('#signup_password_error').show().html('The password field is required. ');
      }else{
        $('#signup_password_error').hide();
      }
      if(signup_cpassword==''){
        $('#signup_cpassword_error').show().html('The confirm password field is required. ');
      }else{
        $('#signup_cpassword_error').hide();
      }
      if(signup_password!=signup_cpassword){        
        $('#signup_cpassword_error').show().html('The password and confirm password does not matched'); 
        $('#signup_password_sucess_icon').show().addClass('fa-times unavailable').removeClass('fa-check available');
        error    = 'yes';    
      }else{
        $('#signup_cpassword_error').hide();
        $('#signup_password_sucess_icon').show().removeClass('fa-times unavailable').addClass('fa-check available');
      }
   });
   $("#imgUploadReceipe").change(function(){ 
	    $('.loader_profile_left').show();   
	    var imageCount             = $('#imageCount').val();
	    //alert('imageCount='+imageCount);
	    var files                  = document.getElementById('imgUploadReceipe').files;
	    var recipe_blog_image_type = $('#recipe_blog_image_type').val();   
	    var totalFileCount = parseInt(imageCount) + parseInt(files.length);
	    //alert('totalFileCount='+totalFileCount);
	    if(recipe_blog_image_type=='recipe'||recipe_blog_image_type=='image'){  
	      console.log('file count ='+totalFileCount);    
	      if(totalFileCount>5){
	        $('#file_type_error').show().html('You can`t upload more than 5 images');
	        $('#file_type_error').delay(5000).hide(1);
	        $('.loader_profile_left').hide();  
	      }else{
	        for (var i = 0; i< files.length; i++) {
	          var file = files[i];
	          uploadRecepeImageFile(file, i,file.type);
	        }
	        $('#imgUploadReceipe').val('');
	      }      
	    }else{
	      for (var i = 0; i< files.length; i++) {
	        var file = files[i];
	        uploadRecepeImageFile(file, i,file.type);
	      }
	      $('#imgUploadReceipe').val('');
	    }
	});
});
function uploadRecepeImageFile(file, i,type){  
    var recipe_blog_image_type = $('#recipe_blog_image_type').val();  
    var xhr = new XMLHttpRequest(); 
    var formData = new FormData();     
    formData.append('user_img',file);  
    formData.append('recipe_blog_image_type',recipe_blog_image_type);  
    xhr.open("POST", base_url+"user/uploadRecipeImg");
      xhr.upload.onprogress = function(e) {
      if (e.lengthComputable) {     
        var percentComplete = (e.loaded / e.total) * 100;   
        $('.loader_profile_left').show();      
      }
    };
    xhr.onload = function() {
    if (this.status == 200) {
      	var resp = this.response;
      	res = JSON.parse(resp);
      	$('.loader_profile_left').hide();
      	if(res.statuss=='true'){
          var recipe_blog_image_type = $('#recipe_blog_image_type').val();
          //console.log('file upload s'+recipe_blog_image_type);
          var imageCount = $('#imageCount').val();
          var imageCount = parseInt(imageCount) + parseInt(1);
          $('#imageCount').val(imageCount);
          //$('#file_type_error').hide();    
          if(recipe_blog_image_type=='blog'){ 
            $('#RecipePics').html(res.imagehtm); 
            var recepeImgsName =  $('#recepeImgsName').val(res.file_name);
          } else if(recipe_blog_image_type=='recipe'||recipe_blog_image_type=='image'){ 
            $('#RecipePics').append(res.imagehtm); 
            var recepeImgsName =  $('#recepeImgsName').val();  
            $('#recepeImgsName').val(recepeImgsName+','+res.file_name);   
          }else if(recipe_blog_image_type=='profile_post'){            
            $('#profile_post_image').attr('src', res.full_path);
            $('#recepeImgsName').val(res.file_name);
          }  
        }else{       
          $('#file_type_error').show().html(res.message);
          $('#file_type_error').delay(5000).hide(1);
       }
    };
  };      
  xhr.send(formData);    
} 
function userLogin(){  
  var username  = $('#username').val();
  var password  = $('#password').val();  
  var error     = 'no';
  if(username==''){
    $('#username_error').show();
    $('#username_error').html('The email field is required.');  
    error    = 'yes';   
  }else{
    $('#username_error').hide(); 
  }
  if(password==''){
    $('#password_error').show();
    $('#password_error').html('The password field is required.');  
    error    = 'yes';   
  }else{
     $('#password_error').hide();
  }
  if(error=='no'){
     $.ajax({ 
        url:base_url+"home/login",
        type:"POST",
        data:{'email':username,'password':password}, 
          success: function(html){   
           var response = $.parseJSON(html); 
           if(response.status=='true'){
              $('#login_error_res').show().html('<div class="alert alert-success" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success : </strong> '+response.message+'</div>');  
              window.location.href=base_url+response.redirect_url;
           }else{               
             $('#login_error_res').show().html('<div class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Error : </strong> '+response.message+'</div>');  
            } 
         }
    });
  }else{
    return false;
  }  
}
function userSignup(){  
  var signup_name       = $('#signup_name').val();
  var signup_username   = $('#signup_username').val();    
  var signup_user_email = $('#signup_user_email').val();
  var signup_password   = $('#signup_password').val(); 
  var error             = 'no';
  if(signup_name==''){
    	$('#signup_name_error').show();
    	$('#signup_name_error').html('A valid full name is required');
    	error    = 'yes';  
  }else{
    	$('#signup_name_error').hide();
  }
  if(signup_username==''){
    	$('#signup_username_error').show();
    	$('#signup_username_error').html('A valid username is required.');  
    	error    = 'yes';   
  }else{
    	$('#signup_username_error').hide();
  }
  if(signup_user_email==''){
    	$('#signup_user_email_error').show();
    	$('#signup_user_email_error').html('A valid email is required.');  
    	error    = 'yes';   
  }else{
    if(isEmail(signup_user_email)==false){
        $('#signup_user_email_error').show();
        $('#signup_user_email_error').html('The email is invalid.'); 
        error    = 'yes'; 
      }else{
        $('#signup_user_email_error').hide();
      } 
  }
  if(signup_password==''){
    $('#signup_password_error').show();
    $('#signup_password_error').html('A valid password is required.');  
    error    = 'yes';  
  }else{
    	$('#signup_password_error').hide();          
  } 
  if(error=='no'){
      $('.loader_profile_left').show();
     	$.ajax({ 
        	url:base_url+"home/signup_res",
        	type:"POST",
        	data:$( "#signup_form" ).serialize(), 
          success: function(html){ 
            var response = $.parseJSON(html); 
            $('.loader_profile_left').hide();
            if(response.status=='true'){ 
                $('#signup_success_res').show();
                $('#signup_success_res').html('<div style="margin-top:0px" class="alert alert-success" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button> '+response.message+'</div>'); 
                $("#signup_form")[0].reset();
                $('#signup_username_sucess_icon').hide();
                $('#signup_email_sucess_icon').hide();
                $('#signup_password_sucess_icon').hide();
                window.location.href=base_url+response.redirect_url;
            }else{
               $('#signup_success_res').show();
               $('#signup_success_res').html('<div class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');
            } 
        }
    	});
  }else{
    return false;
  }  
}
function forgotEmail() {
    var forgotEmail = $('#forgot_email').val();
    $('.message_box').hide(); 
    if(forgotEmail==''){
      $('#forgot_email_error').show();
      $('#forgot_email_error').html('The email field is required.');
      return false;
    }else{    
      if(isEmail(forgotEmail)==false){
           $('#forgot_email_error').show();
           $('#forgot_email_error').html('The email is invalid.'); 
           return false;
        }else{
          $('#forgot_email_error').hide();
        } 
    } 
    $('.loader_profile_left').show();
    $.ajax({
      url:base_url+"home/sendForgotPasswordMail",
      type:"POST",
      data:{'forgotEmail':forgotEmail}, 
        success: function(html){   
         var response = $.parseJSON(html); 
          $('.loader_profile_left').hide();
         if(response.status=='true'){   
            $('#forgot_mail_success_res').show();
            $('#forgot_mail_success_res').html('<div style="margin-top:0px" class="alert alert-success" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>success : </strong>'+response.message+'</div>');  
            $('#forgot_email').val('');  
          }else{ 
            $('#forgot_mail_success_res').show();
            $('#forgot_mail_success_res').html('<div class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Error : </strong> '+response.message+'</div>');
            $('.login_preloader_wrp').hide();  

          } 
       }
    });
}
function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}
function seeNotification(){
  $.ajax({
    url:base_url+"user/seeNotification",
    type:"GET",     
    success: function(html){  
      var response = $.parseJSON(html);
      if(response.status=='true'){ 
        $('#notification_counter').hide();
      }   
    }
  });
}
function see_notification_one(id){
  $.ajax({
    url:base_url+"user/seeNotification/"+id,
    type:"GET",     
    success: function(html){  
      var response = $.parseJSON(html);  
      if(response.status=='true'){ 
        window.location.href=response.redirects;
      }
     }
  });
}
function  newsletterSubscribe(){
    var news_email  = $('#news_email').val(); 
    var news_name   = $('#news_name').val(); 
    var error       = 'no'; 
    if(news_name==''){
      $('#news_name_error').show();
      $('#news_name_error').html('The name field is required.');  
      error    = 'yes';   
    }else{
      $('#news_name_error').hide();      
    }
    if(news_email==''){
      $('#news_email_error').show();
      $('#news_email_error').html('The email field is required.'); 
      error    = 'yes';
    }else{
      if(isEmail(news_email)==false){
        $('#news_email_error').show();
        $('#news_email_error').html('The email is invalid.'); 
        error    = 'yes'; 
      }else{
        $('#news_email_error').hide();
      } 
    } 
    if(error=='no'){
      $.ajax({
        url:base_url+"home/newsletter",
        type:"POST",
        data:{'email':news_email,'name':news_name}, 
          success: function(html){      
           var response = $.parseJSON(html);   
           if(response.status=='true'){   
              $('#news_email_res').show();
              $('#news_email_res').html('<div style="margin-top:0px" class="alert alert-success" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');
              $('#news_email').val('');      
            }else{ 
              $('#news_email_res').show();
              $('#news_email_res').html('<div class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>'); 
            }        
         }
    });
  }
}
function  saveSetting(){
    var contactEmail  = $('#contactEmail').val();  
    var contactMobile = $('#contactMobile').val();  
    var paypalEmail   = $('#paypalEmail').val();  
    var error        = 'no';
    if(paypalEmail!=''){     
      if(isEmail(paypalEmail)==false){          
        $('#paypalEmail_error').show().html('The paypal email is invalid.'); 
        error    = 'yes'; 
      }else{
        $('#paypalEmail_error').hide();
      } 
    }else{
      $('#paypalEmail_error').hide();
    }     
    if(contactEmail!=''){     
      if(isEmail(contactEmail)==false){          
        $('#contactEmail_error').show().html('The contact email is invalid.'); 
        error    = 'yes'; 
      }else{
        $('#contactEmail_error').hide();
      } 
    } else{
      $('#paypalEmail_error').hide();
    }    
    if(error=='no'){
      $.ajax({
        url:base_url+"user/saveSetting",
        type:"POST",
        data:$( "#userSetting" ).serialize(),
        success: function(html){      
          var response = $.parseJSON(html);  
          $(window).scrollTop(120);
          if(response.status=='true'){ 
            $('#setting_res').show().html('<div style="margin-top:0px" class="alert alert-success" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');
            setTimeout(function(){ location.reload(); }, 1000);
          }else{ 
                $('#setting_res').show().html('<div class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>'); 
          }        
        }
      });
    }
}
function  updatePassword(){
  var old_password       = $('#old_password').val();  
  var new_password       = $('#new_password').val();  
  var confirm_password   = $('#confirm_password').val();  
  var error        = 'no';
  if(old_password==''){     
    $('#old_password_error').show().html('The old password is required.'); 
    error    = 'yes'; 
  }else{
    $('#old_password_error').hide();
  }  
  if(new_password==''){     
    $('#new_password_error').show().html('The new password is required.'); 
    error    = 'yes'; 
  }else{
    $('#new_password_error').hide();
  }
  if(confirm_password==''){     
    $('#confirm_password_error').show().html('The new password is required.'); 
    error    = 'yes'; 
  }else{
    $('#confirm_password_error').hide();
  }
  if(error=='no'){
      $.ajax({
        url:base_url+"user/change_password",
        type:"POST",
        data:$("#userSetting" ).serialize(),
        success: function(html){      
          var response = $.parseJSON(html);   
          $(window).scrollTop(120);
          if(response.status=='true'){ 
                $('#setting_res').show().html('<div style="margin-top:0px" class="alert alert-success" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');
          }else{ 
                $('#setting_res').show().html('<div class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>'); 
          }        
        }
      });
    }
}
function  saveProfile(type){
    //alert('s');
    var dateofbirth  = $('#dateofbirth').val();  
    var gender       = $('.gender').val();  
    var error        = 'no';
    if(type=='first_time'){
      var first_name  = $('#first_name').val();  
      if(first_name==''){     
      $('#first_name_error').show().html('The first name is required.'); 
        error    = 'yes';       
      }else{
        $('#first_name_error').hide();
      } 
    }
    if(dateofbirth==''){     
      $('#dateofbirth_error').show().html('The date of birth is required.'); 
      error    = 'yes';       
    }else{
      $('#dateofbirth_error').hide();
    } 
    if(gender==''){     
      $('#gender_error').show().html('The gender is required.'); 
      error    = 'yes';       
    }else{
      $('#gender_error').hide();
    } 
    var useMetricsSystem = $('.useMetricsSystem').is(':checked');
    if(useMetricsSystem){
      var height       = $('.height_cms').val();  
      var weight      = $('.weight').val();     
      if(height==''){     
        $('#height_cms_error').show().html('The height is required.'); 
        error    = 'yes';       
      }else{
        $('#height_cms_error').hide();
      }
      if(weight==''){     
        $('#weight_error').show().html('The weight is required.'); 
        error    = 'yes';       
      }else{
        $('#weight_error').hide();
      }      
    }else{
      var height       = $('.height').val();  
      var weight       = $('.weight').val();       
      if(height==''){     
        $('#height_error').show().html('The height is required.'); 
        error    = 'yes';       
      }else{
        $('#height_error').hide();
      } 
      if(weight==''){     
        $('#weight_error').show().html('The weight is required.'); 
        error    = 'yes';       
      }else{
        $('#weight_error').hide();
      }        
    }  
   //alert('error='+error);  
    if(error=='no'){
      $.ajax({
        url:base_url+"user/edit_profile_res",
        type:"POST",
        data:$( "#editProfile" ).serialize(),
        success: function(html){      
          var response = $.parseJSON(html);  
          $(window).scrollTop(120);
          if(response.status=='true'){ 
            $('#editProfile_res').show().html('<div style="margin-top:0px" class="alert alert-success" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');
            if(response.first_name!=''){
              $('#user_name_title').show().html('Welcome to '+response.first_name+' !</div>');
              $('#left_bar_user_name').show().html(response.first_name);
              $('#edit_profile_model').modal('hide');
              window.location.href="";            
            }
            if(response.about!=''){
               $('#user_name_about').show().html(response.about);            
               $('#left_bar_about').show().html(response.about);     
            } 
            window.location.href=base_url+"user/dashboard";                  
          }else{ 
            $('#editProfile_res').show().html('<div class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>'); 
          }        
        }
      });
    }
} 
function addRecipe(){    
  $('.loader_profile_left').show();
  var recipe_blog_image_type = $('#recipe_blog_image_type').val();
  var pageType               = $('#pageType').val();
  var error                  = 'no';
  if(recipe_blog_image_type=='recipe'){
    var recipie_description  = $('#recipie_description').val();
    var recipie_title        = $('#recipie_title').val();
    var recipie_ingredients  = $('#recipie_ingredients').val();
    var recipie_instructions = $('#recipie_instructions').val();
    var imageCount           = $('#imageCount').val();
    if(recipie_title==''){
      $('#recipie_title_error').show().html('A valid  title is required');
      error = 'yes';
    }else{
      $('#recipie_title_error').hide();
    }
    if(recipie_description==''){
      $('#recipie_description_error').show().html('A valid  description is required');
      error = 'yes';
    }else{
      $('#recipie_description_error').hide();
    }  
    if(recipie_ingredients==''){
      $('#recipie_ingredients_error').show().html('A valid  ingredients is required');
      error = 'yes';
    }else{
      $('#recipie_ingredients_error').hide();
    }  
    if(recipie_instructions==''){
      $('#recipie_instructions_error').show().html('A valid  instructions is required');
      error = 'yes';
    }else{
      $('#recipie_instructions_error').hide();
    }    
    if(imageCount==0){      
      /*if(pageType=='addBlog'){
        $('#file_type_error').show().html('The cover pic is required');
      }*/  
      if(pageType=='addRecipe'){
        $('#file_type_error').show().html('The recipie pic is required');
        error = 'yes';
      } 
    }else{
      $('#file_type_error').hide();
    }
    var ajaxUrl = base_url+"user/recipe_blog_image_res";
  }else if(recipe_blog_image_type=='blog'){
    var recipie_description  = $('#recipie_description').val();
    var recipie_title        = $('#recipie_title').val();
    var tagsUser             = $('#tagsUser').val();
    var content              = $('#recipie_content').val();
    var imageCount           = $('#imageCount').val();
    if(recipie_title==''){
      $('#recipie_title_error').show().html('A valid  title is required');
      error = 'yes';
    }else{
      $('#recipie_title_error').hide();
    }
    if(recipie_description==''){
      $('#recipie_description_error').show().html('A valid  description is required');
      error = 'yes';
    }else{
      $('#recipie_description_error').hide();
    } 
    if(content==''){
      $('#content_error').show().html('A valid  content is required');
      error = 'yes';
    }else{
      $('#content_error').hide();
    } 
    if(tagsUser==''){
      $('#tags_error').show().html('A valid  tags is required');
      error = 'yes';
    }else{
      $('#tags_error').hide();
    }  
    if(imageCount==0){      
      /*if(pageType=='addBlog'){
        $('#file_type_error').show().html('The cover pic is required');
      }*/  
      if(pageType=='addRecipe'){
        $('#file_type_error').show().html('The recipie pic is required');
        error = 'yes';
      } 
    }else{
      $('#file_type_error').hide();
    }
    var ajaxUrl = base_url+"user/recipe_blog_image_res";
  } else if(recipe_blog_image_type=='profile_post'){  
    var location = $('#location').val(); 
    var caption  = $('#caption').val(); 
    if(location==''){
      $('#location_error').show().html('A valid  location is required');
      error = 'yes';
    }else{
      $('#location_error').hide();
    }
    if(caption==''){
      $('#caption_error').show().html('A valid  caption is required');
      error = 'yes';
    }else{
      $('#caption_error').hide();
    }
    var ajaxUrl = base_url+"user/recipe_blog_image_res";
  }else if(recipe_blog_image_type=='image'){
    var imageCount = $('#imageCount').val();
    if(imageCount==0){
      $('#file_type_error').show().html('The image is required');
      error = 'yes';
    }else{
      $('#file_type_error').hide();
    }
    var ajaxUrl = base_url+"user/images_upload_res";
  }
  if(error=='no'){
    $.ajax({
        url:ajaxUrl,
        type:"POST",
        data:$( "#recipie_form" ).serialize(),
        success: function(html){      
          var response = $.parseJSON(html);  
          $(window).scrollTop(120);
          $('.loader_profile_left').hide();
          if(response.status=='true'){ 
            $('#recipie_response').show().html('<div style="margin-top:0px" class="alert alert-success" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');                
            if(response.type=='added'){
              $('#RecipePics').hide();
              $( "#recipie_form" )[0].reset(); 
              $('.loader_profile_left').hide();
            }  
            console.log('recipe_blog_image_type='+recipe_blog_image_type);
            if(recipe_blog_image_type=='image'){
              window.location.href = response.redirectUrl;
            }else if(recipe_blog_image_type=='profile_post'){
              window.location.href = base_url+'user/profile?acntype=image';
            }else{
              window.location.href = base_url+'user/profile?acntype='+recipe_blog_image_type;
            }                
          }else{ 
            $('#recipie_response').show().html('<div class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>'); 
            $('.loader_profile_left').hide();
          }        
        }
      });
  }else{
    $('.loader_profile_left').hide();
  }
}
function deleteParnamentImg(imageid='',image_name='',recepeID=''){
  alertify.confirm("Are you sure you want to  delete  this image", function (e) {
    if (e) {
      $('#'+imageid).hide();
      $.ajax({
        url:base_url+"user/deleteRecepeImage",
        type:"POST",
        data:{'imageid':imageid, 'image_name':image_name, 'recepeID':recepeID},
        success: function(html){}
      });
    }
  });
}
function deleteParnamentImageImg(imageid=''){
  $('#'+imageid).hide();
  var recepeImgsName =  $('#postImagesIds').val(); 
  var imageCount     =  $('#imageCount').val(); 
  $('#postImagesIds').val(newImages);
  var newImgCount = parseInt(imageCount)-parseInt(1);
  $('#imageCount').val(newImgCount);
  var images         = recepeImgsName.split(","); 
  var newImages      = '';
  for (var i =0; i <images.length; i++) {
    if(images[i]!=''&&images[i]!=imageid){
      newImages += ','+images[i];      
    }
  }   
  $.ajax({
    url:base_url+"user/deleteParnamentImageImg",
    type:"POST",
    data:{'imageid':imageid},
    success: function(html){}
  });
}
function deleteReciepeImgLocal(imageid='',imageName=''){
  $('#'+imageid).hide();
  var recepeImgsName =  $('#recepeImgsName').val(); 
  var images         = recepeImgsName.split(","); 
  var newImages      = '';
  var filecount       = 0;
  for (var i =0; i <images.length; i++) {
    if(images[i]!=''&&images[i]!=imageName){
      newImages += ','+images[i]; 
      filecount++;
    }
  } 
  $('#recepeImgsName').val(newImages);
  $('#imageCount').val(filecount);
  //$('#recepeImgsName').val(recepeImgsName+','+res.file_name); 
}
function search_result(){
  var title = $('#search_bar').val();
  $('.loader_profile_left').show();
  $.ajax({
    url:base_url+"home/search",
    type:"GET",
    data:{'title':title},
    success: function(html){      
      $('#loader_profile_left').hide();
      $('#result_list').html(html);
    }
  });
}
function tabMenu(id){   
  $('.loader_profile_left').show();
  $('.tab_sec').hide();
  $('#'+id).show();
  if(id=='Images'){
    $('#type_id').val('image');
    $('#page_title_bars').html('Roky | Images');
    $.ajax({ 
      url:base_url+"user/filterImagesRecipes",
      type:"GET",
      data:$( "#blog_recepe_filters" ).serialize(), 
      success: function(html){ 
        $('#user_added_images').html(html);
        $('#user_added_recipes, #user_added_blogs, #filterBookMarkBlogImges, #filterLikesBlogImges, #alldata_profiles').html('');     
        $('.loader_profile_left').hide();
      }
    }); 
  }else if(id=='Recipes'){
    $('#type_id').val('recipe');
    $('#page_title_bars').html('Roky | Recipes');
    $.ajax({ 
      url:base_url+"user/filterImagesRecipes",
      type:"GET",
      data:$( "#blog_recepe_filters" ).serialize(), 
      success: function(html){ 
        $('#user_added_recipes').html(html);
        $('#user_added_images, #user_added_blogs, #filterBookMarkBlogImges, #filterLikesBlogImges, #alldata_profiles').html('');  
        $('.loader_profile_left').hide();
      }
    }); 
  }else if(id=='Blogs'){
    $('#type_id').val('blog');
    $('#page_title_bars').html('Roky | Blogs');
    $.ajax({ 
      url:base_url+"user/filterBlogs",
      type:"GET",
      data:$( "#blog_recepe_filters" ).serialize(), 
      success: function(html){ 
        $('#user_added_blogs').html(html);
        $('#user_added_recipes, #user_added_images, #filterBookMarkBlogImges, #filterLikesBlogImges, #alldata_profiles').html('');  
        $('.loader_profile_left').hide();
      }
    });
  }else if(id=='Likes'){
    $('#type_id').val('likes');
    $('#page_title_bars').html('Roky | Likes');
    $.ajax({ 
      url:base_url+"user/filterLikesBookMarkBlogImges",
      type:"GET",
      data:$( "#blog_recepe_filters" ).serialize(), 
      success: function(html){ 
        $('#filterLikesBlogImges').html(html);
        $('#user_added_recipes, #user_added_blogs, #filterBookMarkBlogImges, #user_added_images, #alldata_profiles').html('');  
        $('.loader_profile_left').hide();
      }
    });
  }else if(id=='Bookmarks'){
    $('#page_title_bars').html('Roky | Bookmarks');
    $('#type_id').val('bookmark');
    $.ajax({ 
      url:base_url+"user/filterLikesBookMarkBlogImges",
      type:"GET",
      data:$( "#blog_recepe_filters" ).serialize(), 
      success: function(html){ 
        $('#filterBookMarkBlogImges').html(html);
        $('#user_added_recipes, #user_added_blogs, #filterLikesBlogImges, #user_added_images, #alldata_profiles').html('');  
        $('.loader_profile_left').hide();
      }
    });
  }  
  $('.loader_profile_left').hide();
}
function imageDetails(post_id=''){
  $('.loader_profile_left').show();
  var viewUser = $('#profile_user_id').val();
  var type_id = $('#type_id').val();
  $.ajax({ 
    url:base_url+"home/post_details",
    type:"GET",
    data:{'post_id':post_id, 'viewUser':viewUser, 'type':type_id}, 
    success: function(html){ 
      $('.loader_profile_left').hide();
      $('#imageDetails_row').html(html);
    }
  }); 
}
function checkMetrick(){
  var useMetricsSystem = $('.useMetricsSystem').is(':checked');
  //alert('s'+useMetricsSystem);
  if(useMetricsSystem==true){    
    $('#withmetric').show();
    $('#withoutmetric').hide();
    $('#weight_id_kg_lbs').attr('placeholder','Enter your weight in kg');
    var weight_id   = $('#weight_id_kg_lbs').val();
    //console.log('weight_id='+weight_id);
    if(weight_id){
      weight_kg     = parseFloat(weight_id) / parseFloat(2.2046);
      //console.log('weight_kg='+weight_kg);
      weight_kg     = parseFloat(weight_kg);
      weight_kg     = weight_kg.toFixed(0);
      //console.log('aft weight_kg='+weight_kg);
      $('#weight_id_kg_lbs').val(weight_kg);
    }
  }else{    
    $('#withoutmetric').show();
    $('#withmetric').hide();
    $('#weight_id_kg_lbs').attr('placeholder','Enter your weight in lbs');
    var weight_id   = $('#weight_id_kg_lbs').val();
    //console.log('weight_id='+weight_id);
    if(weight_id){
      weight_kg     = parseFloat(weight_id)*parseFloat(2.2046);
      //console.log('weight_kg='+weight_kg);
      weight_kg     = parseFloat(weight_kg);
      weight_kg     = weight_kg.toFixed(0);
      // console.log('aft weight_kg='+weight_kg);
      $('#weight_id_kg_lbs').val(weight_kg);
    }
  }
}
function click_imgs(id){
  if(id=='profile_pic'){
    $('#profile_pic').click();
  }else{
    $('#profile_pic1').click();
  }
  $('#file_type_name').val(id);
  //console.log('id='+id);
}
$("#profile_pic").change(function(){
    //console.log('edit_profile_pic');
    var files= document.getElementById('profile_pic').files;  
    var file = files[0];
    uploadFile(file, 1,file.type);
});
$("#profile_pic1").change(function(){
    console.log('profile_pic1');
    var files= document.getElementById('profile_pic1').files;  
    var file = files[0];
    uploadFile(file, 1,file.type);
});
function uploadFile(file, i,type){  
    var xhr            = new XMLHttpRequest(); 
    var formData       = new FormData();  
    var file_type_name = $('#file_type_name').val();
    //alert('file_type_name'+file_type_name);
    formData.append('user_img',file);     
    formData.append('file_type_name',file_type_name);     
    xhr.open("POST", base_url+"user/profile_pic");
      xhr.upload.onprogress = function(e) {
      if (e.lengthComputable) {     
        var percentComplete = (e.loaded / e.total) * 100; 
        $('.loader_profile_left').show();
        //console.log('percentComplete'+percentComplete); 
      }
    };
    xhr.onload = function() {
    if (this.status == 200) {
      var resp = this.response;
      //console.log('resp'+resp);
      $('.loader_profile_left').hide();
        res = JSON.parse(resp);
        if(res.statuss=='true'){
          $('#metrics_img1_error').hide();
          $('#metrics_img2_error').hide();
          $('#profile_pic_error').hide();       
          if(file_type_name=='metrics_img1'){
            $('#metrics_img1').attr('src', res.full_path);
          }else if(file_type_name=='metrics_img2'){
            $('#metrics_img2').attr('src', res.full_path);
          }else{
            $('#left_bar_profile_pic, #top_header_img').attr('src', res.full_path);
          }
        }else{       
          if(file_type_name=='metrics_img1'){
            $('#metrics_img1_error').show().html(res.message);            
          }else if(file_type_name=='metrics_img2'){
            $('#metrics_img2_error').show().html(res.message);  
          }else{
            $('#profile_pic_error').show().html(res.message);  
          }
        }
    };
  };      
  xhr.send(formData);    
} 
function likePost(post_id=''){
  $.ajax({ 
    url:base_url+"home/PostLike",
    type:"GET",
    data:{'post_id':post_id}, 
    success: function(html){ 
      var response = $.parseJSON(html);         
      if(response.status=='true'){ 
        $('#likeText').html(response.message);
        $("#post_likes_counters").html(response.likeCounter);
      }else{       
        $("#post_errors").html(response.message);
      }
    }
  });
}
function followRequest(user_id='',followingUserID=''){
   $.ajax({ 
    url:base_url+"home/followedUser",
    type:"POST",
    data:{'user_id':user_id,'followingUserID':followingUserID}, 
    success: function(html){ 
      var response = $.parseJSON(html);         
      if(response.status=='true'){ 
        $('#postedFollowed, #postedFollowedLeft').html(response.message);
        if(response.message=='Request sent'){
          $('#postedFollowedLeftR').show();
          $('#postedFollowedLeft').hide();
        }
        $('#leftFollowingCount').html(response.FollowingCount);
        $('#leftFollowersCount').html(response.FollowersCount);
      }else{       
        $("#post_errors").html(response.message);
      }
    }
  });
}
function bookMarkPost(post_id=''){
  $.ajax({ 
    url:base_url+"home/bookMarkPost",
    type:"GET",
    data:{'post_id':post_id}, 
    success: function(html){ 
      var response = $.parseJSON(html);         
      if(response.status=='true'){ 
        $('#bookMarkPostText').html(response.message);
      }else{       
        $("#post_errors").html(response.message);
      }
    }
  });
}
function submitComment(){
  var comments = $('#comments').val();
  var error    = 'no';
  if(comments==''){
    $('#comments_error').show().html('The comment is required');
    error = 'yes';
  }else{
    $('#comments_error').hide();
  }
  if(error=='no'){    
    $.ajax({ 
      url:base_url+"home/postComment",
      type:"POST",
      data:$( "#comments_box_form" ).serialize(), 
      success: function(html){ 
        var response = $.parseJSON(html);         
        if(response.status=='true'){ 
          $('#commentsbox').html(response.comments);
          $('#commentsCounts').html(response.commentCounter);
          $('#comments').val('');
        }else{
          $('#comments_error').show().html(response.message);  
        }
      }
    }); 
  }
}
function stepFirst(){
  $.ajax({ 
    url:base_url+"user/stepFirst",
    type:"POST",
    data:$( "#workoutItems" ).serialize(), 
    success: function(html){ 
      var response = $.parseJSON(html);         
      if(response.status=='true'){ 
        $('#nextWorkOut_res').show().html('<div style="margin-top:0px" class="alert alert-success" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>'); 
      }else{ 
        $('#nextWorkOut_res').show().html('<div style="margin-top:0px" class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');  
      }
    }
  }); 
}
function workOutStepFirstRight(){
  $.ajax({ 
    url:base_url+"user/workOutStepFirstValidation",
    type:"POST",
    data:$( "#workoutItems" ).serialize(), 
    success: function(html){ 
      var response = $.parseJSON(html);         
      if(response.status=='true'){ 
        $('#stepFirst').hide('slide', {direction: 'left'}, 1000);
        $('#stepSecound').show('slide', {direction: 'right'}, 1000);
        $('#nextWorkOut_res').hide();
        $('#workout_execies').html(response.secoundStep);
      }else{ 
        $('#nextWorkOut_res').show().html('<div style="margin-top:0px;width: 80%;" class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');  
      }
    }
  }); 
}
function workOutSecoundleft(){
  $('#stepFirst').show('slide', {direction: 'left'}, 1000);
  $('#stepSecound').hide('slide', {direction: 'right'}, 1000);
}
function workOutSecoundRight(){
  $.ajax({ 
    url:base_url+"user/workOutStepSecoundValidation",
    type:"POST",
    data:$( "#workoutItems" ).serialize(), 
    success: function(html){ 
      var response = $.parseJSON(html);         
      if(response.status=='true'){ 
        $('#stepSecound').hide('slide', {direction: 'left'}, 1000);
        $('#stepThird').show('slide', {direction: 'right'}, 1000);
        $('#thirdStepData').html(response.thirdStep);
        $('#secoundStep_res').hide();
      }else{ 
          $('#secoundStep_res').show().html('<div style="margin-top:0px;width: 80%;" class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');  
        }
    }
  });
}
function workOutThirdLeft(){
  $('#stepSecound').show('slide', {direction: 'right'}, 1000);
  $('#stepThird').hide('slide', {direction: 'right'}, 1000);
}
function workOutSave(){
  var error            = 'no';
  var plan_name        = $('#plan_name').val();
  var plan_description = $('#plan_description').val();
  if(plan_name==''){
    $('#plan_name_error').show().html('The Plan  name is required.');
    error = 'yes';
  }else{
    $('#plan_name_error').hide();
  }
  if(plan_description==''){
    error = 'yes';
    $('#plan_description_error').show().html('The Plan  description is required.');
  }else{    
    $('#plan_description_error').hide();
  }
  if(error=='no'){    
    $.ajax({ 
      url:base_url+"user/saveWorkOut",
      type:"POST",
      data:$( "#workoutItems" ).serialize(), 
      success: function(html){ 
        var response = $.parseJSON(html);         
        if(response.status=='true'){ 
          $('#thirdStep_res').show().html('<div style="margin-top:0px" class="alert alert-success" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>'); 
          setTimeout(function(){
            if(response.id!=0){
              window.location.href=base_url+"user/all_workout_plan";
            }else{
              window.location.href=base_url+"user/create_diet_plan?goal_id="+response.goal_id;;
            }
          }, 3000);
        }else{ 
          $('#thirdStep_res').show().html('<div style="margin-top:0px" class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');  
        }
      }
    });
  }
}
function dietPlanStepFirstRight(){
  $.ajax({ 
    url:base_url+"user/dietPlanStepFirstValidation",
    type:"POST",
    data:$( "#dietPlanItems" ).serialize(), 
    success: function(html){ 
      var response = $.parseJSON(html);         
      if(response.status=='true'){ 
        $('#stepFirst').hide('slide', {direction: 'left'}, 1000);
        $('#stepSecound').show('slide', {direction: 'right'}, 1000);
        $('#nextWorkOut_res').hide();
        $('#planDataList').html(response.itemData);
      }else{ 
        $('#nextWorkOut_res').show().html('<div style="margin-top:0px;width: 80%;" class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');  
      }
    }
  }); 
}
function dietPlanSecoundleft(){
  $('#stepFirst').show('slide', {direction: 'left'}, 1000);
  $('#stepSecound').hide('slide', {direction: 'right'}, 1000);
}
function dietPlanStepSecoundRight(){
  $.ajax({ 
    url:base_url+"user/dietPlanStepSecoundValidation",
    type:"POST",
    data:$( "#dietPlanItems" ).serialize(), 
    success: function(html){ 
      var response = $.parseJSON(html);         
      if(response.status=='true'){ 
        $('#stepSecound').hide('slide', {direction: 'left'}, 1000);
        $('#stepThird').show('slide', {direction: 'right'}, 1000);
        $('#secoundStep_res').hide();
        $('#thirdStepData').html(response.itemData);
      }else{ 
        $('#secoundStep_res').show().html('<div style="margin-top:0px;width: 80%;" class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');  
      }
    }
  }); 
}
function dietPlanThirdLeft(){
  $('#stepSecound').show('slide', {direction: 'right'}, 1000);
  $('#stepThird').hide('slide', {direction: 'right'}, 1000);
}
function dietPlanSave(){
  var error            = 'no';
  var plan_name        = $('#plan_name').val();
  var plan_description = $('#plan_description').val();
  if(plan_name==''){
    $('#plan_name_error').show().html('The Plan  name is required.');
    error = 'yes';
  }else{
    $('#plan_name_error').hide();
  }
  if(plan_description==''){
    error = 'yes';
    $('#plan_description_error').show().html('The Plan  description is required.');
  }else{    
    $('#plan_description_error').hide();
  }
  if(error=='no'){  
    $.ajax({ 
      url:base_url+"user/saveDietPlan",
      type:"POST",
      data:$( "#dietPlanItems" ).serialize(), 
      success: function(html){ 
        var response = $.parseJSON(html);         
        if(response.status=='true'){ 
          $('#thirdStep_res').show().html('<div style="margin-top:0px" class="alert alert-success" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>'); 
          setTimeout(function(){
            window.location.href=base_url+"user/all_diet_plan";
          }, 3000);
        }else{ 
          $('#thirdStep_res').show().html('<div style="margin-top:0px" class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');  
        }
      }
    });
  }
}
/********************** ajax validation **********************/
$('#createGoalSetter').validate({
  rules: {
    height: {required: true},
    wieght: {required: true},
    goal_type: {required: true},
    loseWeight: {required: true, number: true},
    loseDay: {required: true, number: true}
  },
  messages: {
    height:{ required:"The height is required"},
    wieght:{ required:"The weight is required",},
    goal_type:{ required:"The lose weight/gain muscle/maintain is required",},
  },
  submitHandler: function(form) {
    var goal_type   = $("input[name='goal_type']:checked").val()
    var loseWeight  = $('#loseWeight').val();
    var loseDay     = $('#loseDay').val();
    if(goal_type==1){
      if(loseWeight==''){
        $('#loseWeight_error').show("The weight is required").html();
      }else{
        $('#loseWeight_error').hide();
      } 
      if(loseDay==''){
        $('#loseDay_error').show().html("The day is required");
      }else{
        $('#loseDay_error').hide();
      }      
    }
    $.ajax({ 
      url:base_url+"user/goal_set_res",
      type:"POST",
      data:$( "#createGoalSetter" ).serialize(), 
      success: function(html){ 
        var response = $.parseJSON(html);         
        if(response.status=='true'){ 
          $('#createGoalSetter_res').show().html('<div style="margin-top:0px" class="alert alert-success" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>'); 
           setTimeout(function(){
            window.location.href=base_url+"user/create_workout_plan?goal_id="+response.goal_id;
          }, 1000);
        }else{ 
          $('#createGoalSetter_res').show().html('<div style="margin-top:0px" class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');  
        }
      }
    }); 
  }
});
function showExerciseDetails(exerciseID,exerciseTitle){
  $.ajax({ 
    url:base_url+"user/showExerciseDetails/"+exerciseID,
    type:"GET",
    success: function(html){ 
      $('#myModalLabel').html(exerciseTitle);
      $('#workoutExerciseDetails').html(html);
    }
  });
}
function dietPlanDetails(itemID,itemTitle){
  $.ajax({ 
    url:base_url+"user/dietPlanItemDetails/"+itemID,
    type:"GET",
    success: function(html){ 
      $('#myModalLabel').html(itemTitle);
      $('#dietPlanDetails').html(html);
    }
  });
}
function mealType(textTitile='',itemID=''){
  $('#mealType'+itemID).val(textTitile);
  $('#mealTypeText'+itemID).html(textTitile);
}
function servingType(textTitile='',itemID=''){
  $('#servingType'+itemID).val(textTitile);
  $('#servingTypeText'+itemID).html(textTitile);
}
function removePlanDay(dietPlanID='',day=''){
  alertify.confirm("Are you sure you want to  remove this food on "+day, function (e) {
    if (e) {
       $.ajax({ 
          url:base_url+"user/removePlanDay",
          type:"POST",
          data:{'dietPlanID':dietPlanID,'day':day},
          success: function(html){ 
            $('#row_'+dietPlanID+"_"+day).hide();
          }
        });
      }
  });
}
function editPlanDay(dietPlanID='', playType=''){
  if(playType=='1'){
    window.location.href= base_url+"user/edit_diet_plan?id="+dietPlanID;
  }else{
    window.location.href= base_url+"user/edit_workout_plan?id="+dietPlanID;
  }
}
function bookmarkPlanDay(dietPlanID='',day=''){
  $.ajax({ 
    url:base_url+"user/bookmarkPlanDay",
    type:"POST",
    data:{'dietPlanID':dietPlanID,'day':day},
    success: function(html){ 
       var response = $.parseJSON(html); 
      $('.bookmark_'+dietPlanID).html(response.bookmarkIcon);
    }
  });
}
function bookmarkWorkOutPlanDay(workOutPlanID='',day=''){
  $.ajax({ 
    url:base_url+"user/bookmarkWorkOutPlanDay",
    type:"POST",
    data:{'workOutPlanID':workOutPlanID,'day':day},
    success: function(html){ 
       var response = $.parseJSON(html); 
      $('.bookmark_'+workOutPlanID).html(response.bookmarkIcon);
    }
  });
}
function activeDeactivePlan(planID='', status='', statusText=''){
  alertify.confirm("Are you sure you want to  "+statusText+" this plan", function (e) {
    if (e) {
      $.ajax({ 
        url:base_url+"user/activeDeactivePlan",
        type:"POST",
        data:{'planID':planID},
        success: function(html){
          var response = $.parseJSON(html);         
          if(response.status=='true'){
            $('#plan_list').html(response.planData);
          }else{
            $('#planActivate_res').show().html('<div style="margin-top:0px" class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');  
          } 
        }
      });
    }
  });
}
function deleteUserPlan(planID=''){
  alertify.confirm("Are you sure you want to  remove this plan", function (e) {
    if (e) {
      $.ajax({ 
        url:base_url+"user/deleteUserPlan",
        type:"POST",
        data:{'planID':planID},
        success: function(html){
          if(html=='yes'){
            window.location.reload();
          }else{
            $('#planIDs_'+planID).hide();
          }
        }
      });
    }
  });
}
function checkGoalType(id=''){
  if(id=='1'){
    $('#gaol_type_in').show();
  }else{
    $('#gaol_type_in').hide();
  }
}
function save_metric_tracker(){    
  $.ajax({ 
    url:base_url+"user/user_metric_tracker_res",
    type:"POST",
    data:$( "#user_metricTracker" ).serialize(), 
    success: function(html){ 
      var response = $.parseJSON(html);         
      if(response.status=='true'){ 
        $('#metric_tracker_res').show().html('<div style="margin-top:0px" class="alert alert-success" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>'); 
         setTimeout(function(){
          window.location.href=base_url+"user/create_workout_plan?goal_id="+response.goal_id;
        }, 1000);
      }else{ 
        $('#metric_tracker_res').show().html('<div style="margin-top:0px" class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');  
      }
    }
  });   
}
function searchUserResult(){
  var  createGoalSetter = $( "#createGoalSetter").serialize();
  window.location.href = base_url+"user/user_result?"+createGoalSetter;
}
function copy_workout_plan(plan_id="",goal_id="",user_id=""){
  $.ajax({ 
    url:base_url+"user/copy_workout_plan",
    type:"POST",
    data:{plan_id:plan_id}, 
    success: function(html){ 
      var response = $.parseJSON(html);         
      if(response.status=='true'){ 
        $('#copy_workout_plan_res').show().html('<div style="margin-top:0px" class="alert alert-success" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>'); 
         setTimeout(function(){
          window.location.href=base_url+"user/show_user_plan?user_id="+user_id+"&goal_id="+goal_id;
        }, 1000);
      }else{ 
        $('#copy_workout_plan_res').show().html('<div style="margin-top:0px" class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');  
      }
    }
  });
}
function copy_diet_plan(plan_id="",goal_id="",user_id=""){
  $.ajax({ 
    url:base_url+"user/copy_diet_plan",
    type:"POST",
    data:{plan_id:plan_id}, 
    success: function(html){ 
      //alert(html);
      var response = $.parseJSON(html);         
      if(response.status=='true'){ 
        $('#copy_diet_plan_res').show().html('<div style="margin-top:0px" class="alert alert-success" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>'); 
         setTimeout(function(){
           window.location.href=base_url+"user/show_user_plan?user_id="+user_id+"&goal_id="+goal_id;
        }, 1000);
      }else{ 
        $('#copy_diet_plan_res').show().html('<div style="margin-top:0px" class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');  
      }
    }
  });
}
function getFollowerUser(user_id=''){
  $.ajax({ 
    url:base_url+"user/getFollowerUser",
    type:"GET",
    data:{user_id:user_id}, 
    success: function(html){ 
      $('#following_user_list').html('');
      $('#follower_user_list').html(html);
    }
  });
}
function getFollowingUser(user_id=''){
  $.ajax({ 
    url:base_url+"user/getFollowingUser",
    type:"GET",
    data:{user_id:user_id}, 
    success: function(html){     
      $('#following_user_list').html(html);
      $('#follower_user_list').html('');
    }
  });
}
function followUserSet(user_id='',followingUserID=''){
  $.ajax({ 
    url:base_url+"home/followedUser",
    type:"POST",
    data:{user_id:user_id,followingUserID:followingUserID}, 
    success: function(html){     
      var response = $.parseJSON(html);         
      if(response.status=='true'){ 
        $('#copy_diet_plan_res').show().html('<div style="margin-top:0px" class="alert alert-success" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>'); 
         setTimeout(function(){
           window.location.href=base_url+"user/show_user_plan?user_id="+user_id+"&goal_id="+goal_id;
        }, 1000);
      }else{ 
        $('#copy_diet_plan_res').show().html('<div style="margin-top:0px" class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');  
      }
    }
  });
}
function unFollowUserSet(user_id='',followingUserID=''){
  $.ajax({ 
    url:base_url+"home/followedUser",
    type:"POST",
    data:{user_id:user_id,'followingUserID':followingUserID}, 
    success: function(html){  
      var response = $.parseJSON(html);         
      if(response.status=='true'){   
        $('#leftFollowingCount').html(response.FollowingCount);
        $('#FollowersCount').html(response.FollowersCount);
        if(response.fstatus==1){
          $('#following_user_'+user_id).hide();
          if(response.FollowingCount==0){
            console.log('null ');
            $('#following_user_'+user_id).before('<h3 class="text-center text-danger"> No following records found</h3>');
          }
           $('#following_'+user_id).html(response.message);
        }else{
          $('#following_'+user_id).html(response.message);
        }
      }else{
        $('#following_users_res').show().html('<div style="margin-top:0px" class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');
      }
    }
  });
}
function changeStatusReq(requestId='',Status=''){
  $.ajax({ 
    url:base_url+"user/changeStatusReq/"+requestId+"/"+Status,
    type:"GET",
    success: function(html){ 
      var response = $.parseJSON(html);         
      if(response.status=='true'){  
        $('#request_'+requestId).hide();
        $('#request_counter').html(response.reqCount);
        $('#leftFollowingCount').html(response.FollowingCount);
        $('#leftFollowersCount').html(response.FollowersCount);
      }
    }
  });
}
function saveMatrix(day=''){
  $.ajax({ 
    url:base_url+"user/saveMatrix",
    type:"POST",
    data:$( "#user_metricTracker_"+day).serialize(), 
    success: function(html){  
      var response = $.parseJSON(html);         
      if(response.status=='true'){ 
        $('#matrix_res_'+day).show().html('<div style="margin-top:0px" class="alert alert-success" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');
        $('#right_m_image').attr('src', response.rightImg);
        $('#right_m_height').html(response.mrightH);
        $('#right_m_weight').html(response.mrightW);
      }else{
        $('#matrix_res_'+day).show().html('<div style="margin-top:0px" class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');
      }
    }
  });
}
function uploadMFiles(day=''){
    console.log('day='+day);
    /*var files= document.getElementById(day+'_body_shot_file').files; */ 
    var files= document.getElementById(day+'_body_shot_file').files;  
    var file = files[0];
    uploadMFileD(file, 1,file.type,day);
}
function uploadMFileD(file, i,type,day){  
  var xhr            = new XMLHttpRequest(); 
  var formData       = new FormData();  
  formData.append('user_img', file);        
  xhr.open("POST", base_url+"user/uploadMatrixFile");
  xhr.upload.onprogress = function(e) {
    if (e.lengthComputable) {     
      var percentComplete = (e.loaded / e.total) * 100; 
      $('.loader_profile_left').show();
      //console.log('percentComplete'+percentComplete); 
    }
  };
  xhr.onload = function() {
    if (this.status == 200) {
      var resp = this.response;
      res = JSON.parse(resp);
      $('.loader_profile_left').hide();
      console.log('res.full_path'+res.full_path);
      if(res.statuss=='true'){
        console.log('day'+day);
        $('#'+day+'_body_shot_error').hide();     
        $('#'+day+'_maxtrix_img').css('display','inline-block');
        $('#'+day+'_maxtrix_img').attr('src', res.full_path);
        $('#'+day+'_body_shot').val(res.file_name);         
      }else{       
        $('#'+day+'_body_shot_error').show().html(res.message);  
      }        
    };
  };      
  xhr.send(formData);      
} 
function show_week_metrix_data(startDate='',endDate=''){
  $('.loader_profile_left').show();
  $.ajax({ 
    url:base_url+"user/getMatrixData",
    type:"POST",
    data:{'startDate':startDate, 'endDate':endDate}, 
    success: function(html){  
      $('#metrics_tracker_data').html(html);
       $('.loader_profile_left').hide();
    }
  });
}
function show_diet_plan_data(startDate='',endDate='', currentID='',currentDate='', user_id='',type=''){
  $('.loader_profile_left').show();
  $.ajax({ 
    url:base_url+"user/getDietPlanData?user_id="+user_id,
    type:"POST",
    data:{'startDate':startDate, 'endDate':endDate, 'currentID':currentID, 'currentDate':currentDate,'type':type}, 
    success: function(html){  
      $('#diet_plan_data').html(html);
       $('.loader_profile_left').hide();
    }
  });
}
function show_workout_plan_data(startDate='',endDate='', currentID='',currentDate='', user_id='',type=''){
  $('.loader_profile_left').show();
  $.ajax({ 
    url:base_url+"user/getWorkOutPlanData?user_id="+user_id,
    type:"POST",
    data:{'startDate':startDate, 'endDate':endDate, 'currentID':currentID, 'currentDate':currentDate, 'type':type}, 
    success: function(html){  
      $('#workout_plan_data').html(html);
       $('.loader_profile_left').hide();
    }
  });
}
function checkDietPlanFood(dietPlan_ids='', day_id='', dates='',plan_id=''){
  $('.loader_profile_left').show();
  $.ajax({ 
    url:base_url+"user/checkDietPlanFood",
    type:"POST",
    data:{'dietPlan_ids':dietPlan_ids, 'day_id':day_id,'dates':dates,'plan_id':plan_id}, 
    success: function(html){ 
      $('.loader_profile_left').hide(); 
      var response = $.parseJSON(html);         
      if(response.status=='true'){
        if(response.Dstatus=='add'){
          $('#row_'+dietPlan_ids+'_'+day_id).addClass('activePlanRow');
          $('#btn_'+dietPlan_ids+'_'+day_id).html('<i class="fa fa-times-circle"></i>');
        }else{
          $('#row_'+dietPlan_ids+'_'+day_id).removeClass('activePlanRow');
          $('#btn_'+dietPlan_ids+'_'+day_id).html('<i class="fa fa-check-circle"></i>');
        }
        $('#totalServing_'+dates).html(response.totalServing);
        $('#totalProtein_'+dates).html(response.totalProtein);
        $('#totalCarbohydrate_'+dates).html(response.totalCarb);
        $('#totalCacalories_'+dates).html(response.totalCalories);        
      }
    }
  });
}
function checkWorkOutEx(workout_exercise_id='', day_id='', dates='', plan_id=''){
  $('.loader_profile_left').show();
  $.ajax({ 
    url:base_url+"user/checkWorkOutEx",
    type:"POST",
    data:{'workout_exercise_id':workout_exercise_id, 'day_id':day_id,'dates':dates,'plan_id':plan_id}, 
    success: function(html){ 
      $('.loader_profile_left').hide(); 
      var response = $.parseJSON(html);         
      if(response.status=='true'){
        if(response.Dstatus=='add'){
          $('#row_'+workout_exercise_id+'_'+day_id).addClass('activePlanRow');
          $('#btn_'+workout_exercise_id+'_'+day_id).html('<i class="fa fa-times-circle"></i>');
        }else{
          $('#row_'+workout_exercise_id+'_'+day_id).removeClass('activePlanRow');
          $('#btn_'+workout_exercise_id+'_'+day_id).html('<i class="fa fa-check-circle"></i>');
        }  
        $('#totalCals_'+dates).html(response.totalCalories);
        $('#totalMinuts_'+dates).html(response.totalMinuts);
        $('#totalSets_'+dates).html(response.totalSets);
        $('#totalRegs_'+dates).html(response.totalRegs);        
        $('#totalWieghts_'+dates).html(response.totalWieghts);        
      }
    }
  });
}
function editWorkPlanEx(exerciseID=''){
  $('.loader_profile_left').show();
  $.ajax({ 
    url:base_url+"user/editWorkPlanEx",
    type:"POST",
    data:{'exerciseID':exerciseID}, 
    success: function(html){ 
      $('.loader_profile_left').hide(); 
      var response = $.parseJSON(html);         
      if(response.status=='true'){
        $('#myModalLabel').html('Edit Exercise');
        $('#workoutExerciseDetails').html(response.rowHtm);
      }
    }
  });
}
function updateWorkOutEx(){
  $('.loader_profile_left').show();
  $.ajax({ 
    url:base_url+"user/updateWorkPlanEx",
    type:"POST",
    data:$("#editWorkOutExDetails").serialize(), 
    success: function(html){ 
      $('.loader_profile_left').hide(); 
      var response = $.parseJSON(html);         
      if(response.status=='true'){
        $('#editWorkOutExDetails_res').show().html('<div style="margin-top:0px" class="alert alert-success" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');        
        setTimeout(function(){
          location.reload();
        }, 1000);
      }else{
        $('#editWorkOutExDetails_res').show().html('<div style="margin-top:0px" class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');
      }
    }
  });
}
function saveWorkOutSecond(){
  $('.loader_profile_left').show();
  $.ajax({ 
    url:base_url+"user/saveWorkOutSecond",
    type:"POST",
    data:$("#workoutItems").serialize(), 
    success: function(html){ 
      $('.loader_profile_left').hide(); 
      var response = $.parseJSON(html);         
      if(response.status=='true'){
        $('#myModalA').modal('show');
        $('#myModalLabel').html('Add Exercise');
        $('#workoutExerciseDetails').html(response.rowHtm);
      }else{
        $('#nextWorkOut_res').show().html('<div style="margin-top:0px" class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');
      }
    }
  });
}
function addNewWorkOutEx(){
  $('.loader_profile_left').show();
  $.ajax({ 
    url:base_url+"user/addNewWorkOutEx",
    type:"POST",
    data:$("#addWorkOutExDetails").serialize(), 
    success: function(html){ 
      $('.loader_profile_left').hide(); 
      var response = $.parseJSON(html);         
      if(response.status=='true'){
        $('#addWorkOutExDetails_res').show().html('<div style="margin-top:0px" class="alert alert-success" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');;
        setTimeout(function(){
          window.location.href = base_url+"user/workout_plan";
        }, 1000);
      }else{
        $('#addWorkOutExDetails_res').show().html('<div style="margin-top:0px" class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');
      }
    }
  });
}
function editDietPlanFood(foodID=''){
  $('.loader_profile_left').show();
  $.ajax({ 
    url:base_url+"user/editDietPlanFood",
    type:"POST",
    data:{'foodID':foodID}, 
    success: function(html){ 
      $('.loader_profile_left').hide(); 
      var response = $.parseJSON(html);         
      if(response.status=='true'){
        $('#myModalLabel').html('Edit Food');
        $('#dietPlanDetails').html(response.rowHtm);
      }
    }
  });
}
function updateDietPlanEx(){
  $('.loader_profile_left').show();
  $.ajax({ 
    url:base_url+"user/updateDietPlanEx",
    type:"POST",
    data:$("#editDietPlanFoodDetails").serialize(), 
    success: function(html){ 
      $('.loader_profile_left').hide(); 
      var response = $.parseJSON(html);         
      if(response.status=='true'){
        $('#editDietPlanFoodDetails_res').show().html('<div style="margin-top:0px" class="alert alert-success" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');        
        setTimeout(function(){
          location.reload();
        }, 1000);
      }else{
        $('#editDietPlanFoodDetails_res').show().html('<div style="margin-top:0px" class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');
      }
    }
  });
}
function saveDietSecond(){
  $('.loader_profile_left').show();
  $.ajax({ 
    url:base_url+"user/saveDietSecond",
    type:"POST",
    data:$("#dietPlanItems").serialize(), 
    success: function(html){ 
      $('.loader_profile_left').hide(); 
      var response = $.parseJSON(html);         
      if(response.status=='true'){
        $('#myModalA').modal('show');
        $('#myModalLabel').html('Add Food');
        $('#dietPlanDetails').html(response.rowHtm);
      }else{
        $('#nextDietPlan_res').show().html('<div style="margin-top:0px" class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');
      }
    }
  });
}
function addNewDietFood(){
  $('.loader_profile_left').show();
  $.ajax({ 
    url:base_url+"user/addNewDietFood",
    type:"POST",
    data:$("#addDietFoodDetails").serialize(), 
    success: function(html){ 
      $('.loader_profile_left').hide(); 
      var response = $.parseJSON(html);         
      if(response.status=='true'){
        $('#addDietFoodDetails_res').show().html('<div style="margin-top:0px" class="alert alert-success" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');;
        setTimeout(function(){
          window.location.href = base_url+"user/diet_plan";
        }, 1000);
      }else{
        $('#addDietFoodDetails_res').show().html('<div style="margin-top:0px" class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');
      }
    }
  });
}
function deletePost(postID=''){
  alertify.confirm("Are you sure you want to  delete  this post ", function (e) {
    if (e) {
       $.ajax({ 
          url:base_url+"user/deletePost",
          type:"POST",
          data:{'postID':postID},
          success: function(html){ 
            $('#post_id_'+postID).hide();
          }
        });
      }
  });
}
function show_more_des(){
  $('#moreDesc, #lessDes').toggle();  
}
function show_less_des(){
  $('#moreDesc, #lessDes').toggle();  
}
function editPost(post_id='',url_page=''){
  window.location.href= base_url+"user/"+url_page+"?post_id="+post_id;
}
function deleteLike(post_id=''){
  alertify.confirm("Are you sure you want to  remove this post  on like", function (e) {
    if (e) {
      $.ajax({ 
        url:base_url+"home/PostLike",
        type:"GET",
        data:{'post_id':post_id}, 
        success: function(html){ 
          var response = $.parseJSON(html);         
          if(response.status=='true'){ 
            $('#post_id_'+post_id).hide();
          }        
        }
      });
    }
  });
}
function deleteBookMark(post_id=''){
  alertify.confirm("Are you sure you want to  remove this post  on bookmark", function (e) {
    if (e) {
      $.ajax({ 
        url:base_url+"home/bookMarkPost",
        type:"GET",
        data:{'post_id':post_id}, 
        success: function(html){ 
          var response = $.parseJSON(html);         
          if(response.status=='true'){ 
            $('#post_id_'+post_id).hide();
          }        
        }
      });
    }
  });
}
function show_ingredients(){
  $('#next_ingredients, #ingredients').show();
  $('#next_instruction, #recipie_instruction, #next_post, #recipie_instruction, #next_containt, #res_imgs, #next_b_containt, #blog_content').hide();
}
function show_instruction(){
  $('#next_instruction, #recipie_instruction').show();
  $('#next_ingredients, #next_containt, #ingredients, #res_imgs, #next_b_containt, #blog_content').hide();
}
function show_post(){
  $('#next_post, #recipie_instruction').show();
  $('#next_containt, #res_imgs, #next_instruction, #recipie_instruction, #next_ingredients, #ingredients, #next_b_containt, #blog_content').hide();
}
function next_containt(){
  $('#next_ingredients, #ingredients, #next_instruction, #recipie_instruction, #next_post, #recipie_instruction, #next_b_containt, #blog_content').hide();
  $('#next_containt, #res_imgs').show();
}
function show_containt(){
  $('#pre_containt, #res_imgs').hide();
  $('#next_b_containt, #blog_content').show();
}
function pre_containt(){
  $('#pre_containt, #res_imgs').show();
  $('#next_b_containt, #blog_content').hide();
}
function addCopyExecis(exerciseID='', keyID=''){
  $.ajax({ 
    url:base_url+"user/addCopyExecis",
    type:"POST",
    data:{'exerciseID':exerciseID},
    success: function(html){
      var response = $.parseJSON(html);         
      if(response.status=='true'){ 
        $('#'+keyID).after(response.rowData);
        var row_keys = $('#row_keys').val();
        if(row_keys){
          $('#row_keys').val(row_keys+','+response.keyID);
        }else{
          $('#row_keys').val(response.keyID);
        }
      }
    }
  });
}
function addDeleteExecis(exerciseID='', keyID=''){
  alertify.confirm("Are you sure you want to  delete  this exercise", function (e){
    if(e){
      var row_keys = $('#row_keys').val();
      $.ajax({ 
        url:base_url+"user/addDeleteExecis",
        type:"POST",
        data:{'exerciseID':exerciseID, "keyID":keyID, "row_keys":row_keys},
        success: function(row_keys){
          $('#'+keyID).remove();
          $('#row_keys').val(row_keys);
        }
      });
    }
  });
}
function copyExecis(exerciseID='', keyID=''){
  $('.loader_profile_left').show(); 
  $.ajax({ 
    url:base_url+"user/copyExecis",
    type:"POST",
    data:{'exerciseID':exerciseID},
    success: function(html){
      var response = $.parseJSON(html);         
      if(response.status=='true'){ 
        $('.loader_profile_left').hide(); 
        $('#'+keyID).after(response.rowData);
        var add_new_execirces = $('#add_new_execirces').val();
        if(add_new_execirces){
          $('#add_new_execirces').val(add_new_execirces+','+response.exeID);
        }else{
          $('#add_new_execirces').val(response.exeID);
        }
      }
    }
  });
}
function deleteExecis(exerciseID='', keyID=''){
  alertify.confirm("Are you sure you want to  delete  this exercise", function (e){
    if(e){
      $.ajax({ 
        url:base_url+"user/deleteExecis",
        type:"POST",
        data:{'exerciseID':exerciseID},
        success: function(html){
          $('#'+keyID).hide();
          var add_delete_execirces = $('#add_delete_execirces').val();
          if(add_delete_execirces){
            $('#add_delete_execirces').val(add_delete_execirces+','+exerciseID);
          }else{
            $('#add_delete_execirces').val(exerciseID);
          }
        }
      });
    }
  });
}
function editWorkOutSave(){
  $.ajax({ 
      url:base_url+"user/editWorkOut",
      type:"POST",
      data:$( "#editWorkOutSave" ).serialize(), 
      success: function(html){ 
        var response = $.parseJSON(html);         
        if(response.status=='true'){ 
          $('#thirdStep_res').show().html('<div style="margin-top:0px" class="alert alert-success" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>'); 
          setTimeout(function(){
            window.location.href=base_url+"user/all_workout_plan";
          }, 3000);
        }else{ 
          $('#thirdStep_res').show().html('<div style="margin-top:0px" class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');  
        }
      }
    });
}
function editDietSave(){
  $.ajax({ 
      url:base_url+"user/editDietPlan",
      type:"POST",
      data:$( "#editDietSave" ).serialize(), 
      success: function(html){ 
        var response = $.parseJSON(html);         
        if(response.status=='true'){ 
          $('#thirdStep_res').show().html('<div style="margin-top:0px" class="alert alert-success" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>'); 
          setTimeout(function(){
            window.location.href=base_url+"user/all_diet_plan";
          }, 3000);
        }else{ 
          $('#thirdStep_res').show().html('<div style="margin-top:0px" class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');  
        }
      }
    });
}
function addCopyFood(exerciseID='', keyID=''){
  $('.loader_profile_left').show(); 
  $.ajax({ 
    url:base_url+"user/addCopyFood",
    type:"POST",
    data:{'exerciseID':exerciseID},
    success: function(html){
      var response = $.parseJSON(html);
      $('.loader_profile_left').hide();          
      if(response.status=='true'){ 
        $('#'+keyID).after(response.rowData);
        var row_keys = $('#row_keys').val();
        if(row_keys){
          $('#row_keys').val(row_keys+','+response.keyID);
        }else{
          $('#row_keys').val(response.keyID);
        }
      }
    }
  });
}
function addDeleteFood(exerciseID='', keyID=''){
  alertify.confirm("Are you sure you want to  delete  this exercise", function (e){
    if(e){
      var row_keys = $('#row_keys').val();
      $.ajax({ 
        url:base_url+"user/addDeleteExecis",
        type:"POST",
        data:{'exerciseID':exerciseID, "keyID":keyID, "row_keys":row_keys},
        success: function(row_keys){
          $('#'+keyID).remove();
          $('#row_keys').val(row_keys);
        }
      });
    }
  });
}
function copyFood(exerciseID='', keyID=''){
  $.ajax({ 
    url:base_url+"user/copyFood",
    type:"POST",
    data:{'exerciseID':exerciseID},
    success: function(html){
      var response = $.parseJSON(html);         
      if(response.status=='true'){ 
        $('#'+keyID).after(response.rowData);
        var add_new_execirces = $('#add_new_execirces').val();
        if(add_new_execirces){
          $('#add_new_execirces').val(add_new_execirces+','+response.exeID);
        }else{
          $('#add_new_execirces').val(response.exeID);
        }
      }
    }
  });
}
function deleteFood(exerciseID='', keyID=''){
  alertify.confirm("Are you sure you want to  delete  this food", function (e){
    if(e){
      $.ajax({ 
        url:base_url+"user/deleteFood",
        type:"POST",
        data:{'exerciseID':exerciseID},
        success: function(html){
          $('#'+keyID).hide();
          var add_delete_execirces = $('#add_delete_execirces').val();
          if(add_delete_execirces){
            $('#add_delete_execirces').val(add_delete_execirces+','+exerciseID);
          }else{
            $('#add_delete_execirces').val(exerciseID);
          }
        }
      });
    }
  });
}
function editDietPlanSave(){
  $.ajax({ 
    url:base_url+"user/editDietPlan",
    type:"POST",
    data:$("#editDietSave").serialize(), 
    success: function(html){ 
      var response = $.parseJSON(html);         
      if(response.status=='true'){ 
        $('#thirdStep_res').show().html('<div style="margin-top:0px" class="alert alert-success" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>'); 
        setTimeout(function(){
          window.location.href=base_url+"user/all_workout_plan";
        }, 3000);
      }else{ 
        $('#thirdStep_res').show().html('<div style="margin-top:0px" class="alert alert-danger" id="actionMessageError"><button type="button" class="close" data-dismiss="alert">&times;</button>'+response.message+'</div>');  
      }
    }
  });
}
