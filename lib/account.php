<?php

############################################
##### ADD 'MY COURSES' TO ACCOUNT PAGE #####
############################################

class STTV_Add_Acct_Content {
	/**
	 * Custom endpoint name.
	 *
	 * @var string
	 */
	public static $endpoint = 'courses';
	
	public function __construct() {
		// Actions used to insert a new endpoint in the WordPress.
		add_action( 'init', array( $this, 'add_endpoints' ) );
		add_filter( 'query_vars', array( $this, 'add_query_vars' ), 0 );
		
		// Change the page title
		add_filter( 'the_title', array( $this, 'sttv_endpoint_title' ) );
		
		// Insert the new tab/page into the My Account page.
		add_filter( 'woocommerce_account_menu_items', array( $this, 'sttv_account_menu_items' ) );
		add_action( 'woocommerce_account_' . self::$endpoint .  '_endpoint', array( $this, 'sttv_courses_endpoint_content' ) );
	}
	
	
	/**
	 * Register new endpoint to use inside My Account page.
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_rewrite_endpoint/
	 */
	public function add_endpoints() {
		add_rewrite_endpoint( self::$endpoint, EP_ROOT | EP_PAGES );
	}
	
	
	/**
	 * Add new query var.
	 *
	 * @param array $vars
	 * @return array
	 */
	public function add_query_vars( $vars ) {
		$vars[] = self::$endpoint;
		return $vars;
	}
	
	
	/**
	 * Set endpoint title.
	 *
	 * @param string $title
	 * @return string
	 */
	public function sttv_endpoint_title($title) {
		return wc_page_endpoint_title($title);
	}
	
	public function sttv_account_menu_items( $items ) {
		$items = array(
		'dashboard'       => __( 'Dashboard', 'woocommerce' ),
		self::$endpoint	  => __( 'My Courses', 'woocommerce' ),
		'orders'          => __( 'Orders', 'woocommerce' ),
		'downloads'       => __( 'Downloads', 'woocommerce' ),
		'edit-address'    => __( 'Addresses', 'woocommerce' ),
		'payment-methods' => __( 'Payment methods', 'woocommerce' ),
		'edit-account'    => __( 'Account details', 'woocommerce' ),
		'logout' 		  => __( 'Logout', 'woocommerce' ),
	);
		return $items;
	}

	
	public function sttv_courses_endpoint_content() {
		wc_get_template( 'myaccount/courses.php' );
	}
	
}
//new STTV_Add_Acct_Content;


################################
##### DISPLAY USER COURSES #####
################################

function sttv_get_user_courses() {
	$status_query = array( 'user_id' => get_current_user_id(), 'type' => 'sensei_course_status' );
	$user_courses_logs = Sensei_Utils::sensei_check_for_activity( $status_query , true );
		if ( !is_array($user_courses_logs) ) {
			 $user_courses_logs = array( $user_courses_logs );
		}
		$shmost = array();
		foreach ($user_courses_logs as $course) {
			$shmost[] = $course->comment_post_ID;
		}
		
		$shmost = array_unique($shmost,SORT_REGULAR);
		
		$html = '<section class="my-account-courses"><h4>'.__('My Courses','woocommerce').'</h4>';
		
			foreach ($shmost as $post) {
				$pp = get_post($post);
				
				$html .= '<article class="course-'.$pp->ID.' my-course">';
					$html .= '<h5>'.$pp->post_title.'</h5>';
				$html .= '</article>';
			}
	
		$html .= '</section>';
		print ($html);
}
add_action('woocommerce_account_dashboard','sttv_get_user_courses');

function sttv_subs_in_dash() {
	get_template_part('woocommerce/myaccount/my-subscriptions');
}
add_action('woocommerce_account_dashboard','sttv_subs_in_dash');