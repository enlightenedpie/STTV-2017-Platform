<?php 
$results;
for ($i = 0; $i < 100; $i++) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'localhost:8888/sttvroot/api/v1.3/test_dates/act');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);

    $results[] = $result;
}
print_r( $results );