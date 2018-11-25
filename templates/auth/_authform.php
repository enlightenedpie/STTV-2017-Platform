<div id="form-wrapper">
	<div class="loading_overlay"></div>
	<div id="form-identity">
		<img src="<?php print get_header_image(); ?>" alt="Login form header" />
	</div>
	<form id="sttv_login_form" action="/" method="post">
		<p class="message"></p>
		<div class="row" style="color:white;background-color:red;padding:1em">
			<span>ATTENTION: If you purchased one of our courses after November 19th, 2018, DO NOT sign in below. Click <a href="https://courses.supertutortv.com/login">here</a> for the proper signin page.</span>
		</div>
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
		<button type="submit" class="z-depth-1" id="login-btn">Login</button>
		<input type="hidden" name="whichform" value="login" />
	</form>
    <div id="forgot-block"><a class="lostpwd" href="<?php print wp_lostpassword_url(); ?>">Forgot your password?</a></div>
</div>