<?php
if ( ! defined( 'ABSPATH' ) ) exit;

##################################
##### GENERAL FORM SUBMITTER #####
##################################

	
	function sttv_ajax_submitter() {
		
		if (isset($_POST['value'])) :
			parse_str($_POST['value'], $output);
			
			$verified = false;
			
			if ($output['g-recaptcha-response']) : // verify recaptcha if it exists
			
				$recapSecret = '6LdjuA0UAAAAAEtl1TF36zJlwZkVwuSepZiwtJCf';
			
				$response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$recapSecret."&response=".$output['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']),true);
				
					if (!$response['success']) :
						echo wp_send_json_error(array('rmessage'=>'Verification failed. Please try again.','response'=>$response),403);
					endif;
				
			endif; // end verify recaptcha
			
			
			switch ($output['whichform']) :
			
				case 'login' : //checks if this is a login attempt
					//wp_send_json_error($output);
					sttv_ajax_login($output['sttv_user'],$output['sttv_pass']);
					break;
				
				case 'contact' : //checks if this is a contact form attempt
					sttv_send_contact_form('enlightenedpie@gmail.com',$output['sttv_contact_subject'],$output['sttv_contact_message'],$output['sttv_contact_name'],$output['sttv_contact_email']);
					break;
					
				case 'subscribe' :
					sttv_subscribe_send($output['sttv_mc_fname'],$output['sttv_mc_lname'],$output['sttv_mc_email']);
					break;
				
				default;
					wp_send_json_error(array('message'=>'What are you doing?'));
				
			endswitch;
			
		else :
			wp_die('You\'re doing it wrong.');
		endif;
	}
	
	################################
	##### CONTACT FORM HANDLER #####
	################################
	
	function sttv_send_contact_form($to,$subject,$message,$name,$email) {
				
				$headers[] = 'Content-Type: text/html; charset=UTF-8';
				$headers[] = 'Reply-to: '.$name.' <'.sanitize_email($email).'>';
				$headers[] = 'From: SupertutorTV Website <info@supertutortv.com>';
				//$headers[] = 'Bcc: David Paul <dave@supertutortv.com>';
				
				$sentmail = wp_mail($to,$subject,$message,$headers);
				
				if ($sentmail) :
					wp_send_json_success(array('message'=>'Thanks for contacting us! We\'ll get back to you ASAP!','sent'=>$sentmail));
				else :
					wp_send_json_error(array('message'=>'There was an issue sending your message. Please try again later.','sent'=>$sentmail));
				endif;
	}



	function sttv_login_form() { ?>
		<div id="form-wrapper" class="z-depth-4">
		<div class="loading_overlay"></div>
		<div id="form-identity">
			<img src="<?php print get_header_image(); ?>" alt="Login form header" />
			<p>Welcome. Please login.</p>
		</div>
		<form id="sttv_login_form" action="/" method="post">
			<p class="message"></p>
            <div class="row">
        		<div class="input-field s12">
                    <input type="text" name="sttv_user" id="sttv_user" minlength="4" />
                    <label for="sttv_user" data-error="Must be at least 4 characters" data-success="Good job!">Username</label>
                </div>
                <div class="input-field s12">
                    <input type="password" name="sttv_pass" id="sttv_pass" />
                    <label for="sttv_pass">Password</label>
                </div>
            </div>
			<button type="submit" class="z-depth-1 submitter" id="login-btn">Login</button>
            <input type="hidden" name="whichform" value="login" />
		</form>
		<div id="forgot-block"><a class="lostpwd" href="<?php print wp_lostpassword_url(); ?>">Forgot your password?</a></div>
		</div><?php 
	}
	
	function sttv_ajax_login($usernm,$pass) {
			$usernm = sanitize_user($usernm,true);
			
		if (!empty($usernm) && validate_username($usernm)) {
				
				$user = wp_signon( array('user_login' => $usernm, 'user_password' => $pass, 'remember' => true), false );
				
				if( is_wp_error($user) ){
					echo wp_send_json_error(array('message'=> '<strong>ERROR: </strong>The username and/or password is invalid. Please try again.','name'=>$user));
				} else{
					echo wp_send_json_success(array('message'=> __('Success!', 'wordpress-seo'),'name' => $user->user_firstname));
				}
				
			} else {
				if (empty($usernm)) :
					$themsg = 'Username is empty';
				else :
					$themsg = 'The username is invalid';
				endif;
				echo wp_send_json_error(array('message'=> __($themsg, 'wordpress-seo'),'name'=>$usernm));
				
			}
		
	}
	
	##################################
	##### SUBSCRIBE FORM HANDLER #####
	##################################
	
	function sttv_subscribe_send($fname,$lname,$email) {
		$content = array(
			'email_address' => $email,
			'status' => 'subscribed',
			'status_if_new' => 'subscribed',
			'merge_fields' => array(
				'FNAME' => $fname,
				'LNAME' => $lname
			)
		);
		$hashmail = md5(strtolower($email));
		
		$list_id = 'df497b5cbd';
		$api_key = '476c5a967fe76a385906b1537f6fada4-us7';
		$api_path = 'https://us7.api.mailchimp.com/3.0/lists/'.$list_id.'/members/'.$hashmail;
		
		
		$opts = array(
		  'http'=>array(
			'method'=>"POST",
			'header'=>"Authorization: apikey ".$api_key."\r\n"
					."Content-Type: application/json \r\n"
					."X-HTTP-Method-Override: PUT \r\n"
					."User Agent: SupertutorTV onpage ajax (".$_SERVER['SERVER_SOFTWARE'].")",
			'content' => json_encode($content)
		  )
		);
		
		$context = stream_context_create($opts);
		
		$subscribe = file_get_contents($api_path,false,$context);
		
		if ($subscribe) :
			echo wp_send_json_success(array('message'=> __('Success! Thank you for subscribing to SupertutorTV!', 'wordpress-seo'), 'subscribed' => true, 'sdata' => $subscribe));
		else :
			echo wp_send_json_error(array('message'=> __('Something went wrong. Please try again later.', 'wordpress-seo'), 'subscribed' => false, 'sdata' => $subscribe));
		endif;
	}
	
	#######################
	##### AJAX LOGOUT #####
	#######################
	
	function sttv_ajax_logout(){
		wp_clear_auth_cookie();
		wp_logout();
		ob_clean();
		wp_send_json_success();
	}
	
###########################################
##### GRAVITY FORMS API KEY REQUESTER #####
###########################################

function sttv_request_gf_api_key() {
	echo wp_send_json(array('public'=>'ck_34df50caf5ed60aaccd6d6840043cb0ddc2c3e83','secret'=>'cs_56768e11bcca7c65f7d4fb70c0cb6ed2ab660252'));
}

###########################
##### ACT SIGNUP FORM #####
###########################

function sttv_signup_form($form = 'act',$form_id = 3) { ?>
	<form name="sttv-<?php print $form; ?>-signup" action="/">
    	<div class="card">
        	<div class="card-tabs">
                <ul class="tabs tabs-fixed-width">
                    <li class="tab"><a class="active" href="#page1">Step 1: Info</a></li>
                    <li class="tab"><a href="#page2">Step 2: Payment</a></li>
                </ul>
            </div>
            <div class="card-content grey lighten-4">
            <div class="row">
                <div id="page1" class="col s12">
                    <input class="input_10" name="input_10" type="radio" value="The Best ACT Prep Course Ever|199.99" checked="checked" id="choice_3_10_0" tabindex="1">
                    <label for="choice_3_10_0" id="label_3_10_0">The Best ACT Prep Course Ever</label>
                        <br/>
                    <input class="input_10" name="input_10" type="radio" value="ACT Math &amp; Free Explanations|49.99" id="choice_3_10_1" tabindex="2">
                    <label for="choice_3_10_1" id="label_3_10_1">ACT Math &amp; Free Explanations</label>
                        <br/>
                    <input class="input_10" name="input_10" type="radio" value="Free ACT Explanations|0" id="choice_3_10_2" tabindex="3">
                    <label for="choice_3_10_2" id="label_3_10_2">Free ACT Explanations</label>
                </div>
            </div>
            <div class="row">
                <div id="page2" class="col s12">
                	<input type="text" placeholder="credit card number" />
                </div>
            </div>
            </div>
            <button type="submit" class="signup-submit" >Sign up now!</button>
        </div>
        <input type="hidden" id="form_id" value="<?php print $form_id; ?>" />
    </form>
    <script>
		var killit = function() {
			
			var thedata = {
				action : 'gfkey'
			}
			
			$.get(
				sttvAjax.ajaxURL,
				thedata,
				function (response) {
					console.log(response);
				}
			);
		};
	</script>
<?php }