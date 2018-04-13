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

function sttv_get_template($temp,$dir='',$sgt=null) {
	$dir = (!empty($dir))?"{$dir}/":"";
	$path = STTV_TEMPLATE_DIR.$dir.$temp;
	$extension = file_exists($path.'.php') ? '.php': '.html';
	
	require $path.$extension;
}

function sttv_array_map_recursive($callback, $array) {
	$func = function ($item) use (&$func, &$callback) {
		return is_array($item) ? array_map($func, $item) : call_user_func($callback, $item);
	};
  return array_map($func, $array);
}

function sttvhashit($input,$num = 9) {
	return base64_encode(substr(md5($input),0,$num));
}

function sttv_ukey ( $prefix = '', $random = '', $entropy = false, $length = 0 ){
	$string = trim( $prefix . preg_replace('/[^A-Za-z0-9\-]/', '', base64_encode( uniqid( $random, $entropy ) ) ), '=');
	return substr( $string, 0, ($length ?: strlen($string)) );
}

function sttv_uid( $length = 7 ) {
	$str = '';
	for ($i = 0; $i < $length; $i++) {
		$str .= base_convert(mt_rand(0,15),10,16);
	}
	return strtoupper($str);
}

function sttv_verify_rest_nonce( WP_REST_Request $request ) {
	return wp_verify_nonce( $request->get_header('X-WP-Nonce'), STTV_REST_AUTH );
}

function sttv_404_redirect() {
	global $wp_query;
	$wp_query->set_404();
	status_header( 404 );
	get_template_part( 404 );
}
// end of line, man.