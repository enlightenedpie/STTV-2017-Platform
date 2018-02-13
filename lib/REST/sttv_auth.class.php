<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * SupertutorTV Authorization / Login / Logout class.
 *
 * Properties, methods, and endpoints for logging in and out of the Supertutortv app.
 *
 * @class 		STTV_Auth
 * @version		1.4.0
 * @package		STTV
 * @category	Class
 * @author		Supertutor Media, inc.
 */
class STTV_Auth extends WP_REST_Controller {

    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'register_auth_endpoints' ] );
    }

    public function register_auth_endpoints() {
        register_rest_route( STTV_REST_NAMESPACE , '/auth', [
            [
                'methods' => 'POST',
                'callback' => [ $this, 'sttv_auth_processor' ],
                'args' => [
                    'action' => [
                        'required' => true,
                        'type' => 'string',
                        'description' => 'The type of auth action requested',
                        'enum' => [
                            'login',
                            'logout'
                        ]
                    ]
                ]
            ]
        ]);
    }

    public function sttv_auth_processor( WP_REST_Request $request ) {
        $action = $request->get_param('action');
        $auth = $request->get_header('X-STTV-Auth');

        switch ($action) {
            case 'login':
                return $this->login($auth,site_url('/my-account'));
                break;

            case 'logout':
                return $this->logout(site_url());
                break;
        }
    }

    private function login( $auth, $redirect = '' ) {
        if ( null === $auth || empty($auth) ) {
            $data = [
				'code'    => 'auth_header',
				'message' => 'You must set the authentication header X-STTV-Auth',
				'data'    => [ 
					'status' => 401
				]
			];
			return new WP_REST_Response( $data, 401 );
        }

        $auth = explode( ':', base64_decode($auth) );

        $user = $auth[0];
        unset($auth[0]);
        $pw = implode( ':', $auth);

        $login = wp_signon([
            'user_login' => $user,
            'user_password' => $pw,
            'remember' => true
        ], true);

        if ( !is_wp_error( $login ) ){
            unset($login->data->user_pass,$login->data->user_activation_key);
            return [ 'data' => $login->data, 'redirect' => $redirect ];
        } else {
            return $login;
        }
    }

    private function logout( $redirect = '' ){
        wp_logout();
        return $redirect;
    }
}
new STTV_Auth;