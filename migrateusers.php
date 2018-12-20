<?php
/* Template Name: Migration */

// exit if accessed directly
defined( 'ABSPATH' ) || exit;

// exit if not admin
current_user_can('manage_options') || exit;

$users = get_users([ 'role__in' => [ 'the_best_act_prep_course_ever' ] ]);

print_r($users);