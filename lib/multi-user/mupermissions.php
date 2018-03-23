<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class MultiUserPermissions {

    private $words = [];

    private $current_key;

    public function __construct() {
        $this->words = file('https://raw.githubusercontent.com/first20hours/google-10000-english/master/google-10000-english-usa.txt',FILE_IGNORE_NEW_LINES);
    }

    public function keygen() {
        return md5( $this->words[rand(0,9999)] . $this->words[rand(0,9999)] );
    }

}