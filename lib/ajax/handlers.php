<?php 

// Form Submitter
add_action('stajax_nopriv_sttvsubmitter','sttv_ajax_submitter');
add_action('stajax_sttvsubmitter','sttv_ajax_submitter');

// GF token getter
add_action('wp_ajax_nopriv_gfkey','sttv_request_gf_api_key');
add_action('wp_ajax_gfkey','sttv_request_gf_api_key');

//Signup Form
add_action('stajax_nopriv_sttvajax_signup',array('SP_Sub','create'));
add_action('stajax_sttvajax_signup',array('SP_Sub','create'));

// username check
add_action('stajax_nopriv_sttv_email','sttv_check_email');
add_action('stajax_sttv_email','sttv_check_email');

// check ZIP
add_action('stajax_nopriv_sttv_billing_pcode','sttv_check_zip');
add_action('stajax_sttv_billing_pcode','sttv_check_zip');

// check coupon
add_action('stajax_nopriv_sttv_coupon','sttv_check_coupon');
add_action('stajax_sttv_coupon','sttv_check_coupon');

// courses resource download
add_action('stajax_nopriv_courses_res_download','courses_res_download');
add_action('stajax_courses_res_download','courses_res_download');

add_action('stajax_nopriv_sp_get_all_subs','sp_get_all_subs');
add_action('stajax_sp_get_all_subs','sp_get_all_subs');