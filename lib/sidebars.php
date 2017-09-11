<?php 

add_action('widgets_init','sttv_widgets_init');

function sttv_widgets_init() {
	register_sidebar( array(
        'name'          => __( 'Main Navigation','wordpress-seo' ),
        'id'            => 'main-navigation',
        'description'   => __( 'Navigation Widget Area','wordpress-seo' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	register_sidebar( array(
        'name'          => __( 'Account Sidebar','wordpress-seo' ),
        'id'            => 'account',
        'description'   => __( 'Account Sidebar widget area','wordpress-seo' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
	register_sidebar( array(
        'name'          => __( 'Blog Sidebar','wordpress-seo' ),
        'id'            => 'blog-sidebar',
        'description'   => __( 'Sidebar widget area for the blog','wordpress-seo' ),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
}