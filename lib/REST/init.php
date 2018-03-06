<?php 

if ( ! defined( 'ABSPATH' ) ) {exit;}

use STTV\REST\Limiter;

require( __DIR__ . '/limiter/Limiter.php' );

remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );

function sttv_rest_auth() {
	if ( has_filter( 'rest_nonce_action' ) ) {
		$auth = 'sttv:rest:auth';
		add_filter( 'rest_nonce_action', function() {
			return $auth;
		});
		return $auth;
	}
	return 'wp_rest';
}
define('STTV_REST_AUTH', sttv_rest_auth());

function sttvlimiter() {
	static $instance;
	if ( null === $instance ) {
		$instance = new Limiter();
	}
	return $instance;
}
$sttvlimiter = sttvlimiter();

add_action( 'rest_api_init', function() use ( $sttvlimiter ) {
	$sttvlimiter->load();
}, 5 );

require('sttv_feedback.class.php');
require('sttv_product_reviews.class.php');
require('sttv_test_dates.class.php');
require('sttv_jobs.class.php');
require('sttv_forms.class.php');
require('sttv_checkout.class.php');