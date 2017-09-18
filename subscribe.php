<?php

/* Template Name: Subscribe Page */

$current_user = wp_get_current_user();

get_header(); ?>
<?php get_template_part('templates/title'); ?>
<section id="content-wrapper-full">
<div id="subscribe_page_form" class="col s12 m6 push-m3 z-depth-5">
<h4>Join our mailing list to get exclusive news, updates, coupons, and promotions from SupertutorTV!</h4>
	<form id="subscribe_page_mc" class="col s12" action="/" method="post" style="position:relative">
		<div class="loading_overlay"></div>
		<div class="row">
			<div class="input-field '.$context.' m12 s12">
			  <input name="sttv_mc_fname" id="sttv_mc_fname" minlength="2" value="<?php echo $current_user->user_firstname; ?>" type="text" class="validate" required>
			  <label for="sttv_mc_fname" data-error="Please enter your first name">First Name (required)</label>
			</div>
			<div class="input-field '.$context.' m12 s12">
			  <input name="sttv_mc_lname" id="sttv_mc_lname" minlength="2" value="<?php echo $current_user->user_lastname; ?>" type="text" class="validate" required>
			  <label for="sttv_mc_lname" data-error="Please enter your last name">Last Name (required)</label>
			</div>
			<div class="input-field s12">
			  <input name="sttv_mc_email" id="sttv_mc_email" value="<?php echo $current_user->user_email; ?>" type="email" class="validate" required>
			  <label for="sttv_mc_email" data-error="Please enter a valid email address">Email Address (required)</label>
			</div>
			<div class="input-field s12">
				<div id="subscribe_form" class="g-recaptcha"></div>
			</div>
			<p class="message"></p>
			<button class="g-recaptcha z-depth-2 submitter" data-callback="response" data-sitekey="6LdjuA0UAAAAAMBQ0XAQoewK6248ezq5FZVm4T86">Submit</button>
			<input type="hidden" name="whichform" value="subscribe" />
		</div>
	</form>
</div>
<script>
	var subscribe = {
		push : function() {
			$.post(stajax.ajaxURL,{action: 'sttvsubmitter',value: $('#subscribe_page_mc').serialize()})
				.done(function(key){
					subscribe.success(key);
				})
				.fail(function(){
					console.log('failed')
				})
		},
		success : function(k) {
			console.log(k);
		},
		fail : function() {
			
		}
	}
	
	$('#subscribe_page_mc').submit(function(e){
		e.preventDefault();
		alert('You\'re doing it wrong');
		return;
	});
</script>
<?php the_content(); ?>
</section>
<?php get_footer(); ?>