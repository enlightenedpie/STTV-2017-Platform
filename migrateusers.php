<?php
/* Template Name: Migration */

// exit if accessed directly
defined( 'ABSPATH' ) || exit;

// exit if not admin
current_user_can('manage_options') || exit;

$users = get_users([
    'role__in' => [ 'the_best_act_prep_course_ever' ]
]);

$data = serialize($users[0]);

/* $response = wp_remote_post('https://dev.api.supertutortv.com/v2/migrate/users',[
    'headers' => [
        'Content-Type' => 'text/plain',
        'STTVWHSEC' => hash_hmac( 'sha256', $data, STTV_WHSEC )
    ],
    'body' => $data
]); */

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://dev.api.supertutortv.com/v2/migrate/users',
    CURLOPT_USERAGENT => 'STTV Main Site Migration',
    CURLOPT_HTTPHEADER => [
        'Content-Type: text/plain',
        'STTVWHSEC: ' . hash_hmac( 'sha256', $data, STTV_WHSEC )
    ],
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => $data
));

$resp = curl_exec($curl);

curl_close($curl);

echo '<pre>';
print_r( json_decode($resp) );
echo '</pre>';