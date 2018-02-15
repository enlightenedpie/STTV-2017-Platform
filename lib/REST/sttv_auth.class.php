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
                        'description' => 'The type of auth action requested'
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

            case 'logout':
                return $this->logout(site_url());

            default:
                return $this->custom_response( 'action_invalid', 'The action parameter was invalid. Check documentation for allowed actions.', 400 );
        }
    }

    private function login( $auth, $redirect = '' ) {
        if ( null === $auth || empty($auth) ) {
            return $this->custom_response( 'auth_header', 'You must set the authentication header X-STTV-Auth', 401 );
        }

        $auth = explode( ':', base64_decode($auth) );

        // username validation
        $user = sanitize_user($auth[0]);
        if (!validate_username($user)){
            return $this->custom_response( 'login_fail', 'The username or password you entered is incorrect', 401 );
        }

        unset($auth[0]);
        $pw = implode( ':', $auth);

        $login = wp_signon([
            'user_login' => $user,
            'user_password' => $pw,
            'remember' => true
        ], true);

        if ( !is_wp_error( $login ) ){
            unset($login->data->user_pass,$login->data->user_activation_key);
            return $this->custom_response( 'login_success', 'Login successful! Redirecting...', [ 'data' => $login->data, 'redirect' => $redirect ], 200 );
        } else {
            return $this->custom_response( 'login_fail', '<strong>ERROR: </strong>The username or password you entered is incorrect', $login, 401 );
        }
    }

    private function logout( $redirect = '' ){
        wp_logout();
        return $redirect;
    }

    private function custom_response( $code = '', $msg = '', $data = [], $status = 200 ) {
        if ( !is_object($data) && intval($data) > 99 ) {
            $status = $data;
            $data = [ 'status' => $status ];
        }

        $d = [
            'code'    => $code,
            'message' => $msg,
            'data'    => $data
        ];
        return new WP_REST_Response( $d, $status );
    }
}
new STTV_Auth;