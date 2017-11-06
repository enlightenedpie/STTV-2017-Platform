<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * SupertutorTV test dates API.
 *
 * REST endpoints for adding, displaying, and updating product reviews.
 *
 * @class 		STTV_Test_Dates
 * @version		1.1.0
 * @package		STTV
 * @category	Class
 * @author		Supertutor Media, inc.
 */

class STTV_Test_Dates extends WP_REST_Controller {
	
	private $allowed_tests = array(
		'act',
		'sat',
		'satsubject',
		'satii',
		'psat',
		'gre',
		'isee',
	);

	private $tests_table = STTV_PREFIX.'_tests_data';
	
	public function __construct() {
		global $wpdb;
		if($wpdb->get_var("SHOW TABLES LIKE '$this->tests_table'") != $this->tests_table) {
			$this->create_tests_table($wpdb);
		}

		add_action( 'rest_api_init', array($this,'sttv_test_dates_api') );
		
		register_shutdown_function( array( $this, '__destruct' ) );
	}
	
	public function __destruct() {
        return true;
    }
	
	public function sttv_test_dates_api() {
		register_rest_route( STTV_REST_NAMESPACE, '/all_test_dates/',array(
				array(
					'methods' => 'POST',
					'callback' => array($this,'update_test_dates')
				)
			)
		);
		register_rest_route( STTV_REST_NAMESPACE, '/test_dates/(?P<test>[a-zA-Z]+)',array(
				array(
					'methods' => 'GET',
					'callback' => array($this,'get_test_dates'),
					'args' => array(
						'test' => array(
							'validate_callback' => array($this,'is_allowed_test'),
							'required' => true
						)
					)
				),
				array()
			)
	   );
	}

	public function is_allowed_test($val) {
		return ($val === 'all')?:in_array($val, $this->allowed_tests);
	}

	public function get_test_dates($data) {
		global $wpdb;
		$test = esc_sql($data['test']);
		$time = time();
		//return $wpdb->get_results( "SELECT * FROM $this->tests_table WHERE test = '$test' AND test_date > $time",OBJECT);
		return rest_get_server();
	}

	public function update_test_dates(WP_REST_Request $request) {
		return $request->get_param('auth');
	}

	public function check_auth(WP_REST_Request $request, $action) {
		return wp_verify_nonce($request->get_param('auth'),$action) && current_user_can('edit_test_dates_api');
	}

	private function create_tests_table($wpdb){
		$charset_collate = $wpdb->get_charset_collate();
		
			$sql = "CREATE TABLE $this->tests_table (
				 id int(10) NOT NULL AUTO_INCREMENT,
				 test tinytext,
				 test_date int(10) UNSIGNED,
				 reg_date int(10) UNSIGNED,
				 change_date int(10) UNSIGNED,
				 late_reg text,
				 reg_link varchar(255),
				 description text,
				 UNIQUE KEY id (id)
			) $charset_collate;";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
	}
}
new STTV_Test_Dates;

/**
 * SupertutorTV test dates API admin area.
 *
 * Defines the Wordpress admin area and front-end editor for the STTV Test Dates API.
 *
 * @class 		STTV_Test_Dates_Admin
 * @version		1.1.0
 * @package		STTV
 * @category	Class
 * @author		Supertutor Media, inc.
 */

 class STTV_Test_Dates_Admin {

	private $test_dates_cap = 'edit_test_dates_api';

	public function __construct() {
		add_action( 'admin_menu' , array($this,'test_dates_menu_page') );
		add_action( 'admin_menu' , array($this,'define_test_dates_capabilities') );
	}

	public function define_test_dates_capabilities() {
		// add new role specific to authorized API authors
		add_role(
			'test_dates_editor',
			'Test Dates Editor',
			[
				'read'         => true,
				$this->test_dates_cap   => true
			]
		);
		
		// add to admin role
		$admin = get_role('administrator');
		if (!in_array($this->test_dates_cap,$admin->capabilities)) {
			$admin->add_cap($this->test_dates_cap, true);
		}
	}

	public function test_dates_menu_page() {
		add_submenu_page(
			'edit.php?post_type=courses',
			'Test Dates',
			'Test Dates',
			'manage_options',
			STTV_PREFIX.'_test_dates', 
			array($this, 'test_dates_admin')
		);
	}

	public function test_dates_admin() {
		print 'I did naht hit herrr, it\'s nahttrueit\'sbullshit I did nahhhhhhht. Oh hai Mark.';
	}

 }
 new STTV_Test_Dates_Admin;