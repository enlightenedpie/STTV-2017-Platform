<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class MultiUserREST extends WP_REST_Controller {

    private $countrydd;

    private $price_table;

    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'mukey_endpoint' ] );

        $this->countrydd = get_option('sttv_country_options');
        if (!$this->countrydd) {
            $this->countrydd = wp_remote_get('https://gist.githubusercontent.com/enlightenedpie/888ba7972fa617579c374e951bd7eab9/raw/b987e55ddc4cde75f50298559e3a173a132657af/gistfile1.txt');
            update_option('sttv_country_options',$this->countrydd);
        }

        $this->price_table = get_option( 'sttv_mu_pricing_table' );
    }

    public function mukey_endpoint() {
        register_rest_route( STTV_REST_NAMESPACE , '/multi-user', [
            [
                'methods' => 'GET',
                'callback' => function( WP_REST_Request $request ){
                    $key = $request->get_param( 'key' );

                    return (new MultiUserPermissions( $key ))->keygen()->get_current_key();

                },
                'permission_callback' => function(){
                    return current_user_can( 'multi_user' );
                },
                'args' => [
                    'key' => [
                        'required' => true,
                        'type' => 'string',
                        'description' => 'Can be a mukey or user id'
                    ]
                ]
            ],
            [
                'methods' => 'POST',
                'callback' => [ $this, 'multi_user_verify' ],
                'permission_callback' => [ $this, 'multi_user_auth' ]
            ]
        ]);
    }

    public function multi_user_verify( WP_REST_Request $req ) {
        $params = json_decode($req->get_body(),true);
        if ( !isset( $params[ 'mukey' ] ) ) {
            return $this->mu_generic_response( 'null_key', 'You must provide an invitation code with this request.', 400 );
        }

        if ( !isset( $params[ 'email' ] ) ) {
            return $this->mu_generic_response( 'null_email', 'You must provide the email associated with the invitation code.', 400 );
        }

        $mup = new MultiUserPermissions( $params[ 'mukey' ] );

        if ( !$mup->verify_key( $params[ 'email' ] )->is_valid ) {
            return $this->mu_generic_response( 'invalid_key', 'The invitation code provided is invalid.', 403 );
        }

        //$usekey = $mup->usekey()->output;
        ob_start();

        sttv_get_template('checkout','checkout',[
            'countrydd' => $this->countrydd,
            'user' => $mup->get_current_user()
        ]);

        return $this->mu_generic_response(
            'success',
            'Permission granted.',
            200,
            [
                'data' => [
                    'id' => $mup->get_current_key(),
                    'type' => 'multi-user',
                    'price' => $this->price_table[ $params[ 'license' ][ 'id' ] ][ $params[ 'license' ][ 'qty' ] ],
                    'qty' => $params[ 'license' ][ 'qty' ],
                    'course_id' => $params[ 'license' ][ 'id' ],
                    'name' => $params[ 'license' ][ 'title' ],
                    'taxable' => false
                ],
                'html' => ob_get_clean()
            ]
        );
    }

    public function multi_user_auth( WP_REST_Request $req ) {
        return true;
        return wp_verify_nonce( $req->get_header('X-WP-Nonce'), STTV_REST_AUTH );
    }

    private function mu_generic_response( $code = '', $msg = '', $status = 200, $extra = [] ) {
        $data = [
            'code'    => $code,
            'message' => $msg,
            'data'    => [ 
                'status' => $status
            ]
        ];
        $data = array_merge($data, (array) $extra);
        return new WP_REST_Response( $data, $status );
    }

}
new MultiUserREST;