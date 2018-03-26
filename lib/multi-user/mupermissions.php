<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class MultiUserPermissions {

    private $dictionary;

    private $current_key;

    private $keystore;

    private $user = false;

    public function __construct( $key = '', $id = 0 ) {
        if ( ( $key === '' || $key === 0 ) && $id === 0 ){
            return [
                'error'=>'invalid_params',
                'message'=>'You must pass a permission key or user ID'
            ];
        }

        $this->keystore = get_option( 'sttv_mu_permissions_keystore' ) ?: [];

        if ( is_numeric( $key ) ){
            $id = $key+0;
            $key = $this->get_key_by_id( $id );
        }

        if ( $id < 1 && array_key_exists( $key, $this->keystore ) ){
            $id = $this->keystore[$key];
        }

        $dict = get_option( 'sttv_crypto_dictionary' );
        if ( !$dict ){
            update_option( 'sttv_crypto_dictionary', $this->get_dictionary(), true );
            $dict = get_option( 'sttv_crypto_dictionary' );
        }
        $this->dictionary = $dict;

        return $this
            ->set_current_user( get_userdata( $id ) )
            ->set_current_key( $key );
    }

    public function keygen() {
        if ( !$this->user ){
            throw new Exception('You must pass a valid user ID or previous key to generate a new permission key');
        }
        if ( !isset($this->current_key) ){
            throw new Exception('The current key must be set before a new one can be generated. Please provide a key.');
        }
        unset( $this->keystore[$this->current_key] );

        return $this
            ->set_current_key( md5( $this->dictionary[rand(0,9999)] . $this->user->user_email . $this->user->ID ) )
            ->store_current_key()
            ->save();
    }

    public function usekey() {
        if ( !$this->validate_current_key() ) {
            return false;
        }

        return $this
            ->invalidate_key()
            ->save();
    }

    public function get_current_key() {
        return $this->current_key;
    }

    public function get_key_by_id( $id = 0 ) {
        if ( $id === 0 ){
            $id = $this->user->ID;
        }
        return array_search( $id, $this->keystore );
    }

    public function verify_mu_email( $email = '' ){
        return ( $email === $this->user->user_email );
    }

    private function set_current_key( $key ) {
        $this->current_key = $key;
        return $this;
    }

    private function set_current_user( $user ) {
        $this->user = $user;
        return $this;
    }

    private function store_current_key() {
        $this->keystore[$this->current_key] = $this->user->ID;
        return $this;
    }

    private function validate_current_key() {
        return ( array_key_exists( $this->current_key, $this->keystore ) && $this->keystore[$this->current_key] !== 0 );
    }

    private function invalidate_key() {
        $this->keystore[$this->current_key] = 0;
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