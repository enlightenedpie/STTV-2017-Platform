<?php

/* Template Name: Subscribe Page */

$current_user = wp_get_current_user();

get_header(); ?>
<section id="content-wrapper">
<div id="subscribe_page_form" class="col m12 l8 xl6 push-l2 push-xl3 z-depth-1">
<h4>Join our mailing list to get exclusive news, updates, coupons, and promotions from SupertutorTV!</h4>
	<form id="subscribe_page_mc" class="col s12" action="/" method="post">
		<div class="loading_overlay"></div>
		<div class="row">
			<div class="input-field col s12">
			  <input name="sttv_mc_fname" id="sttv_mc_fname" minlength="2" value="<?php echo $current_user->user_firstname; ?>" type="text" class="validate" required>
			  <label for="sttv_mc_fname" data-error="Please enter your first name">First Name (required)</label>
			</div>
			<div class="input-field col s12">
			  <input name="sttv_mc_lname" id="sttv_mc_lname" minlength="2" value="<?php echo $current_user->user_lastname; ?>" type="text" class="validate" required>
			  <label for="sttv_mc_lname" data-error="Please enter your last name">Last Name (required)</label>
			</div>
			<div class="input-field col s12">
			  <input name="sttv_mc_email" id="sttv_mc_email" value="<?php echo $current_user->user_email; ?>" type="email" class="validate" required>
			  <label for="sttv_mc_email" data-error="Please enter a valid email address">Email Address (required)</label>
			</div>
			<div class="input-field col s12">
				<div id="sttv_recap" class="g-recaptcha"></div>
			</div>
		</div>
		<div class="row">
			<p class="message"></p>
		</div>
		<div class="row">
			<button id="subscribe_submit" class="z-depth-1 submitter" type="submit" disabled>Subscribe!</button>
		</div>
	</form>
</div>
<div class="col s12">
<?php the_content(); ?>
</div>
</section>
<?php get_footer(); ?>