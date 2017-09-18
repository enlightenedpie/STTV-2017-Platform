<?php
/**
 * SupertutorTV product page reviews.
 *
 * REST endpoints for adding, displaying, and updating product reviews.
 *
 * @class 		STTV_Product_Reviews
 * @version		1.1.0
 * @package		STTV
 * @category	Class
 * @author		Supertutor Media, inc.
 */

class STTV_Product_Reviews extends WP_REST_Controller {
	
	public function __construct() {
		add_action( 'rest_api_init', array($this,'sttv_product_reviews_api') );
	}
	
	public function sttv_product_reviews_api() {
 		register_rest_route( STTV_REST_NAMESPACE, '/reviews/(?P<id>[\d]+)', 
			array(
				array(
					'methods' => 'GET',
					'callback' => array($this,'get_product_reviews'),
					'args' => array(
						'id' => array(
							'validate_callback' => 'absint',
							'required' => true
						)
					)
				),
				array(
					'methods' => 'PUT',
					'callback' => array($this,'post_product_review'),
					'permission_callback' => array($this,'can_post_reviews'),
					'args' => array(
						'id' => array(
							'validate_callback' => 'absint',
							'required' => true
						)
					)
				),
				array(
					'methods' => 'POST',
					'callback' => array($this,'review_exists')
				)
			)
		);
	} // end sttv_product_reviews_api
	
	public function get_product_reviews($data) {
		$comments = get_comments(array('post_id'=>$data['id'],'status'=>'approve'));
		return $comments;
	}
	
	public function post_product_review(WP_REST_Request $request) {
		$body = json_decode($request->get_body());
		
		$user = get_user_by('id', $body->user_id);
		$full_name = explode(' ',$user->display_name);
		$name = $full_name[0].' '.substr($full_name[1],0,1).'.';
		
		$args = array(
			'comment_post_ID'=>$body->post,
			'comment_approved'=>0,
			'comment_karma'=>$body->rating,
			'comment_content'=>$body->comment_content,
			'comment_agent'=>$body->UA,
			'comment_author'=>$name,
			'comment_author_IP'=>$_SERVER['REMOTE_ADDR'],
			'comment_author_email'=>$user->user_email,
			'user_id'=>$user->ID
		);
		$comment = wp_insert_comment($args);
		
		return rest_ensure_response($comment);
	}
	
	public function can_post_reviews() {
		return current_user_can( 'course_post_reviews' );
	}
	
	public function review_exists($d) {
		$body = json_decode($d->get_body());
		return !!get_comments(array('post_id'=>$body->post,'user_id'=>$body->user_id,'count'=>true));
	}
	
}
new STTV_Product_Reviews;