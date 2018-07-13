<?php if(user_logged_in()){?>
        </div>
      </div>
    </section>
  </div>   
<?php }?>
<!-- <footer class="footer">
  <div class="container">
    <div class="col-md-4">
      <p> @ copyright Loreme Impsum</p>
    </div>
    <div class="col-md-8">
      <ul class="list-inline pull-right">
        <li><a href="javascript:void(0);">About</a></li>
        <li><a href="javascript:void(0);">Help</a></li>
        <li><a href="javascript:void(0);">Terms of Services</a></li>
        <li><a href="javascript:void(0);">Privacy</a></li>
        <li><a href="javascript:void(0);">Cookies</a></li>
      </ul>
    </div>
  </div>
</footer> -->
<!--==Image modal start==-->
  <div class="modal fade" id="imageDetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content user_image_detail">
        <div class="modal-body clearfix">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <div id="imageDetails_row"></div>
        </div>       
      </div>
    </div>
  </div>
  <!--==Image modal end==-->
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="<?php echo  FRONT_THEAM_PATH ;?>js/bootstrap.min.js"></script>
  <script src="<?php echo  FRONT_THEAM_PATH ;?>js/jquery.validate.min.js"></script>   
  <script src="<?php echo admin_theam_path();?>js/alertify.min.js"></script> 
  <script src="<?php echo  FRONT_THEAM_PATH ;?>js/front_validation.js"></script>   

<script src="<?php echo  FRONT_THEAM_PATH ;?>js/jquery.sameheight.min.js"></script>  
  <!-- datepicker js -->
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>  
  <script type="text/javascript">
      $('select').each(function(){
          var $this = $(this), numberOfOptions = $(this).children('option').length;        
          $this.addClass('select-hidden'); 
          $this.wrap('<div class="select"></div>');
          $this.after('<div class="select-styled"></div>');
          var $styledSelect = $this.next('div.select-styled');
          $styledSelect.text($this.children('option').eq(0).text());        
          var $list = $('<ul />', {
              'class': 'select-options'
          }).insertAfter($styledSelect);        
          for (var i = 0; i < numberOfOptions; i++) {
              $('<li />', {
                  text: $this.children('option').eq(i).text(),
                  rel: $this.children('option').eq(i).val()
              }).appendTo($list);
          }        
          var $listItems = $list.children('li');        
          $styledSelect.click(function(e) {
              e.stopPropagation();
              $('div.select-styled.active').not(this).each(function(){
                  $(this).removeClass('active').next('ul.select-options').hide();
              });
              $(this).toggleClass('active').next('ul.select-options').toggle();
          });        
          $listItems.click(function(e) {
              e.stopPropagation();
              $styledSelect.text($(this).text()).removeClass('active');
              $this.val($(this).attr('rel'));
              $list.hide();          });        
          $(document).click(function() {
              $styledSelect.removeClass('active');
              $list.hide();
          });
      });
  </script>
  <script type="text/javascript">
    var  base_url ="<?php echo base_url(); ?>";
  </script>
  <script>      
     $(window).scroll(function() {    
        var scroll = $(window).scrollTop();
        if (scroll >= 200) {
          $(".login").addClass("fixnav");
        } else {
          $(".login").removeClass("fixnav");
        }
     });
  </script>
  <script type="text/javascript">
       $(document).ready(function(){
          $('#sign_up').click(function(){
            $('#signup_form').show();   
            $('#display_login').hide();   
            $('#no_account').hide();   
            $('#have_account').show();  
            $('#enter_key_funcation').val('userSignup');   
            $('#page_title_bars').html('ROKY | Sign Up');   
          });
          $('#log_in').click(function(){
            $('#signup_form').hide();   
            $('#display_login').show();   
            $('#no_account').show();   
            $('#have_account').hide();   
            $('#enter_key_funcation').val('userLogin'); 
            $('#page_title_bars').html('ROKY | Login');     
          });      
          <?php if($this->input->get('acntype')=='login'){?>      
              $('#signup_form').hide();   
              $('#display_login').show();   
              $('#no_account').show();   
              $('#have_account').hide(); 
              $('#enter_key_funcation').val('userLogin');
        <?php }
            if($this->input->get('acntype')=='edit_profile'){ ?>                
              $('#edit_profile_model').modal({
                backdrop: 'static',
                keyboard: false
              });
        <?php }
            if($this->input->get('acntype')=='editDiet'){?>
              dietPlanStepFirstRight();
              setTimeout(function(){dietPlanStepSecoundRight();}, 2000);
          <?php }
            if($this->input->get('acntype')=='image'){?>
              tabMenu('Images');
              $('#Images_tab').addClass('active');
              $('#Images').addClass('active in');
              $('#Recipes').removeClass('active in');
              $('#Blogs').removeClass('active in');
        <?php }
            if($this->input->get('acntype')=='recipe'){?>
              tabMenu('Recipes');
              $('#Recipes_tab').addClass('active');
              $('#Recipes').addClass('active in');
              $('#Images').removeClass('active in');
              $('#Blogs').removeClass('active in');
        <?php }
            if($this->input->get('acntype')=='blog'){?>
              tabMenu('Blogs');
              $('#Blogs_tab').addClass('active');
              $('#Blogs').addClass('active in');
              $('#Recipes').removeClass('active in');
              $('#Images').removeClass('active in');
        <?php } ?>          
        setTimeout(function(){
          var userPlanText       = $('#userPlanText').val();
          var privacyText        = $('#privacyText').val();
          var height_title_text  = $('#height_title_text').val();
          var height_cm_title    = $('#height_cm_title_text').val();
          var visibility_text    = $('#visibility_text').val();
          var gender_text        = $('#gender_text').val();
          if(userPlanText!=''){
            $('.selectPlanMain .select-styled').text(userPlanText);  
          } 
          if(privacyText!=''){
            $('.selectPrivacyMain .select-styled').text(privacyText);  
          }                 
          if(height_title_text!=''){
            $('.height_main .select-styled').text(height_title_text);  
          } 
          if(height_cm_title!=''){
            $('.height_cm .select-styled').text(height_cm_title);  
          }  
          if(visibility_text!=''){
            $('.visibility .select-styled').text(visibility_text);  
          } 
          if(gender_text!=''){
            $('.gender .select-styled').text(gender_text);  
          } 
        }, 2000);
        $("#dateofbirth").datepicker({
          dateFormat: 'mm/dd/yy',
          changeMonth: true,
          changeYear: true,
          maxDate:0,         
          chaneYear: true ,
          yearRange: "-70:", 
          maxDate: "-18Y"    
        });  
        $(".datepicker").datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'mm/dd/yy',
        });   
       });
      $('select').each(function(){
        var $this = $(this), numberOfOptions = $(this).children('option').length;      
        $this.addClass('select-hidden'); 
        $this.wrap('<div class="select"></div>');
        $this.after('<div class="select-styled"></div>');
        var $styledSelect = $this.next('div.select-styled');
        $styledSelect.text($this.children('option').eq(0).text());      
        var $list = $('<ul />', {
            'class': 'select-options'
        }).insertAfter($styledSelect);      
        for (var i = 0; i < numberOfOptions; i++) {
            $('<li />', {
                text: $this.children('option').eq(i).text(),
                rel: $this.children('option').eq(i).val()
            }).appendTo($list);
        }      
        var $listItems = $list.children('li');      
        $styledSelect.click(function(e) {
            e.stopPropagation();
            $('div.select-styled.active').not(this).each(function(){
                $(this).removeClass('active').next('ul.select-options').hide();
            });
            $(this).toggleClass('active').next('ul.select-options').toggle();
        });      
        $listItems.click(function(e) {
            e.stopPropagation();
            $styledSelect.text($(this).text()).removeClass('active');
            $this.val($(this).attr('rel'));
            $list.hide();
        });      
        $(document).click(function() {
          $styledSelect.removeClass('active');
          $list.hide();
        });
      });
      $('.lose_w').click(function(){
        $('#lose_w').slideToggle();
      });
      $(document).ready(function(){
        $('.c15').click(function(){
          $('.c150').slideToggle();
        });
        $('.c16').click(function(){
          $('.c160').slideToggle();
        });
      });    
  </script>
  <!-- socail login and and signup -->
  <script type="text/javascript">
      /*308278286334988*/
      window.fbAsyncInit = function() { 
          FB.init({
              appId:'2023014917937867', cookie:true,
              status:true, xfbml:true,oauth : true
          });
      };
      (function(d){
      var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
      if (d.getElementById(id)) {return;}
      js = d.createElement('script'); js.id = id; js.async = true;
      js.src = "//connect.facebook.net/en_US/all.js";
      ref.parentNode.insertBefore(js, ref);
    }(document));

  //facebook login function
  function fblogin_user() {
      FB.login(function(response) {
      if (response.authResponse) 
      {
        FB.api('/me?fields=id,name,email,picture.width(250).height(250)', function(response) {            
          var email = response.email
          var fb_id = response.id;
          var name = response.name;
          var url = response.picture.data.url;        
            $.ajax({
              url: "<?php echo base_url('home/fb_login'); ?>",
              type:'POST',
              data:{url:url,name:name,email:email,fb_id:fb_id},
              success: function(result){  
                if(result=='success'){   
                  userSignup();                              
                  $('#signup_name').val(name);
                  $('#signup_user_email').val(email);
                  $('#signup_user_email').attr('readonly', true);
                  $('#email_verified').val('yes');                  
                }else if(result=='blocked'){
                  $('#login_error_res, #signup_success_res').show().html('Your account has been banned from using the roky platform. If you believe this has been in error, then please contact support@roksor.com');
                }else {
                  window.location.href=base_url+redirect_url;
                }
            }
          }); 
        });
      } 
      else 
      {
        console.log('User cancelled login or did not fully authorize.');
      }
    }, {scope: 'email,user_likes'});
  }
  </script>
  <script type="text/javascript"> 

  function googleLogin() 
  {
      /*524722519232-2q8k4or2c63s5j0j3slbcttadqtceki0.apps.googleusercontent.com*/
    var myParams = {
      'clientid' : '942680240391-s6ktbk5cpdb5p5vtrijtdnmucp58neqj.apps.googleusercontent.com',
      'cookiepolicy' : 'single_host_origin',
      'callback' : 'loginCallback',
      'approvalprompt':'force',
      'scope' : 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.profile.emails.read'
    };
    gapi.auth.signIn(myParams);
  }
   
  function loginCallback(result)
  {
    console.log(result);
    console.log(result['status']['signed_in']); 
      if(result['status']['signed_in'])
      {
          var request = gapi.client.plus.people.get(
          {
              'userId': 'me'
          });
          request.execute(function (resp)
          {
              var email = '';
              if(resp['emails'])
              {
                  for(i = 0; i < resp['emails'].length; i++)
                  {
                      if(resp['emails'][i]['type'] == 'account')
                      {
                          email = resp['emails'][i]['value'];
                      }
                  }
              }            
              $.ajax({
                url: "<?php echo base_url('home/gmail_login'); ?>",
                type:'POST',
                data:{url:resp['image']['url'],name:resp['displayName'],email:email,'page_url':resp['url']},
                success: function(result){  
                  if(result=='success'){
                    userSignup();
                    $('#signup_name').val(resp['displayName']);
                    $('#signup_user_email').val(email);
                    $('#signup_user_email').attr('readonly', true);
                    $('#email_verified').val('yes');
                  }else if(result=='blocked'){
                    $('#login_error_res').show().html('Your account has been banned from using the Roky platform. If you believe this has been in error, then please contact support@roksor.com');
                  }else {
                    window.location.href=base_url+redirect_url;
                  }
              }
            }); 
          });   
      }  
  }
  function onLoadCallback()
  {
     /* gapi.client.setApiKey('AIzaSyDImb2sTqlogp_j217RIQck74G9PrT4LTc');*/
      gapi.client.setApiKey('AIzaSyCny-3vVRQs8JzDNsWQenoInhtYzojNclc');
      gapi.client.load('plus', 'v1',function(){});
  }
  $(window).scroll(function(){
    var scrollTop = $(window).scrollTop();
    var documentH = $(document).height();
    var windowH = $(window).height();
    var diff = documentH- windowH;
    if ($(window).scrollTop() == $(document).height() - $(window).height()){
      console.log('scrollTop='+scrollTop+', documentH='+documentH+', windowH='+windowH+', diff='+diff);
      var lastCounter = parseInt($("#lastCounter").val());
      var currentCounter  = parseInt($("#currentCounter").val());
      if(lastCounter >= currentCounter) {
        var pageNumber = parseInt($("#currentCounter").val()) + parseInt(1);
        $("#currentCounter").val(pageNumber);
        $('.loader_profile_left').show();
        $.ajax({ 
          url:base_url+"home/getPostAjx",
          type:"GET",
          async:false,
          data:$( "#pagination_frm" ).serialize(), 
          success: function(html){               
            var response = $.parseJSON(html);   
            if(response.status=='true'){ 
              $('#load_datas').before(response.postDetails);               
              setTimeout(function(){ $('.loader_profile_left').hide(); }, 1500);
            }          
          }
        });
      }
    }
  });
  </script> 
   <script type="text/javascript">
        (function() {
         var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
         po.src = 'https://apis.google.com/js/client.js?onload=onLoadCallback';
         var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
       })();       
  </script>
  <!-- <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=ws0ho5ws7nm06fv84r1xenzsi6owcbznfybver4tmy20dywp"></script>
  <script>     
    tinymce.init({
      selector: '.tinymce_edittor',
      height: 500,
      theme: 'modern',
      plugins: 'print preview powerpaste searchreplace autolink directionality advcode visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount tinymcespellchecker a11ychecker imagetools mediaembed  linkchecker contextmenu colorpicker textpattern help',
      toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
      image_advtab: true,
      templates: [
        { title: 'Test template 1', content: 'Test 1' },
        { title: 'Test template 2', content: 'Test 2' }
      ],
      content_css: [
        '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
        '//www.tinymce.com/css/codepen.min.css'
      ]
    });
  </script> -->
   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
 <!--==script progress-bar start==-->
 <script type="text/javascript">
    function drawChart() {
      var tab_types = $('#tab_types').val();
      var allRowD   = [];
      var data = new google.visualization.DataTable();
      if(tab_types=='weekly'){
        data.addColumn('string', 'Day');
      }else if(tab_types=='monthly'){
        data.addColumn('string', 'Day');
      }else if(tab_types=='yearly'){
        data.addColumn('string', 'Month');
      }else{        
        data.addColumn('string', 'Day');
      }
      var sufixText ='';
      $.ajax({
        url:base_url+"home/chartData",
        type:"POST",
        async: false,
        data:$("#progressChartFrm").serialize(),
        success: function(html){ 
          var response = $.parseJSON(html);
           lineCols    = response.lineCols;
          for (ci = 0; ci < lineCols.length; ci++) { 
             data.addColumn('number', lineCols[ci]);
          }
          allRowD   = response.rowD; 
          sufixText =  response.sufixText;
        }
      });
      data.addRows(allRowD);
      var options = {
        chart: {
          title: '',
          subtitle: ''
        },
        vAxis: {format:'# '+sufixText},
        width: 650,
        height: 500
      };
      var chart = new google.charts.Line(document.getElementById('chart_div'));
      chart.draw(data, google.charts.Line.convertOptions(options));
    }
    function changeChart(param=''){
      $('.tab-link').removeClass('active');
      $('#'+param).addClass('active');
      $('#tab_types').val(param);
      google.charts.load('current', {'packages':['line']});
      google.charts.setOnLoadCallback(drawChart);
    }
    function changeChartLine(lineParm){
      google.charts.load('current', {'packages':['line']});
      google.charts.setOnLoadCallback(drawChart);
    }    
    changeChart('all');   
  </script>  
  <!-- <script type="text/javascript" src="http://maps.google.com/maps/api/js?libraries=places&key=AIzaSyCny-3vVRQs8JzDNsWQenoInhtYzojNclc"></script>
  <script>
      var autocomplete = new google.maps.places.Autocomplete($("#location")[0], {});
      google.maps.event.addListener(autocomplete, 'place_changed', function() {
        var place = autocomplete.getPlace();
        console.log(place.address_components);
      });
  </script> -->

<script type="text/javascript">
  $(document).ready(function(){
    $('#bar').click(function(){
     // alert('devas');
      $('#login_main').slideToggle();
    });
  });
</script>
<script src="https://cdn.ckeditor.com/ckeditor5/10.1.0/classic/ckeditor.js"></script>
<script>
      ClassicEditor
        .create( document.querySelector( '.tinymce_edittor' ) )
        .then( editor => {
          console.log( editor );
        } )
        .catch( error => {
          console.error( error );
        } );
    </script>


  </body>  
</html>
