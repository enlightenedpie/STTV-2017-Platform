<?php

/* Template Name: Subscribe Page */

$current_user = wp_get_current_user();

get_header(); ?>
<?php get_template_part('templates/title'); ?>
<section id="content-wrapper-full">
<div id="subscribe_page_form" class="col s12 m6 push-m3 z-depth-1">
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
				<div id="sttv_recap" class="g-recaptcha"></div>
			</div>
			<p class="message"></p>
			<button class="g-recaptcha z-depth-1 submitter" data-callback="response" data-sitekey="6LdjuA0UAAAAAMBQ0XAQoewK6248ezq5FZVm4T86" disabled>Submit</button>
		</div>
	</form>
</div>
<script>
	_st.subscribe = function(){
		this.fields : {
			fname : '',
			lname : '',
			email : '',
			g-recaptcha-response : ''
		}
		this.validate : function(cb){
			var form = $('#subscribe_page_mc')
			typeof cb === 'function' && cb()
		},
		this.push : function() {
			_st.request({
				route : stajax.rest.url+'/subscribe',
				method : 'POST',
				cdata : this.fields,
				headers : {'X-WP-Nonce' : stajax.rest.nonce},
				success : function(d){
					console.log(d)
				},
				error : function(x){
					console.log(x)
				}
			})
		}

		this.validate(this.push())
	}
	
	$('#subscribe_page_mc').on('submit',function(e){
		e.preventDefault();
		var sss = new _st.subscribe()
	});
</script>
<div class="col s12">
<?php the_content(); ?>
</div>
</section>
<?php get_footer(); ?>