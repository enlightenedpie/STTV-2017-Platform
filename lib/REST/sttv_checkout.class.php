<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * SupertutorTV checkout class.
 *
 * Properties, methods, and endpoints for the frontend checkout form to interact with.
 *
 * @class 		STTV_Checkout
 * @version		1.4.0
 * @package		STTV
 * @category	Class
 * @author		Supertutor Media, inc.
 */

class STTV_Checkout extends WP_REST_Controller {

    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'register_checkout_endpoints' ] );
    }

    public function register_checkout_endpoints() {
        register_rest_route( STTV_REST_NAMESPACE , '/checkout', [
            [
                'methods' => 'GET',
                'callback' => [ $this, 'sttv_parameter_checker' ],
                'permission_callback' => [ $this, 'checkout_origin_verify' ],
                'args' => [
                    'email' => [
                        'required' => false,
                        'type' => 'string',
                        'description' => 'Email to check'
                    ],
                    'coupon' => [
                        'required' => false,
                        'type' => 'string',
                        'description' => 'Coupon to check'
                    ],
                    'zip' => [
                        'required' => false,
                        'type' => 'string',
                        'description' => 'Postal code to check'
                    ]
                ]
            ],
            [
                'methods' => 'POST',
                'callback' => [ $this, 'sttv_checkout' ],
                'permission_callback' => [ $this, 'checkout_origin_verify' ],
            ]
        ]);
    }

    public function sttv_parameter_checker( WP_REST_Request $request ) {
        $pars = $request->get_params();

        if ( isset( $pars['email'] ) ) {
            return $this->check_email( sanitize_email($pars['email']) );
        } elseif ( isset( $pars['coupon'] ) ) {
            return $this->check_coupon( sanitize_text_field($pars['coupon']) );
        } elseif ( isset( $pars['zip'] ) ) {
            return $this->check_zip( sanitize_text_field($pars['zip']) );
        } else {
            return $this->checkout_generic_response( 'bad_request', 'Valid parameters are required to use this method/endpoint combination. Only one parameter is allowed per request, and parameters must have value.', 400 );
        }
    }

    public function sttv_checkout( WP_REST_Request $request ) {
        $body = json_decode($request->get_body(),true);
        if ( empty($body) ){
            return $this->checkout_generic_response( 'bad_request', 'Request body cannot be empty', 400 );
        }
        $body = sttv_array_map_recursive('sanitize_text_field',$body);
        return $body;
    }

    private function check_email( $email = '' ) {
        if ( !is_email( $email ) ) {
            return $this->checkout_generic_response( 'bad_request', 'Email cannot be empty or blank, and must be a valid email address.', 400 );
        }
        
        if ( wp_get_current_user()->user_email === $email ) {
            return $this->checkout_generic_response( 'email_current_user', 'Email address is the same as the currently logged in user', 200 );
        }

        if ( email_exists( $email ) ) {
            return $this->checkout_generic_response( 'email_taken', 'Email address is already in use', 200 );
        }
        
        return $this->checkout_generic_response( 'email_available', 'Email address available', 200 );
    }

    private function check_coupon( $coupon = '' ) {
        if ( empty( $coupon ) ) {
            return $this->checkout_generic_response( 'bad_request', 'Coupon cannot be empty or blank.', 400 );
        }
        try {
            $coupon = \Stripe\Coupon::retrieve( $coupon );
            if ( $coupon->valid ) {
                return $this->checkout_generic_response( 'coupon_valid', 'valid coupon', 200, [
                    'percent_off' => $coupon->percent_off,
                    'amount_off' => $coupon->amount_off,
                    'id' => $coupon->id
                ]);
            } else {
                return $this->checkout_generic_response( 'coupon_expired', 'expired coupon', 200 );
            }
        } catch (Exception $e) {
            $body = $e->getJsonBody();
            return $this->checkout_generic_response( 'coupon_invalid', 'invalid coupon', 200, [
                'error' => $body['error']
            ]);
        }
    }

    private function check_zip( $zip = '' ) {
        if ( empty( $zip ) ) {
            return $this->checkout_generic_response( 'bad_request', 'ZIP/Postal code cannot be empty or blank.', 400 );
        }
        $ca_zips = json_decode(file_get_contents('https://gist.githubusercontent.com/enlightenedpie/99139b054dd9e4ad3f81689e2326d198/raw/69b654b47a01d2dc9e9ac34816c05ab5aa9ad355/ca_zips.json'));
        $tax = 0;
        if ( in_array( $zip, $ca_zips->losangeles ) ) {
            $tax = 9.5;
        } else {
            foreach ($ca_zips as $array) {
                if ( in_array( $zip, $array ) ) {
                    $tax = 7.5;
                    break;
                }
            }
        }
        return $this->checkout_generic_response( 'checkout_tax', 'Tax percentage based on ZIP/Postal code', 200, [ 'tax' => $tax ] );
    }

    public function checkout_origin_verify( WP_REST_Request $request ) {
        return true;
        return !!wp_verify_nonce( $request->get_header('X-WP-Nonce'), STTV_REST_AUTH );
    }

    private function checkout_generic_response( $code = '', $msg = '', $status = 200, $extra = [] ) {
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
new STTV_Checkout;