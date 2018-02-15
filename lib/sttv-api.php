<?php

##############################
##### STTV API FUNCTIONS #####
##############################

/**
 * Pulls ZIPS from gist file, returns array of ZIP codes
 *
**/
function sttv_ca_zips() {
	return file('https://gist.githubusercontent.com/enlightenedpie/99139b054dd9e4ad3f81689e2326d198/raw/19abb68964368c29060fbaae5467dd6ccf057c60/gistfile1.txt',FILE_IGNORE_NEW_LINES);
}

/**
 * Checks if specified page (by slug or ID) is a child page, returns bool
 *
**/
function is_child($page = '') {
	global $post;
	if ($post)
	
	if (is_numeric($page)) :
		
		return (is_page() && ($post->post_parent==$page));
		
	else :
		
		$page = !empty($page)?$page:$post->post_name;
		$post_parent = get_post($post->post_parent);
		return ($post_parent->post_name == $page);
	
	endif;
}

function sttv_login_form() { ?>
	<div id="form-wrapper">
	<div class="loading_overlay"></div>
	<div id="form-identity">
		<img src="<?php print get_header_image(); ?>" alt="Login form header" />
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
		<button type="submit" class="z-depth-1" id="login-btn">Login</button>
		<input type="hidden" name="whichform" value="login" />
	</form>
	<div id="forgot-block"><a class="lostpwd" href="<?php print wp_lostpassword_url(); ?>">Forgot your password?</a></div>
	</div><?php 
}

function sttv_name_generate() {
	//delete_transient('_courses_dynamic_js');
	$transient = get_transient('_courses_dynamic_js');
	
	if ( $transient ) :
		
		$filename = $transient;
		
	else :
		$filepath = get_stylesheet_directory().'/s/courses/';
	
		$filename = ''.md5(base64_encode(openssl_random_pseudo_bytes(4))).'.'.dechex(time()).'.js';
		
		$files = glob($filepath.'*.js');
		foreach($files as $file){
			if(is_file($file))
				unlink($file);
		}
		set_transient('_courses_dynamic_js',$filename,MINUTE_IN_SECONDS);
	
	$newfile = file_put_contents($filepath.$filename,$js,LOCK_EX);

		ob_end_clean();
	
	endif;
	
	// enqueue the script after we've done all our logic
	wp_enqueue_script( 'sttv-course-object', get_stylesheet_directory_uri().'/s/courses/'.$filename, 'jquery', null, false);
}

function sttv_get_template($temp,$dir='',$object=null) {
	$dir = (!empty($dir))?"{$dir}/":"";
	$path = get_template_directory().'/templates/'.$dir.$temp;
	$extension = file_exists($path.'.php') ? '.php': '.html';
	
	return (include $path.$extension);
}

function sttvhashit($input,$num = 9) {
	return base64_encode(substr(md5($input),0,$num));
}

add_action('stripepress_events_invalid','sttv_404_redirect');
function sttv_404_redirect() {
	global $wp_query;
	$wp_query->set_404();
	status_header( 404 );
	get_template_part( 404 );
}
// end of line, man.