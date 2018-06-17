<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * SupertutorTV Courses class.
 *
 * Controller class for the STTV course endpoints.
 *
 * @class 		STTV_Courses
 * @version		1.4.0
 * @package		STTV
 * @category	Class
 * @author		Supertutor Media, inc.
 */

class STTV_Courses {

	public function __construct() {
		add_action( 'rest_api_init' , [ $this, 'register_course_meta_endpoint' ] );
		//add_filter( 'rest_pre_serve_request', '__return_true', 1 );
	}

	public function register_course_meta_endpoint() {
		register_rest_route( STTV_REST_NAMESPACE , '/course_log', [
				'methods' => 'POST',
				'callback' => [ $this, 'course_access_log' ],
				'permission_callback' => 'is_user_logged_in'
			]
		);

		register_rest_route( STTV_REST_NAMESPACE , '/course_data/(?P<id>[\d]+)', [
			[
				'methods' => WP_REST_Server::READABLE,
				'callback' => [ $this, 'get_course_meta' ],
				'permission_callback' => [ $this, 'course_permissions_check' ],
				'args' => [
					'alert' => [
						'required' => false
					]
				]
			]
		]);
	}

	public function course_access_log( WP_REST_Request $request ) {
		$data = [
			date('c',time()),
			$_SERVER['REMOTE_ADDR']
		];
		$data = array_merge($data, json_decode($request->get_body(), true));
		$data = implode(' | ',$data);
		return file_put_contents(STTV_LOGS_DIR.get_current_user_id().'.log',PHP_EOL.$data.PHP_EOL,FILE_APPEND);
	}

	private function get_course_alert_template($data) {
		$the_post = get_post($data['id']);
		ob_start();
		require(dirname(__DIR__).'/templates/courses/update_alert.php');
		return [ 'html'=>ob_get_clean(),'hashid'=>sttvhashit($the_post->post_title.'/'.STTV_VERSION.'/'.$the_post->ID) ];
	}

	public function course_permissions_check($data) {
		$thepost = get_post($data['id']);
			$cap = str_replace(' ','_',strtolower($thepost->post_title));

		$legacy = ['_legacy_value_bundle','_legacy_official_guide','_legacy_red_book'];

		foreach($legacy as $l) {
			if (current_user_can($l)){
				return current_user_can( $l );
			}
		}
		return current_user_can( $cap );
	}

	public function get_course_meta($data) {
		if ( isset($data['alert']) ){
			return $this->get_course_alert_template($data);
		}
		
		$meta = get_post_meta( $data['id'], 'sttv_course_data' , true );
		
		$data = [
			'id'=>$meta['id'],
			'name'=>$meta['name'],
			'slug'=>$meta['slug'],
			'link'=>$meta['link'],
			'test'=>strtolower($meta['test']),
			'intro'=>$meta['intro'],
			'version'=>STTV_VERSION
		];
		
		if (current_user_can($meta['cap'])) {
			$data['tl_content'] = $meta['tl_content'];
		}
		
		
		foreach ($meta['sections'] as $sec => $val) {
			$data['sections'][$sec] = [
				'name' => $val['name'],
				'description' => $val['description'],
				'intro' => $val['intro'],
				'color' => $val['color']
			];
			
			if (current_user_can($val['cap'])) {
				$data['sections'][$sec]['resources'] = $val['resources'];
				$data['sections'][$sec]['subsec'] = $val['subsec'];
			} else {
				$data['sections'][$sec]['restricted'] = 'Restricted access. This section will be available when you purchase the full course, or your trial period ends.';
			}
		}
		$data['practice'] = [
			'description' => $meta['practice']['description'],
			'resources' => $meta['practice']['resources'],
			'tests' => []
		];
		foreach ($meta['practice']['tests'] as $s => $v) {
			$tsecs = [];
			foreach ( $v['sections'] as $nm => $ob ) {
				if ( ( $s == 'the-official-act-prep-guide' && strpos( $nm, 'test-4' ) ) && !current_user_can( $ob['cap'] ) ) {
					continue;
				}
				unset( $ob['cap'] );
				$tsecs[$nm] = $ob;
			}
			$data['practice']['tests'][$s] = [
				'name'=> $v['name'],
				'color'=>'rgba(0,0,0,0.60)',
				'subsec'=> $tsecs
			];
		}
		
		$data['size'] = (mb_strlen(json_encode($data), '8bit')/1000).'KB';
		
		return $data;

	}
}
new STTV_Courses;

include_once 'courses/sttv_course.class.php';
include_once 'courses/sttv_courses_admin.class.php';