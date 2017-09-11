<?php

$h = explode('/', $_SERVER['DOCUMENT_ROOT']);
$root_path = '/'.$h[1].'/'.$h[2].'/vim/courses/';

$q = get_query_var('dump');

$path = $root_path.$q.'.json';
$invalid = array('error'=>'This is a badly formed request. Please Try again.');
$response = json_decode(file_get_contents($path));


if (!file_exists($path)) :
	echo wp_send_json($invalid,404);
else :
	echo wp_send_json($response,200);
endif;