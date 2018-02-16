<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'rest_api_init' , 'register_course_meta_endpoint' );
function register_course_meta_endpoint() {
	register_rest_route( STTV_REST_NAMESPACE , '/course_log', [
			'methods' => 'POST',
			'callback' => 'course_access_log',
			'permission_callback' => 'is_user_logged_in'
		]
	);

	register_rest_route( STTV_REST_NAMESPACE , '/course_data/(?P<id>[\d]+)', [
		[
			'methods' => WP_REST_Server::READABLE,
			'callback' => 'get_course_meta',
			'permission_callback' => 'course_permissions_check',
			'args' => [
				'alert' => [
					'required' => false
				]
			]
		]
	]);

	register_rest_route( STTV_REST_NAMESPACE , '/course_dl', [
		[
			'methods' => WP_REST_Server::READABLE,
			'callback' => 'get_course_download',
			'permission_callback' => 'course_permissions_check',
			'args' => [
				'res' => [
					'required' => true
				],
				'section' => [
					'required' => true
				],
				'test' => [
					'required' => true
				],
				'checksum' => [
					'required' => true
				]
			]
		]
	]);
}

function course_access_log( WP_REST_Request $request ) {
	$data = [
		date('c',time()),
		$_SERVER['REMOTE_ADDR']
	];
	$data = array_merge($data, json_decode($request->get_body(), true));
	$data = implode(' | ',$data);
	return file_put_contents(STTV_LOGS_DIR.get_current_user_id().'.log',PHP_EOL.$data.PHP_EOL,FILE_APPEND);
}

function get_course_alert_template($data) {
	$the_post = get_post($data['id']);
	ob_start();
	require(dirname(__DIR__).'/templates/courses/update_alert.php');
	return [ 'html'=>ob_get_clean(),'hashid'=>sttvhashit($the_post->post_title.'/'.STTV_VERSION.'/'.$the_post->ID) ];
}

function course_permissions_check($data) {
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

function get_course_meta($data) {
	if ( isset($data['alert']) ){
		return get_course_alert_template($data);
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
			'color' => $val['color'],
			//'videos' => $val['videos']
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
		$data['practice']['tests'][$s] = [
			'name'=> $v['name'],
			'color'=>'rgba(0,0,0,0.60)',
			'subsec'=> $v['sections']
		];
		
		//if (current_user_can($v['cap'])) {
		//$data['practice']['tests'][$s]['subsec'] = ;
		//} else {
		//	$data['practice'][$s]['restricted'] = 'Restricted access. This practice section will be available when you purchase the full course, or your trial period ends.';
		//}
	}
	
	$data['size'] = (mb_strlen(json_encode($data), '8bit')/1000).'KB';
	
	return $data;

}

function get_course_download( WP_REST_Request $req ){
	$params = array_map( 'trim',$req->get_params() );
	
	$root_path = STTV_RESOURCE_DIR.strtolower( $params['test'] ).'/'.$params['section'].'/';
	$response = new WP_REST_Response;

	if ( is_file($root_path.$params['res']) && $params['checksum'] == md5_file($root_path.$params['res']) ) {
		$headers = [
			'Pragma'=>'public',
			'Expires'=>0,
			'Cache-Control'=>'must-revalidate, post-check=0, pre-check=0',
			'Content-Description'=>'File Transfer',
			'Content-type'=>'application/octet-stream',
			'Content-disposition'=>'attachment;filename="'.$params['res'].'"',
			'Content-Length'=>filesize($root_path.$params['res']),
			'Content-Transfer-Encoding'=>'binary'
		];
		$response->set_headers( $headers );
		readfile($root_path.$params['res']);
	} else {
		$response->set_status( 404 );
	}
	
	return $response;
}

include_once 'courses/sttv_course.class.php';
include_once 'courses/sttv_courses_admin.class.php';