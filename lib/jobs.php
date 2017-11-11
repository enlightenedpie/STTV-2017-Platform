<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * SupertutorTV job board.
 *
 * All operations, including custom routes, template redirection, and REST endpoints for the SupertutorTV job board.
 *
 * @class 		STTV_Jobs
 * @version		1.3.0
 * @package		STTV
 * @category	Class
 * @author		Supertutor Media, inc.
 */

 class STTV_Jobs {

    public function __construct() {
        add_filter( 'query_vars', array($this, 'sttv_jobs_qvar') );
        add_action( 'init', array($this, 'sttv_jobs_endpoint') );
    }

    public function sttv_jobs_qvar($vars) {
        $vars[] = 'job-post';
        return $vars;
    }

    public function sttv_jobs_endpoint() {
        add_rewrite_rule('^jobs/(.*)?$','index.php?pagename=jobs&job-post=$matches[1]','top' );
    }

 }
 new STTV_Jobs;