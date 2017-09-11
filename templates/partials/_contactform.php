<?php

global $current_user;
wp_get_current_user();
	  
?>
<div class="row">
<div class="loading_overlay"></div>
    <form id="sttv_contact" class="col s12" action="/" method="post">
    <p class="message"></p>
      <div class="row">
        <div class="input-field col l6 m12 s12">
          <i class="material-icons prefix">account_circle</i>
          <input name="sttv_contact_name" id="sttv_contact_name" minlength="2" value="<?php echo $current_user->display_name; ?>" type="text" class="validate" required>
          <label for="sttv_contact_name" data-error="Must be at least 2 letters" data-success="Good job!">Name (required)</label>
        </div>
        <div class="input-field col l6 m12 s12">
          <i class="material-icons prefix">email</i>
          <input name="sttv_contact_email" id="sttv_contact_email" type="email" class="validate" value="<?php echo $current_user->user_email; ?>" required>
          <label for="sttv_contact_email" data-error="Not a valid email address" data-success="Valid email address">Your Email (required)</label>
        </div>
        <div class="input-field col s12">
          <i class="material-icons prefix">label</i>
          <input name="sttv_contact_subject" id="sttv_contact_subject" type="text" class="validate" required>
          <label for="sttv_contact_subject">Subject (required)</label>
        </div>
        <div class="input-field col s12">
        	<i class="material-icons prefix">message</i>
        	<textarea name="sttv_contact_message" id="sttv_contact_message" class="materialize-textarea"></textarea>
        	<label for="sttv_contact_message">Message</label>
        </div>
        <div class="input-field col s12">
        	<div id="contact_recap" class="g-recaptcha"></div>
        </div>
        <div class="input-field col s12">
        	<button type="submit" class="z-depth-1 submitter" id="contact_submit">Send Message</button>
        </div>
        <input type="hidden" name="whichform" value="contact" />
      </div>
    </form>
  </div>