<?php

/* Template Name: logout page */
if (!defined('ABSPATH')) {die();}

STTV_Auth::logout(function(){
	wp_redirect(home_url());
	exit;
});