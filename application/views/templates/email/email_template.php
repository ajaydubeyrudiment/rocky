
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title></title><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta name="viewport" content="width=device-width" />
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   <!--  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> -->
   <style>
   /* PUT ALL CSS IN THE EMAIL <HEAD>

These styles are meant for clients that recognize CSS in the <head>; the email WILL STILL WORK for those that don't. */

#outlook a{padding:0;}
body{width:100% !important; background-color:#41849a;-webkit-text-size-adjust:none; -ms-text-size-adjust:none;margin:0 !important; padding:0 !important;}  
.ReadMsgBody{width:100%;} 
.ExternalClass{width:100%;}
ol li {margin-bottom:15px;}
  
img{height:auto; line-height:100%; outline:none; text-decoration:none;}
#backgroundTable{height:100% !important; margin:0; padding:0; width:100% !important;}
  
p {margin: 1em 0;}
  
/*h1, h2, h3, h4, h5, h6 {color:#222222 !important; font-family:"Times New Roman", Times, serif; line-height: 100% !important;}*/
  
table td {border-collapse:collapse;}
  
.yshortcuts, .yshortcuts a, .yshortcuts a:link,.yshortcuts a:visited, .yshortcuts a:hover, .yshortcuts a span { color: black; text-decoration: none !important; border-bottom: none !important; background: none !important;}
  
.im {color:black;}
div[id="tablewrap"] {
    width:100%; 
    max-width:600px!important;
  }
table[class="fulltable"], td[class="fulltd"] {
    max-width:100% !important;
    width:100% !important;
    height:auto !important;
  }
      
@media screen and (max-device-width: 430px), screen and (max-width: 430px) { 
    td[class=emailcolsplit]{
      width:100%!important; 
      float:left!important;
      padding-left:0!important;
      max-width:430px !important;
    }
    td[class=emailcolsplit] img {
    margin-bottom:20px !important;
    }
}
   </style>

</head>

  
<body style="width:100% !important; margin:0 !important; padding:0 !important; -webkit-text-size-adjust:none; -ms-text-size-adjust:none; background-color:rgb(232,232,232);font-family: arial;">
<table cellpadding="0" cellspacing="0" border="0" id="backgroundTable" style="height:auto !important; margin:0; padding:0; width:100% !important; background-color:rgb(232,232,232); color:#222222;font-family: arial;">
  <tr>
    <td>
         <div id="tablewrap" style="width:100% !important; max-width:700px !important;margin-top:40px !important; margin-right: auto !important; margin-bottom:40px !important; margin-left: auto !important;">
          <table id="contenttable" width="700" align="center" cellpadding="0" cellspacing="0" border="0" style="background-color:#FFFFFF; margin-top:50px !important; margin-right: auto !important; margin-bottom:50px !important; margin-left: auto !important;border: 3px solid #d6d1bf;; width: 100% !important; max-width:700px !important;">
            <tr>
                <td width="100%">
                  <table border="0" cellspacing="0" cellpadding="3" width="100%" style="border-bottom: 1px solid #cecece;">
                        <tr>
                            <td bgcolor="#ffffff" style="text-align:center;"><a href="<?php echo base_url() ?>" target="blank"><img src="<?php echo base_url().site_info('logo_url');?>"  border="0" width="140"></a>
                            </td>
                            <td style="text-align:center;">
                              
                            </td>
                        </tr>
                   </table>
                </td>
            </tr>
            <tr>
                <td>
                  <table border="0" cellspacing="0" cellpadding="5" width="100%">
                    <tr>
                      <td width="100%"  style="text-align:left;padding: 20px 20px 0;">
                      <?php echo $email_message; ?>    
                      </td>
                    </tr>
                   </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table  border="0" cellspacing="0" cellpadding="5" width="100%">
                        <tr>
                            <td width="100%"  style="text-align:left;">
                             
                              <p style="color:#504A4A; font-family:Arial, Helvetica, sans-serif; font-size:11px; line-height:20px; font-size:15px; margin-top:0; margin-bottom:15px;padding: 10px 15px 0; font-weight:normal;">
                              Regards,<br/>
                                   <a style="color:#2489B3; font-weight:normal; text-decoration:none;" href="<?php echo base_url();?>"> <?php echo site_info('site_title'); ?></a>
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>            
            <tr>
              <td style="vertical-align: middle;text-align: center;">
              <table width="100%" cellpadding="5">
              <tr><td width="50%" style="text-align: right;">Follow Us</td>
               <td width="50%"  style="text-align: left;">
                <a href="<?php echo site_info('fb_follow');?>" style="display:inline-block;vertical-align: middle;text-decoration: none;"><img src="<?php echo  ADMIN_THEAM_PATH ;?>img/facebook.png" width="25"></a>
                <a href="<?php echo site_info('google_follow'); ?>" style="display:inline-block;    vertical-align: middle;text-decoration: none;"><img src="<?php echo  ADMIN_THEAM_PATH ;?>img/google_pluse.png" width="25"></a>
                <a href="<?php echo  site_info('twitor_follow'); ?>" style="display:inline-block;    vertical-align: middle;text-decoration: none;"><img src="<?php echo  ADMIN_THEAM_PATH ;?>img/twittor.png" width="25"></a>
               </td>
              </tr>
              </table>
              </td>
            </tr>
            <tr>
              <td>
                <table cellpadding="2" style="color:#0b5fa5;width:53%;table-layout:fixed;line-height: 13px;margin: 0 auto;" align="center">
                  <tr>
                    <?php if(site_info('contact_us_link')){ ?>
                    <td style="text-align: center;width:21%;border-right: 1px solid #0b5fa5;"><a href="<?php echo base_url();?><?php echo site_info('contact_us_link'); ?>" style="color:#0b5fa5;font-family: arial;text-decoration: none;font-size: 14px;font-weight:600;">Contact Us</a></td>  
                    <?php 
                    } 
                    if(site_info('privacy_policy')){?>                  
                     <td style="text-align: center;width:25%;border-right: 1px solid #0b5fa5;"><a href="<?php echo base_url();?><?php echo site_info('privacy_policy'); ?>" style="color:#0b5fa5;font-family: arial;font-size: 14px;font-weight:600;text-decoration: none;">Privacy Policy</a></td>
                     <?php } 
                     if(site_info('services')){?>
                      <td style="text-align: center;width:10%;"><a href="<?php echo base_url();?><?php echo site_info('services'); ?>" style="color:#0b5fa5;font-family: arial;font-size: 14px;font-weight:600;text-decoration: none;">Service</a></td>
                    <?php  } ?> 
                  </tr>
                </table>
              </td>
            </tr>
            <tr style="background:#fff">
            <td style="text-align:center;color:#838383;font-size:14px;padding-top:5px;padding-bottom:5px" colspan="2">© <?php echo date('Y')?> <span class="il"><?php echo site_info('site_title'); ?></span>  <?php echo site_info('contact_address'); ?> </td>
            </tr>
            <tr>
              <td>
                <table width="100%" cellpadding="5" style="border-top: 1px solid #dad8d8;color: #838383;font-size:12px;">
                  <tr>
                   <td style="text-align:right;">&nbsp;<b>Phone Number :</b> <?php echo site_info('site_contact_no'); ?> </td>
                   <td style="text-align:left;">&nbsp;<b>E-mail Us :</b> <span style="color: #838383;font-size:12px;text-decoration: none;"><?php echo site_info('global_email'); ?></span></td>
                  </tr>
                </table>
              </td>
            </tr>
        </table>
        </div>
    </td>
  </tr>
</table> 
</body>
</html>