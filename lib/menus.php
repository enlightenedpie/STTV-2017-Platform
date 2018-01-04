<?php

add_action( 'init', 'sttv_add_menus');

function sttv_add_menus() {
	
	register_nav_menus( array(
		'sttv-nav' => __('SupertutorTV Main Nav Menu'),
		'sttv-acct' => __('SupertutorTV Account Menu'),
		'footer-menu' => __('SupertutorTV Footer Menu')
	) );

}

###################################################
##### ADD ADMIN-ONLY BACKEND LINK TO NAV MENU #####
###################################################

add_filter( 'wp_get_nav_menu_items', 'sttv_admin_only_link', 20, 2 );

function sttv_admin_only_link( $items, $menu ) {
	global $post;
	if ( $menu->slug == 'main-nav' ) {
		if (current_user_can('read')) :
			$items[] = _custom_nav_menu_item( 'The Best ACT Prep Course Ever', get_site_url().'/courses/the-best-act-prep-course-ever', 1, array('menu-item-100') );
		endif;
		
		if ( current_user_can( 'edit_others_posts' ) ) :
	  		$items[] = @_custom_nav_menu_item( 'Admin', get_admin_url(), 111, array('admin-link') );
	  		$items[] = @_custom_nav_menu_item( 'Edit '.get_post_type(), get_edit_post_link($post->ID), 120);
		endif;
	}
	return $items;
}
function _custom_nav_menu_item( $title = '', $url = '', $order = 0, $classes = array() ){
  $item = new stdClass();
  $item->ID = 1000000 + $order;
  $item->title = $title;
  $item->url = $url;
  $item->menu_order = $order;
  $item->menu_item_parent = 0;
  $item->target = '';
	$item->attr_title = '';
	$item->description = '';
	$item->xfn = '';
	$item->status = '';
  $item->type = '';
  $item->object = '';
  $item->object_id = '';
  $item->db_id = 0;
  $item->classes = $classes;
  
  return $item;
}