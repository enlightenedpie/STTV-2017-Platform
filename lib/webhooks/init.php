<?php 

function sttv_webhooks(){
    if ( !isset( $_GET['shopify'] ) ){
        return false;
    }
    $obj = file_get_contents("php://input") ?: 'null';
    wp_die($obj);
}