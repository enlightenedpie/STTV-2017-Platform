<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * SupertutorTV Authorization / Login / Logout class.
 *
 * Properties, methods, and endpoints for loggin in and out of the Supertutortv app.
 *
 * @class 		STTV_Auth
 * @version		1.4.0
 * @package		STTV
 * @category	Class
 * @author		Supertutor Media, inc.
 */
class STTV_Auth extends WP_REST_Controller {

    public static function logout($cb){
        wp_logout();
        if (is_callable($cb)){$cb();}
    }
}