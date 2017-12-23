<?php 

if ( ! defined( 'ABSPATH' ) ) {exit;}

use STTV\REST\Limiter;

require( __DIR__ . '/limiter/Limiter.php' );

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