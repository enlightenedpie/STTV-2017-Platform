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

    private $zips = [];

    private $tax = 0;

    const BOOK_PRICE = 2500;

    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'register_checkout_endpoints' ] );

        $this->zips = json_decode(file_get_contents('https://gist.githubusercontent.com/enlightenedpie/99139b054dd9e4ad3f81689e2326d198/raw/69b654b47a01d2dc9e9ac34816c05ab5aa9ad355/ca_zips.json'));
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
                    ],
                    'uid' => [
                        'required' => false,
                        'type' => 'string',
                        'description' => 'Request a generated unique id'
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
        } elseif ( isset( $pars['sid'] ) ) {
            return (new MultiUserPermissions('1c74e69ef1f4f0388bc6da713a599142'))->roll_key()->get_current_key();
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

        if ( isset($body['muid']) ) {
            return $this->_mu_checkout( $body );
        }

        return $this->_checkout( $body );
    }

    private function _mu_checkout( $body ) {
        $mu = new MultiUser;

        $key = $mu->validate_key( $body['muid'] );

        if ( false === $key ) {
            return $this->checkout_generic_response(
                'invalid_multi-user_token',
                'This multi-user token is invalid or expired. Please contact purchaser of this token if you have reached this in error.',
                403
            );
        }

        return $this->checkout_generic_response(
            'subscription_success',
            'Thank you for subscribing! Your order details are below. Check your email for instructions on setting up your account',
            200,
            $mu->activate_key( 1 )
        );
    }

    private function _checkout( $body ){
        
        // set up "customer"
        $customer = null;
        //$customerID = (is_user_logged_in()) ? get_user_meta(get_current_user_id(),'stripe_cus_ID',true) : false;
        $customerID = false;

        //set tax rate based on postal code
        if (in_array($body['shipping']['postal_code'],$this->zips->losangeles)) {
            $this->tax = 9.5;
        } else {
            foreach ($this->zips as $array) {
                if (in_array($body['shipping']['postal_code'],$array)) {
                    $this->tax = 7.5;
                    break;
                }
            }
        }

        // run the Stripe API calls and output any errors
        try {
            if (!$customerID) {
                $customer = \Stripe\Customer::create(
                    [
                        "description" => ucwords(strtolower($body['first_name'].' '.$body['last_name'])),
                        "source" => $body['token']['id'],
                        "email" => $body['email'],
                        "coupon" => $body['coupon'],
                        "shipping" => [
                            "name" => "shipping",
                            "address" => $body['shipping']
                        ]
                    ]
                );
                $customerID = $customer->id;
            } else {
                $customer = \Stripe\Customer::retrieve($customerID);
                $customer->description = $body['first_name'].' '.$body['last_name'];
                $customer->source = $body['token']['id'];
                $customer->save();
            }

            foreach( $body['items'] as $item ) {
                if ( $item['priority'] ) {
                    \Stripe\InvoiceItem::create(
                        [
                            "customer" => $customerID,
                            "amount" => 1285,
                            "currency" => "usd",
                            "description" => "Priority Shipping",
                            "discountable" => false
                        ]
                    );
                }

                if ( $this->tax ) {
                    \Stripe\InvoiceItem::create(
                        [
                            "customer" => $customerID,
                            "amount" => round(self::BOOK_PRICE*($this->tax/100)),
                            "currency" => "usd",
                            "description" => "Sales tax",
                            "discountable" => false
                        ]
                    );
                }

                $sub = \Stripe\Subscription::create(
                    [
                        'customer' => $customerID,
                        'plan' => $item['id'],
                    ]
                );
                if ( !$item['trialing'] ){
                    $sub->trial_end = 'now';
                    $sub->save();
                }
			    $sub->cancel(['at_period_end' => true]);

            }

            return $this->checkout_generic_response(
                'subscription_success',
                'Thank you for subscribing! Your order details are below. Check your email for instructions on setting up your account',
                200,
                $customer
            );

        } catch(\Stripe\Error\Card $e) {
            // card declined
            $body = $e->getJsonBody();
            $err  = $body['error'];

            return $this->checkout_generic_response(
                'card_declined',
                'Your card was declined. Please try a different payment method.',
                402,
                [ 'error' => $err ]
            );
  
          } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
            $body = $e->getJsonBody();
            $err  = $body['error'];
  
            wp_mail( get_bloginfo('admin_email'), 'Stripe API error: Rate Limit', json_encode($err));
  
            return $this->checkout_generic_response(
                'rate_limit',
                'There was a server issue and you have not been charged. Please try again.',
                429,
                [ 'error' => $err ]
            );
  
          } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
            $body = $e->getJsonBody();
            $err  = $body['error'];
  
            wp_mail( get_bloginfo('admin_email'), 'Stripe API error: Invalid Parameters', json_encode($err));
  
            return $this->checkout_generic_response(
                'invalid_request_error',
                'An invalid request was made. You have not been charged. Please reload the page and try again.',
                400,
                [ 'error' => $err ]
            );
  
          } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            $body = $e->getJsonBody();
            $err  = $body['error'];
  
            wp_mail( get_bloginfo('admin_email'), 'Stripe API error: Authentication Failure', json_encode($err) );
            
            return $this->checkout_generic_response(
                'auth_fail',
                'The server could not connect with the payment processor. You have not been charged. Please try again.',
                401,
                [ 'error' => $err ]
            );
  
          } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
            $body = $e->getJsonBody();
            $err  = $body['error'];
  
            wp_mail( get_bloginfo('admin_email'), 'Stripe API error: Connection Failure', json_encode($err) );
  
            return $this->checkout_generic_response(
                'api_comm',
                'The server could not connect with the payment processor. You have not been charged. Please try again.',
                408,
                [ 'error' => $err ]
            );
  
          } catch (\Stripe\Error\Base $e) {
  
            $body = $e->getJsonBody();
            $err  = $body['error'];
  
            wp_mail(get_bloginfo( 'admin_email'), 'Stripe API error: Generic Error', json_encode($err) );

            return $this->checkout_generic_response(
                'generic',
                'We apologize, something went wrong on our end. You have not been charged. Please try again later.',
                500,
                [ 'error' => $err ]
            );
  
          } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
  
            wp_mail( get_bloginfo('admin_email'), 'Generic Error', json_encode($e) );
  
            return $this->checkout_generic_response(
                'generic',
                'We apologize, something went wrong on our end. You have not been charged. Please try again later.',
                500,
                [ 'error' => $e ]
            );
          }
          // END TRY/CATCH
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

        if ( in_array( $zip, $this->zips->losangeles ) ) {
            $tax = 9.5;
        } else {
            foreach ($this->zips as $array) {
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