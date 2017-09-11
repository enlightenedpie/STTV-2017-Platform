<?php 
	if (!is_user_logged_in()) : ?>
	<div id="account-block">
		<div class="sttvmodal"></div>
	<?php
		print sttv_login_form();?>
	</div>
	<script>
		( function ( $ ) { //begin wrapper
			"use strict";

		$('#login').click(function(e) {
			e.preventDefault();
			$('body').addClass('login-sidebar-open');
		});

		} ( jQuery ) ); //end wrapper
	</script>
<?php endif; ?>
<?php //do_shortcode('[mepr-account-form]') ?>