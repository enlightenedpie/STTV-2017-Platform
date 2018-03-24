<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class MultiUserPermissions {

    private $dictionary;

    private $current_key;

    private $keystore;

    private $user = false;

    public function __construct( $key = false, $id = 0 ) {
        $dict = get_option( 'sttv_crypto_dictionary' );

        if ( !$dict ){
            update_option( 'sttv_crypto_dictionary', $this->get_dictionary(), true );
            $dict = get_option( 'sttv_crypto_dictionary' );
        }

        $this->dictionary = $dict;

        $this->keystore = get_option( 'sttv_mu_permissions_keystore' ) ?: [];

        if ( $key ){
            if ( $this->keystore[$key] === 0 ){
                return false;
            }
            $this->set_current_key( $key );
        }

        if ( 1 > $id && array_key_exists( $key, $this->keystore ) ){
            $id = $this->keystore[$key];
        }
        $this->user = get_userdata( $id );

        return $this;
    }

    public function keygen() {
        if ( !$this->user ){
            throw new Exception('You must pass a valid user ID or previous key to generate a new permission key');
        }

        $this
            ->set_current_key( md5( $this->dictionary[rand(0,9999)] . $this->user->user_email . $this->user->ID ) )
            ->store_current_key()
            ->save();

        return $this;
    }

    public function roll_key() {
        if ( !isset($this->current_key) ){
            throw new Exception('The current key must be set before a new one can be generated');
        }
        unset( $this->keystore[$this->current_key] );

        $this->keygen();
        return $this;
    }

    public function use_key() {
        $this->keystore[$this->current_key] = 0;
        return $this->save();
    }

    public function get_current_key() {
        return $this->current_key;
    }

    private function set_current_key( $key ){
        $this->current_key = $key;
        return $this;
    }

    private function store_current_key() {
        $this->keystore[$this->current_key] = $this->user->ID;
        return $this;
    }

    private function save() {
        update_option( 'sttv_mu_permissions_keystore', $this->keystore, true );
        return $this;
    }

    private function get_dictionary() {
        return file('https://raw.githubusercontent.com/first20hours/google-10000-english/master/google-10000-english-usa.txt',FILE_IGNORE_NEW_LINES);
    }
}