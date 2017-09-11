<?php

/* Template Name: logout page */
if (!defined('ABSPATH')) {die();}
$ll = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : home_url() ;
if (is_user_logged_in()) {
	wp_logout();
	wp_redirect($ll);
	exit;
}