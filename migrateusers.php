<?php
/* Template Name: Migration */

// exit if accessed directly
defined( 'ABSPATH' ) || exit;

// exit if not admin
current_user_can('manage_options') || exit;

$users = get_users([ 'role__in' => [ 'the_best_act_prep_course_ever' ], 'fields' => 'all_with_meta' ]);

$data = serialize($users[0]);

$response = wp_remote_post('https://dev.api.supertutortv.com/v2/migrate/users',[
    'headers' => [
        'Content-Type' => 'text/plain',
        'STTVWHSEC' => hash_hmac( 'sha256', $data, STTV_WHSEC )
    ],
    'body' => $data
]);

echo '<pre>';
print_r( $response['response'] );
echo '</pre>';