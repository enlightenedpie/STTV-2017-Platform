<?php 
	if (!is_user_logged_in()) : ?>
	<div id="account-block">
		<div class="sttvmodal">
			<div class="closer-wrapper">
				<i class="material-icons login-closer close">clear</i>
			</div>
			<div class="sttvmodal_inner">
				<?php print sttv_login_form(); ?>
			</div>
		</div>
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