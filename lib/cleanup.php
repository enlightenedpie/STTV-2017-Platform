<?php

#############################
##### FILTER AUTHOR URL #####
#############################

add_filter( 'author_link', 'modify_author_link', 10, 1 ); 	 	 
function modify_author_link( $link ) {	 	 
    $link = site_url('about');
return $link;	 	  	 	 
}

#########################################
##### REMOVE UNNESECCARY GENERATORS #####
#########################################

remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wc_generator_tag');
add_filter('ls_meta_generator','__return_false');


#######################
##### IMAGE SIZES #####
#######################

add_action('get_header', 'my_filter_head');
function my_filter_head() {
	remove_action('wp_head', '_admin_bar_bump_cb');
}

###########################################
##### REMOVE ADMIN BAR FROM FRONT END #####
###########################################

add_filter('show_admin_bar', '__return_false');

// end of line, man.