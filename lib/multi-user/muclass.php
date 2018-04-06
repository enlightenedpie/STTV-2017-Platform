<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class MultiUser {

    const MIN_KEYS = 3;

    private $keys;

    private $created_keys = [];

    private $tokens = [];

    private $token = '';

    private $current_key;

    private $start_time;

    private $root_user_id;

    private $course_id;

    private $autosave;

    public function __construct( $user_id = 0, $course_id = 0, $autosave = true ) {
        $this->autosave = $autosave;
        $this->keys = json_decode( @file_get_contents( MU_FILE_PATH ) ?: '[]', true );
        $this->root_user_id = $user_id;
        $this->course_id = $course_id;
        $this->start_time = time();
        return $this;
    }

    public static function pricing( $stuff = '[]' ){
        $stuff = json_decode( $stuff );
        foreach ( $stuff as $k => $v ){
            $stuff = $k;
        }
        return $stuff;
    }

    public function keygen( $qty = 0 ) {
        if ( $qty < self::MIN_KEYS ){
            return null;
        }

        $prefix = array_merge(range('A','Z'),range('a','z'));
        array_unshift($prefix,'\t');
        
        for ($i = 1; $i <= $qty;) {
            $key = sttv_uid( $prefix[date('n')].$prefix[date('y')].'-', openssl_random_pseudo_bytes( 32 ), true, 32 );
            if ( array_key_exists( $key, $this->keys ) || array_key_exists( $key, $this->created_keys ) ){
                continue;
            }
            
            $this->tokens[] = $key;

            $this->created_keys[$key] = [
                'created' => $this->start_time,
                'activated' => null,
                'expires' => strtotime( '+1 year', $this->start_time ),
                'root_user' => $this->root_user_id,
                'active_user' => 0,
                'course_id' => $this->course_id,
                'valid' => true
            ];

            $i++;
        }

        if ($this->autosave) {
            $this->add();
        }

        return $this->get_tokens();
    }

    public function activate_key( $user_id = 0 ) {
        return $this->set_active_user( $user_id )
            ->set_activated_time()
            ->invalidate_key()
            ->update( $this->current_key )
            ->current_key;
    }

    public function validate_key( $key = '' ){
        if ( !array_key_exists( $key, $this->keys ) ){
            return false;
        }

        $this->set_current_key( $key );

        if ( $this->current_key[$key]['valid'] && $this->current_key[$key]['expires'] < time() ) {
            $this->invalidate_key( $key );
            $this->update( $this->current_key );
        }

        if ( !$this->current_key[$key]['valid'] ) {
            return false;
        }

        return $key;
    }

    public function get_tokens() {
        return $this->tokens;
    }

    public function get_current_key() {
        return $this->current_key;
    }

    private function set_current_key( $key ) {
        $this->token = $key;
        $this->current_key = [
            $key => $this->keys[$key]
        ];
        return $this;
    }

    private function invalidate_key(){
        $this->current_key[$this->token]['valid'] = false;
        return $this;
    }

    private function set_active_user( $active_user = 0 ){
        $this->current_key[$this->token]['active_user'] = $active_user;
        return $this;
    }

    private function set_activated_time(){
        $this->current_key[$this->token]['activated'] = time();
        return $this;
    }

    private function add() {
        file_put_contents( MU_FILE_PATH, json_encode( $this->created_keys ), FILE_APPEND | LOCK_EX );
        return $this;
    }

    private function update( $update = [] ) {
        file_put_contents( MU_FILE_PATH, json_encode( array_merge( $this->keys, $update ) ), LOCK_EX );
        return $this;
    }

    private function delete( $key = '' ){
        if ( 0 === $key || 'all' === $key ){
            $this->keys = [];
        } else {
            unset( $this->keys[$key] );
        }
        return $this->update();
    }
}