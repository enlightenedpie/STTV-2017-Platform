<?php 

// Form Submitter
add_action('wp_ajax_nopriv_sttvsubmitter','sttv_ajax_submitter');
add_action('wp_ajax_sttvsubmitter','sttv_ajax_submitter');

// GF token getter
add_action('wp_ajax_nopriv_gfkey','sttv_request_gf_api_key');
add_action('wp_ajax_gfkey','sttv_request_gf_api_key');