<script language="javascript">

 jQuery(document).ready(function(){
 
 jQuery('#firstid').hide();
 
 var e_id = jQuery('#linkwag_email').val();
 if(e_id == '' )
 {
	jQuery('#link_e_id').show();
 }else
 {
	jQuery('#link_e_id').hide();
 }
 
 jQuery('#linkwag_email').keyup(multiply);
 
function multiply()
 { 
	jQuery('#link_e_id').hide();
	 var e_id = jQuery('#linkwag_email').val();
	 if(e_id == '' )
	 {
		jQuery('#link_e_id').show();
	 }
 }
 jQuery('#unique').keyup(multiply1);
 function multiply1()
 {
	var e_id = jQuery('#linkwag_email').val();
	
	if(e_id == '')
	{
		alert("First Fill the Linkwag E-Mail");
		jQuery('#unique').focus();
		jQuery('#unique').val(''); 
	}
	 
 }
 
 });
function validateboth()
{
var uniquevalue = jQuery('#unique').val(); 
	if(uniquevalue !='')
	{
		return true;
	}else
	{
		alert("somthing worng");
		return false;
	}
}
 
function validateEmail() {

    var emailText = document.getElementById('linkwag_email').value;
	var uniquevalue = jQuery('#unique').val();
	
	if(emailText != '')
{	
    var pattern = /^[a-zA-Z0-9\-_]+(\.[a-zA-Z0-9\-_]+)*@[a-z0-9]+(\-[a-z0-9]+)*(\.[a-z0-9]+(\-[a-z0-9]+)*)*\.[a-z]{2,4}$/;
    if (pattern.test(emailText)   ) {
	
		return true;
		
	} else {

        var div = document.getElementById('error');
	div.innerHTML = "Please enter <b>VALID</b> email-id and Unique Number";

		// set style
		div.style.color = 'red';

       return false;
    }
}
}
</script>



<style>



.myclass {left: 512px;position: relative;top:-450px;}



</style>



 <?php 

 //update_option('linkwag_uni',$randno);
 $linkwag_unique = get_option('linkwag_uni');
 
function generate_random_password($length = 10) {  
 $alphabets = range('A','Z');  
 $numbers = range('0','9');   
 $final_array = array_merge($alphabets,$numbers);   
 $password = '';    
 while($length--) { 
 
 $key = array_rand($final_array);  
 $password .= $final_array[$key];  
 }    
 return $password;  }
 
 if($_POST['save_linkwag_email'])
 {

	$linkwag_unique = get_option('linkwag_uni');
	if($_POST['linkwag_email'] !='' )  
	{
		$linkwag_email = get_option('linkwag_email');
			if($_POST['linkwag_email'] == $linkwag_email )
			{
				if($linkwag_unique == $_POST['unique_key'] )
				{
					$y_login = update_option('y_login','1');
					$val = "Successfull";
					update_option('linkwag_unique',$_POST['unique_key']);
				} else 
				{
					$y_login = update_option('y_login','');
					$val = "You entered wrong verification ";
				} 
			} else {
			
				$randno = generate_random_password(10);
				update_option('linkwag_uni',$randno);
				get_option('linkwag_uni');

				 $to=$_POST['linkwag_email'];
					
						$subject="New Account Created";
						$headers = "From: info@linkwag.com\r\n";
						$headers .= "MIME-Version: 1.0\r\n";
						$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
						$mailtext .="<TR>";
						$mailtext .="<TD width=30% valign=top class=text><BR></TD>";
						$mailtext .="<TD width=70% valign=top class=text>Thank you for registering with us!!&nbsp;</TD>";
						$mailtext .="</TR>";
						$mailtext .="<TR>";
						$mailtext .="<TD width=30% valign=top class=text><BR></TD>";
						$mailtext .="<TD width=70% valign=top class=text> Verification Code =".$randno."</a><br /><br /><BR /></TD>";
						$mailtext .="</TR>";
				
						$send = mail($to,$subject,$mailtext,$headers);
						
						if($send)
						{
							update_option('linkwag_email',$_POST['linkwag_email']);
							update_option('linkwag_unique',$_POST['unique_key']);
							$val = "Please check your mail and verify your E-Mail account ";
						}
					}
	 } 
 }
 
$df = get_theme_root();
$my_file = $df."/linkwag.txt";

if(file_exists($my_file))
{
	$handle = fopen($my_file, 'r');
	$data = fread($handle,filesize($my_file));
 }


 if(get_option('linkwag_email')==''){



add_option('linkwag_email','');

add_option('linkwag_unique','');

}




if((isset($_POST['save_linkwag_options'])) && (isset($_POST['wag_prev_link'])) && $_POST['wag_prev_link']==2 ){




						 $ivalue=$_POST['wag_prev_link'];


						 $file = fopen($my_file , "w");

						fwrite($file,$ivalue);

						fclose($file); 

$my_file = $df."/linkwag.txt";

$handle = fopen($my_file, 'r');

$data = fread($handle,filesize($my_file));
 $data;

  update_option('linkwag_email',$_POST['linkwag_email']);

  update_option('linkwag_unique',$_POST['unique_key']);

$w1=$_POST['lw'];

 global $posts,$wpdb;





if($_POST['wag_prev_link']==2){



  $result = $wpdb->get_results( "select * from $wpdb->posts where post_type='post' OR post_type='page' AND (post_status='publish' OR post_status='draft' OR post_status='trash')  ");

 

}



$pst_id=array();



 foreach ($result as $name) { 



$pst_id[]=$name->ID;







$linkid='linkwag'.$name->ID;



$getid=get_option($linkid);



 if($getid!=2){



 //echo $name->ID."</br>";



 ?>



<script type="text/javascript">



    jQuery(document).ready(function(){



 		var pid ="<?php echo $name->ID; ?>";



		var state =2;

			

		jQuery.post(ajaxurl,{action:'ajax','fb':'wag_post','state':state,id:pid})







    });

	



</script>



<?php }



}



}

if((isset($_POST['save_linkwag_options'])) && $_POST['wag_prev_link']==''){

						 $ivalue= '0';
						 $file = fopen($my_file , "w");
						fwrite($file,$ivalue);
						fclose($file); 


$handle = fopen($my_file, 'r');

$data = fread($handle,filesize($my_file));

 
 
   update_option('linkwag_email',$_POST['linkwag_email']);
 update_option('linkwag_unique',$_POST['unique_key']);
 
$_SESSION['totallink'] = $_POST['wag_prev_link'];



global $posts,$wpdb;



$result = $wpdb->get_results( "select * from $wpdb->posts where post_type='post' OR post_type='page' AND (post_status='publish' OR post_status='draft' OR post_status='trash')  ");



$pst_id=array();



foreach ($result as $name) { 



$pst_id[]=$name->ID;







$linkid='linkwag'.$name->ID;



$getid=get_option($linkid);



if($getid!=2){



 



 ?>



<script type="text/javascript">



    jQuery(document).ready(function(){

		//alert('fgdf');

 		var pid ="<?php echo $name->ID; ?>";



		var state = 0;

		//alert(pid);

		jQuery.post(ajaxurl,{action:'ajax','fb':'wag_post','state':state,id:pid})







    });

	



</script>



<?php }



}

}

 ?>





<div class="data">



<h3>Linkwag Account Settings</h3>



<?php if(get_option('y_login') != '1')  { ?>
<form  method="post" onsubmit="return validateEmail();"action="admin.php?page=linkwag-settings">

  					<div class="f_row">

					<div id="error"></div>

                            <div class="f_left"><strong>Linkwag Email:</strong> 

                            <input style="width:250px;" value="<?php echo get_option('linkwag_email'); ?>" name="linkwag_email" id="linkwag_email" type="email" title="Enter Your Linkwag Email..." />
							<br />												<?php $linkwag_unique = get_option('linkwag_uni'); if($linkwag_unique != '') { ?>					
							Verification Code:<input id="unique" type="text" name="unique_key" value="<?php echo get_option('linkwag_unique'); ?>">												<?php } ?>

                             </div>
							 <div id= "link_e_id" target= "_blank"><a href="http://linkwag.com/register.php"> http://linkwag.com/register.php </a> </div>

                    </div> 

				<div class="f_left">							<input type="hidden" name="save_changes" value="0"  />							<input type="submit" name="save_linkwag_email" class="submit_btn" value="Save Changes" onsubmit="validateEmail();" /></div>
				 <div class="f_row">		                              <strong>Note: </strong>Email you entered above should match with the Email you registered your linkwag Account. Otherwise your data will not show up on Linkwag.com					</div>

</form>
<?php } ?>


  <form id="email_form" name="test" method="post" onsubmit="return validateEmail();"action="admin.php?page=linkwag-settings">  	

							<input style="width:250px;" value="<?php echo get_option('linkwag_email'); ?>" name="linkwag_email" id="linkwag_email" type="hidden" title="Enter Your Linkwag Email..." />
							<br />
							<input id="unique" type="hidden" name="unique_key" value="<?php echo get_option('linkwag_unique'); ?>">
<div class="f_row">							<span style="color:red;"><?php echo $val; ?></span> <br/>                          </div>


</div>


<div class="data">



<h3>Plugin Settings</h3>
 
							<!--<input  class="txt_field" id="cheaked" value="" name="wag_prev_link" type="checkbox" checked="checked"/>-->
<?php if (file_exists($my_file)) { ?>
<div class="f_row">
						<div class="f_left">Wag my previously created links: </div>

							

                            <div class="f_right" style="width:90px;">
							<input  class="txt_field" id="cheaked" value="2" name="wag_prev_link" type="radio" <?php if( $data == '2' ) { ?> checked="checked"  <?php } ?>/>Yes

							<input  class="txt_field" id="cheaked" value="" name="wag_prev_link" type="radio" <?php if( $data == '0' ) { ?> checked="checked"  <?php } ?> />No
							</div>

						</div>
<?php } else { ?>


							<input  class="txt_field" id="firstid" value="2" name="wag_prev_link" type="radio" checked="checked"  />
					<?php 	}	?>
							



 						 <div class="f_row">



                            <div class="f_left">Automatically Wag all new Posts: </div>



                            <div class="f_right"><input value="1" class="txt_field" name="lw[option][wag_new_post_auto]" type="checkbox"   <?php if(get_option('wag_new_post_auto')== '1'){ echo "checked=checked"; }?> /></div>



						</div>



                        <div class="f_row">



                            <div class="f_left">Automatically Wag all new Pages: </div>



                            <div class="f_right"><input value="1" class="txt_field" name="lw[option][wag_new_page_auto]" type="checkbox"  <?php if(get_option('wag_new_page_auto')== '1') echo "checked=checked"; ?> /></div>



						</div>



                        <div class="f_row">



                            <div class="f_left">Add LinkWag icon to the end of Post content: </div>



                            <div class="f_right"><input value="1" class="txt_field" name="lw[option][icon_post]" type="checkbox"  <?php if(get_option('icon_post') == '1') echo "checked=checked"; ?> /></div>



						</div>



                        <div class="f_row">



                            <div class="f_left">Add LinkWag icon to the end of Page content: </div>



                            <div class="f_right"><input value="1" class="txt_field" name="lw[option][icon_page]" type="checkbox"  <?php if(get_option('icon_page') == '1') echo "checked=checked"; ?> /></div>



						</div>



                        <div class="f_row last">


							<?php if(get_option('y_login') == '1') { ?>
							
                            <div class="f_left">							<input type="hidden" name="save_changes" value="0"  />							<input type="submit" name="save_linkwag_options" class="submit_btn" value="Save Changes" onsubmit="validateEmail();" />&nbsp;&nbsp;&nbsp;<b>Hit save changes button to make linkwag work properly</b></div>
							
							<?php } ?>



                            <div class="f_right">&nbsp;</div>



						</div>

    



</form>





</div>