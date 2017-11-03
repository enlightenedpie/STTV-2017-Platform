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