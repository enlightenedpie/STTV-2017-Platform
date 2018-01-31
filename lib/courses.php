<?php

add_action( 'rest_api_init' , 'register_course_meta_endpoint' );
function register_course_meta_endpoint() {
	register_rest_route( STTV_REST_NAMESPACE , '/course_data/(?P<id>[\d]+)/alert', [
			'methods' => WP_REST_Server::READABLE,
			'callback' => 'get_course_alert_template',
			'permission_callback' => 'course_permissions_check',
			'args' => [
				'id' => [
					'validate_callback' => 'absint'
				]
			]
		]
	);

	register_rest_route( STTV_REST_NAMESPACE , '/course_data/(?P<id>[\d]+)', [
		'methods' => WP_REST_Server::READABLE,
		'callback' => 'get_course_meta',
		'permission_callback' => 'course_permissions_check',
		'args' => [
			'id' => [
				'validate_callback' => 'absint'
			]
		]
	]);
}

function get_course_alert_template($data) {
	$the_post = get_post($data['id']);
	ob_start();
	require(dirname(__DIR__).'/templates/courses/update_alert.php');
	return ['html'=>ob_get_clean(),'hashid'=>sttvhashit($the_post->post_title.'/'.STTV_VERSION.'/'.$the_post->ID)];
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
			'videos' => $val['videos']
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
	foreach ($meta['practice']['tests'] as $sec => $val) {
		$data['practice']['tests'][$sec] = [
			'name'=> $val['name'],
			'color'=>'rgba(0,0,0,0.60)'
		];
		
		if (current_user_can($val['cap'])) {
				$data['practice']['tests'][$sec]['subsec'] = $val['sections'];
		} else {
			$data['practice'][$sec]['restricted'] = 'Restricted access. This practice section will be available when you purchase the full course, or your trial period ends.';
		}
	}
	
	$data['size'] = (mb_strlen(json_encode($data), '8bit')/1000).'KB';
	
	return $data;

}

include_once 'courses/sttv_course.class.php';
include_once 'courses/sttv_courses_admin.class.php';